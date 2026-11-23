<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DestinationController;
use Illuminate\Support\Facades\Route;

// Auth Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public API Destinasi
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{slug}', [DestinationController::class, 'show']);

// Public API Booking
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{kode_booking}', [BookingController::class, 'show']);

// Public API Kontak
Route::post('/contact', [ContactController::class, 'store']);

// Protected API (Wajib kirim Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});