<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ubah ke true agar diizinkan
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:'.(date('Y') + 1),
            'stok' => 'required|integer|min:0',
            'harga_denda' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',

            // Validasi File (Cover & Dokumen)
            'cover_gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
            'file_buku' => 'nullable|mimes:pdf,doc,docx|max:5120', // Maks 5MB
        ];
    }

    public function messages()
    {
        // Opsional: Pesan error kustom jika diperlukan (bisa dibiarkan kosong untuk bawaan Laravel)
        return [
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'judul.required' => 'Judul buku wajib diisi.',
            'cover_gambar.image' => 'File cover harus berupa gambar.',
        ];
    }
}
