#
# Frontend
#
FROM node:8-alpine as frontend

RUN mkdir -p /app/public
RUN mkdir -p /app/resources/{js,css}

COPY ./package.json webpack.mix.js yarn.lock /app/
COPY ./resources/js /app/resources/js/
COPY ./resources/sass /app/resources/sass/

WORKDIR /app

RUN yarn install && yarn production

#
# Application PHP
#
FROM nginx:alpine

COPY ./docker/nginx/site.conf /etc/nginx/conf.d/default.conf

# Importing source code
COPY --chown=nginx:nginx . /var/www/html

# Importing webpack assets
COPY --chown=nginx:nginx --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --chown=nginx:nginx --from=frontend /app/public/css/ /var/www/html/public/css/
COPY --chown=nginx:nginx --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json

WORKDIR /var/www/html