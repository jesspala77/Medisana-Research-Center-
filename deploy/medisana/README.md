# Medisana production deployment

This deployment is separate from the existing Synergia deployment. It publishes the `cleanup/medisana-standalone` branch to `medisana-research.com`.

## Production host

Clone the repository to `/var/www/medisana-research-center` on the Medisana host. Configure the web server with [nginx/medisana-research.conf](nginx/medisana-research.conf), then issue TLS certificates for `medisana-research.com` and `www.medisana-research.com`.

Copy `.env.medisana.production.example` to `.env` on the production host. Set the real database credentials, mail settings, `APP_KEY`, and secure portal URL. Do not commit the production `.env` file.

Required production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://medisana-research.com
MEDISANA_DOMAIN=medisana-research.com
```

## GitHub Actions secrets

Create these repository secrets before enabling automatic deployment:

- `MEDISANA_DEPLOY_HOST`
- `MEDISANA_DEPLOY_USER`
- `MEDISANA_DEPLOY_SSH_KEY`
- `MEDISANA_DEPLOY_PORT` (optional; defaults to 22)
- `MEDISANA_DEPLOY_PATH` (for example `/var/www/medisana-research-center`)

The workflow in `.github/workflows/deploy-medisana.yml` deploys only when `cleanup/medisana-standalone` is pushed. It never deploys the Synergia `main` branch.

## First deployment on the host

```bash
cd /var/www/medisana-research-center
git fetch --all --prune
git checkout cleanup/medisana-standalone
git pull --ff-only origin cleanup/medisana-standalone
cp deploy/medisana/.env.medisana.production.example .env
# Edit .env with production secrets and run: php artisan key:generate
chmod +x deploy/medisana/deploy-production.sh
APP_DIR=/var/www/medisana-research-center GIT_BRANCH=cleanup/medisana-standalone ./deploy/medisana/deploy-production.sh
```

After deploying, check:

- `https://medisana-research.com/capabilities`
- `https://medisana-research.com/patients`
- `https://medisana-research.com/es/patients`

