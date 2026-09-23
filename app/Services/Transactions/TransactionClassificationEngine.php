<?php

namespace App\Services\Transactions;

use App\Models\FinancialTransaction;

class TransactionClassificationEngine
{
    public function __construct(
        private TransactionClassifierService $rules,
        private VendorMemoryService $memory,
        private AiTransactionClassifier $ai,
        private ProjectCostPostingService $posting,
    ) {}

    public function process(FinancialTransaction $transaction, bool $postApproved = true): FinancialTransaction
    {
        $result = $this->memory->match($transaction) ?? $this->rules->classify($transaction);

        if (in_array($result->status, ['unclassified', 'needs_review'], true)
            && $result->confidence < config('transaction_classifier.ai.trigger_below', 0.80)) {
            $result = $this->ai->classify($transaction) ?? $result;
        }

        $transaction->forceFill([
            'construction_project_id' => $result->projectId,
            'suggested_category' => $result->category,
            'classification_confidence' => $result->confidence,
            'classification_status' => $result->status,
            'classification_reasons' => $result->reasons,
            'classified_at' => now(),
        ])->save();

        if ($postApproved && $result->status === 'auto_approved') {
            $this->posting->post($transaction->refresh());
        }

        return $transaction->refresh();
    }
}
