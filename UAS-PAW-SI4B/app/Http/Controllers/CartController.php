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
        // Ambil cart dari session, defaultnya kosong
        $cart = session()->get('cart', []);
        
        // Ambil data menu berdasarkan ID yang ada di session
        $menus = Menu::whereIn('id', array_keys($cart))->get();
        
        return view('customers.cart', compact('cart', 'menus'));
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        // Jika sudah ada, tambah 1. Jika belum, set jadi 1
        if(isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        session()->put('cart', $cart);
        
        // FIXED FOR REAL-TIME BADGE: Mengembalikan data JSON jumlah total item 
        // agar dibaca oleh fungsi JavaScript AJAX di frontend tanpa refresh halaman
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
        // 1. Validasi: table_number sekarang opsional (nullable) 
        // karena kita mewajibkan customer_name sebagai alternatif
        $request->validate([
            'table_number'  => 'nullable|numeric',
            'customer_name' => 'required|string|max:255',
            'metode_bayar'  => 'required|string',
            'catatan'       => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return back()->with('error', 'Keranjang kosong!');

        DB::transaction(function () use ($cart, $request) {
            $totalHarga = 0;
            foreach ($cart as $id => $qty) {
                $menu = Menu::find($id);
                if ($menu) $totalHarga += ($menu->price * $qty);
            }

            // 2. Simpan Transaksi dengan menambahkan customer_name
            $transaksi = Transaksi::create([
                'kasir_id'       => null, 
                'table_number'   => $request->table_number, 
                'customer_name'  => $request->customer_name, // <-- TAMBAHKAN INI
                'kode_transaksi' => Transaksi::generateKode(),
                'total_harga'    => $totalHarga,
                'total_bayar'    => $totalHarga, 
                'kembalian'      => 0,
                'metode_bayar'   => $request->metode_bayar,
                'status'         => 'pending',
                'catatan'        => $request->catatan,
            ]);

            // 3. Simpan Detail Transaksi
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

        session()->forget('cart');
        return redirect('/')->with('success', 'Pesanan berhasil dikirim ke kasir!');
    }
}