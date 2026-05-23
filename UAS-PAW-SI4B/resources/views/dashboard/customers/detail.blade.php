    <!-- Tombol Edit Cepat -->
    <a href="{{ url('/dashboard/customers/1/edit') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-semibold rounded-lg transition-colors border border-amber-200">
        <i class="fa-solid fa-pen"></i>
        <span>Edit Informasi</span>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Panel Utama Detail Pelanggan -->
    <div class="md:col-span-1 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
        <div class="w-20 h-20 mx-auto rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-2xl mb-4">
            AD
        </div>
        <h3 class="font-extrabold text-gray-800 text-lg">Aditya Nugroho</h3>
        <p class="text-xs text-gray-400 mt-1">ID Pelanggan: #PM-00412</p>

        <div class="mt-6 pt-6 border-t border-gray-100 text-left space-y-4">
            <div>
                <span class="text-xs text-gray-400 block font-medium">Nomor Meja</span>
                <span class="font-semibold text-gray-700 text-sm">Meja No. 04 (Lantai 1)</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block font-medium">Waktu Registrasi</span>
                <span class="font-semibold text-gray-700 text-sm">Hari ini, 19:15 WIB</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block font-medium">Status Kehadiran</span>
                <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-100 mt-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                    Sedang Makan
                </span>
            </div>
        </div>
    </div>

    <!-- Panel Riwayat Transaksi / Pesanan Aktif -->
    <div class="md:col-span-2 space-y-6">
        <!-- Box Informasi Pesanan Aktif -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Pesanan Aktif (Meja 04)</h4>
                    <p class="text-[11px] text-gray-400">Kode Transaksi: #TRX-20261104</p>
                </div>
                <span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-lg">
                    Diproses di Dapur
                </span>
            </div>

            <!-- Detail Item Pesanan -->
            <div class="p-5 divide-y divide-gray-100">
                <div class="py-3 flex justify-between items-center first:pt-0">
                    <div>
                        <span class="font-semibold text-gray-800 text-sm">2x Rendang Sapi Padmamula</span>
                        <span class="block text-xs text-gray-400">Keterangan: Minta kuah agak banyakan</span>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Rp 52.000</span>
                </div>
                <div class="py-3 flex justify-between items-center">
                    <div>
                        <span class="font-semibold text-gray-800 text-sm">2x Es Jeruk Kelapa Muda</span>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Rp 24.000</span>
                </div>
                <div class="py-3 flex justify-between items-center last:pb-0">
                    <div>
                        <span class="font-semibold text-gray-800 text-sm">1x Kerupuk Kulit Kuah Gulai</span>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Rp 8.000</span>
                </div>
            </div>

            <!-- Total Tagihan -->
            <div class="p-5 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-500 text-sm">Subtotal Pembayaran</span>
                <span class="font-extrabold text-orange-600 text-lg">Rp 84.000</span>
            </div>
        </div>

        <!-- Box Catatan Khusus Pelanggan -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="font-bold text-gray-800 text-sm mb-2">Informasi Penting / Catatan</h4>
            <p class="text-sm text-gray-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                "Porsi nasi rendang minta agak banyak kuahnya."
            </p>
        </div>
    </div>
</div>
