<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_kriteria', function (Blueprint $table) {
            if (Schema::hasColumn('sub_kriteria', 'nilai_min')) {
                $table->dropColumn('nilai_min');
            }
            if (Schema::hasColumn('sub_kriteria', 'nilai_max')) {
                $table->dropColumn('nilai_max');
            }

            if (!Schema::hasColumn('sub_kriteria', 'nilai')) {
                $table->float('nilai')->after('nama');
            } else {
                $table->float('nilai')->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sub_kriteria', function (Blueprint $table) {
            if (!Schema::hasColumn('sub_kriteria', 'nilai_min')) {
                $table->float('nilai_min')->nullable();
            }
            if (!Schema::hasColumn('sub_kriteria', 'nilai_max')) {
                $table->float('nilai_max')->nullable();
            }
        });
    }

};
