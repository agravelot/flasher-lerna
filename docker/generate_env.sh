#!/usr/bin/env bash

set -o pipefail  # trace ERR through pipes
set -o errtrace  # trace ERR through 'time command' and other functions
set -o nounset   ## set -u : exit the script if you try to use an uninitialised variable
set -o errexit   ## set -e : exit the script if any statement returns a non-true return value

ENVIRONMENT=$1

if [[ ENVIRONMENT == "production" ]]; then
    # Production
    export SSH_PRIVATE_KEY=${CI_PRODUCTION_SSH_PRIVATE_KEY}
    export SSH_USER=${CI_PRODUCTION_SSH_USER}
    export CI_DEPLOY_SSH_PORT=${CI_PRODUCTION_DEPLOY_SSH_PORT}
    export CI_DEPLOY_URI=${CI_PRODUCTION_DEPLOY_URI}
    export APP_URL=${APP_URL_PRODUCTION}
    export APP_NAME=${APP_NAME_PRODUCTION}
    export APP_KEY=${APP_KEY_PRODUCTION}
    export ANALYTICS_TRACKING_ID=${ANALYTICS_TRACKING_ID_PROD}
    export SLACK_WEBHOOK_URL=${SLACK_WEBHOOK_URL_PROD}
else
    # Staging
    export SSH_PRIVATE_KEY=${CI_STAGING_SSH_PRIVATE_KEY}
    export SSH_USER=${CI_STAGING_SSH_USER}
    export CI_DEPLOY_SSH_PORT=${CI_STAGING_DEPLOY_SSH_PORT}
    export CI_DEPLOY_URI=${CI_STAGING_DEPLOY_URI}
    export APP_URL=${APP_URL_STAGING}
    export APP_NAME=${APP_NAME_STAGING}
    export APP_KEY=${APP_KEY_STAGING}
    export SLACK_WEBHOOK_URL=${SLACK_WEBHOOK_URL_STAGING}
fi

export APP_ENV=$1

REMOTE=$SSH_USER@${CI_DEPLOY_URI}
PICBLOG_IMAGE_PHP=registry.gitlab.com/flasher/flasher/picblog_php
PICBLOG_IMAGE_NGINX=registry.gitlab.com/flasher/flasher/picblog_nginx
