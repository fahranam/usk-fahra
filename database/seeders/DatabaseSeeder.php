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
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        // Skema sertifikasi awal LSP
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
