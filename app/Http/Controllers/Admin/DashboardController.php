<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Hitung unit yang statusnya 'available'
            'units_available' => Unit::where('status', 'available')->count(),
            
            // Hitung unit yang statusnya 'sold'
            'units_sold' => Unit::where('status', 'sold')->count(),
            
            // Hitung transaksi pending (menunggu verifikasi booking)
            'pending_bookings' => Transaction::where('status', 'process')->count(),
            
            // Hitung transaksi yang sedang review berkas
            'docs_review' => Transaction::where('status', 'docs_review')->count(),
        ];

        // 2. Data Tugas Terbaru
        // Mengambil 5 transaksi terakhir
        $recent_tasks = Transaction::with(['user', 'unit'])
            ->whereIn('status', ['process', 'docs_review'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_tasks'));
    }
}