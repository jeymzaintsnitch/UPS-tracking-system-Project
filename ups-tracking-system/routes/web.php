<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShippedItemController;
use App\Http\Controllers\RetailCenterController;
use App\Http\Controllers\TransportationEventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Landing page accessible to all visitors.
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Requires Login & Email Verification)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Core Entity CRUD (Admin & Staff) ──────────────────────────
    Route::resource('shipped-items', ShippedItemController::class);
    Route::resource('retail-centers', RetailCenterController::class);
    Route::resource('transportation-events', TransportationEventController::class);

    // ── User Profile ──────────────────────────────────────────────
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // ── Admin-Only Routes ─────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/authentication-logs', [App\Http\Controllers\AuthenticationLogController::class, 'index'])->name('authentication-logs.index');
    });

});

/*
|--------------------------------------------------------------------------
| Authentication Scaffolding (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
