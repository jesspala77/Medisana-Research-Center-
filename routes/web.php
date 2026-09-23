<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CommandCenterController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Http\Controllers\SynNexusIntakeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::get('/auth/microsoft', [MicrosoftAuthController::class, 'redirect'])
    ->name('microsoft.redirect');

Route::get('/auth/microsoft/callback', [MicrosoftAuthController::class, 'callback'])
    ->name('microsoft.callback');

Route::get('/intake/cnc-quote', [SynNexusIntakeController::class, 'create'])
    ->name('intake.cnc.create');

Route::post('/intake/cnc-quote', [SynNexusIntakeController::class, 'store'])
    ->name('intake.cnc.store');

Route::get('/dashboard', [CommandCenterController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::post('/calendar/events', [CalendarController::class, 'store'])->name('calendar.events.store');
});

require __DIR__.'/auth.php';
require __DIR__.'/synergia.php';
require __DIR__.'/remodeling.php';
require __DIR__.'/finance.php';