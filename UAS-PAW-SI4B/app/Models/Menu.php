<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus';

    // Tambahkan ini agar Laravel mengizinkan pengisian data ke kolom-kolom ini
    protected $fillable = [
        'category_id', 
        'name', 
        'description', 
        'price', 
        'image_url', 
        'is_recommended', 
        'is_bestseller'
    ];

    // Hubungkan dengan model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}