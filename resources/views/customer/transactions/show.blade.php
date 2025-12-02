@extends('layouts.customer')
@section('title', 'Detail Transaksi')

@section('content')
<div class="bg-[#F0F2F5] min-h-screen py-8 lg:py-10">
    <div class="max-w-4xl mx-auto px-4">

        {{-- BACK BUTTON --}}
        <a href="{{ route('customer.transactions.index') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>

        {{-- HEADER STATUS --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -mr-10 -mt-10"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 tracking-wide border border-slate-200">{{ $trx->code }}</span>
                        <span class="text-xs text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $trx->unit->location->name }}</h1>
                    <p class="text-slate-500 text-sm">Blok {{ $trx->unit->block_number }} — Tipe {{ $trx->unit->type }}</p>
                    
                    <div class="mt-4 flex flex-wrap gap-2 items-center">
                        @php
                            $statusConfig = match($trx->status) {
                                'pending'       => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Pembayaran'],
                                'process'       => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Verifikasi Pembayaran'],
                                'booking_acc'   => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Booking Diterima'],
                                'docs_review'   => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Review Dokumen'],
                                'bank_review'   => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Proses Bank'],
                                'sold'          => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Unit Terjual (Sold)'],
                                'rejected'      => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak / Batal'],
                                default         => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => $trx->status]
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">{{ strtoupper($statusConfig['label']) }}</span>
                        
                        @if($trx->status === 'booking_acc' && $trx->admin_note)
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-600 animate-pulse"><i class="fa-solid fa-circle-exclamation mr-1"></i> Perlu Revisi</span>
                        @endif
                    </div>
                </div>
                <div class="text-left md:text-right border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-bold">Booking Fee</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- ALERT REVISI GLOBAL --}}
            @if($trx->admin_note && in_array($trx->status, ['booking_acc', 'rejected']))
                <div class="mt-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-red-700">Pesan dari Admin:</h4>
                        <p class="text-sm text-red-600 mt-1">{{ $trx->admin_note }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- STEPPER --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6 shadow-sm overflow-hidden">
            <div class="overflow-x-auto no-scrollbar pb-2">
                <div class="flex items-center min-w-max px-2">
                    @for($i=1; $i<=7; $i++)
                        @php
                            $isCompleted = $i < $currentStep; $isCurrent = $i == $currentStep;
                            $circleClass = $isCompleted ? 'bg-emerald-500 text-white border-emerald-500' : ($isCurrent ? 'bg-blue-600 text-white border-blue-600 ring-4 ring-blue-100' : 'bg-white text-slate-300 border-slate-200');
                            $lineClass = $isCompleted ? 'bg-emerald-500' : 'bg-slate-200';
                        @endphp
                        <div class="flex items-center">
                            <div class="relative flex flex-col items-center group">
                                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all z-10 {{ $circleClass }}">@if($isCompleted) <i class="fa-solid fa-check"></i> @else {{ $i }} @endif</div>
                                <div class="absolute -bottom-6 w-32 text-center"><span class="text-[10px] font-bold {{ $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-emerald-600' : 'text-slate-400') }}">{{ $stepTitles[$i] }}</span></div>
                            </div>
                            @if($i < 7) <div class="w-12 h-1 {{ $lineClass }} mx-2 rounded-full"></div> @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- KOLOM KIRI --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="aspect-video bg-slate-100 relative">
                        @if($trx->unit->image) <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover"> @else <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fa-solid fa-house text-4xl"></i></div> @endif
                        <div class="absolute top-3 right-3"><span class="px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">Tipe {{ $trx->unit->type }}</span></div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-slate-900 mb-1">Spesifikasi Unit</h3>
                        <p class="text-xs text-slate-500 mb-4">{{ $trx->unit->location->address }}</p>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-slate-50 pb-2"><span class="text-slate-500">Luas Tanah</span><span class="font-bold text-slate-700">{{ $trx->unit->land_area }} m²</span></div>
                            <div class="flex justify-between text-sm border-b border-slate-50 pb-2"><span class="text-slate-500">Luas Bangunan</span><span class="font-bold text-slate-700">{{ $trx->unit->building_area }} m²</span></div>
                            <div class="flex justify-between text-sm pb-2"><span class="text-slate-500">Harga Cash</span><span class="font-bold text-emerald-600">Rp {{ number_format($trx->unit->price, 0, ',', '.') }}</span></div>
                        </div>
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mau%20tanya%20tentang%20booking%20{{ $trx->code }}" target="_blank" class="block w-full text-center mt-4 py-2.5 bg-green-50 text-green-600 font-bold text-sm rounded-xl border border-green-200 hover:bg-green-100 transition-colors"><i class="fa-brands fa-whatsapp mr-1"></i> Hubungi Marketing</a>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="lg:col-span-2">
                @if($trx->status === 'pending')
                    {{-- UPLOAD BUKTI BAYAR --}}
                    <div x-data="{ photoName: null, photoPreview: null }" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600"><i class="fa-solid fa-receipt text-lg"></i></div>
                            <div><h3 class="font-bold text-slate-900">Upload Bukti Pembayaran</h3><p class="text-sm text-slate-500">Transfer ke BCA 1234567890 a.n PT Perumahan</p></div>
                        </div>
                        <form action="{{ route('customer.transactions.upload_proof', $trx->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            <div class="mt-2 mb-4">
                                <div x-show="! photoPreview" class="w-full h-48 border-2 border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center text-slate-400 bg-slate-50"><i class="fa-regular fa-image text-3xl mb-2"></i><p class="text-sm">Preview foto akan muncul di sini</p></div>
                                <div x-show="photoPreview" style="display: none;"><span class="block w-full h-64 rounded-xl bg-cover bg-center bg-no-repeat shadow-md" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span></div>
                            </div>
                            <input type="file" name="booking_proof" class="hidden" x-ref="photo" x-on:change="photoName = $refs.photo.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                            <div class="flex gap-3"><button type="button" x-on:click="$refs.photo.click()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl border border-slate-200 hover:bg-slate-200">Pilih Foto</button><button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">Kirim Bukti</button></div>
                        </form>
                    </div>

                @elseif($trx->status === 'process')
                    {{-- MENUNGGU VERIFIKASI --}}
                    <div class="bg-white rounded-2xl border border-blue-200 bg-blue-50/50 p-8 text-center shadow-sm">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600 animate-pulse"><i class="fa-solid fa-hourglass-half text-3xl"></i></div>
                        <h3 class="text-xl font-bold text-slate-900">Pembayaran Sedang Diverifikasi</h3>
                        <p class="text-slate-500 mt-2 max-w-md mx-auto">Admin kami sedang mengecek mutasi bank. Mohon tunggu maksimal 1x24 jam.</p>
                    </div>

                @elseif(in_array($trx->status, ['booking_acc', 'docs_review']))
                    {{-- PEMBERKASAN --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        
                        {{-- LOGIC COUNT UPLOADED: Hitung berdasarkan tipe dokumen yang unik --}}
                        @php
                            $uploadedCount = $trx->documents->whereIn('type', array_keys($requiredDocs))->unique('type')->count();
                            $totalRequired = count($requiredDocs);
                            $progressPercent = ($uploadedCount / $totalRequired) * 100;
                        @endphp

                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Kelengkapan Dokumen</h3>
                                <p class="text-sm text-slate-500">Mohon lengkapi seluruh dokumen di bawah.</p>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                                {{ $uploadedCount }} / {{ $totalRequired }} Uploaded
                            </span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-slate-100 rounded-full h-2 mb-6">
                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <div class="space-y-4">
                            @foreach($requiredDocs as $key => $label)
                                @php 
                                    // Ambil dokumen berdasarkan tipe
                                    $doc = $trx->documents->where('type', $key)->first(); 
                                    $isUploaded = $doc != null;
                                    
                                    // Status Logic
                                    $status = $doc->status ?? 'pending';
                                    $isInvalid = $isUploaded && $status === 'invalid';
                                    $isValid   = $isUploaded && $status === 'valid';
                                    $isPending = $isUploaded && $status === 'pending';
                                    
                                    // Visual Classes
                                    if ($isInvalid) {
                                        // Merah Tebal jika Invalid/Revisi
                                        $containerClass = "bg-red-50 border-red-500 ring-1 ring-red-500";
                                        $iconClass = "bg-red-100 text-red-600";
                                        $icon = "fa-solid fa-xmark";
                                        $statusText = "Perlu Revisi";
                                        $btnText = "Upload Revisi";
                                        $btnClass = "bg-red-600 hover:bg-red-700 text-white shadow-red-200";
                                    } elseif ($isValid) {
                                        // Hijau jika Valid
                                        $containerClass = "bg-white border-emerald-500";
                                        $iconClass = "bg-emerald-100 text-emerald-600";
                                        $icon = "fa-solid fa-check";
                                        $statusText = "Valid";
                                        $btnText = "Lihat";
                                        $btnClass = "bg-slate-100 hover:bg-slate-200 text-slate-600";
                                    } elseif ($isPending) {
                                        // Biru/Slate jika Pending Verifikasi
                                        $containerClass = "bg-white border-blue-300";
                                        $iconClass = "bg-blue-100 text-blue-600";
                                        $icon = "fa-regular fa-clock";
                                        $statusText = "Menunggu Verifikasi";
                                        $btnText = "Ganti File";
                                        $btnClass = "bg-slate-600 hover:bg-slate-700 text-white";
                                    } else {
                                        // Default Belum Upload
                                        $containerClass = "bg-slate-50 border-slate-200 hover:border-blue-300 border-dashed";
                                        $iconClass = "bg-slate-200 text-slate-400";
                                        $icon = "fa-solid fa-upload";
                                        $statusText = "Wajib Diisi";
                                        $btnText = "Upload";
                                        $btnClass = "bg-blue-600 hover:bg-blue-700 text-white shadow-blue-200";
                                    }
                                @endphp

                                <div class="group border rounded-xl p-4 transition-all {{ $containerClass }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4">
                                            {{-- Icon --}}
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $iconClass }}">
                                                <i class="{{ $icon }}"></i>
                                            </div>
                                            
                                            <div>
                                                <p class="font-bold text-sm text-slate-800">{{ $label }}</p>
                                                
                                                {{-- Status Text & Note --}}
                                                @if($isInvalid)
                                                    <div class="mt-1">
                                                        <span class="text-[10px] font-bold text-red-600 bg-white px-2 py-0.5 rounded border border-red-200 inline-block mb-1">
                                                            {{ $statusText }}
                                                        </span>
                                                        @if($doc->note)
                                                            <div class="text-xs text-red-700 font-medium bg-red-100/50 p-2 rounded border border-red-200 flex items-start gap-2">
                                                                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                                                                <span>"{{ $doc->note }}"</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($isValid)
                                                    <p class="text-[11px] text-emerald-600 font-bold mt-0.5">{{ $statusText }}</p>
                                                @elseif($isPending)
                                                    <p class="text-[11px] text-blue-600 mt-0.5">{{ $statusText }}</p>
                                                @else
                                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $statusText }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-2">
                                            {{-- Tombol Lihat (Selalu muncul jika sudah upload) --}}
                                            @if($isUploaded)
                                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:underline">
                                                    Lihat File
                                                </a>
                                            @endif
                                            
                                            {{-- Form Upload --}}
                                            @if(!$isValid) {{-- Sembunyikan tombol upload jika sudah valid --}}
                                                <form action="{{ route('customer.transactions.upload_doc', $trx->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="type" value="{{ $key }}">
                                                    <label class="cursor-pointer px-4 py-2 text-xs font-bold rounded-lg transition-colors shadow-sm inline-block text-center min-w-[100px] {{ $btnClass }}">
                                                        {{ $btnText }}
                                                        <input type="file" name="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                            @else
                                                <div class="px-4 py-2 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center gap-1 cursor-default">
                                                    <i class="fa-solid fa-check-double"></i> Terverifikasi
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @else
                    {{-- FINAL STATE --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-sm">
                        @if($trx->status == 'sold')
                            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600"><i class="fa-solid fa-house-chimney-user text-4xl"></i></div>
                            <h3 class="text-2xl font-bold text-slate-900">Selamat! Rumah Milik Anda</h3>
                        @elseif($trx->status == 'rejected')
                             <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600"><i class="fa-regular fa-circle-xmark text-4xl"></i></div>
                            <h3 class="text-xl font-bold text-slate-900">Transaksi Dibatalkan</h3>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection