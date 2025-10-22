<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Menggunakan nama 'mahasiswa' (singular) sesuai controller Anda
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();

            // 1. KUNCI UTAMA: Penghubung ke tabel 'users'
            //    Satu user hanya boleh punya satu profil mahasiswa
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // 2. Data profil/akademik mahasiswa
            $table->string('nama');
            $table->string('nim')->unique();
            $table->string('periode')->nullable();
            $table->string('status')->nullable()->default('Aktif');
            $table->integer('sks')->nullable()->default(0);
            $table->decimal('ipk', 3, 2)->nullable()->default(0.00); // Format 0.00 s/d 4.00
            $table->string('nilai_kp', 5)->nullable();

            // 3. Status untuk ploting koordinator
            $table->string('status_ploting')->default('pending');

            // 4. KUNCI KEDUA: Penghubung ke Dosen Pembimbing
            //    Kolom ini merujuk ke 'users.id' (yang memiliki role 'dosen')
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswa');
    }
};