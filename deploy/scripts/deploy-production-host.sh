#!/usr/bin/env bash

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/synergia}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"
GIT_BRANCH="${GIT_BRANCH:-main}"
TARGET_DOMAIN="${TARGET_DOMAIN:-www.globalsynergiagroup.com}"

if [[ ! -d "$APP_DIR" ]]; then
  echo "App directory $APP_DIR does not exist."
  exit 1
fi

cd "$APP_DIR"

if [[ -f .env.production ]]; then
  cp .env.production .env
fi

if [[ -f .env ]]; then
  echo "Using existing .env"
else
  echo "Missing .env. Copy .env.production.example to .env first."
  exit 1
fi

if [[ -f artisan ]]; then
  echo "[1/10] Enable maintenance mode"
  $PHP_BIN artisan down --render="errors::503" --retry=60 || true

  echo "[2/10] Pull latest code"
  git fetch --all --prune
  git checkout "$GIT_BRANCH"
  git pull --ff-only origin "$GIT_BRANCH"

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

  echo "[8/10] Ensure storage symlink"
  $PHP_BIN artisan storage:link || true

  echo "[9/10] Restart queue workers"
  if command -v supervisorctl >/dev/null 2>&1; then
    supervisorctl reread || true
    supervisorctl update || true
    supervisorctl restart synergia-queue:* || true
  else
    echo "supervisorctl not installed yet; skipping queue restart"
  fi

  echo "[10/10] Disable maintenance mode"
  $PHP_BIN artisan up

  echo "Deployment completed successfully."
else
  echo "Laravel app not found in $APP_DIR"
  exit 1
fi
