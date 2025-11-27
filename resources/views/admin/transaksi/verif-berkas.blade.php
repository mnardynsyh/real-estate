@extends('layouts.admin')
@section('title', 'Verifikasi Berkas')

@section('content')
<div x-data="{ 
    activeModal: null, 
    
    // Data Transaksi Aktif
    trxId: null,
    trxCode: '',
    trxName: '',
    
    // Data Dokumen (Array)
    documents: [],
    activeDoc: null, // Dokumen yang sedang di-preview
    
    // Buka Modal Periksa
    openCheckModal(id, code, name, docsData) {
        this.activeModal = 'check';
        this.trxId = id;
        this.trxCode = code;
        this.trxName = name;
        this.documents = docsData;
        
        // Set default preview ke dokumen pertama jika ada
        if (this.documents.length > 0) {
            this.activeDoc = this.documents[0];
        } else {
            this.activeDoc = null;
        }
    },

    // Ganti Preview Dokumen
    viewDoc(doc) {
        this.activeDoc = doc;
    },

    closeModal() {
        this.activeModal = null;
        this.documents = [];
        this.activeDoc = null;
    }
}" class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- 1. HEADER --}}
        <div class="shrink-0 mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verifikasi Berkas KPR</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">
                Periksa kelengkapan dokumen persyaratan (KTP, KK, NPWP, Slip Gaji) dari calon pembeli.
            </p>
        </div>

        {{-- 2. ALERT --}}
        <div class="shrink-0 flex flex-col gap-4 mb-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms 
                     class="p-4 rounded-xl bg-white border-l-4 border-emerald-500 text-slate-700 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        {{-- 3. TABLE LIST --}}
        <div class="flex-1 flex flex-col">
            <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Unit Properti</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Kelengkapan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $i => $trx)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-center text-xs font-bold text-slate-400">
                                    {{ $transactions->firstItem() + $i }}
                                </td>
                                
                                {{-- Customer --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs shrink-0 border border-purple-100">
                                            {{ substr($trx->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                                            <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $trx->code }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Unit --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 mb-1">
                                            Blok {{ $trx->unit->block_number }}
                                        </span>
                                        <p class="text-xs font-bold text-slate-800">{{ $trx->unit->location->name }}</p>
                                        <p class="text-xs text-slate-500">Tipe {{ $trx->unit->type }}</p>
                                    </div>
                                </td>

                                {{-- Kelengkapan Dokumen --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                        <i class="fa-regular fa-folder-open"></i>
                                        {{ $trx->documents->count() }} Berkas
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Terakhir update: {{ $trx->updated_at->diffForHumans() }}
                                    </p>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <button @click="openCheckModal({{ $trx->id }}, '{{ $trx->code }}', '{{ $trx->user->name }}', {{ $trx->documents }})" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-lg transition-all shadow-md shadow-purple-200 hover:-translate-y-0.5">
                                        <i class="fa-solid fa-magnifying-glass"></i> Periksa
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-clipboard-check text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Tidak ada antrian berkas</h3>
                                        <p class="text-sm font-medium text-slate-500 mt-1">Semua dokumen masuk telah diperiksa.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARD VIEW --}}
            <div class="lg:hidden space-y-4">
                @foreach($transactions as $trx)
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        {{-- Stripe Ungu --}}
                        <div class="absolute top-0 left-0 w-1 h-full bg-purple-500"></div>

                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs border border-purple-100">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                                        <p class="text-xs text-slate-500 font-mono">{{ $trx->code }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 bg-purple-100 text-purple-700 rounded border border-purple-200">
                                    Review
                                </span>
                            </div>

                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Unit:</span>
                                    <span class="font-bold text-slate-700">{{ $trx->unit->location->name }} ({{ $trx->unit->block_number }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Jumlah Berkas:</span>
                                    <span class="font-bold text-slate-700">{{ $trx->documents->count() }} File</span>
                                </div>
                            </div>

                            <button @click="openCheckModal({{ $trx->id }}, '{{ $trx->code }}', '{{ $trx->user->name }}', {{ $trx->documents }})" 
                                    class="w-full py-2.5 bg-purple-600 text-white rounded-lg text-xs font-bold hover:bg-purple-700 shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-magnifying-glass"></i> Periksa Berkas
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            @if($transactions->hasPages())
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-4 py-3 mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================= MODAL SUPER: PERIKSA BERKAS ================= --}}
    <div x-show="activeModal === 'check'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-5xl h-[85vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Header Modal --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Periksa Dokumen</h3>
                    <p class="text-xs text-slate-500">Transaksi <span class="font-mono font-bold" x-text="trxCode"></span> - <span x-text="trxName"></span></p>
                </div>
                <button @click="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Body: Layout Split 2 Kolom --}}
            <div class="flex-1 flex overflow-hidden">
                
                {{-- KIRI: LIST BERKAS --}}
                <div class="w-1/3 bg-slate-50 border-r border-slate-200 flex flex-col">
                    <div class="p-4 overflow-y-auto flex-1 space-y-2 custom-scrollbar">
                        <template x-if="documents.length === 0">
                            <div class="text-center py-10 text-slate-400 text-sm">
                                Tidak ada dokumen diupload.
                            </div>
                        </template>

                        <template x-for="doc in documents" :key="doc.id">
                            <button @click="viewDoc(doc)" 
                                    class="w-full text-left p-3 rounded-xl border transition-all flex items-center gap-3 group"
                                    :class="activeDoc && activeDoc.id === doc.id ? 'bg-white border-blue-500 shadow-md ring-1 ring-blue-500' : 'bg-white border-slate-200 hover:border-blue-300'">
                                
                                {{-- Icon Type --}}
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 text-lg"
                                     :class="activeDoc && activeDoc.id === doc.id ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500'">
                                    <i class="fa-regular fa-file-image"></i>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wide" x-text="doc.type"></p>
                                    <p class="text-[10px] text-slate-500 truncate" x-text="'File ID: ' + doc.id"></p>
                                </div>

                                {{-- Status Indicator (Nanti) --}}
                                <i class="fa-solid fa-chevron-right text-xs text-slate-300" 
                                   :class="activeDoc && activeDoc.id === doc.id ? 'text-blue-500' : ''"></i>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- KANAN: PREVIEW AREA --}}
                <div class="w-2/3 bg-slate-800 flex flex-col justify-center items-center relative overflow-hidden">
                    {{-- Jika ada dokumen dipilih --}}
                    <template x-if="activeDoc">
                        <div class="w-full h-full flex items-center justify-center p-4">
                            <img :src="'/storage/' + activeDoc.file_path" 
                                 class="max-w-full max-h-full object-contain rounded shadow-lg">
                        </div>
                    </template>

                    {{-- Jika kosong --}}
                    <template x-if="!activeDoc">
                        <div class="text-slate-500 flex flex-col items-center">
                            <i class="fa-regular fa-image text-4xl mb-2 opacity-50"></i>
                            <p class="text-sm">Pilih dokumen di sebelah kiri untuk melihat preview.</p>
                        </div>
                    </template>
                    
                    {{-- Overlay Info Nama File di Preview --}}
                    <template x-if="activeDoc">
                        <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-black/50 backdrop-blur-md text-white px-4 py-1.5 rounded-full text-xs font-medium">
                            Preview: <span x-text="activeDoc.type"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Footer: Action Buttons --}}
            <div class="px-6 py-4 border-t border-slate-200 bg-white flex justify-between items-center shrink-0 gap-4">
                
                {{-- Form Revisi --}}
                <form :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/revise'" method="POST" class="flex-1 flex gap-2">
                    @csrf @method('PATCH')
                    <input type="text" name="admin_note" required placeholder="Tulis catatan jika ada berkas yang salah/kurang..." 
                           class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-red-500 focus:ring-red-500">
                    <button type="submit" class="px-5 py-2.5 bg-white border border-red-200 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50 transition-colors">
                        Minta Revisi
                    </button>
                </form>

                {{-- Form Approve --}}
                <form :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/approve'" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> Validasi Lanjut Bank
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection