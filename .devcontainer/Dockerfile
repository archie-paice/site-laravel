FROM php:8.3-fpm-alpine

# Install bash and other required tools
RUN apk add --no-cache bash curl git unzip nodejs npm

# Install build dependencies, configure and install PHP extensions, then remove build deps
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libzip-dev postgresql-dev; \
    apk add --no-cache libzip postgresql-libs; \
    docker-php-ext-install zip pdo pdo_pgsql pcntl; \
    apk del .build-deps

# Install Composer (with installer signature verification)
RUN set -eux; \
    curl -fsSL https://composer.github.io/installer.sig -o /tmp/composer.sig; \
    curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php; \
    php -r "if (hash_file('sha384', '/tmp/composer-setup.php') !== file_get_contents('/tmp/composer.sig')) { echo 'Invalid Composer installer'; exit(1); }"; \
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer; \
    rm /tmp/composer-setup.php /tmp/composer.sig