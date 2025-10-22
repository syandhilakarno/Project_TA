<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'bimbingan';

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'periode',
        'progres',
        'nilai',
    ];

    /**
     * Otomatis mengubah kolom 'progres' dari JSON/text di database
     * menjadi array di Laravel, dan sebaliknya.
     * Ini adalah bagian KUNCI untuk fitur checklist Anda.
     */
    protected $casts = [
        'progres' => 'array',
    ];

    /**
     * Relasi ke Mahasiswa (Satu Bimbingan dimiliki oleh Satu Mahasiswa)
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke Dosen (Satu Bimbingan dimiliki oleh Satu Dosen/User)
     */
    public function dosen()
    {
        // Asumsi 'dosen_id' merujuk ke tabel 'users'
        return $this->belongsTo(User::class, 'dosen_id');
    }
}