@extends('kasir.layouts.kasir')

@section('content')
<div class="container">
    <div class="card p-4">
        <h5 class="fw-bold mb-4">Daftar Panggilan Pelayan</h5>
        
        <div id="panggilan-container">
            @forelse(\App\Models\Panggilan::where('status', 'pending')->get() as $p)
                <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded">
                    <span class="fw-bold">Meja {{ $p->no_meja }}</span>
                    <form action="{{ route('kasir.panggilan.selesai', $p->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-success">Selesai</button>
                    </form>
                </div>
            @empty
                <p class="text-center text-muted">Tidak ada panggilan masuk.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection