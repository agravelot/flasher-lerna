FROM composer:1.8 as vendor-dev
COPY . .

RUN composer global require hirak/prestissimo \
        && composer install \
            --ignore-platform-reqs \
            --no-interaction \
            --prefer-dist \
            --no-progress \
            --profile \
            --no-scripts \
        && php artisan vendor:publish --tag=lfm_public \
        && php artisan vendor:publish --tag=telescope-assets \
        && php artisan vendor:publish --tag=horizon-assets \
        && chown -R 33:33 vendor/