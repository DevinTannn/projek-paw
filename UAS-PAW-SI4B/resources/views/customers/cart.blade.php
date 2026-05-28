@extends('App.customer-master')

@section('title', 'Keranjang Saya')

@section('content')
<div class="container py-4 px-3" style="max-width: 600px; margin: auto;">
    
    <a href="/" class="inline-flex items-center text-slate-500 hover:text-orange-500 font-bold mb-6 transition-colors">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Menu
    </a>

    <h1 class="text-xl font-bold mb-4">Keranjang Pesanan</h1>
    
    @if(isset($menus) && count($menus) > 0)
        <div class="space-y-3">
            @foreach($menus as $menu)
                @php $qty = $cart[$menu->id] ?? 0; @endphp
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-slate-800">{{ $menu->name }}</h3>
                        <p class="text-xs text-slate-500">Rp {{ number_format($menu->price, 0, ',', '.') }} x {{ $qty }}</p>
                    </div>
                    <p class="font-bold text-orange-600 text-sm">
                        Rp {{ number_format($menu->price * $qty, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <button type="button" onclick="openModal()" class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-orange-700 transition-all shadow-lg active:scale-[0.98]">
                PESAN SEKARANG
            </button>
        </div>
    @else
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm text-center">
            <p class="text-slate-400">Belum ada pesanan di keranjang.</p>
        </div>
    @endif
</div>

{{-- MODAL PESANAN --}}
<div id="orderModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 backdrop-blur-sm transition-all">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl animate-fade-in">
        <h2 class="text-xl font-bold mb-4 text-slate-800">Detail Pesanan</h2>
        <form action="{{ route('cart.store') }}" method="POST" id="orderForm">
            @csrf
            <div class="space-y-4">
                {{-- Input Nomor Meja --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Nomor Meja</label>
                    <input type="number" name="table_number" id="table_number" value="{{ session('table_number') }}" 
                           class="w-full border border-slate-200 rounded-lg p-2 mt-1" placeholder="Kosongkan jika tidak ada">
                </div>

                {{-- Input Nama Pemesan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Nama Pemesan <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" required 
                           class="w-full border border-slate-200 rounded-lg p-2 mt-1" placeholder="Masukkan nama Anda">
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                    <select name="metode_bayar" class="w-full border border-slate-200 rounded-lg p-2 mt-1 bg-white">
                        <option value="tunai">Tunai / Bayar di Kasir</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Catatan</label>
                    <textarea name="catatan" class="w-full border border-slate-200 rounded-lg p-2 mt-1 h-20"></textarea>
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="closeModal()" class="w-full bg-slate-100 py-2.5 rounded-lg font-bold">Batal</button>
                <button type="submit" class="w-full bg-orange-600 text-white py-2.5 rounded-lg font-bold">Kirim Pesanan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('orderModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('orderModal').classList.add('hidden'); }

    // Validasi sederhana: pastikan minimal ada nama
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const name = document.getElementById('customer_name').value;
        if (!name.trim()) {
            e.preventDefault();
            alert("Nama Pemesan wajib diisi!");
        }
    });
</script>
@endsection