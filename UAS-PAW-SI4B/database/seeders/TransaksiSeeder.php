<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@vegetarian.com'],
            ['name' => 'Budi Kasir', 'password' => bcrypt('password')]
        );

        $menus = Menu::all();
        if ($menus->isEmpty()) {
            $this->command->warn('Tidak ada menu. Pastikan teman sudah seed data menu terlebih dahulu.');
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $transaksi = Transaksi::create([
                'kasir_id'       => $kasir->id,
                'kode_transaksi' => 'TRX-' . now()->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'total_harga'    => 0,
                'total_bayar'    => 0,
                'kembalian'      => 0,
                'metode_bayar'   => collect(['tunai', 'qris', 'transfer'])->random(),
                'status'         => 'selesai',
            ]);

            $total  = 0;
            $sample = $menus->random(rand(1, 3));

            foreach ($sample as $menu) {
                $qty      = rand(1, 3);
                $subtotal = $menu->price * $qty;   // ← pakai price (kolom teman)
                $total   += $subtotal;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id'      => $menu->id,
                    'qty'          => $qty,
                    'harga_satuan' => $menu->price,  // ← pakai price (kolom teman)
                    'subtotal'     => $subtotal,
                ]);
            }

            $bayar = $total + rand(0, 5) * 1000;
            $transaksi->update([
                'total_harga' => $total,
                'total_bayar' => $bayar,
                'kembalian'   => $bayar - $total,
            ]);
        }

        $this->command->info('✅ 5 transaksi dummy berhasil dibuat.');
    }
}
