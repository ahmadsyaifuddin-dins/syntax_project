<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori,id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'stok' => 'required|integer|min:0',
            'harga_denda' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',

            // Tetap nullable saat update
            'cover_gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_buku' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ];
    }
}
