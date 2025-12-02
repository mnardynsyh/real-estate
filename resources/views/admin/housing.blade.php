@extends('layouts.admin')
@section('title', 'Data Lokasi Perumahan')

@section('content')
<div x-data="{ 
    activeModal: null, 
    isEdit: false,
    
    // Data Form
    formId: null,
    formName: '',
    formCity: '',
    formAddress: '',
    
    // Reset Form
    resetForm() {
        this.isEdit = false;
        this.formId = null;
        this.formName = '';
        this.formCity = '';
        this.formAddress = '';
    },

    // Buka Modal Tambah/Edit
    openFormModal(editMode = false, data = null) {
        this.resetForm();
        this.activeModal = 'form';
        this.isEdit = editMode;
        
        if (editMode && data) {
            this.formId = data.id;
            this.formName = data.name;
            this.formCity = data.city;
            this.formAddress = data.address;
        }
    },

    // Buka Modal Hapus
    openDeleteModal(id, name) {
        this.activeModal = 'delete';
        this.formId = id;
        this.formName = name;
    },

    closeModal() {
        this.activeModal = null;
        setTimeout(() => {
            this.resetForm();
        }, 300);
    }
}" class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Data Lokasi
            </h1>
            <p class="text-slate-600 mt-1 text-sm font-medium">
                Kelola daftar proyek master data perumahan Anda.
            </p>
        </div>
        
        {{-- Tombol Tambah --}}
        <button @click="openFormModal(false)" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 active:scale-95">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Lokasi</span>
        </button>
    </div>

    {{-- alert --}}
    <div class="flex flex-col gap-4 mb-8">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms 
                 class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Berhasil!</h4>
                        <p class="text-xs opacity-90">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
        
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" 
                 class="p-4 rounded-xl bg-red-50 border border-red-100 text-red-800 flex items-center justify-between shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Terdapat Kesalahan Input</h4>
                        <ul class="list-disc pl-4 text-xs opacity-90 mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- table --}}
    <div class="flex-1 flex flex-col">
        
        <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-6 py-4 font-bold">Nama Lokasi</th>
                        <th class="px-6 py-4 font-bold">Kota / Wilayah</th>
                        <th class="px-6 py-4 font-bold">Alamat Lengkap</th>
                        <th class="px-6 py-4 font-bold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($housings as $i => $housing)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">
                                {{ $housings->firstItem() + $i }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 border border-blue-100">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">{{ $housing->name }}</p>
                                        <p class="text-xs text-slate-400 font-mono mt-0.5">ID: #{{ $housing->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600">
                                    <i class="fa-solid fa-city text-slate-400"></i>
                                    {{ $housing->city ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-slate-600 line-clamp-1 max-w-xs">{{ $housing->address }}</p>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openFormModal(true, {{ $housing }})" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" 
                                       title="Edit Data">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>

                                    <button @click="openDeleteModal({{ $housing->id }}, '{{ $housing->name }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all shadow-sm" 
                                            title="Hapus Data">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                        <i class="fa-solid fa-map-location text-3xl text-slate-300"></i>
                                    </div>
                                    <h3 class="text-slate-800 font-bold text-base">Belum ada data lokasi</h3>
                                    <p class="text-slate-500 text-xs mt-1 max-w-xs">Mulai tambahkan lokasi proyek perumahan baru.</p>
                                    <button @click="openFormModal(false)" class="mt-4 text-blue-600 hover:text-blue-700 text-sm font-bold hover:underline">
                                        Tambah Lokasi Baru
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD VIEW --}}
        <div class="lg:hidden space-y-4 mb-6">
            @forelse($housings as $housing)
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                    {{-- Stripe Biru --}}
                    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500 group-hover:w-1.5 transition-all"></div>
                    
                    <div class="pl-3">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg border border-blue-100">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 line-clamp-1">{{ $housing->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                        <i class="fa-solid fa-city text-[10px]"></i> {{ $housing->city }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-4">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Alamat:</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $housing->address }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openFormModal(true, {{ $housing }})" class="flex items-center justify-center py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                                <i class="fa-solid fa-pen mr-2"></i> Edit
                            </button>
                            
                            <button @click="openDeleteModal({{ $housing->id }}, '{{ $housing->name }}')" class="flex items-center justify-center py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                                <i class="fa-solid fa-trash mr-2"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
                    <p class="text-sm font-medium text-slate-500">Belum ada data lokasi.</p>
                </div>
            @endforelse
        </div>

        {{-- paginasi --}}
        @if($housings->hasPages())
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-3 flex items-center justify-between sm:px-6 mt-auto">
                {{ $housings->links() }}
            </div>
        @endif
    </div>

    {{-- modal tambah/edit --}}
    <div x-show="activeModal === 'form'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>

        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Box --}}
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transform transition-all overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="px-6 pt-6 pb-4 flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-slate-900" x-text="isEdit ? 'Edit Lokasi' : 'Tambah Lokasi'"></h3>
                    <p class="text-xs text-slate-500 mt-1">Lengkapi informasi detail lokasi proyek perumahan.</p>
                </div>
                <button @click="closeModal()" class="rounded-full p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Form Body --}}
            <form method="POST" :action="isEdit ? '{{ url('admin/housing') }}/' + formId : '{{ route('admin.housing.store') }}'" class="px-6 pb-6">
                @csrf
                <template x-if="isEdit">
                    @method('PUT')
                </template>

                <div class="space-y-5">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Perumahan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-house-chimney text-slate-400"></i>
                            </div>
                            <input type="text" name="name" x-model="formName" required placeholder="Contoh: Grand Wisata Bekasi"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kota / Kabupaten</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                            </div>
                            <input type="text" name="city" x-model="formCity" required placeholder="Contoh: Bekasi Selatan"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fa-solid fa-location-dot text-slate-400"></i>
                            </div>
                            <textarea name="address" x-model="formAddress" rows="3" required placeholder="Jalan Raya No. 12, RT 01/RW 02..."
                                      class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 font-medium leading-relaxed"></textarea>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="closeModal()" 
                        class="w-full py-2.5 rounded-lg border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                        class="w-full py-2.5 rounded-lg bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5">
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Data'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- modal hapus--}}
    <div x-show="activeModal === 'delete'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>

        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="text-center">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
                    <i class="fa-solid fa-trash-can text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Lokasi?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                    Anda akan menghapus data <b class="text-slate-800" x-text="formName"></b>. <br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="closeModal()" 
                    class="w-full rounded-lg border border-slate-200 bg-white py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </button>

                <form :action="'{{ url('admin/housing') }}/' + formId" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition-colors shadow-lg shadow-red-200">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection