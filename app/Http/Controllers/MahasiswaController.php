<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\koordinator;


class MahasiswaController extends Controller
{
    // Tampilkan semua mahasiswa
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('koordinator.listmahasiswa', compact('mahasiswa'));
    }


    // Halaman plotting dosen pembimbing
    public function Ploting()
    {
        // Ambil semua mahasiswa
        $mahasiswa = \App\Models\Mahasiswa::all();

        // Ambil semua dosen
        $dosen = \App\Models\Dosen::all();

        return view('dashboard.koordinator.ploting-pembimbing', compact('mahasiswa', 'dosen'));


}


}