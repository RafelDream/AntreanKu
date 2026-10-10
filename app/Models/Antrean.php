<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    protected $table = 'antrean';

    protected $fillable = [
        'pasien_id',
        'nomor_antrean',
        'tanggal_antrean',
        'status',
        'is_sisipan',
        'jumlah_panggilan',
        'token',
    ];

    protected $casts = [
        'tanggal_antrean' => 'date',
        'is_sisipan' => 'boolean',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
}