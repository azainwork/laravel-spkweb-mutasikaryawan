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
        Schema::create('mutasi_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('pendidikan_terakhir');
            $table->text('alasan_mutasi');
            $table->string('lokasi_tujuan');
            $table->enum('status', ['menunggu', 'diterima', 'ditolak', 'diproses', 'selesai'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_keputusan')->nullable();
            $table->enum('keputusan_akhir', ['belum', 'diterima', 'ditolak'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_requests');
    }
};
