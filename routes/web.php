<?php

use App\Http\Controllers\MedisanaResearchCenterController;
use Illuminate\Support\Facades\Route;

Route::get('/{page?}', MedisanaResearchCenterController::class)
    ->name('medisana.standalone');

Route::get('/medisana-research-center/{page?}', MedisanaResearchCenterController::class)
    ->name('medisana.research-center');