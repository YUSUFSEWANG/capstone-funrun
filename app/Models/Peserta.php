<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'kode_daftar',
        'no_bib',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'email',
        'asal_instansi',
        'alamat',
        'paket',
        'ukuran_jersey',
        'biaya',
        'status',
        'kontak_darurat_nama',
        'kontak_darurat_hp',
        'riwayat_penyakit',
        'catatan_admin',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    public const PAKET = [
        'lengkap' => ['label' => 'Paket Lengkap', 'biaya' => 120000, 'jersey' => true],
        'hemat' => ['label' => 'Paket Hemat', 'biaya' => 60000, 'jersey' => false],
    ];

    public const UKURAN_JERSEY = ['S', 'M', 'L', 'XL', 'XXL'];

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu_bayar' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getWarnaStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu_bayar' => 'bg-amber-100 text-amber-700',
            'menunggu_verifikasi' => 'bg-langit-300/30 text-langit-500',
            'terverifikasi' => 'bg-emerald-100 text-emerald-700',
            'ditolak' => 'bg-merah-500/10 text-merah-600',
            default => 'bg-navy-100 text-navy-700',
        };
    }

    public function getLabelPaketAttribute(): string
    {
        return self::PAKET[$this->paket]['label'] ?? $this->paket;
    }

    /**
     * Nomor HP dalam format internasional untuk tautan wa.me.
     */
    public function getWaNumberAttribute(): string
    {
        $digits = preg_replace('/\D/', '', (string) $this->no_hp);

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        return str_starts_with($digits, '62') ? $digits : '62' . $digits;
    }
}
