@extends('layouts.customer')
@section('title', 'Detail Transaksi')

@section('content')

<div class="bg-[#F0F2F5] min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4">

        {{-- BACK BUTTON --}}
        <a href="{{ route('customer.transactions.index') }}"
           class="inline-flex items-center gap-2 mb-6 text-slate-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="font-medium">Kembali ke Daftar Transaksi</span>
        </a>

        {{-- ===================== --}}
        {{-- HEADER TRANSAKSI --}}
        {{-- ===================== --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-slate-500">Kode Transaksi</p>
                    <h1 class="text-xl font-bold text-slate-900">{{ $trx->code }}</h1>

                    <p class="text-xs text-slate-500 mt-3">Status Saat Ini</p>
                    @php
                        $statusColor = [
                            'pending'       => 'bg-amber-100 text-amber-700',
                            'process'       => 'bg-blue-100 text-blue-700',
                            'booking_acc'   => 'bg-emerald-100 text-emerald-700',
                            'docs_review'   => 'bg-indigo-100 text-indigo-700',
                            'docs_revise'   => 'bg-red-100 text-red-700',
                            'bank_process'  => 'bg-purple-100 text-purple-700',
                            'sold'          => 'bg-slate-800 text-white',
                            'rejected'      => 'bg-red-200 text-red-800',
                            'canceled'      => 'bg-slate-200 text-slate-600'
                        ][$trx->status] ?? 'bg-slate-100 text-slate-600';
                    @endphp

                    <span class="px-3 py-1 mt-1 inline-block rounded-lg text-xs font-bold uppercase {{ $statusColor }}">
                        {{ $trx->status }}
                    </span>
                </div>

                {{-- HARGA --}}
                <div class="text-right">
                    <p class="text-xs text-slate-500">Booking Fee</p>
                    <p class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>



        {{-- ===================== --}}
        {{-- STEPPER STATUS --}}
        {{-- ===================== --}}
        @php
            $steps = [
                1 => 'Bukti Pembayaran',
                2 => 'Review Admin',
                3 => 'Booking Disetujui',
                4 => 'Upload Dokumen',
                5 => 'Proses Bank',
                6 => 'Selesai'
            ];

            $stepMap = [
                'pending'      => 1,
                'process'      => 2,
                'booking_acc'  => 3,
                'docs_review'  => 4,
                'docs_revise'  => 4,
                'bank_process' => 5,
                'sold'         => 6,
                'rejected'     => 6,
                'canceled'     => 6,
            ];

            $currentStep = $stepMap[$trx->status] ?? 1;
        @endphp

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between">

                @for($i = 1; $i <= 6; $i++)
                    @php
                        $isActive = $currentStep == $i;
                        $isDone   = $i < $currentStep;

                        $circleClass = $isActive
                            ? 'bg-blue-600 text-white'
                            : ($isDone ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500');
                    @endphp

                    <div class="flex flex-col items-center flex-1">
                        <div class="flex items-center w-full">

                            {{-- Circle --}}
                            <div class="w-10 h-10 flex items-center justify-center rounded-full font-bold {{ $circleClass }}">
                                {{ $i }}
                            </div>

                            {{-- Line --}}
                            @if($i < 6)
                                <div class="flex-1 h-1 mx-2 rounded-full 
                                    {{ $isDone ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                </div>
                            @endif
                        </div>

                        <p class="text-[11px] mt-2 text-center text-slate-600 font-medium">
                            {{ $steps[$i] }}
                        </p>
                    </div>
                @endfor

            </div>
        </div>



        {{-- ===================== --}}
        {{-- UNIT DETAILS --}}
        {{-- ===================== --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">

            <h2 class="font-bold text-slate-900 text-lg mb-4">Detail Unit</h2>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-40 h-28 bg-slate-200 rounded-xl overflow-hidden">
                    @if($trx->unit->image)
                        <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400">
                            <i class="fa-solid fa-house text-3xl"></i>
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <p class="font-bold text-slate-900 text-lg">
                        Blok {{ $trx->unit->block_number }}
                    </p>

                    <p class="text-slate-600 text-sm">
                        {{ $trx->unit->location->name }} — {{ $trx->unit->location->city }}
                    </p>

                    <p class="text-xs text-slate-400 mt-3">Alamat Lokasi</p>
                    <p class="text-slate-700 text-sm">
                        {{ $trx->unit->location->address }}
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('unit.show', $trx->unit_id) }}"
                           class="inline-block text-xs px-3 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition">
                            Lihat Detail Unit
                        </a>
                    </div>
                </div>
            </div>
        </div>



        {{-- ===================== --}}
        {{-- UPLOAD BUKTI PEMBAYARAN --}}
        {{-- ===================== --}}
        @if($trx->status == 'pending')
        <div class="bg-white border border-amber-200 rounded-xl p-6 mb-8 shadow-sm">

            <h3 class="text-lg font-bold text-amber-700 mb-3">
                Upload Bukti Pembayaran Booking
            </h3>

            <form action="{{ route('customer.transactions.upload_proof', $trx->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input type="file" name="booking_proof" required accept="image/*"
                       class="block w-full text-sm mb-3 border rounded-lg p-2">

                <button class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700">
                    Upload & Kirim
                </button>
            </form>
        </div>
        @endif




        {{-- ===================== --}}
        {{-- UPLOAD DOKUMEN KPR --}}
        {{-- ===================== --}}
        @php
            $requiredDocs = [
                'ktp' => 'KTP Pemohon',
                'kk' => 'Kartu Keluarga',
                'npwp' => 'NPWP',
                'slip_gaji' => 'Slip Gaji / Bukti Penghasilan',
                'rekening_koran' => 'Rekening Koran 3 Bulan Terakhir'
            ];
        @endphp

        @if(in_array($trx->status, ['booking_acc','docs_review','docs_revise']))
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">

            <h3 class="text-lg font-bold text-slate-900 mb-4">Upload Dokumen KPR</h3>

            <div class="grid gap-4">
                @foreach($requiredDocs as $key => $label)
                    @php
                        $doc = $trx->documents->where('type',$key)->first();
                    @endphp

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">

                        <div class="flex justify-between items-center mb-3">
                            <p class="font-medium text-slate-800">{{ $label }}</p>

                            @if($doc)
                                <span class="text-xs font-bold
                                    {{ $doc->status == 'approved' ? 'text-emerald-600' :
                                       ($doc->status == 'pending' ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ strtoupper($doc->status) }}
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('customer.transactions.upload_doc', $trx->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ $key }}">

                            <input type="file" name="file" required
                                   class="block w-full text-sm mb-2 border rounded-lg p-2">

                            <button class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">
                                Upload Dokumen
                            </button>
                        </form>

                        @if($doc && $doc->note)
                            <p class="text-xs text-red-600 mt-2">{{ $doc->note }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
        @endif


    </div>
</div>

@endsection
