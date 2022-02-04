FROM composer:2.2.5 as composer

FROM php:7.4-fpm

ARG API_PATH

RUN apt-get update && apt install zip unzip -y && docker-php-ext-install pdo pdo_mysql

WORKDIR $API_PATH

COPY api .

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer update && composer install --ignore-platform-req=ext-sockets

RUN chmod -R 777 storage