@extends('kasir.layouts.kasir')
@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Histori Transaksi</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Waktu</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $trx)
                <tr>
                    <td>{{ $trx->kode_transaksi }}</td>
                    <td>{{ $trx->created_at->format('d M H:i') }}</td>
                    <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td><span class="badge bg-success">{{ $trx->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection