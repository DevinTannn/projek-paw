<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer; // Pastikan model Customer sudah ada
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar semua pelanggan di dashboard.
     */
    public function index()
    {
        // Mengambil semua data pelanggan dari database (diurutkan dari yang terbaru)
        $customers = Customer::orderBy('created_at', 'desc')->get();

        // Mengirimkan variabel $customers ke view
        return view('dashboard.customers.index', compact('customers'));
    }

    /**
     * Menampilkan formulir pendaftaran pelanggan baru.
     */
    public function create()
    {
        return view('dashboard.customers.create');
    }

    /**
     * Menyimpan data pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'table_number' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Customer::create([
            'name' => $request->name,
            'table_number' => $request->table_number,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard.customers.index')->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail profil seorang pelanggan.
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('dashboard.customers.show', compact('customer'));
    }

    /**
     * Menampilkan formulir edit data pelanggan.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('dashboard.customers.edit', compact('customer'));
    }

    /**
     * Memperbarui data pelanggan di database.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'table_number' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update([
            'name' => $request->name,
            'table_number' => $request->table_number,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard.customers.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    /**
     * Menghapus data pelanggan dari database.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil dihapus secara permanen!');
    }
}