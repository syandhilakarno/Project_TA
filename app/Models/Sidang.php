<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    use HasFactory;

    protected $table = 'sidang'; // penting!

    protected $fillable = [
        'mahasiswa_id',
        'judul_ta',
        'tanggal_sidang',
        'penguji',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
