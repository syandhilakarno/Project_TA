<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sidang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('judul_ta')->nullable();
            $table->date('tanggal_sidang')->nullable();
            $table->string('ketua_id')->nullable();
            $table->string('penguji_id')->nullable();
            $table->string('penguji2_id')->nullable();
            $table->string('ruang_sidang')->nullable();
            
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sidang');
    }
};
