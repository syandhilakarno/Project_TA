<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@bspg.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Koordinator',
            'email' => 'koor@bspg.com',
            'password' => Hash::make('koor123'),
            'role' => 'koordinator'
        ]);

        User::create([
            'name' => 'Dosen',
            'email' => 'dosen@bspg.com',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen'
        ]);

        User::create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@bspg.com',
            'password' => Hash::make('mhs123'),
            'role' => 'mahasiswa'
        ]);
    }
}
