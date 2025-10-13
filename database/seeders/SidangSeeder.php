<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sidang;

class SidangSeeder extends Seeder
{
    public function run(): void
    {
        Sidang::create([
            'mahasiswa_id' => 1,
            'judul_ta' => 'Implementasi Sistem Informasi Tugas Akhir',
            'tanggal_sidang' => '2025-11-20',
            'penguji' => 'Dr. Andi Saputra',
        ]);

        Sidang::create([
            'mahasiswa_id' => 2,
            'judul_ta' => 'Analisis dan Desain Aplikasi Mobile',
            'tanggal_sidang' => '2025-12-02',
            'penguji' => 'Dr. Siti Nurhaliza',
        ]);
    }
}
