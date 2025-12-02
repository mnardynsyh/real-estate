{{-- 
    WRAPPER UTAMA (x-data) 
    Mengontrol state sidebar (mobile) dan dropdown profil
--}}
<div x-data="{ sidebarOpen: false, profileOpen: false }">

    {{-- ================= NAVBAR ATAS ================= --}}
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3 h-16 flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                
                {{-- Mobile Toggle Button --}}
                <button @click="sidebarOpen = !sidebarOpen" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>

                {{-- Logo Brand --}}
                <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24 items-center gap-2">
                    <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-white">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-gray-800 tracking-tight">RealEstate<span class="text-blue-600">Admin</span></span>
                </a>
            </div>

            {{-- User Profile Dropdown --}}
            <div class="flex items-center relative">
                <div class="flex items-center ms-3">
                    <div>
                        <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-blue-100 transition-all" aria-expanded="false">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e293b&color=FFFFFF&bold=true" 
                                 alt="user photo">
                        </button>
                    </div>
                    
                    {{-- Dropdown Menu --}}
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-10 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-xl border border-gray-100 w-56 origin-top-right" 
                         style="display: none;">
                        
                        <div class="px-4 py-3 bg-gray-50 rounded-t-lg">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate font-medium">Administrator</p>
                        </div>
                        <ul class="py-1">
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-right-from-bracket mr-2 text-red-400"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ================= SIDEBAR ADMIN ================= --}}
    <aside id="admin-sidebar" 
           class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           aria-label="Sidebar">
           
        <div class="h-full pb-4 overflow-y-auto flex flex-col justify-between font-sans">
            
            <ul class="space-y-1.5 font-medium px-3">
                
                <div class="px-2 mb-2 mt-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                    Menu Utama
                </div>

                {{-- 1. DASHBOARD --}}
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                       {{ request()->routeIs('admin.dashboard') 
                           ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-chart-pie w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span class="flex-1 whitespace-nowrap">Dashboard</span>
                    </a>
                </li>

                {{-- 2. MASTER DATA (Dropdown) --}}
                <li x-data="{ open: {{ request()->routeIs(['admin.housing.*', 'admin.units.*']) ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-all duration-200 group
                            {{ request()->routeIs(['admin.housing.*', 'admin.units.*']) 
                                ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-database w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs(['admin.housing.*', 'admin.units.*']) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        
                        <span class="flex-1 ms-1 text-left whitespace-nowrap">Master Data</span>
                        
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" 
                           :class="{ 'rotate-180': open }"></i>
                    </button>

                    {{-- Submenu --}}
                    <ul x-show="open" x-cloak class="mt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.housing.index') }}" 
                               class="flex items-center w-full p-2 pl-11 rounded-lg text-sm transition-colors
                               {{ request()->routeIs('admin.housing.*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                               Lokasi Perumahan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.units.index') }}" 
                               class="flex items-center w-full p-2 pl-11 rounded-lg text-sm transition-colors
                               {{ request()->routeIs('admin.units.*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                               Data Unit
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 3. TRANSAKSI (Dropdown) --}}
                <li x-data="{ open: {{ request()->routeIs('admin.transactions.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-3 py-2.5 rounded-lg transition-all duration-200 group
                            {{ request()->routeIs('admin.transactions.*') 
                                ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-file-invoice-dollar w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs('admin.transactions.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        
                        <span class="flex-1 ms-1 text-left whitespace-nowrap">Transaksi</span>
                        
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" 
                           :class="{ 'rotate-180': open }"></i>
                    </button>

                    <ul x-show="open" x-cloak class="mt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.transactions.booking') }}" 
                               class="flex items-center w-full p-2 pl-11 rounded-lg text-sm transition-colors
                               {{ request()->routeIs('admin.transactions.booking') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                               Verifikasi Booking
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.transactions.documents') }}" 
                               class="flex items-center w-full p-2 pl-11 rounded-lg text-sm transition-colors
                               {{ request()->routeIs('admin.transactions.documents') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                               Verifikasi Berkas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.transactions.approval') }}" 
                               class="flex items-center w-full p-2 pl-11 rounded-lg text-sm transition-colors
                               {{ request()->routeIs('admin.transactions.approval') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                               Approval & DP
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.transactions.index') }}" 
                               class="flex items-center w-full p-2 pl-11 rounded-lg text-sm transition-colors
                               {{ request()->routeIs('admin.transactions.index') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                               Semua Riwayat
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 4. CUSTOMERS --}}
                <li>
                    <a href="{{ route('admin.customers.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                       {{ request()->routeIs('admin.customers.*') 
                           ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-users w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs('admin.customers.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span class="flex-1 whitespace-nowrap">Data Customer</span>
                    </a>
                </li>

            </ul>

            {{-- FOOTER SIDEBAR (Logout Mobile) --}}
            <div class="px-3 pb-6 mt-4 border-t border-gray-100 pt-6">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-3 px-3 py-2.5 w-full text-left rounded-lg transition-all duration-200 text-gray-600 hover:bg-red-50 hover:text-red-600 group">
                        <i class="fa-solid fa-right-from-bracket w-6 text-center text-[18px] transition group-hover:translate-x-1"></i>
                        <span class="flex-1 whitespace-nowrap font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- OVERLAY MOBILE --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:opacity
         class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"></div>

</div>