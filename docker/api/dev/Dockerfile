FROM php:7.4-fpm

RUN apt-get update && \
    apt install zip unzip -y && \
    docker-php-ext-install pdo pdo_mysql
