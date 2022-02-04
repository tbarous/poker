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

Clone the repository and cd into it.

Initialize the project for production with:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Or for development:

    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

Run migrations inside the api container (NOTE: it might take some seconds before DB is ready to accept connections):

    docker exec -it poker_api php artisan migrate:fresh --seed --force

Postman collection for api calls exists in root with name:

    poker.postman_collection.json

Upon *Register* postman call and then *Login* the *Player* api calls will inherit and authorization header with the
token provided by *Login*. After login, all *Player* api CRUD services are available. Otherwise, unauthenticated message
shows.

To populate the database with poker hands invoke the *Add Poker Data* call with a text file containing the hands.
(NOTE: the postman collection DOES NOT include the .txt file despite showing it when imported, so the text file should
be selected manually)

- phpmyadmin (in dev) available on: http://localhost:8000 [server: mysql, username: root, password: password]
- rabbitmq manager available on: http://localhost:15672 [username: guest, password: guest]
- frontend react application available on http://localhost
- backend laravel available on endpoints :
    - http://localhost:8080/api/statistics [all poker rounds with hands and winners]
    - http://localhost:8080/api/best-hands [best poker hands (flush and up)]

To run tests:

    docker exec -it poker_api php artisan test

NOTE: mongodb and rabbitmq manager are not used despite included.

TODO: Hand comparison currently supports different ranks. When two hands are equal currently there is no higher cards
comparison.