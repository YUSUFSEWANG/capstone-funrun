@extends('layouts.publik')

@section('judul', 'Kontak')

@section('konten')
    <section class="bg-navy-700 py-14 text-white">
        <div class="mx-auto max-w-5xl px-4">
            <h1 class="font-display text-4xl font-black italic">Informasi &amp; Pendaftaran</h1>
            <p class="mt-2 text-white/80">Hubungi panitia untuk pertanyaan seputar kegiatan.</p>
        </div>
    </section>

    <section class="mx-auto -mt-8 max-w-5xl px-4">
        <div class="grid gap-4 md:grid-cols-2">
            @foreach (config('funrun.kontak') as $kontak)
                <div class="card">
                    <h2 class="text-xl text-navy-700">{{ $kontak['nama'] }}</h2>
                    <p class="mt-1 text-navy-900/70">{{ $kontak['hp'] }}</p>
                    <a href="https://wa.me/{{ $kontak['wa'] }}" target="_blank" rel="noopener"
                       class="btn mt-4 w-full bg-emerald-500 text-white hover:bg-emerald-600">
                        Chat via WhatsApp
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mx-auto mt-6 max-w-5xl px-4">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="card">
                <h2 class="text-xl text-navy-700">Pembayaran</h2>
                <p class="mt-2 text-sm text-navy-900/70">{{ config('funrun.bank.nama') }}</p>
                <p class="font-display text-2xl font-black text-navy-700">{{ config('funrun.bank.rekening') }}</p>
                <p class="text-sm text-navy-900/70">a.n. {{ config('funrun.bank.atas_nama') }}</p>
            </div>
            <div class="card">
                <h2 class="text-xl text-navy-700">Lokasi Kegiatan</h2>
                <p class="mt-2 text-navy-900/70">{{ config('funrun.lokasi') }}</p>
                <p class="mt-2 text-sm text-navy-900/70">
                    {{ config('funrun.hari_acara') }},
                    {{ \Carbon\Carbon::parse(config('funrun.tanggal_acara'))->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </section>
@endsection
