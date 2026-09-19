<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Peserta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PesertaController extends Controller
{
    public function index(Request $request): View
    {
        $peserta = $this->kueri($request)->paginate(20)->withQueryString();

        return view('admin.peserta.index', [
            'peserta' => $peserta,
            'filter' => $request->only(['cari', 'status', 'paket', 'ukuran']),
        ]);
    }

    public function show(Peserta $peserta): View
    {
        $peserta->load('pembayaran', 'verifikator');

        return view('admin.peserta.show', ['peserta' => $peserta]);
    }

    public function verifikasi(Peserta $peserta): RedirectResponse
    {
        if (! $peserta->pembayaran) {
            return back()->with('gagal', 'Peserta belum mengunggah bukti pembayaran.');
        }

        $peserta->update([
            'status' => 'terverifikasi',
            'catatan_admin' => null,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return back()->with('sukses', 'Pendaftaran ' . $peserta->nama_lengkap . ' berhasil diverifikasi.');
    }

    public function tolak(Request $request, Peserta $peserta): RedirectResponse
    {
        $data = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ], [], ['catatan_admin' => 'alasan penolakan']);

        $peserta->update([
            'status' => 'ditolak',
            'catatan_admin' => $data['catatan_admin'],
            'verified_at' => null,
            'verified_by' => auth()->id(),
        ]);

        return back()->with('sukses', 'Pendaftaran ditandai ditolak. Silakan informasikan peserta melalui WhatsApp.');
    }

    public function bukti(Peserta $peserta): Response
    {
        $berkas = Pembayaran::where('peserta_id', $peserta->id)
            ->first(['file_nama', 'file_mime', 'file_isi']);

        abort_unless($berkas, 404);

        return response(base64_decode($berkas->file_isi, true), 200, [
            'Content-Type' => $berkas->file_mime,
            'Content-Disposition' => 'inline; filename="' . $berkas->file_nama . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; img-src 'self'",
        ]);
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        $peserta->delete();

        return redirect()->route('admin.peserta.index')->with('sukses', 'Data peserta dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $peserta = $this->kueri($request)->with('pembayaran')->get();
        $nama = 'peserta-funrun-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($peserta) {
            $keluaran = fopen('php://output', 'w');
            fwrite($keluaran, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

            fputcsv($keluaran, [
                'No BIB', 'Kode Daftar', 'Nama Lengkap', 'JK', 'Tanggal Lahir', 'No HP',
                'Email', 'Asal Instansi', 'Alamat', 'Paket', 'Ukuran Jersey', 'Biaya',
                'Status', 'Kontak Darurat', 'HP Darurat', 'Riwayat Penyakit',
                'Nama Pengirim', 'Nominal Transfer', 'Tanggal Transfer', 'Tanggal Daftar',
            ], ';');

            foreach ($peserta as $row) {
                fputcsv($keluaran, [
                    $row->no_bib,
                    $row->kode_daftar,
                    $row->nama_lengkap,
                    $row->jenis_kelamin,
                    optional($row->tanggal_lahir)->format('d/m/Y'),
                    $row->no_hp,
                    $row->email,
                    $row->asal_instansi,
                    $row->alamat,
                    $row->label_paket,
                    $row->ukuran_jersey,
                    $row->biaya,
                    $row->label_status,
                    $row->kontak_darurat_nama,
                    $row->kontak_darurat_hp,
                    $row->riwayat_penyakit,
                    optional($row->pembayaran)->nama_pengirim,
                    optional($row->pembayaran)->nominal,
                    optional(optional($row->pembayaran)->tanggal_transfer)->format('d/m/Y'),
                    $row->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($keluaran);
        }, $nama, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function kueri(Request $request)
    {
        $request->validate([
            'status' => ['nullable', Rule::in(['menunggu_bayar', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'])],
            'paket' => ['nullable', Rule::in(array_keys(Peserta::PAKET))],
            'ukuran' => ['nullable', Rule::in(Peserta::UKURAN_JERSEY)],
            'cari' => ['nullable', 'string', 'max:60'],
        ]);

        return Peserta::query()
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->string('cari')->trim();
                $q->where(fn ($sub) => $sub
                    ->where('nama_lengkap', 'like', "%{$cari}%")
                    ->orWhere('kode_daftar', 'like', "%{$cari}%")
                    ->orWhere('no_bib', 'like', "%{$cari}%")
                    ->orWhere('no_hp', 'like', "%{$cari}%")
                    ->orWhere('asal_instansi', 'like', "%{$cari}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('paket'), fn ($q) => $q->where('paket', $request->input('paket')))
            ->when($request->filled('ukuran'), fn ($q) => $q->where('ukuran_jersey', $request->input('ukuran')))
            ->latest();
    }
}
