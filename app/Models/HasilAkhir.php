<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAkhir extends Model
{
    protected $table = 'hasil_akhir';

    protected $fillable = [
        'user_id',
        'tahun',
        'nilai_akhir',
        'ranking_akhir',
        'lokasi_mutasi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
