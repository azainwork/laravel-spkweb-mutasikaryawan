<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $table = 'sub_kriteria';

    protected $fillable = [
        'kriteria_id',
        'nama',
        'nilai',
        'bobot',
        'nilai_max',
        'nilai_min',
        'urutan',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
