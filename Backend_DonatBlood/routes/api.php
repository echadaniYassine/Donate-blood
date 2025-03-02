<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DonationRequestController;
use App\Http\Controllers\DonationHistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/forgot', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('donors', DonorController::class);
    Route::apiResource('recipients', RecipientController::class);
    Route::apiResource('hospitals', HospitalController::class);
    Route::apiResource('donation-requests', DonationRequestController::class);
    Route::apiResource('donation-histories', DonationHistoryController::class);
    Route::apiResource('notifications', NotificationController::class);
});
