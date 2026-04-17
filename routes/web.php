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
        Route::get('/workspace/{studi_kasus}', [BelajarController::class, 'workspace'])->name('belajar.workspace');
        Route::post('/workspace/{studi_kasus}/progress', [BelajarController::class, 'updateProgress'])->name('belajar.progress');
    });

});

require __DIR__.'/auth.php';
