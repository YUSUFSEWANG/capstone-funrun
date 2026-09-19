<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Pengaturan;
use App\Services\PendaftaranService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    public function __construct(private readonly PendaftaranService $pendaftaran)
    {
    }

    public function edit(): View
    {
        return view('admin.pengaturan', [
            'stokJersey' => $this->pendaftaran->stokJersey(),
            'statusPendaftaran' => Pengaturan::get('status_pendaftaran', 'auto'),
            'kuota' => $this->pendaftaran->kuota(),
            'mulai' => $this->pendaftaran->mulai()->format('Y-m-d'),
            'selesai' => $this->pendaftaran->selesai()->format('Y-m-d'),
            'linkGrupWa' => Pengaturan::get('link_grup_wa'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status_pendaftaran' => ['required', Rule::in(['auto', 'buka', 'tutup'])],
            'kuota_peserta' => ['required', 'integer', 'min:1', 'max:100000'],
            'pendaftaran_mulai' => ['required', 'date'],
            'pendaftaran_selesai' => ['required', 'date', 'after_or_equal:pendaftaran_mulai'],
            'link_grup_wa' => ['nullable', 'url', 'starts_with:https://chat.whatsapp.com/', 'max:255'],
            'stok' => ['required', 'array'],
            'stok.*' => ['required', 'integer', 'min:0', 'max:100000'],
        ]);

        $stok = [];

        foreach (Peserta::UKURAN_JERSEY as $ukuran) {
            $stok[$ukuran] = (int) ($data['stok'][$ukuran] ?? 0);
        }

        Pengaturan::setBanyak([
            'status_pendaftaran' => $data['status_pendaftaran'],
            'kuota_peserta' => $data['kuota_peserta'],
            'pendaftaran_mulai' => $data['pendaftaran_mulai'],
            'pendaftaran_selesai' => $data['pendaftaran_selesai'],
            'link_grup_wa' => $data['link_grup_wa'] ?? null,
            'stok_jersey' => json_encode($stok),
        ]);

        return back()->with('sukses', 'Pengaturan berhasil disimpan.');
    }
}
