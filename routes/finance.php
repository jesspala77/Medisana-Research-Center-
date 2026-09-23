<?php

use App\Http\Controllers\TransactionReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/finance/transactions/review', [TransactionReviewController::class, 'index'])
        ->name('transactions.review');
});
