## PHP Poker Project

This project requires docker and docker-compose on host.

Images included:

- MySQL
- Redis
- PHP fpm
- nginx
- rabbitmq
- mongoDB
- composer

*How to use:*

To start the project run in the root directory:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Application is available on http://localhost
