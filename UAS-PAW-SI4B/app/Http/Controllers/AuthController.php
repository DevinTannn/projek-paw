<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Memproses Data Login
    public function login(Request $request)
    {
        // Validasi inputan form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Mengecek kecocokan email dan password ke database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Kalau sukses login, kita arahkan ke halaman kasir dulu sementara
            return redirect()->intended('/kasir/dashboard'); 
        }

        // Kalau gagal login (password salah), kembalikan ke halaman login bawa pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah, coba lagi ya!',
        ])->onlyInput('email');
    }

    // 3. Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); // Setelah logout, tendang balik ke halaman menu pelanggan
    }
}