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
    
    public function moveToPloting(Request $request)
    {
        $ids = $request->input('ids', []);
        if(empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada mahasiswa yang dipilih.'], 400);
        }
        $request->validate(['ids.*' => 'integer|exists:mahasiswa,id']);
        
        // Update statusnya
        Mahasiswa::whereIn('id', $ids)->update(['status_ploting' => 'ready']);

        return response()->json(['success' => true, 'message' => 'Mahasiswa berhasil dipindahkan ke ploting.']);
    }

    // 3. PERBAIKI FUNGSI Ploting()
// MENJADI SEPERTI INI:
public function Ploting()
    {
       $mahasiswa = Mahasiswa::where('status_ploting', 'ready')
                              ->with('dosen', 'user') // <-- TAMBAHKAN 'user' DI SINI
                              ->get(); 
        
        $dosen = User::where('role', 'dosen') 
                     ->orderBy('name', 'asc')
                     ->get();

        return view('dashboard.koordinator.ploting-pembimbing', compact('mahasiswa', 'dosen'));
    }



    // 4. PERBAIKI FUNGSI updatePloting() (Simpan 1 baris)
    public function updatePloting(Request $request, $id)
    {
        // Validasi ke tabel 'users'
        $request->validate([
            'dosen_id' => 'required|integer|exists:users,id'
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->dosen_id = $request->input('dosen_id');
        $mahasiswa->save();

        // !! INI BAGIAN PENTING !!
        // Buat data di tabel Bimbingan agar muncul di halaman Dosen
        Bimbingan::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id], // Cari berdasarkan mhs_id
            [
                'dosen_id' => $mahasiswa->dosen_id, // Isi data jika baru
                'periode' => $mahasiswa->periode ?? date('Y'), // Ambil periode mhs
            ]
        );

        return redirect()->route('koordinator.ploting-pembimbing')->with('success', 'Dosen pembimbing berhasil diperbarui.');
    }

    // 5. PERBAIKI FUNGSI updatePlotingBulk() (Simpan Semua)
    public function updatePlotingBulk(Request $request)
    {
        Log::info('Bulk plotting received:', $request->all());

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

                    // !! INI BAGIAN PENTING !!
                    // Buat juga data bimbingannya
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

    // ... (Salin sisa fungsi Anda seperti Sidang, updateSidang, dll di sini) ...
// MENJADI INI:
    public function Sidang()
    {
        $sidang = Sidang::with('mahasiswa')->get();
        
        // Query ini SUDAH BENAR
        $dosen = User::where('role', 'dosen')
                     ->orderBy('name', 'asc')
                     ->get(); 

        return view('dashboard.koordinator.sidang', compact('sidang', 'dosen'));
    }

    public function updateSidang(Request $request, $id)
    {
        $sidang = \App\Models\Sidang::findOrFail($id);
        $sidang->update([
            'tanggal_sidang' => $request->tanggal_sidang,
            'ketua_id' => $request->ketua_id,
            'penguji_id' => $request->penguji_id,
            'penguji2_id' => $request->penguji2_id,
            'ruang_sidang' => $request->ruang_sidang,
        ]);
        return redirect()->back()->with('success', 'Jadwal sidang berhasil diperbarui!');
    }

    public function updateSidangBulk(Request $request)
    {
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

    // ... (sisanya)
}