<?php

namespace App\Services\Transactions;

final class ClassificationResult
{
    public function __construct(
        public readonly string $category,
        public readonly float $confidence,
        public readonly ?int $projectId,
        public readonly array $reasons,
        public readonly string $status,
    ) {}
}
