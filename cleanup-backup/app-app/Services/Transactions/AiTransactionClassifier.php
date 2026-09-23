<?php

namespace App\Services\Transactions;

use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Http;

class AiTransactionClassifier
{
    public function classify(FinancialTransaction $transaction, array $projectCandidates = []): ?ClassificationResult
    {
        if (!config('transaction_classifier.ai.enabled') || !config('transaction_classifier.ai.api_key')) {
            return null;
        }

        $categories = config('transaction_classifier.categories', []);
        $prompt = [
            'task' => 'Classify a construction company financial transaction.',
            'rules' => [
                'Return only JSON.',
                'Do not invent a project.',
                'Use uncategorized_review when uncertain.',
                'Personal charges and transfers must not be posted to project costs.',
            ],
            'allowed_categories' => $categories,
            'transaction' => [
                'date' => optional($transaction->transaction_date)->toDateString(),
                'merchant' => $transaction->merchant,
                'description' => $transaction->description,
                'amount' => (float) $transaction->amount,
                'original_category' => $transaction->original_category,
            ],
            'project_candidates' => $projectCandidates,
            'response_schema' => [
                'category' => 'one allowed category',
                'confidence' => 'number between 0 and 1',
                'project_id' => 'integer or null',
                'reason' => 'brief explanation',
            ],
        ];

        $response = Http::withToken(config('transaction_classifier.ai.api_key'))
            ->timeout(30)
            ->post(rtrim(config('transaction_classifier.ai.base_url'), '/').'/responses', [
                'model' => config('transaction_classifier.ai.model'),
                'input' => json_encode($prompt, JSON_PRETTY_PRINT),
            ]);

        if (!$response->successful()) {
            return null;
        }

        $text = data_get($response->json(), 'output.0.content.0.text');
        $data = is_string($text) ? json_decode($text, true) : null;
        if (!is_array($data) || !in_array($data['category'] ?? null, $categories, true)) {
            return null;
        }

        $confidence = min(0.89, max(0.0, (float) ($data['confidence'] ?? 0.50)));
        return new ClassificationResult(
            category: $data['category'],
            confidence: $confidence,
            projectId: isset($data['project_id']) ? (int) $data['project_id'] : null,
            reasons: ['AI fallback: '.($data['reason'] ?? 'No reason supplied.')],
            status: $confidence >= config('transaction_classifier.review_threshold', 0.65) ? 'needs_review' : 'unclassified',
        );
    }
}
