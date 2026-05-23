@extends('App.customer-master')
@section('title', 'Pesan Sajian Istimewa')
@section('content')

<!-- ==================== TAMPILAN AWAL / HEADER MENU ==================== -->
<div class="menu-header-section bg-white pt-4 pb-3 mb-4 border-bottom shadow-sm">
    <div class="container px-3 text-center"> <!-- Ditengah untuk identitas resto -->
        <!-- Informasi Resto & Nomor Meja -->
        <div class="d-flex flex-column align-items-center mb-3">
            <span class="text-secondary d-block mb-1" style="font-size: 11px; text-uppercase: uppercase; letter-spacing: 0.05em;">Selamat Datang di</span>
            <h1 class="h4 fw-bolder text-dark mb-2" style="font-weight: 800;">Padmamula Resto ✨</h1>
            
            <!-- Badge Nomor Meja di Tengah -->
            <div class="bg-orange-subtle text-orange rounded-3 px-4 py-1 text-center border border-warning-subtle" style="max-width: 100px;">
                <span class="d-block text-uppercase fw-bold" style="font-size: 9px; opacity: 0.8;">Meja</span>
                <span class="fw-extrabold fs-5 lh-1" style="font-weight: 900;">05</span>
            </div>
        </div>

        <!-- Kotak Pencarian (Search Bar) di Tengah -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="input-group bg-light rounded-pill border border-light-subtle px-3 py-1 align-items-center shadow-sm">
                    <span class="text-secondary bg-transparent border-0 me-1">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 13px;"></i>
                    </span>
                    <input type="text" id="search-menu" onkeyup="filterMenu()" class="form-control bg-transparent border-0 ps-1" placeholder="Cari menu favorit Anda..." style="font-size: 13px; box-shadow: none;">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ====================================================================== -->

<div class="container px-3">
    <!-- Slider Kategori Menu (Rapi & Seimbang di Tengah) -->
    <div class="mb-4 text-center">
        <div class="d-flex gap-2 overflow-x-auto no-scrollbar py-1 justify-content-start justify-content-md-center">
            @foreach($categories as $index => $category)
                <button onclick="scrollToCategory('category-{{ $category->id }}')" 
                        class="btn {{ $index == 0 ? 'btn-orange' : 'btn-light bg-white border text-secondary' }} rounded-pill px-4 py-1.5 text-nowrap" 
                        style="font-size: 11px; font-weight: {{ $index == 0 ? '700' : '600' }};">
                    {{ $category->icon }} {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Looping Kategori & Menu dari Database -->
    @foreach($categories as $category)
        <div id="category-{{ $category->id }}" class="category-section mb-5">
            <!-- Judul Kategori Di Tengah Halaman -->
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3 border-bottom pb-2">
                <span class="fs-4">{{ $category->icon }}</span>
                <h3 class="h6 text-dark mb-0 text-uppercase tracking-wider" style="font-weight: 800; letter-spacing: 0.05em;">{{ $category->name }}</h3>
            </div>
            
            <!-- Ditambahkan 'justify-content-center' agar jajaran kartu menu berada di tengah -->
            <div class="row g-3 justify-content-center">
                @if($category->menus->isEmpty())
                    <div class="col-12 text-center py-3 text-muted" style="font-size: 12px;">
                        Belum ada menu di kategori ini.
                    </div>
                @else
                    @foreach($category->menus as $menu)
                        <!-- Pada layar besar (col-lg-5 atau col-md-6) posisi kartu akan dijejer seimbang -->
                        <div class="col-12 col-md-6 col-lg-5 menu-item" data-name="{{ strtolower($menu->name) }}">
                            <div class="menu-card p-3 shadow-sm bg-white rounded-3 border">
                                <div class="row align-items-center g-2">
                                    <!-- Sisi Kiri: Detail Menu -->
                                    <div class="col-8 col-sm-9 d-flex flex-column justify-content-between">
                                        <div>
                                            @if($menu->is_recommended)
                                                <span class="badge bg-danger-subtle text-danger fw-bold mb-1.5" style="font-size: 8px; padding: 3px 6px;">REKOMENDASI</span>
                                            @elseif($menu->is_bestseller)
                                                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold mb-1.5" style="font-size: 8px; padding: 3px 6px;">BEST SELLER</span>
                                            @endif
                                            
                                            <h4 class="h6 text-dark mb-1" style="font-size: 14px; font-weight: 700;">{{ $menu->name }}</h4>
                                            <p class="text-secondary mb-0 text-truncate-2" style="font-size: 11px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; white-space: normal;">
                                                {{ $menu->description }}
                                            </p>
                                        </div>
                                        <span class="text-orange mt-2 d-block" style="font-size: 14px; font-weight: 800;">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <!-- Sisi Kanan: Gambar & Tombol Tambah -->
                                    <div class="col-4 col-sm-3 text-end">
                                        <div class="menu-image-wrapper position-relative">
                                            <img src="{{ $menu->image_url ?? 'https://placehold.co/100x100?text=No+Image' }}" class="menu-image img-fluid rounded-3" alt="{{ $menu->name }}" style="height: 75px; width: 75px; object-fit: cover;">
                                            
                                            <!-- Tombol Kuantitas Pas Di Tengah-Bawah Gambar -->
                                            <div class="btn-qty-floating position-absolute bottom-0 start-50 translate-middle-x w-100 px-1" style="margin-bottom: -10px;">
                                                <button id="add-btn-{{ $menu->id }}" onclick="addItem({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})" class="btn btn-white text-orange border border-warning-subtle rounded-pill shadow-sm w-100 d-flex align-items-center justify-content-center bg-white" style="font-size: 10px; font-weight: 700; height: 26px;">
                                                    TAMBAH
                                                </button>
                                                <div id="qty-container-{{ $menu->id }}" class="d-none btn btn-orange rounded-pill w-100 p-0 d-flex align-items-center justify-content-between shadow-sm" style="height: 26px;">
                                                    <button onclick="changeQty({{ $menu->id }}, -1)" class="btn btn-link text-white p-0 border-0 ms-2 d-flex align-items-center"><i class="fa-solid fa-minus" style="font-size: 9px;"></i></button>
                                                    <span id="qty-val-{{ $menu->id }}" class="fw-bold text-white" style="font-size: 11px;">1</span>
                                                    <button onclick="changeQty({{ $menu->id }}, 1)" class="btn btn-link text-white p-0 border-0 me-2 d-flex align-items-center"><i class="fa-solid fa-plus" style="font-size: 9px;"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Rincian Biaya & Tombol Kirim Ke Dapur (Juga Berada Di Tengah) -->
<div class="container px-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="bg-white p-3 rounded-3 border shadow-sm">
                <!-- List Items -->
                <div id="checkout-items-list" class="space-y-3 mb-4 pr-1" style="max-height: 240px; overflow-y: auto;">
                    <!-- Ditangani dinamis dari JS -->
                </div>

                <!-- Rincian Biaya -->
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between text-secondary mb-1" style="font-size: 12px;">
                        <span>Subtotal Makanan</span>
                        <span id="summary-subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between text-secondary border-bottom pb-2 mb-2" style="font-size: 12px;">
                        <span>Pajak (PB1 10%)</span>
                        <span id="summary-tax">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between text-dark pt-1 align-items-center">
                        <span class="fw-bold" style="font-size: 14px;">Total Bayar</span>
                        <span id="summary-total" class="text-orange" style="font-size: 16px; font-weight: 800;">Rp 0</span>
                    </div>
                </div>
                
                <div class="pt-3">
                    <button type="button" class="btn btn-light text-secondary rounded-3 px-4 w-100 mb-2" style="font-size: 13px;">Kembali Memilih</button>
                    <button type="button" class="btn btn-orange rounded-3 w-100 py-2.5 shadow-sm" onclick="submitFinalOrder()" style="font-size: 14px; font-weight: 800;">
                        <i class="fa-solid fa-fire-burner me-1.5"></i> Kirim ke Dapur Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterMenu() {
        let input = document.getElementById('search-menu').value.toLowerCase();
        let menuItems = document.getElementsByClassName('menu-item');
        
        for (let i = 0; i < menuItems.length; i++) {
            let menuName = menuItems[i].getAttribute('data-name');
            if (menuName.includes(input)) {
                menuItems[i].style.display = "";
            } else {
                menuItems[i].style.display = "none";
            }
        }
        
        let sections = document.getElementsByClassName('category-section');
        for (let j = 0; j < sections.length; j++) {
            let visibleItems = sections[j].querySelectorAll('.menu-item[style=""]');
            let allItems = sections[j].querySelectorAll('.menu-item');
            if (allItems.length > 0 && visibleItems.length === 0 && input !== "") {
                sections[j].classList.add('d-none');
            } else {
                sections[j].classList.remove('d-none');
            }
        }
    }
</script>
@endpush
@endsection