#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
cd /var/www/html

php artisan keycloak:wait
php artisan db:wait-connection
php artisan cache:clear-wait-connection

if [[ "$env" == "local" ]]; then
  echo "Disabling opcache for local"
  rm -rvf /usr/local/etc/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
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

if [[ "$role" == "app" ]]; then

  echo "App role"
  exec php-fpm

elif [[ "$role" == "queue" ]]; then

  echo "Queue role"
  exec php artisan horizon

elif [[ "$role" == "scheduler" ]]; then

  echo "Scheduler role"
  exec php artisan schedule:run

elif [[ "$role" == "publisher" ]]; then

  php artisan migrate --force

  rm -rvf public/vendor/*
  php artisan horizon:publish
  echo "$CLOUDFRONT_PRIVATE_KEY" | tr -d '\r' > storage/trusted-signer.pem

  if [ "$TELESCOPE_ENABLED" == true ]; then
      echo "Publishing telescope assets"
      php artisan telescope:publish
  fi

else
  echo "Could not match the container role \"$role\""
  exit 1
fi
