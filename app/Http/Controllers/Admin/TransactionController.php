<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDocument;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // ... (Index, BookingVerification, ApproveBooking, RejectBooking tetap sama seperti sebelumnya) ...
    public function index(Request $request) 
    {
        $transactions = Transaction::with(['user', 'unit.location'])->latest()->paginate(10);
        return view('admin.transaksi.riwayat', compact('transactions'));
    }

    public function bookingVerification()
    {
        $transactions = Transaction::with(['user', 'unit.location'])->where('status', 'process')->latest()->paginate(10);
        return view('admin.transaksi.booking', compact('transactions'));
    }

    public function approveBooking($id)
    {
        return DB::transaction(function() use ($id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            if($trx->status !== 'process') return back()->with('error', 'Status tidak valid.');
            $trx->update(['status' => 'booking_acc', 'booking_verified_at' => now(), 'admin_note' => null]);
            return back()->with('success', 'Booking diterima.');
        });
    }

    public function rejectBooking(Request $request, $id)
    {
        $request->validate(['admin_note' => 'required|string']);
        return DB::transaction(function() use ($request, $id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            $unit = Unit::lockForUpdate()->findOrFail($trx->unit_id);
            $trx->update(['status' => 'rejected', 'admin_note' => $request->admin_note]);
            $unit->update(['status' => 'available']);
            return back()->with('success', 'Booking ditolak.');
        });
    }

    /**
     * 3. HALAMAN VERIFIKASI BERKAS
     */
    public function documentVerification()
    {
        // Ambil transaksi yang sedang direview dokumennya
        $transactions = Transaction::with(['user', 'unit.location', 'documents'])
            ->where('status', 'docs_review')
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.verif-berkas', compact('transactions'));
    }

    /**
     * [BARU] VALIDASI PER ITEM DOKUMEN (AJAX)
     */
    public function validateDocumentItem(Request $request, $docId)
    {
        $request->validate([
            'status' => 'required|in:valid,invalid',
            'note'   => 'nullable|string|max:255'
        ]);

        $doc = TransactionDocument::findOrFail($docId);
        
        // Update status dokumen spesifik
        $doc->update([
            'status' => $request->status,
            'note'   => $request->status === 'invalid' ? $request->note : null
        ]);

        return response()->json(['message' => 'Status dokumen diperbarui']);
    }

    /**
     * ACTION: VALIDASI BERKAS (Lanjut ke Bank)
     * Hanya bisa jika semua dokumen VALID
     */
    public function approveDocuments($id)
    {
        return DB::transaction(function() use ($id) {
            $trx = Transaction::with('documents')->findOrFail($id);
            
            // 1. Cek Status Transaksi
            if($trx->status !== 'docs_review') {
                return back()->with('error', 'Status transaksi tidak valid.');
            }

            // 2. Cek Kelengkapan Dokumen (Strict)
            // Tidak boleh ada dokumen yang statusnya 'pending' atau 'invalid'
            $incompleteDocs = $trx->documents->whereIn('status', ['pending', 'invalid'])->count();

            if ($incompleteDocs > 0) {
                return back()->with('error', 'Tidak bisa lanjut. Pastikan semua dokumen sudah diperiksa dan berstatus Valid.');
            }

            // 3. Update Status ke Bank Process
            $trx->update([
                'status' => 'bank_review',
                'admin_note' => null
            ]);

            return back()->with('success', 'Semua berkas valid. Status lanjut ke Proses Bank.');
        });
    }

    /**
     * ACTION: MINTA REVISI (Kembalikan ke User)
     */
    public function reviseDocuments(Request $request, $id)
    {
        $request->validate(['admin_note' => 'required|string|max:255']);

        $trx = Transaction::findOrFail($id);
        
        // Kembalikan status ke 'booking_acc'
        // Ini akan membuka kembali form upload di sisi Customer
        $trx->update([
            'status' => 'booking_acc', 
            'admin_note' => $request->admin_note
        ]);

        return back()->with('success', 'Status dikembalikan ke user untuk revisi.');
    }

    // ... (Method approval, finalizeTransaction, rejectBank, show tetap sama) ...
    public function approval()
    {
        $transactions = Transaction::with(['user', 'unit.location'])
            ->whereIn('status', ['bank_review', 'approved'])->latest()->paginate(10);
        return view('admin.transaksi.approval', compact('transactions'));
    }

    public function finalizeTransaction(Request $request, $id)
    {
        $request->validate(['down_payment' => 'required|numeric|min:0']);
        return DB::transaction(function() use ($request, $id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            $unit = Unit::lockForUpdate()->findOrFail($trx->unit_id);
            $trx->update(['status' => 'sold', 'down_payment' => $request->down_payment, 'dp_verified_at' => now()]);
            $unit->update(['status' => 'sold']);
            return back()->with('success', 'Transaksi Selesai.');
        });
    }

    public function rejectBank($id)
    {
        return DB::transaction(function() use ($id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            $unit = Unit::lockForUpdate()->findOrFail($trx->unit_id);
            $trx->update(['status' => 'rejected', 'admin_note' => 'Gagal Bank/Akad']);
            $unit->update(['status' => 'available']);
            return back()->with('success', 'Transaksi dibatalkan.');
        });
    }

    public function show($id)
    {
        $trx = Transaction::with(['user', 'unit.location', 'documents'])->findOrFail($id);
        return view('admin.transaksi.detail', compact('trx'));
    }
}