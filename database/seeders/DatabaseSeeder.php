<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\HousingLocation;
use App\Models\Unit;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT AKUN ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password login: password
            'role' => 'admin',
        ]);

        // 2. BUAT AKUN CUSTOMER + DATA PROFIL
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // password login: password
            'role' => 'customer',
        ]);

        // Penting: Buat data di tabel customers untuk user ini
        Customer::create([
            'user_id' => $user->id,
            'nik' => '3201234567890001',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Jakarta Selatan',
            'job' => 'Karyawan Swasta',
        ]);

        // 3. BUAT LOKASI PERUMAHAN
        $loc1 = HousingLocation::create([
            'name' => 'Grand Wisata Bekasi',
            'city' => 'Bekasi',
            'address' => 'Jl. Raya Mustika Jaya, Lambangjaya, Kec. Tambun Sel.',
        ]);

        $loc2 = HousingLocation::create([
            'name' => 'Citra Maja Raya',
            'city' => 'Lebak',
            'address' => 'Kecamatan Maja, Kabupaten Lebak, Banten',
        ]);

        // 4. BUAT UNIT RUMAH
        // Unit 1: Available
        Unit::create([
            'housing_location_id' => $loc1->id,
            'block_number' => 'A-01',
            'type' => '36/60',
            'price' => 350000000,
            'land_area' => 60,
            'building_area' => 36,
            'description' => 'Posisi hook, dekat taman.',
            'status' => 'available',
        ]);

        // Unit 2: Booked (Sedang diproses oleh Budi)
        $unitBooked = Unit::create([
            'housing_location_id' => $loc1->id,
            'block_number' => 'B-10',
            'type' => '45/72',
            'price' => 450000000,
            'land_area' => 72,
            'building_area' => 45,
            'description' => 'Menghadap timur.',
            'status' => 'booked', // Status unit jadi booked
        ]);

        // Unit 3: Sold
        Unit::create([
            'housing_location_id' => $loc2->id,
            'block_number' => 'C-05',
            'type' => '36/60',
            'price' => 180000000,
            'land_area' => 60,
            'building_area' => 36,
            'status' => 'sold',
        ]);

        // 5. BUAT TRANSAKSI DUMMY (Untuk User Budi)
        // Agar dashboard customer tidak kosong
        Transaction::create([
            'code' => 'TRX-' . rand(1000, 9999),
            'user_id' => $user->id,
            'unit_id' => $unitBooked->id,
            'booking_fee' => 1000000,
            'status' => 'process', // Status: Menunggu Verifikasi Admin
            // 'booking_proof' => 'path/to/dummy.jpg', // Opsional
        ]);
    }
}