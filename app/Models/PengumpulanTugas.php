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

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}
