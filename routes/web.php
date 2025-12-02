<?php

use Illuminate\Support\Facades\Route;

// ================= PUBLIC ROUTES =================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController as AuthController;
use App\Http\Controllers\Auth\RegisterController as RegisterController;

// ================= ADMIN ROUTES =================
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HousingController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CustomerController;

// ================= CUSTOMER ROUTES =================
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\TransactionController as CustomerTransaction;
use App\Http\Controllers\Customer\ProfileController as CustomerProfile;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [HomeController::class, 'catalog'])->name('catalog');
Route::get('/unit/{id}', [HomeController::class, 'show'])->name('unit.show');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('housing', HousingController::class);
        Route::resource('units', UnitController::class);

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION MANAGEMENT (ADMIN)
        |--------------------------------------------------------------------------
        */
        Route::prefix('transactions')->name('transactions.')->group(function () {

            // EXPORT EXCEL (Letakkan di atas routes dinamis {id})
            Route::get('/export', [TransactionController::class, 'export'])
                ->name('export');

            // BOOKING
            Route::get('/booking', [TransactionController::class, 'bookingVerification'])
                ->name('booking');
            Route::patch('/booking/{id}/approve', [TransactionController::class, 'approveBooking'])
                ->name('booking.approve');
            Route::patch('/booking/{id}/reject', [TransactionController::class, 'rejectBooking'])
                ->name('booking.reject');

            // VERIFIKASI BERKAS
            Route::get('/documents', [TransactionController::class, 'documentVerification'])
                ->name('documents');
            Route::patch('/documents/{id}/approve', [TransactionController::class, 'approveDocuments'])
                ->name('documents.approve');
            Route::patch('/documents/{id}/revise', [TransactionController::class, 'reviseDocuments'])
                ->name('documents.revise');
            
            // Validasi Per Item (AJAX)
            Route::patch('/documents/{docId}/validate', [TransactionController::class, 'validateDocumentItem'])
                ->name('documents.validate_item');

            // FINAL APPROVAL
            Route::get('/approval', [TransactionController::class, 'approval'])
                ->name('approval');
            Route::patch('/approval/{id}/finalize', [TransactionController::class, 'finalizeTransaction'])
                ->name('approval.finalize');
            Route::patch('/approval/{id}/reject-bank', [TransactionController::class, 'rejectBank'])
                ->name('approval.reject');

            // INDEX & DETAIL (Letakkan paling bawah agar tidak konflik dengan /booking atau /approval)
            Route::get('/', [TransactionController::class, 'index'])
                ->name('index');
            Route::get('/{id}', [TransactionController::class, 'show'])
                ->name('show');
        });

        // Customers
        Route::resource('customers', CustomerController::class);
    });



/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'role:customer'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])
            ->name('dashboard');

        // LIST TRANSAKSI
        Route::get('/transactions', 
            [CustomerTransaction::class, 'index']
        )->name('transactions.index');

        // DETAIL TRANSAKSI
        Route::get('/transactions/{id}', 
            [CustomerTransaction::class, 'show']
        )->name('transactions.show');

        // BOOKING (CREATE)
        Route::post('/transactions', 
            [CustomerTransaction::class, 'store']
        )->name('transactions.store');

        // UPLOAD BUKTI BAYAR
        Route::patch('/transactions/{id}/upload-proof',
            [CustomerTransaction::class, 'uploadBookingProof']
        )->name('transactions.upload_proof');

        // UPLOAD DOKUMEN KPR
        Route::post('/transactions/{id}/upload-document', 
            [CustomerTransaction::class, 'uploadDocument']
        )->name('transactions.upload_doc');

        // PROFIL
        Route::get('/profile', [CustomerProfile::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [CustomerProfile::class, 'update'])
            ->name('profile.update');
    });