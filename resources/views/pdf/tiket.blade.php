<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $peserta->kode_daftar }}</title>
    <style>
        /* Helvetica sudah tersedia di pembaca PDF sehingga font tidak perlu ikut ditanam. */
        * { font-family: Helvetica, Arial, sans-serif; }
        body { margin: 0; color: #12397c; }
        .tiket { border: 3px solid #12397c; border-radius: 10px; padding: 18px; }
        .head { background: #12397c; color: #fff; padding: 12px 16px; border-radius: 6px; }
        .head h1 { margin: 0; font-size: 18px; font-style: italic; }
        .head p { margin: 3px 0 0; font-size: 10px; color: #cfe0ff; }
        .head td { padding: 0; vertical-align: middle; }
        .head img { width: 46px; height: 46px; }
        .bib { text-align: center; border: 2px dashed #e4322b; border-radius: 8px; padding: 8px; }
        .bib span { display: block; font-size: 9px; color: #666; letter-spacing: 2px; }
        .bib strong { font-size: 17px; color: #e4322b; letter-spacing: 0.5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 11px; }
        td { padding: 4px 6px; vertical-align: top; }
        td.k { color: #666; width: 34%; }
        .foot { margin-top: 12px; font-size: 9px; color: #666; border-top: 1px solid #dbe6ff; padding-top: 8px; }
        .lunas { display: inline-block; background: #0f9d58; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 10px; }
    </style>
</head>
<body>
    @php
        // Versi JPG berlatar biru dipakai agar logo menyatu dengan header tanpa bergantung pada ekstensi gd.
        $berkasLogo = public_path('img/logo-pgri-tiket.jpg');
        $logo = is_file($berkasLogo) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($berkasLogo)) : null;
    @endphp

    <div class="tiket">
        <table class="head">
            <tr>
                @if ($logo)
                    <td style="width:56px"><img src="{{ $logo }}" alt="PGRI"></td>
                @endif
                <td>
                    <h1>{{ config('funrun.nama') }}</h1>
                    <p>{{ config('funrun.tagline') }}</p>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width:62%">
                    <table>
                        <tr><td class="k">Nama Peserta</td><td><strong>{{ $peserta->nama_lengkap }}</strong></td></tr>
                        <tr><td class="k">Kode Pendaftaran</td><td>{{ $peserta->kode_daftar }}</td></tr>
                        <tr><td class="k">Paket</td><td>{{ $peserta->label_paket }} (Rp{{ number_format($peserta->biaya, 0, ',', '.') }})</td></tr>
                        <tr><td class="k">Ukuran Jersey</td><td>{{ $peserta->ukuran_jersey ?: '-' }}</td></tr>
                        <tr><td class="k">Jenis Kelamin</td><td>{{ $peserta->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                        <tr><td class="k">Asal Instansi</td><td>{{ $peserta->asal_instansi ?: '-' }}</td></tr>
                        <tr><td class="k">Status</td><td><span class="lunas">TERVERIFIKASI</span></td></tr>
                    </table>
                </td>
                <td>
                    <div class="bib">
                        <span>NOMOR PESERTA</span>
                        <strong>{{ $peserta->no_bib }}</strong>
                    </div>
                    <table>
                        <tr><td class="k">Tanggal</td><td>{{ config('funrun.hari_acara') }}, {{ \Carbon\Carbon::parse(config('funrun.tanggal_acara'))->translatedFormat('d F Y') }}</td></tr>
                        <tr><td class="k">Jarak</td><td>{{ config('funrun.jarak') }}</td></tr>
                        <tr><td class="k">Lokasi</td><td>{{ config('funrun.lokasi') }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="foot">
            <strong>WAJIB:</strong> tunjukkan e-ticket ini (cetak atau dari layar HP) saat pengambilan jersey dan nomor peserta.
            Verifikasi pada {{ optional($peserta->verified_at)->translatedFormat('d F Y H:i') }} WITA.
            Informasi: {{ config('funrun.kontak.0.nama') }} {{ config('funrun.kontak.0.hp') }} /
            {{ config('funrun.kontak.1.nama') }} {{ config('funrun.kontak.1.hp') }}.
        </div>
    </div>
</body>
</html>
