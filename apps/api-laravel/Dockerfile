# ==== Frontend ==== #
FROM node:13-alpine as frontend
WORKDIR /app
COPY . .
RUN : \
        && yarn install --production=true \
        && yarn production \
        ;

# ==== PHP Dependencies ==== #
FROM composer:1.10.5 as vendor
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

COPY docker/nginx/conf /etc/nginx
COPY docker/nginx/start.sh /start.sh
COPY --chown=1000:1000 . /var/www/html

RUN : \
        && apk --no-cache --virtual user-add-dep add shadow \
        && usermod -u 1000 nginx \
        && apk del user-add-dep \
        && ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        && ln -s /var/www/html/storage/app/public/sitemap.xml /var/www/html/public/sitemap.xml \
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
COPY --chown=1000:1000 docker/php-fpm/custom.ini /usr/local/etc/php/conf.d/
COPY --chown=1000:1000 docker/php-fpm/opcache.ini /usr/local/etc/php/conf.d/

# importing source code
COPY --chown=1000:1000 . /var/www/html

# importing composer and assets dependencies
COPY --chown=1000:1000 --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
COPY --chown=1000:1000 --from=composer /usr/bin/composer ./composer

RUN : \
        && chown 1000:1000 -R /usr/local/etc/php/conf.d \
        && ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
        && chown -h 1000:1000 /var/www/html/public/storage \
        # check composer reqs
        && ./composer check-platform-reqs \
        && rm -f ./composer \
        ;

# PHP SPX
#RUN : \
#    && apk add --no-cache zlib-dev git $PHPIZE_DEPS \
#    && git clone https://github.com/NoiseByNorthwest/php-spx.git /tmp/php-spx \
#    && cd /tmp/php-spx \
#    && phpize \
#    && ./configure \
#    && make \
#    && make install \
#    && docker-php-ext-enable spx \
#    && echo 'spx.http_enabled=1' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && echo 'spx.http_key="dev"' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && echo 'spx.http_ip_whitelist="*"' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && apk del $PHPIZE_DEPS \
#    && cd -

COPY --chown=1000:1000 docker/php-fpm/start.sh /start.sh
COPY docker/php-fpm/crontab /etc/crontabs/root

USER 1000:1000

ENTRYPOINT /start.sh

FROM php_base AS php_app
