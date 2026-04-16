#!/bin/bash
set -e

# Configure Apache port (Render fournit PORT)
sed -i "s/Listen 80/Listen ${PORT:-10000}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-10000}/g" /etc/apache2/sites-available/000-default.conf

# Generer APP_KEY si absent
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force 2>/dev/null || true
fi

# Cache Laravel config et routes
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan storage:link 2>/dev/null || true

# Demarrer Apache
exec apache2-foreground
