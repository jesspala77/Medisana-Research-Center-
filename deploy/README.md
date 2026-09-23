# Production Deployment Pack

This folder contains ready-to-use deployment artifacts for Synergia.

## Files

- nginx/synergia.conf: Nginx virtual host for Laravel in /var/www/synergia/public
- supervisor/synergia-queue.conf: Queue worker process manager config
- scripts/deploy-production.sh: End-to-end deploy script
- scripts/post-deploy-checks.sh: Post-deploy health checks

## 1. Server prerequisites

Install these packages on Ubuntu:

- nginx
- mysql-server
- php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath
- composer
- nodejs npm
- supervisor
- certbot python3-certbot-nginx

## 2. Place app on server

Clone app into /var/www/synergia and create a valid .env with production values.

## 3. Install Nginx site

sudo cp deploy/nginx/synergia.conf /etc/nginx/sites-available/synergia
sudo ln -s /etc/nginx/sites-available/synergia /etc/nginx/sites-enabled/synergia
sudo nginx -t
sudo systemctl reload nginx

## 4. Issue SSL certificate

sudo certbot --nginx -d your-domain.com -d www.your-domain.com

Then verify certificate paths in /etc/nginx/sites-available/synergia and reload nginx again.

## 5. Install Supervisor queue config

sudo cp deploy/supervisor/synergia-queue.conf /etc/supervisor/conf.d/synergia-queue.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status

## 6. Add scheduler cron

sudo crontab -e

Add:

* * * * * cd /var/www/synergia && php artisan schedule:run >> /dev/null 2>&1

## 7. Run deploy script

chmod +x deploy/scripts/deploy-production.sh deploy/scripts/post-deploy-checks.sh
./deploy/scripts/deploy-production.sh
./deploy/scripts/post-deploy-checks.sh

## 8. Smoke test routes

- /dashboard
- /command-queue
- /bond-agency/outreach
- /remodeling

## Notes

- The deploy script assumes your git default branch is main.
- If your server uses another PHP-FPM socket version, update it in nginx/synergia.conf.
- Queue connection is set to database in current app defaults.
