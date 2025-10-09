<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard berdasarkan role
Route::get('/admin', fn() => view('dashboard.admin'))->middleware('auth.role:admin');
Route::get('/koordinator', fn() => view('dashboard.koordinator'))->middleware('auth.role:koordinator');
Route::get('/dosen', fn() => view('dashboard.dosen'))->middleware('auth.role:dosen');
Route::get('/mahasiswa', fn() => view('dashboard.mahasiswa'))->middleware('auth.role:mahasiswa');
