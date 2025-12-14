echo "Caching configuration..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

echo "Starting PHP-FPM..."
exec php-fpm
