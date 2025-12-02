<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole();
        }
        return view('auth.login');
    }


    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate(); // Mencegah Session Fixation

            //Redirect Sesuai Role
            return $this->redirectUserBasedOnRole();
        }

        // Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
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

        // Default fallback
        return redirect('/');
    }
}