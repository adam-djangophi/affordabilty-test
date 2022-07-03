FROM serversideup/php:8.1-fpm-nginx

RUN apt-get update &&\
    apt-get install -y lsb-release php-dev &&\
    apt-get clean all &&\
    apt install php8.1-xdebug

COPY --chown=webuser:webgroup . ./
COPY --chown=webuser:webgroup .env.example .env

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --optimize-autoloader --no-dev &&\
    chown -R webuser:webgroup /var/www/html &&\
    chmod 777 -R storage bootstrap/cache


RUN php artisan key:generate
RUN php artisan route:cache

