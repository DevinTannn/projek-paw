<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $menus = Menu::whereIn('id', array_keys($cart))->get();
        return view('customers.cart', compact('cart', 'menus'));
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if(isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        session()->put('cart', $cart);
        
        $totalItems = array_sum($cart);
        return response()->json([
            'success' => true,
            'message' => 'Menu ditambahkan!',
            'totalItems' => $totalItems
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
        $qty = $request->qty;

        if ($qty <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $qty;
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'table_number'  => 'required|numeric',
            'customer_name' => 'required|string|max:255',
            'metode_bayar'  => 'required|string',
            'catatan'       => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return back()->with('error', 'Keranjang kosong!');

        $transaksi = null; // Variabel untuk menyimpan objek transaksi

        // 2. Simpan ke database menggunakan Transaction
        DB::transaction(function () use ($cart, $request, &$transaksi) {
            $totalHarga = 0;
            foreach ($cart as $id => $qty) {
                $menu = Menu::find($id);
                if ($menu) $totalHarga += ($menu->price * $qty);
            }

            // Simpan Transaksi
            $transaksi = Transaksi::create([
                'kasir_id'       => null, 
                'table_number'   => $request->table_number, 
                'customer_name'  => $request->customer_name,
                'kode_transaksi' => Transaksi::generateKode(),
                'total_harga'    => $totalHarga,
                'total_bayar'    => $totalHarga, 
                'kembalian'      => 0,
                'metode_bayar'   => $request->metode_bayar,
                'status'         => 'pending',
                'catatan'        => $request->catatan,
            ]);

            // Simpan Detail Transaksi
            foreach ($cart as $id => $qty) {
                $menu = Menu::find($id);
                if ($menu) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'menu_id'      => $id,
                        'qty'          => $qty,
                        'harga_satuan' => $menu->price,
                        'subtotal'     => ($qty * $menu->price),
                    ]);
                }
            }
        });

        // 3. Bersihkan cart dan redirect ke struk
        session()->forget('cart');

        return redirect()->route('customer.struk', ['id' => $transaksi->id])
                         ->with('success', 'Pesanan berhasil dikirim!');
    }
}