<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'job' => 'required|string|max:100',
            'address' => 'required|string|max:500',
        ]);

        $userData = ['name' => $request->name];
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);

        Customer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'nik' => $request->nik,
                'job' => $request->job,
                'address' => $request->address,
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}