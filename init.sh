#! /bin/bash

cd /var/www

composer update
php artisan key:generate
php artisan migrate:fresh --seed
php artisan passport:install
