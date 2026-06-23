<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Penjualan - RM Padmamula</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            font-size: 12px;
            line-height: 1.5;
            margin: 20px;
        }

        /* Kop Surat Dokumen / Judul Utama */
        .company-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
        }

        .company-header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-header p {
            margin: 5px 0 0 0;
            font-size: 11px;
            font-style: italic;
        }

        /* Informasi Nota / Meta Data Dokumen */
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            font-size: 12px;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Blok Ringkasan Angka (Format Baris Khas Akuntansi) */
        .accounting-summary {
            width: 100%;
            margin-bottom: 30px;
            border: 1px solid #000000;
        }

        .accounting-summary th {
            background-color: #f2f2f2;
            border-bottom: 1px solid #000000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .accounting-summary td {
            padding: 10px 8px;
            font-size: 14px;
        }

        /* Tabel Utama Rincian Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
            color: #000000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 8px 5px;
            text-align: left;
        }

        .data-table td {
            padding: 8px 5px;
            border-bottom: 1px dashed #cccccc;
        }

        .data-table tr.total-row td {
            border-top: 1px solid #000000;
            border-bottom: 2px double #000000;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        /* Utilitas Teks Akuntansi */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="company-header">
        <h1>Rumah Makan Vegetarian Padmamula</h1>
        <p>Laporan Rekapitulasi Transaksi dan Penjualan Harian</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%;"><strong>Dokumen</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">Laporan Ringkasan Transaksi</td>
            <td style="width: 18%;"><strong>Tanggal Cetak</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Periode Data</strong></td>
            <td>:</td>
            <td>{{ $startDate->translatedFormat('d F Y') }} s/d {{ $endDate->translatedFormat('d F Y') }}</td>
            <td><strong>Penyaring Status</strong></td>
            <td>:</td>
            <td>{{ $statusText }}</td>
        </tr>
    </table>

    <table class="accounting-summary" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 50%; border-right: 1px solid #000000;">Kumulatif Volume Transaksi</th>
                <th style="width: 50%;">Total Nilai Pendapatan (Omzet)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border-right: 1px solid #000000;" class="fw-bold">{{ $totalTransaksi }} Record Transaksi</td>
                <td class="fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="font-size: 13px; margin-bottom: 8px; text-transform: uppercase;">Lampiran: Rincian Arus Transaksi</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Kode Transaksi</th>
                <th style="width: 30%;">Waktu Transaksi</th>
                <th style="width: 20%; text-align: center;">Status Buku</th>
                <th style="width: 25%; text-align: right;">Jumlah Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $trx)
            <tr>
                <td>{{ $trx->kode_transaksi }}</td>
                <td>{{ $trx->created_at->format('d/m/Y, H:i') }} WIB</td>
                <td class="text-center" style="font-variant: small-caps;">{{ $trx->status }}</td>
                <td class="text-right">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="3" class="text-right">Total Akumulasi Nilai Buku Selesai:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>