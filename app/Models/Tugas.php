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

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }
}
