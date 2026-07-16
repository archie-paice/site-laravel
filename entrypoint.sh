#!/usr/bin/env sh
set -eu

cd /var/www/html

echo "Preparing Laravel runtime directories..."

safe_mkdir() {
  path="$1"

  if [ -e "$path" ] && [ ! -d "$path" ]; then
    echo "Error: $path exists but is not a directory." >&2
    exit 1
  fi

  mkdir -p "$path"
}

safe_mkdir storage
safe_mkdir storage/app
safe_mkdir storage/app/public
safe_mkdir storage/framework
safe_mkdir storage/framework/cache
safe_mkdir storage/framework/cache/data
safe_mkdir storage/framework/sessions
safe_mkdir storage/framework/testing
safe_mkdir storage/framework/views
safe_mkdir storage/logs
safe_mkdir bootstrap/cache

# Only adjust ownership if the container is running as root.
# This avoids failing in restricted environments.
if [ "$(id -u)" = "0" ]; then
  chown -R www-data:www-data storage bootstrap/cache
fi

# Make directories writable by the owner/group without opening them globally.
chmod -R ug+rwX storage bootstrap/cache

# storage:link is idempotent-ish, but can fail if the link already exists.
# So guard it instead of blindly forcing it.
if [ ! -L public/storage ]; then
  if [ -e public/storage ]; then
    echo "Warning: public/storage exists but is not a symlink. Leaving it untouched." >&2
  else
    php artisan storage:link
  fi
fi

# Do not run migrations here. Migrator container owns that.
if [ "$#" -eq 0 ] || { [ "$1" = "php" ] && [ "$2" = "artisan" ]; }; then
  if [ "${LARAVEL_OPTIMIZE:-true}" = "true" ]; then
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
  fi
fi

echo "Laravel runtime directories ready."

if [ "$#" -eq 0 ]; then
  echo "Starting Laravel HTTP server on 0.0.0.0:8080"
  exec php artisan serve --host=0.0.0.0 --port=8080
fi

exec "$@"