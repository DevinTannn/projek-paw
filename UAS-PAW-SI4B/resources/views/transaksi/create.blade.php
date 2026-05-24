@extends('kasir.layouts.kasir')

@section('title', 'Transaksi Baru')
@section('page-title', 'Buat Transaksi Baru')

@push('styles')
<style>
    .menu-card { cursor: pointer; transition: .2s; border: 2px solid transparent; }
    .menu-card:hover { border-color: #66bb6a; transform: translateY(-2px); }
    .menu-card.dipilih { border-color: #2e7d32; background: #f1f8e9; }
    .keranjang-item { background: #f9f9f9; border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; }
    .panel-kanan { position: sticky; top: 24px; }
</style>
@endpush

@section('content')
<form action="{{ route('kasir.transaksi.simpan') }}" method="POST" id="formTransaksi">
@csrf

<div class="row g-4">

    {{-- ── Kiri: Menu dikelompokkan per Kategori (struktur teman) ── --}}
    <div class="col-lg-8">

        @forelse ($categories as $category)
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-success">
                    {{-- icon emoji dari kolom teman --}}
                    {{ $category->icon }} {{ $category->name }}
                </h6>

                @if ($category->menus->isEmpty())
                    <p class="text-muted small">Belum ada menu di kategori ini.</p>
                @else
                <div class="row g-3">
                    @foreach ($category->menus as $menu)
                    <div class="col-md-4">
                        {{-- name & price = kolom teman; image_url = kolom teman --}}
                        <div class="card menu-card h-100"
                             onclick="tambahItem({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})">

                            @if ($menu->image_url)
                                <img src="{{ $menu->image_url }}"
                                     class="card-img-top"
                                     style="height:120px;object-fit:cover;"
                                     alt="{{ $menu->name }}">
                            @else
                                <div class="d-flex justify-content-center align-items-center bg-light"
                                     style="height:120px;">
                                    <i class="bi bi-image text-muted fs-2"></i>
                                </div>
                            @endif

                            <div class="card-body p-2">
                                {{-- badge dari kolom teman --}}
                                @if ($menu->is_recommended)
                                    <span class="badge bg-danger-subtle text-danger" style="font-size:.65rem">REKOMENDASI</span>
                                @elseif ($menu->is_bestseller)
                                    <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size:.65rem">BEST SELLER</span>
                                @endif
                                <div class="fw-semibold small mt-1">{{ $menu->name }}</div>
                                @if ($menu->description)
                                    <div class="text-muted" style="font-size:.7rem">
                                        {{ Str::limit($menu->description, 45) }}
                                    </div>
                                @endif
                                <div class="text-success fw-bold mt-1">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-exclamation-circle fs-1 d-block mb-2"></i>
                    Belum ada data kategori. Pastikan teman sudah mengisi tabel <code>categories</code> dan <code>menus</code>.
                </div>
            </div>
        @endforelse

    </div>

    {{-- ── Kanan: Keranjang (tidak berubah dari versi asli kasir) ── --}}
    <div class="col-lg-4">
        <div class="card panel-kanan">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-cart3 me-2"></i>Keranjang</h6>

                <div id="keranjang-list">
                    <p class="text-muted text-center small py-3">
                        Klik menu untuk menambahkan
                    </p>
                </div>

                <hr />

                <div class="d-flex justify-content-between fw-bold fs-6 mb-3">
                    <span>Total</span>
                    <span id="label-total">Rp 0</span>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Metode Pembayaran</label>
                    <select name="metode_bayar" class="form-select form-select-sm" required>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Jumlah Bayar (Rp)</label>
                    <input type="number" name="total_bayar" id="input-bayar"
                           class="form-control form-control-sm" min="0" placeholder="0" required />
                    <div class="mt-1 small fw-semibold" id="label-kembalian"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control form-control-sm" rows="2"
                              placeholder="Misal: tidak pedas, dll."></textarea>
                </div>

                <div id="hidden-items"></div>

                <button type="submit" class="btn btn-hijau w-100" id="btn-bayar" disabled>
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
let keranjang = {};

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

function renderKeranjang() {
    const list     = document.getElementById('keranjang-list');
    const hidden   = document.getElementById('hidden-items');
    const btnBayar = document.getElementById('btn-bayar');

    list.innerHTML = hidden.innerHTML = '';
    let total = 0, idx = 0;

    for (const [id, item] of Object.entries(keranjang)) {
        const sub = item.harga * item.qty;
        total += sub;
        list.innerHTML += `
        <div class="keranjang-item d-flex justify-content-between align-items-start">
            <div>
                <div class="fw-semibold small">${item.nama}</div>
                <div class="text-muted" style="font-size:.75rem">Rp ${fmt(item.harga)} × ${item.qty}</div>
                <div class="text-success fw-bold small">Rp ${fmt(sub)}</div>
                <div class="mt-1">
                    <input type="text" class="form-control form-control-sm py-0"
                           style="font-size:.75rem;height:22px" id="catatan_${id}" placeholder="catatan item..." />
                </div>
            </div>
            <div class="d-flex align-items-center gap-1 ms-2">
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" onclick="ubahQty(${id},-1)">−</button>
                <span class="fw-bold">${item.qty}</span>
                <button type="button" class="btn btn-sm btn-outline-success py-0 px-1" onclick="ubahQty(${id},1)">+</button>
            </div>
        </div>`;
        hidden.innerHTML += `
            <input type="hidden" name="items[${idx}][menu_id]" value="${id}" />
            <input type="hidden" name="items[${idx}][qty]"     value="${item.qty}" />`;
        idx++;
    }

    if (!Object.keys(keranjang).length) {
        list.innerHTML = '<p class="text-muted text-center small py-3">Klik menu untuk menambahkan</p>';
        btnBayar.disabled = true;
    } else {
        btnBayar.disabled = false;
    }

    document.getElementById('label-total').textContent = 'Rp ' + fmt(total);
    hitungKembalian(total);
}

function hitungKembalian(total) {
    const bayar = parseFloat(document.getElementById('input-bayar').value) || 0;
    const kembalian = bayar - total;
    const label = document.getElementById('label-kembalian');
    if (bayar > 0) {
        label.textContent = kembalian >= 0 ? `Kembalian: Rp ${fmt(kembalian)}` : `⚠ Kurang Rp ${fmt(Math.abs(kembalian))}`;
        label.className = 'mt-1 small fw-semibold ' + (kembalian >= 0 ? 'text-success' : 'text-danger');
    } else { label.textContent = ''; }
}

document.getElementById('input-bayar').addEventListener('input', () => {
    const total = Object.values(keranjang).reduce((s, i) => s + i.harga * i.qty, 0);
    hitungKembalian(total);
});

document.getElementById('formTransaksi').addEventListener('submit', function () {
    let idx = 0;
    for (const id of Object.keys(keranjang)) {
        const val = document.getElementById('catatan_' + id)?.value || '';
        document.getElementById('hidden-items').innerHTML +=
            `<input type="hidden" name="items[${idx}][catatan_item]" value="${val}" />`;
        idx++;
    }
});

function fmt(n) { return new Intl.NumberFormat('id-ID').format(n); }
</script>
@endpush
