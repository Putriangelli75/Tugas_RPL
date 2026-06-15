<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\LapanganController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Lapangan read routes (available for all logged in users)
    Route::get('/lapangan', [LapanganController::class, 'index']);
    Route::get('/lapangan/{lapangan}', [LapanganController::class, 'show']);

    // Booking user routes
    Route::post('/booking', [BookingController::class, 'store']);
    Route::get('/riwayat-booking', [BookingController::class, 'riwayat']);
    Route::post('/booking/{booking}/upload-bukti', [BookingController::class, 'upload']);

    // Admin routes
    Route::middleware('admin')->group(function (): void {
        // Lapangan write routes
        Route::post('/lapangan', [LapanganController::class, 'store']);
        Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update']);
        Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy']);

        // Admin booking routes
        Route::get('/admin/bookings', [BookingController::class, 'allBookings']);
        Route::post('/booking/{booking}/verifikasi', [BookingController::class, 'verifikasi']);
        Route::get('/admin/stats', [BookingController::class, 'stats']);
    });
});
