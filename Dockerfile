FROM nginx:alpine as certs
RUN apk add --no-cache openssl
RUN openssl req -new -passin pass:client11 -out lib/client1.csr \
                         -subj "/C=FR/ST=France" \
                         -newkey rsa:2048 -days 365 -nodes -x509 \
                         -keyout default_ssl.key -out default_ssl.crt

FROM certbot/certbot as letsencrypt
#COPY --from=certs default_ssl.crt /etc/letsencrypt/keys/default_ssl.crt
#COPY --from=certs default_ssl.key /etc/letsencrypt/keys/default_ssl.key

FROM nginx:alpine as nginx_config
COPY docker/nginx/ .
RUN apk add --no-cache bash
RUN cd conf/ \
        && cp nginx.conf nginx.tmp.conf \
        && bash bin/inline.sh nginx.tmp.conf > /conf/nginx.conf \
        && echo "}" >> /conf/nginx.conf

#
# Frontend
#
FROM node:10-alpine as frontend
WORKDIR /app
COPY . .
RUN yarn install && yarn production

#
# PHP Dependencies
#
FROM composer:1.7 as vendor
COPY . .

RUN composer global require hirak/prestissimo \
        && composer install \
            --ignore-platform-reqs \
            --no-interaction \
            --prefer-dist \
            --no-progress \
            --profile \
            --no-scripts \
            # Production only
            --no-dev \
            --optimize-autoloader \
        && php artisan vendor:publish --tag=lfm_public \
        && php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider" \
        && php artisan vendor:publish --tag=telescope-assets

#
# Nginx server
#
FROM nginx:alpine as nginx

WORKDIR /var/www/html

RUN apk --no-cache add shadow \
        && usermod -u 1000 nginx \
        && apk del shadow

COPY --from=nginx_config /conf/nginx.conf /etc/nginx/nginx.conf

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Storrage link
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        && ln -s /var/www/html/storage/sitemap.xml /var/www/html/public/sitemap.xml \
        && chown -h 1000:1000 /var/www/html/public/sitemap.xml

RUN mkdir -p /etc/nginx/certs/keys/

COPY --from=certs default_ssl.crt /etc/nginx/certs/keys/default_ssl.crt
COPY --from=certs default_ssl.key /etc/nginx/certs/keys/default_ssl.key

# Importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/public/vendor/

#
# PHP Application
#
FROM php:7.2-fpm-alpine as php

WORKDIR /var/www/html

RUN apk --no-cache add shadow \
        && usermod -u 1000 www-data  \
        && groupmod -g 1000 www-data \
        && apk del shadow \
# Allow www-data group to read php config files
        && chown root:www-data -R /usr/local/etc/php \
        && chmod g+r -R /usr/local/etc/php \
        && chown -R 1000:1000 /var/www/html

# Add configurations
COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/
COPY docker/php-fpm/custom-supervisord.ini /etc/

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer and assets dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/public/vendor/

# Link storage
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
# Installing required dependencies
        && apk --no-cache add jpegoptim optipng gifsicle freetype-dev libjpeg-turbo-dev icu-dev bzip2-dev libpng-dev libzip-dev zip supervisor \
        && apk --no-cache add --virtual php_deps $PHPIZE_DEPS \
        && docker-php-ext-configure gd \
            --with-gd \
            --with-freetype-dir=/usr/include/ \
            --with-png-dir=/usr/include/ \
            --with-jpeg-dir=/usr/include/ > /dev/null \
        && docker-php-ext-configure zip --with-libzip > /dev/null \
        && docker-php-ext-install -j$(nproc) pdo_mysql intl gd zip bz2 opcache exif bcmath pcntl > /dev/null \
# Install redis
        && pecl install -o -f redis > /dev/null \
        && docker-php-ext-enable redis \
# Cleanup
        && rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/* \
        && rm -rf /tmp/pear ~/.pearrc \
        && apk del php_deps

# Clean laravel cache
CMD php artisan config:clear \
#        && php artisan cache:clear \
        && php artisan view:clear \
# Update database
        && php artisan migrate --force \
# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
        && php artisan optimize \
        && php artisan route:cache \
        && php artisan config:cache \
# Setup permissions
#        && chown -R 1000:1000 storage/ \
#        && chown -R 1000:1000 public/vendor \
# Run queues workers as daemon
        && supervisord -c /etc/custom-supervisord.ini \
# Run php-fpm
# https://github.com/docker-library/php/blob/master/7.2/alpine3.8/fpm/Dockerfile
        && php-fpm
