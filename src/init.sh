#! /bin/bash

cd /var/www

if [ $(cat .env|grep "APP_ENV") = "APP_ENV=local" ] \
|| [ $(cat .env|grep "APP_ENV") = "APP_ENV=dev" ]; then
    composer update
    php artisan key:generate
    php artisan migrate:fresh --seed
else
    composer update --no-dev
    php artisan key:generate
    php artisan migrate:fresh
fi

php artisan passport:install --force
