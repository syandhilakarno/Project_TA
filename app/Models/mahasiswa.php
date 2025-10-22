<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Pastikan Anda menggunakan nama model 'Mahasiswa'
// dan nama tabel 'mahasiswa' (singular)
class Mahasiswa extends Model 
{
    use HasFactory;     

    // Nama tabel Anda (jika singular)
    protected $table = 'mahasiswa'; 

    protected $fillable = [
        'nama',
        'user_id',
        'nim',
        'periode',
        'status',
        'sks',
        'ipk',
        'nilai_kp',
        'status_ploting',
        'dosen_id',
    ];

    /**
     * Relasi ke tabel 'users' untuk data login/nama.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel 'users' untuk Dosen Pembimbing.
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // ... relasi lain seperti bimbingan ...
    public function bimbingan()
    {
        return $this->hasOne(Bimbingan::class, 'mahasiswa_id');
    }
}