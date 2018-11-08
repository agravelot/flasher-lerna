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
    --no-scripts \
    # Production only
#    --no-dev \
    --optimize-autoloader

#
# Application PHP
#
# TODO Add TZ
FROM php:7.2-fpm-alpine

WORKDIR /var/www/html

RUN apk --no-cache add shadow && \
        usermod -u 1000 www-data

COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/

# Installing required dependencies
RUN apk add --update --no-cache $PHPIZE_DEPS freetype-dev libjpeg-turbo-dev icu-dev bzip2-dev libpng-dev jpegoptim optipng gifsicle
RUN docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) pdo_mysql json intl gd zip bz2 opcache exif bcmath
RUN pecl install -o -f redis && \
        rm -rf /tmp/pear && \
        docker-php-ext-enable redis

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/

RUN mv .env.production .env
RUN php artisan storage:link && \
        chown -h 1000:1000 /var/www/html/storage

# TODO Key generation should not be in dockerfile
RUN php artisan key:generate && \
        php artisan cache:clear && \
        php artisan config:clear && \
        php artisan view:clear

# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
RUN php artisan optimize && \
        php artisan route:cache && \
        php artisan config:cache
