<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        'harga_denda',
        'cover_gambar',
        'file_buku',
        'sinopsis',
    ];

    // Relasi Inverse ke tabel Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Tambahkan relasi ini di paling bawah
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
