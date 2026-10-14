<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel penugasan uji kompetensi per skema
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skema_id')->constrained('skemas')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->timestamps();
        });

        // Tabel pengumpulan tugas dan hasil review peserta
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
            $table->string('link_jawaban', 500)->nullable();
            $table->string('file_jawaban')->nullable();
            $table->enum('status', [
                'Menunggu Penilaian',
                'Kompeten',
                'Belum Kompeten'
            ])->default('Menunggu Penilaian');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->unique(['tugas_id', 'peserta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
        Schema::dropIfExists('tugas');
    }
};
