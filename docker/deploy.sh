#!/bin/bash

set -o pipefail  # trace ERR through pipes
set -o errtrace  # trace ERR through 'time command' and other functions
set -o nounset   ## set -u : exit the script if you try to use an uninitialised variable
set -o errexit   ## set -e : exit the script if any statement returns a non-true return value

source init_variables.sh $1

echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null

echo " * OPENING DOCKER SOCKET TUNNEL"
socat \
  "UNIX-LISTEN:/tmp/docker.sock,reuseaddr,fork" \
  "EXEC:'ssh -kTax $REMOTE -p $CI_DEPLOY_SSH_PORT socat STDIO UNIX-CONNECT\:/var/run/docker.sock'" \
  &
export DOCKER_HOST=unix:///tmp/docker.sock
export COMPOSE_PROJECT_NAME=picblog
export TRUSTED_PROXIES=$(docker network inspect nginx-proxy --format='{{ (index .IPAM.Config 0).Subnet }}')
echo " * LOGIN WITH GITLAB-CI TOKEN"
docker login -u $CI_REGISTRY_USER -p $CI_REGISTRY_PASSWORD registry.gitlab.com
# backup current image if already present locally
if [[ ! "$(docker images -q ${PICBLOG_IMAGE_PHP} 2> /dev/null)" == "" && ! "$(docker images -q ${PICBLOG_IMAGE_NGINX} 2> /dev/null)" == ""  ]]; then
  echo " * BACKING UP CURRENT IMAGES VERSIONS"
  docker tag ${PICBLOG_IMAGE_PHP} picblog-php-backup
  docker tag ${PICBLOG_IMAGE_NGINX} picblog-nginx-backup
fi
echo " * PULLING NEW IMAGES"
docker-compose pull
echo " * STOPPING QUEUE WORKERS"
docker-compose exec -T queue php artisan horizon:pause || echo "Container is not running"
# TODO Remove after deployment
docker-compose exec -T -u root queue chown 1000:1000 -R /var/www/html || echo "Container is not running"
docker-compose stop -t 60 queue || echo "Container is not running"
docker-compose stop scheduler || echo "Container is not running"
echo " * PUTTING LARAVEL IN MAINTENANCE MODE"
docker-compose exec -T php php artisan down --message="We'll be back soon" --retry=60 || echo "Container is not running"
echo " * SAVING REDIS STATES"
docker-compose exec -T cache redis-cli SAVE || echo "Container is not running"
echo " * UPDATING RUNNING CONTAINERS"
docker-compose up -d --remove-orphans
echo " * LEAVING MAINTENANCE MODE"
docker-compose exec -T php php artisan up
docker-compose exec -T queue php artisan horizon:continue
#echo " * CLEANING OLD IMAGES"
#ssh -t ${REMOTE} -p $CI_DEPLOY_SSH_PORT "docker-clean images"