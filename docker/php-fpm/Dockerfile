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
    # Production only
    --optimize-autoloader \
    --no-dev

#
# Application PHP
#
# TODO Add TZ
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

WORKDIR /var/www/html

RUN mv .env.production .env && \
        php artisan storage:link && \
        chown -h www-data:www-data /var/www/html/public/storage && \
        php artisan key:generate && \
        php artisan cache:clear && \
        php artisan config:clear && \
        php artisan view:clear

# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
RUN php artisan optimize && \
        php artisan route:cache && \
        php artisan config:cache
