<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $fillable = [
        'kode_skema',
        'nama_skema',
        'deskripsi',
    ];

    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
