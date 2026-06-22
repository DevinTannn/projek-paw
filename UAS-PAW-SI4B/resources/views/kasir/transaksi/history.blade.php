@extends('kasir.layouts.kasir')

@section('page-title', 'Histori Penjualan')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Histori Transaksi</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Waktu</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $trx)
                    <tr>
                        <td class="fw-bold">{{ $trx->kode_transaksi }}</td>
                        {{-- Menggunakan format H:i untuk Jam:Menit, d M untuk tanggal --}}
                        <td>{{ $trx->created_at->format('H:i') }}</td>
                        
                        {{-- Perbaikan: Menjumlahkan quantity dari relasi details --}}
                        <td>{{ $trx->details->sum('quantity') }} item</td>
                        
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $trx->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('kasir.struk', $trx->id) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-printer"></i> Struk
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