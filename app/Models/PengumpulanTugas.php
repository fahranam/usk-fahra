<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'tugas_id',
        'peserta_id',
        'link_jawaban',
        'file_jawaban',
        'status',
        'catatan_admin',
    ];

    /**
     * Nama Fungsi: tugas
     * Tujuan: Mendefinisikan relasi Many-to-One ke model Tugas (berkas jawaban ini merujuk ke satu penugasan uji tertentu).
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    /**
     * Nama Fungsi: peserta
     * Tujuan: Mendefinisikan relasi Many-to-One ke model Peserta (berkas jawaban ini dikumpulkan oleh satu asesi tertentu).
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}
