<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Membuat 30 data mahasiswa dummy...');

        for ($i = 0; $i < 30; $i++) {
            $nim = '12401' . fake()->unique()->numerify('#####');
            $nama = fake()->name();
            $email = $nim . '@mhs.ubpkarawang.ac.id';

            // 1. Buat akun LOGIN di tabel 'users'
            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            // 2. Buat akun PROFIL di tabel 'mahasiswa'
            //    (Tanpa nama, email, atau password)
            Mahasiswa::create([
                'nama' => $nama,
                'user_id' => $user->id, // Hubungkan ke user
                'nim' => $nim,
                'periode' => fake()->randomElement(['2023', '2024', '2025']),
                'sks' => fake()->numberBetween(80, 130),
                'ipk' => fake()->randomFloat(2, 2.75, 3.85),
                'nilai_kp' => fake()->randomElement(['A', 'A-', 'B+', 'B']),
                'status_ploting' => 'pending',
                'dosen_id' => null, 
            ]);
        }
        $this->command->info('Pembuatan data mahasiswa selesai.');
    }
}