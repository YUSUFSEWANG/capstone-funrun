<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('kode_daftar', 20)->unique();
            $table->string('no_bib', 20)->unique();
            $table->string('nama_lengkap', 120);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('no_hp', 20);
            $table->string('email', 120)->nullable();
            $table->string('asal_instansi', 150)->nullable();
            $table->text('alamat');
            $table->enum('paket', ['lengkap', 'hemat']);
            $table->enum('ukuran_jersey', ['S', 'M', 'L', 'XL', 'XXL'])->nullable();
            $table->unsignedInteger('biaya');
            $table->enum('status', ['menunggu_bayar', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'])
                ->default('menunggu_bayar');
            $table->string('kontak_darurat_nama', 120)->nullable();
            $table->string('kontak_darurat_hp', 20)->nullable();
            $table->string('riwayat_penyakit', 255)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'paket']);
            $table->index('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
