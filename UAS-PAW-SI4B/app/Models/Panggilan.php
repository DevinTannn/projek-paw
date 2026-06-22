<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panggilan extends Model
{
    protected $table = 'panggilans';
    protected $fillable = ['no_meja', 'status']; 
}