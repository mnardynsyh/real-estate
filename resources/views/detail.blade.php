@extends('layouts.public')
@section('title', 'Detail Unit')

@section('content')
<div class="bg-[#F0F2F5] min-h-screen py-10" x-data="{ bookingModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $existingTrx = null;
            if(Auth::check() && Auth::user()->role == 'customer') {
                $existingTrx = \App\Models\Transaction::where('user_id', Auth::id())
                    ->where('unit_id', $unit->id)
                    ->whereNotIn('status', ['rejected', 'canceled'])
                    ->first();
            }
            $profileIncomplete = Auth::check() && Auth::user()->role === 'customer' && (!Auth::user()->customer?->nik || !Auth::user()->customer?->phone);
        @endphp

        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house mr-2"></i> Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-slate-300 mx-2 text-xs"></i>
                        <a href="{{ route('catalog') }}" class="text-slate-500 hover:text-blue-600 transition-colors">Katalog</a>
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
            {{-- Left --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200 relative group">
                    @php
                        $badgeColor = match($unit->status) {
                            'available' => 'bg-emerald-500',
                            'booked' => 'bg-amber-500',
                            'sold' => 'bg-slate-800',
                            default => 'bg-gray-500'
                        };
                    @endphp
                    <div class="absolute top-6 left-6 z-10 {{ $badgeColor }} text-white px-4 py-1.5 rounded-full font-bold text-xs shadow-lg tracking-wide uppercase">
                        {{ $unit->status }}
                    </div>

                    <div class="relative h-[400px] md:h-[500px] bg-slate-100 flex items-center justify-center overflow-hidden">
                        @if($unit->image)
                            <img src="{{ Storage::url($unit->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="text-center text-slate-400">
                                <i class="fa-solid fa-house text-6xl mb-4 opacity-30"></i>
                                <p class="font-medium">Tidak ada foto unit</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div class="bg-white rounded-3xl p-8 mt-8 shadow-sm border border-slate-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-600"></i> Detail Spesifikasi
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:border-blue-200 transition-colors">
                            <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Luas Tanah</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->land_area }} m²</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:border-blue-200 transition-colors">
                            <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Luas Bangunan</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->building_area }} m²</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:border-blue-200 transition-colors">
                            <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Tipe</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->type }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center hover:border-blue-200 transition-colors">
                            <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Blok</p>
                            <p class="text-lg font-bold text-slate-800">{{ $unit->block_number }}</p>
                        </div>
                    </div>

                    <h4 class="font-bold text-slate-900 mb-3">Deskripsi Tambahan</h4>
                    <p class="text-slate-600 leading-relaxed text-sm text-justify">
                        {{ $unit->description ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>
                </div>
            </div>

            {{-- Right sticky --}}
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

                    {{-- TOMBOL BOOKING --}}
                    @if($unit->status == 'available')
                        @auth
                            @if(Auth::user()->role == 'customer')
                                {{-- Profile incomplete -> cannot booking --}}
                                @if($profileIncomplete)
                                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm mb-3">
                                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                                        Profil Anda belum lengkap (NIK / WhatsApp). Lengkapi profil untuk melakukan booking.
                                        <div class="mt-3">
                                            <a href="{{ route('customer.profile.edit') }}" class="inline-block px-4 py-2 bg-amber-600 text-white rounded-lg text-xs font-bold">Lengkapi Profil</a>
                                        </div>
                                    </div>
                                {{-- Sudah punya transaksi aktif --}}
                                @elseif($existingTrx)
                                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl text-center mb-3">
                                        <p class="text-xs text-blue-600 font-bold mb-2">Anda sudah melakukan pemesanan untuk unit ini</p>
                                        <a href="{{ route('customer.transactions.index') }}" class="block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-md">
                                            Cek Status
                                        </a>
                                    </div>
                                {{-- Boleh Booking (modal tanpa file) --}}
                                @else
                                    <button @click="bookingModal = true" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-cart-shopping"></i> Booking Sekarang
                                    </button>
                                    <p class="text-center text-xs text-slate-400 mt-3">
                                        <i class="fa-solid fa-shield-halved mr-1"></i> Transaksi Aman & Terverifikasi
                                    </p>
                                @endif
                            @else
                                <div class="w-full py-4 bg-slate-100 border border-slate-200 text-slate-500 font-bold rounded-xl text-center cursor-not-allowed">
                                    <i class="fa-solid fa-user-lock mr-2"></i> Mode Admin
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-center transition-all shadow-lg flex items-center justify-center gap-2">
                                <i class="fa-solid fa-right-to-bracket"></i> Login untuk Booking
                            </a>
                            <p class="text-center text-xs text-slate-400 mt-3">Masuk ke akun Anda untuk memproses pemesanan.</p>
                        @endauth
                    @else
                        <button disabled class="w-full py-4 bg-slate-200 text-slate-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban"></i> Unit Tidak Tersedia
                        </button>
                    @endif

                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-center text-xs font-bold text-slate-400 uppercase mb-3">Butuh Bantuan?</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-green-500 text-green-600 font-bold rounded-xl hover:bg-green-50 transition-colors">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Chat Marketing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BOOKING (tanpa upload bukti) --}}
    <div x-show="bookingModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        <div @click="bookingModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>

        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl transform transition-all flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Booking Unit</h3>
                    <p class="text-xs text-slate-500 font-medium">Blok {{ $unit->block_number }} - {{ $unit->location->name }}</p>
                </div>
                <button @click="bookingModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar">
                <form action="{{ route('customer.transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                    {{-- 1. KONFIRMASI DATA DIRI --}}
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">1. Data Pemesan</h4>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Nama:</span>
                                <span class="font-bold text-slate-800">{{ Auth::user()->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Email:</span>
                                <span class="font-bold text-slate-800">{{ Auth::user()->email ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">WhatsApp:</span>
                                <span class="font-bold text-slate-800">{{ Auth::user()->customer->phone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">NIK:</span>
                                <span class="font-bold text-slate-800">{{ Auth::user()->customer->nik ?? 'Belum Diisi' }}</span>
                            </div>
                        </div>
                        @if(Auth::check() && !Auth::user()->customer?->nik)
                            <div class="mt-2 p-2 bg-amber-50 text-amber-700 text-xs rounded border border-amber-200 flex gap-2 items-center">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Mohon lengkapi NIK dan WhatsApp di profil terlebih dahulu.</span>
                            </div>
                        @endif
                    </div>

                    {{-- 2. INFO PEMBAYARAN --}}
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">2. Pembayaran Booking Fee</h4>
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center gap-4 mb-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-blue-800 font-bold uppercase">Transfer ke BCA</p>
                                <p class="text-lg font-bold text-slate-800 font-mono">123-456-7890</p>
                                <p class="text-xs text-slate-600">a.n PT Developer Perumahan</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center px-2">
                            <span class="text-sm font-medium text-slate-600">Nominal Transfer:</span>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($unit->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- 3. NOTE: upload bukti dilakukan di halaman Transaksi --}}
                    <div class="mb-2">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">3. Upload Bukti</h4>
                        <p class="text-sm text-slate-600">Setelah Anda mengirimkan booking, silakan upload bukti transfer di halaman <a href="{{ route('customer.transactions.index') }}" class="text-blue-600 underline">Transaksi Saya</a>.</p>
                    </div>

                    <div class="mt-8 pt-4 border-t border-slate-100">
                        <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="fa-regular fa-paper-plane"></i> Kirim Booking (Tanpa Upload)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
