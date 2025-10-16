<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen; 

class MahasiswaController extends Controller
{
    // Tampilkan semua mahasiswa
public function index()
{
    $mahasiswa = Mahasiswa::all();
    return view('koordinator.list-mahasiswa', compact('mahasiswa'));
}


    // Proses approve mahasiswa
    public function approve(Request $request)
    {
        $ids = $request->input('selected', []);
        if (count($ids) > 0) {
            Mahasiswa::whereIn('id', $ids)->update(['status' => 'Disetujui']);
        }

        return redirect()->back()->with('success', 'Mahasiswa berhasil di-approve.');
    }

    // Halaman plotting dosen pembimbing
public function ploting()
{
    $mahasiswa = Mahasiswa::all();
    $dosen = Dosen::all(); // pastikan ada model Dosen
        return view('dashboard.koordinator.ploting-pembimbing', compact('mahasiswa', 'dosen'));
}
}