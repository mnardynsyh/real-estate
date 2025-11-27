<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HousingController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Auth\LoginController as AuthController;
use App\Http\Controllers\Auth\RegisterController as RegisterController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\HomeController;

// ================= PUBLIC ROUTES =================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [HomeController::class, 'catalog'])->name('catalog');
Route::get('/unit/{id}', [HomeController::class, 'show'])->name('unit.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// --- ROUTE ADMIN ---
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('housing', HousingController::class);
    Route::resource('units', UnitController::class);

    // 3. MANAJEMEN TRANSAKSI
    Route::prefix('transactions')->name('transactions.')->group(function () {
        
        // Halaman List Verifikasi Booking
        Route::get('/booking', [TransactionController::class, 'bookingVerification'])->name('booking');
        
        // Action: Approve & Reject
        Route::patch('/booking/{id}/approve', [TransactionController::class, 'approveBooking'])->name('booking.approve');
        Route::patch('/booking/{id}/reject', [TransactionController::class, 'rejectBooking'])->name('booking.reject');

        // Halaman Verifikasi Berkas
        Route::get('/documents', [TransactionController::class, 'documentVerification'])->name('documents');
        
        // Action: Approve (Lanjut Bank) & Revisi
        Route::patch('/documents/{id}/approve', [TransactionController::class, 'approveDocuments'])->name('documents.approve');
        Route::patch('/documents/{id}/revise', [TransactionController::class, 'reviseDocuments'])->name('documents.revise');

        // Halaman Approval & Finalisasi
        Route::get('/approval', [TransactionController::class, 'approval'])->name('approval');
        
        // Action: Finalize (Sold) & Reject Bank
        Route::patch('/approval/{id}/finalize', [TransactionController::class, 'finalizeTransaction'])->name('approval.finalize');
        Route::patch('/approval/{id}/reject-bank', [TransactionController::class, 'rejectBank'])->name('approval.reject');

        // Riwayat
        Route::get('/', [TransactionController::class, 'index'])->name('index');
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