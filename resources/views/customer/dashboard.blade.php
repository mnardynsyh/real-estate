@extends('layouts.customer')
@section('title', 'Dashboard Saya')

@section('content')
<div class="w-full min-h-screen bg-slate-100 px-2 pt-8 lg:px-4 lg:pt-8 flex flex-col font-sans text-slate-800">

    <div class="max-w-5xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Halo, {{ Auth::user()->name }}!
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Selamat datang di panel pelanggan perumahan kami.
                </p>
            </div>
        </div>

        {{-- Alert Profile Incomplete --}}
        @if(!Auth::user()->customer?->nik || !Auth::user()->customer?->phone)
            <div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                
                <div class="flex flex-col sm:flex-row items-start gap-5 relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0 backdrop-blur-sm border border-white/20">
                        <i class="fa-solid fa-user-pen text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg">Lengkapi Data Diri Anda</h3>
                        <p class="text-blue-100 text-sm mt-1 leading-relaxed max-w-2xl">
                            Untuk melanjutkan proses booking unit dan pemberkasan KPR, mohon lengkapi data <b>NIK, No HP, dan Pekerjaan</b> Anda terlebih dahulu.
                        </p>
                        <a href="{{ route('customer.profile.edit') }}" class="inline-flex items-center gap-2 mt-4 text-xs font-bold text-blue-600 bg-white px-5 py-2.5 rounded-xl hover:bg-blue-50 transition-all shadow-md">
                            Lengkapi Profil Sekarang <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Active Transaction --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-blue-600"></i> Transaksi Berjalan
            </h2>

            @if($activeTransaction)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative group hover:border-blue-300 transition-all duration-300">
                    
                    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Pesanan</span>
                            <p class="text-sm font-bold text-slate-800 font-mono tracking-wide">#{{ $activeTransaction->code }}</p>
                        </div>
                        
                        @php
                            $statusConfig = match($activeTransaction->status) {
                                'pending'      => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
                                'process'      => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Verifikasi Admin', 'icon' => 'fa-spinner fa-spin'],
                                'booking_acc'  => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Upload Berkas', 'icon' => 'fa-file-upload'],
                                'docs_review'  => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Review Dokumen', 'icon' => 'fa-magnifying-glass'],
                                'bank_process' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank', 'icon' => 'fa-building-columns'],
                                'bank_review'  => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank', 'icon' => 'fa-building-columns'],
                                'sold'         => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Selesai / Terjual', 'icon' => 'fa-check-circle'],
                                'rejected'     => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak', 'icon' => 'fa-circle-xmark'],
                                'canceled'     => ['bg' => 'bg-slate-200', 'text' => 'text-slate-600', 'label' => 'Dibatalkan', 'icon' => 'fa-ban'],
                                default        => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => ucfirst($activeTransaction->status), 'icon' => 'fa-circle-info']
                            };
                        @endphp
                        
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 flex flex-col sm:flex-row gap-6">
                            <div class="w-full sm:w-40 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 relative group-hover:shadow-md transition-all">
                                @if($activeTransaction->unit->image)
                                    <img src="{{ Storage::url($activeTransaction->unit->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-house text-3xl mb-2"></i>
                                        <span class="text-[10px]">No Image</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wide">
                                        Tipe {{ $activeTransaction->unit->type }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $activeTransaction->unit->location->name ?? 'Lokasi Dihapus' }}</h3>
                                <p class="text-sm text-slate-500 font-medium mb-4">
                                    Blok {{ $activeTransaction->unit->block_number }} • 
                                    LT: {{ $activeTransaction->unit->land_area }}m² / LB: {{ $activeTransaction->unit->building_area }}m²
                                </p>
                                
                                <div class="flex items-center gap-6 text-sm border-t border-slate-100 pt-4">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Harga Unit</p>
                                        <p class="font-bold text-slate-800">Rp {{ number_format($activeTransaction->unit->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-px h-8 bg-slate-200"></div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Booking Fee</p>
                                        <p class="font-bold text-emerald-600">Rp {{ number_format($activeTransaction->booking_fee, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col justify-center items-start lg:items-end lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-8">
                            <p class="text-xs text-slate-400 font-bold uppercase mb-3 text-right w-full">Langkah Selanjutnya</p>
                            
                            @if($activeTransaction->status == 'pending')
                                <a href="{{ route('customer.transactions.show', $activeTransaction->id) }}" 
                                   class="w-full text-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                                    <i class="fa-solid fa-upload mr-2"></i> Upload Bukti Bayar
                                </a>
                                <p class="text-[10px] text-amber-600 mt-2 text-center w-full font-medium">
                                    <i class="fa-regular fa-clock"></i> Mohon transfer sebelum 24 jam.
                                </p>

                            @elseif($activeTransaction->status == 'booking_acc')
                                <a href="{{ route('customer.transactions.show', $activeTransaction->id) }}" 
                                   class="w-full text-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-purple-200 hover:-translate-y-0.5">
                                    <i class="fa-solid fa-folder-open mr-2"></i> Upload Berkas KPR
                                </a>

                            @else
                                <a href="{{ route('customer.transactions.show', $activeTransaction->id) }}" 
                                   class="w-full text-center px-6 py-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl transition-all hover:shadow-sm">
                                    Lihat Detail Transaksi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                        <i class="fa-solid fa-house-chimney text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Belum ada Unit yang dipesan</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                        Anda belum memiliki transaksi aktif. Jelajahi katalog perumahan kami dan temukan rumah impian Anda sekarang.
                    </p>
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Rumah
                    </a>
                </div>
            @endif
        </div>

        {{-- Recent Activity --}}
        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> Riwayat Aktivitas
            </h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                @if($recentActivities->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($recentActivities as $trx)
                            <div class="p-4 sm:px-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 shrink-0
                                        {{ $trx->status == 'sold' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-50 text-blue-600' }}">
                                        @if($trx->status == 'sold')
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-file-invoice"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Booking Unit {{ $trx->unit->block_number ?? '?' }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $trx->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusLabel = match($trx->status) {
                                            'sold' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            'canceled' => 'Dibatalkan',
                                            default => 'Proses'
                                        };
                                        $statusClass = match($trx->status) {
                                            'sold' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'rejected' => 'bg-red-50 text-red-700 border-red-100',
                                            'canceled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            default => 'bg-blue-50 text-blue-700 border-blue-100'
                                        };
                                    @endphp
                                    <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 text-center">
                        <a href="{{ route('customer.transactions.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition-all">
                            Lihat Semua Riwayat
                        </a>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <p class="text-sm text-slate-400 font-medium italic">Belum ada riwayat aktivitas.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection