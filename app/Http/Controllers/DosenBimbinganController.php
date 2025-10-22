<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Ini adalah kunci utamanya
use Illuminate\Support\Facades\DB;
// Kita tidak perlu 'App\Models\Dosen' lagi di sini

class DosenBimbinganController extends Controller
{
    /**
     * Menampilkan halaman daftar mahasiswa bimbingan.
     */
    public function index()
    {
        // 1. Dapatkan ID Dosen yang sedang login DARI TABEL USERS
        $dosenId = Auth::id(); // <-- Kembali ke cara yang simpel dan benar

        // 2. Ambil data bimbingan berdasarkan ID dari tabel 'users'
        // Ini akan cocok dengan ID yang disimpan oleh KoordinatorController
        $bimbingan = Bimbingan::where('dosen_id', $dosenId)
                            ->with('mahasiswa') 
                            ->orderBy('created_at', 'desc')
                            ->get();

        // 3. Kirim data yang sudah difilter ke view
        return view('dosen.bimbingan', compact('bimbingan'));
    }

    /**
     * Update data bimbingan untuk satu mahasiswa (via tombol simpan per baris).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'progres' => 'nullable|array',
            'progres.*' => 'string',
            'nilai' => 'nullable|string|max:10',
        ]);

        try {
            $dosenId = Auth::id(); // Ambil ID login dari tabel users
            $bimbingan = Bimbingan::findOrFail($id);

            // 3. Cek keamanan menggunakan Auth::id()
            if ($bimbingan->dosen_id !== $dosenId) {
                return redirect()->back()->with('error', 'Anda tidak berhak mengubah data ini.');
            }

            $bimbingan->update([
                'progres' => $request->progres ?? [], 
                'nilai' => $request->nilai,
            ]);

            return redirect()->back()->with('success', 'Data bimbingan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Update data bimbingan secara bulk (AJAX "Simpan Semua").
     */
    public function updateBulk(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
            'data.*.id' => 'required|integer|exists:bimbingans,id',
            'data.*.progres' => 'nullable|array',
            'data.*.nilai' => 'nullable|string|max:10',
        ]);

        $dosenId = Auth::id(); // Ambil ID login dari tabel users
        $dataToUpdate = $request->input('data');

        DB::beginTransaction();
        try {
            foreach ($dataToUpdate as $item) {
                $bimbingan = Bimbingan::find($item['id']); 

                // 4. Cek Keamanan menggunakan Auth::id()
                if ($bimbingan && $bimbingan->dosen_id == $dosenId) {
                    $bimbingan->update([
                        'progres' => $item['progres'] ?? [],
                        'nilai' => $item['nilai'] ?? null,
                    ]);
                }
            }
            DB::commit(); 
            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }
}