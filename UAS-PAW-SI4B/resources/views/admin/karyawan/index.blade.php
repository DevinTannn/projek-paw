@extends('admin.layout.admin')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Manajemen Karyawan</h4>
            <p class="text-muted small">Kelola data akses pengguna sistem.</p>
        </div>
        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-user-plus me-2"></i>Tambah Karyawan
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            {{-- Search Bar --}}
            <form action="{{ route('admin.karyawan.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded-start-pill ps-3" placeholder="Cari nama karyawan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary rounded-end-pill px-4"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-separate" style="border-spacing: 0 10px;">
                    <thead class="text-secondary" style="font-size: 0.8rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-3">Karyawan</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawans as $karyawan)
                        <tr class="bg-light border-0 shadow-sm" style="border-radius: 10px;">
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    {{-- Logika Menampilkan Foto --}}
                                    @if($karyawan->image_url)
                                        <img src="{{ asset('storage/' . $karyawan->image_url) }}" 
                                             class="rounded-circle me-3 border shadow-sm" 
                                             style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" 
                                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">
                                            {{ strtoupper(substr($karyawan->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="fw-bold text-dark">{{ $karyawan->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted align-middle">{{ $karyawan->email }}</td>
                            <td class="align-middle">
                                <span class="badge {{ $karyawan->role == 'admin' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' }} rounded-pill px-3 py-2">
                                    {{ ucfirst($karyawan->role) }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus karyawan ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data karyawan tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover { transform: scale(1.01); transition: 0.2s; cursor: pointer; }
    .rounded-4 { border-radius: 1.5rem !important; }
</style>
@endsection