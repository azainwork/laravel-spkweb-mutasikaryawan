<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'jabatan',
        'pendidikan',
        'masa_kerja',
        'usia',
        'role_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->hasOne(PegawaiProfile::class);
    }

    public function mutasiRequests()
    {
        return $this->hasMany(MutasiRequest::class);
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function perhitunganOrestes()
    {
        return $this->hasMany(PerhitunganOreste::class);
    }

    public function hasilAkhirs()
    {
        return $this->hasMany(HasilAkhir::class);
    }
}
