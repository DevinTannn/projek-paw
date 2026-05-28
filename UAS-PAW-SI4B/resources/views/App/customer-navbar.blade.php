<header class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-orange-100 text-orange-600 rounded-xl">
                <i class="fa-solid fa-store text-lg" aria-hidden="true"></i>
            </div>
            <div>
                <h1 class="font-extrabold text-slate-800 text-sm tracking-wide leading-none uppercase">Padmamula</h1>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            {{-- Tombol Panggil Pelayan --}}
            <button onclick="panggilPelayan()" class="flex items-center space-x-1.5 px-3 py-2 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg hover:bg-amber-100 transition-all border border-amber-100">
                <i class="fa-solid fa-bell text-amber-600"></i>
                <span class="hidden sm:inline">Panggil</span>
            </button>

            {{-- TOMBOL LOGIN / DASHBOARD (DITAMBAHKAN KEMBALI) --}}
            @guest
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 hover:text-orange-600">
                    Login
                </a>
            @endguest

            @auth
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}" 
                   class="bg-orange-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-orange-600 transition-all">
                    Dashboard
                </a>
            @endauth

            {{-- Keranjang --}}
            <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-600 hover:text-orange-600 transition-all">
                <i class="fa-solid fa-basket-shopping text-xl"></i>
                @php
                    $cartCount = array_sum(session()->get('cart', []));
                @endphp
                <span id="desktop-cart-badge" class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white {{ $cartCount > 0 ? '' : 'hidden' }}">
                    {{ $cartCount }}
                </span>
            </a>
        </div>
    </div>
</header>