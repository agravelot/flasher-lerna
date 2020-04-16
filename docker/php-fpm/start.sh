#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
cd /var/www/html

if [[ "$env" != "local" ]]; then
  php artisan db:wait-connection
  php artisan cache:clear-wait-connection
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
  exec crond -f -c /etc/crontabs -d 8

elif [[ "$role" == "publisher" ]]; then

  php artisan migrate --force
  php artisan passport:keys

  rm -rvf public/vendor/*
  # php artisan telescope:publish
  php artisan horizon:publish
  echo "$CLOUDFRONT_PRIVATE_KEY" | tr -d '\r' > storage/trusted-signer.pem

else
  echo "Could not match the container role \"$role\""
  exit 1
fi
