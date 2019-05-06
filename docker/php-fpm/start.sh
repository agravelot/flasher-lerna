#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
cd /var/www/html

if [[ "$env" != "local" ]]; then

    # Optimizing for production
    # https://laravel.com/docs/5.8/deployment#optimization
    echo "Caching configuration..."
    php artisan config:clear
    php artisan view:clear
    php artisan optimize
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

if [[ "$role" = "app" ]]; then

    echo "App role"

    if [[ "$env" != "local" ]]; then

        php artisan db:wait-connection
        php artisan cache:clear-wait-connection

        php artisan migrate --force
        php artisan passport:keys
        php artisan telescope:publish
        php artisan horizon:assets
    fi

    exec php-fpm

elif [[ "$role" = "queue" ]]; then

    echo "Queue role"
    ls -lah /etc
    exec supervisord --nodaemon -c /etc/custom-supervisord.ini

elif [[ "$role" = "scheduler" ]]; then

    echo "Scheduler role"
    while [[ true ]]
    do
      php /var/www/html/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi