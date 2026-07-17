FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear
RUN php artisan cache:clear

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080