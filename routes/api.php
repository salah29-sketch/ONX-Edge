<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — onx-edge Flutter App
|--------------------------------------------------------------------------
*/

// ── تسجيل دخول موحّد ─────────────────────────────────────────────────────────
// العميل
Route::post('/client/login',  [App\Http\Controllers\Api\ClientAuthController::class, 'login'])->middleware('throttle:10,1');
// المدير والعامل
Route::post('/admin/login',   [App\Http\Controllers\Api\AdminAuthController::class, 'login'])->middleware('throttle:10,1');

// ── Smart Booking API (للعملاء والزوار) ──────────────────────────────────────
Route::prefix('smart-booking')->group(function () {
    Route::get('/init',         [App\Http\Controllers\Api\SmartBookingController::class, 'init']);
    Route::get('/services',     [App\Http\Controllers\Api\SmartBookingController::class, 'services']);
    Route::get('/packages',     [App\Http\Controllers\Api\SmartBookingController::class, 'packages']);
    Route::get('/venues',       [App\Http\Controllers\Api\SmartBookingController::class, 'venues']);
    Route::get('/availability', [App\Http\Controllers\Api\SmartBookingController::class, 'availability']);
    Route::post('/price',       [App\Http\Controllers\Api\SmartBookingController::class, 'price'])->middleware('throttle:60,1');
    Route::post('/promo',       [App\Http\Controllers\Api\SmartBookingController::class, 'promo'])->middleware('throttle:20,1');
    Route::post('/submit',      [App\Http\Controllers\Api\SmartBookingController::class, 'submit'])->middleware('throttle:10,1');
});

// ── مسارات العميل المحمية ────────────────────────────────────────────────────
Route::middleware('auth:api')->prefix('client')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\ClientAuthController::class, 'logout']);
    Route::get('/me',      [App\Http\Controllers\Api\ClientAuthController::class, 'me']);

    // الحجوزات
    Route::get('/bookings',         [App\Http\Controllers\Api\ClientBookingController::class, 'index']);
    Route::get('/bookings/{id}',    [App\Http\Controllers\Api\ClientBookingController::class, 'show']);
});

// ── مسارات المدير والعامل المحمية ────────────────────────────────────────────
Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\AdminAuthController::class, 'logout']);

    // الحجوزات
    Route::get('/bookings',          [App\Http\Controllers\Api\AdminBookingController::class, 'index']);
    Route::post('/bookings',         [App\Http\Controllers\Api\AdminBookingController::class, 'store']);
    Route::put('/bookings/{id}',     [App\Http\Controllers\Api\AdminBookingController::class, 'update']);
    Route::patch('/bookings/{id}/status', [App\Http\Controllers\Api\AdminBookingController::class, 'updateStatus']);
});