@extends('admin.layout.admin')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="card p-4 shadow-sm border-0">
    <h4 class="fw-bold mb-4">Tambah Menu Baru</h4>
    
    {{-- Atribut enctype="multipart/form-data" wajib ada untuk upload file --}}
    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Menu</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama menu" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    {{-- Pastikan $categories tidak null dan berisi data dari Controller --}}
                    @if(isset($categories) && $categories->count() > 0)
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>Kategori belum tersedia</option>
                    @endif
                </select>
                @if(!isset($categories) || $categories->count() === 0)
                    <small class="text-danger">Data kategori kosong, pastikan isi tabel 'categories' di database!</small>
                @endif
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Harga (Rp)</label>
                <input type="number" name="price" class="form-control" placeholder="Contoh: 15000" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Opsional: Tambahkan keterangan menu"></textarea>
        </div>

        {{-- Input File Gambar --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Foto Menu</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB.</small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Simpan Menu</button>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary px-4">Kembali</a>
        </div>
    </form>
</div>
@endsection