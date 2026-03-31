#!/usr/bin/env bash
set -e

cd /var/www/html

# Copier .env si absent
if [ ! -f .env ]; then
    echo "[entrypoint] Copie de .env.example vers .env..."
    cp .env.example .env
fi

# Installer les dépendances Composer si nécessaire
if [ ! -d vendor ]; then
    echo "[entrypoint] Installation des dépendances Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Générer la clé applicative si vide
if grep -q "^APP_KEY=$" .env; then
    echo "[entrypoint] Génération de la clé applicative..."
    php artisan key:generate --no-interaction
fi

# Attendre MySQL
echo "[entrypoint] Attente de MySQL (${DB_HOST}:${DB_PORT:-3306})..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "[entrypoint] MySQL pas encore prêt, nouvel essai dans 2s..."
    sleep 2
done
echo "[entrypoint] MySQL disponible."

# Migrations
php artisan migrate --no-interaction --force

# Démarrer le cron en arrière-plan
service cron start

# Démarrer supervisor (Apache + queue worker)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
