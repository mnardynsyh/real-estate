<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\Unit;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['unit.location', 'documents'])
            ->where('user_id', Auth::id())->latest()->paginate(10);
        return view('customer.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $trx = Transaction::with(['unit.location', 'documents'])
            ->where('user_id', Auth::id())->findOrFail($id);

        $isRevision = ($trx->status === 'booking_acc' && !empty($trx->admin_note));
        
        $stepMap = [
            'pending' => 1, 'process' => 2, 'booking_acc' => $isRevision ? 4 : 3,
            'docs_review' => 5, 'bank_review' => 6, 'approved' => 6,
            'sold' => 7, 'rejected' => 7, 'canceled' => 7,
        ];
        
        $currentStep = $stepMap[$trx->status] ?? 1;
        $stepTitles = [
            1 => 'Bukti Pembayaran', 2 => 'Verifikasi', 3 => 'Booking Valid',
            4 => 'Pemberkasan', 5 => 'Validasi Berkas', 6 => 'Proses Bank', 7 => 'Selesai'
        ];
        $requiredDocs = [
            'ktp' => 'KTP', 'kk' => 'KK', 'npwp' => 'NPWP',
            'slip_gaji' => 'Slip Gaji', 'rekening_koran' => 'Rekening Koran'
        ];

        return view('customer.transactions.show', compact('trx', 'currentStep', 'stepTitles', 'requiredDocs', 'isRevision'));
    }

    public function store(Request $request)
    {
        $request->validate(['unit_id' => 'required|exists:units,id']);
        $user = Auth::user();
        
        if (!$user->customer || !$user->customer->nik) {
            return back()->with('error', 'Lengkapi NIK dan No HP di profil Anda sebelum booking.');
        }

        return DB::transaction(function () use ($request, $user) {
            $unit = Unit::where('id', $request->unit_id)->lockForUpdate()->first();
            if ($unit->status !== 'available') return back()->with('error', 'Maaf, unit ini baru saja dibooking orang lain.');
            
            $existing = Transaction::where('user_id', $user->id)
                ->where('unit_id', $unit->id)
                ->whereNotIn('status', ['rejected', 'canceled'])
                ->exists();

            if ($existing) return back()->with('error', 'Anda sudah melakukan booking unit ini.');

            $bookingFeeAmount = 2000000; 

            $trx = Transaction::create([
                'code' => 'TRX-' . now()->format('ymd') . '-' . strtoupper(Str::random(5)),
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'booking_fee' => $bookingFeeAmount, 
                'status' => 'pending',
            ]);

            $unit->update(['status' => 'booked']);

            return redirect()->route('customer.transactions.show', $trx->id)
                ->with('success', 'Unit berhasil dibooking! Silakan transfer tanda jadi.');
        });
    }

    public function uploadBookingProof(Request $request, $id)
    {
        $request->validate(['booking_proof' => 'required|image|max:5120']);

        return DB::transaction(function () use ($request, $id) {
            $trx = Transaction::where('user_id', Auth::id())->lockForUpdate()->findOrFail($id);
            
            if ($trx->status !== 'pending') return back()->with('error', 'Status transaksi tidak valid.');
            
            if ($trx->booking_proof) Storage::disk('public')->delete($trx->booking_proof);
            
            $path = $request->file('booking_proof')->store('proofs', 'public');
            
            $trx->update([
                'booking_proof' => $path,
                'status' => 'process' 
            ]);

            return back()->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
        });
    }

    /**
     * UPLOAD DOKUMEN (LOGIC: REPLACE FILE LAMA)
     */
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        return DB::transaction(function () use ($request, $id) {
            // Eager load documents untuk efisiensi
            $trx = Transaction::with('documents')->where('user_id', Auth::id())->findOrFail($id);

            if (!in_array($trx->status, ['booking_acc', 'docs_review'])) {
                return back()->with('error', 'Sesi upload dokumen belum dibuka.');
            }

            $path = $request->file('file')->store('documents', 'public');

            // Cek apakah dokumen dengan tipe ini sudah ada?
            $existingDoc = $trx->documents->where('type', $request->type)->first();

            if ($existingDoc) {
                // 1. Hapus file fisik lama agar storage tidak penuh
                if ($existingDoc->file_path && Storage::disk('public')->exists($existingDoc->file_path)) {
                    Storage::disk('public')->delete($existingDoc->file_path);
                }

                // 2. Update record yang ada (Replace)
                $existingDoc->update([
                    'file_path' => $path,
                    'status'    => 'pending', // Reset status jadi pending (agar merah invalid hilang)
                    'note'      => null       // Hapus catatan revisi
                ]);
            } else {
                // 3. Jika belum ada, baru create
                $trx->documents()->create([
                    'type'      => $request->type,
                    'file_path' => $path,
                    'status'    => 'pending',
                    'note'      => null
                ]);
            }

            // Logic Status Transaksi
            // Jika status sekarang 'booking_acc' (fase revisi global/awal), ubah jadi 'docs_review'
            // Ini mentrigger admin bahwa "ada update baru nih"
            if ($trx->status === 'booking_acc') {
                $trx->update([
                    'status'     => 'docs_review',
                    'admin_note' => null 
                ]);
            }

            return back()->with('success', 'Dokumen berhasil diunggah.');
        });
    }
}