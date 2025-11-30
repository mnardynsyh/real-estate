<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\TransactionDocument;
use App\Models\Unit;

class TransactionController extends Controller
{
    /**
     * LIST TRANSAKSI
     */
    public function index()
    {
        $transactions = Transaction::with(['unit.location', 'documents'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.transactions.index', compact('transactions'));
    }

    /**
     * HALAMAN DETAIL TRANSAKSI
     */
    public function show($id)
    {
        $trx = Transaction::with([
            'unit.location',
            'documents'
        ])->where('user_id', Auth::id())
          ->findOrFail($id);

        // Step urutan
        $steps = [
            'pending'       => 1,
            'process'       => 2,
            'booking_acc'   => 3,
            'docs_review'   => 4,
            'bank_process'  => 5,
            'sold'          => 6,
            'rejected'      => 6,
            'canceled'      => 6,
        ];

        $stepTitles = [
            1 => 'Upload Bukti Bayar',
            2 => 'Review Admin',
            3 => 'Booking Disetujui',
            4 => 'Upload Dokumen KPR',
            5 => 'Proses Bank',
            6 => 'Selesai'
        ];

        $currentStep = $steps[$trx->status] ?? 1;

        // Dokumen wajib
        $requiredDocs = [
            'ktp'            => 'KTP Pemohon',
            'kk'             => 'Kartu Keluarga',
            'npwp'           => 'NPWP',
            'slip_gaji'      => 'Slip Gaji / Bukti Penghasilan',
            'rekening_koran' => 'Rekening Koran 3 Bulan Terakhir'
        ];

        return view('customer.transactions.show', compact(
            'trx',
            'currentStep',
            'stepTitles',
            'requiredDocs'
        ));
    }


    /**
     * PROSES BOOKING UNIT
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        // WAJIB: profil lengkap
        $customer = Auth::user()->customer;
        if (!$customer || !$customer->nik || !$customer->phone || !$customer->address) {
            return back()->with('error', 'Lengkapi profil Anda sebelum booking.');
        }

        return DB::transaction(function () use ($request) {

            $unit = Unit::where('id', $request->unit_id)
                ->lockForUpdate()
                ->first();

            if (!$unit || $unit->status !== 'available') {
                return back()->with('error', 'Unit sudah tidak tersedia.');
            }

            // Cegah double booking unit yg sama
            $existing = Transaction::where('user_id', Auth::id())
                ->where('unit_id', $unit->id)
                ->whereNotIn('status', ['rejected', 'canceled'])
                ->exists();

            if ($existing) {
                return back()->with('error', 'Anda sudah memiliki transaksi aktif untuk unit ini.');
            }

            // Booking fee = harga unit
            $bookingFee = $unit->price;

            // Kode transaksi
            $trxCode = 'TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            // Create transaksi
            $trx = Transaction::create([
                'code'        => $trxCode,
                'user_id'     => Auth::id(),
                'unit_id'     => $unit->id,
                'booking_fee' => $bookingFee,
                'status'      => 'pending',
            ]);

            // Lock unit
            $unit->update(['status' => 'booked']);

            return redirect()->route('customer.transactions.show', $trx->id)
                ->with('success', 'Booking dibuat! Upload bukti pembayaran.');
        });
    }


    /**
     * UPLOAD BUKTI PEMBAYARAN
     */
    public function uploadBookingProof(Request $request, $id)
    {
        $request->validate([
            'booking_proof' => 'required|image|max:10240',
        ]);

        $trx = Transaction::where('user_id', Auth::id())->findOrFail($id);

        if ($trx->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak mengizinkan upload bukti.');
        }

        $path = $request->file('booking_proof')->store('proofs', 'public');

        $trx->update([
            'booking_proof' => $path,
            'status'        => 'process',
        ]);

        return back()->with('success', 'Bukti telah dikirim. Menunggu verifikasi admin.');
    }


    /**
     * UPLOAD DOKUMEN KPR
     */
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:3000',
        ]);

        $trx = Transaction::findOrFail($id);

        // Upload file
        $path = $request->file('file')->store('documents', 'public');

        // Simpan record
        $trx->documents()->create([
            'type' => $request->type,
            'file_path' => $path,
        ]);

        // Ubah status menjadi docs_review jika masih booking_acc
        if ($trx->status === 'booking_acc') {
            $trx->update([
                'status' => 'docs_review'
            ]);
        }

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

}
