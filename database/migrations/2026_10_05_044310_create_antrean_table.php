<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrean', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->unsignedInteger('nomor_antrean');
            $table->date('tanggal_antrean');
            $table->enum('status', ['menunggu', 'dipanggil', 'hadir', 'belum_hadir', 'selesai', 'hangus'])
                  ->default('menunggu');
            $table->boolean('is_sisipan')->default(false);
            $table->unsignedTinyInteger('jumlah_panggilan')->default(0);
            $table->timestamps();

            $table->unique(['pasien_id', 'tanggal_antrean']);
            $table->unique(['nomor_antrean', 'tanggal_antrean']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrean');
    }
};