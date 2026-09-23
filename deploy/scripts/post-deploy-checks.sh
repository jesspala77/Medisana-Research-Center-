#!/usr/bin/env bash

set -euo pipefail

APP_DIR="/var/www/synergia"
PHP_BIN="php"

cd "$APP_DIR"

echo "Running post-deploy checks..."

$PHP_BIN artisan about
$PHP_BIN artisan migrate:status --no-interaction
$PHP_BIN artisan queue:failed --no-interaction
$PHP_BIN artisan route:list --compact

echo "Post-deploy checks complete."
