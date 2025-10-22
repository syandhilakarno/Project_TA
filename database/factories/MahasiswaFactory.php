<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; // <-- 1. PASTIKAN INI ADA

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nim = $this->faker->unique()->numerify('124010####'); 
        
        return [
            'nama' => $this->faker->name(),
            'nim' => $nim,
            'email' => $nim . '@mhs.ubpkarawang.ac.id',
            'periode' => $this->faker->randomElement(['2024', '2023', '2025']),
            'status' => $this->faker->randomElement(['Aktif', 'Cuti', 'Lulus', 'Drop Out','Non-Aktif']),
            'sks' => $this->faker->numberBetween(20, 144),
            'ipk' => $this->faker->randomFloat(2, 2.0, 4.0),
            'nilai_kp' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', null]),
            'password' => Hash::make('password'),
        ];
    }
}