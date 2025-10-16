<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class KoordinatorController extends Controller
{
    public function listMahasiswa()
    {
        $mahasiswa = Mahasiswa::all();
        return view('dashboard.koordinator.list-mahasiswa', compact('mahasiswa'));
    }
}