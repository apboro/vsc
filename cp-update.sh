#!/bin/bash

# disable application
php artisan down

# make DB dump
php artisan db:dump

# update repo
git pull

# install dependencies
composer install

# update DB
php artisan migrate --force

# seed data
php artisan db:seed --force

# start application
php artisan up
