<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Nama Fungsi: run
     * Tujuan: Menjalankan seluruh proses seeding data awal (data master skema sertifikasi dan akun administrator default) ke dalam database aplikasi.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        // Data master awal skema sertifikasi kompetensi LSP
        $skemas = [
            [
                'kode_skema' => 'JWD-001',
                'nama_skema' => 'Junior Web Developer',
                'deskripsi'  => 'Skema sertifikasi kompetensi untuk pembuatan dan pemeliharaan website interaktif menggunakan PHP dan MySQL.',
            ],
            [
                'kode_skema' => 'JNA-002',
                'nama_skema' => 'Junior Network Administrator',
                'deskripsi'  => 'Skema sertifikasi kompetensi untuk instalasi, konfigurasi, dan pemeliharaan jaringan komputer skala kecil-menengah.',
            ],
            [
                'kode_skema' => 'DGM-003',
                'nama_skema' => 'Desainer Grafis Muda',
                'deskripsi'  => 'Skema sertifikasi kompetensi perancangan visual, branding, dan aset multimedia digital.',
            ],
            [
                'kode_skema' => 'SAD-004',
                'nama_skema' => 'System Analyst & Design',
                'deskripsi'  => 'Skema sertifikasi kompetensi perancangan arsitektur sistem perangkat lunak, flowchart, dan use case.',
            ],
        ];

        foreach ($skemas as $skema) {
            Skema::updateOrCreate(
                ['kode_skema' => $skema['kode_skema']],
                $skema
            );
        }
    }
}
