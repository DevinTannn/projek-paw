@extends('admin.layout.admin')

@section('title', 'Dashboard Utama')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card p-4 bg-primary text-white">
            <h5>Pendapatan Hari Ini</h5>
            <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 bg-success text-white">
            <h5>Jumlah Transaksi</h5>
            <h2>{{ $jumlahTransaksi }} Transaksi</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 bg-warning text-dark">
            <h5>Pelanggan Hari Ini</h5>
            <h2>{{ $jumlahPembeli }} Orang</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <h5 class="mb-3">Transaksi Hari Ini</h5>
            <table class="table table-hover">
                <thead>
                    <tr><th>ID</th><th>Waktu</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($transaksiHariIni as $t)
                    <tr><td>{{ $t->id }}</td><td>{{ $t->created_at->format('H:i') }}</td><td>Rp {{ number_format($t->total_harga) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h5 class="mb-3">Menu Baru Ditambahkan</h5>
            <ul class="list-group list-group-flush">
                @foreach($menuTerbaru as $m)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $m->name }} <span class="badge bg-info">Baru</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection