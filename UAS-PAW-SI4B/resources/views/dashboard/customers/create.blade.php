<!-- Card Form -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 bg-gray-50/50 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-base">Formulir Data Pelanggan</h3>
        <p class="text-xs text-gray-400 mt-1">Gunakan form ini untuk mendaftarkan pelanggan walk-in atau memetakan pesanan baru.</p>
    </div>

    <form action="#" method="POST" class="p-6 space-y-5">
        @csrf

        <!-- Input Nama -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap Pelanggan</label>
            <input type="text" id="name" name="name" required placeholder="Contoh: Aditya Nugroho" 
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all shadow-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Dropdown No Meja -->
            <div>
                <label for="table_number" class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Nomor Meja</label>
                <select id="table_number" name="table_number" required 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all shadow-sm bg-white">
                    <option value="">-- Pilih Meja --</option>
                    <option value="01">Meja 01</option>
                    <option value="02">Meja 02</option>
                    <option value="03">Meja 03</option>
                    <option value="04">Meja 04</option>
                    <option value="05">Meja 05 (VIP)</option>
                </select>
            </div>

            <!-- Dropdown Status Kehadiran -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status Awal</label>
                <select id="status" name="status" required 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all shadow-sm bg-white">
                    <option value="menunggu">Menunggu Menu</option>
                    <option value="makan">Sedang Makan</option>
                    <option value="selesai">Selesai / Keluar</option>
                </select>
            </div>
        </div>

        <!-- Input Catatan/Keterangan -->
        <div>
            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Khusus (Opsional)</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Sambal minta dipisah, minta air hangat..." 
                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm transition-all shadow-sm"></textarea>
        </div>

        <hr class="border-gray-100 my-2">

        <!-- Tombol Submit -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ url('/dashboard/customers') }}" class="px-5 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm rounded-xl transition-all">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-orange-500/10">
                <i class="fa-solid fa-circle-check mr-1.5"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
