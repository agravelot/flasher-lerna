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
    # --no-plugins \
    # Production only
    # --optimize-autoloader \
    # --no-dev

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
COPY --chown=www-data:www-data . /var/www/html

# Importing composer dependencies
COPY --chown=www-data:www-data --from=vendor /app/vendor/ /var/www/html/vendor/

# Importing webpack assets
COPY --chown=www-data:www-data --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --chown=www-data:www-data --from=frontend /app/public/css/ /var/www/html/public/css/
COPY --chown=www-data:www-data --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json

WORKDIR /var/www/html

RUN mv .env.production .env
RUN php artisan key:generate

RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan view:clear

# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
RUN php artisan optimize
RUN php artisan route:cache
RUN php artisan config:cache
