<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;        // Model Dosen (User)
use App\Models\Mahasiswa;  // Model Mahasiswa
use App\Models\Bimbingan;  // Model Bimbingan
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Kosongkan tabel bimbingan & mahasiswa agar data tidak duplikat
        // Ini aman karena tidak menghapus tabel 'users' Anda
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Bimbingan::truncate();
        Mahasiswa::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Buat 2 Akun Dosen (atau temukan jika emailnya sudah ada)
        // Anda bisa login dengan akun ini. Passwordnya: "password"
        $dosen1 = User::firstOrCreate(
            ['email' => 'budi.santoso@ubpkarawang.ac.id'], // Email untuk login
            [
                'name' => 'Dr. Budi Santoso, M.Kom.',
                'password' => Hash::make('password')
                // tambahkan role atau field lain jika perlu
            ]
        );

        $dosen2 = User::firstOrCreate(
            ['email' => 'retno.wulandari@ubpkarawang.ac.id'], // Email untuk login
            [
                'name' => 'Prof. Retno Wulandari, S.T., M.T.',
                'password' => Hash::make('password')
            ]
        );

        // 3. Buat 30 Mahasiswa palsu menggunakan factory
        $mahasiswa = Mahasiswa::factory(30)->create();

        // 4. Hubungkan Mahasiswa ke Dosen melalui tabel Bimbingan

        // Ambil 15 mahasiswa pertama untuk Dosen 1
        $mahasiswa->slice(0, 15)->each(function ($mhs) use ($dosen1) {
            Bimbingan::factory()->create([
                'mahasiswa_id' => $mhs->id,
                'dosen_id' => $dosen1->id,
            ]);
        });

        // Ambil 15 mahasiswa berikutnya untuk Dosen 2
        $mahasiswa->slice(15, 15)->each(function ($mhs) use ($dosen2) {
            Bimbingan::factory()->create([
                'mahasiswa_id' => $mhs->id,
                'dosen_id' => $dosen2->id,
            ]);
        });

        // Memberi info di terminal
        $this->command->info('Database seeding complete!');
        $this->command->info('Anda bisa login sebagai (password: "password"):');
        $this->command->info('- ' . $dosen1->email);
        $this->command->info('- ' . $dosen2->email);
    }
}