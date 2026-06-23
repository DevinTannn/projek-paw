@extends('admin.layout.admin')

@section('title', 'Dashboard Utama')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark">Dashboard Overview</h4>
        <span class="text-muted">{{ date('d F Y') }}</span>
    </div>

    <div class="row g-4 mb-4">
        @php
            $stats = [
                ['title' => 'Pendapatan', 'value' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'), 'icon' => 'bi-wallet2', 'color' => 'primary'],
                ['title' => 'Transaksi', 'value' => $jumlahTransaksi . ' Transaksi', 'icon' => 'bi-receipt-cutoff', 'color' => 'success'],
                ['title' => 'Pelanggan', 'value' => $jumlahPembeli . ' Orang', 'icon' => 'bi-people', 'color' => 'warning']
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ $stat['color'] }} bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="bi {{ $stat['icon'] }} fs-4 text-{{ $stat['color'] }}"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">{{ $stat['title'] }}</p>
                        <h4 class="fw-bold mb-0">{{ $stat['value'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Transaksi Hari Ini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="text-muted text-uppercase small">
                                <tr>
                                    <th>ID</th>
                                    <th>Waktu</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksiHariIni as $t)
                                <tr>
                                    <td class="fw-semibold">#{{ $t->id }}</td>
                                    <td>{{ $t->created_at->format('H:i') }}</td>
                                    <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($t->status == 'selesai')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Selesai</span>
                                        @elseif($t->status == 'pending')
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Pending</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">{{ ucfirst($t->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Menu Baru</h5>
                    <div class="list-group list-group-flush">
                        @foreach($menuTerbaru as $m)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-2">
                            <span class="text-truncate">{{ $m->name }}</span>
                            <span class="badge bg-primary-subtle text-primary rounded-pill">New</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection