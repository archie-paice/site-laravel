# 1. Load Secrets (The "Whole-File" Pattern)
# If the secret file exists, export its contents as environment variables
if [ -f /run/secrets/app_config ]; then
    # Read line by line, ignoring comments and empty lines
    while IFS='=' read -r key value; do
        if [ -n "$key" ] && [ "${key#\#}" = "$key" ]; then
            export "$key=$value"
        fi
    done < /run/secrets/app_config
fi
exec "$@"

php artisan config:clear

# 2. Production Optimizations
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Run Database Migrations (Force is required in production)
echo "Running migrations..."
php artisan migrate --force

# 4. Start PHP-FPM (The main process)
echo "Starting PHP-FPM..."
exec php-fpm
