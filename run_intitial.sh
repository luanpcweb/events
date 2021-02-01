#! /bin/bash

echo 'Run setup...'

echo ' - Docker Build'
docker-compose build

echo ' - Docker Up'
docker-compose up -d

echo 'Waiting 20 seconds.. Up Data base Mysql'
sleep 20

echo 'Run PHP composer install'
docker-compose exec php-fpm composer install

echo ' - Create schema'
docker-compose exec php-fpm php bin/console doctrine:schema:create

echo ' - Fixtures Load'
docker-compose exec php-fpm bin/console doctrine:fixtures:load

echo "Status:"
docker-compose ps | grep "webserver" | cut -d ";" -f 2

echo "End script"