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
        'user_id', // pastikan bisa diisi
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke mahasiswa yang dibimbing
     */
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_id');
    }

    /**
     * Relasi ke sidang sebagai penguji
     */
    public function sidangDiuji()
    {
        return $this->hasMany(Sidang::class, 'penguji_id');
    }
}
