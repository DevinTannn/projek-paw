<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Admin\AdminDashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman E-Menu Pelanggan (Terbuka untuk Umum)
Route::get('/', [CustomerMenuController::class, 'index'])->name('customer.menu.index');

// Simulasi scan QR Code meja
Route::get('/meja/{nomor_meja}', function ($nomor_meja) {
    session(['table_number' => $nomor_meja]);
    return redirect('/')->with('success', 'Selamat datang! Anda terhubung dari Meja Nomor ' . $nomor_meja);
});

// Grup Rute Pelanggan Lama (Bawaan awal)
Route::prefix('dashboard')->group(function () {
    Route::resource('customers', CustomerController::class);
});


// 2. Rute Autentikasi Pegawai (Login & Logout - Harus di luar grup auth biar bisa diakses)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 3. --- GRUP RUANGAN KASIR (Hanya Kasir yang Bisa Masuk) ---
Route::prefix('kasir')
    ->middleware(['auth', 'role:kasir']) 
    ->name('kasir.')
    ->group(function () {
        
        // Dashboard utama kasir
        Route::get('/dashboard', [TransaksiController::class, 'index'])->name('dashboard');

        // Buat transaksi baru
        Route::get('/transaksi/buat', [TransaksiController::class, 'create'])->name('transaksi.buat');

        // Simpan transaksi
        Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])->name('transaksi.simpan');

        // Detail transaksi
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');

        // Batalkan transaksi
        Route::post('/transaksi/{id}/batal', [TransaksiController::class, 'batal'])->name('transaksi.batal');

        // Cetak struk
        Route::get('/struk/{id}', [TransaksiController::class, 'struk'])->name('struk');
        
    });


// 4. --- GRUP RUANGAN ADMIN (Hanya Admin yang Bisa Masuk) ---
Route::prefix('admin')
    ->middleware(['auth', 'role:admin']) 
    ->name('admin.')
    ->group(function () {
        
        // Dashboard Utama Admin (Tempat jatah tugasmu)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Nanti kalau mau bikin CRUD menu/karyawan, tambahkan rutenya di bawah sini ya!
        
    });