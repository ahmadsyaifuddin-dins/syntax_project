<?php

namespace App\Http\Controllers;

use App\Models\ProgressBelajar;
use App\Traits\MateriBelajarTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Endpoint API untuk menyimpan progress belajar siswa
     */
    public function updateProgress(Request $request, $studi_kasus)
    {
        $progress = ProgressBelajar::where('user_id', Auth::id())
            ->where('studi_kasus', $studi_kasus)
            ->first();

        if ($progress) {
            $progress->update([
                'step_sekarang' => $request->input('step'),
                'status' => $request->input('is_finish') ? 'selesai' : 'sedang',
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
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
}
