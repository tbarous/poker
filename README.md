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

Initialize the project with:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Run migrations inside api container:

    docker exec -it poker_api php artisan migrate:fresh --seed

Postman collection for api calls exists in root with name: 

    poker.postman_collection.json

- phpmyadmin available on: http://localhost:8000 [server: mysql, username: root, password: password]
- rabbitmq manager available on: http://localhost:15672 [username: guest, password: guest]
- frontend react application available on http://localhost
- backend laravel available on endpoints http://localhost:8080/api/
