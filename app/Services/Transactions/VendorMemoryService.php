<?php

namespace App\Services\Transactions;

use App\Models\FinancialTransaction;
use App\Models\TransactionVendorRule;
use Illuminate\Support\Str;

class VendorMemoryService
{
    public function match(FinancialTransaction $transaction): ?ClassificationResult
    {
        $vendor = $this->normalize($transaction->merchant ?: $transaction->description);
        if ($vendor === '') {
            return null;
        }

        $rules = TransactionVendorRule::query()->where('active', true)->orderByDesc('confidence')->get();
        foreach ($rules as $rule) {
            $matched = match ($rule->match_type) {
                'exact' => $vendor === $rule->match_value,
                'starts_with' => Str::startsWith($vendor, $rule->match_value),
                default => Str::contains($vendor, $rule->match_value),
            };
            if ($matched) {
                return new ClassificationResult(
                    category: $rule->category,
                    confidence: min(0.99, (float) $rule->confidence),
                    projectId: $rule->construction_project_id,
                    reasons: ["Learned vendor rule matched: {$rule->match_value}"],
                    status: 'auto_approved',
                );
            }
        }
        return null;
    }

    public function learn(FinancialTransaction $transaction, string $category, ?int $projectId = null): void
    {
        $vendor = $this->normalize($transaction->merchant ?: $transaction->description);
        if (strlen($vendor) < 3) {
            return;
        }

        $rule = TransactionVendorRule::firstOrNew([
            'match_type' => 'contains',
            'match_value' => $vendor,
            'category' => $category,
        ]);
        $rule->normalized_vendor = $vendor;
        $rule->construction_project_id = $projectId;
        $rule->times_confirmed = ($rule->times_confirmed ?: 0) + 1;
        $rule->confidence = min(0.99, 0.80 + ($rule->times_confirmed * 0.03));
        $rule->active = true;
        $rule->save();
    }

    private function normalize(?string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', Str::lower(preg_replace('/[^a-zA-Z0-9 ]/', ' ', (string) $value))));
    }
}
