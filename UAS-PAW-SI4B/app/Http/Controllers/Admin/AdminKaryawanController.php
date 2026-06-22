<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'role'     => 'required',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ];

        // Proses upload gambar
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('profiles', 'public');
        }

        User::create($data);

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    // UPDATE: Memproses perubahan data karyawan
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = \App\Models\User::findOrFail($id);
        $data = $request->except(['password', 'image']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Proses Ganti Gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->image_url) {
                Storage::disk('public')->delete($user->image_url);
            }
            // Simpan gambar baru
            $data['image_url'] = $request->file('image')->store('profiles', 'public');
        }

        $user->update($data);
        return redirect()->route('admin.karyawan.index')->with('success', 'Data berhasil diupdate!');
    }

    // DELETE: Menghapus karyawan
    public function destroy($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil dihapus!');
    }

    public function edit($id)
    {
        // Mengambil data karyawan berdasarkan ID
        $karyawan = User::findOrFail($id);
        
        // Mengirim data ke view edit
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function create()
    {
        $roles = ['kasir', 'admin']; 
        return view('admin.karyawan.create', compact('roles'));
    }
}