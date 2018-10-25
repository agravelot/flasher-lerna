# Picblog

## Prerequisite

`docker docker-compose`

## Deploying in production

### Docker

```bash
docker-compose up -d 
```

### First install

Migrate the database schema

```bash
php artisan migrate
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