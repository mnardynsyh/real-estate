@extends('layouts.admin')
@section('title', 'Detail Transaksi')

@section('content')
<div class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header & Back --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.transactions.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900">
                        Detail Transaksi
                    </h1>
                </div>
                <p class="text-slate-600 mt-1 text-sm font-medium ml-7">
                    Kode: <span class="font-mono font-bold text-slate-800">{{ $trx->code }}</span>
                </p>
            </div>
            
            {{-- Status Badge Global --}}
             @php
                $statusColors = [
                    'pending' => 'bg-slate-200 text-slate-600',
                    'process' => 'bg-amber-100 text-amber-700',
                    'booking_acc' => 'bg-blue-100 text-blue-700',
                    'docs_review' => 'bg-purple-100 text-purple-700',
                    'bank_review' => 'bg-indigo-100 text-indigo-700',
                    'bank_process' => 'bg-indigo-100 text-indigo-700',
                    'sold' => 'bg-emerald-100 text-emerald-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    'canceled' => 'bg-red-100 text-red-700',
                ];
                $statusLabel = ucfirst(str_replace('_', ' ', $trx->status));
                $badgeClass = $statusColors[$trx->status] ?? 'bg-slate-100 text-slate-600';
            @endphp
            <span class="px-4 py-2 rounded-lg text-sm font-bold {{ $badgeClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Unit & Documents --}}
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                {{-- Unit Card --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row">
                        <div class="sm:w-48 h-48 sm:h-auto bg-slate-200 relative">
                             @if($trx->unit->image)
                                <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ $trx->unit->location->name }}</h3>
                                    <p class="text-sm text-slate-500">Blok {{ $trx->unit->block_number }} • Tipe {{ $trx->unit->type }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 uppercase font-bold">Harga Unit</p>
                                    <p class="text-lg font-bold text-slate-800">Rp {{ number_format($trx->unit->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-slate-400 text-xs">Luas Tanah</p>
                                    <p class="font-medium">{{ $trx->unit->land_area }} m²</p>
                                </div>
                                <div>
                                    <p class="text-slate-400 text-xs">Luas Bangunan</p>
                                    <p class="font-medium">{{ $trx->unit->building_area }} m²</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-xs text-slate-400 uppercase font-bold mb-1">Customer</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $trx->user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $trx->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Documents Grid --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-900 text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-folder-open text-blue-500"></i> Dokumen KPR
                    </h3>
                    
                    @if($trx->documents->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($trx->documents as $doc)
                                <div class="p-4 border rounded-xl {{ $doc->status == 'invalid' ? 'border-red-200 bg-red-50' : ($doc->status == 'valid' ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-slate-50') }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider {{ $doc->status == 'invalid' ? 'text-red-700' : 'text-slate-600' }}">{{ $doc->type }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $doc->updated_at->diffForHumans() }}</p>
                                        </div>
                                        @if($doc->status == 'valid')
                                            <span class="text-emerald-600 text-xs font-bold bg-white px-2 py-1 rounded border border-emerald-100"><i class="fa-solid fa-check"></i> Valid</span>
                                        @elseif($doc->status == 'invalid')
                                            <span class="text-red-600 text-xs font-bold bg-white px-2 py-1 rounded border border-red-100"><i class="fa-solid fa-xmark"></i> Invalid</span>
                                        @else
                                            <span class="text-slate-500 text-xs font-bold bg-white px-2 py-1 rounded border border-slate-200">Pending</span>
                                        @endif
                                    </div>

                                    <div class="aspect-video bg-white rounded-lg border border-slate-200 overflow-hidden flex items-center justify-center relative group">
                                        @php
                                            $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']);
                                        @endphp
                                        
                                        @if($isImage)
                                            <img src="{{ Storage::url($doc->file_path) }}" class="w-full h-full object-contain">
                                        @else
                                            <div class="text-center text-slate-400">
                                                <i class="fa-regular fa-file-pdf text-3xl"></i>
                                                <p class="text-[10px] mt-1">PDF Document</p>
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="px-3 py-1.5 bg-white text-slate-800 text-xs font-bold rounded-lg hover:bg-blue-50 transition">
                                                Lihat File
                                            </a>
                                        </div>
                                    </div>

                                    @if($doc->note)
                                        <div class="mt-3 p-2 bg-white rounded border border-red-100">
                                            <p class="text-xs text-red-600 italic">"{{ $doc->note }}"</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400">
                            <p class="text-sm">Belum ada dokumen yang diunggah customer.</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column: Payment & Actions --}}
            <div class="lg:col-span-1 flex flex-col gap-6">
                
                {{-- Payment Info --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-4">Pembayaran Booking</h3>
                    
                    <div class="mb-6">
                        <p class="text-xs text-slate-400">Total Nominal</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}</p>
                    </div>

                    @if($trx->booking_proof)
                        <div class="rounded-xl overflow-hidden border border-slate-200 relative group">
                            <img src="{{ Storage::url($trx->booking_proof) }}" class="w-full h-48 object-cover blur-[2px] group-hover:blur-0 transition-all">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <a href="{{ Storage::url($trx->booking_proof) }}" target="_blank" class="bg-white/90 text-slate-800 px-4 py-2 rounded-lg text-xs font-bold shadow-lg hover:bg-white transition">
                                    Lihat Bukti Transfer
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-400 text-xs">
                            Belum ada bukti transfer
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                @if(in_array($trx->status, ['booking_acc', 'docs_review']))
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                        <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-4">Aksi Admin</h3>
                        
                        {{-- Info --}}
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-xs"></i>
                            <p class="text-xs text-blue-700">
                                Periksa kelengkapan dokumen di sebelah kiri. Jika valid, lanjutkan ke proses bank. Jika tidak, minta revisi.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            {{-- Validasi --}}
                            <form action="{{ route('admin.transactions.documents.approve', $trx->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all">
                                    <i class="fa-solid fa-check-double mr-2"></i> Validasi & Lanjut Bank
                                </button>
                            </form>

                            {{-- Revisi --}}
                            <div x-data="{ open: false }">
                                <button @click="open = !open" type="button" class="w-full py-2.5 bg-white border border-amber-300 text-amber-700 text-sm font-bold rounded-lg hover:bg-amber-50 transition-all">
                                    Minta Revisi Global
                                </button>
                                
                                <div x-show="open" x-transition class="mt-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                    <form action="{{ route('admin.transactions.documents.revise', $trx->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Catatan Revisi</label>
                                        <textarea name="admin_note" rows="3" required class="w-full text-sm border-slate-300 rounded-lg mb-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan dokumen apa yang kurang..."></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="open = false" class="px-3 py-1 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</button>
                                            <button type="submit" class="px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded-lg hover:bg-amber-700">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($trx->status == 'rejected')
                    <div class="bg-red-50 border border-red-100 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-2 text-red-700">
                            <i class="fa-solid fa-circle-xmark text-xl"></i>
                            <h3 class="font-bold">Transaksi Ditolak</h3>
                        </div>
                        <p class="text-xs text-red-600 mb-1 font-bold">Alasan:</p>
                        <p class="text-sm text-red-800 italic">"{{ $trx->admin_note }}"</p>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>
@endsection