@extends('layouts.customer')
@section('title', 'Transaksi Saya')

@section('content')
<div class="bg-[#F0F2F5] min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4">

        <h1 class="text-2xl font-bold text-slate-900 mb-6">Transaksi Saya</h1>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="p-4 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
        @endif


        {{-- =================================================================== --}}
        {{-- KOSONG --}}
        {{-- =================================================================== --}}
        @if($transactions->count() == 0)
            <div class="p-10 bg-white border border-slate-200 rounded-2xl text-center">
                <i class="fa-solid fa-receipt text-5xl text-slate-300 mb-4"></i>
                <p class="text-slate-500">Anda belum memiliki transaksi.</p>

                <a href="{{ route('catalog') }}"
                   class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold">
                    Mulai Booking Unit
                </a>
            </div>
        @else


        {{-- =================================================================== --}}
        {{-- LIST TRANSAKSI --}}
        {{-- =================================================================== --}}
        <div class="space-y-6">

            @foreach($transactions as $trx)
                @php
                    // Mini progress bar (0–100%)
                    $stepMap = [
                        'pending'       => 10,
                        'process'       => 25,
                        'booking_acc'   => 50,
                        'docs_review'   => 75,
                        'bank_process'  => 85,
                        'sold'          => 100,
                        'rejected'      => 100,
                        'canceled'      => 100,
                    ];
                    $progress = $stepMap[$trx->status] ?? 10;

                    // Status badge color
                    $statusColor = [
                        'pending'       => 'bg-amber-100 text-amber-700',
                        'process'       => 'bg-blue-100 text-blue-700',
                        'booking_acc'   => 'bg-emerald-100 text-emerald-700',
                        'docs_review'   => 'bg-indigo-100 text-indigo-700',
                        'docs_revise'   => 'bg-red-100 text-red-700',
                        'bank_process'  => 'bg-purple-100 text-purple-700',
                        'sold'          => 'bg-slate-800 text-white',
                        'rejected'      => 'bg-red-200 text-red-800',
                        'canceled'      => 'bg-slate-200 text-slate-600'
                    ][$trx->status] ?? 'bg-slate-100 text-slate-600';
                @endphp


                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">

                    {{-- HEADER --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs text-slate-500">Kode Transaksi</p>
                            <p class="font-bold text-slate-800">{{ $trx->code }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase {{ $statusColor }}">
                            {{ $trx->status }}
                        </span>
                    </div>

                    {{-- UNIT INFO --}}
                    <div class="flex flex-col md:flex-row gap-4 border rounded-xl p-4 bg-slate-50">

                        {{-- Thumbnail --}}
                        <div class="w-32 h-24 bg-slate-200 rounded-lg overflow-hidden flex-shrink-0">
                            @if($trx->unit->image)
                                <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-slate-400">
                                    <i class="fa-solid fa-house text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">
                                Blok {{ $trx->unit->block_number }} – {{ $trx->unit->location->name }}
                            </p>
                            <p class="text-sm text-slate-600">{{ $trx->unit->location->address }}</p>

                            <p class="text-xs text-slate-400 mt-2">Harga Unit</p>
                            <p class="font-bold text-blue-600 text-lg">
                                Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>


                    {{-- PROGRESS BAR --}}
                    <div class="mt-6">
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Progress: {{ $progress }}%</p>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('customer.transactions.show', $trx->id) }}"
                           class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-sm">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            @endforeach

        </div>


        <div class="mt-6">
            {{ $transactions->links() }}
        </div>


        @endif
    </div>
</div>
@endsection
