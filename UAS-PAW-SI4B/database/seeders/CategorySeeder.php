<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan'],
            ['name' => 'Minuman'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}