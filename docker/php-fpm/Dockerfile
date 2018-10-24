#
# PHP Dependencies
#
FROM composer:1.7 as vendor

COPY ./database database/

COPY ./composer.json composer.json
COPY ./composer.lock composer.lock

RUN composer global require hirak/prestissimo

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --profile \
    --no-scripts
    #    --no-plugins \
    # Production only
#    --optimize-autoloader \
#    --no-dev

#
# Frontend
#
FROM node:8-alpine as frontend

RUN mkdir -p /app/public
RUN mkdir -p /app/resources/{js,css}

COPY ./package.json webpack.mix.js yarn.lock /app/
COPY ./resources/js /app/resources/js/
COPY ./resources/sass /app/resources/sass/

WORKDIR /app

RUN yarn install && yarn production

#
# Application PHP
#
# TODO Add timze zone
FROM php:7.2-fpm

RUN apt-get update
# TODO All required after compilation ?
RUN apt-get install -yqq libcurl4-gnutls-dev libicu-dev libpng-dev libxml2-dev libbz2-dev
RUN apt-get clean
RUN docker-php-ext-install mbstring pdo_mysql curl json intl gd xml zip bz2 opcache exif

# Importing source code
COPY . /var/www/html

# Importing composer dependencies
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

# Importing webpack assets
COPY --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --from=frontend /app/public/css/ /var/www/html/public/css/
COPY --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json

WORKDIR /var/www/html

# TODO Use prod
RUN mv .env.testing .env

RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan view:clear

RUN php artisan key:generate

# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
#RUN php artisan optimize
#RUN php artisan route:cache
#RUN php artisan config:cache

RUN chown -R www-data:www-data /var/www/html

# TODO Volume storage/ log ?

#
#
#server {
#    listen 80;
#    server_name example.com;
#    root /example.com/public;
#
#    add_header X-Frame-Options "SAMEORIGIN";
#    add_header X-XSS-Protection "1; mode=block";
#    add_header X-Content-Type-Options "nosniff";
#
#    index index.html index.htm index.php;
#
#    charset utf-8;
#
#    location / {
#        try_files $uri $uri/ /index.php?$query_string;
#    }
#
#    location = /favicon.ico { access_log off; log_not_found off; }
#    location = /robots.txt  { access_log off; log_not_found off; }
#
#    error_page 404 /index.php;
#
#    location ~ \.php$ {
#        fastcgi_split_path_info ^(.+\.php)(/.+)$;
#        fastcgi_pass php:9000;
#        fastcgi_index index.php;
#        include fastcgi_params;
#    }
#
#    location ~ /\.(?!well-known).* {
#        deny all;
#    }
#}
#
