<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'mahasiswa';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'nim',
        'periode',
        'status',
        'sks',
        'ipk_1_2',
        'ipk_3_4',
        'ipk_5_6',
        'ipk_7_8',
    ];
}
