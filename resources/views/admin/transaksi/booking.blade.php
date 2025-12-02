@extends('layouts.admin')
@section('title', 'Verifikasi Booking')

@section('content')
<div x-data="{ 
    activeModal: null, 
    
    // State Modal
    trxCode: '',
    trxName: '',
    actionUrl: '', // URL dinamis dari Blade route()
    proofImage: '', 
    
    // 1. Modal Lihat Bukti
    openProofModal(image) {
        this.activeModal = 'proof';
        this.proofImage = image;
    },

    // 2. Modal Terima (Menerima URL yang sudah digenerate blade)
    openApproveModal(url, code) {
        this.activeModal = 'approve';
        this.actionUrl = url;
        this.trxCode = code;
    },

    // 3. Modal Tolak (Menerima URL yang sudah digenerate blade)
    openRejectModal(url, code, name) {
        this.activeModal = 'reject';
        this.actionUrl = url;
        this.trxCode = code;
        this.trxName = name;
    },

    closeModal() {
        this.activeModal = null;
        // Delay clear data agar transisi mulus
        setTimeout(() => {
            this.proofImage = '';
            this.actionUrl = '';
            this.trxCode = '';
            this.trxName = '';
        }, 300);
    }
}" class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- HEADER --}}
        <div class="shrink-0 mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verifikasi Booking Masuk</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">
                Validasi bukti transfer dari customer. Pastikan dana mutasi sudah masuk.
            </p>
        </div>

        {{-- ALERT --}}
        <div class="shrink-0 flex flex-col gap-4 mb-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms 
                     class="p-4 rounded-xl bg-white border-l-4 border-emerald-500 text-slate-700 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Berhasil!</h4>
                            <p class="text-xs text-slate-500">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms 
                     class="p-4 rounded-xl bg-white border-l-4 border-red-500 text-slate-700 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Gagal!</h4>
                            <p class="text-xs text-slate-500">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- TABLE LIST --}}
        <div class="flex-1 flex flex-col">
            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Info Transaksi</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Unit Dipesan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Bukti Transfer</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $i => $trx)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 text-center text-xs font-bold text-slate-400">
                                    {{ $transactions->firstItem() + $i }}
                                </td>
                                
                                {{-- Info Transaksi --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-100">
                                            {{ substr($trx->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                                            <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $trx->code }}</p>
                                            <div class="flex items-center gap-1 mt-1 text-[10px] text-slate-400">
                                                <i class="fa-regular fa-clock"></i> 
                                                {{ $trx->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Info Unit --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 mb-1">
                                            Blok {{ $trx->unit->block_number }}
                                        </span>
                                        <p class="text-xs font-bold text-slate-800">{{ $trx->unit->location->name }}</p>
                                        <p class="text-xs text-slate-500">Tipe {{ $trx->unit->type }}</p>
                                    </div>
                                </td>

                                {{-- Bukti Transfer --}}
                                <td class="px-6 py-4 text-center">
                                    @if($trx->booking_proof)
                                        <button @click="openProofModal('{{ Storage::url($trx->booking_proof) }}')" 
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm group/btn">
                                            <i class="fa-regular fa-image group-hover/btn:scale-110 transition-transform"></i> Lihat Bukti
                                        </button>
                                        <p class="text-[10px] font-bold text-slate-500 mt-2 bg-slate-50 inline-block px-2 py-0.5 rounded">
                                            Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <span class="text-xs text-red-500 italic bg-red-50 px-2 py-1 rounded">Belum upload</span>
                                    @endif
                                </td>

                                {{-- Aksi (UPDATE: Menggunakan route() helper) --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                        {{-- Reject Button --}}
                                        <button @click="openRejectModal('{{ route('admin.transactions.booking.reject', $trx->id) }}', '{{ $trx->code }}', '{{ $trx->user->name }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-600 transition-all shadow-sm"
                                                title="Tolak">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        
                                        {{-- Approve Button --}}
                                        <button @click="openApproveModal('{{ route('admin.transactions.booking.approve', $trx->id) }}', '{{ $trx->code }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all shadow-md"
                                                title="Terima">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-hourglass-start text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Tidak ada antrian</h3>
                                        <p class="text-sm font-medium text-slate-500 mt-1">Semua booking masuk telah diproses.</p>
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
                        <div class="absolute top-0 left-0 w-1 h-full bg-yellow-400"></div>

                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-100">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                                        <p class="text-xs text-slate-500 font-mono">{{ $trx->code }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 bg-yellow-50 text-yellow-700 rounded border border-yellow-200">
                                    Pending
                                </span>
                            </div>

                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Unit:</span>
                                    <span class="font-bold text-slate-700">{{ $trx->unit->location->name }} ({{ $trx->unit->block_number }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Nominal:</span>
                                    <span class="font-bold text-slate-700">Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Waktu:</span>
                                    <span class="text-slate-600">{{ $trx->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button @click="openProofModal('{{ Storage::url($trx->booking_proof) }}')" 
                                        class="py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                                    Lihat Bukti
                                </button>
                                <div class="flex gap-2">
                                    {{-- Mobile Reject --}}
                                    <button @click="openRejectModal('{{ route('admin.transactions.booking.reject', $trx->id) }}', '{{ $trx->code }}', '{{ $trx->user->name }}')" 
                                            class="flex-1 py-2.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100 hover:bg-red-100 transition-colors">
                                        Tolak
                                    </button>
                                    {{-- Mobile Approve --}}
                                    <button @click="openApproveModal('{{ route('admin.transactions.booking.approve', $trx->id) }}', '{{ $trx->code }}')" 
                                            class="flex-1 py-2.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md transition-colors">
                                        Terima
                                    </button>
                                </div>
                            </div>
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

    {{-- ================= MODAL BUKTI TRANSFER ================= --}}
    <div x-show="activeModal === 'proof'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-lg bg-transparent transform transition-all flex flex-col items-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <img :src="proofImage" class="w-full max-h-[70vh] object-contain rounded-lg shadow-2xl border-4 border-white bg-black">
            
            <div class="flex gap-3 mt-4">
                <button @click="closeModal()" class="px-4 py-2 bg-white/10 text-white rounded-full hover:bg-white/20 transition-all backdrop-blur-md flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Tutup
                </button>
                <a :href="proofImage" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-up-right-from-square"></i> Buka Full Size
                </a>
            </div>
        </div>
    </div>

    {{-- ================= MODAL APPROVE (Menggunakan Action URL Dinamis) ================= --}}
    <div x-show="activeModal === 'approve'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="text-center">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 border border-emerald-100">
                    <i class="fa-solid fa-check-double text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Terima Pembayaran?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed px-4">
                    Pastikan dana booking fee untuk transaksi <b class="text-slate-800" x-text="trxCode"></b> benar-benar sudah masuk ke rekening.
                </p>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                
                {{-- Form Action Dinamis --}}
                <form :action="actionUrl" method="POST" class="w-full">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full rounded-xl bg-blue-600 py-2.5 text-sm font-bold text-white hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                        Ya, Valid
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= MODAL REJECT (Menggunakan Action URL Dinamis) ================= --}}
    <div x-show="activeModal === 'reject'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="mb-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center shrink-0 border border-red-100">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Tolak Booking</h3>
                </div>
                <p class="text-sm text-slate-500 ml-1">
                    Booking atas nama <b x-text="trxName"></b> akan dibatalkan dan unit akan tersedia kembali untuk publik.
                </p>
            </div>

            {{-- Form Action Dinamis --}}
            <form :action="actionUrl" method="POST">
                @csrf @method('PATCH')
                
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alasan Penolakan</label>
                    <textarea name="admin_note" rows="3" required placeholder="Contoh: Bukti transfer buram / Dana belum masuk..."
                              class="w-full rounded-xl border-slate-300 text-sm focus:border-red-500 focus:ring-red-500 placeholder:text-slate-400"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="w-full rounded-xl bg-red-600 py-2.5 text-sm font-bold text-white hover:bg-red-700 shadow-lg shadow-red-200 transition-all">
                        Tolak Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection