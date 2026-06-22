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

<div id="modalMeja" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-sm">
        <h3 class="font-bold text-slate-800 text-lg mb-1">Panggil Pelayan</h3>
        <p class="text-sm text-slate-500 mb-5">Masukkan nomor meja Anda agar pelayan tahu lokasi Anda.</p>
        
        <label class="block text-xs font-bold text-slate-600 mb-1">NOMOR MEJA</label>
        <input type="number" id="inputNoMeja" class="w-full border border-slate-200 rounded-xl p-3 mb-6 focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="Contoh: 12">
        
        <div class="flex space-x-3">
            <button onclick="tutupModal()" class="flex-1 px-4 py-2.5 text-slate-600 bg-slate-100 rounded-xl font-bold hover:bg-slate-200">Batal</button>
            <button onclick="kirimPanggilan()" class="flex-1 px-4 py-2.5 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700">Panggil</button>
        </div>
    </div>
</div>

<script>
    function panggilPelayan() {
        const modal = document.getElementById('modalMeja');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModal() {
        const modal = document.getElementById('modalMeja');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function kirimPanggilan() {
        const noMeja = document.getElementById('inputNoMeja').value;
        
        if (!noMeja) {
            alert("Mohon masukkan nomor meja terlebih dahulu!");
            return;
        }

        // Anda bisa menambahkan logika fetch/axios ke backend di sini
        console.log("Memanggil pelayan untuk meja: " + noMeja);
        
        alert("Berhasil! Pelayan akan segera datang ke meja " + noMeja);
        tutupModal();
        document.getElementById('inputNoMeja').value = ''; // Reset input
    }
</script>