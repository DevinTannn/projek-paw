@extends('admin.layout.admin')
@section('title', 'Histori Transaksi')

@section('content')
<div class="container-fluid py-4">
    {{-- BAGIAN JUDUL & TOMBOL --}}
    <div class="row mb-4 align-items-center no-print">
        <div class="col-md-6 mb-3 mb-md-0">
            <h3 class="fw-bold text-dark m-0">Histori Transaksi</h3>
            <p class="text-muted small mb-0">Pantau dan kelola laporan transaksi Padmamula.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.rekap.pdf', ['status' => request('status'), 'start_date' => request('tanggal'), 'end_date' => request('tanggal')]) }}"
                class="btn btn-danger rounded-pill px-4 me-2">
                <i class="fas fa-file-pdf me-2"></i>Download PDF
            </a>
            <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-print me-2"></i>Cetak Laporan
            </button>
        </div>
    </div>

    {{-- KOP SURAT (HANYA MUNCUL SAAT CETAK) --}}
    <div class="print-only-header" style="display: none; text-align: center; margin-bottom: 25px;">
        <h1 style="margin: 0; font-size: 24px; color: #000;">Rumah Makan Vegetarian Padmamula</h1>
        <p style="margin: 5px 0 15px 0; font-size: 16px;">Laporan Rekapitulasi Transaksi dan Penjualan Pelanggan</p>
        <div style="border-bottom: 2px solid #000; padding-bottom: 10px;">
            <strong>Periode Data:</strong> {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') : 'Semua Hari' }} | 
            <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y H:i') }} WIB
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0 no-print">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex gap-2">
                    @php
                    $currentStatus = request('status');
                    $statuses = ['' => 'Semua', 'pending' => 'Pending', 'selesai' => 'Selesai', 'diubah' => 'Diubah'];
                    @endphp
                    @foreach($statuses as $val => $label)
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
                <table class="table table-hover align-middle">
                    <thead class="text-uppercase text-secondary">
                        <tr>
                            <th class="ps-3">Kode Transaksi</th>
                            <th>Waktu Transaksi</th>
                            <th class="text-center">Status Buku</th>
                            <th class="text-right pe-4">Total Harga</th>
                            <th class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $totalOmzetSelesai = 0; 
                            $statusColors = ['pending' => '#fff3cd', 'selesai' => '#d1e7dd', 'batal' => '#f8d7da', 'diubah' => '#cff4fc'];
                            $statusText = ['pending' => '#664d03', 'selesai' => '#0f5132', 'batal' => '#842029', 'diubah' => '#055160'];
                        @endphp
                        @forelse($transaksi as $trx)
                        @php if($trx->status === 'selesai') $totalOmzetSelesai += $trx->total_harga; @endphp
                        <tr>
                            <td class="ps-3 fw-bold text-dark">{{ $trx->kode_transaksi }}</td>
                            <td class="text-secondary">
                                {{ $trx->created_at->format('d M Y') }}
                                <small class="text-muted d-block">{{ $trx->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill px-3 py-2" 
                                      style="background-color: {{ $statusColors[$trx->status] ?? '#e2e3e5' }} !important; 
                                             color: {{ $statusText[$trx->status] ?? '#000' }} !important; 
                                             -webkit-print-color-adjust: exact !important; 
                                             print-color-adjust: exact !important;">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="fw-bold text-success text-right pe-4">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center no-print">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.transaksi.edit', $trx->id) }}" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.transaksi.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                        @endforelse
                        <tr class="print-total-row" style="display: none;">
                            <td colspan="3" style="text-align: right; font-weight: bold; border-top: 2px solid #000; padding: 10px;">Total Nilai Buku Selesai:</td>
                            <td style="text-align: right; font-weight: bold; border-top: 2px solid #000; border-bottom: 3px double #000; padding: 10px;" class="pe-4">Rp {{ number_format($totalOmzetSelesai, 0, ',', '.') }}</td>
                            <td class="no-print"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .container-fluid, .container-fluid * { visibility: visible; }
        .container-fluid { position: absolute; left: 0; top: 0; width: 100%; }
        
        .no-print { display: none !important; }
        .print-only-header { display: block !important; }
        .print-total-row { display: table-row !important; }
        
        .card, .card-body { border: none !important; box-shadow: none !important; }
        .table { border: 1px solid #000 !important; width: 100% !important; border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #000 !important; padding: 10px !important; color: #000 !important; }
    }
</style>
@endsection