<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DonationRequestController;
use App\Http\Controllers\DonationHistoryController;
use App\Http\Controllers\BloodStockController;
use App\Http\Controllers\DonationApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::prefix('auth')->group(function () {
    // Authentication Routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

    // Forgot Password & Reset Routes
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {

    // ✅ Donor Routes
    Route::prefix('donors')->group(function () {
        Route::get('profile', [DonorController::class, 'viewProfile']);
        Route::put('profile', [DonorController::class, 'updateProfile']);
        // ✅ Donation Application Routes (Moved apply logic here)
        Route::resource('donation-applications', DonationApplicationController::class)->only(['index', 'show', 'store']);
    });

    // ✅ Hospital Routes
    Route::prefix('hospitals')->group(function () {
        Route::get('profile', [HospitalController::class, 'viewProfile']);
        Route::put('profile', [HospitalController::class, 'updateProfile']);
    });

    // ✅ Donation Request Routes
    Route::resource('donation-requests', DonationRequestController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    // ✅ Define a route for marking a donation as donated
    Route::post('donation-applications/{applicationId}/mark-as-donated', [DonationHistoryController::class, 'markAsDonated']);
    
    // ✅ Blood Stock Routes
    Route::get('blood-stocks', [BloodStockController::class, 'index']);
    Route::get('blood-stocks/{bloodStock}', [BloodStockController::class, 'show']);
    Route::put('blood-stocks/{bloodStock}', [BloodStockController::class, 'update']);



    // ✅ Notifications Route 
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::put('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });
