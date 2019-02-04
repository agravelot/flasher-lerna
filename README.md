# Picblog

## Prerequisite

`docker docker-compose jpegoptim optipng pngquant gifsicle`

Increase php max upload size

; Maximum allowed size for uploaded files.
; http://php.net/upload-max-filesize
upload_max_filesize = 2M

; Maximum number of files that can be uploaded via a single request
max_file_uploads = 20

## Deploying in production

### Docker

```bash
docker-compose up -d 
```

### First install

```bash
php artisan key:generate
```

Migrate the database schema

```bash
php artisan migrate
```

Publish 
```bash 
 php artisan vendor:publish --tag=lfm_public
```

Add the admin user
```bash
php artisan db:seed --class=AdminUserSeeder
```

Default credentails :
- Email `admin@picblog.com`
- Password `secret`

### Updating

## Testing in development

```bash
composer install
```

```bash
yarn install
```

```bash
yarn run dev
```
Check the `packages.json` file for more commands.


env DOCKER_HOST=tcp://nevax.awsmppl.com.:4243 docker-compose -f /home/nevax/Lab/picblog/docker-compose.yml up -d --build --remove-orphans
https://github.com/docker-library/docs/issues/496