<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminMenuController extends Controller {
    public function index(Request $request  ) {
        $query = Menu::with('category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $menus = Menu::with('category')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'price' => 'required|numeric', 'category_id' => 'required']);
        Menu::create($request->all());
        return redirect()->route('admin.menus.index')->with('success', 'Menu ditambahkan!');
    }

    public function show(Menu $menu) {
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu) {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu) {
        $menu->update($request->all());
        return redirect()->route('admin.menus.index')->with('success', 'Menu diupdate!');
    }

    public function destroy(Menu $menu) {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu dihapus!');
    }
}
