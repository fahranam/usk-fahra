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

    /**
     * Nama Fungsi: user
     * Tujuan: Mendefinisikan relasi Many-to-One (inversi) ke model User untuk akun login milik peserta.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Nama Fungsi: skema
     * Tujuan: Mendefinisikan relasi Many-to-One ke model Skema untuk skema sertifikasi yang dipilih oleh peserta.
     */
    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    /**
     * Nama Fungsi: pengumpulanTugas
     * Tujuan: Mendefinisikan relasi One-to-Many ke model PengumpulanTugas untuk seluruh riwayat jawaban tugas milik peserta.
     */
    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    /**
     * Nama Fungsi: getTotalTugasAttribute
     * Tujuan: Menghitung jumlah total penugasan uji kompetensi yang terdaftar pada skema sertifikasi peserta saat ini.
     */
    public function getTotalTugasAttribute(): int
    {
        return Tugas::where('skema_id', $this->skema_id)->count();
    }

    /**
     * Nama Fungsi: getTugasSelesaiCountAttribute
     * Tujuan: Menghitung berapa banyak tugas pada skema aktif yang sudah dikumpulkan bukti pengerjaannya (link atau file gambar) oleh peserta.
     */
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

    /**
     * Nama Fungsi: getTugasKompetenCountAttribute
     * Tujuan: Menghitung jumlah tugas yang telah diperiksa oleh asesor dan dinyatakan 'Kompeten'.
     */
    public function getTugasKompetenCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where('status', 'Kompeten')
            ->count();
    }

    /**
     * Nama Fungsi: getTugasBelumKompetenCountAttribute
     * Tujuan: Menghitung jumlah tugas yang dinilai 'Belum Kompeten' (membutuhkan perbaikan dari asesi).
     */
    public function getTugasBelumKompetenCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where('status', 'Belum Kompeten')
            ->count();
    }

    /**
     * Nama Fungsi: getPersentaseSelesaiAttribute
     * Tujuan: Menghitung persentase pengerjaan seluruh tugas asesi (skala 0 - 100%) untuk tampilan progress bar.
     */
    public function getPersentaseSelesaiAttribute(): int
    {
        $total = $this->total_tugas;
        return $total > 0 ? (int) round(($this->tugas_selesai_count / $total) * 100) : 0;
    }

    /**
     * Nama Fungsi: getPersentaseKompetenAttribute
     * Tujuan: Menghitung persentase tugas yang sudah berhasil lulus/kompeten terhadap total tugas yang ada.
     */
    public function getPersentaseKompetenAttribute(): int
    {
        $total = $this->total_tugas;
        return $total > 0 ? (int) round(($this->tugas_kompeten_count / $total) * 100) : 0;
    }

    /**
     * Nama Fungsi: sinkronkanStatusKompetensi
     * Tujuan: Menghitung dan memperbarui status kelulusan peserta secara otomatis dan bertahap (Kompeten, Belum Kompeten, Menunggu Penilaian, Dalam Proses, atau Belum Mengumpulkan).
     */
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
