@extends('layouts.public')
@section('title', 'Katalog Unit')

@section('content')
<div class="bg-[#F0F2F5] min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Katalog Unit</h1>
            <p class="text-slate-500 mt-2 text-sm">
                Pilih hunian terbaik sesuai kebutuhan Anda.
            </p>
        </div>

        {{-- ============================= --}}
        {{-- FILTER CARD (Now Positioned at Top & Smaller) --}}
        {{-- ============================= --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-10">
            <form action="{{ route('catalog') }}" method="GET">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Lokasi --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Lokasi</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-3 top-[10px] text-slate-400 text-xs"></i>
                            <select name="location"
                                class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl 
                                       bg-slate-50 hover:bg-white focus:border-blue-500 focus:ring-blue-500
                                       transition-colors">
                                <option value="">Semua Lokasi</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }} ({{ $loc->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Min Price --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Harga Minimum</label>
                        <div class="relative">
                            <span class="absolute left-3 top-[10px] text-[10px] font-bold text-slate-400">Rp</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                placeholder="Min"
                                class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl
                                       bg-slate-50 hover:bg-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- Max Price --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Harga Maksimum</label>
                        <div class="relative">
                            <span class="absolute left-3 top-[10px] text-[10px] font-bold text-slate-400">Rp</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                placeholder="Max"
                                class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl
                                       bg-slate-50 hover:bg-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tipe Unit</label>
                        <div class="relative">
                            <i class="fa-solid fa-ruler-combined absolute left-3 top-[10px] text-slate-400 text-xs"></i>
                            <input type="text" name="type" value="{{ request('type') }}"
                                placeholder="Contoh: 36/72"
                                class="w-full pl-8 pr-3 py-2 text-sm border border-slate-200 rounded-xl
                                       bg-slate-50 hover:bg-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                </div>

                {{-- Button --}}
                <div class="flex justify-end gap-3 mt-4">
                    <a href="{{ route('catalog') }}"
                        class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                    <button class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 shadow-sm transition">
                        Terapkan
                    </button>
                </div>

            </form>
        </div>

        {{-- ============================= --}}
        {{-- CONTENT LIST --}}
        {{-- ============================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">

            @forelse($units as $unit)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm group overflow-hidden">

                {{-- Image --}}
                <div class="relative h-56 bg-slate-100">
                    {{-- STATUS BADGE --}}
                    @php
                        $colors = [
                            'available' => 'bg-emerald-500',
                            'booked'    => 'bg-amber-500',
                            'sold'      => 'bg-slate-800'
                        ];
                    @endphp
                    <span class="absolute top-3 left-3 px-3 py-1 text-[10px] font-bold text-white rounded-full uppercase
                                 {{ $colors[$unit->status] ?? 'bg-gray-500' }}">
                        {{ $unit->status }}
                    </span>

                    @if($unit->image)
                        <img src="{{ Storage::url($unit->image) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5 space-y-3">

                    <h3 class="font-bold text-slate-900 text-lg leading-snug">
                        {{ $unit->location->name }} - Blok {{ $unit->block_number }}
                    </h3>

                    <p class="text-[13px] text-slate-500">
                        {{ $unit->location->city }}
                    </p>

                    <div class="flex items-center gap-3 text-sm">
                        <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600">
                            LT {{ $unit->land_area }} m²
                        </span>
                        <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600">
                            LB {{ $unit->building_area }} m²
                        </span>
                        <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600">
                            Tipe {{ $unit->type }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <p class="text-xs text-slate-500">Harga mulai dari</p>
                        <p class="text-xl font-bold text-blue-600">
                            Rp {{ number_format($unit->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <a href="{{ route('unit.show', $unit->id) }}"
                        class="block text-center py-2.5 mt-2 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @empty
                <p class="text-slate-500">Tidak ada unit yang sesuai filter.</p>
            @endforelse

        </div>

        <div class="mt-10">
            {{ $units->links() }}
        </div>

    </div>
</div>
@endsection
