<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiProfile extends Model
{
    protected $fillable = [
        'user_id',
        'alamat',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'agama',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
