<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Kolom yang disembunyikan saat serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tipe data kolom tertentu
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi: user sebagai dosen punya banyak bimbingan
     */
    public function bimbinganMahasiswa()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }

    /**
     * Relasi: jika user adalah mahasiswa, bisa ambil profil mahasiswa
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }
}
