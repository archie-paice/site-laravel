FROM php:8.3-fpm-alpine

# Install build dependencies, configure and install PHP extensions, then remove build deps
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libzip-dev postgresql-dev; \
    apk add --no-cache libzip postgresql-libs; \
    docker-php-ext-configure zip --with-libzip; \
    docker-php-ext-install zip pdo pdo_pgsql; \
    apk del .build-deps