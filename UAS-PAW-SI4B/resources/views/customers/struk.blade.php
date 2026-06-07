<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - {{ $transaksi->kode_transaksi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .order-card { max-width: 450px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .header-section { text-align: center; margin-bottom: 25px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 15px; margin-bottom: 20px; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 10px; color: #333; }
        .total-row { border-top: 1px solid #eee; padding-top: 15px; margin-top: 10px; display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; color: #fd7e14; }
    </style>
</head>
<body>

<div class="container">
    <div class="order-card">
        <div class="header-section">
            <h3 class="fw-bold text-dark">Pesanan Berhasil!</h3>
            <p class="text-muted small">Terima kasih telah memesan di PADMAMULA</p>
        </div>

        <div class="info-box">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Nama</span>
                <span class="fw-semibold">{{ $transaksi->customer_name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Kode</span>
                <span class="fw-semibold">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Meja</span>
                <span class="fw-semibold">{{ $transaksi->table_number ?? '-' }}</span>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold mb-3">Pesanan Anda:</h6>
            @foreach($transaksi->detailTransaksi as $item)
                <div class="item-row">
                    <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="total-row">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>
        
        <p class="small text-center mt-3 text-muted">Metode Pembayaran: {{ strtoupper($transaksi->metode_bayar) }}</p>

        <div class="mt-4">
            <a href="{{ route('customer.struk', ['id' => $transaksi->id, 'download' => 'true']) }}" 
               class="btn btn-primary w-100 mb-2 py-2 rounded-3">
                <i class="bi bi-download"></i> Download PDF
            </a>
            
            <a href="{{ route('customer.menu.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                Kembali ke Menu
            </a>
        </div>
    </div>
</div>

</body>
</html>