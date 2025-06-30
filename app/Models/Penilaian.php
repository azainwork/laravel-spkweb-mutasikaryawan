<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    
    protected $fillable = [
        'user_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai',
        'tahun',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }
}
