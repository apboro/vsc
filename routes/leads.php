<?php

use App\Http\Controllers\Leads\LeadController;
use Illuminate\Support\Facades\Route;

Route::prefix('leads')->group(function () {
    Route::post('init', [LeadController::class, 'init']);
    Route::post('send', [LeadController::class, 'send'])->middleware('leads.protect');
});
