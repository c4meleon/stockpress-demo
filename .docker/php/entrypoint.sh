#!/bin/bash

set -e

function wait_for_db() {
    echo "Waiting for database to be ready..."
    while ! nc -z stockpress-db 3306; do
        sleep 1
    done
    echo "Database is ready."
}

function file_exists() {
    [ -f "$1" ] || [ -d "$1" ]
}

if file_exists "composer.lock"; then
    echo "composer.lock exists, checking dependencies..."
    if ! file_exists "vendor"; then
        echo "No vendor directory, installing dependencies..."
        composer install --no-dev --optimize-autoloader
    else
        echo "Vendor directory already exists."
    fi
else
    echo "No composer.lock file, installing dependencies from composer.json..."
    composer install --no-dev --optimize-autoloader
fi

wait_for_db

echo "Running database migrations..."
php artisan migrate --force

echo "Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching application configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking storage..."
php artisan storage:link

exec "$@"