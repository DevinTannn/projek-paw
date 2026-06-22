<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard Kasir') — Vegetarian</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <style>
        :root { 
            --primary: #10b981; 
            --sidebar-bg: #1e293b; 
            --body-bg: #f8fafc;
        }
        body { background: var(--body-bg); font-family: 'Inter', system-ui, sans-serif; }
        
        .sidebar { 
            width: 260px; background: var(--sidebar-bg); 
            min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .brand { padding: 25px 20px; font-weight: 800; color: white; border-bottom: 1px solid #334155; letter-spacing: -0.5px; }
        
        .nav-link { 
            color: #94a3b8; padding: 12px 20px; margin: 4px 15px; border-radius: 10px;
            transition: all 0.3s ease;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav-link.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        
        .main-content { margin-left: 260px; padding: 24px; }
        
        .topbar { 
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
            border-radius: 16px; padding: 16px 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;
        }

        .card { border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        
        .menu-card { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important; }
        
        .user-profile { 
            background: rgba(255,255,255,0.05); border-radius: 16px; padding: 15px; margin: 0 15px;
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="brand"><i class="bi bi-leaf-fill me-2"></i> Kasir Vegetarian</div>
    <div class="px-4 pt-4 pb-2">
        <h6 class="text-white mb-0 fw-bold">Halo, {{ Auth::user()->name }}! 👋</h6>
    </div>

    <nav class="nav flex-column mt-2">
        <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="{{ route('kasir.transaksi.buat') }}" class="nav-link {{ request()->routeIs('kasir.transaksi.buat') ? 'active' : '' }}"><i class="bi bi-cart-plus me-2"></i> Transaksi Baru</a>
        <a href="{{ route('kasir.transaksi.pending') }}" class="nav-link {{ request()->routeIs('kasir.transaksi.pending') ? 'active' : '' }}"><i class="bi bi-hourglass-split me-2"></i> Pesanan Pending</a>
    </nav>

    <div class="mt-auto p-4">
        <div class="user-profile d-flex align-items-center mb-3">
            @if(Auth::user()->image_url)
                <img src="{{ asset('storage/' . Auth::user()->image_url) }}?v={{ time() }}" class="rounded-3 me-3" style="width: 45px; height: 45px; object-fit: cover;">
            @else
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: 800;">{{ strtoupper(substr(Auth::user()->name ?? 'K', 0, 1)) }}</div>
            @endif
            <div class="text-white">
                <div class="fw-bold">{{ Auth::user()->name ?? 'Kasir' }}</div>
                <div class="text-primary" style="font-size: 0.75rem; font-weight: 600;">{{ ucfirst(Auth::user()->role ?? 'Staff') }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-light w-100 rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
        <div class="d-flex align-items-center gap-3">
            @php $panggilanCount = \App\Models\Panggilan::where('status', 'pending')->count(); @endphp
            <a href="#" class="position-relative text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalPanggilan">
                <i class="bi bi-bell fs-5 {{ $panggilanCount > 0 ? 'text-warning' : 'text-secondary' }}"></i>
                <span class="badge rounded-pill {{ $panggilanCount > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">{{ $panggilanCount }}</span>
            </a>
            <span class="text-muted small border-start ps-3">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>
    @if (session('success')) <div class="alert alert-success rounded-4">{{ session('success') }}</div> @endif
    @yield('content')
</div>

<div class="modal fade" id="modalPanggilan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0"><h6 class="modal-title fw-bold">Daftar Panggilan</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body" id="notif-body">Memuat...</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('modalPanggilan').addEventListener('show.bs.modal', function () {
        const notifBody = document.getElementById('notif-body');
        notifBody.innerHTML = '<div class="text-center py-3">Memuat data...</div>';
        fetch("{{ route('kasir.panggilan.list') }}")
            .then(res => res.text())
            .then(html => { notifBody.innerHTML = html; })
            .catch(() => { notifBody.innerHTML = '<p class="text-center text-danger">Gagal memuat.</p>'; });
    });
</script>
@stack('scripts')
</body>
</html>