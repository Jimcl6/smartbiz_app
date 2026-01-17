<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user'])->middleware('auth');
});

// Protected API Routes
Route::middleware('auth')->group(function () {

    // Clients API
    Route::apiResource('clients', ClientController::class);

    // Services API
    Route::apiResource('services', ServiceController::class);

    // Appointments API
    Route::apiResource('appointments', AppointmentController::class);
    Route::post('appointments/{appointment}/services/{service}', [AppointmentController::class, 'attachService']);
    Route::delete('appointments/{appointment}/services/{service}', [AppointmentController::class, 'detachService']);

    // Payments API
    Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);
    Route::post('payments', [PaymentController::class, 'store']);

    // Dashboard API
    Route::prefix('dashboard')->group(function () {
        Route::get('summary', [DashboardController::class, 'summary']);
        Route::get('monthly-revenue', [DashboardController::class, 'monthlyRevenue']);
        Route::get('upcoming-appointments', [DashboardController::class, 'upcomingAppointments']);
    });

    // Activity Logs API
    Route::apiResource('activity-logs', ActivityLogController::class)->only(['index']);
});
