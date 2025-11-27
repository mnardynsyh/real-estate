<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Unit;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // riwayat semua transaksi
    public function index(Request $request) 
    {

        $query = Transaction::with(['user', 'unit.location']);


        $transactions = $query->latest()->paginate(10);

        return view('admin.transaksi.riwayat', compact('transactions'));
    }

    // verifikasi booking
    public function bookingVerification()
    {
        $transactions = Transaction::with(['user', 'unit.location'])
            ->where('status', 'process') 
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.booking', compact('transactions'));
    }

    // ACTION: TERIMA BOOKING
    public function approveBooking($id)
    {
        $trx = Transaction::findOrFail($id);
        
        $trx->update([
            'status' => 'booking_acc', 
            'booking_verified_at' => now()
        ]);

        return back()->with('success', 'Booking diterima. User dapat melanjutkan pemberkasan.');
    }

    // ACTION: TOLAK BOOKING
    public function rejectBooking(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:255'
        ]);

        $trx = Transaction::findOrFail($id);
        
        // Ambil unit terkait
        $unit = Unit::findOrFail($trx->unit_id);

        // Ubah status transaksi jadi 'rejected'
        $trx->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note
        ]);

        $unit->update(['status' => 'available']);

        return back()->with('success', 'Booking ditolak. Unit kembali tersedia untuk publik.');
    }

    // 5. HALAMAN VERIFIKASI BERKAS (Status 'docs_review')
    public function documentVerification()
    {
        // Ambil transaksi yang statusnya sedang direview admin
        // Load relasi 'documents' untuk ditampilkan di modal
        $transactions = Transaction::with(['user', 'unit.location', 'documents'])
            ->where('status', 'docs_review')
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.verif-berkas', compact('transactions'));
    }

    // 6. ACTION: VALIDASI BERKAS (Lanjut ke Bank)
    public function approveDocuments($id)
    {
        $trx = Transaction::findOrFail($id);
        
        // Ubah status jadi 'bank_process' (Proses Bank)
        $trx->update([
            'status' => 'bank_process'
        ]);

        return back()->with('success', 'Berkas valid. Status diperbarui menjadi Proses Bank.');
    }

    // 7. ACTION: MINTA REVISI (Kembalikan ke User)
    public function reviseDocuments(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:255'
        ]);

        $trx = Transaction::findOrFail($id);
        
        // Ubah status kembali ke 'booking_acc' agar user bisa upload ulang
        $trx->update([
            'status' => 'booking_acc', // Mundur satu langkah
            'admin_note' => $request->admin_note
        ]);

        return back()->with('success', 'Catatan revisi dikirim ke user.');
    }
    // 8. HALAMAN APPROVAL & DP (Status 'bank_process')
    public function approval()
    {
        // Menampilkan transaksi yang sedang proses bank atau menunggu DP
        $transactions = Transaction::with(['user', 'unit.location'])
            ->whereIn('status', ['bank_process', 'approved'])
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.approval', compact('transactions'));
    }

    // 9. ACTION: FINALISASI (Unit Terjual / Sold)
    public function finalizeTransaction(Request $request, $id)
    {
        $request->validate([
            'down_payment' => 'required|numeric|min:0',
        ]);

        $trx = Transaction::findOrFail($id);
        $unit = Unit::findOrFail($trx->unit_id);

        // Update Transaksi -> Sold (Selesai)
        $trx->update([
            'status' => 'sold',
            'down_payment' => $request->down_payment,
            'dp_verified_at' => now(),
        ]);

        // PENTING: Update Unit -> Sold (Permanen)
        $unit->update(['status' => 'sold']);

        return back()->with('success', 'Selamat! Transaksi selesai & Unit resmi terjual.');
    }

    // 10. ACTION: GAGAL BANK (Batalkan)
    public function rejectBank($id)
    {
        $trx = Transaction::findOrFail($id);
        $unit = Unit::findOrFail($trx->unit_id);

        // Update Transaksi -> Rejected
        $trx->update([
            'status' => 'rejected',
            'admin_note' => 'Pengajuan KPR ditolak oleh Bank/Developer, atau user membatalkan saat proses akad.'
        ]);

        // PENTING: Update Unit -> Available (Bisa dijual lagi)
        $unit->update(['status' => 'available']);

        return back()->with('success', 'Transaksi dibatalkan. Unit kembali tersedia untuk publik.');
    }
}