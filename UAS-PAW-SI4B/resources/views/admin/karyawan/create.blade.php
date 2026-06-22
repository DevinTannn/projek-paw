@extends('kasir.layouts.kasir')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Tambah Karyawan Baru</h5>
        
        <form action="{{ route('admin.karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Foto Profil</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>
            </div>

            <button type="submit" class="btn btn-success">Simpan Data</button>
            <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection