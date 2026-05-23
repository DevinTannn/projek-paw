<header class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        
        <!-- Sisi Kiri: Identitas & Nomor Meja -->
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-orange-100 text-orange-600 rounded-xl">
                <i class="fa-solid fa-store text-lg animate-pulse"></i>
            </div>
            <div>
                <h1 class="font-extrabold text-slate-800 text-sm tracking-wide leading-none uppercase">Padmamula</h1>
                <!-- Dynamic Session Table Number -->
                <span class="inline-flex items-center text-[10px] text-orange-600 font-bold bg-orange-50 px-2 py-1 rounded-md mt-1 border border-orange-100">
                    <i class="fa-solid fa-chair mr-1 text-[9px]"></i>
                    MEJA NO. {{ session('table_number', '08') }}
                </span>
            </div>
        </div>

        <!-- Sisi Kanan: Panggil Pelayan & Keranjang Desktop -->
        <div class="flex items-center space-x-3">
            <!-- Fitur Panggil Pelayan -->
            <button onclick="panggilPelayan()" class="flex items-center space-x-1.5 px-3 py-2 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg hover:bg-amber-100 transition-colors border border-amber-100">
                <i class="fa-solid fa-bell text-amber-600"></i>
                <span>Panggil Pelayan</span>
            </button>

            <!-- Keranjang Belanja Desktop (Hanya muncul di layar sedang ke atas) -->
            <a href="#" class="relative p-2.5 text-slate-600 hover:text-orange-600 hover:bg-slate-50 rounded-xl transition-all hidden md:block">
                <i class="fa-solid fa-basket-shopping text-xl"></i>
                <span id="desktop-cart-badge" class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                    3
                </span>
            </a>
        </div>

    </div>
</header>