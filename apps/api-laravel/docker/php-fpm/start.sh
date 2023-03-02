#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
cd /var/www/html

if [[ "${DISABLE_OPCACHE:-false}" == true ]]; then
  echo "Disable opcache"
  rm -rvf /usr/local/etc/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
fi

if [[ "${ENABLE_SPX:-false}" == false ]]; then
  echo "Disable PHP SPX"
  rm -rvf /usr/local/etc/php/conf.d/docker-php-ext-spx.ini
fi

if [[ "$env" == "local" ]]; then
  echo "Install composer dev dependencies"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php
  ./composer.phar install

  echo "Clearing cache"
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
else
  # Optimizing for production
  # https://laravel.com/docs/6.0/deployment#optimization
  echo "Caching configuration..."
  php artisan view:clear
  php artisan optimize
  php artisan view:cache
  php artisan event:cache
fi

#php artisan keycloak:wait
php artisan db:wait-connection
php artisan cache:clear-wait-connection

echo Role : $role

if [[ "$role" == "app" ]]; then

    rm -rvf public/vendor/*

    php artisan migrate --force
    php artisan horizon:publish
    echo "$CLOUDFRONT_PRIVATE_KEY" | tr -d '\r' > storage/trusted-signer.pem

    if [ "$TELESCOPE_ENABLED" == true && "$env" == "local" ]; then
        echo "Publishing telescope assets"
        php artisan telescope:publish
    fi

  exec php-fpm

elif [[ "$role" == "queue" ]]; then

  exec php artisan horizon

elif [[ "$role" == "scheduler" ]]; then

  php artisan schedule:run
  exit 0

else
  echo "Could not match the container role : "$role
  exit 1
fi
