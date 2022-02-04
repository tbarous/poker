FROM composer:2.2.5 as composer

FROM php:7.4-fpm

ARG API_PATH

RUN apt-get update && \
    apt install zip unzip -y && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-install sockets

WORKDIR $API_PATH

COPY api .

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN php artisan cache:clear

RUN composer install

RUN chmod -R 777 storage