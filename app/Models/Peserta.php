<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'no_hp',
        'alamat',
        'skema_id',
        'tanggal_daftar',
        'status_kompetensi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function getTotalTugasAttribute(): int
    {
        return Tugas::where('skema_id', $this->skema_id)->count();
    }

    public function getTugasSelesaiCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where(function ($q) {
                $q->whereNotNull('link_jawaban')
                  ->orWhereNotNull('file_jawaban');
            })
            ->count();
    }

    public function getTugasKompetenCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where('status', 'Kompeten')
            ->count();
    }

    public function getTugasBelumKompetenCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where('status', 'Belum Kompeten')
            ->count();
    }

    public function getPersentaseSelesaiAttribute(): int
    {
        $total = $this->total_tugas;
        return $total > 0 ? (int) round(($this->tugas_selesai_count / $total) * 100) : 0;
    }

    public function getPersentaseKompetenAttribute(): int
    {
        $total = $this->total_tugas;
        return $total > 0 ? (int) round(($this->tugas_kompeten_count / $total) * 100) : 0;
    }

    public function sinkronkanStatusKompetensi(): string
    {
        $total = $this->total_tugas;

        if ($total === 0) {
            $status = 'Belum Mengumpulkan';
        } else {
            $kompeten = $this->tugas_kompeten_count;
            $belumKompeten = $this->tugas_belum_kompeten_count;
            $selesai = $this->tugas_selesai_count;

            if ($kompeten === $total) {
                $status = 'Kompeten';
            } elseif ($belumKompeten > 0) {
                $status = 'Belum Kompeten';
            } elseif ($selesai === $total) {
                $status = 'Menunggu Penilaian';
            } elseif ($selesai > 0) {
                $status = 'Dalam Proses';
            } else {
                $status = 'Belum Mengumpulkan';
            }
        }

        $this->status_kompetensi = $status;
        $this->save();

        return $status;
    }
}
