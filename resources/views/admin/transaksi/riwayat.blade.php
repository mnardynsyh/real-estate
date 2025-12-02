@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- ===================================================== --}}
        {{-- HEADER & ACTION --}}
        {{-- ===================================================== --}}
        <div class="shrink-0 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900">Riwayat Transaksi</h1>
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Arsip lengkap seluruh transaksi dan progress proses KPR.
                </p>
            </div>

            {{-- Tombol Export Excel --}}
            <div class="flex-shrink-0">
                <a href="{{ route('admin.transactions.export') }}" target="_blank" 
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 gap-2 hover:-translate-y-0.5">
                    <i class="fa-solid fa-file-excel text-lg"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- FILTER & SEARCH --}}
        {{-- ===================================================== --}}
        <div class="shrink-0 mb-6">
            <form action="{{ route('admin.transactions.index') }}" method="GET"
                  class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-3">

                {{-- Filter Status --}}
                <div class="relative w-full sm:w-56">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </span>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg text-slate-700 focus:ring-blue-500 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending"      {{ request('status')=='pending' ? 'selected':'' }}>Menunggu Bayar</option>
                        <option value="process"      {{ request('status')=='process' ? 'selected':'' }}>Verifikasi Admin</option>
                        <option value="booking_acc"  {{ request('status')=='booking_acc' ? 'selected':'' }}>Pemberkasan</option>
                        <option value="docs_review"  {{ request('status')=='docs_review' ? 'selected':'' }}>Review Berkas</option>
                        <option value="bank_process" {{ request('status')=='bank_process' ? 'selected':'' }}>Proses Bank</option>
                        <option value="sold"         {{ request('status')=='sold' ? 'selected':'' }}>Sold</option>
                        <option value="rejected"     {{ request('status')=='rejected' ? 'selected':'' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Divider --}}
                <div class="hidden sm:block h-8 w-px bg-slate-200"></div>

                {{-- Search --}}
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode transaksi atau nama pembeli..."
                           class="w-full pl-10 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 text-slate-700 placeholder-slate-400">
                </div>

                <button type="submit"
                        class="hidden sm:block px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-sm">
                    Cari
                </button>
            </form>
        </div>

        {{-- ===================================================== --}}
        {{-- TABLE VIEW (DESKTOP) --}}
        {{-- ===================================================== --}}
        <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase w-16 text-center">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Kode & Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Pembeli</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Unit</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center w-24">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($transactions as $i => $trx)
                    <tr class="hover:bg-slate-50 transition">
                        
                        {{-- NUMBER --}}
                        <td class="px-6 py-4 text-center text-xs font-bold text-slate-400">
                            {{ $transactions->firstItem() + $i }}
                        </td>

                        {{-- KODE --}}
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-mono text-sm font-bold text-slate-800">{{ $trx->code }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    {{ $trx->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </td>

                        {{-- USER --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($trx->user->name,0,1) }}
                                </div>
                                <p class="text-sm font-medium text-slate-700">{{ $trx->user->name }}</p>
                            </div>
                        </td>

                        {{-- UNIT --}}
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-800">
                                {{ $trx->unit->location->name }} – Blok {{ $trx->unit->block_number }}
                            </p>
                            <p class="text-xs text-slate-500">
                                Tipe {{ $trx->unit->type }}
                            </p>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $badge = [
                                    'pending'      => ['bg'=>'bg-slate-100',  'text'=>'text-slate-600','label'=>'Menunggu Bayar'],
                                    'process'      => ['bg'=>'bg-yellow-100', 'text'=>'text-yellow-800','label'=>'Verifikasi Admin'],
                                    'booking_acc'  => ['bg'=>'bg-blue-100',   'text'=>'text-blue-700','label'=>'Pemberkasan'],
                                    'docs_review'  => ['bg'=>'bg-purple-100', 'text'=>'text-purple-700','label'=>'Review Berkas'],
                                    'bank_process' => ['bg'=>'bg-orange-100', 'text'=>'text-orange-700','label'=>'Proses Bank'],
                                    'bank_review'  => ['bg'=>'bg-purple-100', 'text'=>'text-purple-700','label'=>'Proses Bank'],
                                    'sold'         => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Terjual / Lunas'],
                                    'rejected'     => ['bg'=>'bg-red-100',    'text'=>'text-red-700','label'=>'Ditolak'],
                                    'canceled'     => ['bg'=>'bg-slate-200',  'text'=>'text-slate-700','label'=>'Dibatalkan']
                                ][$trx->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600','label'=>$trx->status];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.transactions.show', $trx->id) }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-all shadow-sm"
                            title="Lihat Detail">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-4xl mb-3 opacity-40"></i>
                            <p class="text-sm font-medium">Belum ada transaksi.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- ===================================================== --}}
        {{-- MOBILE VERSION --}}
        {{-- ===================================================== --}}
        <div class="lg:hidden space-y-4">

            @foreach($transactions as $trx)
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">

                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                            {{ substr($trx->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                            <p class="text-xs font-mono text-slate-500">{{ $trx->code }}</p>
                        </div>
                    </div>

                    @php
                        // Logic badge mobile copy dari desktop agar konsisten
                        $mobileBadge = [
                            'pending'      => ['bg'=>'bg-slate-100',  'text'=>'text-slate-600'],
                            'process'      => ['bg'=>'bg-yellow-100', 'text'=>'text-yellow-800'],
                            'booking_acc'  => ['bg'=>'bg-blue-100',   'text'=>'text-blue-700'],
                            'docs_review'  => ['bg'=>'bg-purple-100', 'text'=>'text-purple-700'],
                            'bank_process' => ['bg'=>'bg-orange-100', 'text'=>'text-orange-700'],
                            'bank_review'  => ['bg'=>'bg-purple-100', 'text'=>'text-purple-700'],
                            'sold'         => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700'],
                            'rejected'     => ['bg'=>'bg-red-100',    'text'=>'text-red-700'],
                            'canceled'     => ['bg'=>'bg-slate-200',  'text'=>'text-slate-700']
                        ][$trx->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600'];
                    @endphp
                    <span class="px-2 py-1 rounded text-[10px] font-bold {{ $mobileBadge['bg'] }} {{ $mobileBadge['text'] }}">
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

                <a href="{{ route('admin.transactions.show', $trx->id) }}"
                class="w-full py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors">
                    Lihat Detail
                </a>

            </div>
            @endforeach

        </div>

        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}
        @if($transactions->hasPages())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-4 py-3 mt-5">
            {{ $transactions->links() }}
        </div>
        @endif

    </div>
</div>
@endsection