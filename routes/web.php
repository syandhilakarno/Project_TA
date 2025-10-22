<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController; // (Boleh ada untuk dashboard mhs)
use App\Http\Controllers\KoordinatorController; // (PENTING)
use App\Http\Controllers\DosenBimbinganController; // (PENTING)
// use App\Http\Controllers\SidangController; // (Logika sidang bisa di KoordinatorController)

// ... (Rute Login & Logout Anda) ...
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// 🔹 Role-based dashboards
Route::middleware(['auth'])->group(function () {
    // (Tambahkan route untuk TU dan Fakultas jika perlu)
    Route::get('/koordinator/dashboard', [KoordinatorController::class, 'dashboard'])->name('koordinator.dashboard');
    Route::get('/dosen/dashboard', [DosenBimbinganController::class, 'index'])->name('dosen.dashboard'); // Arahkan ke bimbingan
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
});


// 🔹 Khusus Koordinator
Route::middleware(['auth', 'role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function () {

    // List Mahasiswa
    Route::get('/listmahasiswa', [KoordinatorController::class, 'listmahasiswa'])->name('listmahasiswa');
    Route::post('/mahasiswa/move-to-ploting', [KoordinatorController::class, 'moveToPloting'])->name('mahasiswa.moveToPloting');

    // Ploting Pembimbing
    Route::get('/ploting-pembimbing', [KoordinatorController::class, 'Ploting'])->name('ploting-pembimbing'); // <-- INI YANG BENAR
    Route::put('/ploting-pembimbing/{id}', [KoordinatorController::class, 'updatePloting'])->name('updatePloting'); // <-- INI YANG BENAR
    Route::post('/ploting/update-bulk', [KoordinatorController::class, 'updatePlotingBulk'])->name('updatePlotingBulk');
    // Halaman Sidang
    Route::get('/sidang', [KoordinatorController::class, 'Sidang'])->name('sidang');

    // RUTE UNTUK UPDATE SIDANG (INI YANG ANDA BUTUHKAN)
    Route::put('/sidang/update/{id}', [KoordinatorController::class, 'updateSidang'])->name('updateSidang');
    Route::post('/sidang/update-bulk', [KoordinatorController::class, 'updateSidangBulk'])->name('updateSidangBulk');
});
// 🔹 Khusus Dosen
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {

    Route::get('/bimbingan', [DosenBimbinganController::class, 'index'])->name('bimbingan');
    Route::put('/bimbingan/update/{id}', [DosenBimbinganController::class, 'updateBimbingan'])->name('updateBimbingan');
    Route::post('/bimbingan/update-bulk', [DosenBimbinganController::class, 'updateBulk'])->name('updateBimbinganBulk');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    // Dashboard mahasiswa
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

    // Contoh route tambahan, misal melihat bimbingan
    Route::get('/bimbingan', [MahasiswaController::class, 'bimbingan'])->name('bimbingan');

    // Route lain bisa ditambahkan sesuai fitur mahasiswa
});
