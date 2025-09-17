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

// API routes untuk lokasi check-in
Route::post('/api/fleet/{fleet}/checkin', [LocationCheckInController::class, 'store']);
Route::get('/api/fleet/{fleet}/location', [LocationCheckInController::class, 'show']);
