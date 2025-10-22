<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
// 1. GANTI Dosen ke User
use App\Models\User;       
use App\Models\Sidang;
// 2. TAMBAHKAN Bimbingan
use App\Models\Bimbingan;  
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class KoordinatorController extends Controller
{
    // ... (fungsi dashboard dan listMahasiswa Anda sudah OK) ...
    public function dashboard()
    {
        $mahasiswa = Mahasiswa::all();
        $totalMahasiswa = $mahasiswa->count();
        $totalSidang = Sidang::count();
        return view('dashboard.koordinator.koordinator', compact('mahasiswa', 'totalMahasiswa', 'totalSidang'));
    }

    public function listMahasiswa()
    {
        // Gunakan filter status_ploting yang sudah kita buat
        $mahasiswa = Mahasiswa::where('status_ploting', 'pending')->get(); 
        return view('dashboard.koordinator.listmahasiswa', compact('mahasiswa'));
    }
}

