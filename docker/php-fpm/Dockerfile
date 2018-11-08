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
FROM php:7.2-fpm

WORKDIR /var/www/html

RUN usermod -u 1000 www-data

COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/

RUN apt-get update
# TODO All required after compilation ?
RUN apt-get install -y libcurl4-gnutls-dev libicu-dev libpng-dev libxml2-dev libbz2-dev jpegoptim optipng gifsicle libjpeg-dev libfreetype6-dev
RUN apt-get clean
RUN docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) mbstring pdo_mysql curl json intl gd xml zip bz2 opcache exif bcmath
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
