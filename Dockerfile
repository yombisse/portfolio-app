
# ============================================================
# 1. Installation des dépendances PHP avec Composer
# ============================================================
FROM composer:2 AS vendor

WORKDIR /app

# Copier tout le projet
# Nécessaire car Composer exécute :
# php artisan package:discover
COPY . .

# Installation des dépendances PHP
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader


# ============================================================
# 2. Compilation des assets Vite / Tailwind
# ============================================================
FROM node:22-alpine AS assets

WORKDIR /app

# Copier les fichiers npm
COPY package.json package-lock.json ./

# Installer les dépendances Node
RUN npm ci

# Copier les ressources nécessaires au build
COPY resources ./resources
COPY public ./public

# Configuration Vite / Tailwind
COPY tailwind.config.js postcss.config.js vite.config.js ./

# Compiler les assets
RUN npm run build


# ============================================================
# 3. Image finale : PHP 8.4 + Apache
# ============================================================
FROM php:8.4-apache

# Document root Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# ============================================================
# Installation des dépendances système et extensions PHP
# ============================================================
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
# 4. Répertoire de travail Laravel
# ============================================================
WORKDIR /var/www/html


# ============================================================
# 5. Copier l'application Laravel
# ============================================================
COPY --from=vendor /app ./


# ============================================================
# 6. Copier les assets compilés par Vite
# ============================================================
COPY --from=assets /app/public/build ./public/build

# Démarrage : migrations, seeders et serveur Apache
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh


# ============================================================
# 7. Préparer les répertoires nécessaires à Laravel
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
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
