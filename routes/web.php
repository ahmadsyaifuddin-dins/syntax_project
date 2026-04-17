<?php

use App\Http\Controllers\Admin\BukuController as AdminBukuController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman Landing Page (Public)
Route::get('/', function () {
    return view('welcome');
});
Route::get('/demo/tutorial', [BelajarController::class, 'demoTutorial'])->name('belajar.demo.tutorial');

Route::get('/demo/{studi_kasus}', [BelajarController::class, 'demo'])->name('belajar.demo');

// Middleware Authenticated (Harus Login)
Route::middleware(['auth', 'verified'])->group(function () {

    // Rute Global (Bisa diakses Admin & Student)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // AREA ADMIN
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::resource('buku', AdminBukuController::class);
        Route::resource('kategori', KategoriController::class);
    });

    // ==========================================
    // AREA STUDENT (PESERTA BELAJAR)
    // ==========================================
    Route::middleware(['role:student,admin'])->prefix('belajar')->group(function () {
        Route::get('/dashboard', [BelajarController::class, 'index'])->name('belajar.index');

        // Rute untuk Project Sandbox Multi-file
        Route::get('/project/{studi_kasus}', [BelajarController::class, 'project'])->name('belajar.project');
        Route::post('/project/{studi_kasus}/save', [BelajarController::class, 'saveProject'])->name('belajar.project.save');

        // 1. Rute statis di atas
        Route::get('/tutorial', [BelajarController::class, 'tutorial'])->name('belajar.tutorial');

        // 2. Rute dinamis di bawah
        Route::get('/workspace/{studi_kasus}', [BelajarController::class, 'workspace'])->name('belajar.workspace');
        Route::post('/workspace/{studi_kasus}/progress', [BelajarController::class, 'updateProgress'])->name('belajar.progress');

        Route::get('/progress', [BelajarController::class, 'progressSaya'])->name('belajar.progress.saya');

    });

});

require __DIR__.'/auth.php';
