@extends('kasir.layouts.kasir')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h5 class="fw-bold mb-1">{{ $transaksi->kode_transaksi }}</h5>
                <div class="text-muted small">
                    {{ $transaksi->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }}
                </div>
                <div class="text-muted small">Kasir: {{ $transaksi->kasir->name }}</div>
            </div>
            <span class="badge rounded-pill px-3 py-2 badge-{{ $transaksi->status }} fs-6">
                {{ ucfirst($transaksi->status) }}
            </span>
        </div>

        <table class="table table-borderless mb-0">
            <thead class="table-light">
                <tr>
                    <th>Menu</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $detail)
                <tr>
                    <td>
                        {{ $detail->menu->name }}  {{-- kolom teman: name --}}
                        @if ($detail->catatan_item)
                            <div class="text-muted small">📝 {{ $detail->catatan_item }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-top">
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold text-success fs-6">
                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end text-muted small">
                        Bayar ({{ $transaksi->metode_bayar }})
                    </td>
                    <td class="text-end text-muted small">
                        Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end text-muted small">Kembalian</td>
                    <td class="text-end text-muted small">
                        Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        @if ($transaksi->catatan)
        <div class="alert alert-light border mt-3 mb-0">
            <i class="bi bi-chat-left-text me-2"></i><strong>Catatan:</strong> {{ $transaksi->catatan }}
        </div>
        @endif

    </div>
    <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('kasir.struk', $transaksi->id) }}" class="btn btn-hijau btn-sm">
            <i class="bi bi-printer"></i> Cetak Struk
        </a>
        @if ($transaksi->status === 'pending')
        <form action="{{ route('kasir.transaksi.batal', $transaksi->id) }}" method="POST"
              onsubmit="return confirm('Batalkan transaksi ini?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-x-circle"></i> Batalkan
            </button>
        </form>
        @endif
    </div>
</div>
</div>
</div>
@endsection
