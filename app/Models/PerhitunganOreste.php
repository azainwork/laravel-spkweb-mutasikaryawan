<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerhitunganOreste extends Model
{
    protected $table = 'perhitungan_oreste';

    protected $fillable = [
        'user_id',
        'tahun',
        'nilai_kinerja',
        'nilai_kompetensi',
        'ranking_kinerja',
        'ranking_kompetensi',
        'rekomendasi_lokasi',
        'status_mutasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
