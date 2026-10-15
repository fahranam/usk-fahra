<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'skema_id',
        'judul',
        'deskripsi',
    ];

    /**
     * Nama Fungsi: skema
     * Tujuan: Mendefinisikan relasi Many-to-One ke model Skema (setiap tugas dimiliki oleh satu skema sertifikasi).
     */
    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    /**
     * Nama Fungsi: pengumpulan
     * Tujuan: Mendefinisikan relasi One-to-Many ke model PengumpulanTugas (satu tugas memiliki banyak jawaban dari berbagai asesi).
     */
    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }
}
