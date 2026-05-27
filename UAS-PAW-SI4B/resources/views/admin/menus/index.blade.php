@extends('admin.layout.admin')

@section('title', 'Manajemen Menu')

@section('content')
<div class="card p-4 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Daftar Menu Padmamula</h4>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.menus.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari menu..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
            </form>
            
            {{-- Tombol Tambah Menu dengan rute yang benar --}}
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Menu
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                <tr>
                    <td class="fw-semibold">{{ $menu->name }}</td>
                    <td>{{ $menu->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{-- Tombol Edit dengan rute yang benar --}}
                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-warning text-white me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada menu yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection