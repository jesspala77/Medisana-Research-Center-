<?php

use App\Http\Controllers\TransactionImportController;
use App\Http\Controllers\TransactionReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('transactions')->group(function () {
    Route::post('/imports', [TransactionImportController::class, 'store']);
    Route::get('/review', [TransactionReviewController::class, 'index']);
    Route::post('/{transaction}/reclassify', [TransactionReviewController::class, 'reclassify']);
    Route::post('/{transaction}/approve', [TransactionReviewController::class, 'approve']);
});
