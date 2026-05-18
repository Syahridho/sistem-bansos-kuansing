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
- Domain: social assistance decision support with beneficiary ranking and period management
- Core data model: `PeriodeBantuan` with nested `Alternatif`, `Kriteria`, `Penilaian`, and `AssistanceType`

Where agents should look first

- `routes/web.php` for route and middleware structure
- `app/Http/Controllers/PeriodeBantuanController.php` for period lifecycle, import, and status toggling
- `app/Http/Controllers/AlternatifController.php` for alternative CRUD inside a period
- `app/Http/Controllers/SpkController.php` for ranking and export behavior
- `app/Models/PeriodeBantuan.php`, `Alternatif.php`, `Kriteria.php`, `Penilaian.php`, `AssistanceType.php`
- `app/Imports/AlternatifImport.php` and `app/Imports/AlternatifSheetImport.php` for Excel header/row mapping and duplicate NIK handling
- `app/Services/NikValidationService.php` for cross-period duplicate checks
- `app/Services/CalendarEventService.php` for calendar event generation
- `resources/views` for Blade templates, layout, and UI patterns
- `database/migrations` and `database/seeders/DatabaseSeeder.php` for schema and sample data

Conventions & notes

- Follow Laravel conventions (Eloquent, resource controllers, migrations).
- This app uses role middleware: `auth`, `role:admin,operator`, and `role:admin`.
- `PeriodeBantuan` status is `buka` / `tutup`; operator role may not reopen a closed period.
- Excel import validates headers, saves alternatives + ratings, and records duplicate NIK warnings without blocking import.
- Ranking/export respects `AssistanceType::maksimal_penerima` and produces result files via `app/Exports/PerangkinganExport.php`.
- Use `php artisan migrate --seed` after `.env` setup.
- Frontend assets are built with Vite. Use `npm run dev` for development.
- Prefer understanding the custom domain from code first; this repo’s `README.md` is generic Laravel boilerplate.

If you update this file

- Keep guidance minimal and link to in-repo docs for details.
- Add quick commands or pitfalls you ran into that other agents should know.

Next suggestions

- Create a focused skill for Excel import validation and duplicate-NIK handling.
- Create a focused skill for SPK ranking and export validation.
