@extends('layouts.admin')
@section('title', 'Verifikasi Berkas')

@section('content')
<div 
    x-data="verifBerkas()" 
    class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800"
>

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Verifikasi Dokumen KPR
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Periksa kelengkapan dan validitas berkas sebelum diajukan ke Bank.
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-4 mb-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms 
                     class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Berhasil!</h4>
                            <p class="text-xs opacity-90">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms 
                     class="p-4 rounded-xl bg-red-50 border border-red-100 text-red-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Gagal!</h4>
                            <p class="text-xs opacity-90">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        <div class="flex-1 flex flex-col">
            <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                            <th class="px-6 py-4 font-bold">Customer</th>
                            <th class="px-6 py-4 font-bold">Unit</th>
                            <th class="px-6 py-4 font-bold text-center">Progress Validasi</th>
                            <th class="px-6 py-4 font-bold text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($transactions as $i => $trx)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 text-center font-medium text-slate-400">
                                    {{ $transactions->firstItem() + $i }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0 border border-indigo-100">
                                            {{ substr($trx->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $trx->user->name }}</p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $trx->code }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 mb-1">
                                            Blok {{ $trx->unit->block_number }}
                                        </span>
                                        <p class="text-xs font-bold text-slate-800">{{ $trx->unit->location->name }}</p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $totalDocs = $trx->documents->count();
                                        $validDocs = $trx->documents->where('status', 'valid')->count();
                                        $invalidDocs = $trx->documents->where('status', 'invalid')->count();
                                    @endphp
                                    
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="flex gap-1">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                {{ $validDocs }} Valid
                                            </span>
                                            @if($invalidDocs > 0)
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-700 border border-red-200">
                                                    {{ $invalidDocs }} Revisi
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] font-medium text-slate-400">Total {{ $totalDocs }} dokumen</span>
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
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all hover:-translate-y-0.5"
                                    >
                                        <i class="fa-solid fa-magnifying-glass"></i> Periksa
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Tidak ada antrian</h3>
                                        <p class="text-slate-500 text-xs mt-1">Semua berkas telah diperiksa.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="lg:hidden space-y-4 mb-6">
                @foreach($transactions as $trx)
                    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>

                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</h3>
                                        <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $trx->code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Unit</span>
                                    <span class="font-bold text-slate-700">{{ $trx->unit->location->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Dokumen</span>
                                    <div class="flex gap-1">
                                        <span class="text-emerald-600 font-bold">{{ $trx->documents->where('status', 'valid')->count() }} Valid</span>
                                        <span class="text-slate-300">/</span>
                                        <span class="text-slate-600">{{ $trx->documents->count() }} Total</span>
                                    </div>
                                </div>
                            </div>

                            <button 
                                @click="openCheckModal(
                                    {{ $trx->id }},
                                    '{{ $trx->code }}',
                                    '{{ $trx->user->name }}',
                                    {{ json_encode($trx->documents) }}
                                )"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 shadow-md transition-colors">
                                <i class="fa-solid fa-magnifying-glass"></i> Periksa Berkas
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $transactions->links() }}
        </div>
    </div>

    <div x-show="activeModal === 'check'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-6xl h-[90vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Periksa Dokumen</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Transaksi <span class="font-mono font-bold text-slate-700" x-text="trxCode"></span> — <span x-text="trxName"></span>
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden lg:flex gap-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Pending</div>
                        <div class="flex items-center gap-1.5 text-emerald-600"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Valid</div>
                        <div class="flex items-center gap-1.5 text-red-600"><span class="w-2 h-2 rounded-full bg-red-500"></span> Invalid</div>
                    </div>
                    
                    <button @click="closeModal" class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 transition-all">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 flex overflow-hidden">
                <div class="w-80 bg-slate-50 border-r border-slate-200 flex flex-col shrink-0">
                    <div class="p-4 overflow-y-auto flex-1 space-y-2 custom-scrollbar">
                        <template x-for="doc in documents" :key="doc.id">
                            <button @click="viewDoc(doc)"
                                    class="w-full text-left p-3 rounded-xl border flex items-center gap-3 transition relative overflow-hidden group"
                                    :class="isActive(doc) ? 'bg-white border-indigo-500 ring-1 ring-indigo-500 shadow-md z-10' : 'bg-white border-slate-200 hover:border-indigo-300 hover:shadow-sm'">
                                
                                <div class="absolute left-0 top-0 bottom-0 w-1"
                                     :class="{
                                        'bg-slate-300': doc.status === 'pending',
                                        'bg-emerald-500': doc.status === 'valid',
                                        'bg-red-500': doc.status === 'invalid'
                                     }"></div>

                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg shrink-0 ml-1 transition-colors"
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
                                    <p class="text-[10px] text-slate-500 truncate font-medium" 
                                       x-text="doc.status === 'pending' ? 'Belum diperiksa' : (doc.status === 'valid' ? 'Sudah Valid' : 'Perlu Revisi')"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex-1 flex flex-col bg-slate-100 relative">
                    <div class="flex-1 overflow-hidden relative flex items-center justify-center p-6 bg-slate-200/50">
                        <template x-if="activeDoc">
                            <div class="w-full h-full flex items-center justify-center">
                                <template x-if="isImage(activeDoc.file_path)">
                                    <img :src="'/storage/' + activeDoc.file_path" class="max-w-full max-h-full object-contain shadow-lg rounded bg-white">
                                </template>
                                <template x-if="!isImage(activeDoc.file_path)">
                                    <div class="text-center p-8 bg-white rounded-2xl shadow-sm">
                                        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mx-auto mb-4">
                                            <i class="fa-regular fa-file-pdf text-3xl"></i>
                                        </div>
                                        <p class="text-slate-800 font-bold text-sm mb-1">Format PDF Terdeteksi</p>
                                        <p class="text-slate-500 text-xs mb-4">Preview tidak tersedia di sini.</p>
                                        <a :href="'/storage/' + activeDoc.file_path" target="_blank" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition-colors">
                                            Buka File PDF
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="!activeDoc">
                            <div class="text-slate-400 flex flex-col items-center opacity-60">
                                <i class="fa-solid fa-arrow-left-long text-3xl mb-3"></i>
                                <p class="text-sm font-medium">Pilih dokumen di sebelah kiri</p>
                            </div>
                        </template>
                    </div>

                    <template x-if="activeDoc">
                        <div class="bg-white border-t border-slate-200 p-4 shrink-0 flex items-center justify-between gap-4 shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.1)] z-20 relative">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Saat Ini</p>
                                <div class="flex items-center gap-2">
                                    <span x-show="activeDoc.status === 'pending'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Pending
                                    </span>
                                    <span x-show="activeDoc.status === 'valid'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                        <i class="fa-solid fa-check"></i> Valid
                                    </span>
                                    <span x-show="activeDoc.status === 'invalid'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-xs font-bold border border-red-100">
                                        <i class="fa-solid fa-xmark"></i> Invalid
                                    </span>
                                    
                                    <span x-show="activeDoc.status === 'invalid' && activeDoc.note" class="text-xs text-red-600 italic border-l-2 border-red-200 pl-2 ml-2" x-text="'Catatan: ' + activeDoc.note"></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div x-data="{ openReject: false, note: '' }" class="relative">
                                    <button @click="openReject = !openReject" class="px-4 py-2.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-50 transition-colors shadow-sm">
                                        <i class="fa-solid fa-xmark mr-1"></i> Tolak / Revisi
                                    </button>
                                    
                                    <div x-show="openReject" @click.outside="openReject = false" 
                                         class="absolute bottom-full right-0 mb-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 p-4 z-50 origin-bottom-right"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                                        
                                        <p class="text-xs font-bold text-slate-700 mb-2">Alasan Penolakan</p>
                                        <textarea x-model="note" class="w-full text-sm border-slate-200 bg-slate-50 rounded-xl focus:ring-red-500 focus:border-red-500 mb-3 font-medium placeholder:text-slate-400" rows="3" placeholder="Contoh: Foto buram, terpotong..."></textarea>
                                        
                                        <div class="flex justify-end gap-2">
                                            <button @click="openReject = false" class="px-3 py-1.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">Batal</button>
                                            <button @click="updateStatus('invalid', note); openReject = false; note=''" 
                                                    class="px-3 py-1.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-md shadow-red-200 transition-all"
                                                    :disabled="!note">
                                                Kirim Revisi
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button @click="updateStatus('valid')" class="px-6 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">
                                    <i class="fa-solid fa-check mr-1.5"></i> Validasi
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500"></i>
                    Pastikan semua dokumen berstatus <b class="text-slate-700">Valid</b> sebelum melanjutkan.
                </div>

                <div class="flex gap-3">
                    <form :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/revise'" method="POST" x-data="{ open: false }">
                        @csrf @method('PATCH')
                        <div class="relative">
                            <button type="button" @click="open = !open" class="px-4 py-2.5 bg-white border border-amber-200 text-amber-700 text-xs font-bold rounded-xl hover:bg-amber-50 transition-colors shadow-sm">
                                Minta Upload Ulang
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute bottom-full left-0 mb-2 w-72 bg-white p-4 rounded-xl shadow-xl border border-slate-200 z-50">
                                <textarea name="admin_note" class="w-full text-sm border-slate-200 rounded-xl mb-2" placeholder="Pesan umum untuk customer..."></textarea>
                                <button class="w-full py-2 bg-amber-600 text-white text-xs font-bold rounded-lg hover:bg-amber-700">Kirim Permintaan</button>
                            </div>
                        </div>
                    </form>

                    <form :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/approve'" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" 
                                class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                                :disabled="hasInvalidOrPending">
                            <i class="fa-solid fa-check-double mr-2"></i> Lanjut Proses Bank
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    .badge { @apply px-2.5 py-1 rounded-md text-xs font-bold border flex items-center; }
    .btn-action { @apply px-4 py-2 rounded-lg text-sm font-bold border transition flex items-center gap-2; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

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

            get hasInvalidOrPending() {
                return this.documents.some(d => d.status === 'pending' || d.status === 'invalid');
            },

            async updateStatus(status, note = null) {
                if (!this.activeDoc) return;

                const docId = this.activeDoc.id;
                const url = '{{ url("admin/transactions/documents") }}/' + docId + '/validate'; 

                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenMeta) {
                    alert('Error: CSRF Token tidak ditemukan.');
                    return;
                }

                try {
                    const docIndex = this.documents.findIndex(d => d.id === docId);
                    if (docIndex !== -1) {
                        this.documents[docIndex].status = status;
                        this.documents[docIndex].note = note;
                        this.activeDoc.status = status;
                        this.activeDoc.note = note;
                    }

                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfTokenMeta.getAttribute('content')
                        },
                        body: JSON.stringify({ status, note })
                    });

                    if (!response.ok) {
                        console.error('Server Error:', await response.text());
                        throw new Error('Gagal update status.');
                    }

                } catch (error) {
                    console.error(error);
                    alert('Gagal menyimpan status dokumen.');
                }
            }
        }
    }
</script>
@endsection