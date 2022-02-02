#!/bin/bash

apt-get update && apt install zip unzip -y && docker-php-ext-install pdo pdo_mysql && rm /tmp/setup.sh

export OAUTH_PRIVATE_KEY=$(cat ../../keys/oauth-private.key)
export OAUTH_PUBLIC_KEY=$(cat ../../keys/oauth-public.key)
