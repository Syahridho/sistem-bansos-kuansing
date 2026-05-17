# AGENTS.md — Agent onboarding and quick reference

Purpose: provide concise, actionable guidance so AI coding agents can be productive immediately.

Quick Start

Run these from the project root:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev    # or npm run build for production
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Tests

```bash
vendor/bin/phpunit
# or
php artisan test
```

What this repo is

- Framework: Laravel (PHP)
- MVC structure: controllers in [app/Http/Controllers](app/Http/Controllers), models in [app/Models](app/Models), views in [resources/views](resources/views)
- Key models: `User`, `Alternatif`, `Kriteria`, `Penilaian`, `PeriodeBantuan`

Where agents should look first

- Project overview: [README.md](README.md)
- Models and business logic: [app/Models](app/Models)
- Routes: [routes/web.php](routes/web.php)
- DB schema: [database/migrations](database/migrations)
- Seeders: [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)

Conventions & notes

- Follow Laravel conventions (Eloquent models, resource controllers, migrations).
- Database: ensure `.env` DB settings are correct before running `migrate`/`seed`.
- Frontend assets use Vite. Use `npm run dev` during development.
- Prefer linking to existing docs (README) rather than copying them.

If you update this file

- Keep guidance minimal and link to in-repo docs for details.
- Add quick commands or pitfalls you ran into that other agents should know.

Next suggestions

- Create specialized instructions or skills for testing and deployment workflows if needed.
