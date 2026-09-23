<?php

use App\Http\Controllers\CommandQueueController;
use App\Http\Controllers\LeadWorkflowController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/command-queue', [CommandQueueController::class, 'index'])
        ->name('command-queue.index');

    Route::post('/leads/{lead}/workflow/{workflowAction}', [LeadWorkflowController::class, 'apply'])
        ->name('leads.workflow.apply');
});
