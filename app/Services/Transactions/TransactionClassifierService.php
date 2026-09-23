<?php

namespace App\Services\Transactions;

use App\Models\ConstructionProject;
use App\Models\FinancialTransaction;
use Illuminate\Support\Str;

class TransactionClassifierService
{
    public function classify(FinancialTransaction $transaction): ClassificationResult
    {
        $merchant = Str::lower(trim((string) $transaction->merchant));
        $description = Str::lower(trim((string) $transaction->description));
        $candidates = [];
        $reasons = [];

        foreach (config('transaction_classifier.merchant_rules', []) as $needle => $rule) {
            if ($merchant !== '' && Str::contains($merchant, Str::lower($needle))) {
                $candidates[] = $rule;
                $reasons[] = "Merchant matched rule: {$needle}";
            }
        }

        foreach (config('transaction_classifier.description_rules', []) as $needle => $rule) {
            if ($description !== '' && Str::contains($description, Str::lower($needle))) {
                $candidates[] = $rule;
                $reasons[] = "Description matched rule: {$needle}";
            }
        }

        [$projectId, $projectConfidence, $projectReason] = $this->matchProject($transaction);
        if ($projectReason) {
            $reasons[] = $projectReason;
        }

        if (empty($candidates)) {
            $category = 'uncategorized_review';
            $confidence = max(0.30, $projectConfidence - 0.15);
            $reasons[] = 'No deterministic merchant or description rule matched.';
        } else {
            usort($candidates, fn ($a, $b) => $b['confidence'] <=> $a['confidence']);
            $category = $candidates[0]['category'];
            $confidence = (float) $candidates[0]['confidence'];
        }

        if ($projectId && Str::startsWith($category, 'direct_')) {
            $confidence = min(0.99, $confidence + ($projectConfidence * 0.10));
        }

        $status = $this->statusFor($confidence);

        return new ClassificationResult(
            category: $category,
            confidence: round($confidence, 4),
            projectId: $projectId,
            reasons: $reasons,
            status: $status,
        );
    }

    public function apply(FinancialTransaction $transaction): FinancialTransaction
    {
        $result = $this->classify($transaction);

        $transaction->forceFill([
            'construction_project_id' => $result->projectId,
            'suggested_category' => $result->category,
            'classification_confidence' => $result->confidence,
            'classification_status' => $result->status,
            'classification_reasons' => $result->reasons,
            'classified_at' => now(),
        ])->save();

        return $transaction->refresh();
    }

    private function statusFor(float $confidence): string
    {
        if ($confidence >= config('transaction_classifier.auto_approve_threshold', 0.92)) {
            return 'auto_approved';
        }

        if ($confidence >= config('transaction_classifier.review_threshold', 0.65)) {
            return 'needs_review';
        }

        return 'unclassified';
    }

    private function matchProject(FinancialTransaction $transaction): array
    {
        if (!$transaction->transaction_date) {
            return [null, 0.0, null];
        }

        $days = config('transaction_classifier.lookback_days_for_project_match', 45);
        $text = Str::lower(trim($transaction->merchant.' '.$transaction->description));

        $projects = ConstructionProject::query()
            ->whereDate('created_at', '<=', $transaction->transaction_date)
            ->whereDate('created_at', '>=', $transaction->transaction_date->copy()->subDays($days))
            ->latest('created_at')
            ->limit(50)
            ->get();

        $best = null;
        $bestScore = 0.0;

        foreach ($projects as $project) {
            $tokens = collect([
                $project->name ?? null,
                $project->client_name ?? null,
                $project->address ?? null,
            ])->filter()->flatMap(fn ($value) => preg_split('/\s+/', Str::lower($value)))
              ->filter(fn ($token) => strlen($token) >= 4)
              ->unique();

            $matches = $tokens->filter(fn ($token) => Str::contains($text, $token))->count();
            $score = $tokens->count() > 0 ? $matches / $tokens->count() : 0;

            if ($score > $bestScore) {
                $best = $project;
                $bestScore = $score;
            }
        }

        if (!$best || $bestScore < 0.20) {
            return [null, 0.0, null];
        }

        return [$best->id, min(0.90, 0.45 + $bestScore), "Matched likely project: {$best->id}"];
    }
}
