<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Padmamula</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex">

    <div class="w-64 h-screen bg-slate-800 text-white p-5 fixed">
        <h2 class="text-2xl font-bold mb-8 text-center text-amber-400">Padmamula Admin</h2>
        <nav class="space-y-2">
            <a href="#" class="block p-3 rounded bg-slate-700 font-semibold">Dashboard</a>
            <a href="#" class="block p-3 rounded hover:bg-slate-700 transition">Manajemen Menu</a>
            <a href="#" class="block p-3 rounded hover:bg-slate-700 transition">Manajemen Karyawan</a>
            
            <form action="{{ route('logout') }}" method="POST" class="pt-10">
                @csrf
                <button type="submit" class="w-full text-left p-3 rounded bg-red-600 hover:bg-red-700 transition font-semibold">
                    Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="ml-64 p-10 w-full">
        <header class="flex justify-between items-center mb-10 pb-5 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang di Ruang Kontrol Utama</h1>
            <p class="text-gray-600">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-amber-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Total Menu Padmamula</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">0 Menu</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm font-medium uppercase">Total Pegawai</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">0 Orang</p>
            </div>
        </div>
    </div>

</body>
</html>