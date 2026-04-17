<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_projects', function (Blueprint $table) {
            $table->id();
            // Relasi ke user yang sedang login
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Nama studi kasus (misal: 'perpustakaan')
            $table->string('studi_kasus');

            // Nama file yang sedang dikerjakan
            $table->string('file_name');

            // Isi kodingan user (disimpan agar tidak hilang saat direfresh)
            $table->text('content')->nullable();

            // Status apakah file ini sudah benar/lulus checkCode()
            $table->boolean('is_completed')->default(false);

            $table->timestamps();

            // Cegah duplikasi data (1 user hanya punya 1 file koneksi.php di studi kasus perpustakaan)
            $table->unique(['user_id', 'studi_kasus', 'file_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_projects');
    }
};
