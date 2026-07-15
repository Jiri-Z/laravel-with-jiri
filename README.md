# Laravel With Jiri

E-learning platform for modern Laravel development — courses, lessons, reading steps, quizzes, and a PHP code REPL.

Work in progress. Email registration needs Resend credentials to work properly. The two available courses are low quality placeholders.

Built with [Laravel](https://laravel.com), [Livewire](https://livewire.laravel.com), [Tailwind CSS](https://tailwindcss.com), and [Pest](https://pestphp.com).

## Features

- Course/lesson/step hierarchy with publishing workflow
- Step types: reading (Markdown), quiz (single/multiple/text choice)
- Step locking — complete steps in order
- Course progress tracking
- Trivia quiz system (standalone)
- Admin panel for course management
- Czech localization (locale-scoped courses)
- PHP code REPL (standalone, browser-based via php-wasm)

## Requirements

- PHP 8.3+
- Node.js 18+
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

Opens at `http://localhost:8000`.

### Seeded accounts

| Email | Role | Password |
|-------|------|----------|
| `admin@example.com` | Admin | `password` |
| `instructor@example.com` | Instructor | `password` |
| `student@example.com` | Student | `password` |

## Running tests

```bash
php artisan test
```

## Static analysis

```bash
vendor/bin/phpstan analyse
vendor/bin/rector process --dry-run
vendor/bin/pint
```

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
