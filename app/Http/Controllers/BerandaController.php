<?php

namespace App\Http\Controllers;

use App\Services\PendaftaranService;
use Illuminate\Contracts\View\View;

class BerandaController extends Controller
{
    public function __construct(private readonly PendaftaranService $pendaftaran)
    {
    }

    public function index(): View
    {
        return view('publik.beranda', [
            'dibuka' => $this->pendaftaran->dibuka(),
            'alasanTutup' => $this->pendaftaran->alasanTutup(),
            'sisaKuota' => $this->pendaftaran->sisaKuota(),
            'terdaftar' => $this->pendaftaran->terdaftar(),
        ]);
    }

    public function tentang(): View
    {
        return view('publik.tentang');
    }

    public function kontak(): View
    {
        return view('publik.kontak');
    }
}
