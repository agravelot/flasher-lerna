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
COPY docker/nginx/conf /etc/nginx
RUN apk add --no-cache bash \
         && cd /etc/nginx/ \
         && cp nginx.conf nginx.tmp.conf \
         && bash bin/inline.sh nginx.tmp.conf > /etc/nginx/nginx.inlined.conf \
         && echo "}" >> /etc/nginx/nginx.inlined.conf

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

COPY --from=nginx_config /etc/nginx/nginx.inlined.conf /etc/nginx/nginx.inlined.conf

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

CMD envsubst '\$NGINX_HOST' < /etc/nginx/nginx.inlined.conf > /etc/nginx/nginx.conf \
        && exec nginx -g 'daemon off;'

#
# PHP Application
#
FROM nevax/docker-php-fpm-alpine-laravel:php7.3 as php

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
        && chown -h 1000:1000 /var/www/html/public/storage

# Clean laravel cache
CMD php artisan config:clear \
# Update database
        && php artisan migrate --force \
# Optimizing for production
# https://laravel.com/docs/5.7/deployment#optimization
        && php artisan cache:clear \
        && php artisan view:clear \
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
