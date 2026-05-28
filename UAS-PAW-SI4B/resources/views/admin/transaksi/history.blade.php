@extends('admin.layout.admin')
@section('title', 'Histori Transaksi')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        {{-- Header Modern dengan Filter Pills --}}
        <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">Laporan Semua Transaksi</h5>
            
            <div class="d-flex gap-2">
                @php $currentStatus = request('status'); @endphp
                <a href="{{ route('admin.transaksi.history') }}" 
                   class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3">Semua</a>
                
                <a href="{{ route('admin.transaksi.history', ['status' => 'pending']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'pending' ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill px-3">Pending</a>
                
                <a href="{{ route('admin.transaksi.history', ['status' => 'selesai']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'selesai' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3">Selesai</a>
                
                <a href="{{ route('admin.transaksi.history', ['status' => 'diubah']) }}" 
                   class="btn btn-sm {{ $currentStatus == 'diubah' ? 'btn-info' : 'btn-outline-info' }} rounded-pill px-3">Diubah</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
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
                            <td><span class="badge bg-info text-white">{{ ucfirst($trx->metode_bayar) }}</span></td>
                            <td>
                                {{-- Logika Tampilan Badge yang Diperbaiki --}}
                                @if($trx->status == 'diubah' || $trx->is_edited)
                                    <span class="badge bg-warning text-dark" title="{{ $trx->catatan_edit }}">
                                        <i class="fas fa-exclamation-triangle"></i> Diubah
                                    </span>
                                @elseif($trx->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($trx->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($trx->status == 'batal')
                                    <span class="badge bg-danger">Batal</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($trx->status) }}</span>
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
</div>

<style>
    .btn { transition: all 0.3s ease; }
    .btn-outline-secondary:hover { color: white; }
</style>
@endsection