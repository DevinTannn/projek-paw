<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\TransaksiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Menampilkan halaman e-menu pelanggan (Data diambil dari Database lewat Controller)
Route::get('/', [CustomerMenuController::class, 'index'])->name('customer.menu.index');

// Simulasi scan QR Code meja
Route::get('/meja/{nomor_meja}', function ($nomor_meja) {
    session(['table_number' => $nomor_meja]);
    return redirect('/')->with('success', 'Selamat datang! Anda terhubung dari Meja Nomor ' . $nomor_meja);
});

// Grup Rute Dashboard (Admin / Staff)
Route::prefix('dashboard')->group(function () {
    Route::resource('customers', CustomerController::class);
});

Route::prefix('kasir')
    ->middleware(['auth', 'verified'])
    ->name('kasir.')
    ->group(function () {

        // Dashboard kasir
        Route::get('/dashboard', [TransaksiController::class, 'index'])
            ->name('dashboard');

        // Buat transaksi baru
        Route::get('/transaksi/buat', [TransaksiController::class, 'create'])
            ->name('transaksi.buat');

        // Simpan transaksi
        Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])
            ->name('transaksi.simpan');

        // Detail transaksi
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])
            ->name('transaksi.show');

        // Batalkan transaksi
        Route::post('/transaksi/{id}/batal', [TransaksiController::class, 'batal'])
            ->name('transaksi.batal');

        // Cetak struk
        Route::get('/struk/{id}', [TransaksiController::class, 'struk'])
            ->name('struk');
    });
