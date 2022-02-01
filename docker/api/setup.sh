#!/bin/bash

apt-get update && apt install zip unzip -y && docker-php-ext-install pdo pdo_mysql && rm /tmp/setup.sh