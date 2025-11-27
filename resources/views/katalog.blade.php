@extends('layouts.public')
@section('title', 'Katalog Unit')

@section('content')
<div class="bg-[#F0F2F5] min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Katalog Unit</h1>
            <p class="text-slate-500 mt-2">Temukan hunian impian Anda dari berbagai pilihan lokasi terbaik.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- SIDEBAR FILTER (Sticky on Desktop) --}}
            <div class="w-full lg:w-1/4 shrink-0">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 lg:sticky lg:top-24">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-filter text-blue-600"></i> Filter
                        </h3>
                        <a href="{{ route('catalog') }}" class="text-xs font-semibold text-slate-400 hover:text-red-500 transition-colors">
                            Reset
                        </a>
                    </div>

                    <form action="{{ route('catalog') }}" method="GET" class="space-y-5">
                        
                        {{-- Lokasi --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lokasi</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-3 top-3 text-slate-400"></i>
                                <select name="location" class="w-full pl-9 text-sm border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 text-slate-700 bg-slate-50 hover:bg-white transition-colors cursor-pointer">
                                    <option value="">Semua Lokasi</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }} ({{ $loc->city }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Range Harga --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Range Harga</label>
                            <div class="space-y-3">
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                           class="w-full pl-9 text-sm border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50 hover:bg-white transition-colors">
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                           class="w-full pl-9 text-sm border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50 hover:bg-white transition-colors">
                                </div>
                            </div>
                        </div>

                        {{-- Tipe --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipe Rumah</label>
                            <div class="relative">
                                <i class="fa-solid fa-ruler-combined absolute left-3 top-3 text-slate-400"></i>
                                <input type="text" name="type" value="{{ request('type') }}" placeholder="Contoh: 36/72"
                                       class="w-full pl-9 text-sm border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-slate-50 hover:bg-white transition-colors">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600