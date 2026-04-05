# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

**Zone Ciné** — Laravel 12 aggregator for French-language cinema: films, series, streaming platforms. Data sourced entirely from the TMDB API.

Stack: PHP 8.3, Laravel 12, MySQL/MariaDB, Redis (queues/cache), Vite + Tailwind CSS v4 + DaisyUI v5.

## Environment

All `composer`, `php artisan`, and `pnpm` commands must be run inside the `app` Docker container:

```bash
docker compose exec app composer [...]
docker compose exec app php artisan [...]
docker compose exec app pnpm [...]
```

The frontend package manager is **pnpm** (not npm or yarn).

## Common commands

```bash
# Full dev environment (server + queue + logs + vite HMR, all in one)
docker compose exec app composer dev

# Run tests
docker compose exec app composer test

# Run a single test file
docker compose exec app php artisan test --filter=ExampleTest

# Lint PHP with Pint
docker compose exec app ./vendor/bin/pint

# Build frontend assets
docker compose exec app pnpm run build

# Database migrations
php artisan migrate

# Import TMDB catalog from daily exports
docker compose exec app php artisan tmdb:import-export --type=movies --min-popularity=10
docker compose exec app php artisan tmdb:import-export --type=tv --min-popularity=10

# Queue worker for TMDB import jobs
docker compose exec app php artisan queue:work redis --queue=tmdb-import --sleep=3 --tries=3 --max-jobs=500
```

## Architecture

### Data pipeline

TMDB data flows through: **TmdbClient** (HTTP) → **TmdbImporter** (maps + persists) → dispatched by **Jobs** (`ImportTmdbMovie`, `ImportTmdbTvShow`) on the `tmdb-import` queue.

- `ImportTmdbExport` artisan command downloads TMDB's nightly export files, filters by popularity, and dispatches one job per title.
- `SyncTmdbPopular` is a scheduled job (runs nightly at 3h) that keeps popular/now-playing/upcoming content fresh.
- `TmdbClient` always requests `language=fr-FR` and watch providers filtered to `FR`.

### Models & relationships

```
TvShow  ──< Season ──< Episode
TvShow  >──< Genre        (pivot: genre_tv_show)
TvShow  >──< Person       (pivot: person_tv_show, department/job/character/order)
TvShow  >──< WatchProvider (pivot: tv_show_watch_provider, type: flatrate|rent|buy|free)
TvShow  ──< MediaVideo    (polymorphic: mediable)

Movie   >──< Genre, Person, WatchProvider (same pattern)
Movie   ──< MediaVideo
```

Person cast/crew distinction is on the pivot `department` column: `Acting` = cast, anything else = crew.

### Frontend

- CSS is in `resources/css/app.css` using Tailwind CSS v4 `@import 'tailwindcss'` syntax (no `tailwind.config.js`).
- DaisyUI v5 loaded via `@plugin 'daisyui'`. The custom theme is `[data-theme='zone-cine']` defined at the top of `app.css`.
- Component styles use BEM-like class naming (`.media-card`, `.media-card__poster`, etc.) inside `@layer components`.
- Icons come from the `kienso/blade-google-material-symbols` package, used as `<x-gmsi-*>` Blade components.
- `lite-youtube-embed` is used for lazy YouTube embeds in trailer modals.

### Views structure

- `layouts/app.blade.php` — main layout
- `resources/views/tv/`, `movies/`, `people/`, `genres/` — section views
- `resources/views/components/` — reusable Blade components (media-card, person-card, streaming-providers, trailer-button)

### URL routing

| Prefix | Controller |
|---|---|
| `/` | HomeController |
| `/films[/{slug}]` | MovieController |
| `/series[/{slug}]` | TvShowController |
| `/personnes/{slug}` | PersonController |
| `/genre/films/{slug}`, `/genre/series/{slug}` | GenreController |

Routes use slugs (not IDs). The importer generates slugs via `Str::slug()`, appending `-{tmdbId}` on collision.
