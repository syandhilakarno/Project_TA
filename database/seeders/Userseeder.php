<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@bspg.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Koordinator
        User::updateOrCreate(
            ['email' => 'koordinator@bspg.com'],
            [
                'name' => 'Koordinator Prodi',
                'password' => Hash::make('koor123'),
                'role' => 'koordinator',
            ]
        );

        // Dosen
        User::updateOrCreate(
            ['email' => 'dosen@bspg.com'],
            [
                'name' => 'Dosen',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
            ]
        );

        // Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@bspg.com'],
            [
                'name' => 'Mahasiswa',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
            ]
        );

        // Contoh akun tambahan khusus Prodi/Dosen/Mahasiswa
        User::updateOrCreate(
            ['email' => 'budi.santoso@ubpkarawang.ac.id'],
            [
                'name' => 'Dr. Budi Santoso, M.Kom.',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        User::updateOrCreate(
            ['email' => 'retno.wulandari@ubpkarawang.ac.id'],
            [
                'name' => 'Prof. Retno Wulandari, S.T., M.T.',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        User::updateOrCreate(
            ['email' => '23416255@mhs.ubpkarawang.ac.id'],
            [
                'name' => 'Mahasiswa UBP 1',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );
    }
}
