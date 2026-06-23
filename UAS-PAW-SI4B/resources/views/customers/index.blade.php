@extends('App.customer-master')
@section('content')
<div class="container py-6 px-4" style="max-width: 600px; margin: auto;">
    
    <input type="text" id="search-menu" onkeyup="searchMenu()" class="w-full px-5 py-3 rounded-full border border-slate-300 shadow-sm mb-6 outline-orange-500" placeholder="Cari menu favorit...">

    <div class="flex justify-center gap-3 mb-8">
        <button onclick="filterCategory('all', this)" class="filter-btn px-6 py-2 rounded-full bg-orange-500 text-white font-bold border border-orange-500">Semua</button>
        <button onclick="filterCategory('makanan', this)" class="filter-btn px-6 py-2 rounded-full bg-white text-slate-600 border border-slate-300 font-bold hover:border-orange-500">Makanan</button>
        <button onclick="filterCategory('minuman', this)" class="filter-btn px-6 py-2 rounded-full bg-white text-slate-600 border border-slate-300 font-bold hover:border-orange-500">Minuman</button>
    </div>

    <div id="menu-container" class="space-y-4">
        @foreach($categories as $category)
            @php $catName = strtolower($category->name); @endphp
            @foreach($category->menus as $menu)
                @php 
                    $qty = session('cart.'.$menu->id, 0); 
                    $imagePath = $menu->image_url;
                    if ($imagePath && !str_contains($imagePath, 'menus/')) {
                        $imagePath = 'menus/' . $imagePath;
                    }
                @endphp
                
                {{-- Tambahkan class 'relative' agar label bisa ditempel di atasnya --}}
                <div class="menu-item relative category-{{ $catName }} flex items-center bg-white p-4 rounded-3xl border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.08)] gap-4 transition-transform hover:scale-[1.01]">
                    
                    {{-- LABEL BEST SELLER --}}
                    @if(in_array($menu->id, $bestSellerIds))
                        <div class="absolute -top-1 -left-1 bg-yellow-400 text-slate-900 text-[10px] font-extrabold px-3 py-1 rounded-br-xl rounded-tl-3xl z-10 shadow-lg">
                            BEST SELLER
                        </div>
                    @endif

                    <img src="{{ $menu->image_url ? asset('storage/'.$imagePath) : 'https://placehold.co/128x128' }}" 
                         class="w-24 h-24 rounded-2xl object-cover shadow-sm"
                         onerror="this.onerror=null; this.src='https://placehold.co/128x128';">
                    
                    <div class="flex-grow">
                        <h3 class="font-extrabold text-slate-800 text-lg">{{ $menu->name }}</h3>
                        @if(!empty($menu->description))
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed line-clamp-2 max-w-[200px]">{{ $menu->description }}</p>
                        @endif
                        <p class="text-orange-600 font-bold text-sm mt-1.5 bg-orange-50 px-2 py-0.5 rounded-lg w-max">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    <div id="controls-{{ $menu->id }}">
                        @if($qty > 0)
                            <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-full px-2 py-1">
                                <button onclick="updateCart({{ $menu->id }}, {{ $qty - 1 }})" class="text-slate-400 hover:text-orange-500 font-bold w-7 h-7 flex items-center justify-center">-</button>
                                <span class="font-bold text-slate-800 w-6 text-center">{{ $qty }}</span>
                                <button onclick="updateCart({{ $menu->id }}, {{ $qty + 1 }})" class="text-orange-500 font-bold w-7 h-7 flex items-center justify-center">+</button>
                            </div>
                        @else
                            <button onclick="updateCart({{ $menu->id }}, 1)" class="bg-orange-500 text-white px-5 py-2.5 rounded-full font-bold text-sm shadow-md hover:bg-orange-600 transition-all active:scale-95">TAMBAH</button>
                        @endif
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>

@php $totalItems = array_sum(session()->get('cart', [])); @endphp
<div id="floating-cart-bar" class="fixed bottom-6 left-0 right-0 z-[990] px-4 {{ $totalItems > 0 ? '' : 'hidden' }}">
    <a href="{{ route('cart.index') }}" class="mx-auto max-w-[300px] bg-orange-600 text-white py-3.5 px-6 rounded-full shadow-2xl flex items-center justify-between hover:bg-orange-700 transition-all active:scale-95">
        <span class="font-bold">Cek Pesanan Anda</span>
        <span class="bg-white text-orange-600 font-bold px-3 py-1 rounded-full text-xs">{{ $totalItems }} Item</span>
    </a>
</div>
@endsection

@push('scripts')
<script>
    function searchMenu() {
        let input = document.getElementById('search-menu').value.toLowerCase();
        let menuItems = document.querySelectorAll('.menu-item');

        menuItems.forEach(function(item) {
            // Mencari tag <h3> yang berisi nama menu di dalam div menu-item
            let menuName = item.querySelector('h3').textContent.toLowerCase();
            
            if (menuName.includes(input)) {
                item.style.display = 'flex'; // Tampilkan jika cocok
            } else {
                item.style.display = 'none'; // Sembunyikan jika tidak cocok
            }
        });
    }
    
    function updateCart(id, qty) {
        fetch('/keranjang/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ id: id, qty: qty })
        }).then(() => window.location.reload());
    }
    function filterCategory(cat, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => { b.classList.replace('bg-orange-500', 'bg-white'); b.classList.replace('text-white', 'text-slate-600'); b.classList.replace('border-orange-500', 'border-slate-300'); });
        btn.classList.replace('bg-white', 'bg-orange-500'); btn.classList.replace('text-slate-600', 'text-white'); btn.classList.replace('border-slate-300', 'border-orange-500');
        document.querySelectorAll('.menu-item').forEach(i => i.style.display = (cat === 'all' || i.classList.contains('category-' + cat)) ? 'flex' : 'none');
    }
</script>
@endpush