@extends('admin.layout.admin')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Manajemen Menu</h4>
            <p class="text-muted small">Kelola daftar menu yang tersedia di Padmamula.</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Tambah Menu
        </a>
    </div>

    {{-- Filter Kategori --}}
    <div class="d-flex gap-2 mb-4">
        @php $currentCat = request('category'); @endphp
        <a href="{{ route('admin.menus.index') }}" 
           class="btn btn-sm px-4 rounded-pill {{ !$currentCat ? 'btn-primary' : 'btn-light shadow-sm' }}">Semua</a>
        <a href="{{ route('admin.menus.index', ['category' => 'Makanan']) }}" 
           class="btn btn-sm px-4 rounded-pill {{ $currentCat == 'Makanan' ? 'btn-primary' : 'btn-light shadow-sm' }}">Makanan</a>
        <a href="{{ route('admin.menus.index', ['category' => 'Minuman']) }}" 
           class="btn btn-sm px-4 rounded-pill {{ $currentCat == 'Minuman' ? 'btn-primary' : 'btn-light shadow-sm' }}">Minuman</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            {{-- Search Bar --}}
            <form action="{{ route('admin.menus.index') }}" method="GET" class="mb-4">
                {{-- Hidden input agar filter kategori tetap aktif saat mencari --}}
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded-start-pill ps-3" placeholder="Cari nama menu..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary rounded-end-pill px-4"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-separate" style="border-spacing: 0 10px;">
                    <thead class="text-secondary" style="font-size: 0.8rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-3">Nama Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr class="bg-light border-0 shadow-sm" style="border-radius: 10px;">
                            <td class="ps-3 fw-bold">{{ $menu->name }}</td>
                            <td class="text-muted">{{ $menu->category->name ?? '-' }}</td>
                            <td class="text-primary fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada menu yang ditemukan.</td></tr>
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