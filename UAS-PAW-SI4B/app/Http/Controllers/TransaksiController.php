<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Category;        // ← Model milik teman, JANGAN diubah
use App\Models\DetailTransaksi;
use App\Models\Menu;             // ← Model milik teman, JANGAN diubah
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ── GET /kasir/dashboard ──────────────────────────────────
    public function index()
    {
        $transaksiHariIni = Transaksi::with('details')
            ->whereDate('created_at', today())
            ->where('status', 'selesai')
            ->orderByDesc('created_at')
            ->get();

        $totalPendapatan  = $transaksiHariIni->sum('total_harga');
        $totalTransaksi   = $transaksiHariIni->count();
        $transaksiPending = Transaksi::where('status', 'pending')
                                     ->whereDate('created_at', today())
                                     ->count();

        return view('kasir.dashboard', compact(
            'transaksiHariIni',
            'totalPendapatan',
            'totalTransaksi',
            'transaksiPending'
        ));
    }

    // ── GET /kasir/transaksi/buat ─────────────────────────────
    public function create()
    {
        // Ambil kategori + menu dari tabel teman (categories & menus)
        // Kolom menu: name, price, image_url, description, is_recommended, is_bestseller
        $categories = Category::with('menus')->get();

        return view('kasir.transaksi.create', compact('categories'));
    }

    // ── POST /kasir/transaksi/simpan ──────────────────────────
    public function store(StoreTransaksiRequest $request)
    {
        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $itemsData  = [];

            foreach ($request->items as $item) {
                $menu      = Menu::findOrFail($item['menu_id']);
                $subtotal  = $menu->price * $item['qty'];   // kolom teman: price
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'menu_id'      => $menu->id,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $menu->price,          // kolom teman: price
                    'subtotal'     => $subtotal,
                    'catatan_item' => $item['catatan_item'] ?? null,
                ];
            }

            $kembalian = $request->total_bayar - $totalHarga;
            if ($kembalian < 0) {
                return back()
                    ->withErrors(['total_bayar' => 'Jumlah bayar kurang dari total harga.'])
                    ->withInput();
            }

            $transaksi = Transaksi::create([
                'kasir_id'       => Auth::id(),
                'kode_transaksi' => Transaksi::generateKode(),
                'total_harga'    => $totalHarga,
                'total_bayar'    => $request->total_bayar,
                'kembalian'      => $kembalian,
                'metode_bayar'   => $request->metode_bayar,
                'status'         => 'selesai',
                'catatan'        => $request->catatan,
            ]);

            foreach ($itemsData as &$d) {
                $d['transaksi_id'] = $transaksi->id;
            }
            DetailTransaksi::insert($itemsData);

            DB::commit();
            return redirect()
                ->route('kasir.struk', $transaksi->id)
                ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // ── GET /kasir/transaksi/{id} ─────────────────────────────
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['details.menu', 'kasir'])->findOrFail($id);
        return view('kasir.transaksi.show', compact('transaksi'));
    }

    // ── GET /kasir/struk/{id} ─────────────────────────────────
    public function struk(string $id)
    {
        $transaksi = Transaksi::with(['details.menu', 'kasir'])->findOrFail($id);
        return view('kasir.transaksi.struk', compact('transaksi'));
    }

    // ── POST /kasir/transaksi/{id}/batal ─────────────────────
    public function batal(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->status === 'selesai') {
            return back()->withErrors(['error' => 'Transaksi yang sudah selesai tidak bisa dibatalkan.']);
        }
        $transaksi->update(['status' => 'batal']);
        return redirect()->route('kasir.dashboard')->with('success', 'Transaksi berhasil dibatalkan.');
    }
}
