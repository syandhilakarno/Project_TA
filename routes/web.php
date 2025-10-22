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

//  Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//  Role-base dashboards
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->get('/admin', fn() => view('dashboard.admin.admin'))->name('admin.dashboard');
    Route::middleware(['role:koordinator'])->get('/koordinator', fn() => view('dashboard.koordinator.koordinator'))->name('koordinator.dashboard');
    Route::middleware(['role:dosen'])->get('/dosen', fn() => view('dashboard.dosen.dosen'))->name('dosen.dashboard');
    Route::middleware(['role:mahasiswa'])->get('/mahasiswa', fn() => view('dashboard.mahasiswa.mahasiswa'))->name('mahasiswa.dashboard');
});

//  Khusus Koordinator
Route::middleware(['auth', 'role:koordinator'])->prefix('koordinator')->group(function () {
    Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('koordinator.dashboard');

    // List semua mahasiswa
    Route::get('/listmahasiswa', [KoordinatorController::class, 'listmahasiswa'])->name('koordinator.listmahasiswa');
    Route::post('/koordinator/mahasiswa/bulk-update', [KoordinatorController::class, 'bulkUpdate'])->name('koordinator.mahasiswa.bulkUpdate');
    Route::post('/koordinator/mahasiswa/move-to-ploting', [KoordinatorController::class, 'moveToPloting'])->name('koordinator.mahasiswa.moveToPloting');



    //  Halaman ploting dosen pembimbing
    Route::get('/ploting-pembimbing', [KoordinatorController::class, 'Ploting'])->name('koordinator.ploting-pembimbing');
    Route::post('/update-ploting-bulk', [KoordinatorController::class, 'updatePlotingBulk'])->name('koordinator.updatePlotingBulk');
    Route::put('/ploting-pembimbing/{id}', [KoordinatorController::class, 'updatePloting'])->name('koordinator.updatePloting');

    //  Halaman sidang
    Route::get('/sidang', [KoordinatorController::class, 'Sidang'])->name('koordinator.sidang');
    Route::put('/sidang/update-tanggal/{id}', [KoordinatorController::class, 'updateTanggalSidang'])->name('koordinator.updateTanggalSidang');
    Route::post('/sidang-bulk', [KoordinatorController::class, 'updateSidangBulk'])->name('koordinator.updateSidangBulk');
    Route::put('/sidang/{id}', [KoordinatorController::class, 'updateSidang'])->name('koordinator.updateSidang');

});

//  Khusus Dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/bimbingan', [DosenBimbinganController::class, 'index'])->name('bimbingan');
        Route::put('/bimbingan/update/{id}', [DosenBimbinganController::class, 'update'])->name('updateBimbingan');
        Route::post('/bimbingan/update-bulk', [DosenBimbinganController::class, 'updateBulk'])->name('updateBimbinganBulk');
});

// Route Tata Usaha
//Route::get('/tata-usaha/dashboard', [TataUsahaController::class, 'dashboard'])->name('tata_usaha.dashboard');
// Route Fakultas
//Route::get('/fakultas/dashboard', [FakultasController::class, 'dashboard'])->name('fakultas.dashboard');