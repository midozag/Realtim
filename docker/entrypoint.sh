#!/bin/bash
set -e

echo "Starting Task Management App Container"

echo "Waiting for MySQL..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    sleep 2
done
echo "MySQL is ready"

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Generating application key..."
    BROADCAST_CONNECTION=log php artisan key:generate --force
fi

echo "Running migrations..."
BROADCAST_CONNECTION=log php artisan migrate --force

echo "Caching config routes and views..."
BROADCAST_CONNECTION=log php artisan config:cache
BROADCAST_CONNECTION=log php artisan route:cache
BROADCAST_CONNECTION=log php artisan view:cache

echo "Linking storage..."
BROADCAST_CONNECTION=log php artisan storage:link --force 2>/dev/null || true

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting services via Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf