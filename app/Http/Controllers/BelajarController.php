<?php

namespace App\Http\Controllers;

use App\Models\ProgressBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BelajarController extends Controller
{
    // Halaman daftar modul studi kasus
    public function index()
    {
        $user = Auth::user();

        // Ambil progress perpustakaan, jika belum ada, buatkan data awal
        $progressPerpus = ProgressBelajar::firstOrCreate(
            ['user_id' => $user->id, 'studi_kasus' => 'perpustakaan'],
            ['step_sekarang' => 1, 'status' => 'belum']
        );

        return view('belajar.index', compact('progressPerpus'));
    }

    // Halaman Workspace Interaktif
    public function workspace($studi_kasus)
    {
        $user = Auth::user();
        $progress = ProgressBelajar::where('user_id', $user->id)
            ->where('studi_kasus', $studi_kasus)
            ->firstOrFail();

        // Ubah status jadi 'sedang' jika baru pertama kali dibuka
        if ($progress->status === 'belum') {
            $progress->update(['status' => 'sedang']);
        }

        // Siapkan data materi (hardcoded untuk versi awal agar cepat selesai)
        $materi = $this->getMateriPerpustakaan();

        return view('belajar.workspace', compact('progress', 'materi', 'studi_kasus'));
    }

    // Endpoint AJAX untuk menyimpan progress saat user klik "Lanjut"
    public function updateProgress(Request $request, $studi_kasus)
    {
        $progress = ProgressBelajar::where('user_id', Auth::id())
            ->where('studi_kasus', $studi_kasus)
            ->first();

        if ($progress) {
            $stepBaru = $request->input('step');
            $progress->update([
                'step_sekarang' => $stepBaru,
                'status' => $request->input('is_finish') ? 'selesai' : 'sedang',
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // Kumpulan Materi & Step (Nanti bisa dipindah ke database kalau mau dinamis)
    // Kumpulan Materi & Step (Retro Game Themed Learning)
    private function getMateriPerpustakaan()
    {
        return [
            1 => [
                'judul' => 'STAGE 1: KONEKSI DATABASE',
                'teks' => '
                    <p class="mb-3 text-gray-800">Langkah pertama dalam PHP Native adalah membangun "jembatan" ke database MySQL. Kita menggunakan fungsi bawaan <code>mysqli_connect()</code>.</p>
                    <div class="bg-black text-green-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono">
                        <span class="text-gray-500">// Struktur Dasar:</span><br>
                        $koneksi = mysqli_connect("host", "user", "password", "nama_db");
                    </div>
                    <p class="text-xs text-gray-600 italic">*Jika pakai XAMPP, host selalu "localhost", user "root", dan password dikosongkan ("").</p>
                ',
                'instruksi' => 'Buat variabel $conn dan lakukan koneksi ke database "perpustakaan" dengan user "root" dan password "".',
                'kode_harapan' => '$conn = mysqli_connect("localhost", "root", "", "perpustakaan");',
            ],
            2 => [
                'judul' => 'STAGE 2: READ (MENAMPILKAN DATA)',
                'teks' => '
                    <p class="mb-3 text-gray-800">Untuk mengambil data, kita mengirimkan perintah Raw SQL <code>SELECT</code> melalui fungsi <code>mysqli_query()</code>.</p>
                    <div class="bg-black text-blue-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono">
                        <span class="text-gray-500">// Contoh Raw SQL:</span><br>
                        SELECT * FROM nama_tabel;
                    </div>
                    <p class="text-xs text-gray-600">Nantinya data ini di-looping menggunakan <code>while($row = mysqli_fetch_assoc($query))</code> untuk dirender ke dalam tabel HTML.</p>
                ',
                'instruksi' => 'Simpan hasil mysqli_query ke variabel $query dengan perintah SELECT seluruh data dari tabel "buku".',
                'kode_harapan' => '$query = mysqli_query($conn, "SELECT * FROM buku");',
            ],
            3 => [
                'judul' => 'STAGE 3: CREATE (MENAMBAH DATA)',
                'teks' => '
                    <p class="mb-3 text-gray-800">Data biasanya dikirim dari form HTML menggunakan method <code>POST</code>. Di file PHP, kita menangkapnya dengan variabel global <code>$_POST</code>.</p>
                    <div class="bg-black text-orange-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono overflow-x-auto whitespace-pre">
<span class="text-gray-500">&lt;!-- 1. Form HTML --&gt;</span>
&lt;form action="simpan.php" method="POST"&gt;
   &lt;input type="text" name="judul"&gt;
&lt;/form&gt;

<span class="text-gray-500">// 2. Tangkap di PHP</span>
$judul = $_POST[\'judul\'];
</div>
                ',
                'instruksi' => 'Tulis query eksekusi INSERT INTO ke tabel "buku" kolom (judul) dengan value variabel \'$judul\'. Gunakan mysqli_query.',
                'kode_harapan' => 'mysqli_query($conn, "INSERT INTO buku (judul) VALUES (\'$judul\')");',
            ],
            4 => [
                'judul' => 'STAGE 4: UPDATE (MENGUBAH DATA)',
                'teks' => '
                    <div class="bg-red-100 border-l-4 border-red-500 p-2 mb-3">
                        <p class="text-red-700 font-black text-sm uppercase animate-pulse"><i class="fa-solid fa-triangle-exclamation"></i> WARNING: JANGAN LUPA WHERE!</p>
                        <p class="text-xs text-red-600 mt-1">Jika kamu menjalankan UPDATE tanpa WHERE, <b>seluruh data di tabel akan berubah!</b></p>
                    </div>
                    <div class="bg-black text-purple-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono whitespace-pre">
<span class="text-gray-500">// Raw SQL Update:</span>
UPDATE tabel SET kolom = \'nilai\' WHERE id = \'1\';
</div>
                ',
                'instruksi' => 'Tulis mysqli_query untuk UPDATE tabel "buku", SET kolom stok = \'$stok\', WHERE id = \'$id\'.',
                'kode_harapan' => 'mysqli_query($conn, "UPDATE buku SET stok = \'$stok\' WHERE id = \'$id\'");',
            ],
            5 => [
                'judul' => 'STAGE 5: DELETE (MENGHAPUS DATA)',
                'teks' => '
                    <p class="mb-3 text-gray-800">Menghapus data juga sangat bergantung pada klausa <code>WHERE</code>. Biasanya, ID dikirim melalui URL dan ditangkap dengan metode <code>GET</code>.</p>
                    <div class="bg-black text-pink-400 p-3 text-xs rounded border-2 border-gray-700 mb-3 shadow-inner font-mono whitespace-pre">
<span class="text-gray-500">&lt;!-- 1. Tombol Hapus HTML --&gt;</span>
&lt;a href="hapus.php?id=5"&gt;Hapus Data&lt;/a&gt;

<span class="text-gray-500">// 2. Tangkap URL & Delete (PHP)</span>
$id = $_GET[\'id\'];
DELETE FROM tabel WHERE id = \'$id\';
</div>
                ',
                'instruksi' => 'Tulis mysqli_query dengan perintah DELETE FROM tabel "buku" WHERE id = \'$id\'.',
                'kode_harapan' => 'mysqli_query($conn, "DELETE FROM buku WHERE id = \'$id\'");',
            ],
        ];
    }

    public function demo($studi_kasus)
    {
        // Ambil materi seperti biasa
        $materi = $this->getMateriPerpustakaan();

        // Buat objek progress dummy agar View tidak error
        $progress = (object) [
            'step_sekarang' => 1,
            'status' => 'belum',
        ];

        // Gunakan view yang sama (workspace.blade.php)
        return view('belajar.workspace', compact('progress', 'materi', 'studi_kasus'));
    }
}
