<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminKaryawanController extends Controller
{
    // READ: Menampilkan daftar karyawan dengan fitur pencarian
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin'); // Hanya tampilkan kasir/pegawai

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $karyawans = $query->get();
        return view('admin.karyawan.index', compact('karyawans'));
    }

    // CREATE: Menyimpan karyawan baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'kasir',
        ]);

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    // UPDATE: Memproses perubahan data karyawan
    public function update(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Jika password diisi, update passwordnya
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $karyawan->update($data);

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan diperbarui!');
    }

    // DELETE: Menghapus karyawan
    public function destroy($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}