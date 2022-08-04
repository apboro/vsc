<?php

use App\Http\Controllers\Leads\LeadController;
use App\Http\Controllers\Leads\LeadFormController;
use Illuminate\Support\Facades\Route;

Route::get('/leads/form', [LeadFormController::class, 'index'])->name('leads.subscription.contract.fill');

Route::middleware(['leads'])->group(function () {
    Route::post('/leads/init', [LeadController::class, 'init']);
    Route::post('/leads/send', [LeadController::class, 'send'])->middleware('leads.protect');
});
