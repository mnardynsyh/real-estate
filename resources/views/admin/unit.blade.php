@extends('layouts.admin')
@section('title', 'Data Unit Rumah')

@section('content')
<div x-data="{ 
    activeModal: null, 
    isEdit: false,
    
    // Data Form
    formId: null,
    formLocationId: '',
    formBlock: '',
    formType: '',
    formPrice: '',
    formLand: '',
    formBuilding: '',
    formDesc: '', // Field Deskripsi
    formStatus: 'available',
    formImageOld: null,
    
    // Reset Form
    resetForm() {
        this.isEdit = false;
        this.formId = null;
        this.formLocationId = '';
        this.formBlock = '';
        this.formType = '';
        this.formPrice = '';
        this.formLand = '';
        this.formBuilding = '';
        this.formDesc = '';
        this.formStatus = 'available';
        this.formImageOld = null;
        if(document.getElementById('fileInput')) {
            document.getElementById('fileInput').value = ''; 
        }
    },

    // Buka Modal Tambah/Edit
    openFormModal(editMode = false, data = null) {
        this.resetForm();
        this.activeModal = 'form';
        this.isEdit = editMode;
        
        if (editMode && data) {
            this.formId = data.id;
            this.formLocationId = data.housing_location_id;
            this.formBlock = data.block_number;
            this.formType = data.type;
            this.formPrice = data.price;
            this.formLand = data.land_area;
            this.formBuilding = data.building_area;
            this.formDesc = data.description; // Load deskripsi
            this.formStatus = data.status;
            this.formImageOld = data.image;
        }
    },

    // Buka Modal Hapus
    openDeleteModal(id, block) {
        this.activeModal = 'delete';
        this.formId = id;
        this.formBlock = block;
    },

    closeModal() {
        this.activeModal = null;
        setTimeout(() => {
            this.resetForm();
        }, 300);
    }
}" class="w-full min-h-screen bg-slate-100 px-2 pt-16 lg:px-4 lg:pt-16 flex flex-col font-sans text-slate-800">

    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4 border-b border-slate-400 pb-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Data Unit Rumah
                </h1>
                <p class="text-slate-600 mt-1 text-sm font-medium">
                    Kelola stok unit, harga, dan spesifikasi properti.
                </p>
            </div>
            
            {{-- Tombol Tambah --}}
            <button @click="openFormModal(false)" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 active:scale-95">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Unit</span>
            </button>
        </div>

        {{-- alert--}}
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

        <div class="flex-1 flex flex-col">
            
            {{-- DESKTOP TABLE VIEW --}}
            <div class="hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wide">Unit & Lokasi</th>
                            <th class="px-6 py-4 font-bold tracking-wide">Spesifikasi</th>
                            <th class="px-6 py-4 font-bold tracking-wide">Harga</th>
                            <th class="px-6 py-4 font-bold tracking-wide text-center">Status</th>
                            <th class="px-6 py-4 font-bold tracking-wide text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($units as $unit)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0 relative">
                                            @if($unit->image)
                                                <img src="{{ Storage::url($unit->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $unit->block_number }}</p>
                                            <div class="flex items-center gap-1 text-xs text-slate-500 mt-0.5">
                                                <i class="fa-solid fa-map-pin text-[10px]"></i>
                                                {{ $unit->location->name ?? 'Tanpa Lokasi' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                            Tipe {{ $unit->type }}
                                        </span>
                                        <p class="text-xs text-slate-500">
                                            LT: <b>{{ $unit->land_area }}</b>m² | LB: <b>{{ $unit->building_area }}</b>m²
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800">Rp {{ number_format($unit->price, 0, ',', '.') }}</p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match($unit->status) {
                                            'available' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'booked' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'sold' => 'bg-slate-800 text-white border-slate-900',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                        $statusLabel = match($unit->status) {
                                            'available' => 'Tersedia',
                                            'booked' => 'Booked',
                                            'sold' => 'Terjual',
                                            default => $unit->status
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openFormModal(true, {{ $unit }})" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm"
                                           title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <button @click="openDeleteModal({{ $unit->id }}, '{{ $unit->block_number }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all shadow-sm"
                                                title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="fa-solid fa-house-chimney text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-base">Belum ada data unit</h3>
                                        <p class="text-slate-500 text-xs mt-1 max-w-xs">Unit yang Anda tambahkan akan muncul di sini.</p>
                                        <button @click="openFormModal(false)" class="mt-4 text-blue-600 hover:text-blue-700 text-sm font-bold hover:underline">
                                            Tambah Unit Baru
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
                @forelse($units as $unit)
                    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                        {{-- Stripe Status --}}
                        @php
                            $stripeColor = match($unit->status) {
                                'available' => 'bg-emerald-500',
                                'booked' => 'bg-amber-500',
                                'sold' => 'bg-slate-800',
                                default => 'bg-gray-300'
                            };
                        @endphp
                        <div class="absolute top-0 left-0 w-1 h-full {{ $stripeColor }}"></div>
                        
                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    {{-- Image --}}
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        @if($unit->image)
                                            <img src="{{ Storage::url($unit->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">{{ $unit->block_number }}</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $unit->location->name ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                {{-- Status Badge --}}
                                @php
                                    $statusLabel = match($unit->status) {
                                        'available' => 'Tersedia',
                                        'booked' => 'Booked',
                                        'sold' => 'Terjual',
                                        default => $unit->status
                                    };
                                    $statusClass = match($unit->status) {
                                        'available' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'booked' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'sold' => 'bg-slate-800 text-white border-slate-900',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="text-[10px] font-bold px-2 py-1 rounded border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="bg-slate-50 p-2 rounded border border-slate-100 text-center">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Harga</p>
                                    <p class="text-xs font-bold text-slate-800">Rp {{ number_format($unit->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-slate-50 p-2 rounded border border-slate-100 text-center">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Tipe</p>
                                    <p class="text-xs font-bold text-slate-800">{{ $unit->type }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button @click="openFormModal(true, {{ $unit }})" class="flex items-center justify-center py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                                    <i class="fa-solid fa-pen mr-2"></i> Edit
                                </button>
                                <button @click="openDeleteModal({{ $unit->id }}, '{{ $unit->block_number }}')" class="flex items-center justify-center py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                                    <i class="fa-solid fa-trash mr-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
                        <p class="text-sm font-medium text-slate-500">Belum ada data unit.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($units->hasPages())
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-3 flex items-center justify-between sm:px-6 mt-auto">
                    {{ $units->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- modal form --}}
    <div x-show="activeModal === 'form'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>

        {{-- Overlay --}}
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        {{-- Modal Box --}}
        <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transform transition-all overflow-hidden flex flex-col max-h-[90vh]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="px-8 pt-8 pb-6 flex justify-between items-start shrink-0 bg-white z-10">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900" x-text="isEdit ? 'Edit Unit Rumah' : 'Tambah Unit Baru'"></h3>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi spesifikasi unit properti.</p>
                </div>
                <button @click="closeModal()" class="rounded-full p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            {{-- form edit --}}
            <div class="px-8 py-2 overflow-y-auto custom-scrollbar">
                <form method="POST" enctype="multipart/form-data" 
                      :action="isEdit ? '{{ url('admin/units') }}/' + formId : '{{ route('admin.units.store') }}'">
                    @csrf
                    <template x-if="isEdit">@method('PUT')</template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6">
                        
                        {{-- 1. Lokasi & Blok --}}
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lokasi Perumahan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                                    </div>
                                    <select name="housing_location_id" x-model="formLocationId" required
                                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium cursor-pointer">
                                        <option value="" disabled>Pilih Lokasi</option>
                                        @foreach($housings as $h)
                                            <option value="{{ $h->id }}">{{ $h->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Blok/Kavling</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fa-solid fa-tag text-slate-400"></i>
                                    </div>
                                    <input type="text" name="block_number" x-model="formBlock" required placeholder="Contoh: A-12"
                                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium">
                                </div>
                            </div>
                        </div>

                        {{-- 2. Harga & Tipe --}}
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga Jual (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-slate-400 font-bold text-xs">Rp</span>
                                </div>
                                <input type="number" name="price" x-model="formPrice" required placeholder="0"
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Rumah</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fa-solid fa-ruler-combined text-slate-400"></i>
                                </div>
                                <input type="text" name="type" x-model="formType" required placeholder="36/72"
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium">
                            </div>
                        </div>

                        {{-- 3. Luas Tanah & Bangunan --}}
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Luas Tanah (m²)</label>
                            <input type="number" name="land_area" x-model="formLand" required placeholder="60"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium">
                        </div>

                        <div class="group">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Luas Bangunan (m²)</label>
                            <input type="number" name="building_area" x-model="formBuilding" required placeholder="36"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium">
                        </div>

                        {{-- 4. Deskripsi --}}
                        <div class="md:col-span-2 group">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Unit</label>
                            <textarea name="description" x-model="formDesc" rows="3" placeholder="Fasilitas, keunggulan, atau catatan tambahan..."
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium leading-relaxed"></textarea>
                        </div>

                        {{-- 5. Status --}}
                        <template x-if="isEdit">
                            <div class="md:col-span-2 group">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Unit</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fa-solid fa-rotate text-slate-400"></i>
                                    </div>
                                    <select name="status" x-model="formStatus" required
                                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold cursor-pointer">
                                        <option value="available">Available (Tersedia)</option>
                                        <option value="booked">Booked (Dipesan)</option>
                                        <option value="sold">Sold (Terjual)</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        {{-- 6. Upload Foto --}}
                        <div class="md:col-span-2 group">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Unit (Opsional)</label>
                            <input type="file" name="image" id="fileInput" accept="image/*"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer border border-slate-200 rounded-lg">
                            <p class="text-xs text-slate-400 mt-2 ml-1">*Maksimal ukuran file 10MB.</p>
                            
                            {{-- Preview Link jika ada --}}
                            <template x-if="isEdit && formImageOld">
                                <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-blue-50 rounded-lg border border-blue-100 w-fit">
                                    <i class="fa-solid fa-image text-blue-500"></i>
                                    <span class="text-xs text-blue-700 font-medium">Foto saat ini tersimpan.</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-2 grid grid-cols-2 gap-4 pt-6 border-t border-slate-100 bg-white">
                        <button type="button" @click="closeModal()" 
                            class="w-full py-2.5 rounded-lg border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                            class="w-full py-2.5 rounded-lg bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal hapus --}}
    <div x-show="activeModal === 'delete'" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition.opacity.duration.300ms>
        
        <div @click="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[3px]"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="text-center">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
                    <i class="fa-solid fa-trash-can text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Unit?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed px-4">
                    Unit Blok <b class="text-slate-800" x-text="formBlock"></b> akan dihapus permanen.
                </p>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="closeModal()" class="w-full rounded-lg border border-slate-200 bg-white py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                <form :action="'{{ url('admin/units') }}/' + formId" method="POST" class="w-full">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-bold text-white hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection