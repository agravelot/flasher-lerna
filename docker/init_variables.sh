#!/bin/bash

set -o pipefail  # trace ERR through pipes
set -o errtrace  # trace ERR through 'time command' and other functions
set -o nounset   ## set -u : exit the script if you try to use an uninitialised variable
set -o errexit   ## set -e : exit the script if any statement returns a non-true return value

if [[ $1 == "production" ]]; then
# Production
    SSH_PRIVATE_KEY=${CI_PRODUCTION_SSH_PRIVATE_KEY}
    SSH_USER=${CI_PRODUCTION_SSH_USER}
    CI_DEPLOY_SSH_PORT=${CI_PRODUCTION_DEPLOY_SSH_PORT}
    CI_DEPLOY_URI=${CI_PRODUCTION_DEPLOY_URI}
    APP_URL=${APP_URL_PRODUCTION}
    APP_NAME=${APP_NAME_PRODUCTION}
    APP_KEY=${APP_KEY_PRODUCTION}
else
# Stagging
    SSH_PRIVATE_KEY=${CI_STAGGING_SSH_PRIVATE_KEY}
    SSH_USER=${CI_STAGGING_SSH_USER}
    CI_DEPLOY_SSH_PORT=${CI_STAGGING_DEPLOY_SSH_PORT}
    CI_DEPLOY_URI=${CI_STAGGING_DEPLOY_URI}
    APP_URL=${APP_URL_STAGGING}
    APP_NAME=${APP_NAME_STAGGING}
    APP_KEY=${APP_KEY_STAGGING}
fi

REMOTE=$SSH_USER@${CI_DEPLOY_URI}
PICBLOG_IMAGE_PHP=registry.gitlab.com/nevax/picblog/picblog_php
PICBLOG_IMAGE_NGINX=registry.gitlab.com/nevax/picblog/picblog_nginx