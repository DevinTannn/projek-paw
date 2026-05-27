@extends('admin.layout.admin')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="card p-4 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Daftar Karyawan</h4>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.karyawan.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari karyawan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
            </form>
            <a href="#" class="btn btn-success"><i class="fas fa-user-plus me-2"></i>Tambah Karyawan</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $karyawan)
                <tr>
                    <td class="fw-semibold">{{ $karyawan->name }}</td>
                    <td>{{ $karyawan->email }}</td>
                    <td><span class="badge bg-info text-dark">{{ ucfirst($karyawan->role) }}</span></td>
                    <td class="text-center">
                        <a href="#" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data karyawan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection