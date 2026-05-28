<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Struk {{ $transaksi->kode_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace; font-size: 12px;
            background: #f5f5f5; display: flex; justify-content: center; padding: 20px;
        }
        .struk { background: #fff; width: 300px; padding: 20px 16px; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        .center { text-align: center; }
        .bold   { font-weight: bold; }
        .line   { border-top: 1px dashed #999; margin: 8px 0; }
        .row    { display: flex; justify-content: space-between; }
        .nama-toko { font-size: 15px; font-weight: bold; }
        .item-row  { display: flex; justify-content: space-between; margin: 6px 0; }
        .item-row .kiri  { flex: 1; }
        .item-row .kanan { text-align: right; white-space: nowrap; }
        .btn-cetak {
            display: block; width: 100%; margin-top: 16px; padding: 8px;
            background: #2e7d32; color: #fff; border: none; border-radius: 4px;
            font-size: 13px; cursor: pointer;
        }
        .btn-back {
            display: block; width: 100%; margin-top: 8px; padding: 8px;
            background: #eee; color: #333; border: none; border-radius: 4px;
            font-size: 13px; text-align: center; text-decoration: none;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .struk { box-shadow: none; }
            .btn-cetak, .btn-back { display: none; }
        }
    </style>
</head>
<body>
<div class="struk">

    <div class="center" style="margin-bottom: 8px;">
        <div class="nama-toko">🌿 Rumah Makan Vegetarian</div>
        <div style="color:#555">Sehat, Lezat, Berkah</div>
    </div>
    <div class="line"></div>

    <div class="row"><span>No.</span><span class="bold">{{ $transaksi->kode_transaksi }}</span></div>
    <div class="row"><span>Tanggal</span><span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span></div>
    {{-- Menggunakan null-safe operator agar tidak error jika kasir kosong --}}
    <div class="row"><span>Kasir</span><span>{{ $transaksi->kasir?->name ?? 'Admin' }}</span></div>
    <div class="line"></div>

    @foreach ($transaksi->details as $detail)
    <div class="item-row">
        <div class="kiri">
            {{-- Menampilkan Nama Menu & Deskripsi jika ada --}}
            <div class="bold">{{ $detail->menu?->name ?? 'Menu tidak ditemukan' }}</div> 
            @if(!empty($detail->menu?->description))
                <div style="color:#777; font-size: 10px;">{{ $detail->menu->description }}</div>
            @endif
            <div style="color:#555">{{ $detail->qty }} × Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
        </div>
        <div class="kanan bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
    </div>
    @endforeach

    <div class="line"></div>

    <div class="row bold" style="font-size:13px">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
    </div>
    <div class="row" style="margin-top:4px">
        <span>Bayar ({{ strtoupper($transaksi->metode_bayar ?? 'TUNAI') }})</span>
        <span>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
    </div>
    <div class="row">
        <span>Kembalian</span>
        <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
    </div>

    @if ($transaksi->catatan)
    <div class="line"></div>
    <div>📝 {{ $transaksi->catatan }}</div>
    @endif

    <div class="line"></div>
    <div class="center" style="color:#555">Terima kasih sudah berkunjung!</div>
    <div class="center" style="color:#555">Semoga makanannya berkah 🌱</div>

    <button class="btn-cetak" onclick="window.print()">🖨️ Cetak Struk</button>
    <a href="{{ route('kasir.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>

</div>
</body>
</html>