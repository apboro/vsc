<?php

use App\Http\Controllers\Leads\LeadContractController;
use App\Http\Controllers\Leads\LeadNewController;
use App\Http\Controllers\Leads\LeadFormController;
use App\Http\Controllers\Leads\LeadInitController;
use Illuminate\Support\Facades\Route;

Route::get('/leads/form', [LeadFormController::class, 'index'])->name('leads.subscription.contract.fill');

Route::middleware(['leads'])->group(function () {
    Route::post('/leads/init', [LeadInitController::class, 'init']);
    Route::post('/leads/send', [LeadNewController::class, 'send'])->middleware('leads.protect');
    Route::post('/leads/contract', [LeadContractController::class, 'contract'])->middleware('leads.protect');
});
