#!/bin/sh
set -e

# Ensure writable storage and cache even when volumes reset ownership
mkdir -p storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R u+rwX,g+rwX storage bootstrap/cache

echo "Caching configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan key:generate
php artisan storage:link

echo "Starting PHP-FPM..."
exec php-fpm
