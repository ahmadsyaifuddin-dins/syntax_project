<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\File;

class BukuController extends Controller
{
    public function index()
    {
        // Ambil data buku beserta relasi kategorinya
        $buku = Buku::with(['kategori', 'user'])->latest()->get();

        return view('admin.buku.index', compact('buku'));
    }

    public function create()
    {
        $kategori = Kategori::all();

        return view('admin.buku.create', compact('kategori'));
    }

    public function store(StoreBukuRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        // 1. Upload Cover Gambar (Old School Method ke public/uploads/images)
        if ($request->hasFile('cover_gambar')) {
            $file = $request->file('cover_gambar');
            $nama_file = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/images'), $nama_file);
            $data['cover_gambar'] = $nama_file;
        }

        // 2. Upload File Dokumen (Old School Method ke public/uploads/documents)
        if ($request->hasFile('file_buku')) {
            $file = $request->file('file_buku');
            $nama_file = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/documents'), $nama_file);
            $data['file_buku'] = $nama_file;
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil ditambahkan!');
    }

    public function edit(Buku $buku)
    {
        $kategori = Kategori::all();

        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $data = $request->validated();

        // 1. Update Cover Gambar
        if ($request->hasFile('cover_gambar')) {
            // Hapus file lama jika ada
            if ($buku->cover_gambar && File::exists(public_path('uploads/images/'.$buku->cover_gambar))) {
                File::delete(public_path('uploads/images/'.$buku->cover_gambar));
            }

            $file = $request->file('cover_gambar');
            $nama_file = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/images'), $nama_file);
            $data['cover_gambar'] = $nama_file;
        }

        // 2. Update File Dokumen
        if ($request->hasFile('file_buku')) {
            // Hapus file lama jika ada
            if ($buku->file_buku && File::exists(public_path('uploads/documents/'.$buku->file_buku))) {
                File::delete(public_path('uploads/documents/'.$buku->file_buku));
            }

            $file = $request->file('file_buku');
            $nama_file = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/documents'), $nama_file);
            $data['file_buku'] = $nama_file;
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        // Hapus file fisik sebelum menghapus data di database
        if ($buku->cover_gambar && File::exists(public_path('uploads/images/'.$buku->cover_gambar))) {
            File::delete(public_path('uploads/images/'.$buku->cover_gambar));
        }

        if ($buku->file_buku && File::exists(public_path('uploads/documents/'.$buku->file_buku))) {
            File::delete(public_path('uploads/documents/'.$buku->file_buku));
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil dihapus!');
    }
}
