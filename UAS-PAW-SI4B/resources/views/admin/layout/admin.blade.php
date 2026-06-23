<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padmamula Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar { background: #2d3436; min-height: 100vh; color: white; width: 260px; transition: 0.3s; }
        .nav-link { color: #b2bec3; padding: 12px 20px; transition: 0.3s; font-size: 0.95rem; }
        .nav-link:hover, .nav-link.active { background: #0984e3; color: white; border-radius: 10px; }
        .content { flex: 1; padding: 2rem; }
        /* Kartu modern dengan sedikit shadow */
        .card { border: none; border-radius: 18px !important; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar p-3">
        <div class="text-center py-4">
            <i class="bi bi-shop fs-2 text-warning"></i>
            <h5 class="fw-bold text-white mt-2">Padmamula</h5>
        </div>
        <nav class="nav flex-column gap-2 px-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <i class="fas fa-utensils me-2"></i> Manajemen Menu
            </a>
            <a href="{{ route('admin.karyawan.index') }}" class="nav-link {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Manajemen Karyawan
            </a>
            <a href="{{ route('admin.transaksi.history') }}" class="nav-link {{ request()->routeIs('admin.transaksi.history') ? 'active' : '' }}">
                <i class="fas fa-history me-2"></i> Histori Transaksi
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-4 px-2">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
            <h5 class="m-0 text-muted fw-bold">@yield('title', 'Dashboard')</h5>
            <div class="fw-bold small bg-light px-3 py-2 rounded-pill border"><i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}</div>
        </div>

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>