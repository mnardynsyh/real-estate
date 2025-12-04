<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Real Estate System</title>
    
    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Hide scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-white h-screen w-full overflow-hidden flex text-slate-800">

    <!-- Left Section: Branding (Desktop Only) -->
    <div class="hidden lg:flex w-1/2 relative bg-gradient-to-br from-slate-900 via-slate-800 to-black items-center justify-center overflow-hidden">
        
        {{-- Decorative --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-500 opacity-5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 p-16 w-full h-full flex flex-col justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-2 text-white/90">
                <div class="w-8 h-8 border border-white/30 rounded flex items-center justify-center backdrop-blur-sm">
                     <i class="fa-solid fa-city text-xs"></i>
                </div>
                <span class="font-medium tracking-widest text-xs uppercase">TerraHome</span>
            </div>
            
            <div>
                <h2 class="text-5xl font-light text-white leading-[1.1] tracking-tight mb-6">
                    Start Your <br> <span class="font-medium">Investment</span> <br> Journey Here.
                </h2>
                <p class="text-slate-400 font-light text-base max-w-md leading-relaxed">
                    Daftarkan diri Anda sekarang untuk mengakses katalog properti premium dan layanan terpercaya kami.
                </p>
            </div>

            <div class="flex gap-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                <span>&copy; {{ date('Y') }} <a href="https://github.com/mnardynsyh">mnardynsyh</a></span>
            </div>
        </div>
    </div>

    <!-- Right Section: Register Form -->
    <div class="w-full lg:w-1/2 flex flex-col bg-white relative h-full">
        
        <!-- Scrollable Content Container -->
        <div class="flex-1 overflow-y-auto px-8 py-8 sm:px-12 lg:px-24">
            
            <!-- Inner Wrapper untuk membatasi lebar & centering -->
            <div class="w-full max-w-[480px] mx-auto min-h-full flex flex-col justify-center">
                
                <!-- Tombol Kembali (Inline Flow) -->
                <div class="mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-500 bg-white border border-slate-200 rounded-full hover:text-slate-900 hover:border-slate-400 transition-all group shadow-sm">
                        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center text-white shadow-lg">
                         <i class="fa-solid fa-city"></i>
                    </div>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Buat Akun Baru</h1>
                    <p class="text-slate-500 mt-2 text-sm">Lengkapi data diri Anda untuk bergabung.</p>
                </div>

                @if($errors->any())
                    <div class="mb-8 p-3 bg-red-50 border-l-2 border-red-500 text-red-600 text-xs font-medium rounded-r">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- GRID LAYOUT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        
                        <!-- Nama Lengkap (Full Width) -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                                placeholder="Contoh: Budi Santoso">
                        </div>

                        <!-- Email (Sebelah Kiri) -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                                placeholder="nama@email.com">
                        </div>

                        <!-- No WhatsApp (Sebelah Kanan) -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">No. WhatsApp</label>
                            <input type="number" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                                placeholder="08123456789">
                        </div>

                        <!-- Password (Sebelah Kiri) -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                                placeholder="Min. 8 karakter">
                        </div>

                        <!-- Confirm Password (Sebelah Kanan) -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Konfirmasi</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                                placeholder="Ulangi password">
                        </div>

                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                            class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg shadow-slate-200 hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-md">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-10 text-center border-t border-slate-100 pt-6 pb-6">
                    <p class="text-sm text-slate-500">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="font-bold text-slate-900 hover:underline transition-all ml-1">Masuk disini</a>
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>