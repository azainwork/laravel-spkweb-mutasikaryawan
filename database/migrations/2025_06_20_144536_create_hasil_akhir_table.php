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
        Schema::create('hasil_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->year('tahun');
            $table->float('nilai_akhir', 8, 3)->nullable();
            $table->integer('ranking_akhir')->nullable();
            $table->string('lokasi_mutasi')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_akhir');
    }
};
