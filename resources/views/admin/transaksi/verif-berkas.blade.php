@extends('layouts.admin')
@section('title', 'Verifikasi Berkas')

@section('content')
<div 
    x-data="verifBerkas()" 
    class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800"
>

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- HEADER --}}
        <div class="shrink-0 mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verifikasi Dokumen KPR</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">
                Periksa kelengkapan dan validitas berkas sebelum diajukan ke Bank.
            </p>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div x-data="{show:true}" x-show="show" class="mb-6 p-4 bg-white border-l-4 border-emerald-500 rounded-xl shadow-sm flex justify-between items-center">
                <div class="flex items-center gap-3 text-slate-700">
                    <i class="fa-solid fa-check-circle text-emerald-500 text-lg"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show=false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- TABLE LIST --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <table class="w-full border-collapse text-left">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase w-16 text-center">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Unit</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Progress Validasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $i => $trx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center text-xs font-bold text-slate-400">
                                {{ $transactions->firstItem() + $i }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                                        <p class="text-xs text-slate-500 font-mono">{{ $trx->code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    Blok {{ $trx->unit->block_number }}
                                </span>
                                <p class="text-xs font-bold text-slate-800 mt-1">{{ $trx->unit->location->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{-- Hitung Progress --}}
                                @php
                                    $totalDocs = $trx->documents->count();
                                    $validDocs = $trx->documents->where('status', 'valid')->count();
                                    $invalidDocs = $trx->documents->where('status', 'invalid')->count();
                                @endphp
                                
                                <div class="flex flex-col items-center gap-1">
                                    <div class="flex gap-1">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">
                                            {{ $validDocs }} Valid
                                        </span>
                                        @if($invalidDocs > 0)
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-700">
                                                {{ $invalidDocs }} Revisi
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400">dari {{ $totalDocs }} dokumen</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    @click="openCheckModal(
                                        {{ $trx->id }},
                                        '{{ $trx->code }}',
                                        '{{ $trx->user->name }}',
                                        {{ json_encode($trx->documents) }}
                                    )"
                                    class="px-4 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-md shadow-indigo-200 flex items-center gap-2 mx-auto"
                                >
                                    <i class="fa-solid fa-magnifying-glass"></i> Periksa
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 opacity-50"></i>
                                <p>Tidak ada antrian verifikasi berkas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $transactions->links() }}
    </div>

    {{-- ================================ MODAL PERIKSA ================================ --}}
    <div 
        x-show="activeModal === 'check'" 
        class="fixed inset-0 z-50 flex items-center justify-center px-4" 
        style="display:none"
        x-transition.opacity
    >
        {{-- Backdrop --}}
        <div @click="closeModal" class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-6xl h-[90vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            {{-- 1. Modal Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Periksa Dokumen</h3>
                    <p class="text-xs text-slate-500">
                        Transaksi <span class="font-mono font-bold" x-text="trxCode"></span> — <span x-text="trxName"></span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right mr-4 hidden lg:block">
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Status Dokumen</p>
                        <div class="flex gap-2 mt-1">
                            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500"><div class="w-2 h-2 rounded-full bg-slate-300"></div> Pending</span>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-600"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> Valid</span>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-red-600"><div class="w-2 h-2 rounded-full bg-red-500"></div> Invalid</span>
                        </div>
                    </div>
                    <button @click="closeModal" class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            {{-- 2. Modal Body --}}
            <div class="flex-1 flex overflow-hidden">
                
                {{-- LEFT SIDEBAR: LIST DOKUMEN --}}
                <div class="w-80 bg-slate-50 border-r border-slate-200 flex flex-col shrink-0">
                    <div class="p-4 overflow-y-auto flex-1 space-y-2 custom-scrollbar">
                        <template x-for="doc in documents" :key="doc.id">
                            <button 
                                @click="viewDoc(doc)"
                                class="w-full text-left p-3 rounded-xl border flex items-center gap-3 transition relative overflow-hidden"
                                :class="isActive(doc) ? 'bg-white border-indigo-500 ring-1 ring-indigo-500 shadow-md z-10' : 'bg-white border-slate-200 hover:border-indigo-300'"
                            >
                                {{-- Status Indicator Bar --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1"
                                     :class="{
                                        'bg-slate-200': doc.status === 'pending',
                                        'bg-emerald-500': doc.status === 'valid',
                                        'bg-red-500': doc.status === 'invalid'
                                     }">
                                </div>

                                {{-- Icon --}}
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg shrink-0 ml-1"
                                     :class="{
                                        'bg-emerald-100 text-emerald-600': doc.status === 'valid',
                                        'bg-red-100 text-red-600': doc.status === 'invalid',
                                        'bg-slate-100 text-slate-500': doc.status === 'pending'
                                     }">
                                    <template x-if="doc.status === 'valid'"><i class="fa-solid fa-check"></i></template>
                                    <template x-if="doc.status === 'invalid'"><i class="fa-solid fa-xmark"></i></template>
                                    <template x-if="doc.status === 'pending'"><i class="fa-regular fa-file"></i></template>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 uppercase truncate" x-text="doc.type"></p>
                                    <p class="text-[10px] text-slate-500 truncate" x-text="doc.status === 'pending' ? 'Belum diperiksa' : (doc.status === 'valid' ? 'Valid' : 'Perlu Revisi')"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- RIGHT MAIN: PREVIEW & ACTION --}}
                <div class="flex-1 flex flex-col bg-slate-100 relative">
                    
                    {{-- Area Gambar --}}
                    <div class="flex-1 overflow-hidden relative flex items-center justify-center p-6">
                        <template x-if="activeDoc">
                            <div class="w-full h-full flex items-center justify-center">
                                {{-- Jika Gambar --}}
                                <template x-if="isImage(activeDoc.file_path)">
                                    <img :src="'/storage/' + activeDoc.file_path" class="max-w-full max-h-full object-contain shadow-lg rounded bg-white">
                                </template>
                                {{-- Jika PDF --}}
                                <template x-if="!isImage(activeDoc.file_path)">
                                    <div class="text-center">
                                        <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center text-red-500 shadow-sm mx-auto mb-4 text-4xl">
                                            <i class="fa-regular fa-file-pdf"></i>
                                        </div>
                                        <p class="text-slate-500 text-sm mb-4">Preview PDF tidak tersedia di sini.</p>
                                        <a :href="'/storage/' + activeDoc.file_path" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-indigo-200 shadow-lg hover:bg-indigo-700">
                                            Buka File PDF
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!activeDoc">
                            <div class="text-slate-400 flex flex-col items-center">
                                <i class="fa-solid fa-arrow-left-long text-3xl mb-2"></i>
                                <p>Pilih dokumen dari daftar di sebelah kiri.</p>
                            </div>
                        </template>
                    </div>

                    {{-- Action Bar Per Item (Floating Bottom) --}}
                    <template x-if="activeDoc">
                        <div class="bg-white border-t border-slate-200 p-4 shrink-0 flex items-center justify-between gap-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                            
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Status Dokumen Ini:</p>
                                <div class="flex items-center gap-2">
                                    <span x-show="activeDoc.status === 'pending'" class="badge bg-slate-100 text-slate-600 border-slate-200">Pending</span>
                                    <span x-show="activeDoc.status === 'valid'" class="badge bg-emerald-100 text-emerald-700 border-emerald-200"><i class="fa-solid fa-check mr-1"></i> Valid</span>
                                    <span x-show="activeDoc.status === 'invalid'" class="badge bg-red-100 text-red-700 border-red-200"><i class="fa-solid fa-xmark mr-1"></i> Invalid</span>
                                    
                                    {{-- Tampilkan Catatan Jika Invalid --}}
                                    <span x-show="activeDoc.status === 'invalid' && activeDoc.note" class="text-xs text-red-600 italic border-l-2 border-red-200 pl-2 ml-2" x-text="'Catatan: ' + activeDoc.note"></span>
                                </div>
                            </div>

                            {{-- Tombol Aksi (AJAX) --}}
                            <div class="flex items-center gap-2">
                                {{-- Tombol Invalid (Show Input) --}}
                                <div x-data="{ openReject: false, note: '' }" class="relative">
                                    <button @click="openReject = !openReject" class="btn-action bg-white border-red-200 text-red-600 hover:bg-red-50">
                                        <i class="fa-solid fa-xmark"></i> Tolak / Revisi
                                    </button>
                                    
                                    {{-- Popover Input Revisi --}}
                                    <div x-show="openReject" @click.outside="openReject = false" 
                                         class="absolute bottom-full right-0 mb-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 p-4 z-50"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0">
                                        <p class="text-xs font-bold text-slate-700 mb-2">Alasan Penolakan:</p>
                                        <textarea x-model="note" class="w-full text-sm border-slate-300 rounded-lg focus:ring-red-500 focus:border-red-500 mb-3" rows="3" placeholder="Contoh: Foto buram, terpotong..."></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button @click="openReject = false" class="px-3 py-1.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-lg">Batal</button>
                                            <button 
                                                @click="updateStatus('invalid', note); openReject = false; note=''" 
                                                class="px-3 py-1.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm"
                                                :disabled="!note"
                                            >
                                                Kirim Revisi
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Valid --}}
                                <button @click="updateStatus('valid')" class="btn-action bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-200 shadow-md">
                                    <i class="fa-solid fa-check"></i> Valid
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            {{-- 3. Modal Footer (Global Action) --}}
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                <div class="text-xs text-slate-500">
                    <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i>
                    Pastikan semua dokumen berstatus <b>Valid</b> sebelum lanjut.
                </div>

                <div class="flex gap-3">
                    {{-- Form Global Revisi (Jika ada banyak salah) --}}
                    <form :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/revise'" method="POST" x-data="{ open: false }">
                        @csrf @method('PATCH')
                        <div class="relative">
                            <button type="button" @click="open = !open" class="px-4 py-2.5 bg-white border border-amber-300 text-amber-700 text-sm font-bold rounded-xl hover:bg-amber-50">
                                Minta Upload Ulang
                            </button>
                            {{-- Popover Global Note --}}
                            <div x-show="open" @click.outside="open = false" class="absolute bottom-full left-0 mb-2 w-72 bg-white p-4 rounded-xl shadow-xl border border-slate-200 z-50">
                                <textarea name="admin_note" class="w-full text-sm border-slate-300 rounded mb-2" placeholder="Pesan umum untuk customer..."></textarea>
                                <button class="w-full py-1.5 bg-amber-600 text-white text-xs font-bold rounded">Kirim Permintaan</button>
                            </div>
                        </div>
                    </form>

                    {{-- Form Approve Final --}}
                    <form :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/approve'" method="POST">
                        @csrf @method('PATCH')
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="hasInvalidOrPending"
                        >
                            <i class="fa-solid fa-check-double mr-2"></i> Validasi Lanjut Bank
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- STYLE HELPER --}}
<style>
    .badge { @apply px-2.5 py-1 rounded-md text-xs font-bold border flex items-center; }
    .btn-action { @apply px-4 py-2 rounded-lg text-sm font-bold border transition flex items-center gap-2; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

{{-- SCRIPT ALPINE --}}
<script>
    function verifBerkas() {
        return {
            activeModal: null,
            trxId: null,
            trxCode: '',
            trxName: '',
            documents: [],
            activeDoc: null,

            openCheckModal(id, code, name, docs) {
                this.activeModal = 'check';
                this.trxId = id;
                this.trxCode = code;
                this.trxName = name;
                this.documents = docs;
                // Pilih dokumen pertama
                this.activeDoc = docs.length ? docs[0] : null;
            },

            closeModal() {
                this.activeModal = null;
                setTimeout(() => {
                    this.documents = [];
                    this.activeDoc = null;
                }, 300);
            },

            viewDoc(doc) {
                this.activeDoc = doc;
            },

            isActive(doc) {
                return this.activeDoc && this.activeDoc.id === doc.id;
            },

            isImage(filePath) {
                if (!filePath) return false;
                const ext = filePath.split('.').pop().toLowerCase();
                return ['jpg', 'jpeg', 'png', 'webp', 'bmp'].includes(ext);
            },

            // Computed property untuk cek kelayakan tombol Approve
            get hasInvalidOrPending() {
                return this.documents.some(d => d.status === 'pending' || d.status === 'invalid');
            },

            // AJAX Update Status Per Item
            async updateStatus(status, note = null) {
                if (!this.activeDoc) return;

                const docId = this.activeDoc.id;
                // UPDATE PENTING: Gunakan URL Generator Blade
                // Jangan hardcode "/admin/..." karena bisa error di subfolder
                const url = '{{ url("admin/transactions/documents") }}/' + docId + '/validate'; 

                // CSRF Token Check
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    alert('Error: CSRF Token tidak ditemukan. Pastikan meta tag ada di layout.');
                    return;
                }

                try {
                    // Optimistic UI Update (Update tampilan dulu biar cepet)
                    const docIndex = this.documents.findIndex(d => d.id === docId);
                    if (docIndex !== -1) {
                        this.documents[docIndex].status = status;
                        this.documents[docIndex].note = note;
                        this.activeDoc.status = status; // Update active view juga
                        this.activeDoc.note = note;
                    }

                    // Kirim ke Server
                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfTokenMeta.getAttribute('content')
                        },
                        body: JSON.stringify({ status, note })
                    });

                    if (!response.ok) {
                        // Revert jika gagal (Opsional)
                        console.error('Server Error:', await response.text());
                        throw new Error('Gagal update status di server.');
                    }

                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan status dokumen. Cek console untuk detail.');
                }
            }
        }
    }
</script>
@endsection