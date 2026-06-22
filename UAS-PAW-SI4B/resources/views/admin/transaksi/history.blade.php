@extends('admin.layout.admin')
@section('title', 'Histori Transaksi')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark">Histori Transaksi</h3>
            <p class="text-muted">Pantau dan kelola laporan transaksi Padmamula.</p>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            {{-- Filter Area --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex gap-2">
                    @php $currentStatus = request('status'); @endphp
                    @foreach(['' => 'Semua', 'pending' => 'Pending', 'selesai' => 'Selesai', 'diubah' => 'Diubah'] as $val => $label)
                        <a href="{{ route('admin.transaksi.history', ['status' => $val, 'tanggal' => request('tanggal')]) }}" 
                           class="btn btn-sm px-4 rounded-pill {{ ($currentStatus == $val || (!$currentStatus && !$val)) ? 'btn-primary' : 'btn-light text-secondary' }}">
                           {{ $label }}
                        </a>
                    @endforeach
                </div>

                <form action="{{ route('admin.transaksi.history') }}" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="date" name="tanggal" class="form-control form-control-sm rounded-pill" value="{{ request('tanggal') }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">Filter</button>
                </form>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle border-separate" style="border-spacing: 0 10px;">
                    <thead class="text-uppercase text-secondary" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <tr>
                            <th class="ps-3">Kode Transaksi</th>
                            <th>Waktu</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $trx)
                        <tr class="bg-white border-0 shadow-sm" style="border-radius: 10px;">
                            <td class="ps-3 fw-bold">{{ $trx->kode_transaksi }}</td>
                            <td class="text-secondary">{{ $trx->created_at->format('d M Y') }} <small class="text-muted d-block">{{ $trx->created_at->format('H:i') }}</small></td>
                            <td class="fw-bold text-primary">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusColors = ['pending' => 'bg-warning-subtle text-warning', 'selesai' => 'bg-success-subtle text-success', 'batal' => 'bg-danger-subtle text-danger', 'diubah' => 'bg-info-subtle text-info'];
                                @endphp
                                <span class="badge {{ $statusColors[$trx->status] ?? 'bg-secondary-subtle' }} rounded-pill px-3 py-2">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.transaksi.edit', $trx->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 1.5rem !important; }
    .table-hover tbody tr:hover { transform: scale(1.01); transition: 0.2s; background-color: #f8f9fa !important; }
    .badge { font-weight: 500; font-size: 0.8rem; }
</style>
@endsection