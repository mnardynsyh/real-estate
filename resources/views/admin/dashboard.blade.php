@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center pt-16 mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-sm text-slate-500">Ringkasan aktivitas penjualan perumahan.</p>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200">
            <i class="fa-regular fa-calendar text-slate-500"></i>
            <span class="text-sm font-medium text-slate-600">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unit Tersedia</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['units_available'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <i class="fa-solid fa-house-chimney text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Verifikasi Booking</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pending_bookings'] }}</h3>
                @if($stats['pending_bookings'] > 0)
                    <p class="text-xs text-yellow-600 mt-1 font-medium animate-pulse">Butuh Tindakan!</p>
                @else
                    <p class="text-xs text-slate-400 mt-1">Aman terkendali</p>
                @endif
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg">
                <i class="fa-solid fa-hourglass-start text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Review Berkas</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['docs_review'] }}</h3>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                <i class="fa-solid fa-folder-open text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex justify-between items-start">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Terjual</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['units_sold'] }}</h3>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="font-bold text-slate-800">Tugas Masuk (Pending)</h2>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">Lihat Semua</a>
        </div>
        
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Kode TRX</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recent_tasks as $task)
                    <tr class="bg-white hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $task->code }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                    {{ substr($task->user->name ?? '?', 0, 2) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-700">{{ $task->user->name ?? 'User Terhapus' }}</span>
                                    <span class="text-xs text-slate-400">{{ $task->user->email ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $task->unit->block_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($task->status == 'process')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-500 rounded-full"></span>
                                    Cek Booking
                                </span>
                            @elseif($task->status == 'docs_review')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-purple-500 rounded-full"></span>
                                    Cek Berkas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $task->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-900 font-medium text-sm">Proses</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-regular fa-clipboard-check text-4xl mb-3 opacity-50"></i>
                            <p>Tidak ada tugas pending saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection