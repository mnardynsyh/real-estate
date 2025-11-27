{{-- NAVBAR ATAS --}}
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
    <div class="px-3 py-3 lg:px-5 lg:pl-3 h-16 flex items-center justify-between">
        <div class="flex items-center justify-start rtl:justify-end">
            
            {{-- Mobile Toggle --}}
            <button data-drawer-target="customer-sidebar" data-drawer-toggle="customer-sidebar" aria-controls="customer-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>

            {{-- Logo Brand --}}
            <a href="#" class="flex ms-2 md:me-24 items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                    <i class="fa-solid fa-house"></i>
                </div>
                <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-gray-800 tracking-tight">RealEstate<span class="text-blue-600">Ku</span></span>
            </a>
        </div>
    </div>
</nav>

{{-- SIDEBAR CUSTOMER (THEME: WHITE/LIGHT) --}}
<aside id="customer-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full pb-4 overflow-y-auto flex flex-col justify-between font-sans">
        
        <ul class="space-y-1 font-medium px-3">
            
            <div class="px-2 mb-2 mt-2 text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Menu Pelanggan
            </div>

            {{-- 1. Dashboard --}}
            <li>
                <a href="{{ route('customer.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('customer.dashboard') 
                       ? 'bg-blue-50 text-blue-700 font-bold' 
                       : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    
                    <i class="fa-solid fa-chart-pie w-6 text-center text-[18px] transition duration-200 
                       {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 whitespace-nowrap">Beranda</span>
                </a>
            </li>

            {{-- 2. Katalog (Lihat Unit) --}}
            <li>
                <a href="#" {{-- Nanti arahkan ke halaman public/landing --}}
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                    <i class="fa-solid fa-building-user w-6 text-center text-[18px] text-gray-400"></i>
                    <span class="flex-1 whitespace-nowrap">Cari Rumah</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400"></i>
                </a>
            </li>

            {{-- 3. Transaksi Saya --}}
            <li>
                <a href="#" {{-- route('customer.transactions.index') --}}
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('customer.transactions.*') 
                       ? 'bg-blue-50 text-blue-700 font-bold' 
                       : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    
                    <i class="fa-solid fa-file-invoice-dollar w-6 text-center text-[18px] transition duration-200 
                       {{ request()->routeIs('customer.transactions.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 whitespace-nowrap">Riwayat Booking</span>
                </a>
            </li>

            {{-- 4. Profil Saya --}}
            <li>
                <a href="#" {{-- route('customer.profile') --}}
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('customer.profile') 
                       ? 'bg-blue-50 text-blue-700 font-bold' 
                       : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    
                    <i class="fa-solid fa-user-gear w-6 text-center text-[18px] transition duration-200 
                       {{ request()->routeIs('customer.profile') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 whitespace-nowrap">Profil & Berkas</span>
                </a>
            </li>

        </ul>

        {{-- Bantuan / Support --}}
        <div class="px-3 pb-6 mt-4">
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h4 class="text-sm font-bold text-blue-900">Butuh Bantuan?</h4>
                </div>
                <p class="text-xs text-blue-700 mb-3">Hubungi admin marketing kami jika ada kendala.</p>
                <a href="#" class="block w-full py-2 text-xs font-bold text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Chat Admin
                </a>
            </div>
        </div>
    </div>
</aside>