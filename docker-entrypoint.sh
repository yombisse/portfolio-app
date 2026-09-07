#!/bin/sh

set -e

cd /var/www/html

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    attempts=0

    until php artisan migrate --force; do
        attempts=$((attempts + 1))

        if [ "$attempts" -ge 10 ]; then
            echo "Unable to connect to the database after ${attempts} attempts." >&2
            exit 1
        fi

        echo "Database is not ready. Retrying in 5 seconds..." >&2
        sleep 5
    done
fi

if [ "${RUN_SEEDERS:-true}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground