<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminKaryawanController; 
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman E-Menu Pelanggan
Route::get('/', [CustomerMenuController::class, 'index'])->name('customer.menu.index');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/update', [CartController::class, 'update']);
// Tambahkan di bawah rute keranjang yang sudah ada
Route::post('/keranjang/pesan', [CartController::class, 'store'])->name('cart.store');

// Simulasi scan QR
Route::get('/meja/{nomor_meja}', function ($nomor_meja) {
    session(['table_number' => $nomor_meja]);
    return redirect('/')->with('success', 'Selamat datang di Meja ' . $nomor_meja);
});

// 2. Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. --- GRUP RUANGAN KASIR ---
Route::prefix('kasir')
    ->middleware(['auth', 'role:kasir'])
    ->name('kasir.') // Semua rute di dalam grup ini otomatis diawali 'kasir.'
    ->group(function () {
        
        // FIX AMAN: Mendaftarkan rute untuk 'kasir.index' DAN 'kasir.dashboard' sekaligus 
        // agar tidak terjadi bentrok di file view/sidebar/controller Anda.
        Route::get('/dashboard', [TransaksiController::class, 'index'])->name('index');
        Route::get('/main-dashboard', [TransaksiController::class, 'index'])->name('dashboard');
        
        // Rute Transaksi
        Route::get('/transaksi/buat', [TransaksiController::class, 'create'])->name('transaksi.buat');
        Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::post('/transaksi/{id}/batal', [TransaksiController::class, 'batal'])->name('transaksi.batal');
        
        Route::get('/transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');
        
        // URL & Nama rute disebelah sini sudah bersih dari double prefix 'kasir'
        Route::post('/transaksi/{id}/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
        
        // Rute Struk
        Route::get('/struk/{id}', [TransaksiController::class, 'struk'])->name('struk');
    });

// 4. --- GRUP RUANGAN ADMIN ---
Route::prefix('admin')
    ->middleware(['auth', 'role:admin']) 
    ->name('admin.')
    ->group(function () {
        
        // Dashboard Utama Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD Manajemen Menu
        Route::resource('menus', AdminMenuController::class);
        
        // Manajemen Karyawan
        Route::resource('karyawan', AdminKaryawanController::class);
        
        Route::get('/transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');

        Route::get('/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/transaksi/{id}/update', [TransaksiController::class, 'update'])->name('transaksi.update');
    });