@extends('admin.layout.admin')
@section('title', 'Histori Transaksi')

{{-- WAJIB MENGGUNAKAN @section('content') --}}
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Laporan Semua Transaksi</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Waktu</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $trx)
                    <tr>
                        <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                        <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($trx->metode_bayar) }}</span></td>
                        <td>
                            @if($trx->is_edited)
                                <span class="badge bg-warning text-dark" title="{{ $trx->catatan_edit }}">Diubah</span>
                            @else
                                <span class="badge bg-success">{{ ucfirst($trx->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.transaksi.edit', $trx->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection