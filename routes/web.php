<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationCheckInController;

// routes/web.php
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/track', [ShipmentController::class, 'track'])->name('shipments.track');
Route::resource('fleets', FleetController::class);
Route::resource('shipments', ShipmentController::class);
Route::resource('orders', OrderController::class);

// Location Check-In Routes
Route::prefix('location')->group(function () {
    Route::get('/map', [LocationCheckInController::class, 'map'])->name('location.map');
    Route::get('/checkin', [LocationCheckInController::class, 'checkInForm'])->name('location.checkin');
    Route::post('/checkin', [LocationCheckInController::class, 'store'])->name('location.store');
    Route::get('/fleet/{fleet}/history', [LocationCheckInController::class, 'show'])->name('location.history');
    Route::get('/report', [LocationCheckInController::class, 'report'])->name('location.report');
});

// API routes untuk lokasi check-in
Route::prefix('api')->group(function () {
    Route::post('/fleet/{fleet}/checkin', [LocationCheckInController::class, 'store']);
    Route::get('/fleet/{fleet}/location', [LocationCheckInController::class, 'getFleetLocation']);
    Route::get('/locations', [LocationCheckInController::class, 'getAllLocations']);
});
