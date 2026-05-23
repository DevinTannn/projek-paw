<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

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
}