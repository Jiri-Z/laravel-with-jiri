# Jiri

E-learning platform for modern Laravel development.

## Requirements

- PHP 8.3
- Node.js
- Composer
- SQLite (default) or PostgreSQL

## Development

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
composer run dev
```

Opens at `http://localhost:8000`. Login with seeded credentials: `admin@example.com` / `password`.

## Production

```bash
cp .env.example .env
# Edit .env — set APP_ENV=production, APP_DEBUG=false, APP_URL
composer install --no-dev
php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
npm install
npm run build
```

Serve via your web server pointing to `public/`, or use a process manager for `php artisan serve`.
