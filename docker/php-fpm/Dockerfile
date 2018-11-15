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
        usermod -u 1000 www-data && \
        groupmod -g 1000 www-data

# Allow www-data group to read php config files
RUN chown root:www-data -R /usr/local/etc/php && \
        chmod g+r -R /usr/local/etc/php

RUN chown -R 1000:1000 /var/www/html


# Add configurations
COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/
RUN mkdir /etc/supervisor.d
#COPY docker/php-fpm/laravel-worker.ini /etc/supervisor.d/
COPY docker/php-fpm/custom-supervisord.ini /etc/

# Installing required dependencies
RUN apk --no-cache add jpegoptim optipng gifsicle freetype-dev libjpeg-turbo-dev icu-dev bzip2-dev libpng-dev supervisor
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
        apk del php_deps shadow

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/

RUN mv .env.production .env

# Link storage
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage && \
        chown -h 1000:1000 /var/www/html/public/storage

# Clean laravel cache
CMD chown -R 1000:1000 /var/www/html/storage && \
        php artisan config:clear && \
#        php artisan cache:clear && \
        php artisan view:clear && \

# Publish assets
        php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider" && \
        php artisan vendor:publish --tag=telescope-assets --force && \

# Update database
        php artisan migrate --force && \

# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
        php artisan optimize && \
        php artisan route:cache && \
        php artisan config:cache && \

#        chown -R 1000:1000 . && \

# Run queues workers
        supervisord -c /etc/custom-supervisord.ini && \

# Run php-fpm
# https://github.com/docker-library/php/blob/master/7.2/alpine3.8/fpm/Dockerfile
        php-fpm
