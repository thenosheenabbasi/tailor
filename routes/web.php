<?php

use App\Http\Controllers\Admin\TailorOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:admin,manager')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/orders', [TailorOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [TailorOrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [TailorOrderController::class, 'store'])->name('orders.store');

        Route::middleware('role:admin')->group(function (): void {
            Route::get('/orders/{tailorOrder}/edit', [TailorOrderController::class, 'edit'])->name('orders.edit');
            Route::patch('/orders/{tailorOrder}', [TailorOrderController::class, 'update'])->name('orders.update');
            Route::post('/orders/{tailorOrder}/complete', [TailorOrderController::class, 'complete'])->name('orders.complete');
            Route::patch('/orders/{tailorOrder}/status', [TailorOrderController::class, 'updateStatus'])->name('orders.update-status');
            Route::delete('/orders/{tailorOrder}', [TailorOrderController::class, 'destroy'])->name('orders.destroy');
            Route::get('/orders/{tailorOrder}/receipt', [TailorOrderController::class, 'receipt'])->name('orders.receipt');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        });
    });
});
