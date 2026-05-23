<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // HAPUS ATAU KOMENTARI BARIS INI JIKA ADA:
    // protected $table = 'kategori'; 

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}