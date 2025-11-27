@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- 1. HEADER --}}
        <div class="shrink-0 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900">Riwayat Transaksi</h1>
                <p class="text-sm text-slate-500 mt-1 font-medium">Arsip lengkap seluruh aktivitas penjualan unit.</p>
            </div>
        </div>

        {{-- 2. FILTER & SEARCH --}}
        <div class="shrink-0 mb-6">
            <form action="{{ route('admin.transactions.index') }}" method="GET" 
                  class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-2">
                
                {{-- Filter Status --}}
                <div class="relative w-full sm:w-48">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </span>
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full pl-9 py-2 bg-slate-50 border-none text-sm font-medium text-slate-700 rounded-lg focus:ring-0 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Pending (Verif)</option>
                        <option value="booking_acc" {{ request('status') == 'booking_acc' ? 'selected' : '' }}>Pemberkasan</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Terjual (Sold)</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                {{-- Search --}}
                <div class="flex-1 w-full relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-9 border-none text-sm focus:ring-0 text-slate-700 placeholder-slate-400 bg-transparent" 
                           placeholder="Cari Kode TRX atau Nama Pembeli...">
                </div>
                
                <button type="submit" class="hidden sm:block px-4 py-2 bg-slate-800 text-white text-sm font-bold rounded-lg hover:bg-slate-700">
                    Cari
                </button>
            </form>
        </div>

        {{-- 3. TABLE LIST --}}
        <div class="flex-1 flex flex-col">
            <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode & Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pembeli</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Unit Properti</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status Terkini</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $i => $trx)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-center text-xs font-bold text-slate-400">
                                    {{ $transactions->firstItem() + $i }}
                                </td>
                                
                                {{-- Kode --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-mono text-sm font-bold text-slate-800">{{ $trx->code }}</span>
                                        <span class="text-xs text-slate-500 mt-0.5">
                                            <i class="fa-regular fa-calendar mr-1"></i>
                                            {{ $trx->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Pembeli --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($trx->user->name, 0, 1) }}
                                        </div>
                                        <p class="text-sm font-medium text-slate-700">{{ $trx->user->name }}</p>
                                    </div>
                                </td>

                                {{-- Unit --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $trx->unit->location->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Blok <b class="text-slate-700">{{ $trx->unit->block_number }}</b> • Tipe {{ $trx->unit->type }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusConfig = match($trx->status) {
                                            'pending' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Menunggu Bayar'],
                                            'process' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Verifikasi Admin'],
                                            'booking_acc' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Pemberkasan'],
                                            'docs_review' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Review Berkas'],
                                            'bank_process'=> ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank'],
                                            'sold' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Terjual / Lunas'],
                                            'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                            'canceled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'Dibatalkan'],
                                            default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => $trx->status],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-all shadow-sm" title="Lihat Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 opacity-50"></i>
                                    <p class="text-sm font-medium">Data transaksi tidak ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="lg:hidden space-y-4">
                @foreach($transactions as $trx)
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($trx->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                                    <p class="text-xs text-slate-500 font-mono">{{ $trx->code }}</p>
                                </div>
                            </div>
                            @php
                                // Re-use logic status config for mobile
                                $statusMobile = match($trx->status) {
                                    'sold' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'process' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="text-[10px] font-bold px-2 py-1 rounded border border-transparent {{ $statusMobile }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-1">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Unit:</span>
                                <span class="font-bold text-slate-700">{{ $trx->unit->location->name }} ({{ $trx->unit->block_number }})</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tanggal:</span>
                                <span class="font-bold text-slate-700">{{ $trx->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <button class="w-full py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors">
                            Lihat Detail
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-4 py-3 mt-auto">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection