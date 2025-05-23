php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\LawyerController;
use App\Http\Controllers\AppointmentController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('v1')->group(function () {
    Route::apiResource('firms', FirmController::class);
    Route::apiResource('lawyers', LawyerController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::get('/dashboard-stats', [AppointmentController::class, 'dashboardStats']);
});