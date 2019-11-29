#!/bin/sh

set -o pipefail  # trace ERR through pipes
#set -o errtrace  # trace ERR through 'time command' and other functions
set -o nounset   ## set -u : exit the script if you try to use an uninitialised variable
set -o errexit   ## set -e : exit the script if any statement returns a non-true return value

# Update cnginx config from environments
cat /etc/nginx/nginx.conf | envsubst \$ERROR_LOG_LEVEL > /etc/nginx/nginx.conf
cat /etc/nginx/sites-enabled/default.conf | envsubst \$NGINX_HOST > /etc/nginx/sites-enabled/default.conf

cat /etc/nginx/nginx.conf /etc/nginx/sites-enabled/default.conf

# Validate nginx configuraition
nginx -t

# Goooooooooo
exec nginx -g 'daemon off;'
