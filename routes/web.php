<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\KategoriController; // (Uncomment nanti kalau sudah dibuat)
use Illuminate\Support\Facades\Route;

// Halaman Landing Page (Public)
Route::get('/', function () {
    return view('welcome');
});

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
        Route::resource('buku', BukuController::class);
        // Route::resource('kategori', KategoriController::class);
    });

    // ==========================================
    // AREA STUDENT (PESERTA BELAJAR)
    // ==========================================
    Route::middleware(['role:student'])->prefix('belajar')->group(function () {
        // Nanti kita isi dengan rute halaman interactive guide
        // Route::get('/studi-kasus', [BelajarController::class, 'index'])->name('belajar.index');
    });

});

require __DIR__.'/auth.php';
