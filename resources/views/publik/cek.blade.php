@extends('layouts.publik')

@section('judul', 'Cek Pendaftaran')

@section('konten')
    <section class="bg-navy-700 py-14 text-white">
        <div class="mx-auto max-w-3xl px-4">
            <h1 class="font-display text-4xl font-black italic">Cek Pendaftaran</h1>
            <p class="mt-2 text-white/80">Masukkan kode pendaftaran, nomor BIB, atau nomor HP yang didaftarkan.</p>
        </div>
    </section>

    <section class="mx-auto -mt-8 max-w-3xl px-4">
        <form method="POST" action="{{ route('cek.cari') }}" class="card">
            @csrf
            <label class="label" for="kata_kunci">Kode Pendaftaran / No. BIB / No. HP</label>
            <div class="flex flex-col gap-3 sm:flex-row">
                <input id="kata_kunci" name="kata_kunci" type="text" class="input" required maxlength="60"
                       placeholder="FR26-XXXXXX" value="{{ old('kata_kunci', $kataKunci ?? '') }}">
                <button type="submit" class="btn-merah shrink-0">Cari</button>
            </div>
            @error('kata_kunci')
                <p class="mt-2 text-sm text-merah-600">{{ $message }}</p>
            @enderror
        </form>
    </section>

    @if ($sudahCari)
        <section class="mx-auto mt-4 max-w-3xl px-4">
            @if ($hasil->isEmpty())
                <div class="card text-center">
                    <p class="text-navy-900/70">Data pendaftaran tidak ditemukan.</p>
                    <a href="{{ route('pendaftaran.create') }}" class="btn-merah mt-4">Daftar Sekarang</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($hasil as $peserta)
                        <a href="{{ route('pendaftaran.show', $peserta->kode_daftar) }}"
                           class="card flex flex-wrap items-center justify-between gap-3 transition hover:ring-2 hover:ring-langit-400">
                            <div>
                                <p class="font-display text-lg font-bold text-navy-700">{{ $peserta->nama_lengkap }}</p>
                                <p class="text-sm text-navy-900/60">
                                    {{ $peserta->kode_daftar }} &middot; BIB {{ $peserta->no_bib }} &middot; {{ $peserta->label_paket }}
                                </p>
                            </div>
                            <span class="badge {{ $peserta->warna_status }}">{{ $peserta->label_status }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
@endsection
