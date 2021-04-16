FROM composer:2 as vendor-dev
COPY . .

RUN composer install \
        --ignore-platform-reqs \
        --no-interaction \
        --prefer-dist \
        --no-progress \
        --profile \
        --no-scripts \
        --optimize-autoloader \
        --classmap-authoritative \
    && chown -R 33:33 vendor/
