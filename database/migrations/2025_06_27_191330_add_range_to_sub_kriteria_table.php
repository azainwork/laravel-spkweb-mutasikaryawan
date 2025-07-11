<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sub_kriteria', function (Blueprint $table) {
            $table->integer('nilai_min')->nullable()->after('nama');
            $table->integer('nilai_max')->nullable()->after('nilai_min');
        });
    }

    public function down()
    {
        Schema::table('sub_kriteria', function (Blueprint $table) {
            $table->dropColumn(['nilai_min', 'nilai_max']);
        });
    }
};
