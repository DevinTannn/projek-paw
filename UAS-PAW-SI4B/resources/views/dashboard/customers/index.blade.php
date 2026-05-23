@extends('layouts.app')

@section('title', 'Daftar Pelanggan')
@section('page_title', 'Kelola Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Header Aksi & Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Input Pencarian -->
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" placeholder="Cari nama atau nomor meja..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all shadow-sm">
        </div>

        <!-- Tombol Tambah Pelanggan Baru -->
        <a href="{{ route('customers.create') }}" class="inline-flex items-center justify-center space-x-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-orange-500/10">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pelanggan</span>
        </a>
    </div>

    <!-- Tabel Data Pelanggan -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Nomor Meja</th>
                        <th class="py-4 px-6">Status Kehadiran</th>
                        <th class="py-4 px-6">Waktu Masuk</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block">{{ $customer->name }}</span>
                                        <span class="text-xs text-gray-400">ID Pelanggan: #PM-00{{ $customer->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-lg border border-slate-200">
                                    <i class="fa-solid fa-chair mr-1.5 text-[10px]"></i>
                                    Meja No. {{ $customer->table_number }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($customer->status == 'makan')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-100">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                        Sedang Makan
                                    </span>
                                @elseif($customer->status == 'menunggu')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-100">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                                        Menunggu Menu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-50 text-gray-500 text-xs font-semibold rounded-full border border-gray-100">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        Selesai / Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-400 text-xs">
                                {{ $customer->created_at->diffForHumans() }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('customers.show', $customer->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    
                                    <!-- Form Aksi Delete -->
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data pelanggan {{ $customer->name }}?')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                <i class="fa-solid fa-users-slash text-3xl mb-2 block"></i>
                                Belum ada data pelanggan terdaftar hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
