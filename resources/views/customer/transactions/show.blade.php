@extends('layouts.customer')
@section('title', 'Detail Transaksi')

@section('content')
<div class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">
    <div class="max-w-5xl mx-auto w-full flex-1 flex flex-col">

        <div class="mb-6">
            <a href="{{ route('customer.transactions.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50/50 to-transparent rounded-full -mr-20 -mt-20 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 tracking-wide border border-slate-200 font-mono">
                            #{{ $trx->code }}
                        </span>
                        <span class="text-xs font-medium text-slate-400 flex items-center gap-1">
                            <i class="fa-regular fa-calendar"></i> {{ $trx->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    
                    <h1 class="text-2xl lg:text-3xl font-bold text-slate-900">{{ $trx->unit->location->name }}</h1>
                    <p class="text-slate-500 text-sm mt-1 font-medium">Blok {{ $trx->unit->block_number }} • Tipe {{ $trx->unit->type }}</p>

                    <div class="mt-5 flex flex-wrap gap-2 items-center">
                        @php
                            $statusConfig = match($trx->status) {
                                'pending'       => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
                                'process'       => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Verifikasi Admin', 'icon' => 'fa-spinner fa-spin'],
                                'booking_acc'   => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Upload Pemberkasan', 'icon' => 'fa-file-upload'],
                                'docs_review'   => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Review Dokumen', 'icon' => 'fa-magnifying-glass'],
                                'bank_process'  => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank', 'icon' => 'fa-building-columns'],
                                'bank_review'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Proses Bank', 'icon' => 'fa-building-columns'],
                                'sold'          => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Unit Terjual (Sold)', 'icon' => 'fa-check-circle'],
                                'rejected'      => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak / Batal', 'icon' => 'fa-circle-xmark'],
                                'canceled'      => ['bg' => 'bg-slate-200', 'text' => 'text-slate-600', 'label' => 'Dibatalkan', 'icon' => 'fa-ban'],
                                default         => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => ucfirst($trx->status), 'icon' => 'fa-circle-info']
                            };
                        @endphp

                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                            {{ strtoupper($statusConfig['label']) }}
                        </span>

                        @if($trx->status === 'booking_acc' && $trx->admin_note)
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-600 animate-pulse border border-red-200">
                                <i class="fa-solid fa-circle-exclamation"></i> Perlu Revisi
                            </span>
                        @endif
                    </div>
                </div>

                <div class="text-left md:text-right border-t md:border-t-0 pt-4 md:pt-0 border-slate-100 min-w-[150px]">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Booking Fee</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        Rp {{ number_format($trx->booking_fee, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-emerald-600 font-bold mt-1 flex items-center justify-start md:justify-end gap-1">
                        <i class="fa-solid fa-check-circle"></i> Lunas
                    </p>
                </div>
            </div>

            @if($trx->admin_note && in_array($trx->status, ['booking_acc', 'rejected']))
                <div class="mt-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-red-800">Pesan dari Admin:</h4>
                        <p class="text-sm text-red-600 mt-1 leading-relaxed">"{{ $trx->admin_note }}"</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6 shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between w-full relative">
                @for($i=1; $i<=7; $i++)
                    @php
                        $isCompleted = $i < $currentStep;
                        $isCurrent   = $i == $currentStep;
                        
                        $circleClass = $isCompleted 
                            ? 'bg-emerald-500 text-white border-emerald-500' 
                            : ($isCurrent ? 'bg-blue-600 text-white border-blue-600 ring-4 ring-blue-100' : 'bg-white text-slate-300 border-slate-200');
                        
                        $lineClass = $isCompleted ? 'bg-emerald-500' : 'bg-slate-200';
                        $textClass = $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-emerald-600' : 'text-slate-400');
                    @endphp

                    <div class="relative flex flex-1 md:flex-col items-start md:items-center {{ $i < 7 ? 'pb-8 md:pb-0' : '' }}">
                        
                        @if($i < 7)
                            <div class="absolute left-[14px] top-8 bottom-0 w-1 {{ $lineClass }} md:hidden"></div>
                            <div class="hidden md:block absolute top-4 left-1/2 w-full h-1 {{ $lineClass }} -z-10"></div>
                        @endif

                        <div class="z-10 flex items-center justify-center w-8 h-8 rounded-full border-2 text-xs font-bold transition-all shrink-0 {{ $circleClass }} bg-white">
                            @if($isCompleted) <i class="fa-solid fa-check"></i> @else {{ $i }} @endif
                        </div>

                        <div class="ml-4 md:ml-0 md:mt-3 md:text-center pt-1.5 md:pt-0">
                            <span class="text-[10px] font-bold uppercase tracking-wide {{ $textClass }} block">
                                {{ $stepTitles[$i] }}
                            </span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-10">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm h-fit">
                    <div class="aspect-video bg-slate-100 relative group">
                        @if($trx->unit->image)
                            <img src="{{ Storage::url($trx->unit->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-house text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 bg-black/60 text-white text-xs font-bold rounded backdrop-blur-sm">
                                Tipe {{ $trx->unit->type }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="font-bold text-slate-900 mb-1 text-lg">Spesifikasi Unit</h3>
                        <p class="text-xs text-slate-500 mb-5 flex items-start gap-2">
                            <i class="fa-solid fa-map-pin mt-0.5 text-red-500"></i>
                            {{ $trx->unit->location->address }}
                        </p>
                        
                        <div class="space-y-3 border-t border-slate-100 pt-4">
                            <div class="flex justify-between text-sm pb-2 border-b border-slate-50">
                                <span class="text-slate-500">Luas Tanah</span>
                                <span class="font-bold text-slate-700">{{ $trx->unit->land_area }} m²</span>
                            </div>
                            <div class="flex justify-between text-sm pb-2 border-b border-slate-50">
                                <span class="text-slate-500">Luas Bangunan</span>
                                <span class="font-bold text-slate-700">{{ $trx->unit->building_area }} m²</span>
                            </div>
                            <div class="flex justify-between text-sm pt-1">
                                <span class="text-slate-500 font-medium">Harga Cash</span>
                                <span class="font-bold text-emerald-600">Rp {{ number_format($trx->unit->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mau%20tanya%20tentang%20booking%20{{ $trx->code }}" target="_blank" class="flex items-center justify-center w-full mt-6 py-2.5 bg-emerald-50 text-emerald-600 font-bold text-sm rounded-xl border border-emerald-200 hover:bg-emerald-100 transition-colors gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi Marketing
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">

                @if($trx->status === 'pending')
                    <div x-data="{ photoName: null, photoPreview: null }" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                                <i class="fa-solid fa-receipt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Upload Bukti Pembayaran</h3>
                                <p class="text-sm text-slate-500">Silakan transfer ke rekening <b>BCA 1234567890 a.n PT Perumahan</b> dan upload buktinya di sini.</p>
                            </div>
                        </div>

                        <form action="{{ route('customer.transactions.upload_proof', $trx->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            
                            <div class="mb-6">
                                <div x-show="! photoPreview" class="w-full h-52 border-2 border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center text-slate-400 bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" x-on:click="$refs.photo.click()">
                                    <i class="fa-regular fa-image text-3xl mb-2 text-slate-300"></i>
                                    <p class="text-sm font-medium">Klik untuk upload foto bukti</p>
                                    <p class="text-xs text-slate-400 mt-1">JPG, PNG (Max 5MB)</p>
                                </div>
                                <div x-show="photoPreview" style="display: none;">
                                    <span class="block w-full h-64 rounded-xl bg-cover bg-center bg-no-repeat shadow-inner border border-slate-200"
                                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                    </span>
                                    <button type="button" x-on:click="photoPreview = null" class="mt-2 text-xs text-red-600 font-bold hover:underline">Hapus Foto</button>
                                </div>
                            </div>

                            <input type="file" name="booking_proof" class="hidden" x-ref="photo"
                                   x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                   ">

                            <div class="flex gap-3">
                                <button type="button" x-on:click="$refs.photo.click()" class="px-6 py-3 bg-white text-slate-700 font-bold text-sm rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                                    Pilih File
                                </button>
                                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 disabled:opacity-50" :disabled="!photoPreview">
                                    Kirim Bukti Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>

                @elseif($trx->status === 'process')
                    <div class="bg-white rounded-2xl border border-blue-200 bg-blue-50/30 p-8 text-center shadow-sm">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-5 text-blue-600 animate-pulse">
                            <i class="fa-solid fa-hourglass-half text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Pembayaran Sedang Diverifikasi</h3>
                        <p class="text-slate-500 mt-2 max-w-md mx-auto text-sm leading-relaxed">
                            Terima kasih! Admin kami sedang mengecek mutasi bank. Mohon tunggu maksimal <b>1x24 jam</b>. Notifikasi akan muncul di sini jika status berubah.
                        </p>
                    </div>

                @elseif(in_array($trx->status, ['booking_acc', 'docs_review']))
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        
                        @php
                            $uploadedCount = $trx->documents->whereIn('type', array_keys($requiredDocs))->unique('type')->count();
                            $totalRequired = count($requiredDocs);
                            $progressPercent = ($uploadedCount / $totalRequired) * 100;
                        @endphp

                        <div class="flex items-end justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Kelengkapan Dokumen</h3>
                                <p class="text-sm text-slate-500 mt-0.5">Mohon lengkapi seluruh dokumen persyaratan KPR di bawah ini.</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-indigo-600">{{ $uploadedCount }}</span>
                                <span class="text-sm text-slate-400 font-bold">/ {{ $totalRequired }}</span>
                            </div>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-2.5 mb-8 overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full transition-all duration-700 ease-out shadow-[0_0_10px_rgba(79,70,229,0.4)]" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <div class="space-y-4">
                            @foreach($requiredDocs as $key => $label)
                                @php 
                                    $doc = $trx->documents->where('type', $key)->sortByDesc('created_at')->first(); 
                                    $isUploaded = $doc != null;
                                    
                                    $status = $doc->status ?? 'pending';
                                    $isInvalid = $isUploaded && $status === 'invalid';
                                    $isValid   = $isUploaded && $status === 'valid';
                                    $isPending = $isUploaded && $status === 'pending';
                                    
                                    if ($isInvalid) {
                                        $containerClass = "bg-red-50 border-red-300 ring-1 ring-red-200";
                                        $iconClass = "bg-red-100 text-red-600";
                                        $icon = "fa-solid fa-xmark";
                                        $statusText = "Perlu Revisi";
                                        $btnText = "Upload Revisi";
                                        $btnClass = "bg-red-600 hover:bg-red-700 text-white shadow-red-200";
                                    } elseif ($isValid) {
                                        $containerClass = "bg-white border-emerald-200";
                                        $iconClass = "bg-emerald-100 text-emerald-600";
                                        $icon = "fa-solid fa-check";
                                        $statusText = "Valid";
                                        $btnText = "Lihat";
                                        $btnClass = "bg-slate-100 hover:bg-slate-200 text-slate-600";
                                    } elseif ($isPending) {
                                        $containerClass = "bg-white border-blue-300 shadow-sm";
                                        $iconClass = "bg-blue-100 text-blue-600";
                                        $icon = "fa-regular fa-clock";
                                        $statusText = "Menunggu Verifikasi";
                                        $btnText = "Ganti File";
                                        $btnClass = "bg-white border border-slate-300 text-slate-600 hover:bg-slate-50";
                                    } else {
                                        $containerClass = "bg-slate-50 border-slate-200 hover:border-indigo-300 border-dashed transition-colors";
                                        $iconClass = "bg-slate-200 text-slate-400";
                                        $icon = "fa-solid fa-upload";
                                        $statusText = "Wajib Diisi";
                                        $btnText = "Upload";
                                        $btnClass = "bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-200 shadow-md";
                                    }
                                @endphp

                                <div class="group border rounded-xl p-4 transition-all {{ $containerClass }}">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $iconClass }}">
                                                <i class="{{ $icon }}"></i>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <p class="font-bold text-sm text-slate-800">{{ $label }}</p>
                                                
                                                @if($isInvalid)
                                                    <div class="mt-1">
                                                        <span class="text-[10px] font-bold text-red-600 bg-white px-2 py-0.5 rounded border border-red-200 inline-block mb-2">
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
                                                    <p class="text-[11px] text-blue-600 font-bold mt-0.5">{{ $statusText }}</p>
                                                    <p class="text-[10px] text-slate-400">Diunggah {{ $doc->created_at->diffForHumans() }}</p>
                                                @else
                                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $statusText }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 self-end sm:self-center">
                                            @if($isUploaded)
                                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    Lihat
                                                </a>
                                            @endif
                                            
                                            @if(!$isValid) 
                                                <form action="{{ route('customer.transactions.upload_doc', $trx->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="type" value="{{ $key }}">
                                                    <label class="cursor-pointer px-4 py-2 text-xs font-bold rounded-lg transition-all inline-block text-center min-w-[100px] {{ $btnClass }}">
                                                        {{ $btnText }}
                                                        <input type="file" name="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                            @else
                                                <div class="px-4 py-2 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center gap-1 cursor-default">
                                                    <i class="fa-solid fa-check-double"></i> Selesai
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif(in_array($trx->status, ['bank_process', 'bank_review', 'approved']))
                    <div class="bg-white rounded-2xl border border-indigo-200 bg-indigo-50/30 p-10 text-center shadow-sm">
                        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-600 shadow-inner border-4 border-indigo-50 animate-pulse">
                            <i class="fa-solid fa-building-columns text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900">Pengajuan KPR Diproses</h3>
                        <p class="text-slate-500 mt-3 max-w-lg mx-auto leading-relaxed">
                            Dokumen Anda telah divalidasi oleh Admin. Saat ini pengajuan KPR sedang diproses oleh pihak Bank/Developer. 
                            <br><br>
                            Mohon tunggu info selanjutnya untuk jadwal <b>Akad Kredit</b>.
                        </p>
                    </div>

                @else
                    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
                        @if($trx->status == 'sold')
                            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 shadow-inner border-4 border-emerald-50">
                                <i class="fa-solid fa-house-chimney-user text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">Selamat! Rumah Milik Anda</h3>
                            <p class="text-slate-500 mt-2 max-w-md mx-auto">Proses akad telah selesai dengan sukses. Terima kasih telah mempercayakan hunian impian Anda kepada kami.</p>
                            <a href="{{ route('customer.transactions.index') }}" class="inline-block mt-6 px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">Lihat Riwayat</a>
                        
                        @elseif($trx->status == 'rejected')
                             <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 shadow-inner border-4 border-red-50">
                                <i class="fa-regular fa-circle-xmark text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">Transaksi Dibatalkan</h3>
                            <p class="text-slate-500 mt-2 max-w-md mx-auto">Mohon maaf, transaksi ini tidak dapat dilanjutkan. Hubungi marketing kami untuk informasi lebih lanjut.</p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="inline-block mt-6 px-6 py-3 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition">Hubungi Bantuan</a>
                        @endif
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection