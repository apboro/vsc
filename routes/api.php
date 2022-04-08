<?php

use App\Http\Controllers\API\NotFoundController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware(['api', 'auth:sanctum'])->group(function () {

    require base_path('routes/api/dictionaries.php');
    require base_path('routes/api/roles.php');
    require base_path('routes/api/staff.php');
    require base_path('routes/api/settings.php');

    Route::any('{any}', [NotFoundController::class, 'notFound'])->where('any', '.*');
});

