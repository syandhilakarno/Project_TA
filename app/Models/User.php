<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Bimbingan;
class User extends Authenticatable

{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function bimbinganMahasiswa()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }
}
