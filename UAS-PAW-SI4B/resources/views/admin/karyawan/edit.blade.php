@extends('admin.layout.admin')

@section('title', 'Edit Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4 col-md-8 mx-auto">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Edit Karyawan: {{ $karyawan->name }}</h4>

            {{-- ALERT UNTUK MENAMPILKAN ERROR VALIDASI (Jika ada) --}}
            @if ($errors->any())
            <div class="alert alert-danger mb-4 rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- PREVIEW GAMBAR (SUDAH DISESUAIKAN LANGSUNG KE FOLDER PUBLIC UPLOADS) --}}
                <div class="text-center mb-4">
                    @if($karyawan->image && file_exists(public_path($karyawan->image)))
                    <img src="{{ asset($karyawan->image) }}" class="rounded-circle border shadow-sm" width="100"
                        height="100" style="object-fit: cover;">
                    @elseif($karyawan->image_url && file_exists(public_path($karyawan->image_url)))
                    <img src="{{ asset($karyawan->image_url) }}" class="rounded-circle border shadow-sm" width="100"
                        height="100" style="object-fit: cover;">
                    @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-sm"
                        style="width: 100px; height: 100px; font-size: 2rem; font-weight: bold;">
                        {{ strtoupper(substr($karyawan->name, 0, 1)) }}
                    </div>
                    @endif
                </div>

                {{-- INPUT GAMBAR --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti Foto Profil</label>
                    <input type="file" name="image" class="form-control rounded-pill" accept="image/*">
                    <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG. Max: 2MB</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control rounded-pill" value="{{ $karyawan->name }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control rounded-pill" value="{{ $karyawan->email }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted">(Kosongkan jika tidak
                            ingin diubah)</small></label>
                    <input type="password" name="password" class="form-control rounded-pill" placeholder="********">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.karyawan.index') }}"
                        class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection