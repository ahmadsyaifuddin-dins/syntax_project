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
    private function getMateriPerpustakaan()
    {
        return [
            1 => [
                'judul' => 'Koneksi Database',
                'teks' => 'Langkah pertama membuat CRUD PHP Native adalah menghubungkan file PHP dengan database MySQL menggunakan fungsi <code>mysqli_connect()</code>.',
                'instruksi' => 'Ketik variabel koneksi berikut ke dalam editor.',
                'kode_harapan' => '$conn = mysqli_connect("localhost", "root", "", "perpustakaan");',
            ],
            2 => [
                'judul' => 'Read: Menampilkan Data',
                'teks' => 'Setelah terkoneksi, kita perlu mengambil data buku menggunakan perintah SQL <code>SELECT</code> dan menampilkannya menggunakan perulangan <code>while()</code>.',
                'instruksi' => 'Buat query untuk mengambil semua data dari tabel buku.',
                'kode_harapan' => '$query = mysqli_query($conn, "SELECT * FROM buku");',
            ],
            // Nanti kita tambah step 3 (Create), 4 (Update), 5 (Delete)
        ];
    }
}
