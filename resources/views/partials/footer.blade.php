<footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            {{-- Brand & Description --}}
            <div class="col-span-1 lg:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4 group w-fit">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-900/50">
                        <i class="fa-solid fa-house text-sm"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Terra<span class="text-blue-500">Home</span></span>
                </a>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    Menghubungkan Anda dengan hunian impian melalui proses yang transparan, mudah, dan terpercaya.
                </p>
                
                {{-- Social Media Icons (Dark Mode) --}}
                <div class="flex gap-3 mt-6">
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:bg-blue-600 hover:text-white transition-all border border-slate-700 hover:border-blue-600">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:bg-blue-600 hover:text-white transition-all border border-slate-700 hover:border-blue-600">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:bg-blue-600 hover:text-white transition-all border border-slate-700 hover:border-blue-600">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h4 class="font-bold text-white mb-4">Navigasi</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-blue-400 transition-colors flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-slate-600 group-hover:bg-blue-500 transition-colors"></span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('catalog') }}" class="hover:text-blue-400 transition-colors flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-slate-600 group-hover:bg-blue-500 transition-colors"></span>
                            Katalog Unit
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-blue-400 transition-colors flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-slate-600 group-hover:bg-blue-500 transition-colors"></span>
                            Login Member
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="font-bold text-white mb-4">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex items-start gap-3 group cursor-pointer">
                        <div class="mt-1 text-slate-500 group-hover:text-blue-400 transition-colors">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <span class="group-hover:text-white transition-colors">+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-start gap-3 group cursor-pointer">
                        <div class="mt-1 text-slate-500 group-hover:text-blue-400 transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <span class="group-hover:text-white transition-colors">info@realestateku.com</span>
                    </li>
                    <li class="flex items-start gap-3 group">
                        <div class="mt-1 text-slate-500 group-hover:text-blue-400 transition-colors">
                            <i class="fa-solid fa-map-pin"></i>
                        </div>
                        <span class="leading-snug group-hover:text-white transition-colors">Jl. Sudirman No. 45, Jakarta Selatan</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-col md:flex-row items-center gap-2 md:gap-6">
                <p class="text-xs text-slate-500 text-center md:text-left">
                    &copy; {{ date('Y') }} <span class="text-slate-400 font-medium"><a href="https://github.com/mnardynsyh" target="_blank" class="hover:text-white transition-colors">mnardynsyh</a></span>. All rights reserved.
                </p>
            </div>

            <div class="flex gap-6 text-xs text-slate-500 font-medium">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>