<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar customer
     */
    public function index(Request $request)
    {
        // Ambil user yang role-nya customer
        // Eager load relasi 'customer' (tabel profil) dan 'transactions' (untuk hitung jumlah beli)
        $query = User::with(['customer', 'transactions'])
                     ->where('role', 'customer');

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($subQ) use ($search) {
                      $subQ->where('phone', 'like', "%{$search}%")
                           ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customer', compact('customers'));
    }

    /**
     * Menampilkan detail customer (Opsional/Modal)
     * Untuk saat ini kita pakai modal di index saja biar cepat.
     */
    public function show($id)
    {
        // Placeholder jika butuh halaman detail terpisah
    }

    /**
     * Menghapus customer (User + Data Profil + Transaksi terkait)
     */
    public function destroy($id)
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        
        // Laravel Cascade Delete akan otomatis menghapus data di tabel 'customers' 
        // dan 'transactions' jika foreign key dikonfigurasi dengan benar.
        $user->delete();

        return back()->with('success', 'Data customer berhasil dihapus dari sistem.');
    }
}