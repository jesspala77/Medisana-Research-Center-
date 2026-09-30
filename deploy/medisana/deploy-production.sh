#!/usr/bin/env bash

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/medisana-research-center}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"
GIT_BRANCH="${GIT_BRANCH:-cleanup/medisana-standalone}"

cd "$APP_DIR"

if [[ ! -f .env ]]; then
  echo "Missing Medisana production .env in $APP_DIR."
  exit 1
fi

echo "[1/9] Enable maintenance mode"
$PHP_BIN artisan down --render="errors::503" --retry=60 || true

echo "[2/9] Update Medisana standalone branch"
git fetch --all --prune
git checkout "$GIT_BRANCH"
git pull --ff-only origin "$GIT_BRANCH"

echo "[3/9] Install PHP dependencies"
$COMPOSER_BIN install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "[4/9] Install and build frontend assets"
$NPM_BIN ci
$NPM_BIN run build

echo "[5/9] Run database migrations"
$PHP_BIN artisan migrate --force

echo "[6/9] Cache Laravel artifacts"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "[7/9] Ensure storage symlink"
$PHP_BIN artisan storage:link || true

echo "[8/9] Restart Medisana queue workers when configured"
if command -v supervisorctl >/dev/null 2>&1; then
  supervisorctl restart medisana-queue:* || true
fi

echo "[9/9] Disable maintenance mode"
$PHP_BIN artisan up

echo "Medisana standalone deployment completed successfully."
