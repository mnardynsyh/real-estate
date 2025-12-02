@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Dashboard
            </h1>
            <p class="text-slate-800 mt-1 text-md font-semibold">
                Selamat datang, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span>!
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- total unit --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-32 hover:border-slate-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Unit Tersedia</span>
                        <div class="text-blue-400 bg-slate-50 p-2 rounded-lg">
                            <i class="fa-solid fa-house text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <span class="text-3xl font-bold text-slate-800">{{ $stats['units_available'] }}</span>
                        <span class="text-sm text-slate-400 ml-1">Unit</span>
                    </div>
                </div>

                {{-- verif booking --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-32 hover:border-slate-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Verifikasi Booking
                        </span>
                        <div class="text-amber-600 bg-amber-50 p-2 rounded-lg">
                            <i class="fa-solid fa-clipboard-check text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <span class="text-3xl font-bold text-slate-800">{{ $stats['pending_bookings'] }}</span>
                        <span class="text-sm text-slate-400 ml-1">Pending</span>
                    </div>
                </div>

                {{-- dokumen --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-32 hover:border-slate-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Proses Dokumen</span>
                        <div class="text-indigo-600 bg-indigo-50 p-2 rounded-lg">
                            <i class="fa-solid fa-file-contract text-sm"></i>
                        </div>
                    </div>
                    <div>
                        {{-- Gabungan Booking ACC + Review Berkas --}}
                        <span class="text-3xl font-bold text-slate-800">{{ $stats['docs_review'] }}</span>
                        <span class="text-sm text-slate-400 ml-1">Berkas</span>
                    </div>
                </div>

            </div>

            {{-- tabel riwayat --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-slate-800 text-lg">Antrian Tugas</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi terbaru yang membutuhkan tindakan.</p>
                    </div>
                    <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1 transition-colors">
                        Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold tracking-wide">Transaksi</th>
                                <th class="px-6 py-3 font-semibold tracking-wide">Status</th>
                                <th class="px-6 py-3 font-semibold tracking-wide">Waktu</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_tasks as $task)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                {{-- Transaksi Info --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-500 shrink-0 uppercase">
                                            {{ substr($task->user->name ?? '?', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $task->user->name ?? 'Deleted User' }}</div>
                                            <div class="text-xs text-slate-400 font-mono mt-0.5">
                                                {{ $task->code }} • Blok {{ $task->unit->block_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4">
                                    @if($task->status == 'process')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Verifikasi Booking
                                        </span>
                                    @elseif($task->status == 'docs_review')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            Cek Berkas
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $task->status }}</span>
                                    @endif
                                </td>

                                {{-- Waktu --}}
                                <td class="px-6 py-4 text-xs text-slate-500 tabular-nums">
                                    {{ $task->updated_at->diffForHumans() }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-right">
                                    @php
                                        $actionUrl = match($task->status) {
                                            'process'     => route('admin.transactions.booking'),
                                            'docs_review' => route('admin.transactions.documents'),
                                            default       => route('admin.transactions.show', $task->id)
                                        };
                                        $btnText = $task->status == 'process' ? 'Proses' : ($task->status == 'docs_review' ? 'Periksa' : 'Lihat');
                                    @endphp

                                    <a href="{{ $actionUrl }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                                        {{ $btnText }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400 opacity-60">
                                        <i class="fa-regular fa-circle-check text-3xl mb-2"></i>
                                        <p class="text-sm font-medium">Tidak ada tugas pending</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- progress card --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="bg-slate-900 rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-5 blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-indigo-500 opacity-10 blur-2xl"></div>

                <h3 class="text-sm font-medium text-slate-300 uppercase tracking-wider mb-1">Total Penjualan</h3>
                <div class="flex items-baseline gap-2 mb-6">
                    <span class="text-4xl font-bold tracking-tight">{{ $stats['units_sold'] }}</span>
                    <span class="text-sm text-slate-400">Unit Terjual</span>
                </div>

                @php
                    $totalUnits = $stats['units_available'] + $stats['units_sold'] + $stats['pending_bookings'];
                    $soldPercent = $totalUnits > 0 ? ($stats['units_sold'] / $totalUnits) * 100 : 0;
                    $processPercent = $totalUnits > 0 ? ($stats['pending_bookings'] / $totalUnits) * 100 : 0;
                @endphp

                {{-- Progress Bar --}}
                <div class="mb-2 flex justify-between text-xs font-medium text-slate-400">
                    <span>Progress Penjualan</span>
                    <span>{{ round($soldPercent) }}%</span>
                </div>
                <div class="w-full bg-slate-700 h-2.5 rounded-full overflow-hidden flex mb-6">
                    <div class="bg-emerald-500 h-full" style="width: {{ $soldPercent }}%"></div>
                    <div class="bg-amber-500 h-full" style="width: {{ $processPercent }}%"></div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-slate-300">Sold Out</span>
                        </div>
                        <span class="font-bold">{{ $stats['units_sold'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-slate-300">Dalam Proses</span>
                        </div>
                        <span class="font-bold">{{ $stats['pending_bookings'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm border-t border-slate-700 pt-3 mt-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                            <span class="text-slate-300">Tersedia</span>
                        </div>
                        <span class="font-bold">{{ $stats['units_available'] }}</span>
                    </div>
                </div>
            </div>

            {{-- shortcut --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Akses Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.transactions.booking') }}" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-slate-100 hover:border-slate-200 transition text-center group">
                        <i class="fa-solid fa-check-to-slot text-slate-400 group-hover:text-slate-600 mb-2"></i>
                        <span class="text-xs font-semibold text-slate-600">Verifikasi</span>
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-slate-100 hover:border-slate-200 transition text-center group">
                        <i class="fa-solid fa-list text-slate-400 group-hover:text-slate-600 mb-2"></i>
                        <span class="text-xs font-semibold text-slate-600">Semua Data</span>
                    </a>
                    <a href="{{ route('admin.units.index') }}" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-slate-100 hover:border-slate-200 transition text-center group">
                        <i class="fa-solid fa-building text-slate-400 group-hover:text-slate-600 mb-2"></i>
                        <span class="text-xs font-semibold text-slate-600">Master Unit</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-slate-100 hover:border-slate-200 transition text-center group">
                        <i class="fa-solid fa-users text-slate-400 group-hover:text-slate-600 mb-2"></i>
                        <span class="text-xs font-semibold text-slate-600">Customer</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection