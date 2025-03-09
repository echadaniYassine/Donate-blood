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
    // Auth routes for registration, login, and logout
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Donor Routes
    Route::middleware('auth:sanctum')->prefix('donors')->group(function () {
        Route::get('profile', [DonorController::class, 'viewProfile']);  // View donor profile
        Route::put('profile', [DonorController::class, 'updateProfile']); // Update donor profile
        Route::post('apply', [DonorController::class, 'applyForDonation']); // Apply for donation
    });

    // Hospital Routes
    Route::middleware('auth:sanctum')->prefix('hospitals')->group(function () {
        Route::get('profile', [HospitalController::class, 'viewProfile']);  // View hospital profile
        Route::put('profile', [HospitalController::class, 'updateProfile']); // Update hospital profile
    });

    // Donation Request Routes
    Route::resource('donation-requests', DonationRequestController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    // Donation History Routes
    Route::resource('donation-history', DonationHistoryController::class)->only(['index', 'show']);

    // Blood Stock Routes
    Route::resource('blood-stocks', BloodStockController::class)->only(['index', 'show', 'update']);

    // Donation Application Routes
    Route::resource('donation-applications', DonationApplicationController::class)->only(['index', 'show', 'store']);

    // Notification Routes
    Route::get('notifications', [NotificationController::class, 'index']); // Get all notifications
});