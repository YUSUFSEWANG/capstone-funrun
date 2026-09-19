@extends('layouts.publik')

@section('judul', 'Beranda')

@section('konten')
    @php
        $tanggalAcara = \Carbon\Carbon::parse(config('funrun.tanggal_acara'));
        $mulai = \Carbon\Carbon::parse(config('funrun.pendaftaran_mulai'));
        $selesai = \Carbon\Carbon::parse(config('funrun.pendaftaran_selesai'));
    @endphp

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-langit-300 via-langit-400 to-navy-700 text-white">
        <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-merah-500/30 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 pb-24 pt-16 lg:grid-cols-2 lg:pb-32 lg:pt-24">
            <div>
                <span class="badge bg-merah-500 text-white">Jangan Lewatkan Kesempatan Ini!</span>
                <h1 class="mt-4 font-display text-5xl font-black italic leading-none drop-shadow-sm md:text-6xl">
                    FUN RUN<br>
                    <span class="text-merah-400">PGRI TOMINI</span><br>
                    2026
                </h1>
                <p class="mt-4 max-w-md font-display text-lg italic text-white/90">
                    Sehat Bersama, Solid Berkarya, Semarakkan Tomini
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('pendaftaran.create') }}" class="btn-merah shadow-lg">
                        Daftar Sekarang
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('cek.index') }}" class="btn border-2 border-white text-white hover:bg-white hover:text-navy-700">
                        Cek Pendaftaran
                    </a>
                </div>

                @if (! $dibuka)
                    <p class="mt-4 inline-block rounded-lg bg-white/15 px-3 py-2 text-sm font-semibold">{{ $alasanTutup }}</p>
                @else
                    <p class="mt-4 text-sm font-semibold text-white/90">Sisa kuota: {{ number_format($sisaKuota, 0, ',', '.') }} peserta</p>
                @endif
            </div>

            <div class="space-y-4 self-center">
                <div class="rounded-2xl bg-navy-700/85 p-5 shadow-card ring-1 ring-white/20">
                    <p class="text-sm font-semibold uppercase tracking-wide text-white/70">Pendaftaran</p>
                    <p class="font-display text-xl font-bold">{{ $mulai->translatedFormat('d F') }} – {{ $selesai->translatedFormat('d F Y') }}</p>
                </div>
                <div class="rounded-2xl bg-merah-500/90 p-5 shadow-card ring-1 ring-white/20">
                    <p class="text-sm font-semibold uppercase tracking-wide text-white/80">Pelaksanaan Kegiatan</p>
                    <p class="font-display text-xl font-bold">{{ config('funrun.hari_acara') }}, {{ $tanggalAcara->translatedFormat('d F Y') }}</p>
                </div>
                <div class="rounded-2xl bg-white/15 p-5 ring-1 ring-white/20">
                    <p class="text-sm font-semibold uppercase tracking-wide text-white/70">Lokasi</p>
                    <p class="font-display text-lg font-bold">{{ config('funrun.lokasi') }}</p>
                </div>

                <div x-data="hitungMundur('{{ $tanggalAcara->toIso8601String() }}')" x-init="jalan()"
                     class="grid grid-cols-4 gap-2 rounded-2xl bg-white p-4 text-center text-navy-700 shadow-card">
                    @foreach (['hari' => 'Hari', 'jam' => 'Jam', 'menit' => 'Menit', 'detik' => 'Detik'] as $key => $label)
                        <div>
                            <p class="font-display text-2xl font-black" x-text="sisa.{{ $key }}">0</p>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-navy-700/60">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- BENEFIT --}}
    <section class="relative z-10 mx-auto -mt-12 max-w-7xl px-4">
        <div class="card p-6 md:p-8">
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <span class="badge bg-merah-500 px-4 py-1.5 text-white">BENEFIT</span>
                <h2 class="font-display text-xl italic text-navy-700 md:text-2xl">Yang Peserta Dapatkan</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (config('funrun.benefit') as $i => $benefit)
                    <div class="flex h-full flex-col rounded-xl border border-navy-100 bg-krem p-5">
                        <span class="badge h-7 w-7 justify-center bg-navy-700 p-0 text-white">{{ $i + 1 }}</span>
                        <h3 class="mt-3 text-lg leading-snug text-navy-700">{{ $benefit['judul'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-navy-900/70">{{ $benefit['ket'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- INFO UTAMA --}}
    <section class="mx-auto mt-6 max-w-7xl px-4">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Jarak Tempuh</p>
                <p class="font-display text-4xl font-black text-merah-500">{{ config('funrun.jarak') }}</p>
                <p class="mt-1 text-sm text-navy-900/70">{{ config('funrun.lokasi') }}</p>
            </div>

            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Kategori Lomba</p>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center justify-between rounded-lg bg-merah-500 px-3 py-2 text-white">
                        <span class="text-sm font-semibold">Lengkap</span>
                        <span class="font-display font-bold">Rp120.000</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-navy-700 px-3 py-2 text-white">
                        <span class="text-sm font-semibold">Hemat</span>
                        <span class="font-display font-bold">Rp60.000</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Pembayaran</p>
                <p class="mt-1 text-sm font-semibold text-navy-700">Transfer {{ config('funrun.bank.nama') }}</p>
                <p class="font-display text-xl font-black tracking-tight text-navy-700">{{ config('funrun.bank.rekening') }}</p>
                <p class="text-sm text-navy-900/70">a.n. {{ config('funrun.bank.atas_nama') }}</p>
            </div>

            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Ukuran Jersey</p>
                <p class="font-display text-2xl font-black text-navy-700">S / M / L / XL / XXL</p>
                <p class="mt-1 text-sm text-navy-900/70">(Berdasarkan ketersediaan)</p>
            </div>
        </div>
    </section>

    {{-- TIGA KARTU --}}
    <section class="mx-auto mt-8 max-w-7xl px-4">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="card">
                <h3 class="text-lg text-navy-700">Informasi Pendaftaran</h3>
                <p class="mt-3 text-sm text-navy-900/70">Pendaftaran dibuka mulai:</p>
                <p class="font-display text-2xl font-black text-merah-500">
                    {{ $mulai->translatedFormat('d F') }} – {{ $selesai->translatedFormat('d F Y') }}
                </p>
                <a href="{{ route('pendaftaran.create') }}" class="btn-merah mt-5 w-full">Daftar Sekarang</a>
            </div>

            <div class="card">
                <h3 class="text-lg text-navy-700">Hubungi Panitia</h3>
                <p class="text-sm text-navy-900/60">Informasi &amp; Pendaftaran</p>
                <div class="mt-4 space-y-3">
                    @foreach (config('funrun.kontak') as $kontak)
                        <a href="https://wa.me/{{ $kontak['wa'] }}" target="_blank" rel="noopener"
                           class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 transition hover:bg-emerald-100">
                            <span class="grid h-9 w-9 place-items-center rounded-full bg-emerald-500 text-white">WA</span>
                            <span>
                                <span class="block text-sm font-bold text-navy-700">{{ $kontak['nama'] }}</span>
                                <span class="block text-sm text-navy-900/70">{{ $kontak['hp'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
                <p class="mt-4 text-center font-display italic text-merah-500">Kami Siap Membantu!</p>
            </div>

            <div class="card">
                <h3 class="text-lg text-navy-700">Cara Pembayaran</h3>
                <ol class="mt-3 space-y-2 text-sm text-navy-900/80">
                    <li class="flex gap-3"><span class="badge bg-navy-700 text-white">1</span> Lakukan transfer melalui {{ config('funrun.bank.nama') }}</li>
                    <li class="flex gap-3"><span class="badge bg-navy-700 text-white">2</span> Nomor Rekening: {{ config('funrun.bank.rekening') }}</li>
                    <li class="flex gap-3"><span class="badge bg-navy-700 text-white">3</span> a.n. {{ config('funrun.bank.atas_nama') }}</li>
                    <li class="flex gap-3"><span class="badge bg-navy-700 text-white">4</span> Konfirmasi pembayaran setelah transfer</li>
                </ol>
                <a href="{{ route('cek.index') }}" class="btn-navy mt-5 w-full">
                    Upload Bukti Pembayaran
                </a>
                <p class="mt-1 text-center text-xs text-navy-900/60">(JPG/PNG/PDF, maks. {{ round(config('funrun.upload.max_kb') / 1024, 1) }} MB)</p>
            </div>
        </div>
    </section>

    {{-- AJAKAN --}}
    <section class="mx-auto mt-10 max-w-7xl px-4">
        <div class="rounded-2xl bg-navy-700 px-6 py-10 text-center text-white">
            <h2 class="font-display text-3xl font-black italic">Ayo Ikut!</h2>
            <p class="mx-auto mt-3 max-w-2xl text-white/80">
                Mari bergerak bersama, menjaga kesehatan, mempererat persaudaraan, dan menyemarakkan Tomini
                melalui {{ config('funrun.nama') }}.
            </p>
            <p class="mt-4 font-display italic text-langit-300">Sehat Bersama &middot; Solid Berkarya &middot; Semarakkan Tomini</p>
            <a href="{{ route('pendaftaran.create') }}" class="btn-merah mt-6">Daftar Sekarang</a>
        </div>
    </section>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('hitungMundur', (target) => ({
                sisa: { hari: 0, jam: 0, menit: 0, detik: 0 },
                jalan() {
                    const akhir = new Date(target).getTime();
                    const hitung = () => {
                        const selisih = Math.max(0, akhir - Date.now());
                        this.sisa = {
                            hari: Math.floor(selisih / 86400000),
                            jam: Math.floor((selisih % 86400000) / 3600000),
                            menit: Math.floor((selisih % 3600000) / 60000),
                            detik: Math.floor((selisih % 60000) / 1000),
                        };
                    };
                    hitung();
                    setInterval(hitung, 1000);
                },
            }));
        });
    </script>
@endsection
