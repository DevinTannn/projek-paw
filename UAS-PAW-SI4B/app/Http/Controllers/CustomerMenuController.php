<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class CustomerMenuController extends Controller
{
    /**
     * Menampilkan halaman e-menu untuk dibaca dan dipesan oleh pelanggan.
     */
    public function index()
    {
        // Mengambil semua data kategori beserta menu yang ada di dalamnya
        $categories = Category::with('menus')->get();

        // Mengirimkan variabel $categories ke file view Blade
        return view('customers.index', compact('categories'));
    }

    public function struk($id)
{
    $transaksi = \App\Models\Transaksi::with('detailTransaksi.menu')->findOrFail($id);
    
    // Jika ada request ?download=true, maka buat PDF
    if (request()->has('download')) {
        $pdf = Pdf::loadView('customers.struk', compact('transaksi'));
        return $pdf->download('Struk_Pesanan_' . $transaksi->kode_transaksi . '.pdf');
    }

    // Jika tidak, tampilkan view biasa
    return view('customers.struk', compact('transaksi'));
}
}