<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        Mahasiswa::create([
            'nama' => 'Rafly Nugraha',
            'nim' => '231001',
            'periode' => '2025',
            'status' => 'Disetujui',
            'sks' => 144,
            'ipk_1_2' => 3.25,
            'ipk_3_4' => 3.35,
            'ipk_5_6' => 3.45,
            'ipk_7_8' => 3.60,

        ]);

        Mahasiswa::create([
            'nama' => 'Lina Oktavia',
            'nim' => '231002',
            'periode' => '2025',
            'status' => 'Menunggu',
            'sks' => 140,
            'ipk_1_2' => 3.10,
            'ipk_3_4' => 3.20,
            'ipk_5_6' => 3.30,
            'ipk_7_8' => 3.40
        ]);
    }
}
