<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// =====================================
// PUBLIC ROUTES (Tidak perlu login)
// =====================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// =====================================
// USER PROTECTED ROUTES
// =====================================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/', [OrderController::class, 'home'])->name('home');
    Route::get('/home', [OrderController::class, 'home']);

    // ORDERS
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // SALIP ANTRIAN
    Route::get('/salip-antrian', [OrderController::class, 'showSalipQueue'])
        ->name('orders.salip');
    Route::post('/salip-antrian', [OrderController::class, 'skipQueue'])
        ->name('orders.skip');

    // PEMBAYARAN DP
    Route::get('/pembayaran-dp', [OrderController::class, 'showDownPayment'])
        ->name('orders.dp');
    Route::post('/pembayaran-dp', [OrderController::class, 'uploadDp'])
        ->name('orders.uploadDp');

    // API LAIN
    Route::get('/api/calculate-price', [OrderController::class, 'calculatePriceApi'])
        ->name('api.calculate-price');

    // MISC
    Route::get('/about', [OrderController::class, 'about'])->name('about');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// =====================================
// ADMIN ROUTES
// =====================================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Orders
        Route::get('/orders', [AdminController::class, 'orders'])
            ->name('orders');
        Route::post('/orders/delete/{id}', [AdminController::class, 'deleteOrder'])
            ->name('orders.delete');
        Route::get('/orders/{id}/detail', [AdminController::class, 'getDetail'])->name('detail');
        Route::get('/orders/{id}/print', [AdminController::class, 'print'])->name('print');
        Route::get('/orders/{id}/download-image', [AdminController::class, 'downloadImage'])->name('download-image');
        Route::get('/orders/export', [AdminController::class, 'export'])->name('export');
        Route::post('/orders/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.orders.update-status');
        // Users
        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');

        Route::post('/users/delete/{id}', [AdminController::class, 'deleteUser'])
            ->name('users.delete');

        // Skip Queue Requests
        Route::get('/skip-queue/requests', [AdminController::class, 'skipQueueRequests'])
            ->name('skip-queue.requests');

        Route::post('/skip-queue/approve', [AdminController::class, 'approveSkipQueue'])
            ->name('skip-queue.approve');

        // Dp
        Route::get('/dp/verify', [AdminController::class, 'dpVerificationList'])
            ->name('dp.list');

        Route::post('/dp/verify', [AdminController::class, 'verifyDp'])
            ->name('dp.verify');
    });
