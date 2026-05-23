<header class="bg-white border-bottom sticky-top shadow-sm z-3">
    <div class="container-xl">
        <div class="d-flex align-items-center justify-content-between" style="height: 64px;">
            
            <!-- Sisi Kiri: Identitas & Nomor Meja -->
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 bg-orange-light text-orange rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-store fs-5"></i>
                </div>
                <div>
                    <h1 class="h6 fw-extrabold text-dark mb-0 text-uppercase tracking-wider" style="font-size: 13px; font-weight: 800;">Padmamula</h1>
                    <!-- Dynamic Session Table Number -->
                    <span class="badge bg-orange-light text-orange border border-warning mt-1" style="font-size: 10px; font-weight: 700; padding: 4px 8px;">
                        <i class="fa-solid fa-chair me-1" style="font-size: 9px;"></i>
                        MEJA NO. {{ session('table_number', '08') }}
                    </span>
                </div>
            </div>

            <!-- Sisi Kanan: Panggil Pelayan & Keranjang Desktop -->
            <div class="d-flex align-items-center gap-3">
                <!-- Fitur Panggil Pelayan -->
                <button onclick="panggilPelayan()" class="btn btn-warning btn-sm fw-bold text-dark border border-warning bg-opacity-10 d-flex align-items-center gap-1.5" style="background-color: #fffbeb; font-size: 11px; padding: 6px 12px; border-radius: 8px;">
                    <i class="fa-solid fa-bell text-warning"></i>
                    <span>Panggil Pelayan</span>
                </button>

                <!-- Keranjang Belanja Desktop (Hanya muncul di desktop ke atas - d-none d-md-block) -->
                <a href="#" class="position-relative p-2 text-secondary hover:text-orange d-none d-md-block text-decoration-none rounded-3" style="background-color: #f8f9fa;">
                    <i class="fa-solid fa-basket-shopping fs-5 text-dark"></i>
                    <span id="desktop-cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 9px; padding: 3px 5px;">
                        3
                    </span>
                </a>
            </div>

        </div>
    </div>
</header>