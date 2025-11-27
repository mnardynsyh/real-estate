<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Real Estate System</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen">
        
        {{-- BAGIAN KIRI: GAMBAR (Sama seperti Login, untuk konsistensi) --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1600596542815-9ad4dc7553e3?q=80&w=2575&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Background">
            <div class="relative z-10 p-16 flex flex-col justify-between h-full text-white">
                <div>
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-900/50">
                        <i class="fa-solid fa-city text-2xl"></i>
                    </div>
                    <h2 class="text-4xl font-bold leading-tight">Bergabunglah <br>Bersama Kami.</h2>
                    <p class="mt-4 text-slate-300 text-lg font-light">Mulai langkah awal memiliki hunian impian Anda hari ini.</p>
                </div>
                <div class="flex gap-4 text-sm text-slate-400">
                    <span>&copy; {{ date('Y') }} RealEstateKu.</span>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: FORM REGISTER --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 bg-[#F8FAFC]">
            <div class="w-full max-w-[480px] bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">
                
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
                    <p class="text-sm text-slate-500 mt-2">Lengkapi data diri Anda untuk mendaftar.</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                placeholder="Contoh: Budi Santoso">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- No HP / WhatsApp --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No. WhatsApp</label>
                            <input type="number" name="phone" value="{{ old('phone') }}" required 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                placeholder="081234567890">
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                placeholder="nama@email.com">
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Password --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password</label>
                                <input type="password" name="password" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                    placeholder="Minimal 8 karakter">
                                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                    placeholder="Ulangi password">
                            </div>
                        </div>

                        {{-- Tombol Daftar --}}
                        <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 active:translate-y-0 active:shadow-md mt-4">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">Login disini</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>