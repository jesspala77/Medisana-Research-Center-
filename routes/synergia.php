<?php

use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\AiOutreachController;
use App\Http\Controllers\BailBondAgencyImportController;
use App\Http\Controllers\ClinicalRegulatoryController;
use App\Http\Controllers\CommandQueueController;
use App\Http\Controllers\CompanyProgramController;
use App\Http\Controllers\LeadModuleController;
use App\Http\Controllers\LeadWorkflowController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/admin/user-access', [UserAccessController::class, 'index'])
        ->middleware('synnexus.admin')
        ->name('admin.user-access.index');

    Route::post('/admin/user-access/{user}', [UserAccessController::class, 'update'])
        ->middleware('synnexus.admin')
        ->name('admin.user-access.update');

    Route::get('/command-queue', [CommandQueueController::class, 'index'])
        ->middleware('synnexus.admin')
        ->name('command-queue.index');

    Route::post('/leads/{lead}/workflow/{workflowAction}', [LeadWorkflowController::class, 'apply'])
        ->middleware('synnexus.admin')
        ->name('leads.workflow.apply');

    Route::get('/programs/{program}', [CompanyProgramController::class, 'show'])
        ->name('programs.show');

    Route::get('/bond-agency/import', [BailBondAgencyImportController::class, 'create'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.import');

    Route::post('/bond-agency/import', [BailBondAgencyImportController::class, 'store'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.import.store');

    Route::get('/bond-agency/outreach', [AiOutreachController::class, 'index'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.index');

    Route::get('/bond-agency/outreach/export', [AiOutreachController::class, 'export'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.export');

    Route::get('/bond-agency/outreach/signature', [AiOutreachController::class, 'signature'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.signature');

    Route::post('/bond-agency/outreach/send-approved', [AiOutreachController::class, 'sendApproved'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.send-approved');

    Route::post('/bond-agency/outreach/{aiOutreachMessage}/approve', [AiOutreachController::class, 'approve'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.approve');

    Route::post('/bond-agency/outreach/{aiOutreachMessage}', [AiOutreachController::class, 'update'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.update');

    Route::post('/bond-agency/outreach/{aiOutreachMessage}/send', [AiOutreachController::class, 'send'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.send');

    Route::post('/bond-agency/outreach/{aiOutreachMessage}/sent', [AiOutreachController::class, 'markSent'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.sent');

    Route::post('/bond-agency/outreach/{aiOutreachMessage}/replied', [AiOutreachController::class, 'markReplied'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.replied');

    Route::post('/bond-agency/outreach/{aiOutreachMessage}/opt-out', [AiOutreachController::class, 'optOut'])
        ->middleware('synnexus.module:bond-agency')
        ->name('bond-agency.outreach.opt-out');

    Route::prefix('clinical-regulatory')
        ->name('clinical-regulatory.')
        ->controller(ClinicalRegulatoryController::class)
        ->middleware('synnexus.module:clinical-recruitment')
        ->group(function () {
            Route::get('/', 'dashboard')->name('dashboard');
            Route::get('/records', 'index')->name('records.index');
            Route::get('/records/create', 'create')->name('records.create');
            Route::post('/records', 'store')->name('records.store');
            Route::get('/records/{regulatoryServiceRecord}', 'show')->name('records.show');
            Route::post('/records/{regulatoryServiceRecord}/status', 'updateStatus')->name('records.status');
        });

    foreach (['capital-funding', 'bond-agency', 'clinical-recruitment', 'cnc-quote'] as $module) {
        Route::prefix($module)
            ->name($module.'.')
            ->controller(LeadModuleController::class)
            ->middleware('synnexus.module:'.$module)
            ->group(function () use ($module) {
                Route::get('/', 'dashboard')->defaults('module', $module)->name('dashboard');
                Route::get('/leads', 'index')->defaults('module', $module)->name('leads.index');
                Route::get('/leads/create', 'create')->defaults('module', $module)->name('leads.create');
                Route::post('/leads', 'store')->defaults('module', $module)->name('leads.store');
                Route::get('/leads/{lead}', 'show')->defaults('module', $module)->name('leads.show');
                Route::post('/leads/{lead}/status', 'updateStatus')->defaults('module', $module)->name('leads.status');
            });
    }
});
