<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '081234567890',
                'address' => 'Jl. Jend. Sudirman No. 12, Palembang',
                'point' => 150,
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@email.com',
                'phone' => '081398765432',
                'address' => 'Jl. Merdeka Blok B3, Palembang',
                'point' => 85,
            ],
            [
                'name' => 'Hendrawan',
                'email' => 'hendra@email.com',
                'phone' => '085211223344',
                'address' => 'Perumahan Sekip Bendung, Palembang',
                'point' => 210,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@email.com',
                'phone' => '087855667788',
                'address' => 'Jl. R. Sukamto No. 45, Palembang',
                'point' => 45,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'fauzi.ahmad@email.com',
                'phone' => '089677889900',
                'address' => 'Jl. KH. Ahmad Dahlan, Palembang',
                'point' => 120,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}