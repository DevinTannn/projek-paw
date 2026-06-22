<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'kasir_id',
        'table_number',
        'customer_name',
        'kode_transaksi',
        'total_harga',
        'total_bayar',
        'kembalian',
        'metode_bayar',
        'status',
        'catatan',
        'is_edited',    
        'catatan_edit',
        'tipe_transaksi',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'kembalian'   => 'decimal:2',
    ];

    // ── Relasi ───────────────────────────────────────────────
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    // ── Helper ───────────────────────────────────────────────
    /**
     * Generate kode transaksi unik, contoh: TRX-20240523-001
     */
    public static function generateKode(): string
    {
        $prefix  = 'TRX-' . now()->format('Ymd') . '-';
        $last    = self::where('kode_transaksi', 'like', $prefix . '%')
                       ->orderByDesc('id')
                       ->first();
        $urutan  = $last ? ((int) substr($last->kode_transaksi, -3)) + 1 : 1;
        return $prefix . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    public function details()
    {
        return $this->detailTransaksi();
    }
    
    public function detailTransaksi()
    {
        // Satu transaksi memiliki banyak detail transaksi
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'id');
    }
}
