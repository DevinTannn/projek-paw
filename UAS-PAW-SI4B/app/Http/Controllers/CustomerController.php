<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Tampilkan semua data pelanggan di dashboard
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('dashboard.customers.index', compact('customers'));
    }

    // Form tambah pelanggan
    public function create()
    {
        return view('dashboard.customers.create');
    }

    // Simpan data pelanggan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
                         ->with('success', 'Pelanggan Padmamula berhasil ditambahkan!');
    }

    // Tampilkan detail pelanggan (opsional)
    public function show(Customer $customer)
    {
        return view('dashboard.customers.show', compact('customer'));
    }

    // Form edit data pelanggan
    public function edit(Customer $customer)
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    // Perbarui data pelanggan
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'point' => 'required|integer|min:0',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
                         ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // Hapus data pelanggan
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('success', 'Pelanggan berhasil dihapus dari sistem.');
    }
}