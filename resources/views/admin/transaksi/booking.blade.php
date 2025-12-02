@extends('layouts.admin')
@section('title', 'Verifikasi Booking')

@section('content')
<div x-data="{ 
    activeModal: null, 
    trxCode: '',
    trxName: '',
    actionUrl: '', 
    proofImage: '', 
    
    openProofModal(image) {
        this.activeModal = 'proof';
        this.proofImage = image;
    },

    openApproveModal(url, code) {
        this.activeModal = 'approve';
        this.actionUrl = url;
        this.trxCode = code;
    },

    openRejectModal(url, code, name) {
        this.activeModal = 'reject';
        this.actionUrl = url;
        this.trxCode = code;
        this.trxName = name;
    },

    closeModal() {
        this.activeModal = null;
        setTimeout(() => {
            this.proofImage = '';
            this.actionUrl = '';
            this.trxCode = '';
            this.trxName = '';
        }, 300);
    }
}" class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Verifikasi Booking
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Validasi bukti transfer dari customer untuk mengamankan unit.
                </p>
            </div>
        </div>

        {{-- Alerts --}}
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

        {{-- Table List --}}
        <div class="flex-1 flex flex-col">
            
            {{-- Desktop View --}}
            <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                            <th class="px-6 py-4 font-bold">Info Transaksi</th>
                            <th class="px-6 py-4 font-bold">Unit Dipesan</th>
                            <th class="px-6 py-4 font-bold text-center">Bukti Transfer</th>
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
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-100">
                                            {{ substr($trx->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $trx->user->name }}</p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $trx->code }}</p>
                                            <div class="flex items-center gap-1 mt-1 text-[10px] text-slate-400">
                                                <i class="fa-regular fa-clock"></i> 
                                                {{ $trx->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 mb-1">
                                            Blok {{ $trx->unit->block_number }}
                                        </span>
                                        <p class="text-xs font-bold text-slate-800">{{ $trx->unit->location->name }}</p>
                                        <p class="text-xs text-slate-500">Tipe {{ $trx->unit->type }}</p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($trx->booking_proof)
                                        <button @click="openProofModal('{{ Storage::url($trx->booking_proof) }}')" 
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm group/btn">
                                            <i class="fa-regular fa-image group-hover/btn:scale-110 transition-transform"></i> Lihat
                                        </button>
                                        <p class="text-[10px] font-bold text-slate-500 mt-2">
                                            Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <span class="text-xs text-red-500 italic bg-red-50 px-2 py-1 rounded">Belum upload</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openRejectModal('{{ route('admin.transactions.booking.reject', $trx->id) }}', '{{ $trx->code }}', '{{ $trx->user->name }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all shadow-sm"
                                                title="Tolak">
                                            <i class="fa-solid fa-xmark text-sm"></i>
                                        </button>
                                        
                                        <button @click="openApproveModal('{{ route('admin.transactions.booking.approve', $trx->id) }}', '{{ $trx->code }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-md shadow-blue-200 transition-all"
                                                title="Terima">
                                            <i class="fa-solid fa-check text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-hourglass-start text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Tidak ada antrian</h3>
                                        <p class="text-slate-500 text-xs mt-1">Semua booking masuk telah diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile View --}}
            <div class="lg:hidden space-y-4 mb-6">
                @foreach($transactions as $trx)
                    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400 group-hover:w-1.5 transition-all"></div>

                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold border border-blue-100">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</h3>
                                        <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $trx->code }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 bg-amber-50 text-amber-700 rounded border border-amber-200">
                                    Pending
                                </span>
                            </div>

                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Unit</span>
                                    <span class="font-bold text-slate-700">{{ $trx->unit->location->name }} ({{ $trx->unit->block_number }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Nominal</span>
                                    <span class="font-bold text-slate-700">Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Waktu</span>
                                    <span class="text-slate-600">{{ $trx->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button @click="openProofModal('{{ Storage::url($trx->booking_proof) }}')" 
                                        class="flex items-center justify-center py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors">
                                    Lihat Bukti
                                </button>
                                <div class="flex gap-2">
                                    <button @click="openRejectModal('{{ route('admin.transactions.booking.reject', $trx->id) }}', '{{ $trx->code }}', '{{ $trx->user->name }}')" 
                                            class="flex-1 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-xs font-bold hover:bg-red-50 transition-colors">
                                        Tolak
                                    </button>
                                    <button @click="openApproveModal('{{ route('admin.transactions.booking.approve', $trx->id) }}', '{{ $trx->code }}')" 
                                            class="flex-1 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md transition-colors">
                                        Terima
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-3 flex items-center justify-between sm:px-6 mt-auto">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Bukti --}}
    <div x-show="activeModal === 'proof'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-lg bg-transparent transform transition-all flex flex-col items-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <img :src="proofImage" class="w-full max-h-[70vh] object-contain rounded-xl shadow-2xl border-4 border-white bg-black">
            
            <div class="flex gap-3 mt-4">
                <button @click="closeModal()" class="px-4 py-2 bg-white/10 text-white rounded-full hover:bg-white/20 transition-all backdrop-blur-md flex items-center gap-2 border border-white/20 text-sm font-medium">
                    <i class="fa-solid fa-xmark"></i> Tutup
                </button>
                <a :href="proofImage" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all shadow-lg flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-up-right-from-square"></i> Buka Full Size
                </a>
            </div>
        </div>
    </div>

    {{-- Modal Approve --}}
    <div x-show="activeModal === 'approve'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="text-center">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 border border-emerald-100">
                    <i class="fa-solid fa-check-double text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Terima Pembayaran?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed px-4">
                    Pastikan dana booking fee untuk transaksi <b class="text-slate-800" x-text="trxCode"></b> benar-benar sudah masuk ke rekening.
                </p>
            </div>

            <div class="mt-8 flex gap-3">
                <button @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </button>
                
                <form :action="actionUrl" method="POST" class="w-full">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">
                        Ya, Valid
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div x-show="activeModal === 'reject'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="mb-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center shrink-0 border border-red-100">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Tolak Booking</h3>
                </div>
                <p class="text-sm text-slate-500 ml-1">
                    Booking atas nama <b x-text="trxName"></b> akan dibatalkan dan unit akan tersedia kembali untuk publik.
                </p>
            </div>

            <form :action="actionUrl" method="POST">
                @csrf @method('PATCH')
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alasan Penolakan</label>
                    <textarea name="admin_note" rows="3" required placeholder="Contoh: Bukti transfer buram / Dana belum masuk..."
                              class="w-full rounded-xl border-slate-200 text-slate-900 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 placeholder:text-slate-400 font-medium"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="w-full rounded-xl bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:-translate-y-0.5">
                        Tolak Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection