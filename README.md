# Zone Ciné

Agrégateur cinéma francophone — films, séries, plateformes streaming.
Construit avec Laravel 12 + MySQL + Redis.

---

## Prérequis

- PHP 8.3+
- MySQL / MariaDB
- Redis
- Composer
- Un compte TMDB avec un **API Read Access Token** (Bearer)

---

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configurer la base de données et Redis dans `.env`, puis :

```bash
php artisan migrate
```

---

## Configuration TMDB

Dans `.env`, renseigner le token TMDB (Bearer) :

```env
TMDB_API_TOKEN=eyJhbGciOiJIUzI1NiJ9...
```

Le token est disponible dans les paramètres du compte TMDB → **API → API Read Access Token**.

---

## Import du catalogue TMDB

### Import initial via les exports journaliers

TMDB publie chaque nuit des fichiers contenant tous les IDs de leur base.
La commande les télécharge, les lit en streaming et dispatche un job par titre.

```bash
# Films (popularité ≥ 10, ~80-100k titres)
php artisan tmdb:import-export --type=movies --min-popularity=10

# Séries
php artisan tmdb:import-export --type=tv --min-popularity=10
```

**Options disponibles**

| Option | Défaut | Description |
|---|---|---|
| `--type` | `movies` | `movies` ou `tv` |
| `--min-popularity` | `10` | Score de popularité TMDB minimum |
| `--date` | hier | Date de l'export au format `MM_DD_YYYY` |
| `--dry-run` | — | Compte les entrées sans dispatcher les jobs |

```bash
# Simuler sans importer
php artisan tmdb:import-export --type=movies --dry-run

# Importer un export spécifique
php artisan tmdb:import-export --type=movies --date=03_29_2026
```

### Workers (à configurer dans Supervisor)

Les jobs sont placés sur la queue `tmdb-import`. Lancer un ou plusieurs workers :

```bash
php artisan queue:work redis --queue=tmdb-import --sleep=3 --tries=3 --max-jobs=500
```

> `--max-jobs=500` redémarre le worker toutes les 500 jobs pour éviter les fuites mémoire lors d'un import massif.
> Avec plusieurs workers en parallèle, l'import de ~100k films prend quelques heures (limité par le rate limit TMDB ≈ 40 req/s sur le plan gratuit).

### Synchro quotidienne automatique

Un job planifié tourne chaque nuit à 3h pour maintenir les populaires, les films à l'affiche et les prochaines sorties à jour :

```bash
# S'assurer que le scheduler Laravel tourne (cron sur le VPS)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Routes

| URL | Description |
|---|---|
| `/` | Accueil — à l'affiche, à venir, séries populaires |
| `/films` | Catalogue films (filtres : genre, année, langue, tri) |
| `/films/{slug}` | Fiche film |
| `/series` | Catalogue séries |
| `/series/{slug}` | Fiche série |
| `/personnes/{slug}` | Fiche acteur / réalisateur |
| `/genre/films/{slug}` | Films par genre |
| `/genre/series/{slug}` | Séries par genre |
