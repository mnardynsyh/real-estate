<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
 
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole();
        }
        return view('auth.register');
    }

    // Proses Registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        try {
            DB::transaction(function () use ($request) {

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'customer',
                ]);

                Customer::create([
                    'user_id' => $user->id,
                    'phone' => $request->phone,
                ]);

                Auth::login($user);
            });

            return redirect()->route('customer.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['email' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        }
    }

    // Helper: Logika Redirect
    protected function redirectUserBasedOnRole()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        if (Auth::user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        return redirect('/');
    }
}