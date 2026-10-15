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

    /**
     * Nama Fungsi: pesertas
     * Tujuan: Mendefinisikan relasi One-to-Many ke model Peserta (satu skema memiliki banyak asesi/peserta terdaftar).
     */
    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }

    /**
     * Nama Fungsi: tugas
     * Tujuan: Mendefinisikan relasi One-to-Many ke model Tugas (satu skema memiliki banyak penugasan uji kompetensi).
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
