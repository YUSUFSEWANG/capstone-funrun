<?php

namespace App\Services;

use App\Models\Peserta;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PendaftaranService
{
    public function mulai(): Carbon
    {
        return Carbon::parse(Pengaturan::get('pendaftaran_mulai', config('funrun.pendaftaran_mulai')))->startOfDay();
    }

    public function selesai(): Carbon
    {
        return Carbon::parse(Pengaturan::get('pendaftaran_selesai', config('funrun.pendaftaran_selesai')))->endOfDay();
    }

    public function kuota(): int
    {
        return (int) Pengaturan::get('kuota_peserta', 500);
    }

    public function terdaftar(): int
    {
        return Peserta::where('status', '!=', 'ditolak')->count();
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota() - $this->terdaftar());
    }

    /**
     * Stok jersey per ukuran beserta jumlah yang sudah terpakai.
     *
     * @return array<string, array{stok:int, terpakai:int, sisa:int}>
     */
    public function stokJersey(): array
    {
        $stok = json_decode((string) Pengaturan::get('stok_jersey', '{}'), true) ?: [];

        $terpakai = Peserta::where('status', '!=', 'ditolak')
            ->whereNotNull('ukuran_jersey')
            ->selectRaw('ukuran_jersey, COUNT(*) as total')
            ->groupBy('ukuran_jersey')
            ->pluck('total', 'ukuran_jersey');

        $hasil = [];

        foreach (Peserta::UKURAN_JERSEY as $ukuran) {
            $jumlah = (int) ($stok[$ukuran] ?? 0);
            $dipakai = (int) ($terpakai[$ukuran] ?? 0);

            $hasil[$ukuran] = [
                'stok' => $jumlah,
                'terpakai' => $dipakai,
                'sisa' => max(0, $jumlah - $dipakai),
            ];
        }

        return $hasil;
    }

    /**
     * @return array<int, string>
     */
    public function ukuranTersedia(): array
    {
        return array_keys(array_filter($this->stokJersey(), fn ($item) => $item['sisa'] > 0));
    }

    public function dibuka(): bool
    {
        return $this->alasanTutup() === null;
    }

    /**
     * Alasan pendaftaran tidak dapat dilakukan, null bila terbuka.
     */
    public function alasanTutup(): ?string
    {
        $mode = Pengaturan::get('status_pendaftaran', 'auto');

        if ($mode === 'tutup') {
            return 'Pendaftaran sedang ditutup oleh panitia.';
        }

        if ($mode !== 'buka') {
            $sekarang = now();

            if ($sekarang->lt($this->mulai())) {
                return 'Pendaftaran dibuka mulai ' . $this->mulai()->translatedFormat('d F Y') . '.';
            }

            if ($sekarang->gt($this->selesai())) {
                return 'Pendaftaran telah ditutup pada ' . $this->selesai()->translatedFormat('d F Y') . '.';
            }
        }

        if ($this->sisaKuota() < 1) {
            return 'Kuota peserta sudah terpenuhi.';
        }

        return null;
    }

    public function biayaPaket(string $paket): int
    {
        return Peserta::PAKET[$paket]['biaya'] ?? 0;
    }

    public function butuhJersey(string $paket): bool
    {
        return (bool) (Peserta::PAKET[$paket]['jersey'] ?? false);
    }

    public function buatKodeDaftar(): string
    {
        do {
            $kode = 'FR26-' . Str::upper(Str::random(6));
        } while (Peserta::where('kode_daftar', $kode)->exists());

        return $kode;
    }

    public function buatNoBib(): string
    {
        $terakhir = (int) Peserta::max('id');

        return str_pad((string) (1000 + $terakhir + 1), 4, '0', STR_PAD_LEFT);
    }
}
