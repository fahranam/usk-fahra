<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('nama', 50);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->default('Laki-laki');
            $table->string('nik', 16)->unique();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('email');
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('skema_id')->constrained('skemas')->cascadeOnDelete();
            $table->date('tanggal_daftar');
            $table->enum('status_kompetensi', [
                'Belum Mengumpulkan',
                'Dalam Proses',
                'Menunggu Penilaian',
                'Kompeten',
                'Belum Kompeten'
            ])->default('Belum Mengumpulkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
