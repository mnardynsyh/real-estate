@extends('layouts.public')
@section('title', 'Detail Unit')

@section('content')
<div class="bg-[#F0F2F5] min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-blue-600">
                        <i class="fa-solid fa-house mr-2"></i> Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-slate-300 mx-2 text-xs"></i>
                        <a href="{{ route('catalog') }}" class="text-slate-500 hover:text-blue-600">Katalog</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-slate-300 mx-2 text-xs"></i>
                        <span class="text-slate-900 font-medium">{{ $unit->location->name }} - {{ $unit->block_number }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: GAMBAR UTAMA --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200 relative group">
                    {{-- Status Badge --}}
                    @php
                        $badgeColor = match($unit->status) {
                            'available' => 'bg-emerald-500',
                            'booked' => 'bg-amber-500',
                            'sold' => 'bg-slate-800',
                        };
                    @endphp
                    <div class="absolute top-6 left-6 z-10 {{ $badgeColor }} text-white px-4 py-1.5 rounded-full font-bold text-xs shadow-lg tracking-wide uppercase">
                        {{ $unit->status }}
                    </div>

                    {{-- Image Container --}}
                    <div class="relative h-[400px] md:h-[500px] bg-slate-100">
                        @if($unit->image)
                            <img src="{{ Storage::url($unit->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                                <i class="fa-solid fa-house text-6xl mb-4 opacity-30"></i>
                                <span class="font-medium">Tidak ada foto unit</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi & Spesifikasi --}}
                <div class="bg-white rounded-3xl p-8 mt-8 shadow-sm border border-slate-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-600"></i> Detail Spesifikasi
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Luas Tanah</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->land_area }} m²</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Luas Bangunan</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->building_area }} m²</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Tipe</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->type }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Blok</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->block_number }}</p>
                        </div>
                    </div>

                    <h4 class="font-bold text-slate-900 mb-3">Deskripsi Tambahan</h4>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        {{ $unit->description ?? 'Tidak ada deskripsi tambahan untuk unit ini. Hubungi marketing kami untuk informasi lebih lanjut mengenai spesifikasi teknis bangunan.' }}
                    </p>
                </div>
            </div>

            {{-- KOLOM KANAN: INFO HARGA & BOOKING (Sticky) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blue-900/5 border border-slate-200 lg:sticky lg:top-24">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 leading-tight mb-2">{{ $unit->location->name }}</h1>
                        <div class="flex items-start gap-2 text-slate-500 text-sm">
                            <i class="fa-solid fa-location-dot mt-1 text-blue-500"></i>
                            <p>{{ $unit->location->address }}, {{ $unit->location->city }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5 mb-6 border border-slate-100">
                        <p class="text-xs text-slate-500 font-bold uppercase mb-1">Harga Unit</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($unit->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">*Harga belum termasuk biaya proses KPR</p>
                    </div>

                    {{-- LOGIKA TOMBOL BOOKING --}}
                    @if($unit->status == 'available')
                        @auth
                            @if(Auth::user()->role == 'customer')
                                {{-- User Customer Login --}}
                                <form action="#" method="GET"> 
                                    {{-- NANTI DIGANTI: route('customer.transactions.create', $unit->id) --}}
                                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-cart-shopping"></i> Booking Sekarang
                                    </button>
                                </form>
                                <p class="text-center text-xs text-slate-400 mt-3">
                                    <i class="fa-solid fa-shield-halved mr-1"></i> Transaksi Aman & Terverifikasi
                                </p>
                            @else
                                {{-- User Admin Login --}}
                                <div class="w-full py-4 bg-slate-100 border border-slate-200 text-slate-500 font-bold rounded-xl text-center cursor-not-allowed">
                                    <i class="fa-solid fa-user-lock mr-2"></i> Login sebagai Customer
                                </div>
                            @endif
                        @else
                            {{-- Belum Login --}}
                            <a href="{{ route('login') }}" class="block w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-center transition-all shadow-lg flex items-center justify-center gap-2">
                                <i class="fa-solid fa-right-to-bracket"></i> Login untuk Booking
                            </a>
                            <p class="text-center text-xs text-slate-400 mt-3">Masuk ke akun Anda untuk memproses pemesanan.</p>
                        @endauth
                    @else
                        {{-- Status Booked/Sold --}}
                        <button disabled class="w-full py-4 bg-slate-200 text-slate-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban"></i> Unit Tidak Tersedia
                        </button>
                    @endif

                    {{-- Kontak Marketing --}}
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-center text-xs font-bold text-slate-400 uppercase mb-3">Butuh Bantuan?</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-green-500 text-green-600 font-bold rounded-xl hover:bg-green-50 transition-colors">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Chat Marketing
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- RELATED UNITS (Jika ada) --}}
        @if($relatedUnits->count() > 0)
            <div class="mt-16 pt-10 border-t border-slate-200">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">Unit Lain di Lokasi Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedUnits as $related)
                        <a href="{{ route('unit.show', $related->id) }}" class="group bg-white rounded-2xl border border-slate-200 p-4 hover:shadow-lg transition-all flex items-center gap-4">
                            <div class="w-20 h-20 bg-slate-200 rounded-xl overflow-hidden shrink-0">
                                @if($related->image)
                                    <img src="{{ Storage::url($related->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fa-solid fa-house"></i></div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 mb-1">Blok {{ $related->block_number }}</p>
                                <p class="font-bold text-blue-600">Rp {{ number_format($related->price / 1000000, 0) }} Juta</p>
                                <span class="text-[10px] text-slate-400 mt-1 block group-hover:text-blue-500 transition-colors">Lihat Detail <i class="fa-solid fa-arrow-right ml-1"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection