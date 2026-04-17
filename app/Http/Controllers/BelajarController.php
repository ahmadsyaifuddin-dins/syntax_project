<?php

namespace App\Http\Controllers;

use App\Models\ProgressBelajar;
use App\Models\User;
use App\Traits\MateriBelajarTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BelajarController extends Controller
{
    // Panggil Trait agar bisa memakai method getMateriDasar() dan getMateriPerpustakaan()
    use MateriBelajarTrait;

    /**
     * Tampilkan halaman awal modul belajar (Stage Select)
     */
    public function index()
    {
        $user = Auth::user();

        $progressPerpus = ProgressBelajar::firstOrCreate(
            ['user_id' => $user->id, 'studi_kasus' => 'perpustakaan'],
            ['step_sekarang' => 1, 'status' => 'belum']
        );

        return view('belajar.index', compact('progressPerpus'));
    }

    /**
     * Tampilkan antarmuka Workspace (Live Coding)
     */
    public function workspace($studi_kasus)
    {
        $user = Auth::user();
        $progress = ProgressBelajar::where('user_id', $user->id)
            ->where('studi_kasus', $studi_kasus)
            ->firstOrFail();

        if ($progress->status === 'belum') {
            $progress->update(['status' => 'sedang']);
        }

        $materi = $this->getMateriPerpustakaan();

        return view('belajar.workspace', compact('progress', 'materi', 'studi_kasus'));
    }

    /**
     * Tampilkan Training Camp (Cheat Sheet) untuk User Auth
     */
    public function tutorial()
    {
        $materi = $this->getMateriDasar();
        $isDemo = false;

        return view('belajar.tutorial', compact('materi', 'isDemo'));
    }

    /**
     * Tampilkan Training Camp (Cheat Sheet) untuk Guest (Sebagian dilock)
     */
    public function demoTutorial()
    {
        $materi = $this->getMateriDasar();
        $isDemo = true;

        return view('belajar.tutorial', compact('materi', 'isDemo'));
    }

    /**
     * Tampilkan antarmuka Workspace khusus mode Demo (Guest)
     */
    public function demo($studi_kasus)
    {
        $materi = $this->getMateriPerpustakaan();

        // Progress dummy agar UI tidak error karena Guest tidak punya database progress
        $progress = (object) [
            'step_sekarang' => 1,
            'status' => 'belum',
        ];

        return view('belajar.workspace', compact('progress', 'materi', 'studi_kasus'));
    }

    /**
     * Endpoint API untuk menyimpan progress belajar siswa (DIPERBARUI UNTUK GAMIFIKASI)
     */
    public function updateProgress(Request $request, $studi_kasus)
    {
        $user = Auth::user();
        $progress = ProgressBelajar::where('user_id', $user->id)
            ->where('studi_kasus', $studi_kasus)
            ->first();

        if ($progress) {
            $stepBaru = $request->input('step');
            $isFinish = $request->input('is_finish');

            // --- SISTEM GAMIFIKASI ---
            // Hanya berikan poin jika user maju ke step baru (mencegah farming point dengan mundur step)
            if ($stepBaru > $progress->step_sekarang || $isFinish) {
                $user->points += 100; // Dapat 100 poin tiap lewat 1 stage

                $achievements = $user->achievements ? json_decode($user->achievements, true) : [];

                // Achievement 1: First Blood (Selesai step 1 pertama kali)
                if ($stepBaru == 2 && ! in_array('FIRST_BLOOD', $achievements)) {
                    $achievements[] = 'FIRST_BLOOD';
                }
                // Achievement 2: CRUD Master (Menyelesaikan 1 modul full)
                if ($isFinish && ! in_array('CRUD_MASTER', $achievements)) {
                    $achievements[] = 'CRUD_MASTER';
                }

                $user->achievements = json_encode($achievements);
                // Kita force save() langsung pake DB query karena user dari Auth kadang rewel kalau save langsung
                DB::table('users')->where('id', $user->id)->update([
                    'points' => $user->points,
                    'achievements' => $user->achievements,
                ]);
            }
            // ---------------------------

            $progress->update([
                'step_sekarang' => $stepBaru,
                'status' => $isFinish ? 'selesai' : 'sedang',
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Halaman Progress, Achievement, dan Leaderboard
     */
    public function progressSaya()
    {
        $user = Auth::user();

        // Ambil Top 10 High Scores
        $leaderboard = User::where('role', 'student')
            ->orderBy('points', 'desc')
            ->take(10)
            ->get();

        // Cari posisi rank user saat ini
        $myRank = User::where('role', 'student')
            ->where('points', '>', $user->points)
            ->count() + 1;

        $userAchievements = $user->achievements ? json_decode($user->achievements, true) : [];

        // Definisi Master Data Achievement (Bisa dipindah ke Trait nanti kalau makin banyak)
        $badges = [
            'FIRST_BLOOD' => ['nama' => 'First Blood', 'deskripsi' => 'Berhasil melewati rintangan pertama (Koneksi Database).', 'icon' => 'fa-droplet', 'warna' => 'text-red-500', 'bg' => 'bg-red-100 border-red-500'],
            'CRUD_MASTER' => ['nama' => 'CRUD Master', 'deskripsi' => 'Menyelesaikan modul pertama dengan sempurna.', 'icon' => 'fa-crown', 'warna' => 'text-yellow-500', 'bg' => 'bg-yellow-100 border-yellow-500'],
            'SPEED_RUNNER' => ['nama' => 'Speed Runner', 'deskripsi' => 'Menyelesaikan stage tanpa HINT (Coming Soon).', 'icon' => 'fa-bolt', 'warna' => 'text-blue-500', 'bg' => 'bg-blue-100 border-blue-500'],
        ];

        return view('belajar.progress', compact('user', 'leaderboard', 'myRank', 'badges', 'userAchievements'));
    }

    /**
     * Halaman Project Sandbox (Multi-file VS Code Clone)
     */
    public function project($studi_kasus)
    {
        $user = Auth::user();
        $blueprint = $this->getProjectPerpustakaan();

        // Ambil data project yang sudah dikerjakan user dari database
        $savedProjects = DB::table('user_projects')
            ->where('user_id', $user->id)
            ->where('studi_kasus', $studi_kasus)
            ->get()
            ->keyBy('file_name');

        $files = [];
        $previousCompleted = true; // File urutan 1 (koneksi.php) selalu terbuka

        // Gabungkan Master Blueprint dengan Data User
        foreach ($blueprint as $fileName => $data) {
            $saved = $savedProjects->get($fileName);
            $content = $saved ? $saved->content : '';
            $isCompleted = $saved ? (bool) $saved->is_completed : false;

            $files[$fileName] = [
                'order' => $data['order'],
                'judul' => $data['judul'],
                'teks' => $data['teks'],
                'instruksi' => $data['instruksi'],
                'kode_harapan' => $data['kode_harapan'],
                'hint' => $data['hint'],
                'content' => $content,
                'is_completed' => $isCompleted,
                'is_locked' => ! $previousCompleted, // Kunci jika file sebelumnya belum selesai
            ];

            // Status file ini menentukan apakah file selanjutnya terbuka atau terkunci
            $previousCompleted = $isCompleted;
        }

        return view('belajar.project', compact('files', 'studi_kasus'));
    }

    /**
     * Endpoint API Autosaver (Dipanggil via AJAX saat user ngetik)
     */
    public function saveProject(Request $request, $studi_kasus)
    {
        $user = Auth::user();
        $fileName = $request->input('file_name');
        $content = $request->input('content');
        $isCompleted = $request->input('is_completed');

        DB::table('user_projects')->updateOrInsert(
            ['user_id' => $user->id, 'studi_kasus' => $studi_kasus, 'file_name' => $fileName],
            ['content' => $content, 'is_completed' => $isCompleted, 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }
}
