# Production deployment guide

This project is a Laravel 12 app with a Vite frontend and database-backed queue workers. The production host should run:

- PHP 8.2
- MySQL 8+
- Composer
- Node.js + npm
- Nginx
- Supervisor
- Certbot

## 1. Server prep

```bash
sudo apt update
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath composer nodejs npm supervisor certbot python3-certbot-nginx
```

## 2. Prepare app folder

```bash
sudo mkdir -p /var/www/synergia
sudo chown -R $USER:www-data /var/www/synergia
cd /var/www/synergia
git clone <your-repository-url> .
```

## 3. Create environment file

```bash
cp .env.production.example .env
```

Then update values in `.env` for your live host and credentials:

- `APP_URL`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `MAIL_FROM_ADDRESS`
- Microsoft OAuth values
- `MICROSOFT_REDIRECT_URI`

## 4. Generate app key and install dependencies

```bash
php artisan key:generate
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
```

## 5. Create database

```bash
mysql -u root -p
CREATE DATABASE synergia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'synergia'@'localhost' IDENTIFIED BY 'CHANGE_ME';
GRANT ALL PRIVILEGES ON synergia.* TO 'synergia'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 6. Run migrations

```bash
php artisan migrate --force
php artisan db:seed
```

## 7. Configure Nginx

```bash
sudo cp deploy/nginx/synergia.conf /etc/nginx/sites-available/synergia
sudo sed -i 's/EXAMPLE_DOMAIN/yourdomain.com/g' /etc/nginx/sites-available/synergia
sudo ln -sf /etc/nginx/sites-available/synergia /etc/nginx/sites-enabled/synergia
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

## 8. Issue TLS certificate

```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

## 9. Configure queue worker

```bash
sudo cp deploy/supervisor/synergia-queue.conf /etc/supervisor/conf.d/synergia-queue.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status
```

## 10. Scheduler cron

```bash
sudo crontab -e
```

Add:

```cron
* * * * * cd /var/www/synergia && php artisan schedule:run >> /dev/null 2>&1
```

## 11. Deploy command

```bash
chmod +x deploy/scripts/deploy-production-host.sh
./deploy/scripts/deploy-production-host.sh
```

## 12. Post-deploy smoke tests

- [Login](https://yourdomain.com/login)
- [Dashboard](https://yourdomain.com/dashboard)
- [Bond agency outreach](https://yourdomain.com/bond-agency/outreach)

## Notes

- The app is configured for a database-backed queue by default.
- If your host uses a different PHP-FPM socket, edit the `fastcgi_pass` value in [deploy/nginx/synergia.conf](deploy/nginx/synergia.conf).
- For production, set `APP_ENV=production`, `APP_DEBUG=false`, and a valid `APP_KEY` before launch.
- The official Global Synergia Group logo asset is located in the repo under [assests/GSG LOGO FINAL.png](../assests/GSG%20LOGO%20FINAL.png).
