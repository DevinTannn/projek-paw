@extends('layouts.app') {{-- Sesuaikan dengan master layout Anda --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Dashboard Pelanggan - RM Padmamula</h2>
        <a href="{{ route('customers.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow">
            + Tambah Pelanggan
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-left text-sm uppercase font-semibold">
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">No. HP</th>
                    <th class="px-5 py-3">Poin Loyalitas</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($customers as $customer)
                <tr class="border-b border-gray-200 hover:bg-gray-55">
                    <td class="px-5 py-4">{{ $customer->name }}</td>
                    <td class="px-5 py-4">{{ $customer->email }}</td>
                    <td class="px-5 py-4">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-5 py-4">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                            {{ $customer->point }} Pts
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('customers.edit', $customer->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3 font-medium">Edit</a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                            class="inline-block" onsubmit="return confirm('Hapus pelanggan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-5 text-center text-gray-500">Belum ada data pelanggan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 bg-white border-t">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection