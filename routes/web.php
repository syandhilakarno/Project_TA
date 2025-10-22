<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\DosenBimbinganController; 
// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// 🔹 Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 Role-based dashboards
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->get('/admin', fn() => view('dashboard.admin.admin'))->name('admin.dashboard');
    Route::middleware(['role:koordinator'])->get('/koordinator', fn() => view('dashboard.koordinator.koordinator'))->name('koordinator.dashboard');
    Route::middleware(['role:dosen'])->get('/dosen', fn() => view('dashboard.dosen.dosen'))->name('dosen.dashboard');
    Route::middleware(['role:mahasiswa'])->get('/mahasiswa', fn() => view('dashboard.mahasiswa.mahasiswa'))->name('mahasiswa.dashboard');
});

// 🔹 Khusus Koordinator
Route::middleware(['auth', 'role:koordinator'])->prefix('koordinator')->group(function () {
    Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('koordinator.dashboard');

    // List semua mahasiswa
    Route::get('/listmahasiswa', [KoordinatorController::class, 'listmahasiswa'])->name('koordinator.listmahasiswa');
    Route::post('/koordinator/mahasiswa/bulk-update', [KoordinatorController::class, 'bulkUpdate'])->name('koordinator.mahasiswa.bulkUpdate');
    Route::post('/koordinator/mahasiswa/move-to-ploting', [KoordinatorController::class, 'moveToPloting'])->name('koordinator.mahasiswa.moveToPloting');



    // ✅ Halaman plotting pembimbing
    Route::get('/ploting-pembimbing', [MahasiswaController::class, 'ploting'])->name('koordinator.ploting');
    Route::put('/ploting-pembimbing/{id}', [MahasiswaController::class, 'updatePloting'])->name('koordinator.updatePloting');

    // ✅ Halaman sidang
    Route::get('/koordinator/sidang', [SidangController::class, 'index'])->name('koordinator.sidang');
});
