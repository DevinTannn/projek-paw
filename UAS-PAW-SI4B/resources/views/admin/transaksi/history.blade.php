@extends('admin.layout.admin')
@section('title', 'Histori Transaksi')

@section('content')
<div class="container-fluid py-4 section-to-print">
    {{-- BAGIAN JUDUL & TOMBOL AKSI (Disembunyikan saat print via class no-print) --}}
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

    {{-- KOP SURAT FORMAL PERUSAHAAN (Hanya muncul saat dicetak ke printer) --}}
    <div class="print-only-header">
        <h1>Rumah Makan Vegetarian Padmamula</h1>
        <p class="subtitle">Laporan Rekapitulasi Transaksi dan Penjualan Pelanggan</p>

        <table class="print-meta-table">
            <tr>
                <td style="width: 15%;"><strong>Periode Data</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;">{{ request('tanggal') ?
                    \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') : 'Semua Hari' }}</td>
                <td style="width: 15%;"><strong>Tanggal Cetak</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;">{{ \Carbon\Carbon::now()->translatedFormat('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Status Buku</strong></td>
                <td>:</td>
                <td>{{ request('status') ? ucfirst(request('status')) : 'Semua Status' }}</td>
                <td><strong>Format</strong></td>
                <td>:</td>
                <td>Dokumen Cetak Fisik</td>
            </tr>
        </table>
    </div>

    <div class="card shadow-lg border-0 rounded-4 main-card-print">
        <div class="card-header bg-white border-0 pt-4 pb-0 no-print">
            {{-- Filter Area (Disembunyikan saat print) --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex gap-2">
                    @php
                    $currentStatus = request('status');
                    $statuses = [
                    '' => 'Semua',
                    'pending' => 'Pending',
                    'selesai' => 'Selesai',
                    'diubah' => 'Diubah'
                    ];
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
                    <input type="date" name="tanggal" class="form-control form-control-sm rounded-pill"
                        value="{{ request('tanggal') }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">Filter</button>
                    @if(request('status') || request('tanggal'))
                    <a href="{{ route('admin.transaksi.history') }}"
                        class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card-body p-4 body-card-print">
            <div class="table-responsive" style="overflow-x: visible !important;">
                <table class="table table-hover align-middle border-separate table-print"
                    style="border-spacing: 0 10px;">
                    <thead class="text-uppercase text-secondary">
                        <tr>
                            <th class="ps-3">Kode Transaksi</th>
                            <th>Waktu Transaksi</th>
                            <th style="text-align: center;">Status Buku</th>
                            <th style="text-align: right;" class="pe-3">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalOmzetSelesai = 0; @endphp
                        @forelse($transaksi as $trx)
                        @php
                        if($trx->status === 'selesai') {
                        $totalOmzetSelesai += $trx->total_harga;
                        }
                        @endphp
                        <tr class="bg-light border-0 shadow-sm row-print" style="border-radius: 10px;">
                            <td class="ps-3 fw-bold text-dark">{{ $trx->kode_transaksi }}</td>
                            <td class="text-secondary">
                                {{ $trx->created_at->format('d M Y') }}
                                <small class="text-muted d-block window-time">{{ $trx->created_at->format('H:i') }}
                                    WIB</small>
                            </td>
                            <td class="text-center">
                                @php
                                $statusColors = [
                                'pending' => 'bg-warning-subtle text-warning',
                                'selesai' => 'bg-success-subtle text-success',
                                'batal' => 'bg-danger-subtle text-danger',
                                'diubah' => 'bg-info-subtle text-info'
                                ];
                                @endphp
                                <span
                                    class="badge {{ $statusColors[$trx->status] ?? 'bg-secondary-subtle' }} rounded-pill px-3 py-2 badge-print">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="fw-bold text-success text-right pe-3">Rp {{ number_format($trx->total_harga, 0,
                                ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Tidak ada transaksi yang sesuai dengan
                                filter saat ini.</td>
                        </tr>
                        @endforelse

                        {{-- Baris akumulasi total --}}
                        <tr class="print-total-row">
                            <td colspan="3"
                                style="text-align: right; font-weight: bold; border-top: 1px solid #000; padding: 8px 0;">
                                Total Nilai Buku Selesai:</td>
                            <td style="text-align: right; font-weight: bold; border-top: 1px solid #000; border-bottom: 3px double #000; padding: 8px 0;"
                                class="pe-3">
                                Rp {{ number_format($totalOmzetSelesai, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* ==========================================
       STYLE INTERFACE LAYAR WEB NORMAL
       ========================================== */
    .card {
        border-radius: 1.5rem !important;
    }

    .table-hover tbody tr {
        border-radius: 10px;
        transition: 0.2s;
    }

    .table-hover tbody tr:hover {
        transform: scale(1.005);
        background-color: #f1f3f5 !important;
        cursor: pointer;
    }

    .badge {
        font-weight: 600;
        font-size: 0.75rem;
    }

    .bg-success-subtle {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .bg-warning-subtle {
        background-color: #fff3cd;
        color: #664d03;
    }

    .bg-danger-subtle {
        background-color: #f8d7da;
        color: #842029;
    }

    .bg-info-subtle {
        background-color: #cff4fc;
        color: #055160;
    }

    .print-only-header,
    .print-total-row {
        display: none;
    }

    .text-right {
        text-align: right;
    }

    /* =========================================================================
       CSS MEDIA PRINT REVISI SECARA SPESIFIK & TARGETED
       ========================================================================= */
    @media print {

        /* 1. Sembunyikan secara manual komponen sidebar/navbar agar tidak bocor */
        .no-print,
        nav,
        aside,
        .sidebar,
        .main-sidebar,
        .navbar,
        .main-header,
        .card-header,
        button {
            display: none !important;
            height: 0 !important;
            width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 2. Paksa pembungkus utama admin agar tetap memunculkan kontainer data */
        html,
        body,
        .wrapper,
        .content-wrapper,
        .content,
        .main-content {
            background-color: #ffffff !important;
            color: #000000 !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            position: relative !important;
        }

        /* 3. Tarik kontainer section-to-print ke posisi terluar kertas A4 */
        .section-to-print {
            display: block !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 4. Tampilkan Kop Surat Resmi */
        .print-only-header {
            display: block !important;
            text-align: center !important;
            margin-bottom: 25px !important;
            width: 100% !important;
        }

        .print-only-header h1 {
            margin: 0 !important;
            font-size: 20px !important;
            font-family: 'Times New Roman', Times, serif !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
        }

        .print-only-header .subtitle {
            margin: 5px 0 15px 0 !important;
            font-size: 11px !important;
            font-family: 'Times New Roman', Times, serif !important;
            font-style: italic !important;
            border-bottom: 3px double #000000 !important;
            padding-bottom: 8px !important;
        }

        .print-meta-table {
            width: 100% !important;
            margin-bottom: 20px !important;
            font-size: 12px !important;
            font-family: 'Times New Roman', Times, serif !important;
            text-align: left !important;
        }

        /* 5. Ubah Desain Tabel Menjadi Format Laporan Keuangan Kantor */
        .main-card-print {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .body-card-print {
            padding: 0 !important;
        }

        .table-print {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table-print th {
            border-top: 1px solid #000000 !important;
            border-bottom: 1px solid #000000 !important;
            color: #000000 !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            padding: 8px 4px !important;
            font-size: 11px !important;
            font-family: 'Times New Roman', Times, serif !important;
        }

        .table-print td {
            border-bottom: 1px dashed #cccccc !important;
            padding: 8px 4px !important;
            background: transparent !important;
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 12px !important;
        }

        .row-print {
            background: transparent !important;
            box-shadow: none !important;
        }

        .badge-print {
            background: transparent !important;
            color: #000000 !important;
            font-weight: bold !important;
            padding: 0 !important;
        }

        .window-time {
            display: inline !important;
            margin-left: 5px;
        }

        .print-total-row {
            display: table-row !important;
        }
    }
</style>
@endsection