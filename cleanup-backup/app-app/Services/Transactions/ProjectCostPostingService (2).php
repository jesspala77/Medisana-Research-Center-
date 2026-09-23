<?php

namespace App\Services\Transactions;

use App\Models\ConstructionProjectCost;
use App\Models\FinancialTransaction;
use Illuminate\Support\Str;
use RuntimeException;

class ProjectCostPostingService
{
    public function post(FinancialTransaction $transaction): ?ConstructionProjectCost
    {
        if (!$transaction->construction_project_id || !Str::startsWith((string) $transaction->suggested_category, 'direct_')) {
            return null;
        }

        if (!in_array($transaction->classification_status, ['auto_approved', 'approved'], true)) {
            throw new RuntimeException('Only approved direct costs may be posted to a project.');
        }

        return ConstructionProjectCost::updateOrCreate(
            ['financial_transaction_id' => $transaction->id],
            [
                'construction_project_id' => $transaction->construction_project_id,
                'cost_date' => $transaction->transaction_date,
                'cost_category' => $transaction->suggested_category,
                'vendor' => $transaction->merchant,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'metadata' => ['confidence' => $transaction->classification_confidence],
            ]
        );
    }
}
