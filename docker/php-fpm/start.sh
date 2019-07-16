#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
cd /var/www/html

if [[ "$role" = "app" ]]; then

    echo "App role"
    exec php-fpm

elif [[ "$role" = "queue" ]]; then

    echo "Queue role"
    exec php artisan horizon

elif [[ "$role" = "scheduler" ]]; then

    echo "Scheduler role"
    exec crond -f -c /etc/crontabs -d 8

elif [[ "$role" = "publisher" ]]; then
  php artisan db:wait-connection
  php artisan cache:clear-wait-connection
  php artisan migrate --force
  php artisan passport:keys

  rm -rvf public/vendor
  # php artisan telescope:publish
  php artisan horizon:assets

  if [[ "$env" != "local" ]]; then
      # Optimizing for production
      # https://laravel.com/docs/5.8/deployment#optimization
      echo "Caching configuration..."
      php artisan view:clear
      php artisan optimize
      php artisan view:cache
  fi
else
    echo "Could not match the container role \"$role\""
    exit 1
fi
