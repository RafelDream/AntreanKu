<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_pasien', ['baru', 'lama']);
            $table->string('nik', 16)->unique()->nullable();
            $table->string('no_rekam_medis', 20)->unique()->nullable();
            $table->date('tanggal_lahir');
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};