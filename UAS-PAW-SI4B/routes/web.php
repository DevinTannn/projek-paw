<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\CustomerMenuController;

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