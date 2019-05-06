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
FROM composer:1.8 as vendor
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
            --classmap-authoritative

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

# Importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
#COPY --chown=1000:1000 --from=vendor /app/public/vendor/ /var/www/html/public/vendor/

CMD envsubst '\$NGINX_HOST' < /etc/nginx/nginx.inlined.conf > /etc/nginx/nginx.conf \
        && exec nginx -g 'daemon off;'

#
# PHP Application
#
FROM nevax/docker-php-fpm-alpine-laravel as php_base

# Add configurations
COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer and assets dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public

# Link storage
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage

# Check composer reqs
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer check-platform-reqs
RUN rm -f /usr/bin/composer

COPY docker/php-fpm/start.sh /start.sh

CMD /start.sh

FROM php_base AS php_app

FROM php_base AS php_queue

FROM php_base AS php_scheduler
