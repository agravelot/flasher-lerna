# Flasher

[![pipeline status](https://gitlab.com/flasher/flasher/badges/master/pipeline.svg)](https://gitlab.com/flasher/flasher/commits/master)
[![coverage report](https://gitlab.com/flasher/flasher/badges/master/coverage.svg)](https://gitlab.com/flasher/flasher/commits/master)

Flasher is a website specialized for photographer.
Administration panel is accessible in separate project `flasher-admin`.

## Features
- Show albums collections
- Categories collections
- Cosplayers (Model) collections
- Testimonials
- Contact page

## Prerequisite

- docker
- docker-compose

## Dev in local with docker

### Step 1

Begin by cloning this repository to your machine, and copy the `.env` configuration template.

```bash 
git clone git@github.com:FlasherProject/flasher.git
cd flasher
cp .env.docker .env
```

### Step 3
Next, install the required php dependencies, generate the required keys and add storage symbolic link.

```bash
composer install
php artisan key:generate
```

To enable laravel telescope debugging panel, run `php artisan telescope:install`.

### Step 4

Next we are gonna need to compile our assets for styling our content.

```bash
yarn install
yarn dev # or watch, or prod
```

### Step 5

You can now fire up our stack !

```bash
docker network create database-network
docker network create nginx-proxy
docker network create keycloak-network
docker-compose -f docker-compose.yml -f docker-compose.local.yml -f docker-compose.local-dev.yml up -d
```

You can now open your browser `http://flasher.localhost`.

### Step 6

Create administrator user from command line tool, and follow instructions.

```bash
docker-compose exec php sh
php artisan user:create
```

Alternatively, you can create an dummy user from the seeders.

You can seed a default admin user.
```bash
php artisan db:seed --class=AdminUserSeeder
```

Default credentails :
- Email `admin@flasher.com`
- Password `secret`

## Deploying in production

### Docker


#### Step 1
On CI/CD pipelines, you can generate the required `.env` files with `docker/generate_env.sh` script, make sure to provide the required variables. 

#### Step 2
Fire up the docker stack, `-d` can be removed to watch the logs. Or run `docker-compos logs -f`.
```bash
docker-compose up -d 
```

**Warning** : By default, no ports is published outside of the docker stack. To do so, you are gonna need to use the custom docker compose file.
```bash
docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
```

#### Step 3

Connect into running php container.

```bash 
docker-compose exec php sh
``` 

Create administrator user from command line tool, and follow instructions.

```bash
php artisan user:create
```

Alternatively, you can create an dummy user from the seeders.

You can seed a default admin user.
```bash
php artisan db:seed --class=AdminUserSeeder
```

now you should be able to login at `/login` path.

Default credentails :
- Email `admin@picblog.com`
- Password `secret`

## Coding Standards

This project is following Laravel coding standard.
You can read the php-cs-fixer rules [here](https://github.com/matt-allan/laravel-code-style).

## License

Flasher is open-sourced software licensed under GNU GPL V2.
