#!/usr/bin/env bash

set -euo pipefail

APP_DIR="/var/www/synergia"
PHP_BIN="php"
COMPOSER_BIN="composer"
NPM_BIN="npm"
SUPERVISORCTL_BIN="supervisorctl"

cd "$APP_DIR"

echo "[1/10] Enable maintenance mode"
$PHP_BIN artisan down --render="errors::503" --retry=60 || true

echo "[2/10] Pull latest code"
git fetch --all --prune
git checkout main
git pull --ff-only origin main

echo "[3/10] Install PHP dependencies"
$COMPOSER_BIN install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "[4/10] Install Node dependencies"
$NPM_BIN ci

echo "[5/10] Build frontend assets"
$NPM_BIN run build

echo "[6/10] Run database migrations"
$PHP_BIN artisan migrate --force

echo "[7/10] Cache framework artifacts"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "[8/10] Ensure storage symlink exists"
$PHP_BIN artisan storage:link || true

echo "[9/10] Restart queue workers"
$SUPERVISORCTL_BIN reread
$SUPERVISORCTL_BIN update
$SUPERVISORCTL_BIN restart synergia-queue:*

echo "[10/10] Disable maintenance mode"
$PHP_BIN artisan up

echo "Deployment completed successfully."
