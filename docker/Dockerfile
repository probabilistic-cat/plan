FROM php:8.5-fpm-trixie AS base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip curl libzip-dev libonig-dev libicu-dev libpq-dev \
    && docker-php-ext-install \
        pdo_pgsql pgsql zip intl mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# php config
COPY ./docker/config/php/php.custom.ini /usr/local/etc/php/conf.d/php.custom.ini

# apcu
RUN pecl install apcu && docker-php-ext-enable apcu
COPY ./docker/config/php/apcu.ini /usr/local/etc/php/conf.d/apcu.ini

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM base AS dev

# xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
COPY ./docker/config/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN touch /var/log/xdebug.log \
    && chown www-data:www-data /var/log/xdebug.log \
    && chmod 666 /var/log/xdebug.log

CMD ["php-fpm"]

FROM base AS prod

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && composer dump-env prod

CMD ["php-fpm"]
