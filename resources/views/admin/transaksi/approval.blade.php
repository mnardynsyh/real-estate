@extends('layouts.admin')
@section('title', 'Approval & Finalisasi')

@section('content')
<div x-data="{ 
    activeModal: null, 
    trxId: null,
    trxCode: '',
    trxName: '',
    unitPrice: 0,
    bookingFee: 0,
    
    openFinalizeModal(id, code, name, price, booking) {
        this.activeModal = 'finalize';
        this.trxId = id;
        this.trxCode = code;
        this.trxName = name;
        this.unitPrice = price;
        this.bookingFee = booking;
    },

    openRejectModal(id, code, name) {
        this.activeModal = 'reject';
        this.trxId = id;
        this.trxCode = code;
        this.trxName = name;
    },

    closeModal() {
        this.activeModal = null;
    },

    get estimatedDp() {
        return (this.unitPrice * 0.2) - this.bookingFee;
    }
}" class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Approval Bank & DP
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Pantau proses pengajuan KPR. Finalisasi transaksi jika Akad Kredit selesai dan DP lunas.
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
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Table List --}}
        <div class="flex-1 flex flex-col">
            
            {{-- Desktop --}}
            <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                            <th class="px-6 py-4 font-bold">Customer</th>
                            <th class="px-6 py-4 font-bold">Unit Properti</th>
                            <th class="px-6 py-4 font-bold">Estimasi Harga</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
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
                                        <p class="text-xs text-slate-500">Tipe {{ $trx->unit->type }}</p>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800">Rp {{ number_format($trx->unit->price, 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-slate-500 mt-1">Booking: Rp {{ number_format($trx->booking_fee, 0, ',', '.') }} (Lunas)</p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold border border-orange-200 animate-pulse">
                                        <i class="fa-solid fa-building-columns"></i>
                                        Proses Bank
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openRejectModal({{ $trx->id }}, '{{ $trx->code }}', '{{ $trx->user->name }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-600 transition-all shadow-sm"
                                                title="Gagal / Batal">
                                            <i class="fa-solid fa-xmark text-sm"></i>
                                        </button>
                                        
                                        <button @click="openFinalizeModal({{ $trx->id }}, '{{ $trx->code }}', '{{ $trx->user->name }}', {{ $trx->unit->price }}, {{ $trx->booking_fee }})" 
                                                class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-all shadow-md shadow-green-200 hover:-translate-y-0.5">
                                            <i class="fa-solid fa-handshake"></i> Akad & Sold
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-briefcase text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Tidak ada proses berjalan</h3>
                                        <p class="text-sm font-medium text-slate-500 mt-1">Semua transaksi bank telah diselesaikan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="lg:hidden space-y-4 mb-6">
                @foreach($transactions as $trx)
                    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>

                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-xs border border-orange-100">
                                        {{ substr($trx->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</h3>
                                        <p class="text-xs text-slate-500 font-mono">{{ $trx->code }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 bg-orange-100 text-orange-700 rounded border border-orange-200">
                                    Bank Process
                                </span>
                            </div>

                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Unit:</span>
                                    <span class="font-bold text-slate-700">{{ $trx->unit->location->name }} ({{ $trx->unit->block_number }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-medium">Harga:</span>
                                    <span class="font-bold text-slate-700">Rp {{ number_format($trx->unit->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <button @click="openRejectModal({{ $trx->id }}, '{{ $trx->code }}', '{{ $trx->user->name }}')" 
                                        class="col-span-1 py-2.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100 hover:bg-red-100 transition-colors">
                                    Gagal
                                </button>
                                <button @click="openFinalizeModal({{ $trx->id }}, '{{ $trx->code }}', '{{ $trx->user->name }}', {{ $trx->unit->price }}, {{ $trx->booking_fee }})" 
                                        class="col-span-2 py-2.5 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 shadow-md transition-colors flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-handshake"></i> Akad & Sold
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-3 mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Finalize --}}
    <div x-show="activeModal === 'finalize'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-lg rounded-2xl bg-white p-8 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="text-center mb-6">
                <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-green-600 border-4 border-green-50 shadow-inner">
                    <i class="fa-solid fa-award text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Finalisasi Penjualan</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Transaksi <b x-text="trxCode"></b> atas nama <b x-text="trxName"></b>.
                </p>
            </div>

            <form :action="'{{ url('admin/transactions/approval') }}/' + trxId + '/finalize'" method="POST">
                @csrf @method('PATCH')
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Harga Unit</span>
                        <span class="font-bold text-slate-800" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(unitPrice)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Booking Fee (Sudah dibayar)</span>
                        <span class="font-bold text-green-600" x-text="'- Rp ' + new Intl.NumberFormat('id-ID').format(bookingFee)"></span>
                    </div>
                    <div class="border-t border-slate-200 my-2"></div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Total DP / Pelunasan (Dibayarkan User)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 font-bold text-sm">Rp</span>
                            <input type="number" name="down_payment" :value="estimatedDp" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-900 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">*Nominal ini akan dicatat sebagai pemasukan DP.</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="w-full rounded-xl bg-green-600 py-3 text-sm font-bold text-white hover:bg-green-700 shadow-lg shadow-green-200 transition-all hover:-translate-y-0.5">
                        Konfirmasi SOLD
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div x-show="activeModal === 'reject'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="mb-5 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
                    <i class="fa-solid fa-file-circle-xmark text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Batalkan Transaksi?</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Transaksi ini akan ditandai sebagai <b>Gagal/Ditolak Bank</b>. Unit akan otomatis kembali menjadi <b>Available</b>.
                </p>
            </div>

            <form :action="'{{ url('admin/transactions/approval') }}/' + trxId + '/reject-bank'" method="POST">
                @csrf @method('PATCH')
                
                <div class="flex gap-3 mt-6">
                    <button type="button" @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Kembali</button>
                    <button type="submit" class="w-full rounded-xl bg-red-600 py-2.5 text-sm font-bold text-white hover:bg-red-700 shadow-lg shadow-red-200 transition-all">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection