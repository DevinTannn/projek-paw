<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    CustomerMenuController,
    CartController,
    TransaksiController,
    PanggilanController
};
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminMenuController,
    AdminKaryawanController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Pelanggan
Route::get('/', [CustomerMenuController::class, 'index'])->name('customer.menu.index');
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/update', [CartController::class, 'update']);
Route::post('/keranjang/pesan', [CartController::class, 'store'])->name('cart.store');
Route::get('/struk/{id}', [CustomerMenuController::class, 'struk'])->name('customer.struk');

Route::get('/meja/{nomor_meja}', function ($nomor_meja) {
    session(['table_number' => $nomor_meja]);
    return redirect('/')->with('success', 'Selamat datang di Meja ' . $nomor_meja);
});

Route::post('/panggil-pelayan', [PanggilanController::class, 'store'])->name('panggil.pelayan');

// 2. Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Grup Kasir
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->name('kasir.')->group(function () {
    Route::get('/dashboard', [TransaksiController::class, 'index'])->name('index');
    Route::get('/main-dashboard', [TransaksiController::class, 'index'])->name('dashboard');
    Route::get('/transaksi/pending', [TransaksiController::class, 'daftarPending'])->name('transaksi.pending');
    Route::get('/transaksi/buat', [TransaksiController::class, 'create'])->name('transaksi.buat');
    Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{id}/batal', [TransaksiController::class, 'batal'])->name('transaksi.batal');
    Route::get('/transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');
    Route::post('/transaksi/{id}/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
    
    // Rute Panggilan Pelayan (PENTING)
    Route::get('/panggilan', [PanggilanController::class, 'index'])->name('panggilan.index');
    Route::get('/panggilan/list', [PanggilanController::class, 'getList'])->name('panggilan.list');
    Route::post('/panggilan/{id}/selesai', [PanggilanController::class, 'selesai'])->name('panggilan.selesai');
    
    Route::get('/struk/{id}', [TransaksiController::class, 'struk'])->name('struk');
});

// 4. Grup Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('karyawan', AdminKaryawanController::class);
    Route::resource('menus', AdminMenuController::class);
    Route::get('/transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');
    Route::get('/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{id}', [TransaksiController::class, 'update'])->name('transaksi.update'); 
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy'); 
    Route::get('/rekap-penjualan/download-pdf', [TransaksiController::class, 'downloadPdf'])->name('rekap.pdf');
});