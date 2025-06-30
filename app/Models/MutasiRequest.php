<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiRequest extends Model
{
    
    protected $fillable = [
        'user_id',
        'pendidikan_terakhir',
        'alasan_mutasi',
        'lokasi_tujuan',
        'status',
        'keterangan',
        'tanggal_pengajuan',
        'tanggal_keputusan',
        'keputusan_akhir',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
