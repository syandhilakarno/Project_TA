<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sidang;
use App\Models\Mahasiswa;

class SidangController extends Controller
{
    public function index()
    {
        $sidang = Sidang::with('mahasiswa')->get();
        return view('dashboard.koordinator.sidang', compact('sidang'));
    }
}