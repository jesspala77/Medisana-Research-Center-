<?php

namespace App\Jobs;

use App\Models\FinancialTransaction;
use App\Services\Transactions\TransactionClassificationEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClassifyFinancialTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $transactionId) {}

    public function handle(TransactionClassificationEngine $engine): void
    {
        $transaction = FinancialTransaction::find($this->transactionId);
        if ($transaction) {
            $engine->process($transaction);
        }
    }
}
