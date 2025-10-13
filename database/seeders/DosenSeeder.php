<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        Dosen::create([
            'nama' => 'Dr. Budi Santoso',
            'nidn' => '1234567890',
            'email' => 'budi@kampus.ac.id',
            'no_hp' => '081234567890',
        ]);

        Dosen::create([
            'nama' => 'Ir. Sari Lestari, M.Kom',
            'nidn' => '0987654321',
            'email' => 'sari@kampus.ac.id',
            'no_hp' => '081298765432',
        ]);
         Dosen::create([
            'nama' => 'Dr. Andi Saputra',
            'nidn' => '1234567891',
            'email' => 'andi@ubp.com',
            'no_hp' => '081234567890'
        ]);

        Dosen::create([
            'nama' => 'Dr. Rina Marlina',
            'nidn' => '0987654322',
            'email' => 'rina@ubp.com',
            'no_hp' => '081987654321'
        ]);

        Dosen::create([
            'nama' => 'Ir. Budi Santoso',
            'nidn' => '1122334455',
            'email' => 'budi@ubp.com',
            'no_hp' => '082233445566'
        ]);
    }
}
