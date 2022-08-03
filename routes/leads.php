<?php

use App\Http\Controllers\Leads\LeadController;
use App\Http\Controllers\Leads\LeadFormController;
use Illuminate\Support\Facades\Route;

Route::prefix('leads')->middleware('leads')->group(function () {
    Route::get('form', [LeadFormController::class, 'index'])->name('leads.subscription.contract.fill');
    Route::post('init', [LeadController::class, 'init']);
    Route::post('send', [LeadController::class, 'send'])->middleware('leads.protect');
});
