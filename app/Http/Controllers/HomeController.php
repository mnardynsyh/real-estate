<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\HousingLocation;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $featuredUnits = Unit::with('location')
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        $locations = HousingLocation::all();

        return view('welcome', compact('featuredUnits', 'locations'));
    }

    /**
     * Katalog: Daftar Semua Unit + Filter
     */
    public function catalog(Request $request)
    {
        $query = Unit::with('location');

        // 1. Filter Lokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('housing_location_id', $request->location);
        }

        // 2. Filter Tipe Rumah
        if ($request->has('type') && $request->type != '') {
            $query->where('type', 'like', "%{$request->type}%");
        }

        // 3. Filter Range Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $units = $query->latest()->paginate(9)->withQueryString();
        $locations = HousingLocation::all();

        return view('katalog', compact('units', 'locations'));
    }

    /**
     * Detail Unit
     */
    public function show($id)
    {
        $unit = Unit::with('location')->findOrFail($id);
        
        // Ambil unit lain di lokasi yang sama (Rekomendasi)
        $relatedUnits = Unit::where('housing_location_id', $unit->housing_location_id)
            ->where('id', '!=', $unit->id)
            ->take(3)
            ->get();

        return view('detail', compact('unit', 'relatedUnits'));
    }
}
