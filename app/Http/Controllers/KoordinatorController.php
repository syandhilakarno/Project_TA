<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;       // <-- BENAR: Pakai User
use App\Models\Sidang;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 

class KoordinatorController extends Controller
{
    public function dashboard()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalSidang = Sidang::count();
        
        // (Pastikan Anda punya view 'dashboard.koordinator.koordinator')
        return view('dashboard.koordinator.koordinator', compact('totalMahasiswa', 'totalSidang'));
    }

    // List mahasiswa (HANYA YANG BELUM DIPLOTING)
    public function listmahasiswa() 
    {
        $mahasiswa = Mahasiswa::where('status_ploting', 'pending')
                              ->with('user') // Ambil data 'user' untuk tampilkan nama
                              ->get(); 
        return view('dashboard.koordinator.listmahasiswa', compact('mahasiswa'));
    }

    // Pindahkan mahasiswa ke ploting
    public function moveToPloting(Request $request)
    {
        $ids = $request->input('ids', []);
        if(empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada mahasiswa yang dipilih.'], 400);
        }
        $request->validate(['ids.*' => 'integer|exists:mahasiswa,id']);
        
        Mahasiswa::whereIn('id', $ids)->update(['status_ploting' => 'ready']);

        return response()->json(['success' => true, 'message' => 'Mahasiswa berhasil dipindahkan ke ploting.']);
    }

    // Halaman plotting pembimbing
    public function Ploting()
    {
        $mahasiswa = Mahasiswa::where('status_ploting', 'ready')
                              ->with('user', 'dosen') // Ambil relasi user (nama mhs) dan dosen
                              ->get(); 
        
        // Ambil dosen dari tabel 'users'
        $dosen = User::where('role', 'dosen') 
                     ->orderBy('name', 'asc')
                     ->get();

        return view('dashboard.koordinator.ploting-pembimbing', compact('mahasiswa', 'dosen'));
    }

    // Update 1 plotting dosen
    public function updatePloting(Request $request, $id)
    {
        // Validasi ke tabel 'users'
        $request->validate([
            'dosen_id' => 'required|integer|exists:users,id'
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id); // $id = mahasiswa.id
        $mahasiswa->dosen_id = $request->input('dosen_id');
        $mahasiswa->save();

        // Buat data di tabel Bimbingan agar muncul di halaman Dosen
        Bimbingan::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id], 
            [
                'dosen_id' => $mahasiswa->dosen_id, // Ini adalah users.id
                'periode' => $mahasiswa->periode ?? date('Y'),
            ]
        );

        return redirect()->route('koordinator.ploting-pembimbing')->with('success', 'Dosen pembimbing berhasil diperbarui.');
    }

    // Update plotting massal
    public function updatePlotingBulk(Request $request)
    {
        // Validasi ke tabel 'users'
        $data = $request->validate([
            'assignments' => 'required|array',
            'assignments.*.id' => 'required|integer|exists:mahasiswa,id',
            'assignments.*.dosen_id' => 'required|integer|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($data['assignments'] as $assignment) {
                $mahasiswa = Mahasiswa::find($assignment['id']);
                if ($mahasiswa) {
                    $mahasiswa->dosen_id = $assignment['dosen_id'];
                    $mahasiswa->save();

                    Bimbingan::firstOrCreate(
                        ['mahasiswa_id' => $mahasiswa->id],
                        [
                            'dosen_id' => $mahasiswa->dosen_id,
                            'periode' => $mahasiswa->periode ?? date('Y'),
                        ]
                    );
                }
            }
            DB::commit();
            return response()->json(['message' => 'Ploting pembimbing berhasil diperbarui.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk ploting error: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan.'], 500);
        }
    }
// ... (Fungsi-fungsi Anda yang lain) ...

    // FUNGSI SIDANG YANG BENAR
    public function Sidang()
    {
        // Ambil data sidang
        // Eager load 'mahasiswa' dan 'mahasiswa.user' (untuk nama mahasiswa)
        $sidang = Sidang::with('mahasiswa.user')->get(); 
        
        // Ambil SEMUA staf (Dosen, Koor, dll) dari tabel 'users'
        // untuk dipakai sebagai Ketua Sidang & Penguji.
        // Kita filter semua yang BUKAN mahasiswa.
        $dosen = User::where('role', '!=', 'mahasiswa')
                     ->orderBy('name', 'asc')
                     ->get(); 

        // Pastikan view Anda ada di:
        // resources/views/dashboard/koordinator/sidang.blade.php
        return view('dashboard.koordinator.sidang', compact('sidang', 'dosen'));
    }

    // ... (Fungsi updateSidang dan updateSidangBulk Anda) ...
    // Pastikan validasi di fungsi ini juga merujuk ke tabel 'users'
    
    public function updateSidang(Request $request, $id)
    {
        $request->validate([
            'tanggal_sidang' => 'required|date',
            'ketua_id' => 'required|integer|exists:users,id', // Validasi ke users
            'penguji_id' => 'required|integer|exists:users,id', // Validasi ke users
            'penguji2_id' => 'required|integer|exists:users,id', // Validasi ke users
            'ruang_sidang' => 'required|string',
        ]);
        
        $sidang = \App\Models\Sidang::findOrFail($id);
        $sidang->update($request->all());
        
        return redirect()->back()->with('success', 'Jadwal sidang berhasil diperbarui!');
    }

    public function updateSidangBulk(Request $request)
    {
        // Validasi data bulk (jika perlu)
        
        $data = $request->input('data', []);
        foreach ($data as $item) {
            $sidang = \App\Models\Sidang::find($item['id']);
            if ($sidang) {
                $sidang->update([
                    'tanggal_sidang' => $item['tanggal_sidang'],
                    'ketua_id' => $item['ketua_id'],
                    'penguji_id' => $item['penguji_id'],
                    'penguji2_id' => $item['penguji2_id'],
                    'ruang_sidang' => $item['ruang_sidang'],
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
}