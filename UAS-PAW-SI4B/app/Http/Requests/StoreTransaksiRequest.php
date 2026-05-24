<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pastikan middleware role:kasir sudah handle ini
    }

    public function rules(): array
    {
        return [
            'items'                => 'required|array|min:1',
            'items.*.menu_id'      => 'required|exists:menus,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.catatan_item' => 'nullable|string|max:255',
            'metode_bayar'         => 'required|in:tunai,transfer,qris',
            'total_bayar'          => 'required|numeric|min:0',
            'catatan'              => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'          => 'Pesanan tidak boleh kosong.',
            'items.*.menu_id.exists'  => 'Menu tidak ditemukan.',
            'items.*.qty.min'         => 'Jumlah item minimal 1.',
            'total_bayar.min'         => 'Jumlah bayar tidak valid.',
            'metode_bayar.in'         => 'Metode bayar tidak valid.',
        ];
    }
}
