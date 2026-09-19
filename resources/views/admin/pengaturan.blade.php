@extends('layouts.admin')

@section('judul', 'Pengaturan')

@section('konten')
    <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="grid gap-4 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <div class="card">
            <h2 class="text-lg text-navy-700">Periode &amp; Kuota</h2>

            <div class="mt-4 space-y-4">
                <div>
                    <label class="label" for="status_pendaftaran">Status Pendaftaran</label>
                    <select id="status_pendaftaran" name="status_pendaftaran" class="input">
                        <option value="auto" @selected($statusPendaftaran === 'auto')>Otomatis (ikuti tanggal)</option>
                        <option value="buka" @selected($statusPendaftaran === 'buka')>Paksa Buka</option>
                        <option value="tutup" @selected($statusPendaftaran === 'tutup')>Paksa Tutup</option>
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label" for="pendaftaran_mulai">Tanggal Mulai</label>
                        <input id="pendaftaran_mulai" name="pendaftaran_mulai" type="date" class="input" required value="{{ old('pendaftaran_mulai', $mulai) }}">
                    </div>
                    <div>
                        <label class="label" for="pendaftaran_selesai">Tanggal Selesai</label>
                        <input id="pendaftaran_selesai" name="pendaftaran_selesai" type="date" class="input" required value="{{ old('pendaftaran_selesai', $selesai) }}">
                    </div>
                </div>

                <div>
                    <label class="label" for="kuota_peserta">Kuota Peserta</label>
                    <input id="kuota_peserta" name="kuota_peserta" type="number" min="1" max="100000" class="input" required value="{{ old('kuota_peserta', $kuota) }}">
                </div>

                <div>
                    <label class="label" for="link_grup_wa">Link Grup WhatsApp Peserta</label>
                    <input id="link_grup_wa" name="link_grup_wa" type="url" class="input"
                           placeholder="https://chat.whatsapp.com/xxxxxxxx" value="{{ old('link_grup_wa', $linkGrupWa) }}">
                    <p class="mt-1 text-xs text-navy-900/60">Muncul sebagai pop-up setelah peserta selesai mendaftar. Kosongkan bila belum ada grup.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="text-lg text-navy-700">Stok Jersey</h2>
            <p class="text-sm text-navy-900/60">Angka di bawah adalah jumlah stok total per ukuran.</p>

            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-5">
                @foreach ($stokJersey as $ukuran => $info)
                    <div>
                        <label class="label" for="stok-{{ $ukuran }}">{{ $ukuran }}</label>
                        <input id="stok-{{ $ukuran }}" name="stok[{{ $ukuran }}]" type="number" min="0" max="100000"
                               class="input" required value="{{ old('stok.' . $ukuran, $info['stok']) }}">
                        <p class="mt-1 text-[11px] text-navy-900/60">terpakai {{ $info['terpakai'] }} &middot; sisa {{ $info['sisa'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2">
            <button type="submit" class="btn-merah">Simpan Pengaturan</button>
        </div>
    </form>
@endsection
