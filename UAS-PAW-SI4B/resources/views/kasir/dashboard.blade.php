@extends('kasir.layouts.kasir')

@section('title', 'Dashboard Kasir')
@section('page-title', 'Dashboard Kasir')

@section('content')

{{-- ── Kartu Statistik ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-cash-coin fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Pendapatan Hari Ini</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-receipt fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Transaksi</div>
                    <div class="fw-bold fs-5">{{ $totalTransaksi }} transaksi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Transaksi Pending</div>
                    <div class="fw-bold fs-5">{{ $transaksiPending }} pending</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tabel Transaksi Hari Ini ── --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Transaksi Hari Ini</h6>
            <a href="{{ route('kasir.transaksi.buat') }}" class="btn btn-hijau btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
            </a>
        </div>

        @if ($transaksiHariIni->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Belum ada transaksi hari ini
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksiHariIni as $trx)
                        <tr>
                            <td class="fw-semibold">{{ $trx->kode_transaksi }}</td>
                            <td class="text-muted small">{{ $trx->created_at->format('H:i') }}</td>
                            <td>{{ $trx->details->count() }} item</td>
                            <td class="fw-semibold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-light text-dark text-capitalize">
                                    {{ $trx->metode_bayar }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $trx->status }} px-2 py-1 rounded-pill text-capitalize">
                                    {{ $trx->status }}
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
        @endif
    </div>
</div>

{{-- ── Area Pesanan Pelanggan (Pending) ── --}}
<div class="card mb-4 border-warning shadow-sm">
    <div class="card-header bg-warning text-white fw-bold">
        <i class="bi bi-hourglass-split me-2"></i> Pesanan Pelanggan (Pending: {{ $pesananPending->count() }})
    </div>
    <div class="card-body">
        @if ($pesananPending->isEmpty())
            <p class="text-center text-muted my-3">Tidak ada pesanan masuk dari pelanggan.</p>
        @else
            <div class="row g-3">
                @foreach ($pesananPending as $p)
                <div class="col-md-4">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <h6 class="fw-bold">Meja: {{ $p->table_number ?? '-' }}</h6>
                            <small class="text-muted">{{ $p->kode_transaksi }}</small>
                            
                            <ul class="list-unstyled mt-2 small border-top pt-2">
                                @foreach ($p->details as $d)
                                    <li>{{ $d->qty }}x {{ $d->menu->name ?? 'Menu Terhapus' }}</li>
                                @endforeach
                            </ul>

                            {{-- Menampilkan Catatan Pelanggan (Jika Ada) --}}
                            @if($p->catatan)
                                <div class="alert alert-info p-2 small mt-2">
                                    <strong>Catatan:</strong> {{ $p->catatan }}
                                </div>
                            @endif

                            <div class="fw-bold mt-2 text-danger">Total: Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                            
                            <div class="d-flex gap-2 mt-3">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('kasir.transaksi.show', $p->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                    Detail
                                </a>
                                {{-- Tombol Selesaikan --}}
                                <form action="{{ route('kasir.transaksi.selesai', $p->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 btn-sm">Selesaikan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection