@extends('App.customer-master') {{-- Pastikan ini layout utama customer --}}
@section('title', 'Pesan Sajian Istimewa')

@section('content')
<div class="container py-3" style="max-width: 500px; margin: auto;">
    
    <div class="text-center mb-4">
        <h1 class="h4 fw-bold">Padmamula Resto ✨</h1>
        <div class="badge bg-light text-dark border">Meja 05</div>
    </div>

    @foreach($categories as $category)
        <div class="mb-4">
            <h5 class="fw-bold border-bottom pb-2">{{ $category->name }}</h5>
            <div class="d-flex flex-column gap-3 mt-3">
                @foreach($category->menus as $menu)
                    <div class="card p-3 shadow-sm border-0 rounded-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $menu->image_url ? asset('storage/'.$menu->image_url) : 'https://placehold.co/80x80' }}" 
                                 class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $menu->name }}</div>
                                <div class="text-muted small">{{ $menu->description }}</div>
                                <div class="text-orange fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">Tambah</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

</div>
@endsection