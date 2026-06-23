<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Category;        // ← Model milik teman, JANGAN diubah
use App\Models\DetailTransaksi;
use App\Models\Menu;            // ← Model milik teman, JANGAN diubah
use App\Models\Transaksi;
use App\Models\Panggilan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;    
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // ── GET /kasir/dashboard ──────────────────────────────────
    public function index()
    {
        // 1. Data Transaksi Selesai (Statistik)
        $transaksiHariIni = Transaksi::with('details')
            ->whereDate('created_at', today())
            ->where('status', 'selesai')
            ->orderByDesc('created_at')
            ->get();

        $totalPendapatan = $transaksiHariIni->sum('total_harga');
        $totalTransaksi  = $transaksiHariIni->count();
        
        // 2. Data Pesanan (Status: pending)
        $pesananPending = Transaksi::with(['details.menu'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
            
        // --- TAMBAHKAN BARIS INI ---
        // Menghitung jumlah transaksi dengan status 'pending' untuk ditampilkan di statistik
        $transaksiPending = Transaksi::where('status', 'pending')->count();

        // 3. Data Panggilan (Model Panggilan)
        $daftarPanggilan = Panggilan::where('status', 'aktif')->get();

        return view('kasir.dashboard', compact(
            'transaksiHariIni', 'totalPendapatan', 'totalTransaksi', 
            'transaksiPending', 'pesananPending', 'daftarPanggilan'
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
                $menu      = Menu::findOrFail($item['menu_id']);
                $subtotal  = $menu->price * $item['qty'];   
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'menu_id'      => $menu->id,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $menu->price,
                    'subtotal'     => $subtotal,
                ];
            }

            // --- UBAH DI SINI ---
            // Paksa status jadi 'selesai' agar tidak masuk antrean pending
            $statusAwal = 'selesai'; 
            
            // Pastikan total bayar minimal sama dengan total harga
            $totalBayar = $request->total_bayar >= $totalHarga ? $request->total_bayar : $totalHarga;
            $kembalian  = $totalBayar - $totalHarga;

            $transaksi = Transaksi::create([
                'kasir_id'       => Auth::id(),
                'kode_transaksi' => Transaksi::generateKode(),
                'total_harga'    => $totalHarga,
                'total_bayar'    => $totalBayar,
                'kembalian'      => $kembalian,
                'metode_bayar'   => $request->metode_bayar,
                'status'         => $statusAwal, // Selalu selesai
                'catatan'        => $request->catatan,
                'tipe_transaksi' => $request->tipe_transaksi ?? 'kasir', // Simpan tipe transaksi
            ]);

            foreach ($itemsData as &$d) {
                $d['transaksi_id'] = $transaksi->id;
            }
            DetailTransaksi::insert($itemsData);

            DB::commit();

            // Redirect langsung ke halaman struk
            return redirect()->route('kasir.struk', $transaksi->id)
                ->with('success', 'Transaksi berhasil diproses!');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()])->withInput();
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

        // 1. Logika Filter Status
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'diubah') {
                $query->where('is_edited', true);
            } else {
                $query->where('status', $request->status);
            }
        }

        // 2. LOGIKA BARU: Filter Berdasarkan Tanggal
        if ($request->has('tanggal') && !empty($request->tanggal)) {
            $query->whereDate('created_at', $request->tanggal);
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
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->metode_bayar === 'tunai') {
            // Validasi di sini: Jika input < total_harga, proses akan berhenti
            $request->validate([
                'total_bayar' => 'required|numeric|min:' . $transaksi->total_harga,
            ], [
                'total_bayar.min' => 'Uang yang dibayarkan kurang dari total tagihan!',
            ]);
            
            $totalBayar = $request->total_bayar;
        } else {
            $totalBayar = $transaksi->total_harga;
        }

        $transaksi->update([
            'total_bayar' => $totalBayar,
            'kembalian'   => $totalBayar - $transaksi->total_harga,
            'status'      => 'selesai'
        ]);
        
        return redirect()->route('kasir.dashboard')->with('success', 'Transaksi selesai!');
    }

    public function daftarPending()
    {
        $pesananPending = Transaksi::with(['details.menu'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kasir.transaksi.pending', compact('pesananPending'));
    }

    // ── METHOD BARU: DOWNLOAD REKAP PDF ──────────────────────
    public function downloadPdf(Request $request)
    {
        $status = $request->input('status');
        
        // Menangkap parameter tanggal, jika kosong default ke rentang bulan ini
        $startDate = $request->input('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = Transaksi::with(['details.menu', 'kasir']);

        // Filter berdasarkan parameter rentang tanggal dari request
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        }

        if (!empty($status)) {
            if ($status == 'diubah') {
                $query->where('is_edited', true);
            } else {
                $query->where('status', $status);
            }
        }

        $transaksi = $query->orderByDesc('created_at')->get();

        $totalPendapatan = $transaksi->where('status', 'selesai')->sum('total_harga'); 
        $totalTransaksi  = $transaksi->count();

        // Data dikirimkan lengkap dengan $startDate dan $endDate agar dibaca oleh Blade PDF
        $data = [
            'transaksi'       => $transaksi,
            'totalPendapatan' => $totalPendapatan,
            'totalTransaksi'  => $totalTransaksi,
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'statusText'      => $status ? ucfirst($status) : 'Semua'
        ];

        $pdf = Pdf::loadView('admin.rekap.pdf', $data);
        return $pdf->download('Laporan_Transaksi_Padmamula_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }
}