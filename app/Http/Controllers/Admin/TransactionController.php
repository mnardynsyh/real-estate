<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDocument; 
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * 1. LIST SEMUA TRANSAKSI (RIWAYAT)
     */
    public function index(Request $request) 
    {
        $query = Transaction::with(['user', 'unit.location']);

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transaksi.riwayat', compact('transactions'));
    }

    /**
     * 2. HALAMAN VERIFIKASI BOOKING (Status: process)
     */
    public function bookingVerification()
    {
        $transactions = Transaction::with(['user', 'unit.location'])
            ->where('status', 'process')
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.booking', compact('transactions'));
    }

    /**
     * ACTION: TERIMA BOOKING
     */
    public function approveBooking($id)
    {
        return DB::transaction(function() use ($id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            
            if($trx->status !== 'process') {
                return back()->with('error', 'Status tidak valid.');
            }

            $trx->update([
                'status' => 'booking_acc', 
                'booking_verified_at' => now(), 
                'admin_note' => null
            ]);

            return back()->with('success', 'Booking diterima.');
        });
    }

    /**
     * ACTION: TOLAK BOOKING
     */
    public function rejectBooking(Request $request, $id)
    {
        $request->validate(['admin_note' => 'required|string']);

        return DB::transaction(function() use ($request, $id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            $unit = Unit::lockForUpdate()->findOrFail($trx->unit_id);

            // Validasi status
            if($trx->status !== 'process') {
                return back()->with('error', 'Status tidak valid.');
            }

            $trx->update(['status' => 'rejected', 'admin_note' => $request->admin_note]);
            $unit->update(['status' => 'available']);

            return back()->with('success', 'Booking ditolak.');
        });
    }

    /**
     * 3. HALAMAN VERIFIKASI BERKAS (Status: docs_review)
     */
    public function documentVerification()
    {
        $transactions = Transaction::with(['user', 'unit.location', 'documents'])
            ->where('status', 'docs_review')
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.verif-berkas', compact('transactions'));
    }

    /**
     * [AJAX] VALIDASI PER ITEM DOKUMEN
     */
    public function validateDocumentItem(Request $request, $docId)
    {
        $request->validate([
            'status' => 'required|in:valid,invalid',
            'note'   => 'nullable|string|max:255'
        ]);

        $doc = TransactionDocument::findOrFail($docId);
        
        $doc->update([
            'status' => $request->status,
            'note'   => $request->status === 'invalid' ? $request->note : null
        ]);

        return response()->json(['message' => 'Status dokumen diperbarui']);
    }

    /**
     * ACTION: VALIDASI SEMUA BERKAS (Lanjut ke Bank)
     */
    public function approveDocuments($id)
    {
        return DB::transaction(function() use ($id) {
            $trx = Transaction::with('documents')->findOrFail($id);
            
            if($trx->status !== 'docs_review') {
                return back()->with('error', 'Status transaksi tidak valid.');
            }

            $incompleteDocs = $trx->documents->whereIn('status', ['pending', 'invalid'])->count();

            if ($incompleteDocs > 0) {
                return back()->with('error', 'Tidak bisa lanjut. Pastikan semua dokumen sudah diperiksa dan berstatus Valid.');
            }

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
        
        $trx->update([
            'status' => 'booking_acc', 
            'admin_note' => $request->admin_note
        ]);

        return back()->with('success', 'Status dikembalikan ke user untuk revisi.');
    }

    /**
     * 4. HALAMAN APPROVAL & DP (Status: bank_review)
     */
    public function approval()
    {
        $transactions = Transaction::with(['user', 'unit.location'])
            ->whereIn('status', ['bank_review', 'approved'])
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.approval', compact('transactions'));
    }

    /**
     * ACTION: FINALISASI (Status: Sold)
     */
    public function finalizeTransaction(Request $request, $id)
    {
        $request->validate(['down_payment' => 'required|numeric|min:0']);

        return DB::transaction(function() use ($request, $id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            $unit = Unit::lockForUpdate()->findOrFail($trx->unit_id);

            $trx->update([
                'status' => 'sold', 
                'down_payment' => $request->down_payment, 
                'dp_verified_at' => now()
            ]);
            
            $unit->update(['status' => 'sold']);

            return back()->with('success', 'Transaksi Selesai. Unit resmi terjual.');
        });
    }

    /**
     * ACTION: GAGAL BANK / BATAL
     */
    public function rejectBank($id)
    {
        return DB::transaction(function() use ($id) {
            $trx = Transaction::lockForUpdate()->findOrFail($id);
            $unit = Unit::lockForUpdate()->findOrFail($trx->unit_id);

            $trx->update([
                'status' => 'rejected', 
                'admin_note' => 'Gagal Bank/Akad atau Dibatalkan.'
            ]);
            
            $unit->update(['status' => 'available']);

            return back()->with('success', 'Transaksi dibatalkan. Unit tersedia kembali.');
        });
    }

    /**
     * 5. EXPORT EXCEL
     */
    public function export() 
    {
        $fileName = 'laporan-transaksi-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new TransactionsExport, $fileName);
    }

    /**
     * DETAIL TRANSAKSI
     */
    public function show($id)
    {
        $trx = Transaction::with(['user', 'unit.location', 'documents'])->findOrFail($id);
        return view('admin.transaksi.detail', compact('trx'));
    }
}