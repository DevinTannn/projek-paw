<?php
namespace App\Http\Controllers;

use App\Models\Panggilan;
use Illuminate\Http\Request;

class PanggilanController extends Controller {
    public function index() {
        $panggilan = Panggilan::where('status', 'pending')->get();
        return view('kasir.panggilan.index', compact('panggilan'));
    }

    public function store(Request $request) {
        
        $noMeja = $request->input('no_meja') ?? session('table_number');

        if (!$noMeja) {
            return response()->json(['success' => false, 'message' => 'Nomor meja tidak ditemukan. Silakan refresh halaman.'], 400);
        }

        \App\Models\Panggilan::create([
            'no_meja' => $noMeja,
            'status'  => 'pending'
        ]);

        return response()->json(['success' => true]);
    }

    public function selesai($id) {
        Panggilan::where('id', $id)->update(['status' => 'done']);
        return back()->with('success', 'Meja ' . \App\Models\Panggilan::find($id)->no_meja . ' sudah dilayani.');
    }

    public function getList() {
        $panggilan = Panggilan::where('status', 'pending')->get();
        return view('kasir.panggilan.partials.list', compact('panggilan'));
    }
}