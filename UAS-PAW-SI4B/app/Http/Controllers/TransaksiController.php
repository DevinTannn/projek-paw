<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Category;        // ← Model milik teman, JANGAN diubah
use App\Models\DetailTransaksi;
use App\Models\Menu;            // ← Model milik teman, JANGAN diubah
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;    

class TransaksiController extends Controller
{
    // ── GET /kasir/dashboard ──────────────────────────────────
    public function index()
    {
        // Transaksi Selesai (tabel bawah)
        $transaksiHariIni = Transaksi::with('details')
            ->whereDate('created_at', today())
            ->where('status', 'selesai')
            ->orderByDesc('created_at')
            ->get();

        // Statistik
        $totalPendapatan = $transaksiHariIni->sum('total_harga');
        $totalTransaksi = $transaksiHariIni->count();
        
        // Pesanan Pending (Dibatasi 5 untuk Dashboard Utama)
        $pesananPending = Transaksi::with(['details.menu'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(5) 
            ->get();
            
        $transaksiPending = Transaksi::where('status', 'pending')->count();

        return view('kasir.dashboard', compact(
            'transaksiHariIni', 'totalPendapatan', 'totalTransaksi', 'transaksiPending', 'pesananPending'
        ));
    }

    // ── GET /kasir/transaksi/buat ─────────────────────────────
    public function create()
    {
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
                $menu       = Menu::findOrFail($item['menu_id']);
                $subtotal  = $menu->price * $item['qty'];   
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'menu_id'      => $menu->id,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $menu->price,
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

    // ── TAMBAHAN UNTUK HISTORI ───────────────────────────────
    public function history(Request $request)
    {
        // Gunakan Query Builder agar bisa ditambah filter
        $query = Transaksi::with(['details.menu', 'kasir']);

        // Logika Filter Status
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'diubah') {
                $query->where('is_edited', true);
            } else {
                $query->where('status', $request->status);
            }
        }

        $transaksi = $query->orderByDesc('created_at')->get();

        if (Auth::user()->role === 'admin') {
            return view('admin.transaksi.history', compact('transaksi'));
        }
        return view('kasir.transaksi.history', compact('transaksi'));
    }

    // ── EDIT TRANSAKSI (KHUSUS ADMIN) ──────────────────────
    public function edit($id)
    {
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);
        return view('admin.transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'total_harga'  => 'required|numeric',
            'catatan_edit' => 'required|string|max:255'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        
        // Update data
        $transaksi->update([
            'total_harga'  => $request->total_harga,
            'status'       => 'diubah',
            'is_edited'    => true,
            'catatan_edit' => $request->catatan_edit . ' | Diubah oleh Admin pada ' . now()
        ]);

        return redirect()->route('admin.transaksi.history')
                        ->with('success', 'Transaksi berhasil diupdate dan dicatat.');
    }

    public function dashboardKasir() 
    {
        $pesananPending = Transaksi::where('status', 'pending')
                                ->with('details.menu')
                                ->get();
        
        // Anda bisa menggabungkan ini dengan data dashboard yang sudah ada
        return view('kasir.dashboard', compact('pesananPending'));
    }

    public function selesai(Request $request, $id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        
        // Jika metode BUKAN tunai, kita set totalBayar = total_harga
        // Jika tunai, gunakan input dari form
        $isNonTunai = in_array($transaksi->metode_bayar, ['qris', 'transfer']);
        
        $totalBayar = $isNonTunai ? $transaksi->total_harga : $request->total_bayar;

        // Validasi hanya jika tunai
        if (!$isNonTunai) {
            $request->validate([
                'total_bayar' => 'required|numeric|min:' . $transaksi->total_harga,
            ], [
                'total_bayar.min' => 'Uang yang dibayarkan kurang dari total tagihan!'
            ]);
        }

        $transaksi->update([
            'total_bayar' => $totalBayar,
            'kembalian'   => $totalBayar - $transaksi->total_harga,
            'status'      => 'selesai'
        ]);
        
        return redirect()->route('kasir.transaksi.pending')
                        ->with('success', 'Pesanan berhasil diselesaikan!');
    }

    public function daftarPending()
    {
        $pesananPending = Transaksi::with(['details.menu'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasir.transaksi.pending', compact('pesananPending'));
    }
}