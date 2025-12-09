FROM mcr.microsoft.com/devcontainers/php:1-8.4-bullseye

# Install the system dependency (libzip-dev) and the PHP extension (zip)
RUN sudo apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && sudo apt-get install -y libzip-dev libpq-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_pgsql