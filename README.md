# Flasher

[![pipeline status](https://gitlab.com/flasher/flasher/badges/master/pipeline.svg)](https://gitlab.com/flasher/flasher/commits/master)
[![coverage report](https://gitlab.com/flasher/flasher/badges/master/coverage.svg)](https://gitlab.com/flasher/flasher/commits/master)

Flasher is a website specialized for photographer.
Administration panel is accessible from `/admin` path.

## Features
- Show albums collections
- Categories collections
- Cosplayers (Model) collections
- Testimonials
- Contact page

## Prerequisite

## Deploying in production

### Docker

On local enthronements, you can fill up `.env` files manually from `env.example` files.

- `docker/db/.env.example` -> `docker/db/.env`
- `docker/nginx/.env.example` -> `docker/nginx/.env`
- `.env.production` -> `docker/php-fpm/.env`

On CI/CD pipelines, you can generate the required `.env` files with `docker/generate_env.sh` script, make sure to provide the required variables. 

```bash
docker-compose up -d 
```

You can seed a default admin user.
```bash
php artisan db:seed --class=AdminUserSeeder
```

Default credentails :
- Email `admin@picblog.com`
- Password `secret`

## Testing in local

```bash
composer install
```

```bash
yarn install
```

```bash
yarn run dev
```

## Coding Standards

This project is following Laravel coding standard.
You can read the php-cs-fixer rules [here](https://github.com/matt-allan/laravel-code-style).

## License

Flasher is open-sourced software licensed under GNU GPL V2.
