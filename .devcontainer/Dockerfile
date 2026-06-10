FROM mcr.microsoft.com/devcontainers/php:1-8.4-bookworm

# Some upstream devcontainer variants include a Yarn apt source without a valid key.
# Remove it to keep apt update deterministic.
RUN find /etc/apt/sources.list.d -maxdepth 1 -type f -iname "*yarn*" -delete

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev postgresql-client nodejs npm libzip-dev zip \
    && docker-php-ext-install pdo_pgsql pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

USER vscode
