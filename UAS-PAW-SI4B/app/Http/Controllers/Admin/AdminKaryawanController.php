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
        $query = User::where('role', '!=', 'admin'); 

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

        // Simpan gambar langsung ke folder public/uploads/profiles
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Pindahkan file fisik
            $file->move(public_path('uploads/profiles'), $fileName);
            
            // Set path database untuk kedua jenis kolom agar aman menghadapi skema apa pun
            $data['image'] = 'uploads/profiles/' . $fileName;
            $data['image_url'] = 'uploads/profiles/' . $fileName;
        }

        User::create($data);

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    // UPDATE: Memproses perubahan data karyawan
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::findOrFail($id);
        
        // Ambil semua data inputan kecuali password dan file gambarnya
        $data = $request->except(['password', 'image']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // PROSES GANTI GAMBAR LANGSUNG DI FOLDER PUBLIC
        if ($request->hasFile('image')) {
            // Hapus file fisik lama jika ada di database & storage lokal
            $oldImage = $user->image ?? $user->image_url;
            if ($oldImage && file_exists(public_path($oldImage))) {
                @unlink(public_path($oldImage));
            }
            
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Pindahkan file baru
            $file->move(public_path('uploads/profiles'), $fileName);
            
            // Update kedua kolom agar serasi
            $data['image'] = 'uploads/profiles/' . $fileName;
            $data['image_url'] = 'uploads/profiles/' . $fileName;
        }

        $user->update($data);
        
        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diupdate!');
    }

    // DELETE: Menghapus karyawan
    public function destroy($id)
    {
        $karyawan = User::findOrFail($id);
        
        // Hapus file fisik sebelum menghapus row data
        $oldImage = $karyawan->image ?? $karyawan->image_url;
        if ($oldImage && file_exists(public_path($oldImage))) {
            @unlink(public_path($oldImage));
        }
        
        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil dihapus!');
    }

    public function edit($id)
    {
        $karyawan = User::findOrFail($id);
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function create()
    {
        $roles = ['kasir', 'admin']; 
        return view('admin.karyawan.create', compact('roles'));
    }
}