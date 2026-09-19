<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Aman dijalankan berulang saat deploy: data yang sudah ada tidak ditimpa.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@funrunpgritomini.id'],
            [
                'name' => 'Panitia Fun Run',
                'password' => Hash::make('FunRun#2026'),
            ]
        );

        $bawaan = [
            'status_pendaftaran' => 'auto',
            'kuota_peserta' => 500,
            'pendaftaran_mulai' => config('funrun.pendaftaran_mulai'),
            'pendaftaran_selesai' => config('funrun.pendaftaran_selesai'),
            'stok_jersey' => json_encode(['S' => 100, 'M' => 150, 'L' => 150, 'XL' => 75, 'XXL' => 25]),
            'link_grup_wa' => null,
        ];

        foreach ($bawaan as $kunci => $nilai) {
            Pengaturan::firstOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
        }

        Cache::forget(Pengaturan::CACHE_KEY);
    }
}
