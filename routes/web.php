<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:user')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::post('/bookings/{booking}/payment', [BookingController::class, 'uploadPayment'])->name('bookings.payment');
        Route::get('/api/slots', [BookingController::class, 'getSlots'])->name('api.slots');
        Route::get('/api/promos/check', [PromoController::class, 'check'])->name('api.promos.check');
    });

    Route::middleware('role:admin_kasir')->prefix('admin')->group(function () {
        Route::get('/reports/orders', [ReportController::class, 'exportOrders'])->name('admin.reports.orders');
        Route::post('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
            ->name('admin.bookings.status');
    });

    Route::middleware('role:owner')->prefix('owner')->group(function () {
        Route::get('/reports/orders', [ReportController::class, 'exportOrders'])->name('owner.reports.orders');
        Route::get('/reports/revenue', [ReportController::class, 'exportRevenue'])->name('owner.reports.revenue');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('owner.users.destroy');
        Route::post('/promos', [PromoController::class, 'store'])->name('owner.promos.store');
        Route::put('/promos/{promo}', [PromoController::class, 'update'])->name('owner.promos.update');
        Route::delete('/promos/{promo}', [PromoController::class, 'destroy'])->name('owner.promos.destroy');
    });
});
