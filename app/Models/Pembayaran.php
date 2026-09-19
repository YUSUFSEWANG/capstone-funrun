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
        'file_bukti',
    ];

    protected $casts = [
        'tanggal_transfer' => 'date',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }
}
