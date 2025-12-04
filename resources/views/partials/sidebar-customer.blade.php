
<div x-data="{ sidebarOpen: false, profileOpen: false }">

    {{-- NAVBAR ATAS --}}
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3 h-16 flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                
                {{-- Mobile Toggle Button (Updated with Alpine) --}}
                <button @click="sidebarOpen = !sidebarOpen" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>

                {{-- Logo Brand --}}
                <a href="{{ route('home') }}" class="flex ms-2 md:me-24 items-center gap-2">
                    <span class="self-center text-md font-bold sm:text-2xl whitespace-nowrap text-gray-800 tracking-tight">Terra<span class="text-blue-600">Home</span></span>
                </a>
            </div>

            {{-- User Profile Dropdown --}}
            <div class="flex items-center relative">
                <div class="flex items-center ms-3">
                    <div>
                        <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-blue-100 transition-all" aria-expanded="false">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563EB&color=FFFFFF&bold=true" 
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
                            <p class="text-xs text-gray-500 truncate font-medium">{{ Auth::user()->email }}</p>
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

    {{-- SIDEBAR CUSTOMER --}}
    <aside id="customer-sidebar" 
           class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           aria-label="Sidebar">
           
        <div class="h-full pb-4 overflow-y-auto flex flex-col justify-between font-sans">
            
            <ul class="space-y-1 font-medium px-3">
                
                <div class="px-2 mb-2 mt-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                    Menu Pelanggan
                </div>

                <li>
                    <a href="{{ route('customer.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                       {{ request()->routeIs('customer.dashboard') 
                           ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-chart-pie w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span class="flex-1 whitespace-nowrap">Beranda</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('customer.transactions.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                       {{ request()->routeIs('customer.transactions.*') 
                           ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-file-contract w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs('customer.transactions.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span class="flex-1 whitespace-nowrap">Riwayat Booking</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('customer.profile.edit')}}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                       {{ request()->routeIs('customer.profile.*') 
                           ? 'bg-blue-50 text-blue-700 font-bold shadow-sm ring-1 ring-blue-200' 
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        
                        <i class="fa-solid fa-user-pen w-6 text-center text-[18px] transition duration-200 
                           {{ request()->routeIs('customer.profile.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span class="flex-1 whitespace-nowrap">Profil Saya</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('catalog') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                        <i class="fa-solid fa-magnifying-glass-location w-6 text-center text-[18px] text-gray-400"></i>
                        <span class="flex-1 whitespace-nowrap">Cari Unit Baru</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400"></i>
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
         x-transition.opacity
         class="fixed inset-0 z-30 bg-gray-900/50 sm:hidden"></div>

</div>