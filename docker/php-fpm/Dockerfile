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
RUN apk --no-cache add jpegoptim optipng gifsicle freetype-dev libjpeg-turbo-dev icu-dev bzip2-dev libpng-dev
RUN apk --no-cache add --virtual php_deps $PHPIZE_DEPS
RUN docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) pdo_mysql json intl gd zip bz2 opcache exif bcmath pcntl

# Install redis
RUN pecl install -o -f redis && \
        docker-php-ext-enable redis

# Cleanup
RUN rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/* && \
        rm -rf /tmp/pear ~/.pearrc && \
        apk del php_deps

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/

RUN mv .env.production .env

# Link storage
RUN ln -s /var/www/html/storage/app/public && \
        chown -h 1000:1000 /var/www/html/storage

#RUN php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"

# Clean laravel cache
CMD php artisan config:clear && \
        php artisan cache:clear && \
        php artisan view:clear && \

# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
        php artisan optimize && \
        php artisan route:cache && \
        php artisan config:cache && \

# Run php-fpm
# https://github.com/docker-library/php/blob/master/7.2/alpine3.8/fpm/Dockerfile
        php-fpm
