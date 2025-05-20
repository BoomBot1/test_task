FROM php:8.4-fpm

# Arguments defined in docker-compose
ARG app_mode
ARG xdebug_enabled

USER root

COPY "./deploy/fpm/conf.d/php.ini-$app_mode" "$PHP_INI_DIR/php.ini"
COPY "./deploy/fpm/conf.d/www.conf-$app_mode" "/usr/local/etc/php-fpm.d/www.conf"
COPY "./deploy/fpm/conf.d/xdebug.ini" "/tmp/conf.d/xdebug.ini"

# Install XDebug only in local development mode
RUN if [ "$xdebug_enabled" = "true" ]; then \
        cp /tmp/conf.d/xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini && \
        pecl install xdebug && \
        docker-php-ext-enable xdebug; \
    fi

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

USER www-data
