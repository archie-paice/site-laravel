# 2. Production Optimizations
echo "Caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Start PHP-FPM (The main process)
echo "Starting PHP-FPM..."
exec php-fpm

exec "$@"
