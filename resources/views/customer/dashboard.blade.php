@extends('layouts.customer')
@section('title', 'Dashboard Saya')

@section('content')
<div class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800">
    <div class="max-w-5xl mx-auto w-full">

        {{-- 1. WELCOME SECTION --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-sm text-slate-500 mt-1">Selamat datang di panel pelanggan perumahan kami.</p>
            </div>
            <div class="text-xs font-medium bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200 text-slate-500">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- 2. ALERT: JIKA PROFIL BELUM LENGKAP --}}
        {{-- Mengecek apakah data customer sudah ada/lengkap --}}
        @if(!Auth::user()->customer?->nik || !Auth::user()->customer?->phone)
        <div class="mb-8 p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-white border border-blue-100 flex flex-col sm:flex-row items-start gap-5 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full blur-3xl -mr-16 -mt-16 opacity-50"></div>
            
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 shadow-sm z-10">
                <i class="fa-solid fa-user-pen text-xl"></i>
            </div>
            <div class="flex-1 z-10">
                <h3 class="font-bold text-blue-900 text-base">Lengkapi Data Diri Anda</h3>
                <p class="text-sm text-blue-700 mt-1 leading-relaxed max-w-2xl">
                    Untuk melanjutkan proses booking unit dan pemberkasan KPR, mohon lengkapi data <b>NIK, No HP, dan Pekerjaan</b> Anda terlebih dahulu.
                </p>
                <a href="#" class="inline-flex items-center gap-2 mt-4 text-xs font-bold text-white bg-blue-600 px-5 py-2.5 rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-200">
                    Lengkapi Profil Sekarang <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- 3. STATUS TRANSAKSI AKTIF (CARD BESAR) --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-yellow-500"></i> Transaksi Berjalan
            </h2>

            @if($activeTransaction)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative group hover:shadow-md transition-all duration-300">
                    {{-- Status Bar Atas --}}
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode Pesanan</span>
                            <p class="text-sm font-bold text-slate-800 font-mono tracking-wide">#{{ $activeTransaction->code }}</p>
                        </div>
                        
                        @php
                            $statusConfig = match($activeTransaction->status) {
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
                                'process' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Verifikasi Admin', 'icon' => 'fa-spinner fa-spin'],
                                'booking_acc' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Upload Berkas', 'icon' => 'fa-file-upload'],
                                'docs_review' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Review Dokumen', 'icon' => 'fa-magnifying-glass'],
                                'bank_process' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank', 'icon' => 'fa-building-columns'],
                                'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Disetujui / Bayar DP', 'icon' => 'fa-check-circle'],
                                default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => ucfirst($activeTransaction->status), 'icon' => 'fa-circle-info']
                            };
                        @endphp
                        
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Info Unit --}}
                        <div class="lg:col-span-2 flex flex-col sm:flex-row gap-5">
                            {{-- Foto Unit --}}
                            <div class="w-full sm:w-32 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 relative">
                                @if($activeTransaction->unit->image)
                                    <img src="{{ Storage::url($activeTransaction->unit->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-house text-3xl mb-2"></i>
                                        <span class="text-[10px]">No Image</span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Detail Unit --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">
                                        {{ $activeTransaction->unit->type }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $activeTransaction->unit->location->name ?? 'Lokasi Dihapus' }}</h3>
                                <p class="text-sm text-slate-500 font-medium mb-4">
                                    Blok {{ $activeTransaction->unit->block_number }} • 
                                    LT: {{ $activeTransaction->unit->land_area }}m² / LB: {{ $activeTransaction->unit->building_area }}m²
                                </p>
                                
                                <div class="flex items-center gap-4 text-sm">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Harga Unit</p>
                                        <p class="font-bold text-slate-800">Rp {{ number_format($activeTransaction->unit->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-px h-8 bg-slate-200"></div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Booking Fee</p>
                                        <p class="font-bold text-slate-800">Rp {{ number_format($activeTransaction->booking_fee, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Area --}}
                        <div class="flex flex-col justify-center items-start lg:items-end lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-8">
                            <p class="text-xs text-slate-500 mb-3 text-right w-full">Langkah Selanjutnya:</p>
                            
                            @if($activeTransaction->status == 'pending')
                                <a href="#" {{-- route('customer.transactions.show', $activeTransaction->id) --}}
                                   class="w-full text-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                                    <i class="fa-solid fa-upload mr-2"></i> Upload Bukti Bayar
                                </a>
                                <p class="text-[10px] text-slate-400 mt-2 text-center w-full">Mohon transfer sebelum 24 jam.</p>

                            @elseif($activeTransaction->status == 'booking_acc')
                                <a href="#" 
                                   class="w-full text-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-purple-200 hover:-translate-y-0.5">
                                    <i class="fa-solid fa-folder-open mr-2"></i> Upload Berkas KPR
                                </a>

                            @else
                                <a href="#" 
                                   class="w-full text-center px-6 py-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl transition-all hover:shadow-sm">
                                    Lihat Detail Transaksi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- 4. STATE KOSONG (BELUM ADA BOOKING) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 sm:p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                        <i class="fa-solid fa-house-chimney text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Belum ada Unit yang dipesan</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                        Anda belum memiliki transaksi aktif. Jelajahi katalog perumahan kami dan temukan rumah impian Anda sekarang.
                    </p>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Rumah
                    </a>
                </div>
            @endif
        </div>

        {{-- 5. RIWAYAT AKTIVITAS (SIMPLE LIST) --}}
        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-4">Riwayat Aktivitas</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                @if($recentActivities->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($recentActivities as $trx)
                            <div class="p-4 sm:px-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 shrink-0
                                        {{ $trx->status == 'sold' ? 'bg-green-100 text-green-600' : 'bg-slate-100' }}">
                                        @if($trx->status == 'sold')
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-file-invoice"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Booking Unit {{ $trx->unit->block_number ?? '?' }}</p>
                                        <p class="text-xs text-slate-500">{{ $trx->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block text-[10px] font-bold px-2 py-1 rounded border 
                                        {{ $trx->status == 'sold' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Footer List --}}
                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 text-center">
                        <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">Lihat Semua Riwayat</a>
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