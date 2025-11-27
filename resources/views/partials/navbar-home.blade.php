<nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fa-solid fa-house"></i>
                </div>
                <span class="text-xl font-bold text-slate-900 tracking-tight">RealEstate<span class="text-blue-600">Ku</span></span>
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex space-x-8 items-center">
                <a href="{{ url('/') }}" class="text-sm font-semibold {{ request()->is('/') ? 'text-blue-600' : 'text-slate-600 hover:text-blue-600' }} transition-colors">Beranda</a>
                <a href="{{ route('catalog') }}" class="text-sm font-semibold {{ request()->routeIs('catalog*') ? 'text-blue-600' : 'text-slate-600 hover:text-blue-600' }} transition-colors">Katalog Unit</a>
                
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all shadow-md">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-200">
                            Dashboard Saya
                        </a>
                    @endif
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold text-slate-700 hover:text-blue-600 transition-colors">Masuk</a>
                        <a href="{{ url('/register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-200">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Mobile Menu Button (Opsional, icon hamburger) --}}
            <div class="md:hidden flex items-center">
                <button class="text-slate-600 hover:text-blue-600 focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>