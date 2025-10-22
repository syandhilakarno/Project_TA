<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Dosen;


class Sidang extends Model
{
    use HasFactory;

    protected $table = 'sidang';

    protected $fillable = [
        'mahasiswa_id',
        'judul_ta',
        'tanggal_sidang',
        'ketua_id',
        'penguji_id',
        'penguji2_id',
        'ruang_sidang',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
    public function penguji() 
    {
        return $this->belongsTo(Dosen::class, 'penguji_id');
    }


    public function penguji2() 
    {
        return $this->belongsTo(Dosen::class, 'penguji2_id');
    }


    public function ketua() 
    {
        return $this->belongsTo(Dosen::class, 'ketua_id');
    }

}