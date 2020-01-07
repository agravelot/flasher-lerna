# ==== Frontend ==== #
FROM node:13-alpine as frontend
WORKDIR /app
COPY . .
RUN : \
        && yarn install \
        && yarn production \
        ;

# ==== PHP Dependencies ==== #
FROM composer:1.9 as vendor
COPY . .

RUN : \
        && composer global require hirak/prestissimo \
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

# ==== Nginx ==== #
FROM nginx:alpine as nginx

WORKDIR /var/www/html

RUN : \
        && apk --no-cache --virtual user-add-dep add shadow \
        && usermod -u 1000 nginx \
        && apk del user-add-dep \
        ;

COPY docker/nginx/conf /etc/nginx
COPY docker/nginx/start.sh /start.sh

# importing source code
COPY --chown=1000:1000 . /var/www/html

# storrage link
RUN : \
        && ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        && ln -s /var/www/html/storage/sitemap.xml /var/www/html/public/sitemap.xml \
        && chown -h 1000:1000 /var/www/html/public/sitemap.xml \
        && mkdir /var/www/html/public/vendor \
        && chown -h 1000:1000 /var/www/html/public/vendor \
        && chmod +x /start.sh \
        ;

# importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public

ENTRYPOINT /start.sh

# ==== PHP Application ==== #
FROM registry.gitlab.com/nevax/docker-php-fpm-alpine-laravel:php7.4 as php_base

# import PHP configurations
COPY docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/

# importing source code
COPY --chown=1000:1000 . /var/www/html

# importing composer and assets dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
COPY --chown=1000:1000 --from=composer /usr/bin/composer ./composer

# link storage
RUN : \
        && ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        # check composer reqs
        && ./composer check-platform-reqs \
        && rm -f ./composer \
        ;

COPY --chown=1000:1000 docker/php-fpm/start.sh /start.sh
COPY docker/php-fpm/crontab /etc/crontabs/root

USER 1000:1000

ENTRYPOINT /start.sh

FROM php_base AS php_app
