<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Menu Padmamula') - RM Padmamula</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Menyembunyikan scrollbar pada navigasi kategori */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Header / Navbar Pelanggan -->
    @include('App.customer-navbar')

    <!-- Konten Menu Utama (diberi padding bawah ekstra untuk bottom nav di mobile) -->
    <main class="flex-grow pb-24 md:pb-8">
        @yield('content')
    </main>

    <!-- Bottom Navigation (Hanya muncul di HP/Mobile - md:hidden) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] md:hidden z-50">
        <div class="flex justify-around items-center py-3">
            <!-- Tombol Beranda/Menu -->
            <a href="#" class="flex flex-col items-center text-orange-500">
                <i class="fa-solid fa-utensils text-xl"></i>
                <span class="text-[10px] mt-1 font-semibold tracking-wide">Menu</span>
            </a>
            
            <!-- Tombol Keranjang Floating di Tengah -->
            <a href="#" class="relative -top-6 bg-gradient-to-tr from-orange-500 to-amber-500 text-white p-4 rounded-full shadow-lg shadow-orange-500/30 flex items-center justify-center w-14 h-14 hover:scale-105 transition-transform">
                <i class="fa-solid fa-basket-shopping text-xl"></i>
                <!-- Badge Jumlah Item -->
                <span id="mobile-cart-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white">
                    3
                </span>
            </a>

            <!-- Tombol Pesanan Saya -->
            <a href="#" class="flex flex-col items-center text-slate-400 hover:text-orange-500 transition-colors">
                <i class="fa-solid fa-receipt text-xl"></i>
                <span class="text-[10px] mt-1 font-semibold tracking-wide">Pesanan Saya</span>
            </a>
        </div>
    </div>

    <!-- Footer Tradisional (Hanya muncul di Desktop - hidden md:block) -->
    <footer class="hidden md:block py-6 bg-white border-t border-slate-100 text-center text-sm text-slate-400">
        <div class="max-w-6xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Rumah Makan Padmamula. Rasa Tradisional, Layanan Digital.</p>
        </div>
    </footer>

    <!-- Toast Notification (Untuk aksi panggil pelayan / tambah keranjang) -->
    <div id="toast" class="fixed bottom-24 right-4 md:bottom-8 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg flex items-center space-x-3 translate-y-20 opacity-0 transition-all duration-300 z-50">
        <span id="toast-icon" class="text-amber-400"><i class="fa-solid fa-info-circle"></i></span>
        <span id="toast-message" class="text-sm font-medium">Notifikasi</span>
    </div>

    @stack('scripts')
</body>
</html>
