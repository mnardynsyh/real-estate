<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HousingController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Auth\LoginController as AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROUTE ADMIN ---
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('housing', HousingController::class);
    Route::resource('units', UnitController::class);

    // Transaksi
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/booking', [TransactionController::class, 'bookingVerification'])->name('booking');
        Route::get('/documents', [TransactionController::class, 'documentVerification'])->name('documents');
        Route::get('/approval', [TransactionController::class, 'approval'])->name('approval');
    });

    // Users
    Route::resource('customers', CustomerController::class);
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->middleware(['auth', 'role:customer'])->name('customer.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

    // Route lain menyusul (Profile, Transaksi, dll)
    // Route::get('/profile', ...)->name('profile');
    
});