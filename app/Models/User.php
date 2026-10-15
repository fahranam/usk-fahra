<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'no_hp', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'no_hp',
        'password',
        'role',
    ];

    /**
     * Nama Fungsi: peserta
     * Tujuan: Mendefinisikan relasi One-to-One antara akun User dengan profil data Peserta sertifikasi.
     */
    public function peserta()
    {
        return $this->hasOne(Peserta::class);
    }

    /**
     * Nama Fungsi: casts
     * Tujuan: Menentukan aturan casting tipe data otomatis untuk atribut model (tanggal verifikasi email dan enkripsi hash password).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
