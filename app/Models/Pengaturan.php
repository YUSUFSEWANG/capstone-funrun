<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $primaryKey = 'kunci';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['kunci', 'nilai'];

    public const CACHE_KEY = 'pengaturan.all';

    public static function semua(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::pluck('nilai', 'kunci')->all());
    }

    public static function get(string $kunci, mixed $default = null): mixed
    {
        return static::semua()[$kunci] ?? $default;
    }

    public static function set(string $kunci, mixed $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
        Cache::forget(self::CACHE_KEY);
    }

    public static function setBanyak(array $data): void
    {
        foreach ($data as $kunci => $nilai) {
            static::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
