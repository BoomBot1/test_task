#!/bin/sh
php-fpm -D
# Инициализация
php artisan storage:link
php artisan migrate
php artisan optimize:clear
# Не трогать!
nginx -g "daemon off;"
