@extends('layouts.admin')
@section('title', 'Detail Transaksi')

@section('content')

<div class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-12 lg:px-8 lg:pt-10 flex flex-col text-slate-800">

    <div class="max-w-6xl mx-auto w-full">

        {{-- BACK --}}
        <a href="{{ route('admin.transactions.index') }}"
           class="inline-flex items-center gap-2 mb-6 text-slate-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="font-medium">Kembali ke Riwayat Transaksi</span>
        </a>

        {{-- HEADER --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-10">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-slate-500">Kode Transaksi</p>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $trx->code }}</h1>

                    <p class="text-xs text-slate-500 mt-3">Status Saat Ini</p>
                    @php
                        $map = [
                            'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Pembayaran'],
                            'process' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Verifikasi Admin'],
                            'booking_acc' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Pemberkasan'],
                            'docs_review' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Review Berkas'],
                            'bank_review' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Proses Bank'],
                            'sold' => ['bg' => 'bg-emerald-600', 'text' => 'text-white', 'label' => 'Selesai / Terjual'],
                            'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                        ][$trx->status];
                    @endphp

                    <span class="px-3 py-1 inline-block rounded-lg text-xs font-bold {{ $map['bg'] }} {{ $map['text'] }}">
                        {{ $map['label'] }}
                    </span>
                </div>

                <div class="text-right">
                    <p class="text-xs text-slate-500">Booking Fee</p>
                    <p class="text-3xl font-bold text-blue-600">
                        Rp {{ number_format($trx->booking_fee,0,',','.') }}
                    </p>
                </div>
            </div>
        </div>


        {{-- UNIT DETAILS --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">

            <h2 class="font-bold text-lg text-slate-900 mb-4">Detail Unit</h2>

            <div class="flex flex-col md:flex-row gap-4">

                <div class="w-48 h-32 bg-slate-200 rounded-xl overflow-hidden">
                    @if($trx->unit->image)
                        <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-house text-4xl"></i>
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <p class="font-bold text-xl text-slate-900">
                        Blok {{ $trx->unit->block_number }} – Tipe {{ $trx->unit->type }}
                    </p>
                    <p class="text-slate-600">{{ $trx->unit->location->name }}, {{ $trx->unit->location->city }}</p>

                    <p class="text-xs text-slate-400 mt-3">Alamat Lokasi</p>
                    <p class="text-slate-700 text-sm">
                        {{ $trx->unit->location->address }}
                    </p>
                </div>

            </div>

        </div>


        {{-- DOKUMEN CUSTOMER --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">

            <h2 class="font-bold text-lg text-slate-900 mb-4">Dokumen KPR Customer</h2>

            @if($trx->documents->count() == 0)
                <p class="text-slate-500 text-sm">Belum ada dokumen yang diunggah.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    @foreach($trx->documents as $doc)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="flex justify-between items-center mb-3">
                                <p class="font-bold text-slate-800 uppercase text-xs">{{ $doc->type }}</p>

                                <span class="text-xs font-bold 
                                    @if($doc->status == 'approved') text-emerald-600
                                    @elseif($doc->status == 'pending') text-amber-600
                                    @else text-red-600 @endif">
                                    {{ strtoupper($doc->status) }}
                                </span>
                            </div>

                            <div class="w-full bg-white border rounded-lg p-2">
                                <img src="{{ Storage::url($doc->file_path) }}" 
                                     class="w-full h-56 object-contain rounded">
                            </div>

                            @if($doc->note)
                                <p class="text-xs text-red-600 mt-2">{{ $doc->note }}</p>
                            @endif
                        </div>
                    @endforeach

                </div>
            @endif

        </div>


        {{-- ADMIN ACTIONS (Jika status masih bisa diproses) --}}
        @if(in_array($trx->status, ['booking_acc','docs_review']))
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-10">

            <h2 class="font-bold text-lg text-slate-900 mb-4">Tindakan Admin</h2>

            <div class="flex flex-col md:flex-row gap-4">

                {{-- REVISI --}}
                <form action="{{ route('admin.transactions.documents.revise', $trx->id) }}" method="POST" class="flex-1">
                    @csrf @method('PATCH')

                    <textarea name="admin_note" rows="3"
                              class="w-full border rounded-xl p-3 text-sm bg-slate-50"
                              placeholder="Tulis catatan revisi dokumen..."></textarea>

                    <button class="mt-3 px-5 py-2 bg-red-600 text-white rounded-lg font-bold text-sm hover:bg-red-700">
                        Minta Revisi
                    </button>
                </form>

                {{-- APPROVE --}}
                <form action="{{ route('admin.transactions.documents.approve', $trx->id) }}" method="POST" class="flex items-end">
                    @csrf @method('PATCH')

                    <button class="px-6 py-3 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700">
                        Validasi & Teruskan ke Bank
                    </button>
                </form>

            </div>
        </div>
        @endif

    </div>

</div>

@endsection
