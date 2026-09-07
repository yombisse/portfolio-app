
# ============================================================
# 1. Installation des dépendances PHP
# ============================================================
FROM composer:2 AS vendor

WORKDIR /app

# Copier tout le projet pour que Laravel puisse exécuter
# artisan pendant composer install
COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader


# ============================================================
# 2. Build des assets Vite / Tailwind
# ============================================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY public ./public

COPY tailwind.config.js postcss.config.js vite.config.js ./

RUN npm run build


# ============================================================
# 3. Image finale PHP + Apache
# ============================================================
FROM php:8.4-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Installation des extensions nécessaires à Laravel
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        pdo_pgsql \
        pcntl \
        zip \
    && a2enmod rewrite \
    && sed -ri \
        -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*


# ============================================================
# 4. Configuration du répertoire de travail
# ============================================================
WORKDIR /var/www/html


# ============================================================
# 5. Copier le projet Laravel
# ============================================================
COPY --from=vendor /app ./


# ============================================================
# 6. Copier les assets compilés
# ============================================================
COPY --from=assets /app/public/build ./public/build


# ============================================================
# 7. Préparer les répertoires Laravel
# ============================================================
RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R ug+rwX \
        storage \
        bootstrap/cache


# ============================================================
# 8. Port HTTP
# ============================================================
EXPOSE 80


# ============================================================
# 9. Démarrage Apache
# ============================================================
CMD ["apache2-foreground"]
