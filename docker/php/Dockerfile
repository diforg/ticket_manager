FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    zip \
    unzip \
    linux-headers \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    bcmath \
    pcntl \
    intl \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
