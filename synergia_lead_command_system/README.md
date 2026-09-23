# Synergia Lead Command System - Laravel Starter Pack

This starter pack adds the operational CRM foundation for:

1. Command Queue
2. SLA Timers
3. Next Best Action
4. One-Click Workflow Buttons
5. Missing Info Checklist
6. Lead Quality Score
7. Campaign Source Tracking
8. Duplicate Merge Assistant

## Install Steps

Copy these folders into your Laravel project:

- `app/Models`
- `app/Services`
- `app/Http/Controllers`
- `database/migrations`
- `database/seeders`
- `routes/synergia.php`

Then register the route file in `routes/web.php`:

```php
require __DIR__.'/synergia.php';
```

Run:

```bash
php artisan migrate
php artisan db:seed --class=SynergiaSeeder
```

## Suggested First Screen

Build `/command-queue` first. It should show:
- New leads
- Overdue follow-ups
- Missing info
- Hot leads
- Next best action
- SLA status
