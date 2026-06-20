#!/usr/bin/env sh
set -e

cd /var/www/html

# --- Bind nginx to the port Railway provides (defaults to 8080 locally) ---
: "${PORT:=8080}"
sed -i "s/__PORT__/${PORT}/g" /etc/nginx/conf.d/default.conf

# --- Ensure the storage skeleton exists ---------------------------------
# The Railway volume is mounted at storage/app and is empty on first boot,
# so (re)create the directories the app and framework expect.
mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/app/backups \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Fix ownership/permissions (mounted volumes are root-owned on first mount)
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# --- Fail fast if the app key is missing --------------------------------
# Never auto-generate in production: a new key each boot would break every
# encrypted session and any encrypted data. Set APP_KEY as a Railway variable.
if [ -z "${APP_KEY}" ]; then
    echo "FATAL: APP_KEY is not set. Generate one with 'php artisan key:generate --show' and add it as a Railway variable." >&2
    exit 1
fi

# --- Public storage symlink (public/storage -> storage/app/public) ------
php artisan storage:link || true

# --- Cache config/routes/views, then migrate ----------------------------
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# --- Hand off to supervisor (php-fpm + nginx) ---------------------------
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
