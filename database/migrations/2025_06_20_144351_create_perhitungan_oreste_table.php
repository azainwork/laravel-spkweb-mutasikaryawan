<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perhitungan_oreste', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // pegawai
            $table->year('tahun');
            $table->float('nilai_kinerja', 8, 3)->nullable();
            $table->float('nilai_kompetensi', 8, 3)->nullable();
            $table->integer('ranking_kinerja')->nullable();
            $table->integer('ranking_kompetensi')->nullable();
            $table->string('rekomendasi_lokasi')->nullable();
            $table->enum('status_mutasi', ['belum', 'rekomendasi', 'diproses', 'selesai'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhitungan_oreste');
    }
};
