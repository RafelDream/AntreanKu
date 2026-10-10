<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'nama_lengkap',
        'jenis_pasien',
        'nik',
        'no_rekam_medis',
        'tanggal_lahir',
        'no_telepon',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function antrean()
    {
        return $this->hasMany(Antrean::class);
    }
}