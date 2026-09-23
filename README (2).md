# SynNexus Transaction Classification Engine

Laravel-ready construction expense classification module.

## Included integrations

- CSV/FreshBooks detailed expense import
- Deterministic merchant and description rules
- Learned vendor memory from reviewer corrections
- Existing SynNexus construction-project matching
- Optional OpenAI fallback for ambiguous transactions
- Queue-based classification
- Human review API and basic Blade review screen
- Approved direct-cost posting into `construction_project_costs`
- Feedback/audit history

## Install in the existing SynNexus project

1. Open the SynNexus Laravel project root in VS Code.
2. Back up or commit the current branch.
3. Copy each folder from this package into matching project folders.
4. Merge `routes/api.php` and `routes/web.php`; do not overwrite unrelated routes.
5. Add `.env.example.additions` values to `.env`.
6. Run:

```bash
php artisan migrate
php artisan optimize:clear
php artisan queue:work
```

7. Confirm the existing model is named `App\Models\ConstructionProject`. If not, update imports and foreign-key naming.
8. Test CSV import through `POST /api/transactions/imports`.
9. Open `/finance/transactions/review` while authenticated.

## Safe defaults

AI is disabled by default. Transactions below deterministic confidence thresholds remain in review. Only approved `direct_*` classifications with a project ID are posted as project costs.

## Important merge points

- Existing user/auth layout may not be `layouts.app`; update the Blade view if needed.
- If the project cost table already exists, map `ProjectCostPostingService` to that model instead of creating a duplicate.
- If API auth is not Sanctum, replace the route middleware.
