<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\HousingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('location');


        // Filter Dropdown Lokasi
        if ($request->has('location_id') && $request->location_id != '') {
            $query->where('housing_location_id', $request->location_id);
        }

        $units = $query->latest()->paginate(10);
        
        $housings = HousingLocation::all();

        return view('admin.unit', compact('units', 'housings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'housing_location_id' => 'required|exists:housing_locations,id',
            'housing_location_id' => 'required|exists:housing_locations,id',
            'block_number' => [
                'required',
                'string',
                Rule::unique('units')->where(function ($query) use ($request) {
                    return $query->where('housing_location_id', $request->housing_location_id);
                })
            ],
            'type' => 'required|string',
            'price' => 'required|numeric',
            'land_area' => 'required|numeric',
            'building_area' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        // Upload Gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('units', 'public');
        }

        // Default status
        $data['status'] = 'available';

        Unit::create($data);

        return back()->with('success', 'Unit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $data = $request->validate([
            'housing_location_id' => 'required|exists:housing_locations,id',
            'block_number' => [
                'required',
                'string',
                Rule::unique('units')->where(function ($query) use ($request) {
                    return $query->where('housing_location_id', $request->housing_location_id);
                })
            ],
            'type' => 'required|string',
            'price' => 'required|numeric',
            'land_area' => 'required|numeric',
            'building_area' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:available,booked,sold',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $oldImage = $unit->image; // Simpan path lama
        $data = $request->only([
            'housing_location_id', 'block_number', 'type', 
            'price', 'land_area', 'building_area', 'description', 'status'
        ]);

            // Proses Upload Baru
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('units', 'public');
            }

            // Update Database
            $unit->update($data);

            // Hapus file lama SETELAH update DB sukses & jika ada gambar baru
            if ($request->hasFile('image') && $oldImage) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldImage)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
                }
            }

            return back()->with('success', 'Data unit berhasil diperbarui.');

    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        // Cek apakah unit memiliki transaksi
        if ($unit->transactions()->exists()) {
            return back()->with('error', 'Gagal hapus! Unit ini memiliki riwayat transaksi.');
        }

        // Hapus gambar jika ada
        if ($unit->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($unit->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($unit->image);
        }

        $unit->delete();

        return back()->with('success', 'Unit berhasil dihapus.');
    }
}