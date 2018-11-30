#!/usr/bin/env bash

envsubst < docker/db/.env.example > docker/db/.env
envsubst < docker/nginx/.env.example > docker/nginx/.env
envsubst < .env.production > docker/php-fpm/.env