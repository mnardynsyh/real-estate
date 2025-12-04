<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-white/50 backdrop-blur-sm'"
     class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-slate-200/50">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fa-solid fa-house"></i>
                </div>
                <span class="text-xl font-bold text-slate-900 tracking-tight">
                    Terra<span class="text-blue-600">Home</span>
                </span>
            </a>

            {{-- DESKTOP MENU --}}
            <div class="hidden md:flex space-x-8 items-center">
                <a href="{{ url('/') }}" 
                   class="text-sm font-bold {{ request()->is('/') ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ route('catalog') }}" 
                   class="text-sm font-bold {{ request()->routeIs('catalog*') ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600' }} transition-colors">
                    Katalog Unit
                </a>
                
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 hover:-translate-y-0.5">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                            Dashboard Saya
                        </a>
                    @endif
                @else
                    <div class="flex items-center gap-4 pl-4 border-l border-slate-200">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                            Daftar Sekarang
                        </a>
                    </div>
                @endauth
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-blue-600 focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU DROPDOWN --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0 top-20 z-40"
         style="display: none;">
        
        <div class="px-4 py-6 space-y-4">
            <a href="{{ url('/') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                Beranda
            </a>
            <a href="{{ route('catalog') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('catalog*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                Katalog Unit
            </a>

            <div class="border-t border-slate-100 pt-4 mt-4">
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block w-full text-center px-5 py-3 text-base font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 shadow-md">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="block w-full text-center px-5 py-3 text-base font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md">
                            Dashboard Saya
                        </a>
                    @endif
                @else
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 text-base font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" class="block w-full text-center px-5 py-3 text-base font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md">
                            Daftar Sekarang
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>