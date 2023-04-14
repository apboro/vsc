<?php

use App\Http\Controllers\API\NotFoundController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware(['api', 'auth:sanctum'])->group(function () {

    require base_path('routes/api/dictionaries.php');
    require base_path('routes/api/roles.php');
    require base_path('routes/api/staff.php');
    require base_path('routes/api/training_base.php');
    require base_path('routes/api/settings.php');
    require base_path('routes/api/organizations.php');
    require base_path('routes/api/contracts.php');
    require base_path('routes/api/letters.php');
    require base_path('routes/api/services.php');
    require base_path('routes/api/leads.php');
    require base_path('routes/api/clients.php');
    require base_path('routes/api/subscriptions.php');

    Route::any('{any}', [NotFoundController::class, 'notFound'])->where('any', '.*');
});

