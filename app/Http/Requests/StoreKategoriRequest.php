<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Wajib true agar form bisa disubmit
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }
}
