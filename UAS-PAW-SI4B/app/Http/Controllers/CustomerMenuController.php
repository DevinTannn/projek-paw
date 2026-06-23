<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;

class CustomerMenuController extends Controller
{
    public function index()
    {
        $categories = Category::with('menus')->get();

        // Ambil hanya ID menu yang masuk 5 besar terlaris
        $bestSellerIds = DB::table('detail_transaksis')
            ->select('menu_id', DB::raw('SUM(qty) as total'))
            ->groupBy('menu_id')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('menu_id')
            ->toArray();

        return view('customers.index', compact('categories', 'bestSellerIds'));
    }

    public function struk($id)
    {
        $transaksi = \App\Models\Transaksi::with('detailTransaksi.menu')->findOrFail($id);
        if (request()->has('download')) {
            $pdf = PDF::loadView('customers.pdf', compact('transaksi'));
            return $pdf->download('Struk_Pesanan_' . $transaksi->kode_transaksi . '.pdf');
        }
        return view('customers.struk', compact('transaksi'));
    }
}