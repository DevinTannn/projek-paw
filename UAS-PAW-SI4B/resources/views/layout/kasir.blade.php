<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard Kasir') — Vegetarian</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <style>
        :root { --hijau: #2e7d32; --hijau-muda: #66bb6a; }
        body { background: #f5f5f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 240px; min-height: 100vh;
            background: var(--hijau); position: fixed; top: 0; left: 0;
        }
        .sidebar .brand { padding: 20px 16px; color: #fff; font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #388e3c; }
        .sidebar .nav-link { color: rgba(255,255,255,.8); padding: 10px 20px; border-radius: 8px; margin: 2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #388e3c; color: #fff; }
        .sidebar .nav-link i { margin-right: 8px; }
        .main-content { margin-left: 240px; padding: 24px; }
        .topbar { background: #fff; border-radius: 12px; padding: 12px 20px; margin-bottom: 24px;
                  display: flex; justify-content: space-between; align-items: center;
                  box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 6px rgba(0,0,0,.08); }
        .btn-hijau { background: var(--hijau); color: #fff; border: none; }
        .btn-hijau:hover { background: #1b5e20; color: #fff; }
        .badge-selesai { background: #e8f5e9; color: #2e7d32; }
        .badge-pending { background: #fff8e1; color: #f57f17; }
        .badge-batal   { background: #ffebee; color: #c62828; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<div class="sidebar d-flex flex-column">
    <div class="brand">
        <i class="bi bi-leaf-fill me-2"></i> Kasir Vegetarian
    </div>
    <nav class="nav flex-column mt-3">
        <a href="{{ route('kasir.dashboard') }}"
           class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('kasir.transaksi.buat') }}"
           class="nav-link {{ request()->routeIs('kasir.transaksi.buat') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i> Transaksi Baru
        </a>
    </nav>
    <div class="mt-auto p-3">
        <div class="text-white-50 small mb-1">{{ Auth::user()->name }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-light w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- ── Konten Utama ── --}}
<div class="main-content">
    {{-- Topbar --}}
    <div class="topbar">
        <h5 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h5>
        <span class="text-muted small">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger rounded-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Konten halaman --}}
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
