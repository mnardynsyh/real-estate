<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Real Estate System</title>
    
    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
                    Curating <br> <span class="font-medium">Exceptional</span> <br> Lifestyles.
                </h2>
                <p class="text-slate-400 font-light text-base max-w-md leading-relaxed">
                    Akses portofolio properti eksklusif dan kelola investasi masa depan Anda dalam satu platform terintegrasi.
                </p>
            </div>

            <div class="flex gap-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                <span>&copy; {{ date('Y') }} <a href="https://github.com/mnardynsyh">mnardynsyh</a></span>
            </div>
        </div>
    </div>

    <!-- Right Section: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white px-8 sm:px-12 lg:px-24 relative">
        
        <!-- Tombol Kembali -->
        <a href="{{ route('home') }}" class="absolute top-8 left-6 lg:top-8 lg:left-8 inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-500 bg-white border border-slate-200 rounded-full hover:text-slate-900 hover:border-slate-400 transition-all group z-20 shadow-sm">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Beranda</span>
        </a>

        <div class="w-full max-w-sm">
            
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center text-white shadow-lg">
                     <i class="fa-solid fa-city"></i>
                </div>
            </div>

            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Selamat Datang</h1>
                <p class="text-slate-500 mt-2 text-sm">Masuk untuk mengelola properti Anda.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-3 bg-red-50 border-l-2 border-red-500 text-red-600 text-xs font-medium rounded-r">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                        placeholder="nama@email.com">
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-900 uppercase tracking-wider">Password</label>
                        <a href="#" class="text-xs text-slate-400 hover:text-slate-800 transition-colors">Lupa Password?</a>
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 text-slate-900 text-sm focus:outline-none focus:border-slate-800 transition-colors placeholder:text-slate-300"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center pt-2">
                    <input id="remember" name="remember" type="checkbox" 
                        class="w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900 focus:ring-offset-0 cursor-pointer bg-slate-100">
                    <label for="remember" class="ml-2 text-sm text-slate-500 cursor-pointer select-none hover:text-slate-800 transition-colors">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" 
                    class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg shadow-slate-200 hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0">
                    Masuk
                </button>
            </form>

            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <p class="text-sm text-slate-500">
                    Belum memiliki akun? 
                    <a href="{{ url('/register') }}" class="font-bold text-slate-900 hover:underline transition-all ml-1">Masuk disini</a>
                </p>
            </div>
        </div>

    </div>

</body>
</html>