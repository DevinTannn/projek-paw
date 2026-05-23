<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional, tapi bagus untuk memastikan kesesuaian)
    protected $table = 'customers';

    // Kolom yang diizinkan untuk diisi mass-assignment melalui Controller
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'point',
    ];
}