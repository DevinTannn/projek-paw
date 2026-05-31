<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - {{ $transaksi->kode_transaksi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Courier New', Courier, monospace; }
        .struk-card { max-width: 400px; margin: 40px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header-struk { text-align: center; border-bottom: 2px dashed #ccc; padding-bottom: 15px; margin-bottom: 15px; }
        .item-list { font-size: 0.9rem; }
        .total-section { border-top: 2px dashed #ccc; padding-top: 10px; margin-top: 10px; }
        
        /* Sembunyikan tombol saat dicetak */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="struk-card">
        <div class="header-struk">
            <h4 class="fw-bold">PADMAMULA</h4>
            <p class="mb-0 small">Terima kasih atas pesanan Anda!</p>
            <p class="small text-muted">{{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="mb-3">
            <p class="mb-1 small"><strong>Nama:</strong> {{ $transaksi->customer_name }}</p>
            <p class="mb-1 small"><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</p>
            <p class="mb-1 small"><strong>Meja:</strong> {{ $transaksi->table_number ?? '-' }}</p>
        </div>

        <div class="item-list">
            @foreach($transaksi->detailTransaksi as $item)
                <div class="d-flex justify-content-between mb-1">
                    <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="total-section">
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <p class="small text-center mt-3 text-muted">Metode: {{ strtoupper($transaksi->metode_bayar) }}</p>
        </div>

        <div class="text-center mt-4 no-print">
            <a href="{{ route('customer.menu.index') }}" class="btn btn-success w-100 mb-2">Kembali ke Menu</a>
            
            <a href="{{ route('customer.struk', ['id' => $transaksi->id, 'download' => 'true']) }}" 
               class="btn btn-outline-primary w-100 mb-2">
               <i class="bi bi-download"></i> Download PDF
            </a>
            
            <button onclick="window.print()" class="btn btn-outline-secondary w-100">
                <i class="bi bi-printer"></i> Cetak Struk
            </button>
        </div>
    </div>
</div>

</body>
</html>