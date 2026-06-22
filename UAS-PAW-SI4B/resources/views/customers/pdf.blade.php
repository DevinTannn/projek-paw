<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 13px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px dashed #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #ea580c; }
        .info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { border-bottom: 1px solid #ddd; padding: 10px; text-align: left; background-color: #f9f9f9; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; font-size: 15px; }
        .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">PADMAMULA</div>
        <p>Terima kasih telah memesan di tempat kami!</p>
    </div>

    <div class="info">
        <p><strong>Kode Trx:</strong> {{ $transaksi->kode_transaksi }}</p>
        <p><strong>Pelanggan:</strong> {{ $transaksi->customer_name }}</p>
        <p><strong>Nomor Meja:</strong> {{ $transaksi->table_number }}</p>
        <p><strong>Metode Bayar:</strong> {{ strtoupper($transaksi->metode_bayar) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $item)
            <tr>
                <td>{{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">TOTAL:</td>
                <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        <p>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
    </div>
</body>
</html>