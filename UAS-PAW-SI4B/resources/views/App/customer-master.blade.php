<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Menu Padmamula') - RM Padmamula</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    @include('App.customer-navbar')

    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[100]">
        @guest
            <a href="{{ route('login') }}" class="bg-white/90 backdrop-blur text-slate-600 px-6 py-2 rounded-full shadow-lg text-xs font-bold border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-lock"></i> Login
            </a>
        @endguest

        @auth
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('kasir.dashboard') }}" class="bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-2 rounded-full shadow-lg text-xs font-bold hover:scale-105 transition-transform">
                Dashboard 🚀
            </a>
        @endauth
    </div>

    <main class="flex-grow pb-24 md:pb-8">
        @yield('content')
    </main>

    <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-100 md:hidden z-50">
        <div class="flex justify-around items-center py-3">
            <a href="/" class="flex flex-col items-center text-orange-500">
                <i class="fa-solid fa-utensils text-xl"></i>
                <span class="text-[10px] mt-1 font-semibold">Menu</span>
            </a>
            
            <a href="{{ route('cart.index') }}" class="relative -top-6 bg-gradient-to-tr from-orange-500 to-amber-500 text-white p-4 rounded-full shadow-lg w-14 h-14 flex items-center justify-center">
                <i class="fa-solid fa-basket-shopping text-xl"></i>
                
                @php
                    $totalItems = session()->has('cart') ? array_sum(session('cart')) : 0;
                @endphp
                
                <span id="mobile-cart-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full {{ $totalItems > 0 ? '' : 'hidden' }}">
                    {{ $totalItems }}
                </span>
            </a>

            <a href="#" class="flex flex-col items-center text-slate-400">
                <i class="fa-solid fa-receipt text-xl"></i>
                <span class="text-[10px] mt-1 font-semibold">Pesanan</span>
            </a>
        </div>
    </div>

    <footer class="hidden md:block py-6 bg-white border-t border-slate-100 text-center text-sm text-slate-400">
        <p>&copy; {{ date('Y') }} Rumah Makan Padmamula. Rasa Tradisional, Layanan Digital.</p>
    </footer>

    @stack('scripts')
</body>
</html>