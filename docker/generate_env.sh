#!/usr/bin/env bash

set -o pipefail  # trace ERR through pipes
set -o errtrace  # trace ERR through 'time command' and other functions
set -o nounset   ## set -u : exit the script if you try to use an uninitialised variable
set -o errexit   ## set -e : exit the script if any statement returns a non-true return value

source init_variables.sh $1

envsubst < db/.env.example > db/.env
envsubst < nginx/.env.example > nginx/.env
envsubst < ../.env.production > php-fpm/.env