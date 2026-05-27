@extends('App.customer-master')
@section('title', 'Menu Padmamula')

@section('content')
<div class="container py-4 px-3" style="max-width: 600px; margin: auto;">

    {{-- Kolom Pencarian --}}
    <div class="mb-6">
        <input type="text" id="search-menu" onkeyup="searchMenu()" 
               class="w-full px-4 py-3 rounded-full border border-slate-200 shadow-sm focus:ring-2 focus:ring-orange-500 outline-none" 
               placeholder="Cari menu favorit...">
    </div>

    {{-- Tombol Filter Kategori --}}
    <div class="flex justify-center gap-3 mb-6">
        <button onclick="filterCategory('all', this)" 
                class="filter-btn px-6 py-2 rounded-full text-sm font-bold bg-orange-500 text-white shadow-md transition-all">Semua</button>
        <button onclick="filterCategory('makanan', this)" 
                class="filter-btn px-6 py-2 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 shadow-sm transition-all">Makanan</button>
        <button onclick="filterCategory('minuman', this)" 
                class="filter-btn px-6 py-2 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 shadow-sm transition-all">Minuman</button>
    </div>

    {{-- Container List Menu --}}
    <div id="menu-container" class="space-y-4">
        @foreach($categories as $category)
            @foreach($category->menus as $menu)
                @php
                    $qty = session('cart.'.$menu->id, 0);
                @endphp
                <div class="menu-item category-{{ strtolower($category->name) }} flex items-center bg-white p-3 rounded-2xl shadow-sm border border-slate-100 gap-4">
                    
                    {{-- FIX VALIDASI GAMBAR MENU: Mengambil dari storage jika ada, jika tidak pakai placeholder --}}
                    <img src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://placehold.co/80x80' }}" 
                         alt="{{ $menu->name }}" 
                         class="w-20 h-20 rounded-xl object-cover bg-slate-50 flex-shrink-0">
                    
                    {{-- Deskripsi Text Menu --}}
                    <div class="flex-grow min-w-0">
                        <h3 class="font-bold text-slate-800 text-sm truncate">{{ $menu->name }}</h3>
                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ $menu->description }}</p>
                        <p class="font-bold text-orange-600 text-sm mt-0.5">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>

                    {{-- Kontrol Aksi Tambah & Qty --}}
                    <div class="flex-shrink-0">
                        <button id="add-btn-{{ $menu->id }}" onclick="addItem({{ $menu->id }})" 
                                class="{{ $qty > 0 ? 'hidden' : '' }} bg-orange-500 text-white px-5 py-2 rounded-full text-xs font-bold hover:bg-orange-600 transition-all">
                            TAMBAH
                        </button>

                        <div id="qty-control-{{ $menu->id }}" class="{{ $qty > 0 ? '' : 'hidden' }} flex items-center gap-2 bg-slate-100 p-1 rounded-full">
                            <button onclick="updateQty({{ $menu->id }}, -1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow text-slate-600">-</button>
                            <span id="qty-val-{{ $menu->id }}" class="font-bold w-6 text-center text-sm">{{ $qty }}</span>
                            <button onclick="updateQty({{ $menu->id }}, 1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-orange-500 shadow text-white">+</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterCategory(category, btnElement) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.replace('bg-orange-500', 'bg-white');
            btn.classList.replace('text-white', 'text-slate-600');
        });
        btnElement.classList.replace('bg-white', 'bg-orange-500');
        btnElement.classList.replace('text-slate-600', 'text-white');

        document.querySelectorAll('.menu-item').forEach(item => {
            item.style.display = (category === 'all' || item.classList.contains('category-' + category)) ? 'flex' : 'none';
        });
    }

    function searchMenu() {
        let input = document.getElementById('search-menu').value.toLowerCase();
        document.querySelectorAll('.menu-item').forEach(item => {
            item.style.display = item.innerText.toLowerCase().includes(input) ? 'flex' : 'none';
        });
    }

    function addItem(id) {
        document.getElementById('add-btn-' + id).classList.add('hidden');
        document.getElementById('qty-control-' + id).classList.remove('hidden');
        document.getElementById('qty-val-' + id).innerText = 1;
        updateCartOnServer(id, 1);
    }

    function updateQty(id, delta) {
        let qtySpan = document.getElementById('qty-val-' + id);
        let newQty = parseInt(qtySpan.innerText) + delta;

        if (newQty <= 0) {
            document.getElementById('add-btn-' + id).classList.remove('hidden');
            document.getElementById('qty-control-' + id).classList.add('hidden');
            updateCartOnServer(id, 0);
        } else {
            qtySpan.innerText = newQty;
            updateCartOnServer(id, newQty);
        }
    }

    function updateCartOnServer(id, qty) {
        fetch('/keranjang/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: id, qty: qty })
        }).then(response => response.json())
          .then(data => {
              // Jika Anda ingin memperbarui jumlah total badge keranjang di navbar secara real-time:
              let cartBadge = document.getElementById('nav-cart-badge');
              if (cartBadge && data.totalItems !== undefined) {
                  cartBadge.innerText = data.totalItems;
              }
              
              // Catatan: Jika layout master membutuhkan reload session untuk sinkronisasi total, 
              // biarkan baris di bawah ini tetap aktif. Jika tidak, hapus agar performa lancar tanpa kedip.
              window.location.reload(); 
          });
    }
</script>
@endpush