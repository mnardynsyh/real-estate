<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\HousingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('housing', HousingController::class);
    Route::resource('units', UnitController::class);

    // Transaksi (Custom Routes untuk masing-masing status)
    Route::prefix('transactions')->name('transactions.')->group(function () {
        // Halaman Utama Riwayat
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        
        // Halaman Verifikasi Booking (Pending)
        Route::get('/booking', [TransactionController::class, 'bookingVerification'])->name('booking');
        
        // Halaman Verifikasi Berkas
        Route::get('/documents', [TransactionController::class, 'documentVerification'])->name('documents');
        
        // Halaman Approval & DP
        Route::get('/approval', [TransactionController::class, 'approval'])->name('approval');
    });

    // Users Management
    Route::resource('customers', CustomerController::class);

});