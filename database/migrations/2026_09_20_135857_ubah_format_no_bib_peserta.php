<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah nomor BIB dari format 1001 menjadi PGRI-001 tanpa menghapus data peserta.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE peserta MODIFY no_bib VARCHAR(20) NOT NULL');
        }

        $urutan = 1;

        foreach (DB::table('peserta')->orderBy('id')->pluck('id') as $id) {
            DB::table('peserta')->where('id', $id)->update([
                'no_bib' => 'PGRI-' . str_pad((string) $urutan, 3, '0', STR_PAD_LEFT),
            ]);

            $urutan++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $urutan = 1001;

        foreach (DB::table('peserta')->orderBy('id')->pluck('id') as $id) {
            DB::table('peserta')->where('id', $id)->update(['no_bib' => (string) $urutan]);
            $urutan++;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE peserta MODIFY no_bib VARCHAR(10) NOT NULL');
        }
    }
};
