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
    // 1. Validasi input yang masuk
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 2. Coba proses login (Si satpam mencocokkan email & password)
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // --- DISINI KUNCI OTOMATISNYA ---
        // Ambil data user yang baru saja sukses login
        $user = Auth::user();

        // Periksa rolenya dan arahkan ke dashboard yang tepat
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        // Jalur alternatif kalau ada role lain yang tidak dikenal
        return redirect('/');
    }

    // 3. Kalau email atau password salah, kembalikan dengan pesan error
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