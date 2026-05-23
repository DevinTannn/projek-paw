<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Menu Padmamula') - RM Padmamula</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        /* Menghilangkan scrollbar pada kategori menu */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Variabel Warna Khas RM Padmamula */
        :root {
            --pm-orange: #f97316;
            --pm-orange-hover: #ea580c;
            --pm-amber: #f59e0b;
            --pm-dark: #0f172a;
        }
        /* Kustom Utility Class */
        .bg-orange-light {
            background-color: #fff7ed !important;
        }
        .text-orange {
            color: var(--pm-orange) !important;
        }
        .btn-orange {
            background-color: var(--pm-orange);
            color: white;
            border: none;
            font-weight: 700;
        }
        .btn-orange:hover {
            background-color: var(--pm-orange-hover);
            color: white;
        }
        .btn-dark-pm {
            background-color: var(--pm-dark);
            color: white;
            font-weight: 700;
            border: none;
        }
        .btn-dark-pm:hover {
            background-color: var(--pm-orange);
            color: white;
        }
        .bg-gradient-pm {
            background: linear-gradient(135deg, var(--pm-orange), var(--pm-amber)) !important;
        }
        /* Bottom Navigation Mobile-First Style */
        .bottom-nav {
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .floating-cart {
            width: 56px;
            height: 56px;
            top: -24px;
            background: linear-gradient(135deg, var(--pm-orange), var(--pm-amber));
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }
        .floating-cart:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Header / Navbar Pelanggan -->
    @include('App.customer-navbar')

    <!-- Konten Menu Utama (diberi padding bawah ekstra untuk navigasi bawah di HP) -->
    <main class="flex-grow-1 pb-5 mb-5 mb-md-0">
        @yield('content')
    </main>

    <!-- Bottom Navigation (Hanya muncul di HP/Mobile - d-md-none) -->
    <div class="fixed-bottom border-top bottom-nav d-md-none z-3">
        <div class="d-flex justify-content-around align-items-center py-2">
            <!-- Tombol Beranda/Menu -->
            <a href="#" class="d-flex flex-column align-items-center text-decoration-none text-orange">
                <i class="fa-solid fa-utensils fs-5"></i>
                <span style="font-size: 10px;" class="fw-bold mt-1">Menu</span>
            </a>
            
            <!-- Tombol Keranjang Floating di Tengah -->
            <a href="#" class="position-relative floating-cart text-white text-decoration-none">
                <i class="fa-solid fa-basket-shopping fs-4"></i>
                <!-- Badge Jumlah Item -->
                <span id="mobile-cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 9px; padding: 4px 6px;">
                    3
                </span>
            </a>

            <!-- Tombol Pesanan Saya -->
            <a href="#" class="d-flex flex-column align-items-center text-decoration-none text-secondary">
                <i class="fa-solid fa-receipt fs-5"></i>
                <span style="font-size: 10px;" class="fw-semibold mt-1 text-muted">Pesanan</span>
            </a>
        </div>
    </div>

    <!-- Footer Tradisional (Hanya muncul di Desktop - d-none d-md-block) -->
    <footer class="d-none d-md-block py-4 bg-white border-top text-center text-muted mt-auto">
        <div class="container-xl">
            <p class="mb-0">&copy; {{ date('Y') }} Rumah Makan Padmamula. Rasa Tradisional, Layanan Digital.</p>
        </div>
    </footer>

    <!-- Toast Notification (Bootstrap Style) -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050; margin-bottom: 75px;">
        <div id="liveToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <span id="toast-icon" class="text-warning"><i class="fa-solid fa-info-circle"></i></span>
                    <span id="toast-message">Notifikasi</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>