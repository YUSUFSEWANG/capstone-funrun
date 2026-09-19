<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'peserta_id',
        'nama_pengirim',
        'bank_pengirim',
        'nominal',
        'tanggal_transfer',
        'file_nama',
        'file_mime',
        'file_ukuran',
        'file_isi',
    ];

    protected $hidden = ['file_isi'];

    protected $casts = [
        'tanggal_transfer' => 'date',
    ];

    /**
     * Kolom tanpa isi berkas, dipakai untuk kueri daftar agar hemat memori.
     */
    public const KOLOM_RINGKAS = [
        'id',
        'peserta_id',
        'nama_pengirim',
        'bank_pengirim',
        'nominal',
        'tanggal_transfer',
        'file_nama',
        'file_mime',
        'file_ukuran',
        'created_at',
        'updated_at',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }
}
