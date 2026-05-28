@extends('admin.layout.admin')
@section('title', 'Edit Menu')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Edit Menu: {{ $menu->name }}</h5>
        </div>
        <div class="card-body">
            {{-- Debug Info: Hapus bagian ini jika sudah berjalan lancar --}}
            @if(empty($menu->image_url))
                <div class="alert alert-warning">Catatan: Data gambar di database kosong. Harap unggah gambar baru.</div>
            @endif

            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Preview Gambar --}}
                <div class="mb-4">
                    <label class="form-label">Gambar Saat Ini</label><br>
                    @if(!empty($menu->image_url) && file_exists(public_path('storage/' . $menu->image_url)))
                        <img src="{{ asset('storage/' . $menu->image_url) }}" class="img-thumbnail shadow-sm" width="200" alt="Menu Image">
                    @else
                        <div class="p-3 bg-light border text-center text-muted" style="width: 200px; border-style: dashed !important;">
                            Tidak ada gambar ditemukan
                        </div>
                    @endif
                </div>

                {{-- Input Ganti Gambar --}}
                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (Opsional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                {{-- Nama Menu --}}
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga --}}
                <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $menu->price) }}" required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="form-label">Deskripsi Menu</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan detail atau komposisi menu...">{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection