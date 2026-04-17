<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class PerpustakaanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori
        $kategoriIT = Kategori::create([
            'nama_kategori' => 'Teknologi Informasi',
            'deskripsi' => 'Buku-buku seputar pemrograman, jaringan, dan komputer.',
        ]);

        $kategoriFiksi = Kategori::create([
            'nama_kategori' => 'Fiksi & Sastra',
            'deskripsi' => 'Novel, cerpen, dan karya sastra lainnya.',
        ]);

        // 2. Buat Buku
        Buku::create([
            'kategori_id' => $kategoriIT->id,
            'judul' => 'Belajar Laravel 11 dari Nol',
            'penulis' => 'Syntax Project Team',
            'penerbit' => 'Informatika Press',
            'tahun_terbit' => 2024,
            'stok' => 15,
            'harga_denda' => 5000,
            'sinopsis' => 'Panduan interaktif menguasai CRUD PHP Native dan Laravel.',
        ]);

        Buku::create([
            'kategori_id' => $kategoriFiksi->id,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'tahun_terbit' => 2005,
            'stok' => 5,
            'harga_denda' => 2000,
            'sinopsis' => 'Kisah perjuangan 10 anak di Belitung untuk mendapatkan pendidikan.',
        ]);
    }
}
