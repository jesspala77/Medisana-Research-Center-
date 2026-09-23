<?php

namespace App\Console\Commands;

use App\Models\FinancialTransaction;
use App\Services\Transactions\TransactionClassifierService;
use Illuminate\Console\Command;

class ClassifyFinancialTransactions extends Command
{
    protected $signature = 'transactions:classify {--limit=500}';
    protected $description = 'Classify pending financial transactions and route uncertain items to review.';

    public function handle(TransactionClassifierService $service): int
    {
        FinancialTransaction::query()
            ->where('classification_status', 'pending')
            ->orderBy('id')
            ->limit((int) $this->option('limit'))
            ->each(function (FinancialTransaction $transaction) use ($service) {
                $service->apply($transaction);
                $this->line("Classified transaction {$transaction->id}");
            });

        return self::SUCCESS;
    }
}
