#
# Frontend
#
FROM node:10-alpine as frontend

WORKDIR /app

COPY . .

RUN yarn install && yarn production

#
# Application PHP
#
FROM nginx:alpine

WORKDIR /var/www/html

RUN apk --no-cache add shadow \
        && usermod -u 1000 nginx

COPY docker/nginx/conf/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf/conf.d/default.conf /etc/nginx/conf.d

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Storrage link
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
    && chown -h 1000:1000 /var/www/html/public/storage

# Importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/ /var/www/html/public
