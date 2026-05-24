# Mobin Backend System

Mobin is a full-stack application for tracking spiritual practice activity, awarding points, and surfacing competitive leaderboards. The repository contains a **Laravel 12 REST API** and a **Vue 3 single-page client** that work together as a versioned JSON API (`/api/v1`).

## Overview

Users register and authenticate with token-based sessions (Laravel Sanctum). Authenticated users can log **zikr** and **tasbeh** activities to earn points and build daily streaks. Public leaderboard endpoints rank the top performers across multiple time windows without requiring authentication.

| Layer | Technology |
| --- | --- |
| API | PHP 8.2+, Laravel 12, Sanctum |
| Database | MariaDB (MySQL-compatible) |
| Frontend | Vue 3, Vite, Pinia, Vue Router |
| Testing | Pest |

## Features

### Authentication
- Register, login, and logout with API tokens
- Current user profile (`/me`)
- Password reset via email OTP

### Points & streaks
- Record activity types: `zikr` and `tasbeh`
- Points roll up into daily, weekly, monthly, and all-time aggregates
- Streak tracking based on consecutive days of earning points

### Leaderboards
Public top-10 rankings by period:
- All time
- Today
- This week
- This month

### Web client (`frontend/`)
- Home page with tabbed leaderboards
- Registration, login, forgot/reset password flows
- Authenticated dashboard for earning points

## Repository structure

```
Mobin-Backend-System/
├── app/                    # Laravel application code
│   ├── Http/Controllers/v1/
│   ├── Http/Requests/
│   ├── Http/Middleware/
│   └── Models/
├── database/migrations/    # Schema and aggregate tables
├── routes/api.php          # Versioned API routes
├── frontend/               # Vue SPA (separate Vite app)
│   ├── src/
│   └── package.json
├── tests/                  # Pest feature and unit tests
└── composer.json
```

## API reference

All routes are prefixed with `/api/v1` and return JSON (`ForceJsonResponse` middleware).

| Method | Endpoint | Auth | Description |
| --- | --- | --- | --- |
| `POST` | `/register` | — | Create account; returns user + token |
| `POST` | `/login` | — | Sign in; returns user + token |
| `POST` | `/logout` | Bearer | Revoke current token |
| `GET` | `/me` | Bearer | Authenticated user profile |
| `POST` | `/password/forget` | — | Send password-reset OTP |
| `POST` | `/password/reset` | — | Reset password with OTP |
| `POST` | `/points` | Bearer | Log `zikr` or `tasbeh` activity |
| `GET` | `/points/all` | — | All-time leaderboard |
| `GET` | `/points/day` | — | Today's leaderboard |
| `GET` | `/points/week` | — | This week's leaderboard |
| `GET` | `/points/month` | — | This month's leaderboard |

Authenticated requests use the `Authorization: Bearer <token>` header.

## Prerequisites

- PHP 8.2 or newer with common extensions (`mbstring`, `pdo`, etc.)
- [Composer](https://getcomposer.org/)
- Node.js 18+ and npm
- MariaDB or MySQL

## Getting started

### 1. Backend

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure database credentials in `.env` (default database name: `mobin_backend_system`):

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mobin_backend_system
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and start the API:

```bash
php artisan migrate
php artisan serve
```

The API is available at `http://127.0.0.1:8000`.

**One-command setup** (install, env, key, migrate, and build root assets):

```bash
composer setup
```

**Development** (API server, queue worker, and root Vite in parallel):

```bash
composer dev
```

### 2. Frontend

```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

In development, the Vite dev server proxies API calls to the Laravel backend. For production, set `VITE_API_BASE_URL` to your deployed API base (e.g. `https://your-domain.com/api/v1`).

```bash
npm run build
```

### 3. Mail (password reset)

Password reset sends OTP codes via Laravel Mail. Configure your mail driver in `.env`. For local development, `MAIL_MAILER=log` writes messages to `storage/logs/laravel.log`.

## Running tests

```bash
composer test
# or
php artisan test
```

## Health check

Laravel exposes a health endpoint at `/up` for uptime monitoring.

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
