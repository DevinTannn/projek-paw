<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Transaksi;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    // READ: Menampilkan daftar semua menu
    public function index() {
        // 1. Transaksi Hari Ini
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->get();
        $totalPendapatan = $transaksiHariIni->sum('total_harga');
        $jumlahTransaksi = $transaksiHariIni->count();

        // 2. Total Pelanggan (Asumsi yang melakukan transaksi hari ini)
        $jumlahPembeli = $transaksiHariIni->pluck('user_id')->unique()->count();

        // 3. Menu Terbaru (5 menu terakhir)
        $menuTerbaru = Menu::latest()->take(5)->get();

        return view('admin.dashboard', compact('transaksiHariIni', 'totalPendapatan', 'jumlahTransaksi', 'jumlahPembeli', 'menuTerbaru'));
    }

    // CREATE: Menyimpan menu baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Menu berhasil ditambahkan!');
    }

    // UPDATE: Menampilkan form edit (opsional jika pakai modal) atau memproses update
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
        ]);

        $menu->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Menu berhasil diperbarui!');
    }

    // DELETE: Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Menu berhasil dihapus!');
    }
}