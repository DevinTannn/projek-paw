<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Transaksi;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request) {
        // 1. Data Statistik Utama
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->get();
        $totalPendapatan = $transaksiHariIni->sum('total_harga');
        $jumlahTransaksi = $transaksiHariIni->count();
        $jumlahPembeli = $transaksiHariIni->pluck('user_id')->unique()->count();
        $menuTerbaru = Menu::latest()->take(5)->get();

        // 2. Fitur Menu Favorit / Best Seller
        $filter = $request->get('filter', 'daily');
        
        // PERBAIKAN: Gunakan 'name' (bukan nama_menu) dan 'qty' (bukan jumlah)
        $query = DB::table('detail_transaksis')
            ->join('menus', 'detail_transaksis.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(detail_transaksis.qty) as total_terjual'));

        if ($filter == 'daily') {
            $query->whereDate('detail_transaksis.created_at', Carbon::today());
        } elseif ($filter == 'weekly') {
            $query->whereBetween('detail_transaksis.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter == 'monthly') {
            $query->whereMonth('detail_transaksis.created_at', Carbon::now()->month);
        }

        // PERBAIKAN: Sesuaikan groupBy dengan select
        $bestSellers = $query->groupBy('menus.id', 'menus.name')
                            ->orderByDesc('total_terjual')
                            ->limit(5)
                            ->get();

        return view('admin.dashboard', compact(
            'transaksiHariIni', 'totalPendapatan', 'jumlahTransaksi', 
            'jumlahPembeli', 'menuTerbaru', 'bestSellers', 'filter'
        ));
    }

    // CREATE: Menyimpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
        ]);

        Menu::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Menu berhasil ditambahkan!');
    }

    // UPDATE: Memproses update menu
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