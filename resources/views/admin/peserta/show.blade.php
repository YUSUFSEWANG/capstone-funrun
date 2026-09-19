@extends('layouts.admin')

@section('judul', 'Detail Peserta')

@section('konten')
    @php
        $pesanTerverifikasi = "Halo {$peserta->nama_lengkap}, pendaftaran Anda pada " . config('funrun.nama')
            . " telah TERVERIFIKASI.\nKode: {$peserta->kode_daftar}\nNo. BIB: {$peserta->no_bib}\n"
            . ($peserta->ukuran_jersey ? "Ukuran Jersey: {$peserta->ukuran_jersey}\n" : '')
            . "E-ticket: " . route('pendaftaran.show', $peserta->kode_daftar)
            . "\nSampai jumpa di garis start!";

        $pesanMenunggu = "Halo {$peserta->nama_lengkap}, pendaftaran Anda pada " . config('funrun.nama')
            . " sudah kami terima dengan kode {$peserta->kode_daftar}.\nMohon lakukan pembayaran Rp"
            . number_format($peserta->biaya, 0, ',', '.') . " ke " . config('funrun.bank.nama') . " "
            . config('funrun.bank.rekening') . " a.n. " . config('funrun.bank.atas_nama')
            . ", lalu unggah bukti di: " . route('pendaftaran.show', $peserta->kode_daftar);

        $pesanDitolak = "Halo {$peserta->nama_lengkap}, mohon maaf bukti pembayaran Anda belum dapat kami verifikasi.\nCatatan: "
            . ($peserta->catatan_admin ?: '-') . "\nSilakan unggah ulang di: " . route('pendaftaran.show', $peserta->kode_daftar);

        $pesanWa = match ($peserta->status) {
            'terverifikasi' => $pesanTerverifikasi,
            'ditolak' => $pesanDitolak,
            default => $pesanMenunggu,
        };
    @endphp

    <a href="{{ route('admin.peserta.index') }}" class="text-sm font-semibold text-navy-700 hover:underline">&larr; Kembali ke daftar peserta</a>

    <div class="mt-3 grid gap-4 lg:grid-cols-3">
        <div class="card lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-2xl text-navy-700">{{ $peserta->nama_lengkap }}</h2>
                    <p class="text-sm text-navy-900/60">{{ $peserta->kode_daftar }} &middot; BIB {{ $peserta->no_bib }}</p>
                </div>
                <span class="badge {{ $peserta->warna_status }}">{{ $peserta->label_status }}</span>
            </div>

            <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                <div><dt class="text-navy-900/60">Jenis Kelamin</dt><dd class="font-semibold">{{ $peserta->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                <div><dt class="text-navy-900/60">Tanggal Lahir</dt><dd class="font-semibold">{{ $peserta->tanggal_lahir->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-navy-900/60">No. HP</dt><dd class="font-semibold">{{ $peserta->no_hp }}</dd></div>
                <div><dt class="text-navy-900/60">Email</dt><dd class="font-semibold">{{ $peserta->email ?: '-' }}</dd></div>
                <div><dt class="text-navy-900/60">Asal Instansi</dt><dd class="font-semibold">{{ $peserta->asal_instansi ?: '-' }}</dd></div>
                <div><dt class="text-navy-900/60">Paket</dt><dd class="font-semibold">{{ $peserta->label_paket }} — Rp{{ number_format($peserta->biaya, 0, ',', '.') }}</dd></div>
                <div><dt class="text-navy-900/60">Ukuran Jersey</dt><dd class="font-semibold">{{ $peserta->ukuran_jersey ?: 'Tanpa jersey' }}</dd></div>
                <div><dt class="text-navy-900/60">Kontak Darurat</dt><dd class="font-semibold">{{ $peserta->kontak_darurat_nama ?: '-' }} {{ $peserta->kontak_darurat_hp ? '(' . $peserta->kontak_darurat_hp . ')' : '' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-navy-900/60">Alamat</dt><dd class="font-semibold">{{ $peserta->alamat }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-navy-900/60">Riwayat Penyakit</dt><dd class="font-semibold">{{ $peserta->riwayat_penyakit ?: '-' }}</dd></div>
                <div><dt class="text-navy-900/60">Waktu Daftar</dt><dd class="font-semibold">{{ $peserta->created_at->translatedFormat('d F Y H:i') }}</dd></div>
                <div><dt class="text-navy-900/60">Diverifikasi</dt><dd class="font-semibold">{{ $peserta->verified_at ? $peserta->verified_at->translatedFormat('d F Y H:i') . ' oleh ' . optional($peserta->verifikator)->name : '-' }}</dd></div>
            </dl>

            @if ($peserta->catatan_admin)
                <div class="mt-4 rounded-xl border border-merah-400/40 bg-merah-500/10 px-4 py-3 text-sm text-merah-700">
                    <p class="font-bold">Catatan panitia:</p>
                    <p>{{ $peserta->catatan_admin }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="card">
                <h3 class="text-lg text-navy-700">Bukti Pembayaran</h3>

                @if ($peserta->pembayaran)
                    <dl class="mt-3 space-y-2 text-sm">
                        <div><dt class="text-navy-900/60">Nama Pengirim</dt><dd class="font-semibold">{{ $peserta->pembayaran->nama_pengirim }}</dd></div>
                        <div><dt class="text-navy-900/60">Bank Pengirim</dt><dd class="font-semibold">{{ $peserta->pembayaran->bank_pengirim ?: '-' }}</dd></div>
                        <div><dt class="text-navy-900/60">Nominal</dt><dd class="font-display text-lg font-black {{ $peserta->pembayaran->nominal >= $peserta->biaya ? 'text-emerald-600' : 'text-merah-500' }}">Rp{{ number_format($peserta->pembayaran->nominal, 0, ',', '.') }}</dd></div>
                        <div><dt class="text-navy-900/60">Tanggal Transfer</dt><dd class="font-semibold">{{ $peserta->pembayaran->tanggal_transfer->translatedFormat('d F Y') }}</dd></div>
                    </dl>

                    <a href="{{ route('admin.peserta.bukti', $peserta) }}" target="_blank" rel="noopener" class="btn-outline mt-4 w-full">Lihat File Bukti</a>
                @else
                    <p class="mt-2 text-sm text-navy-900/60">Peserta belum mengunggah bukti pembayaran.</p>
                @endif
            </div>

            <div class="card space-y-3">
                <h3 class="text-lg text-navy-700">Tindakan</h3>

                @if ($peserta->status !== 'terverifikasi')
                    <x-konfirmasi
                        judul="Verifikasi Pembayaran"
                        pesan="Pendaftaran {{ $peserta->nama_lengkap }} akan ditandai lunas dan e-ticket peserta menjadi aktif."
                        label="Verifikasi Pembayaran"
                        kelas-tombol="btn w-full bg-emerald-500 text-white hover:bg-emerald-600"
                        kelas-konfirmasi="btn bg-emerald-500 text-white hover:bg-emerald-600"
                        label-konfirmasi="Ya, Verifikasi"
                        method="PATCH"
                        :aksi="route('admin.peserta.verifikasi', $peserta)" />
                @endif

                <form method="POST" action="{{ route('admin.peserta.tolak', $peserta) }}" class="space-y-2">
                    @csrf
                    @method('PATCH')
                    <label class="label" for="catatan_admin">Alasan Penolakan</label>
                    <textarea id="catatan_admin" name="catatan_admin" rows="2" class="input" maxlength="500"
                              placeholder="Contoh: nominal tidak sesuai">{{ old('catatan_admin') }}</textarea>
                    <button type="submit" class="btn-merah w-full">Tandai Ditolak</button>
                </form>

                <a href="https://wa.me/{{ $peserta->wa_number }}?text={{ rawurlencode($pesanWa) }}" target="_blank" rel="noopener"
                   class="btn w-full bg-emerald-600 text-white hover:bg-emerald-700">Kirim Notifikasi WhatsApp</a>

                <x-konfirmasi
                    judul="Hapus Data Peserta"
                    pesan="Data {{ $peserta->nama_lengkap }} beserta bukti pembayarannya akan dihapus permanen dan tidak dapat dikembalikan."
                    label="Hapus Data Peserta"
                    kelas-tombol="w-full rounded-full px-6 py-2 text-sm font-semibold text-merah-600 hover:bg-merah-500/10"
                    :aksi="route('admin.peserta.destroy', $peserta)" />
            </div>
        </div>
    </div>
@endsection
