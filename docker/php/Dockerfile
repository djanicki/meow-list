FROM php:8.5-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    acl \
    file \
    gettext \
    git \
    bind-tools \
    icu-dev \
    libzip-dev \
    postgresql-dev

# Install PHP extensions
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install zip
# RUN docker-php-ext-install opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP
# You can mount a custom php.ini here or use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/conf.d/symfony.ini $PHP_INI_DIR/conf.d/symfony.ini

WORKDIR /app
