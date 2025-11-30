@extends('layouts.customer')
@section('title', 'Profil Saya')

@section('content')
<div class="w-full min-h-screen bg-[#F0F2F5] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 flex flex-col font-sans text-slate-800">
    <div class="max-w-6xl mx-auto w-full">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-slate-900">Profil Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi data diri Anda untuk kemudahan proses booking dan KPR.</p>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 shadow-sm">
                <p class="font-bold text-sm mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Mohon periksa inputan Anda:</p>
                <ul class="list-disc pl-5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: INFO AKUN --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Avatar & Basic Info --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-blue-600 to-blue-500"></div>
                        
                        <div class="relative z-10 w-24 h-24 mx-auto -mt-4 bg-white rounded-full p-1.5 shadow-lg">
                            <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-3xl font-bold border border-slate-200">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                        </div>
                        
                        <h3 class="mt-4 font-bold text-lg text-slate-900">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100">
                            <i class="fa-solid fa-user-check"></i> Akun Pelanggan
                        </div>
                    </div>

                    {{-- Ubah Password & Kontak --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h4 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-slate-400"></i> Akun & Keamanan
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email (Login)</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                       class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                                <p class="text-[10px] text-slate-400 mt-1">*Email tidak dapat diubah.</p>
                            </div>

                            <hr class="border-slate-100 my-2">

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Baru <span class="text-slate-400 font-normal normal-case">(Opsional)</span></label>
                                <input type="password" name="password" placeholder="Biarkan kosong jika tidak diganti"
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: DATA DIRI (PENTING) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 h-full relative overflow-hidden">
                        
                        {{-- Background Decoration --}}
                        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-50 rounded-bl-full -mr-10 -mt-10 opacity-50 pointer-events-none"></div>

                        <div class="relative z-10 mb-6 pb-6 border-b border-slate-100">
                            <h2 class="text-xl font-bold text-slate-900">Data Pribadi</h2>
                            <p class="text-sm text-slate-500 mt-1">
                                Informasi ini wajib diisi untuk keperluan <b class="text-slate-700">SPK dan Pengajuan KPR</b>.
                            </p>
                        </div>

                        <div class="space-y-6 relative z-10">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- NIK --}}
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIK (Sesuai KTP)</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                            <i class="fa-regular fa-id-card"></i>
                                        </span>
                                        <input type="number" name="nik" value="{{ old('nik', $user->customer->nik ?? '') }}" required placeholder="16 digit angka"
                                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-slate-700 placeholder:text-slate-400">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 ml-1">Wajib 16 digit.</p>
                                </div>

                                {{-- No HP --}}
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No. WhatsApp Aktif</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-green-500 transition-colors">
                                            <i class="fa-brands fa-whatsapp text-lg"></i>
                                        </span>
                                        <input type="number" name="phone" value="{{ old('phone', $user->customer->phone ?? '') }}" required placeholder="0812..."
                                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-slate-700 placeholder:text-slate-400">
                                    </div>
                                </div>
                            </div>

                            {{-- Pekerjaan (Grid Penuh karena Gaji dihapus) --}}
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pekerjaan</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </span>
                                    <input type="text" name="job" value="{{ old('job', $user->customer->job ?? '') }}" required placeholder="Karyawan Swasta / PNS / Wirausaha"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-slate-700 placeholder:text-slate-400">
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Domisili Lengkap</label>
                                <div class="relative">
                                    <span class="absolute top-3.5 left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </span>
                                    <textarea name="address" rows="3" required placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos"
                                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-slate-700 placeholder:text-slate-400 leading-relaxed">{{ old('address', $user->customer->address ?? '') }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="fa-solid fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection