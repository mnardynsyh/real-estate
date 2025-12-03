@extends('layouts.customer')
@section('title', 'Transaksi Saya')

@section('content')
<div class="w-full min-h-screen bg-slate-100 px-2 pt-8 lg:px-4 lg:pt-8 flex flex-col font-sans text-slate-800">
    
    <div class="max-w-5xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Transaksi Saya
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Riwayat pembelian dan progress pengajuan unit rumah Anda.
                </p>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-500"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- Empty State --}}
        @if($transactions->count() == 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                    <i class="fa-solid fa-receipt text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Belum ada transaksi</h3>
                <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto leading-relaxed">
                    Anda belum melakukan pemesanan unit apapun. Silakan cek katalog kami untuk memulai.
                </p>
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                    <i class="fa-solid fa-house-chimney"></i> Mulai Booking Unit
                </a>
            </div>
        @else

        {{-- List Transaksi --}}
        <div class="space-y-6">
            @foreach($transactions as $trx)
                @php
                    // Logic Progress Bar
                    $progress = match($trx->status) {
                        'pending'      => 10,
                        'process'      => 25,
                        'booking_acc'  => 50,
                        'docs_review'  => 75,
                        'bank_process' => 85,
                        'bank_review'  => 85,
                        'sold'         => 100,
                        'rejected'     => 100,
                        'canceled'     => 100,
                        default        => 0
                    };

                    // Logic Warna Badge
                    $statusConfig = match($trx->status) {
                        'pending'      => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Pembayaran'],
                        'process'      => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Verifikasi Admin'],
                        'booking_acc'  => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Pemberkasan'],
                        'docs_review'  => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Review Dokumen'],
                        'bank_process' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank'],
                        'bank_review'  => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank'],
                        'sold'         => ['bg' => 'bg-slate-800', 'text' => 'text-white', 'label' => 'Selesai'],
                        'rejected'     => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                        'canceled'     => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Dibatalkan'],
                        default        => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => ucfirst($trx->status)]
                    };
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all duration-300 relative overflow-hidden group">
                    
                    {{-- Stripe Status --}}
                    <div class="absolute top-0 left-0 w-1.5 h-full {{ str_contains($statusConfig['bg'], 'bg-slate-800') ? 'bg-slate-800' : str_replace('bg-', 'bg-', $statusConfig['bg']) }}"></div>

                    <div class="p-6">
                        
                        {{-- Card Header --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kode Transaksi</p>
                                <p class="text-lg font-bold text-slate-800 font-mono">#{{ $trx->code }}</p>
                            </div>
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>

                        {{-- Content --}}
                        <div class="flex flex-col md:flex-row gap-6">
                            
                            {{-- Thumbnail --}}
                            <div class="w-full md:w-40 h-32 bg-slate-100 rounded-xl border border-slate-200 overflow-hidden shrink-0 relative">
                                @if($trx->unit->image)
                                    <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-house text-3xl"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Detail Info --}}
                            <div class="flex-1">
                                <div class="flex flex-col h-full justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900 mb-1">
                                            {{ $trx->unit->location->name ?? 'Lokasi Tidak Ditemukan' }}
                                        </h3>
                                        <p class="text-sm text-slate-600 font-medium">
                                            Blok {{ $trx->unit->block_number }} • Tipe {{ $trx->unit->type }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $trx->unit->location->address }}</p>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-6">
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">Harga Unit</p>
                                            <p class="font-bold text-slate-800">Rp {{ number_format($trx->unit->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">Booking Fee</p>
                                            <p class="font-bold text-emerald-600">Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="flex flex-col justify-end md:items-end min-w-[140px]">
                                <a href="{{ route('customer.transactions.show', $trx->id) }}" 
                                   class="w-full md:w-auto text-center px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-blue-600 hover:border-blue-300 transition-all shadow-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        @if(!in_array($trx->status, ['rejected', 'canceled']))
                            <div class="mt-6">
                                <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase mb-2">
                                    <span>Progress Transaksi</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full transition-all duration-1000 ease-out relative overflow-hidden" style="width: {{ $progress }}%">
                                        <div class="absolute inset-0 bg-white/20 w-full h-full animate-[shimmer_2s_infinite]"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $transactions->links() }}
        </div>

        @endif
    </div>
</div>
@endsection