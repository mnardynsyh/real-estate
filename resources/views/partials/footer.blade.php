<footer class="bg-slate-900 text-white pt-16 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                        <i class="fa-solid fa-house text-sm"></i>
                    </div>
                    <span class="text-xl font-bold">RealEstateKu</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                    Platform properti terpercaya untuk menemukan hunian impian keluarga Anda dengan proses KPR yang transparan dan mudah.
                </p>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">Navigasi</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ url('/') }}" class="hover:text-blue-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('catalog') }}" class="hover:text-blue-400 transition-colors">Katalog Unit</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Simulasi KPR</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">Kontak</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><i class="fa-solid fa-phone mr-2"></i> +62 812 3456 7890</li>
                    <li><i class="fa-solid fa-envelope mr-2"></i> info@realestateku.com</li>
                    <li><i class="fa-solid fa-map-pin mr-2"></i> Jakarta Selatan, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} RealEstateKu System. All rights reserved.
        </div>
    </div>
</footer>