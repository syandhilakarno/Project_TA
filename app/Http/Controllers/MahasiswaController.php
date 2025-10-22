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
            // Jika user login sebagai mahasiswa tapi tidak punya profil
            Auth::logout();
            return redirect('/login')->with('msg', 'Profil mahasiswa Anda tidak ditemukan.');
        }

        // 3. Ambil data bimbingan mahasiswa ini (relasi ke dosen)
        $bimbingan = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->with('dosen')
            ->first();

        // 4. Kirim data ke view (sesuaikan path dengan folder dashboard)
        return view('dashboard.mahasiswa.dashboard', compact('mahasiswa', 'bimbingan'));
    }
}
