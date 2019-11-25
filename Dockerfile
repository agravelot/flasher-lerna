#
# Frontend
#
FROM node:12-alpine as frontend
WORKDIR /app
COPY . .
RUN yarn install && yarn production

#
# PHP Dependencies
#
FROM composer:1.9 as vendor
COPY . .

ARG COMPOSER_ALLOW_SUPERUSER=1

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

COPY docker/nginx/conf /etc/nginx

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Storrage link
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        && ln -s /var/www/html/storage/sitemap.xml /var/www/html/public/sitemap.xml \
        && chown -h 1000:1000 /var/www/html/public/sitemap.xml \
        && mkdir /var/www/html/public/vendor \
        && chown -h 1000:1000 /var/www/html/public/vendor \
        ;

# Importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
#COPY --chown=1000:1000 --from=vendor /app/public/vendor/ /var/www/html/public/vendor/

CMD cat /etc/nginx/nginx.conf | envsubst '\$ACCESS_LOG_LEVEL' > /etc/nginx/nginx.conf \
        && cat /etc/nginx/sites-enabled/picblog.conf | envsubst '\$NGINX_HOST' > /etc/nginx/sites-enabled/picblog.conf \
        && exec nginx -g 'daemon off;'

#
# PHP Application
#
FROM registry.gitlab.com/nevax/docker-php-fpm-alpine-laravel as php_base

# Add configurations
COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Importing composer and assets dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
COPY --chown=1000:1000 --from=composer /usr/bin/composer ./composer

# Link storage
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        # Check composer reqs
        && ./composer check-platform-reqs && rm -f ./composer

COPY --chown=1000:1000 docker/php-fpm/start.sh /start.sh
COPY docker/php-fpm/crontab /etc/crontabs/root

USER 1000:1000

CMD /start.sh

FROM php_base AS php_app
