FROM cyberiastudio/php:8.4-fpm

USER root

# Устанавливаем Nginx
RUN apt-get update && apt-get install -y nginx

# Устанавливаем Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Копируем конфигурации
COPY "./deploy/build/fpm/conf.d/php.ini" "$PHP_INI_DIR/php.ini"
COPY "./deploy/build/fpm/conf.d/www.conf" "/usr/local/etc/php-fpm.d/www.conf"
COPY "./deploy/build/fpm/nginx/default.conf" "/etc/nginx/sites-available/default"
COPY "./deploy/build/fpm/start.sh" "/usr/local/bin/start.sh"

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем только файлы, необходимые для установки зависимостей
COPY composer.json composer.lock package.json package-lock.json ./

# Устанавливаем зависимости Composer и npm
RUN composer install --no-interaction --no-scripts --no-autoloader \
    && npm install

# Копируем весь код приложения
COPY . .

# Генерируем автозагрузку Composer и выполняем post-autoload-dump
RUN composer dump-autoload --optimize && composer run-script post-autoload-dump

# Собираем фронтенд
RUN npm run build

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/bootstrap/cache /var/www/storage

# Открываем порты
EXPOSE 80
# Создаем скрипт для запуска PHP-FPM и Nginx
RUN chmod +x /usr/local/bin/start.sh

# Запускаем скрипт
CMD ["/usr/local/bin/start.sh"]
