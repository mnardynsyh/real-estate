<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar customer
     */
    public function index(Request $request)
    {
    
        $query = User::with(['customer', 'transactions'])
                     ->where('role', 'customer');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($subQ) use ($search) {
                      $subQ->where('phone', 'like', "%{$search}%")
                           ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customer', compact('customers'));
    }

    public function destroy($id)
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        
        $user->delete();

        return back()->with('success', 'Data customer berhasil dihapus dari sistem.');
    }
}