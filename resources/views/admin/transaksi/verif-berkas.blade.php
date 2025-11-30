@extends('layouts.admin')
@section('title', 'Verifikasi Berkas')

@section('content')
<div 
    x-data="verifBerkas()" 
    class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800"
>

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- ================================ HEADER ================================ --}}
        <div class="shrink-0 mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Verifikasi Dokumen KPR
            </h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">
                Periksa berkas KTP, KK, NPWP, slip gaji, serta dokumen lain dari calon pembeli.
            </p>
        </div>

        {{-- ================================ ALERT ================================ --}}
        @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-transition.duration.300ms
                 class="mb-6 p-4 bg-white border-l-4 border-emerald-500 rounded-xl shadow-sm flex justify-between items-center">
                <div class="flex items-center gap-3 text-slate-700">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show=false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- ================================ TABLE DESKTOP ================================ --}}
        <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <table class="w-full border-collapse text-left">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Kelengkapan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40 text-center">Aksi</th>
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
                                    <div class="w-10 h-10 rounded-full bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($trx->user->name, 0, 2)) }}
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        Blok {{ $trx->unit->block_number }}
                                    </span>
                                    <p class="text-xs font-bold text-slate-800">{{ $trx->unit->location->name }}</p>
                                    <p class="text-xs text-slate-500">Tipe {{ $trx->unit->type }}</p>
                                </div>
                            </td>

                            {{-- Dokumen --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                                    <i class="fa-regular fa-folder-open"></i>
                                    {{ $trx->documents->count() }} Berkas
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    Update: {{ $trx->updated_at->diffForHumans() }}
                                </p>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <button 
                                    @click="openCheckModal(
                                        {{ $trx->id }},
                                        '{{ $trx->code }}',
                                        '{{ $trx->user->name }}',
                                        {{ json_encode($trx->documents) }}
                                    )"
                                    class="px-4 py-2 text-xs font-bold bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition shadow-md shadow-purple-200 hover:-translate-y-0.5 flex items-center gap-2 mx-auto"
                                >
                                    <i class="fa-solid fa-magnifying-glass"></i> Periksa
                                </button>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-clipboard-check text-3xl"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800">Tidak ada antrian berkas</h3>
                                    <p class="text-sm text-slate-500 mt-1">Semua berkas telah diperiksa.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($transactions->hasPages())
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-4 py-3 mt-4">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>


    {{-- ================================ MODAL ================================ --}}
    <div 
        x-show="activeModal === 'check'" 
        class="fixed inset-0 z-50 flex items-center justify-center px-4" 
        style="display:none"
        x-transition.opacity.duration.200ms
    >
        <div @click="closeModal" class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>

        <div 
            class="relative w-full max-w-5xl h-[85vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
            x-transition.scale.origin.top.duration.250ms
        >

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Periksa Dokumen</h3>
                    <p class="text-xs text-slate-500">
                        Transaksi 
                        <span class="font-mono font-bold" x-text="trxCode"></span> — 
                        <span x-text="trxName"></span>
                    </p>
                </div>

                <button @click="closeModal" class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="flex-1 flex overflow-hidden">

                {{-- LEFT LIST --}}
                <div class="w-1/3 bg-slate-50 border-r border-slate-200 flex flex-col">
                    <div class="p-4 overflow-y-auto flex-1 space-y-2 custom-scrollbar">

                        <template x-if="documents.length === 0">
                            <div class="text-center py-10 text-slate-400 text-sm">
                                Tidak ada dokumen diupload.
                            </div>
                        </template>

                        <template x-for="doc in documents" :key="doc.id">
                            <button 
                                @click="viewDoc(doc)"
                                class="w-full text-left p-3 rounded-xl border flex items-center gap-3 transition"
                                :class="activeDoc?.id === doc.id
                                    ? 'bg-white border-blue-500 shadow-md ring-1 ring-blue-500'
                                    : 'bg-white border-slate-200 hover:border-blue-300'"
                            >
                                {{-- Icon --}}
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg"
                                    :class="activeDoc?.id === doc.id 
                                        ? 'bg-blue-100 text-blue-600' 
                                        : 'bg-slate-100 text-slate-500'"
                                >
                                    <i class="fa-regular fa-file-image"></i>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 uppercase" x-text="doc.type"></p>
                                    <p class="text-[10px] text-slate-500 truncate" x-text="'File: ' + doc.file_path"></p>
                                </div>

                                <i class="fa-solid fa-chevron-right text-xs"
                                   :class="activeDoc?.id === doc.id ? 'text-blue-500' : 'text-slate-300'"></i>
                            </button>
                        </template>

                    </div>
                </div>

                {{-- PREVIEW --}}
                <div class="w-2/3 bg-slate-800 flex justify-center items-center relative">

                    {{-- Jika ada dokumen --}}
                    <template x-if="activeDoc">
                        <img 
                            :src="'/storage/' + activeDoc.file_path" 
                            class="max-w-full max-h-full p-4 rounded object-contain shadow-xl"
                        >
                    </template>

                    {{-- Tidak ada dokumen --}}
                    <template x-if="!activeDoc">
                        <div class="text-slate-500 flex flex-col items-center">
                            <i class="fa-regular fa-image text-4xl mb-2 opacity-50"></i>
                            <p class="text-sm">Pilih dokumen untuk menampilkan preview.</p>
                        </div>
                    </template>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t border-slate-200 bg-white flex justify-between gap-4">

                {{-- Revisi --}}
                <form 
                    :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/revise'"
                    method="POST" 
                    class="flex-1 flex gap-2"
                >
                    @csrf @method('PATCH')
                    <input 
                        type="text" 
                        name="admin_note" 
                        placeholder="Catatan revisi..."
                        required
                        class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-red-500 focus:border-red-500"
                    >
                    <button 
                        class="px-5 py-2.5 bg-white border border-red-200 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50">
                        Minta Revisi
                    </button>
                </form>

                {{-- Approve --}}
                <form 
                    :action="'{{ url('admin/transactions/documents') }}/' + trxId + '/approve'"
                    method="POST">
                    @csrf @method('PATCH')
                    <button 
                        class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> Validasi Lanjut Bank
                    </button>
                </form>

            </div>

        </div>
    </div>

</div>


{{-- ============================ ALPINE COMPONENT ============================ --}}
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

        viewDoc(doc) {
            this.activeDoc = doc;
        },

        closeModal() {
            this.activeModal = null;
            this.documents = [];
            this.activeDoc = null;
        }
    }
}
</script>

@endsection
