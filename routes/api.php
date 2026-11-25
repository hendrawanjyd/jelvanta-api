<?php

use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Api\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DestinationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{slug}', [DestinationController::class, 'show']);

Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{kode_booking}', [BookingController::class, 'show']);

Route::post('/contact', [ContactController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/destinations', [AdminDestinationController::class, 'index']);
    Route::post('/destinations', [AdminDestinationController::class, 'store']);
    Route::get('/destinations/{id}', [AdminDestinationController::class, 'show']);
    Route::put('/destinations/{id}', [AdminDestinationController::class, 'update']);
    Route::delete('/destinations/{id}', [AdminDestinationController::class, 'destroy']);

    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show']);
    Route::patch('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus']);
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy']);

    Route::get('/contacts', [AdminContactMessageController::class, 'index']);
    Route::patch('/contacts/{id}/status', [AdminContactMessageController::class, 'updateStatus']);
    Route::delete('/contacts/{id}', [AdminContactMessageController::class, 'destroy']);
});