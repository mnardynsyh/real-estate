@extends('layouts.admin')
@section('title', 'Data Customer')

@section('content')
<div x-data="{ 
    activeModal: null, 
    userId: null,
    userName: '',
    userEmail: '',
    userPhone: '',
    userNik: '',
    userJob: '',
    userAddress: '',
    userJoinDate: '',
    
    openDetailModal(user) {
        this.activeModal = 'detail';
        this.userId = user.id;
        this.userName = user.name;
        this.userEmail = user.email;
        this.userJoinDate = new Date(user.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
        
        if (user.customer) {
            this.userPhone = user.customer.phone || '-';
            this.userNik = user.customer.nik || '-';
            this.userJob = user.customer.job || '-';
            this.userAddress = user.customer.address || '-';
        } else {
            this.userPhone = '-';
            this.userNik = '-';
            this.userJob = '-';
            this.userAddress = '-';
        }
    },

    openDeleteModal(id, name) {
        this.activeModal = 'delete';
        this.userId = id;
        this.userName = name;
    },

    closeModal() {
        this.activeModal = null;
    }
}" class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Data Customer
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Daftar pengguna terdaftar yang berpotensi menjadi pembeli.
                </p>
            </div>
        </div>

        {{-- Alerts --}}
        <div class="flex flex-col gap-4 mb-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms 
                     class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Search --}}
        <div class="shrink-0 mb-6">
            <form action="{{ route('admin.customers.index') }}" method="GET" 
                  class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-3">
                
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:ring-blue-500 text-slate-700 placeholder-slate-400 font-medium" 
                           placeholder="Cari Nama, Email, atau No. WhatsApp...">
                </div>

                <button type="submit" class="hidden sm:block px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all">
                    Cari
                </button>
            </form>
        </div>

        {{-- Table List --}}
        <div class="flex-1 flex flex-col">
            
            {{-- Desktop --}}
            <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                            <th class="px-6 py-4 font-bold">Info Customer</th>
                            <th class="px-6 py-4 font-bold">Kontak & NIK</th>
                            <th class="px-6 py-4 font-bold text-center">Total Transaksi</th>
                            <th class="px-6 py-4 font-bold text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($customers as $i => $customer)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 text-center font-medium text-slate-400">
                                    {{ $customers->firstItem() + $i }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200">
                                            {{ substr($customer->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $customer->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $customer->email }}</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">
                                                Join: {{ $customer->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($customer->customer)
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                                                <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                                                {{ $customer->customer->phone ?? '-' }}
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                                <i class="fa-regular fa-id-card text-slate-400"></i>
                                                {{ $customer->customer->nik ?? 'Belum isi NIK' }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-red-400 italic bg-red-50 px-2 py-1 rounded">Profil belum dilengkapi</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($customer->transactions->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            {{ $customer->transactions->count() }} Unit
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openDetailModal({{ $customer }})" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition-all shadow-sm"
                                                title="Lihat Detail">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                        <button @click="openDeleteModal({{ $customer->id }}, '{{ $customer->name }}')" 
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition-all shadow-sm"
                                                title="Hapus User">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400 opacity-60">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-users text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Belum ada customer</h3>
                                        <p class="text-sm font-medium text-slate-500 mt-1">Data pendaftar akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="lg:hidden space-y-4 mb-6">
                @foreach($customers as $customer)
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                        
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs border border-slate-200">
                                {{ substr($customer->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $customer->name }}</p>
                                <p class="text-xs text-slate-500">{{ $customer->email }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4 text-xs space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500 font-medium">WhatsApp</span>
                                <span class="font-bold text-slate-700">{{ $customer->customer->phone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 font-medium">Status NIK</span>
                                @if($customer->customer && $customer->customer->nik)
                                    <span class="text-emerald-600 font-bold">Terisi</span>
                                @else
                                    <span class="text-red-500 italic">Kosong</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openDetailModal({{ $customer }})" class="py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                                Detail Profil
                            </button>
                            <button @click="openDeleteModal({{ $customer->id }}, '{{ $customer->name }}')" class="py-2.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100 hover:bg-red-100 transition-colors">
                                Hapus User
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($customers->hasPages())
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-3 mt-auto flex items-center justify-between sm:px-6">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Detail --}}
    <div x-show="activeModal === 'detail'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-lg rounded-2xl bg-white p-0 shadow-2xl transform transition-all overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Header Gradient --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white relative">
                <button @click="closeModal()" class="absolute top-4 right-4 text-blue-200 hover:text-white transition-colors rounded-full p-1 hover:bg-white/10">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold border-2 border-white/30 backdrop-blur-sm">
                        <span x-text="userName.substring(0,2).toUpperCase()"></span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold" x-text="userName"></h3>
                        <p class="text-blue-100 text-sm opacity-90" x-text="userEmail"></p>
                    </div>
                </div>
            </div>

            {{-- Body Info --}}
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">WhatsApp</p>
                        <p class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                            <span x-text="userPhone"></span>
                        </p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">NIK (KTP)</p>
                        <p class="text-sm font-semibold text-slate-800" x-text="userNik"></p>
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Pekerjaan</p>
                    <p class="text-sm font-semibold text-slate-800" x-text="userJob"></p>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Alamat Domisili</p>
                    <p class="text-sm font-semibold text-slate-800 leading-relaxed" x-text="userAddress"></p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
                    <span>Terdaftar sejak:</span>
                    <span class="font-bold text-slate-600" x-text="userJoinDate"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div x-show="activeModal === 'delete'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="text-center mb-6">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
                    <i class="fa-solid fa-user-xmark text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Customer?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed px-2">
                    Akun <b x-text="userName" class="text-slate-800"></b> beserta seluruh riwayat transaksi dan dokumennya akan dihapus permanen.
                </p>
            </div>

            <div class="flex gap-3">
                <button @click="closeModal()" class="w-full rounded-xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                
                <form :action="'{{ url('admin/customers') }}/' + userId" method="POST" class="w-full">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full rounded-xl bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:-translate-y-0.5">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection