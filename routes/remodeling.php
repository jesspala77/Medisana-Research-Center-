<?php

use App\Http\Controllers\ProjectEstimateController;
use App\Http\Controllers\RemodelingController;
use App\Http\Controllers\SiteVisitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'synnexus.module:remodeling'])->group(function () {
    Route::get('/remodeling', [RemodelingController::class, 'dashboard'])->name('remodeling.dashboard');
    Route::get('/remodeling/projects', [RemodelingController::class, 'index'])->name('remodeling.projects.index');
    Route::get('/remodeling/projects/create', [RemodelingController::class, 'create'])->name('remodeling.projects.create');
    Route::post('/remodeling/projects', [RemodelingController::class, 'store'])->name('remodeling.projects.store');
    Route::get('/remodeling/projects/{project}', [RemodelingController::class, 'show'])->name('remodeling.projects.show');
    Route::post('/remodeling/projects/{project}/stage', [RemodelingController::class, 'updateStage'])->name('remodeling.projects.stage');
    Route::post('/remodeling/projects/{project}/site-visits', [SiteVisitController::class, 'store'])->name('remodeling.site-visits.store');
    Route::post('/remodeling/site-visits/{siteVisit}/complete', [SiteVisitController::class, 'complete'])->name('remodeling.site-visits.complete');
    Route::post('/remodeling/projects/{project}/estimates', [ProjectEstimateController::class, 'store'])->name('remodeling.estimates.store');
    Route::post('/remodeling/estimates/{estimate}/line-items', [ProjectEstimateController::class, 'addLineItem'])->name('remodeling.estimates.line-items.store');
    Route::post('/remodeling/estimates/{estimate}/sent', [ProjectEstimateController::class, 'markSent'])->name('remodeling.estimates.sent');
    Route::post('/remodeling/estimates/{estimate}/accepted', [ProjectEstimateController::class, 'markAccepted'])->name('remodeling.estimates.accepted');
});
