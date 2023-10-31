FROM php:8.2-fpm

RUN apt-get update -y  \
    && apt-get upgrade -y  \
    && apt-get install -y libpq-dev git libzip-dev unzip

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql zip
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug