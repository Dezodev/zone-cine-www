#!/usr/bin/env bash
# deploy.sh — Met à jour l'application en production (Nginx + PHP-FPM + Linux)
# Usage : ./deploy.sh [--skip-assets] [--skip-migrations]
set -euo pipefail

# ── Configuration ─────────────────────────────────────────────────────────────
REPO_DIR="/home/freeing9996/wwwroot/zone-cine.fr/www"
APP_DIR="$REPO_DIR"                  # Laravel à la racine du dépôt
PHP_BIN="/www/server/php/83/bin/php" # Ex : /usr/bin/php8.3
COMPOSER_BIN="composer"
PNPM_BIN="/www/server/nodejs/v24.13.0/bin/pnpm"
PHP_USER="freeing9996"               # Utilisateur qui exécute PHP-FPM
# ─────────────────────────────────────────────────────────────────────────────

# ── Options ───────────────────────────────────────────────────────────────────
SKIP_ASSETS=false
SKIP_MIGRATIONS=false
for arg in "$@"; do
    case $arg in
        --skip-assets)     SKIP_ASSETS=true ;;
        --skip-migrations) SKIP_MIGRATIONS=true ;;
        *) echo "Option inconnue : $arg" >&2; exit 1 ;;
    esac
done

# ── Couleurs ──────────────────────────────────────────────────────────────────
BLUE='\033[1;34m'; GREEN='\033[1;32m'; YELLOW='\033[1;33m'; RED='\033[1;31m'; RESET='\033[0m'

step() { echo -e "\n${BLUE}▶  $1${RESET}"; }
ok()   { echo -e "${GREEN}✓  $1${RESET}"; }
warn() { echo -e "${YELLOW}⚠  $1${RESET}"; }
fail() { echo -e "${RED}✗  $1${RESET}" >&2; }

# ── Helpers ───────────────────────────────────────────────────────────────────
# Exécute une commande en tant que $PHP_USER (sudo si nécessaire)
as_webuser() {
    if [ "$(id -un)" = "$PHP_USER" ]; then
        "$@"
    else
        sudo -u "$PHP_USER" "$@"
    fi
}

ARTISAN() { as_webuser "$PHP_BIN" -d memory_limit=-1 "$APP_DIR/artisan" "$@"; }

# ── Vérifications préalables ──────────────────────────────────────────────────
if [ ! -d "$APP_DIR" ]; then
    fail "Répertoire introuvable : $APP_DIR"
    exit 1
fi

if [ ! -f "$APP_DIR/artisan" ]; then
    fail "Fichier artisan introuvable dans $APP_DIR"
    exit 1
fi

echo -e "\n${BLUE}═══════════════════════════════════════════${RESET}"
echo -e "${BLUE}   Déploiement — $(date '+%Y-%m-%d %H:%M:%S')${RESET}"
echo -e "${BLUE}═══════════════════════════════════════════${RESET}"

# ── 1. Git pull ───────────────────────────────────────────────────────────────
step "Mise à jour du code (git pull)"
git -C "$REPO_DIR" pull
ok "Code mis à jour — $(git -C "$REPO_DIR" log -1 --format='%h %s')"

# ── 2. Mode maintenance ───────────────────────────────────────────────────────
step "Activation du mode maintenance"
ARTISAN down --retry=15

# Désactive le mode maintenance en cas d'erreur
trap 'fail "Erreur durant le déploiement !"; ARTISAN up 2>/dev/null || true; echo ""' ERR

# ── 3. Dépendances PHP ────────────────────────────────────────────────────────
step "Installation des dépendances PHP (Composer)"
as_webuser "$COMPOSER_BIN" install \
    --working-dir="$APP_DIR" \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --quiet
ok "Dépendances PHP installées"

# ── 4. Assets frontend ────────────────────────────────────────────────────────
if [ "$SKIP_ASSETS" = false ]; then
    step "Compilation des assets frontend (pnpm)"
    NODE_BIN_DIR="$(dirname "$PNPM_BIN")"
    as_webuser env PATH="$NODE_BIN_DIR:$PATH" "$PNPM_BIN" --dir "$APP_DIR" install --frozen-lockfile --silent
    as_webuser env PATH="$NODE_BIN_DIR:$PATH" "$PNPM_BIN" --dir "$APP_DIR" run build --silent
    ok "Assets compilés"
else
    warn "Assets ignorés (--skip-assets)"
fi

# ── 5. Migrations ─────────────────────────────────────────────────────────────
if [ "$SKIP_MIGRATIONS" = false ]; then
    step "Migrations de base de données"
    ARTISAN migrate --force
    ok "Migrations terminées"
else
    warn "Migrations ignorées (--skip-migrations)"
fi

# ── 6. Nettoyage des caches ───────────────────────────────────────────────────
step "Nettoyage des caches Laravel"
ARTISAN optimize:clear
ok "Caches nettoyés"

# ── 7. Reconstruction des caches ─────────────────────────────────────────────
step "Reconstruction des caches (config, routes, vues, events)"
ARTISAN optimize
ok "Caches reconstruits"

# ── 8. Fin du mode maintenance ────────────────────────────────────────────────
trap - ERR
step "Désactivation du mode maintenance"
ARTISAN up

# ── 9. Redémarrage du worker de queue ────────────────────────────────────────
step "Redémarrage du worker de queue (Supervisor)"
if command -v supervisorctl >/dev/null 2>&1; then
    sudo supervisorctl restart zone-cine-queue:* 2>/dev/null || true
    ok "Workers de queue redémarrés"
else
    warn "supervisorctl introuvable — redémarrez manuellement les workers si nécessaire"
fi

# ── 10. Sitemap ───────────────────────────────────────────────────────────────
step "Génération du sitemap XML"
ARTISAN sitemap:generate
ok "Sitemap généré"

# ── Résumé ────────────────────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}═══════════════════════════════════════════${RESET}"
echo -e "${GREEN}   Déploiement terminé avec succès !${RESET}"
echo -e "${GREEN}═══════════════════════════════════════════${RESET}"
echo ""
