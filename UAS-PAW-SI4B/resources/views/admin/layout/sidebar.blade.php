<!DOCTYPE html>
<html lang="id">
<head>
    <style>
        .wrapper { display: flex; }
        .sidebar { width: 260px; min-height: 100vh; background: #1e293b; color: white; }
        .main-content { flex: 1; }
        /* Tambahkan style untuk hover agar lebih interaktif */
        .nav-link:hover { background: rgba(255,255,255,0.1); border-radius: 5px; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar p-3">
        <h4 class="text-warning text-center py-3">Padmamula</h4>
        <nav class="nav flex-column mt-3">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a>
            <a href="{{ route('admin.menus.index') }}" class="nav-link text-white">Manajemen Menu</a>
            <a href="{{ route('admin.karyawan.index') }}" class="nav-link text-white">Manajemen Karyawan</a>
            
            <a href="{{ route('admin.transaksi.history') }}" class="nav-link text-white">Histori Transaksi</a>
        </nav>
    </div>

    <div class="main-content">
        @include('admin.layout.navbar') 

        <div class="p-4">
            @yield('content')
        </div>
    </div>
</div>

</body>
</html>