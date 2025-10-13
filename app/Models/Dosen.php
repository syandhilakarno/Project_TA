<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nama',
        'nidn',
        'email',
        'no_hp',
    ];

    // Relasi ke tabel mahasiswa (jika nanti 1 dosen bisa membimbing banyak mahasiswa)
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_id');
    }
}
