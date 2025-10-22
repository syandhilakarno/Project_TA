<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bimbingan>
 */
class BimbinganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Daftar progres yang sama dengan yang ada di view Anda
        $langkahBimbingan = [
            'ACC Judul',
            'ACC BAB 1',
            'ACC BAB 2',
            'ACC BAB 3',
            'ACC BAB 4',
            'ACC BAB 5',
            'Seminar Proposal',
            'Sidang Akhir'
        ];

        // Ambil beberapa langkah progres secara acak (antara 0 s/d semua langkah)
        $jumlahProgres = $this->faker->numberBetween(0, count($langkahBimbingan));

        $progres = $jumlahProgres > 0 ? 
                   $this->faker->randomElements($langkahBimbingan, $jumlahProgres) : 
                   []; // Penting: harus array kosong jika tidak ada progres

        return [
            'periode' => $this->faker->randomElement(['2024/2025 Ganjil', '2023/2024 Genap', '2023/2024 Ganjil']),
            'progres' => $progres, // Factory akan otomatis encode ini ke JSON
            'nilai' => $this->faker->randomElement(['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', null, null, null]),

            // 'mahasiswa_id' dan 'dosen_id' akan kita isi dari Seeder
        ];
    }
}