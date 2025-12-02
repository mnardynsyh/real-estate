@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header & Action --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Riwayat Transaksi
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Arsip lengkap seluruh transaksi dan progress proses KPR.
                </p>
            </div>

            <div class="flex-shrink-0">
                <a href="{{ route('admin.transactions.export') }}" target="_blank" 
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 gap-2 hover:-translate-y-0.5">
                    <i class="fa-solid fa-file-excel text-lg"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="shrink-0 mb-6">
            <form action="{{ route('admin.transactions.index') }}" method="GET"
                  class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-3">

                <div class="relative w-full sm:w-56">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </span>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-lg text-slate-700 focus:ring-blue-500 cursor-pointer font-medium">
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

                <div class="hidden sm:block h-8 w-px bg-slate-200"></div>

                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode transaksi atau nama customer..."
                           class="w-full pl-10 pr-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 text-slate-700 placeholder-slate-400 font-medium">
                </div>

                <button type="submit"
                        class="hidden sm:block px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all">
                    Cari
                </button>
            </form>
        </div>

        {{-- Table View (Desktop) --}}
        <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-6 py-4 font-bold">Kode & Tanggal</th>
                        <th class="px-6 py-4 font-bold">Pembeli</th>
                        <th class="px-6 py-4 font-bold">Unit</th>
                        <th class="px-6 py-4 font-bold text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-center w-24">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($transactions as $i => $trx)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        
                        <td class="px-6 py-4 text-center font-medium text-slate-400">
                            {{ $transactions->firstItem() + $i }}
                        </td>

                        <td class="px-6 py-4">
                            <div>
                                <p class="font-mono text-sm font-bold text-slate-800">{{ $trx->code }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                    {{ $trx->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200">
                                    {{ substr($trx->user->name,0,1) }}
                                </div>
                                <p class="text-sm font-bold text-slate-700">{{ $trx->user->name }}</p>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-800">
                                {{ $trx->unit->location->name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Blok {{ $trx->unit->block_number }} • Tipe {{ $trx->unit->type }}
                            </p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $badge = [
                                    'pending'      => ['bg'=>'bg-slate-100',  'text'=>'text-slate-600','label'=>'Menunggu Bayar'],
                                    'process'      => ['bg'=>'bg-amber-100', 'text'=>'text-amber-800','label'=>'Verifikasi Admin'],
                                    'booking_acc'  => ['bg'=>'bg-blue-100',   'text'=>'text-blue-700','label'=>'Pemberkasan'],
                                    'docs_review'  => ['bg'=>'bg-indigo-100', 'text'=>'text-indigo-700','label'=>'Review Berkas'],
                                    'bank_process' => ['bg'=>'bg-orange-100', 'text'=>'text-orange-700','label'=>'Proses Bank'],
                                    'bank_review'  => ['bg'=>'bg-orange-100', 'text'=>'text-orange-700','label'=>'Proses Bank'],
                                    'sold'         => ['bg'=>'bg-emerald-100','text'=>'text-emerald-700','label'=>'Terjual'],
                                    'rejected'     => ['bg'=>'bg-red-100',    'text'=>'text-red-700','label'=>'Ditolak'],
                                    'canceled'     => ['bg'=>'bg-slate-200',  'text'=>'text-slate-700','label'=>'Dibatalkan']
                                ][$trx->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600','label'=>$trx->status];
                            @endphp

                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border border-transparent {{ $badge['bg'] }} {{ $badge['text'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>

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
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400 opacity-60">
                                <i class="fa-regular fa-folder-open text-3xl mb-3"></i>
                                <p class="text-sm font-medium">Belum ada transaksi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Mobile View --}}
        <div class="lg:hidden space-y-4">
            @foreach($transactions as $trx)
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold border border-slate-200">
                            {{ substr($trx->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $trx->user->name }}</p>
                            <p class="text-xs font-mono text-slate-500">{{ $trx->code }}</p>
                        </div>
                    </div>

                    @php
                        $mobileBadge = $badge ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600'];
                    @endphp
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold {{ $mobileBadge['bg'] }} {{ $mobileBadge['text'] }}">
                        {{ ucfirst($trx->status) }}
                    </span>
                </div>

                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Unit</span>
                        <span class="font-bold text-slate-700">{{ $trx->unit->location->name }} ({{ $trx->unit->block_number }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Tanggal</span>
                        <span class="font-bold text-slate-700">{{ $trx->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.transactions.show', $trx->id) }}"
                   class="flex items-center justify-center py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors">
                    Lihat Detail
                </a>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-3 mt-5 flex items-center justify-between sm:px-6">
            {{ $transactions->links() }}
        </div>
        @endif

    </div>
</div>
@endsection