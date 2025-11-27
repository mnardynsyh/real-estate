@extends('layouts.public') 
{{-- ^^^ Ini fungsinya memanggil kerangka layout (navbar + footer) --}}

@section('title', 'Beranda')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative bg-slate-900 py-20 lg:py-32 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=2670&auto=format&fit=crop" 
             class="absolute inset-0 w-full h-full object-cover opacity-40" alt="Background">
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-blue-600/20 border border-blue-500/30 text-blue-300 text-xs font-bold mb-6 backdrop-blur-sm">
                #1 Platform Properti Terpercaya
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6">
                Temukan Hunian <br> <span class="text-blue-500">Masa Depan</span> Anda
            </h1>
            <p class="text-lg text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                Kami menyediakan pilihan rumah subsidi dan komersil terbaik dengan lokasi strategis dan proses KPR yang mudah.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('catalog') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/50 hover:-translate-y-1">
                    Lihat Semua Unit
                </a>
                <a href="#featured" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold rounded-xl transition-all backdrop-blur-sm">
                    Unit Unggulan
                </a>
            </div>
        </div>
    </section>

    {{-- FEATURED UNITS (Cuplikan Katalog) --}}
    <section id="featured" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Unit Pilihan Terbaik</h2>
                <p class="text-slate-500 mt-2">Rekomendasi hunian eksklusif yang siap huni.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredUnits as $unit)
                    <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                        {{-- Image --}}
                        <div class="relative h-64 overflow-hidden bg-slate-200">
                            @if($unit->image)
                                <img src="{{ Storage::url($unit->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-house text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-md">
                                AVAILABLE
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $unit->location->name }}</h3>
                                    <p class="text-sm text-slate-500">Blok {{ $unit->block_number }} • Tipe {{ $unit->type }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 my-4 text-xs text-slate-500 font-medium">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-ruler-combined"></i> {{ $unit->land_area }}m² Tanah</span>
                                <span class="flex items-center gap-1"><i class="fa-regular fa-building"></i> {{ $unit->building_area }}m² Bangunan</span>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Harga Mulai</p>
                                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($unit->price / 1000000, 0) }} Juta</p>
                                </div>
                                <a href="{{ route('unit.show', $unit->id) }}" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">
                    Lihat Katalog Lengkap <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection