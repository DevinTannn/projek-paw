<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panggilans', function (Blueprint $table) {
            $table->id();
            $table->string('no_meja'); // Menambahkan kolom nomor meja
            $table->string('status')->default('pending'); // Menambahkan kolom status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panggilans');
    }
};