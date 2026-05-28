@extends('kasir.layouts.kasir')
@section('title', 'Daftar Semua Pesanan Pending')
@section('page-title', 'Daftar Semua Pesanan Pending')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Meja</th>
                        <th>Total</th>
                        <th>Metode</th> 
                        <th>Status</th>
                        <th>Bayar</th> 
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesananPending as $p)
                    <tr>
                        <td>{{ $p->kode_transaksi }}</td>
                        <td>{{ $p->table_number ?? '-' }}</td>
                        <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        
                        <td>
                            <span class="badge bg-{{ $p->metode_bayar == 'tunai' ? 'success' : 'primary' }}">
                                {{ strtoupper($p->metode_bayar) }}
                            </span>
                        </td>

                        <td><span class="badge bg-warning text-dark">Pending</span></td>

                        <td>
                            <form action="{{ route('kasir.transaksi.selesai', $p->id) }}" method="POST">
                                @csrf
                                <input type="number" name="total_bayar" 
                                       class="form-control form-control-sm" 
                                       placeholder="Jumlah Uang" required 
                                       value="{{ in_array($p->metode_bayar, ['qris', 'transfer']) ? $p->total_harga : '' }}"
                                       {{ in_array($p->metode_bayar, ['qris', 'transfer']) ? 'readonly' : '' }}
                                       min="{{ $p->total_harga }}">
                        </td>

                        <td>
                            <a href="{{ route('kasir.transaksi.show', $p->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            <button type="submit" class="btn btn-sm btn-success">Proses Selesai</button>
                            </form> 
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $pesananPending->links() }}
        </div>
    </div>
</div>
@endsection