<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel kategori
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();

            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->integer('stok')->default(0);
            $table->integer('harga_denda')->default(0); // Untuk testing input-currency

            // Simpan nama file saja (Old School upload)
            $table->string('cover_gambar')->nullable();
            $table->string('file_buku')->nullable();

            $table->text('sinopsis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
