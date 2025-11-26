<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingLocation; // Pastikan Model Sesuai
use Illuminate\Http\Request;

class HousingController extends Controller
{
    public function index(Request $request)
    {
        $query = HousingLocation::query();

        // Ambil data (Paginate 10 per halaman)
        $housings = $query->latest()->paginate(10);

        return view('admin.housing', compact('housings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        HousingLocation::create($request->all());

        return back()->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $housing = HousingLocation::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $housing->update($request->all());

        return back()->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        HousingLocation::findOrFail($id)->delete();
        return back()->with('success', 'Lokasi berhasil dihapus.');
    }
}