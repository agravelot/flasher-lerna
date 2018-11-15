#
# Frontend
#
FROM node:8-alpine as frontend

RUN mkdir -p /app/public
RUN mkdir -p /app/resources/{js,css}

COPY ./package.json webpack.mix.js yarn.lock /app/
COPY ./resources/js /app/resources/js
COPY ./resources/sass /app/resources/sass

WORKDIR /app

RUN yarn install && yarn production

#
# Application PHP
#
FROM nginx:alpine

WORKDIR /var/www/html

RUN apk --no-cache add shadow && \
        usermod -u 1000 nginx

COPY docker/nginx/site.conf /etc/nginx/conf.d/default.conf

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Storrage link
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage && \
    chown -h 1000:1000 /var/www/html/public/storage

# Importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/js/ /var/www/html/public/js
COPY --chown=1000:1000 --from=frontend /app/public/css/ /var/www/html/public/css
COPY --chown=1000:1000 --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json
