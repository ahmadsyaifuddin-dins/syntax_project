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

    public function tutorial()
    {
        $materi = $this->getMateriDasar();
        $isDemo = false;

        return view('belajar.tutorial', compact('materi', 'isDemo'));
    }

    // Halaman Tutorial untuk Guest (Partial Access)
    public function demoTutorial()
    {
        $materi = $this->getMateriDasar();
        $isDemo = true;

        // Gunakan view yang sama, nanti kita batasi di level UI
        return view('belajar.tutorial', compact('materi', 'isDemo'));
    }

    // Kumpulan Materi Dasar PHP Native (Cheat Sheet)
    private function getMateriDasar()
    {
        return [
            [
                'id' => 'koneksi',
                'judul' => 'Koneksi Database',
                'icon' => 'fa-plug',
                'is_locked_for_guest' => false,
                'penjelasan' => 'Sebelum melakukan CRUD, aplikasi PHP harus dihubungkan ke MySQL menggunakan <code>mysqli_connect()</code>. Variabel koneksi ini wajib di-include di setiap halaman yang butuh database.',
                'kode' => "<?php\n// format: mysqli_connect(host, username, password, database);\n\$conn = mysqli_connect('localhost', 'root', '', 'nama_database');\n\nif (!\$conn) {\n    die('Koneksi Gagal: ' . mysqli_connect_error());\n}\necho 'Koneksi Berhasil!';\n?>",
            ],
            [
                'id' => 'read',
                'judul' => 'Read (SELECT)',
                'icon' => 'fa-table-list',
                'is_locked_for_guest' => false,
                'penjelasan' => 'Menampilkan data menggunakan perintah <code>SELECT</code>. Hasil query ditangkap dengan <code>mysqli_query()</code>, lalu dipecah menjadi array menggunakan perulangan <code>while</code> dan <code>mysqli_fetch_assoc()</code>.',
                'kode' => "<?php\n\$query = mysqli_query(\$conn, \"SELECT * FROM users ORDER BY id DESC\");\n\n// Looping data ke dalam tabel HTML\nwhile (\$row = mysqli_fetch_assoc(\$query)) {\n    echo \"<tr>\";\n    echo \"<td>\" . \$row['nama'] . \"</td>\";\n    echo \"<td>\" . \$row['email'] . \"</td>\";\n    echo \"</tr>\";\n}\n?>",
            ],
            [
                'id' => 'create',
                'judul' => 'Create (INSERT)',
                'icon' => 'fa-plus',
                'is_locked_for_guest' => true, // Kunci untuk Guest!
                'penjelasan' => 'Menyimpan data baru. Data biasanya dikirim dari form HTML via method POST, ditangkap PHP dengan <code>$_POST</code>, lalu dieksekusi dengan perintah <code>INSERT INTO</code>.',
                'kode' => "<?php\n// Tangkap data dari form input name=\"nama\"\n\$nama = \$_POST['nama'];\n\$email = \$_POST['email'];\n\n// Query Insert\n\$sql = \"INSERT INTO users (nama, email) VALUES ('\$nama', '\$email')\";\n\$eksekusi = mysqli_query(\$conn, \$sql);\n\nif (\$eksekusi) {\n    echo \"Data berhasil ditambahkan!\";\n}\n?>",
            ],
            [
                'id' => 'update',
                'judul' => 'Update (UPDATE)',
                'icon' => 'fa-pen',
                'is_locked_for_guest' => true,
                'penjelasan' => 'Mengubah data. <b>WAJIB MENGGUNAKAN WHERE</b> agar tidak semua data ikut terubah. Biasanya ID dikirim via form tersembunyi (hidden input).',
                'kode' => "<?php\n\$id = \$_POST['id'];\n\$nama_baru = \$_POST['nama'];\n\n// Query Update dengan WHERE\n\$sql = \"UPDATE users SET nama = '\$nama_baru' WHERE id = '\$id'\";\n\$eksekusi = mysqli_query(\$conn, \$sql);\n?>",
            ],
            [
                'id' => 'delete',
                'judul' => 'Delete (DELETE)',
                'icon' => 'fa-trash',
                'is_locked_for_guest' => true,
                'penjelasan' => 'Menghapus data berdasarkan ID. Biasanya ID dikirim melalui URL dan ditangkap dengan metode <code>$_GET</code>.',
                'kode' => "<?php\n// Tangkap ID dari URL (contoh: hapus.php?id=5)\n\$id = \$_GET['id'];\n\n// Query Delete\n\$sql = \"DELETE FROM users WHERE id = '\$id'\";\n\$eksekusi = mysqli_query(\$conn, \$sql);\n?>",
            ],
        ];
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
                    <p class="text-xs text-gray-600 italic">*Jika pakai Laragon/XAMPP, host selalu "localhost", user "root", dan password dikosongkan ("").</p>
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
