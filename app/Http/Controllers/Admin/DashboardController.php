<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Services\PendaftaranService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly PendaftaranService $pendaftaran)
    {
    }

    public function index(): View
    {
        $perStatus = Peserta::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $perPaket = Peserta::where('status', '!=', 'ditolak')
            ->selectRaw('paket, COUNT(*) as total')
            ->groupBy('paket')
            ->pluck('total', 'paket');

        return view('admin.dashboard', [
            'perStatus' => $perStatus,
            'perPaket' => $perPaket,
            'totalPeserta' => $this->pendaftaran->terdaftar(),
            'kuota' => $this->pendaftaran->kuota(),
            'sisaKuota' => $this->pendaftaran->sisaKuota(),
            'stokJersey' => $this->pendaftaran->stokJersey(),
            'danaTerverifikasi' => (int) Peserta::where('status', 'terverifikasi')->sum('biaya'),
            'danaTertunda' => (int) Peserta::where('status', 'menunggu_verifikasi')->sum('biaya'),
            'perluVerifikasi' => Peserta::with('pembayaran')
                ->where('status', 'menunggu_verifikasi')
                ->latest()
                ->limit(5)
                ->get(),
            'statusPendaftaran' => $this->pendaftaran->dibuka() ? 'Dibuka' : 'Ditutup',
            'alasanTutup' => $this->pendaftaran->alasanTutup(),
        ]);
    }
}
