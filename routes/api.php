<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DestinationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Destinasi
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{slug}', [DestinationController::class, 'show']);

// Public API Booking
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{kode_booking}', [BookingController::class, 'show']);

// Public API Kontak
Route::post('/contact', [ContactController::class, 'store']);