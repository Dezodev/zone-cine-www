#!/usr/bin/env bash
# setup.sh — Premier déploiement depuis zéro (serveur Nginx + PHP-FPM)
# Usage : ./setup.sh [--repo=<url_git>] [--skip-import]
set -euo pipefail

# ── Configuration ──────────────────────────────────────────────────────────────
REPO_URL="https://github.com/Dezodev/zone-cine-www.git"        # URL SSH ou HTTPS du dépôt (ou --repo=…)
REPO_DIR="/home/freeing9996/wwwroot/zone-cine.fr/www"          # Racine du dépôt git cloné
APP_DIR="$REPO_DIR"                # Racine Laravel (là où artisan se trouve)
PHP_BIN="/www/server/php/83/bin/php"                           # Ex : /usr/bin/php8.3
COMPOSER_BIN="composer"
PNPM_BIN="/www/server/nodejs/v24.13.0/bin/pnpm"
PHP_USER="freeing9996"             # Utilisateur PHP-FPM
# ──────────────────────────────────────────────────────────────────────────────

# ── Options ────────────────────────────────────────────────────────────────────
SKIP_IMPORT=false
for arg in "$@"; do
    case $arg in
        --repo=*)      REPO_URL="${arg#*=}" ;;
        --skip-import) SKIP_IMPORT=true ;;
        *) echo "Option inconnue : $arg" >&2; exit 1 ;;
    esac
done

# ── Couleurs ───────────────────────────────────────────────────────────────────
BLUE='\033[1;34m'; GREEN='\033[1;32m'; YELLOW='\033[1;33m'; RED='\033[1;31m'; RESET='\033[0m'

step() { echo -e "\n${BLUE}▶  $1${RESET}"; }
ok()   { echo -e "${GREEN}✓  $1${RESET}"; }
warn() { echo -e "${YELLOW}⚠  $1${RESET}"; }
fail() { echo -e "${RED}✗  $1${RESET}" >&2; }

# ── Helpers ────────────────────────────────────────────────────────────────────
as_webuser() {
    if [ "$(id -un)" = "$PHP_USER" ]; then
        "$@"
    else
        sudo -u "$PHP_USER" "$@"
    fi
}

ARTISAN() { as_webuser "$PHP_BIN" -d memory_limit=-1 "$APP_DIR/artisan" "$@"; }

# ─────────────────────────────────────────────────────────────────────────────
echo -e "\n${BLUE}═══════════════════════════════════════════${RESET}"
echo -e "${BLUE}   Premier déploiement — $(date '+%Y-%m-%d %H:%M:%S')${RESET}"
echo -e "${BLUE}═══════════════════════════════════════════${RESET}"

# ── 0. Prérequis ───────────────────────────────────────────────────────────────
step "Vérification des prérequis"
for cmd in git "$PHP_BIN" "$COMPOSER_BIN"; do
    command -v "$cmd" >/dev/null 2>&1 \
        || { fail "Commande requise introuvable : $cmd — installez-la avant de continuer."; exit 1; }
done
test -x "$PNPM_BIN" \
    || { fail "Commande requise introuvable : $PNPM_BIN — installez-la avant de continuer."; exit 1; }
ok "git, php, composer, pnpm disponibles"

# ── 1. Clonage du dépôt ────────────────────────────────────────────────────────
step "Clonage du dépôt Git"
if [ -d "$REPO_DIR/.git" ]; then
    warn "Dépôt déjà présent dans $REPO_DIR — clonage ignoré"
elif [ "$REPO_URL" = "<à renseigner>" ] || [ -z "$REPO_URL" ]; then
    fail "REPO_URL non configurée. Modifiez le script ou passez --repo=<url>."
    exit 1
else
    git clone "$REPO_URL" "$REPO_DIR"
    ok "Dépôt cloné dans $REPO_DIR"
fi

if [ ! -f "$APP_DIR/artisan" ]; then
    fail "Fichier artisan introuvable dans $APP_DIR — vérifiez REPO_DIR et APP_DIR."
    exit 1
fi

# ── 2. Fichier .env ────────────────────────────────────────────────────────────
step "Configuration du fichier .env"
if [ ! -f "$APP_DIR/.env" ]; then
    cp "$APP_DIR/.env.example" "$APP_DIR/.env"
    warn "Fichier .env créé à partir de .env.example"
    echo ""
    echo -e "  ${YELLOW}Valeurs minimales à renseigner dans $APP_DIR/.env :${RESET}"
    echo "    APP_ENV=production"
    echo "    APP_DEBUG=false"
    echo "    APP_URL=https://zone-cine.fr"
    echo "    DB_CONNECTION=mysql"
    echo "    DB_HOST=…"
    echo "    DB_DATABASE=…"
    echo "    DB_USERNAME=…"
    echo "    DB_PASSWORD=…"
    echo "    REDIS_HOST=…"
    echo "    TMDB_API_KEY=…"
    echo ""
    read -r -p "  Éditez le fichier, puis appuyez sur Entrée pour continuer…"
else
    ok ".env déjà présent"
fi

# ── 3. Permissions ─────────────────────────────────────────────────────────────
step "Permissions"
if [ "$(id -u)" = "0" ]; then
    chown -R "$PHP_USER:www" "$APP_DIR"
fi
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
ok "Permissions définies sur $PHP_USER"

# ── 4. Dépendances PHP ─────────────────────────────────────────────────────────
step "Installation des dépendances PHP (Composer)"
as_webuser "$COMPOSER_BIN" install \
    --working-dir="$APP_DIR" \
    --no-dev \
    --optimize-autoloader
ok "Dépendances PHP installées"

# ── 5. Clé d'application ───────────────────────────────────────────────────────
step "Génération de la clé d'application (APP_KEY)"
ARTISAN key:generate --force
ok "APP_KEY générée"

# ── 6. Assets frontend ─────────────────────────────────────────────────────────
step "Compilation des assets frontend (pnpm)"
NODE_BIN_DIR="$(dirname "$PNPM_BIN")"
as_webuser env PATH="$NODE_BIN_DIR:$PATH" "$PNPM_BIN" --dir "$APP_DIR" install --frozen-lockfile
as_webuser env PATH="$NODE_BIN_DIR:$PATH" "$PNPM_BIN" --dir "$APP_DIR" run build
ok "Assets compilés"

# ── 7. Migrations ──────────────────────────────────────────────────────────────
step "Migrations de base de données"
ARTISAN migrate --force
ok "Migrations terminées"

# ── 8. Supervisor (queue worker) ─────────────────────────────────────────────
step "Configuration du worker de queue (Supervisor)"

QUEUE_CONNECTION=$(grep -E '^QUEUE_CONNECTION=' "$APP_DIR/.env" | cut -d '=' -f2 | tr -d '[:space:]' || echo "database")
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}
ok "Driver de queue détecté : $QUEUE_CONNECTION"

if [ "$QUEUE_CONNECTION" = "database" ]; then
    step "Création de la table de queue (database driver)"
    if ! ARTISAN migrate:status 2>/dev/null | grep -q 'jobs'; then
        ARTISAN queue:table
        ARTISAN migrate --force
        ok "Table de queue créée"
    else
        ok "Table de queue déjà présente"
    fi
fi

SUPERVISOR_CONF="/etc/supervisor/conf.d/zone-cine-queue.conf"
if [ ! -f "$SUPERVISOR_CONF" ]; then
    cat > "$SUPERVISOR_CONF" <<CONF
[program:zone-cine-queue]
process_name=%(program_name)s_%(process_num)02d
command=$PHP_BIN -d memory_limit=-1 $APP_DIR/artisan queue:work $QUEUE_CONNECTION --queue=tmdb-import --sleep=3 --tries=3 --max-jobs=500
directory=$APP_DIR
user=$PHP_USER
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/zone-cine-queue.log
stdout_logfile_maxbytes=10MB
stopwaitsecs=60
CONF
    ok "Fichier $SUPERVISOR_CONF créé"
else
    ok "Config Supervisor déjà présente"
fi

if command -v supervisorctl >/dev/null 2>&1; then
    supervisorctl reread
    supervisorctl update
    supervisorctl start zone-cine-queue:* 2>/dev/null || supervisorctl restart zone-cine-queue:* 2>/dev/null || true
    ok "Worker de queue démarré"
else
    warn "supervisorctl introuvable — installez Supervisor puis lancez : supervisorctl reread && supervisorctl update"
fi

# ── 10. Import du catalogue TMDB ──────────────────────────────────────────────
if [ "$SKIP_IMPORT" = false ]; then
    step "Import du catalogue TMDB (peut prendre plusieurs minutes)"
    ARTISAN tmdb:import-export --type=movies --min-popularity=10
    ARTISAN tmdb:import-export --type=tv --min-popularity=10
    ok "Import TMDB terminé"
else
    warn "Import TMDB ignoré (--skip-import)"
fi

# ── 11. Caches ────────────────────────────────────────────────────────────────
step "Reconstruction des caches (config, routes, vues)"
ARTISAN optimize
ok "Caches reconstruits"

# ── 12. Sitemap ───────────────────────────────────────────────────────────────
step "Génération du sitemap XML"
ARTISAN sitemap:generate
ok "Sitemap généré"

# ── Résumé ─────────────────────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}═══════════════════════════════════════════${RESET}"
echo -e "${GREEN}   Installation terminée avec succès !${RESET}"
echo -e "${GREEN}═══════════════════════════════════════════${RESET}"
echo ""
echo "  Pour les mises à jour suivantes : ./deploy.sh"
echo ""
