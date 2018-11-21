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

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d/site.conf /etc/nginx/conf.d/default.conf

# Importing source code
COPY --chown=1000:1000 . /var/www/html

# Storrage link
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
    && chown -h 1000:1000 /var/www/html/public/storage

# Importing webpack assets
COPY --chown=1000:1000 --from=frontend /app/public/js/ /var/www/html/public/js
COPY --chown=1000:1000 --from=frontend /app/public/css/ /var/www/html/public/css
COPY --chown=1000:1000 --from=frontend /app/public/mix-manifest.json /var/www/html/public/mix-manifest.json
