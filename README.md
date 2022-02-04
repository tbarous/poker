## PHP Poker Project

This project requires docker and docker-compose on host.

Images included:

- MySQL
- PHP fpm
- nginx
- rabbitmq
- mongoDB
- composer
- phpmyadmin

### Instructions:

Initialize the project for production with:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Or for development:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Run migrations inside the api container:

    docker exec -it poker_api php artisan migrate:fresh --seed --force

Postman collection for api calls exists in root with name:

    poker.postman_collection.json

Upon *Register* postman call and then *Login* the *Player* api calls will inherit and authorization header with the
token provided by *Login*

- phpmyadmin available on: http://localhost:8000 [server: mysql, username: root, password: password]
- rabbitmq manager available on: http://localhost:15672 [username: guest, password: guest]
- frontend react application available on http://localhost
- backend laravel available on endpoints http://localhost:8080/api/
