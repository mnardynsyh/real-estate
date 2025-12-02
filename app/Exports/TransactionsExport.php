<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function query()
    {
        // Ambil semua data transaksi, urutkan dari yang terbaru
        // Eager load relasi agar query ringan
        return Transaction::query()
            ->with(['user.customer', 'unit.location'])
            ->latest();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal Booking',
            'Nama Customer',
            'NIK',
            'No HP',
            'Lokasi Perumahan',
            'Blok Unit',
            'Tipe',
            'Harga Unit',
            'Booking Fee',
            'DP (Down Payment)',
            'Status',
            'Catatan Admin'
        ];
    }

    /**
     * Mapping data per baris
     */
    public function map($trx): array
    {
        // Format Status agar lebih mudah dibaca manusia
        $statusLabel = match($trx->status) {
            'pending' => 'Menunggu Bukti',
            'process' => 'Verifikasi Pembayaran',
            'booking_acc' => 'Booking Diterima',
            'docs_review' => 'Review Dokumen',
            'bank_review' => 'Proses Bank',
            'sold' => 'Terjual (Selesai)',
            'rejected' => 'Ditolak',
            'canceled' => 'Dibatalkan',
            default => $trx->status
        };

        return [
            $trx->id,
            $trx->code,
            $trx->created_at->format('d/m/Y H:i'),
            $trx->user->name,
            $trx->user->customer->nik ?? '-',
            $trx->user->customer->phone ?? '-',
            $trx->unit->location->name ?? '-',
            $trx->unit->block_number,
            $trx->unit->type,
            $trx->unit->price,
            $trx->booking_fee,
            $trx->down_payment ?? 0,
            $statusLabel,
            $trx->admin_note
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style baris pertama (Heading) jadi Bold
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}