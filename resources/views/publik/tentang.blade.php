@extends('layouts.publik')

@section('judul', 'Tentang Kegiatan')

@section('konten')
    <section class="bg-navy-700 py-14 text-white">
        <div class="mx-auto max-w-5xl px-4">
            <h1 class="font-display text-4xl font-black italic">Tentang Kegiatan</h1>
            <p class="mt-2 text-white/80">{{ config('funrun.tagline') }}</p>
        </div>
    </section>

    <section class="mx-auto -mt-8 max-w-5xl px-4">
        <div class="card space-y-4 text-navy-900/80">
            <p>
                <strong class="text-navy-700">{{ config('funrun.nama') }}</strong> merupakan kegiatan olahraga bersama yang
                diselenggarakan sebagai wadah untuk meningkatkan semangat hidup sehat, mempererat kebersamaan, serta
                membangun solidaritas di lingkungan masyarakat Tomini.
            </p>
            <p>
                Dengan jarak tempuh <strong class="text-merah-500">{{ config('funrun.jarak') }}</strong>, peserta akan
                diajak menikmati aktivitas lari dalam suasana yang menyenangkan sekaligus menikmati keindahan alam
                Tomini, Kabupaten Parigi Moutong, Sulawesi Tengah.
            </p>
            <p>
                Kegiatan ini terbuka bagi siapa saja yang ingin berpartisipasi, berolahraga, bersilaturahmi, dan menjadi
                bagian dari semarak kegiatan PGRI di Kecamatan Tomini.
            </p>
        </div>
    </section>

    <section class="mx-auto mt-6 max-w-5xl px-4">
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Waktu Pelaksanaan</p>
                <p class="mt-1 font-display text-lg text-navy-700">
                    {{ config('funrun.hari_acara') }}, {{ \Carbon\Carbon::parse(config('funrun.tanggal_acara'))->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Lokasi</p>
                <p class="mt-1 font-display text-lg text-navy-700">{{ config('funrun.lokasi') }}</p>
            </div>
            <div class="card">
                <p class="text-sm font-semibold text-navy-900/60">Jarak</p>
                <p class="mt-1 font-display text-3xl font-black text-merah-500">{{ config('funrun.jarak') }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto mt-6 max-w-5xl px-4">
        <h2 class="section-title">Pilihan Paket</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div class="card border-t-4 border-merah-500">
                <h3 class="text-xl text-navy-700">Paket Lengkap</h3>
                <p class="font-display text-3xl font-black text-merah-500">Rp120.000</p>
                <p class="mt-2 text-sm text-navy-900/70">Mendapatkan jersey dan fasilitas kegiatan sesuai ketentuan panitia.</p>
                <p class="mt-3 text-sm font-semibold text-navy-700">Ukuran jersey: S / M / L / XL / XXL</p>
                <p class="text-xs text-navy-900/60">Ukuran jersey berdasarkan ketersediaan.</p>
            </div>
            <div class="card border-t-4 border-navy-700">
                <h3 class="text-xl text-navy-700">Paket Hemat</h3>
                <p class="font-display text-3xl font-black text-navy-700">Rp60.000</p>
                <p class="mt-2 text-sm text-navy-900/70">Mendapatkan fasilitas kegiatan sesuai ketentuan panitia.</p>
            </div>
        </div>
    </section>

    <section class="mx-auto mt-6 max-w-5xl px-4">
        <div class="card">
            <h2 class="text-xl text-navy-700">Alur Pendaftaran</h2>
            <ol class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Isi formulir pendaftaran dengan data yang benar dan lengkap.',
                    'Lakukan transfer biaya pendaftaran ke rekening panitia.',
                    'Unggah bukti pembayaran melalui halaman Cek Pendaftaran.',
                    'Tunggu verifikasi panitia, lalu unduh e-ticket peserta.',
                ] as $i => $langkah)
                    <li class="rounded-xl border border-navy-100 bg-krem p-4">
                        <span class="badge bg-merah-500 text-white">{{ $i + 1 }}</span>
                        <p class="mt-2 text-sm text-navy-900/80">{{ $langkah }}</p>
                    </li>
                @endforeach
            </ol>
            <a href="{{ route('pendaftaran.create') }}" class="btn-merah mt-6">Daftar Sekarang</a>
        </div>
    </section>
@endsection
