<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id();
            
            // Asumsi Anda punya tabel 'mahasiswa' dan 'users' (untuk dosen)
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            
            $table->string('periode')->nullable();
            
            // Gunakan json() atau text() untuk menyimpan array 'progres'
            // json() lebih baik jika database Anda mendukung (MySQL 5.7+, PostgreSQL)
            $table->json('progres')->nullable(); 
            
            $table->string('nilai', 10)->nullable(); // Cukup untuk nilai "A" atau "85.5"
            $table->timestamps();
            
            // Opsional: pastikan satu mahasiswa hanya punya satu data bimbingan
            $table->unique(['mahasiswa_id', 'dosen_id']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('bimbingan');
    }
};