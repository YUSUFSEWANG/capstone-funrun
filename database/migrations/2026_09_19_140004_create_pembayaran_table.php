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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->string('nama_pengirim', 120);
            $table->string('bank_pengirim', 60)->nullable();
            $table->unsignedInteger('nominal');
            $table->date('tanggal_transfer');
            $table->string('file_nama', 160);
            $table->string('file_mime', 100);
            $table->unsignedInteger('file_ukuran');
            // Isi berkas disimpan base64 (MEDIUMTEXT 16 MB) agar tidak butuh storage eksternal.
            $table->mediumText('file_isi');
            $table->timestamps();

            $table->unique('peserta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
