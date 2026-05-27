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
        // Validasi input data yang dikirim dari modal detail pesanan pelanggan
        $request->validate([
            'table_number' => 'required|numeric',
            'metode_bayar' => 'required|string',
            'catatan'      => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return back()->with('error', 'Keranjang kosong!');

        // Membungkus proses simpan dengan DB Transaction agar aman jika terjadi error di tengah jalan
        DB::transaction(function () use ($cart, $request) {
            // 1. Hitung Total Harga
            $totalHarga = 0;
            foreach ($cart as $id => $qty) {
                $menu = Menu::find($id);
                if ($menu) $totalHarga += ($menu->price * $qty);
            }

            // 2. Simpan Transaksi dengan menangkap data request dari modal pelanggan
            $transaksi = Transaksi::create([
                'kasir_id'       => null, // Null karena pesanan datang mandiri dari pelanggan via web
                'table_number'   => $request->table_number, // FIXED: Menyimpan nomor meja dari modal
                'kode_transaksi' => Transaksi::generateKode(), // Generate otomatis kode unik transaksi
                'total_harga'    => $totalHarga,
                'total_bayar'    => $totalHarga, 
                'kembalian'      => 0,
                'metode_bayar'   => $request->metode_bayar, // FIXED: Menyimpan metode bayar (tunai/qris) dari modal
                'status'         => 'pending', // Masuk ke status pending supaya nampil di dashboard kasir
                'catatan'        => $request->catatan, // FIXED: Menyimpan catatan dari modal pelanggan
            ]);

            // 3. Simpan Detail Transaksi untuk setiap item menu di keranjang
            foreach ($cart as $id => $qty) {
                $menu = Menu::find($id);
                if ($menu) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'menu_id'      => $id,
                        'qty'          => $qty,
                        'harga_satuan' => $menu->price,
                        'subtotal'     => ($qty * $menu->price),
                        'catatan_item' => null,
                    ]);
                }
            }
        });

        // Hapus data keranjang dari session setelah pesanan sukses disimpan
        session()->forget('cart');
        
        return redirect('/')->with('success', 'Pesanan berhasil dikirim ke kasir!');
    }
}