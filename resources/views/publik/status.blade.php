@extends('layouts.publik')

@section('judul', 'Status Pendaftaran')

@section('konten')
    @php
        // Grup WhatsApp hanya ditawarkan setelah peserta mengunggah bukti pembayaran.
        $bolehGabungGrup = $linkGrupWa && in_array($peserta->status, ['menunggu_verifikasi', 'terverifikasi'], true);
    @endphp

    @if ($bolehGabungGrup)
        <div x-data="{ buka: {{ session('tampilkan_grup') ? 'true' : 'false' }} }" x-cloak>
            <div x-show="buka" class="fixed inset-0 z-[60] grid place-items-center bg-navy-900/60 p-4" @keydown.escape.window="buka = false">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 text-center shadow-card" @click.outside="buka = false">
                    <span class="grid mx-auto h-14 w-14 place-items-center rounded-full bg-emerald-500 text-xl font-black text-white">WA</span>
                    <h2 class="mt-4 text-2xl text-navy-700">Pembayaran Terkirim!</h2>
                    <p class="mt-2 text-sm text-navy-900/70">
                        Pendaftaran dan bukti pembayaran Anda sudah kami terima. Silakan bergabung ke grup WhatsApp
                        peserta untuk menerima informasi teknis, pengumuman, dan konfirmasi dari panitia.
                    </p>
                    <a href="{{ $linkGrupWa }}" target="_blank" rel="noopener"
                       class="btn mt-5 w-full bg-emerald-500 text-white hover:bg-emerald-600">
                        Gabung Grup WhatsApp
                    </a>
                    <button type="button" @click="buka = false" class="mt-2 w-full rounded-full px-6 py-2 text-sm font-semibold text-navy-700 hover:bg-navy-50">
                        Nanti saja
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section class="bg-navy-700 py-14 text-white">
        <div class="mx-auto max-w-4xl px-4">
            <p class="text-sm uppercase tracking-widest text-white/70">Kode Pendaftaran</p>
            <h1 class="font-display text-4xl font-black">{{ $peserta->kode_daftar }}</h1>
            <p class="mt-1 text-white/80">Simpan kode ini untuk mengecek status pendaftaran Anda.</p>
        </div>
    </section>

    <section class="mx-auto -mt-8 max-w-4xl px-4">
        <div class="card">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl text-navy-700">{{ $peserta->nama_lengkap }}</h2>
                    <p class="text-sm text-navy-900/60">{{ $peserta->no_bib }} &middot; {{ $peserta->label_paket }}</p>
                </div>
                <span class="badge {{ $peserta->warna_status }}">{{ $peserta->label_status }}</span>
            </div>

            <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                <div><dt class="text-navy-900/60">Jenis Kelamin</dt><dd class="font-semibold">{{ $peserta->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                <div><dt class="text-navy-900/60">Tanggal Lahir</dt><dd class="font-semibold">{{ $peserta->tanggal_lahir->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-navy-900/60">No. HP</dt><dd class="font-semibold">{{ $peserta->no_hp }}</dd></div>
                <div><dt class="text-navy-900/60">Asal Instansi</dt><dd class="font-semibold">{{ $peserta->asal_instansi ?: '-' }}</dd></div>
                <div><dt class="text-navy-900/60">Ukuran Jersey</dt><dd class="font-semibold">{{ $peserta->ukuran_jersey ?: 'Tanpa jersey' }}</dd></div>
                <div><dt class="text-navy-900/60">Biaya</dt><dd class="font-display text-lg font-black text-merah-500">Rp{{ number_format($peserta->biaya, 0, ',', '.') }}</dd></div>
            </dl>
        </div>
    </section>

    @if ($bolehGabungGrup)
        <section class="mx-auto mt-4 max-w-4xl px-4">
            <div class="card flex flex-wrap items-center justify-between gap-3 border-l-4 border-emerald-500">
                <div>
                    <h2 class="text-lg text-navy-700">Grup WhatsApp Peserta</h2>
                    <p class="text-sm text-navy-900/70">Semua informasi teknis kegiatan diumumkan di grup ini.</p>
                </div>
                <a href="{{ $linkGrupWa }}" target="_blank" rel="noopener"
                   class="btn bg-emerald-500 text-white hover:bg-emerald-600">Gabung Grup</a>
            </div>
        </section>
    @endif

    @if ($peserta->status === 'ditolak' && $peserta->catatan_admin)
        <section class="mx-auto mt-4 max-w-4xl px-4">
            <div class="rounded-xl border border-merah-400/40 bg-merah-500/10 px-4 py-3 text-sm text-merah-700">
                <p class="font-bold">Catatan panitia:</p>
                <p>{{ $peserta->catatan_admin }}</p>
            </div>
        </section>
    @endif

    @if ($peserta->status === 'terverifikasi')
        <section class="mx-auto mt-4 max-w-4xl px-4">
            <div class="card border-l-4 border-emerald-500">
                <h2 class="text-xl text-emerald-700">Pembayaran Terverifikasi</h2>
                <p class="mt-1 text-sm text-navy-900/70">
                    Selamat! Anda resmi terdaftar sebagai peserta. Unduh e-ticket Anda, lalu tunjukkan saat
                    <strong>pengambilan jersey dan nomor peserta</strong> — boleh dicetak atau ditampilkan dari layar HP.
                </p>
                <a href="{{ route('pendaftaran.tiket', $peserta->kode_daftar) }}" class="btn-navy mt-4">Unduh E-Ticket (PDF)</a>
            </div>
        </section>
    @else
        <section class="mx-auto mt-4 max-w-4xl px-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="card">
                    <h2 class="text-xl text-navy-700">Instruksi Pembayaran</h2>
                    <p class="mt-3 text-sm text-navy-900/70">Transfer sejumlah</p>
                    <p class="font-display text-3xl font-black text-merah-500">Rp{{ number_format($peserta->biaya, 0, ',', '.') }}</p>
                    <div class="mt-3 rounded-xl bg-krem p-4 text-sm">
                        <p class="font-semibold text-navy-700">{{ config('funrun.bank.nama') }}</p>
                        <p class="font-display text-xl font-black text-navy-700">{{ config('funrun.bank.rekening') }}</p>
                        <p class="text-navy-900/70">a.n. {{ config('funrun.bank.atas_nama') }}</p>
                    </div>
                    <p class="mt-3 text-xs text-navy-900/60">
                        Setelah transfer, unggah bukti pembayaran pada formulir di samping.
                        Butuh bantuan? Hubungi {{ config('funrun.kontak.0.nama') }} ({{ config('funrun.kontak.0.hp') }}).
                    </p>
                </div>

                <div class="card">
                    <h2 class="text-xl text-navy-700">Upload Bukti Pembayaran</h2>

                    @if ($errors->any())
                        <div class="mt-3 rounded-xl border border-merah-400/40 bg-merah-500/10 px-3 py-2 text-sm text-merah-700">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $pesan)
                                    <li>{{ $pesan }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($peserta->pembayaran)
                        <p class="mt-3 rounded-lg bg-krem px-3 py-2 text-xs text-navy-900/70">
                            Bukti terakhir diunggah {{ $peserta->pembayaran->updated_at->translatedFormat('d F Y H:i') }}.
                            Mengunggah ulang akan menggantikan bukti sebelumnya.
                        </p>
                    @endif

                    <form method="POST" action="{{ route('pendaftaran.bukti', $peserta->kode_daftar) }}"
                          enctype="multipart/form-data" class="mt-4 space-y-3">
                        @csrf

                        <div>
                            <label class="label" for="nama_pengirim">Nama Pengirim <span class="text-merah-500">*</span></label>
                            <input id="nama_pengirim" name="nama_pengirim" type="text" class="input" required maxlength="120"
                                   value="{{ old('nama_pengirim', optional($peserta->pembayaran)->nama_pengirim ?? $peserta->nama_lengkap) }}">
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="label" for="bank_pengirim">Bank Pengirim</label>
                                <input id="bank_pengirim" name="bank_pengirim" type="text" class="input" maxlength="60"
                                       placeholder="BRI / BNI / Mandiri" value="{{ old('bank_pengirim', optional($peserta->pembayaran)->bank_pengirim) }}">
                            </div>
                            <div>
                                <label class="label" for="tanggal_transfer">Tanggal Transfer <span class="text-merah-500">*</span></label>
                                <input id="tanggal_transfer" name="tanggal_transfer" type="date" class="input" required
                                       max="{{ now()->format('Y-m-d') }}"
                                       value="{{ old('tanggal_transfer', optional(optional($peserta->pembayaran)->tanggal_transfer)->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div>
                            <label class="label" for="nominal">Nominal Transfer (Rp) <span class="text-merah-500">*</span></label>
                            <input id="nominal" name="nominal" type="number" class="input" required min="1000" step="1"
                                   value="{{ old('nominal', optional($peserta->pembayaran)->nominal ?? $peserta->biaya) }}">
                        </div>

                        <div>
                            <label class="label" for="file_bukti">File Bukti Transfer <span class="text-merah-500">*</span></label>
                            <input id="file_bukti" name="file_bukti" type="file" class="input" required
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <p class="mt-1 text-xs text-navy-900/60">Format JPG, PNG, atau PDF. Maksimal {{ round(config('funrun.upload.max_kb') / 1024, 1) }} MB.</p>
                        </div>

                        <button type="submit" class="btn-merah w-full">Kirim Bukti Pembayaran</button>
                    </form>
                </div>
            </div>
        </section>
    @endif
@endsection
