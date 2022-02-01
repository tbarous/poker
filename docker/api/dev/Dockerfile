FROM php:7.4-fpm

ARG API_PATH

COPY docker/api/setup.sh /tmp/setup.sh

RUN chmod +x /tmp/setup.sh && /tmp/setup.sh

WORKDIR $API_PATH