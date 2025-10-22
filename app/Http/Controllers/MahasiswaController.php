<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan dashboard untuk mahasiswa yang sedang login.
     */
    public function dashboard()
    {
        // 1. Dapatkan ID user yang login
        $userId = Auth::id();

        // 2. Cari profil mahasiswa yang terhubung dengan user ini
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            // Ini terjadi jika user login sebagai mhs tapi tidak punya profil
            Auth::logout();
            return redirect('/login')->with('msg', 'Profil mahasiswa Anda tidak ditemukan.');
        }

        // 3. Ambil data bimbingan mahasiswa ini
        $bimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
                              ->with('dosen') // Ambil data dosen pembimbing
                              ->first();

        // 4. Kirim data ke view
        return view('dashboard.mahasiswa.mahasiswa', compact('mahasiswa', 'bimbingan'));
    }

    // HAPUS SEMUA FUNGSI LAIN DARI FILE INI
    // (Fungsi 'index', 'Ploting', 'updatePloting' BUKAN milik controller ini)
}