<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate System</title>
    
    {{-- Vite Resources (CSS & JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen">
        
        {{-- BAGIAN KIRI: GAMBAR (Hidden di Mobile) --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
            {{-- Background Image --}}
            <img src="https://images.unsplash.com/photo-1600596542815-9ad4dc7553e3?q=80&w=2575&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60" 
                 alt="Luxury House">
            
            {{-- Overlay Content --}}
            <div class="relative z-10 p-16 flex flex-col justify-between h-full text-white">
                <div>
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-900/50">
                        <i class="fa-solid fa-city text-2xl"></i>
                    </div>
                    <h2 class="text-4xl font-bold leading-tight">Wujudkan Rumah <br>Impian Anda.</h2>
                    <p class="mt-4 text-slate-300 text-lg font-light">Platform terpercaya untuk menemukan hunian nyaman dan investasi masa depan.</p>
                </div>
                
                <div class="flex gap-4 text-sm text-slate-400">
                    <span>&copy; {{ date('Y') }} RealEstateKu.</span>
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: FORM LOGIN --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 bg-[#F8FAFC]">
            <div class="w-full max-w-[420px] bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">
                
                <div class="mb-8 text-center lg:text-left">
                    <h1 class="text-2xl font-bold text-slate-900">Selamat Datang! 👋</h1>
                    <p class="text-sm text-slate-500 mt-2">Silakan login untuk mengakses akun Anda.</p>
                </div>

                {{-- Alert Error --}}
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <div class="text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5">
                        {{-- Input Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                    placeholder="nama@email.com">
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-slate-700">Password</label>
                                <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">Lupa password?</a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input type="password" name="password" required
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                            <label for="remember" class="ml-2 text-sm font-medium text-slate-600 cursor-pointer select-none">
                                Ingat saya di perangkat ini
                            </label>
                        </div>

                        {{-- Tombol Login --}}
                        <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 active:translate-y-0 active:shadow-md">
                            Masuk Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Belum punya akun? 
                        <a href="{{ url('/register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">Daftar Akun</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>