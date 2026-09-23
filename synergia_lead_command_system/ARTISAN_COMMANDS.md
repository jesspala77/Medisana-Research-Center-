# Artisan Commands

Run these inside your Laravel project root:

```bash
php artisan make:controller CommandQueueController
php artisan make:controller LeadWorkflowController
php artisan migrate
php artisan db:seed --class=SynergiaSeeder
php artisan route:list
```

Optional queue tables:

```bash
php artisan queue:table
php artisan migrate
```

Start local server:

```bash
php artisan serve
```
