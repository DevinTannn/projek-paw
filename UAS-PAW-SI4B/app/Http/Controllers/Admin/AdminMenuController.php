<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller {
    public function index(Request $request) 
    {
        $query = Menu::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }
    
        $menus = $query->get();
        
        return view('admin.menus.index', compact('menus'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required', 
            'price' => 'required|numeric', 
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            // PERBAIKAN: Gunakan 'image_url' sesuai dengan kolom di database dan model
            $data['image_url'] = $path; 
        }

        // Hapus kunci 'image' agar tidak mencoba dimasukkan ke database (karena tidak ada kolomnya)
        unset($data['image']);

        Menu::create($data);
        
        return redirect()->route('admin.menus.index')->with('success', 'Menu ditambahkan!');
    }

    public function update(Request $request, Menu $menu) 
    {
        $request->validate([
            'name'        => 'required|string', 
            'price'       => 'required|numeric', 
            'category_id' => 'required',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        // Ambil semua input kecuali file gambar
        $data = $request->except(['image']); 

        // Proses upload gambar ke kolom image_url (sesuai Model)
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image_url) {
                Storage::disk('public')->delete($menu->image_url);
            }
            // Simpan gambar baru ke folder 'menus' dan masukkan ke field image_url
            $data['image_url'] = $request->file('image')->store('menus', 'public');
        }

        // Update data ke database
        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diupdate!');
    }

    public function show(Menu $menu) {
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu) {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function destroy(Menu $menu) {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu dihapus!');
    }
}
