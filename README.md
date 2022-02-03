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

### Instructions:

Initialize the project with:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Run migrations inside api container:

    docker exec -it poker_api php artisan migrate:fresh --seed

Application is available on http://localhost
