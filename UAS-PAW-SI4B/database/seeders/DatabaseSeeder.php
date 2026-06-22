<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Bikin akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'role' => 'admin'
        ]);

        // 2. Bikin akun Kasir
        User::create([
            'name' => 'Kasir Depan',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('kasir'),
            'role' => 'kasir' 
        ]);

        // 4. Memanggil seeder
        $this->call([
            CategorySeeder::class, // Tambahkan di sini
            // CustomerSeeder::class, // Hapus atau beri komentar jika tidak dipakai
        ]);
    }
}