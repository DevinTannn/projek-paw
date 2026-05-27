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
            'role' => 'admin' // (Buka komentar ini kalau kolom role sudah ada)
        ]);

        // 2. Bikin akun Kasir
        User::create([
            'name' => 'Kasir Depan',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('kasir'),
            'role' => 'kasir' 
        ]);

        // 3. Bikin akun Test User bawaan Laravel
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'customer'
        ]);

        // 4. Memanggil seeder buatan temanmu
        $this->call([
            CustomerSeeder::class,
        ]);
    }
}