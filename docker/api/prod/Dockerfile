FROM composer:2.2.5 as composer

FROM php:7.4-fpm

ARG API_PATH

COPY docker/api/setup.sh /tmp/setup.sh

RUN chmod +x /tmp/setup.sh && /tmp/setup.sh && rm /tmp/setup.sh

WORKDIR $API_PATH

COPY . .

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install

RUN chmod -R 777 storage/logs

RUN php artisan migrate