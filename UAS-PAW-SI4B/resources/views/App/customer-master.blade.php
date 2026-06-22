<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Menu Padmamula')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    {{-- Navbar di include di sini --}}
    @include('App.customer-navbar')

    <main class="flex-grow pb-8">
        @yield('content')
    </main>

    <div id="modalMeja" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-sm">
            <h3 class="font-bold text-slate-800 text-lg mb-1">Panggil Pelayan</h3>
            <p class="text-sm text-slate-500 mb-5">Masukkan nomor meja Anda agar pelayan tahu lokasi Anda.</p>
            
            <label class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-wider">NOMOR MEJA</label>
            <input type="number" id="inputNoMeja" class="w-full border border-slate-200 rounded-xl p-3 mb-6 focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="Contoh: 12">
            
            <div class="flex flex-col gap-3">
                <button onclick="kirimPanggilan()" class="w-full py-3 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition active:scale-95">
                    Panggil Sekarang
                </button>
                <button onclick="tutupModal()" class="w-full py-3 text-slate-600 bg-slate-100 rounded-xl font-bold hover:bg-slate-200 transition active:scale-95">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function panggilPelayan() {
            const modal = document.getElementById('modalMeja');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Fungsi untuk menutup modal
        function tutupModal() {
            const modal = document.getElementById('modalMeja');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Fungsi kirim data ke server (Controller)
        function kirimPanggilan() {
            const noMeja = document.getElementById('inputNoMeja').value;
            
            if (!noMeja) {
                alert("Mohon masukkan nomor meja terlebih dahulu!");
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch("{{ route('panggil.pelayan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ no_meja: noMeja })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert("Berhasil! Pelayan akan segera datang ke meja " + noMeja);
                    tutupModal();
                    document.getElementById('inputNoMeja').value = ''; 
                } else {
                    alert("Gagal memanggil pelayan.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan koneksi.");
            });
        }
    </script>

    @stack('scripts')
</body>
</html>