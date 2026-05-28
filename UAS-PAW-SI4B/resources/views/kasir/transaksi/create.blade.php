@extends('kasir.layouts.kasir')

@section('title', 'Transaksi Baru')
@section('page-title', 'Buat Transaksi Baru')

@push('styles')
<style>
    .menu-card { cursor: pointer; transition: .2s; border: 2px solid transparent; }
    .menu-card:hover { border-color: #66bb6a; transform: translateY(-2px); }
    .keranjang-item { background: #f9f9f9; border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; }
    .panel-kanan { position: sticky; top: 24px; }
</style>
@endpush

@section('content')
{{-- FORM WAJIB MENAMBAHKAN enctype UNTUK UPLOAD/IMAGE HANDLING --}}
<form action="{{ route('kasir.transaksi.store') }}" method="POST" id="formTransaksi" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        {{-- Kiri: Menu --}}
        <div class="col-lg-8">
            @forelse ($categories as $category)
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-success">{{ $category->icon ?? '🍽️' }} {{ $category->name }}</h6>
                    <div class="row g-3">
                        @foreach ($category->menus as $menu)
                        <div class="col-md-4">
                            <div class="card menu-card h-100" onclick="tambahItem({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})">
                                
                                {{-- PERBAIKAN LOGIKA GAMBAR --}}
                                @php
                                    $imagePath = $menu->image_url;
                                    // Memastikan path benar dan memiliki fallback jika gambar tidak ada
                                    $src = ($imagePath) ? asset('storage/' . ltrim($imagePath, '/')) : 'https://placehold.co/120x120?text=No+Img';
                                @endphp
                                <img src="{{ $src }}" 
                                     class="card-img-top" 
                                     style="height:120px; object-fit:cover;" 
                                     alt="{{ $menu->name }}" 
                                     onerror="this.src='https://placehold.co/120x120?text=No+Img';">
                                
                                <div class="card-body p-2">
                                    <div class="fw-semibold small mt-1">{{ $menu->name }}</div>
                                    <div class="text-success fw-bold mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
                <div class="alert alert-info">Belum ada menu tersedia.</div>
            @endforelse
        </div>

        {{-- Kanan: Keranjang --}}
        <div class="col-lg-4">
            <div class="card panel-kanan">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-cart3 me-2"></i>Keranjang</h6>
                    <div id="keranjang-list"><p class="text-muted text-center small py-3">Klik menu untuk menambahkan</p></div>
                    <hr />
                    <div class="d-flex justify-content-between fw-bold fs-6 mb-3">
                        <span>Total</span>
                        <span id="label-total">Rp 0</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Metode Pembayaran</label>
                        <select name="metode_bayar" id="metode_bayar" class="form-select form-select-sm" onchange="updatePembayaran()" required>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah Bayar (Rp)</label>
                        <input type="number" name="total_bayar" id="input-bayar" class="form-control form-control-sm" required />
                    </div>

                    <div id="hidden-items"></div>

                    <button type="submit" class="btn btn-success w-100" id="btn-bayar" disabled>
                        <i class="bi bi-check-circle me-1"></i> Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let keranjang = {}, totalGlobal = 0;

    function fmt(n) { return new Intl.NumberFormat('id-ID').format(n); }

    function tambahItem(id, nama, harga) {
        keranjang[id] ? keranjang[id].qty++ : (keranjang[id] = { nama, harga, qty: 1 });
        renderKeranjang();
    }

    function ubahQty(id, delta) {
        if (!keranjang[id]) return;
        keranjang[id].qty += delta;
        if (keranjang[id].qty <= 0) delete keranjang[id];
        renderKeranjang();
    }

    function updatePembayaran() {
        const metode = document.getElementById('metode_bayar').value;
        const input = document.getElementById('input-bayar');
        if (metode === 'qris' || metode === 'transfer') {
            input.value = totalGlobal;
            input.readOnly = true;
        } else {
            input.readOnly = false;
            input.value = '';
        }
    }

    function renderKeranjang() {
        const list = document.getElementById('keranjang-list');
        const hidden = document.getElementById('hidden-items');
        const btnBayar = document.getElementById('btn-bayar');
        list.innerHTML = hidden.innerHTML = '';
        totalGlobal = 0; let idx = 0;

        for (const [id, item] of Object.entries(keranjang)) {
            totalGlobal += (item.harga * item.qty);
            list.innerHTML += `
            <div class="keranjang-item">
                <div class="fw-semibold small">${item.nama}</div>
                <div class="text-muted small">Rp ${fmt(item.harga)} x ${item.qty}</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0" onclick="ubahQty(${id},-1)">−</button>
                    <span class="fw-bold">${item.qty}</span>
                    <button type="button" class="btn btn-sm btn-outline-success py-0" onclick="ubahQty(${id},1)">+</button>
                </div>
            </div>`;
            hidden.innerHTML += `<input type="hidden" name="items[${idx}][menu_id]" value="${id}">
                                 <input type="hidden" name="items[${idx}][qty]" value="${item.qty}">`;
            idx++;
        }

        btnBayar.disabled = (Object.keys(keranjang).length === 0);
        document.getElementById('label-total').textContent = 'Rp ' + fmt(totalGlobal);
        updatePembayaran();
    }
</script>
@endpush