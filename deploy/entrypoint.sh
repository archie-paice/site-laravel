#!/bin/sh
set -e

echo "Caching configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

echo "Starting Laravel HTTP server on 0.0.0.0:8080"
exec php artisan serve --host=0.0.0.0 --port=8080
