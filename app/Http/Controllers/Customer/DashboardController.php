<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan user login (bisa dihapus jika nanti pakai middleware, tapi aman ada di sini)
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }

        $user = Auth::user();

        // 1. Ambil Transaksi yang sedang AKTIF (Belum selesai/batal)
        // Status selesai = sold, Status batal = rejected, canceled
        // Kita gunakan 'with' untuk memuat relasi Unit & Lokasi agar efisien
        $activeTransaction = Transaction::with(['unit.location'])
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['sold', 'rejected', 'canceled'])
            ->latest()
            ->first();

        // 2. Ambil Riwayat Aktivitas (5 transaksi terakhir)
        $recentActivities = Transaction::with('unit')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('activeTransaction', 'recentActivities'));
    }
}