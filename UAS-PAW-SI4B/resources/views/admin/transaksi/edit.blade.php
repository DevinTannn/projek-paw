@extends('admin.layout.admin')
@section('title', 'Edit Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Transaksi: {{ $transaksi->kode_transaksi }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.transaksi.update', $transaksi->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Harga (Rp)</label>
                            <input type="number" name="total_harga" class="form-control" 
                                   value="{{ $transaksi->total_harga }}" required>
                            <small class="text-muted">Masukkan total harga yang benar setelah dikoreksi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Perubahan</label>
                            <textarea name="catatan_edit" class="form-control" rows="3" 
                                      placeholder="Contoh: Salah input nominal menu" required></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.transaksi.history') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection