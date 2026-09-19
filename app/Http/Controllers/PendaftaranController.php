<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuktiBayarRequest;
use App\Http\Requests\PendaftaranRequest;
use App\Models\Pengaturan;
use App\Models\Peserta;
use App\Services\PendaftaranService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function __construct(private readonly PendaftaranService $pendaftaran)
    {
    }

    public function create(): View
    {
        return view('publik.pendaftaran', [
            'dibuka' => $this->pendaftaran->dibuka(),
            'alasanTutup' => $this->pendaftaran->alasanTutup(),
            'sisaKuota' => $this->pendaftaran->sisaKuota(),
            'stokJersey' => $this->pendaftaran->stokJersey(),
        ]);
    }

    public function store(PendaftaranRequest $request): RedirectResponse
    {
        if (! $this->pendaftaran->dibuka()) {
            return back()->withInput()->with('gagal', $this->pendaftaran->alasanTutup());
        }

        $data = $request->validated();
        $paket = $data['paket'];

        $peserta = DB::transaction(function () use ($data, $paket) {
            return Peserta::create([
                'kode_daftar' => $this->pendaftaran->buatKodeDaftar(),
                'no_bib' => $this->pendaftaran->buatNoBib(),
                'nama_lengkap' => $data['nama_lengkap'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'no_hp' => $data['no_hp'],
                'email' => $data['email'] ?? null,
                'asal_instansi' => $data['asal_instansi'] ?? null,
                'alamat' => $data['alamat'],
                'paket' => $paket,
                'ukuran_jersey' => $this->pendaftaran->butuhJersey($paket) ? $data['ukuran_jersey'] : null,
                'biaya' => $this->pendaftaran->biayaPaket($paket),
                'status' => 'menunggu_bayar',
                'kontak_darurat_nama' => $data['kontak_darurat_nama'] ?? null,
                'kontak_darurat_hp' => $data['kontak_darurat_hp'] ?? null,
                'riwayat_penyakit' => $data['riwayat_penyakit'] ?? null,
            ]);
        });

        return redirect()
            ->route('pendaftaran.show', $peserta->kode_daftar)
            ->with('sukses', 'Pendaftaran berhasil! Simpan kode pendaftaran Anda dan lanjutkan pembayaran.')
            ->with('tampilkan_grup', true);
    }

    public function show(string $kode): View
    {
        $peserta = $this->cariPeserta($kode);

        return view('publik.status', [
            'peserta' => $peserta,
            'linkGrupWa' => Pengaturan::get('link_grup_wa'),
        ]);
    }

    public function uploadBukti(BuktiBayarRequest $request, string $kode): RedirectResponse
    {
        $peserta = $this->cariPeserta($kode);

        if ($peserta->status === 'terverifikasi') {
            return back()->with('gagal', 'Pembayaran Anda sudah terverifikasi.');
        }

        $data = $request->validated();
        $disk = config('funrun.disk_bukti');

        DB::transaction(function () use ($peserta, $data, $request, $disk) {
            $path = $request->file('file_bukti')->store('bukti', $disk);

            if ($peserta->pembayaran && Storage::disk($disk)->exists($peserta->pembayaran->file_bukti)) {
                Storage::disk($disk)->delete($peserta->pembayaran->file_bukti);
            }

            $peserta->pembayaran()->updateOrCreate([], [
                'nama_pengirim' => $data['nama_pengirim'],
                'bank_pengirim' => $data['bank_pengirim'] ?? null,
                'nominal' => $data['nominal'],
                'tanggal_transfer' => $data['tanggal_transfer'],
                'file_bukti' => $path,
            ]);

            $peserta->update([
                'status' => 'menunggu_verifikasi',
                'catatan_admin' => null,
            ]);
        });

        return redirect()
            ->route('pendaftaran.show', $peserta->kode_daftar)
            ->with('sukses', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi panitia.');
    }

    public function tiket(string $kode): Response
    {
        $peserta = $this->cariPeserta($kode);

        abort_unless($peserta->status === 'terverifikasi', 403, 'E-ticket hanya tersedia setelah pembayaran terverifikasi.');

        $pdf = Pdf::loadView('pdf.tiket', ['peserta' => $peserta])->setPaper('a5', 'landscape');

        return $pdf->download('E-Ticket-' . $peserta->no_bib . '-' . $peserta->kode_daftar . '.pdf');
    }

    private function cariPeserta(string $kode): Peserta
    {
        return Peserta::with('pembayaran')->where('kode_daftar', $kode)->firstOrFail();
    }
}
