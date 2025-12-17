<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\HousingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'block_number' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'land_area' => 'required|numeric',
            'building_area' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
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
            'block_number' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'land_area' => 'required|numeric',
            'building_area' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:available,booked,sold',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($unit->image) Storage::disk('public')->delete($unit->image);
            $data['image'] = $request->file('image')->store('units', 'public');
        }

        $unit->update($data);

        return back()->with('success', 'Data unit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        if ($unit->image) Storage::disk('public')->delete($unit->image);
        $unit->delete();

        return back()->with('success', 'Unit berhasil dihapus.');
    }
}