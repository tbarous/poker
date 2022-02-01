FROM nginx:latest

ARG ENVIRONMENT

COPY docker/nginx/api.conf /etc/nginx/api.conf

COPY docker/nginx/web.conf /etc/nginx/web.conf

COPY docker/nginx/$ENVIRONMENT.conf /etc/nginx/conf.d/default.conf