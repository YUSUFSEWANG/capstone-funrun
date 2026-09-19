@extends('layouts.publik')

@section('judul', 'Pendaftaran Peserta')

@section('konten')
    <section class="bg-navy-700 py-14 text-white">
        <div class="mx-auto max-w-4xl px-4">
            <h1 class="font-display text-4xl font-black italic">Formulir Pendaftaran</h1>
            <p class="mt-2 text-white/80">Isi data dengan benar. Kode pendaftaran akan ditampilkan setelah formulir dikirim.</p>
        </div>
    </section>

    <section class="mx-auto -mt-8 max-w-4xl px-4 pb-6">
        @if (! $dibuka)
            <div class="card border-l-4 border-merah-500">
                <h2 class="text-xl text-merah-600">Pendaftaran Belum Tersedia</h2>
                <p class="mt-2 text-navy-900/70">{{ $alasanTutup }}</p>
                <a href="{{ route('beranda') }}" class="btn-navy mt-5">Kembali ke Beranda</a>
            </div>
        @else
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-merah-400/40 bg-merah-500/10 px-4 py-3 text-sm text-merah-700">
                    <p class="font-bold">Periksa kembali isian berikut:</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pendaftaran.store') }}" class="card space-y-6"
                  x-data="{ paket: '{{ old('paket', 'lengkap') }}' }">
                @csrf

                <div>
                    <h2 class="text-lg text-navy-700">1. Pilih Paket</h2>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        <label class="cursor-pointer rounded-xl border-2 p-4 transition"
                               :class="paket === 'lengkap' ? 'border-merah-500 bg-merah-500/5' : 'border-navy-100'">
                            <input type="radio" name="paket" value="lengkap" x-model="paket" class="sr-only">
                            <span class="block font-display text-lg font-bold text-navy-700">Paket Lengkap</span>
                            <span class="block font-display text-2xl font-black text-merah-500">Rp120.000</span>
                            <span class="mt-1 block text-sm text-navy-900/70">Jersey + fasilitas kegiatan</span>
                        </label>
                        <label class="cursor-pointer rounded-xl border-2 p-4 transition"
                               :class="paket === 'hemat' ? 'border-navy-700 bg-navy-50' : 'border-navy-100'">
                            <input type="radio" name="paket" value="hemat" x-model="paket" class="sr-only">
                            <span class="block font-display text-lg font-bold text-navy-700">Paket Hemat</span>
                            <span class="block font-display text-2xl font-black text-navy-700">Rp60.000</span>
                            <span class="mt-1 block text-sm text-navy-900/70">Fasilitas kegiatan (tanpa jersey)</span>
                        </label>
                    </div>

                    <div x-show="paket === 'lengkap'" x-cloak class="mt-4">
                        <span class="label">Ukuran Jersey</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($stokJersey as $ukuran => $info)
                                <label class="relative">
                                    <input type="radio" name="ukuran_jersey" value="{{ $ukuran }}" class="peer sr-only"
                                           @checked(old('ukuran_jersey') === $ukuran) @disabled($info['sisa'] < 1)>
                                    <span @class([
                                        'block rounded-xl border-2 px-5 py-3 text-center font-display font-bold',
                                        'border-navy-100 text-navy-700 cursor-pointer peer-checked:border-merah-500 peer-checked:bg-merah-500 peer-checked:text-white' => $info['sisa'] > 0,
                                        'border-navy-100 bg-navy-50 text-navy-900/30 line-through cursor-not-allowed' => $info['sisa'] < 1,
                                    ])>
                                        {{ $ukuran }}
                                    </span>
                                    <span class="mt-1 block text-center text-[11px] text-navy-900/60">
                                        {{ $info['sisa'] > 0 ? 'sisa ' . $info['sisa'] : 'habis' }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg text-navy-700">2. Data Peserta</h2>
                    <div class="mt-3 grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="label" for="nama_lengkap">Nama Lengkap <span class="text-merah-500">*</span></label>
                            <input id="nama_lengkap" name="nama_lengkap" type="text" class="input" required
                                   maxlength="120" value="{{ old('nama_lengkap') }}">
                        </div>

                        <div>
                            <label class="label" for="jenis_kelamin">Jenis Kelamin <span class="text-merah-500">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="input" required>
                                <option value="">— Pilih —</option>
                                <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                                <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="label" for="tanggal_lahir">Tanggal Lahir <span class="text-merah-500">*</span></label>
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date" class="input" required
                                   value="{{ old('tanggal_lahir') }}">
                        </div>

                        <div>
                            <label class="label" for="no_hp">No. HP / WhatsApp <span class="text-merah-500">*</span></label>
                            <input id="no_hp" name="no_hp" type="tel" class="input" required maxlength="20"
                                   placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}">
                        </div>

                        <div>
                            <label class="label" for="email">Email (opsional)</label>
                            <input id="email" name="email" type="email" class="input" maxlength="120" value="{{ old('email') }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="label" for="asal_instansi">Asal Instansi / Sekolah / Komunitas</label>
                            <input id="asal_instansi" name="asal_instansi" type="text" class="input" maxlength="150"
                                   value="{{ old('asal_instansi') }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="label" for="alamat">Alamat <span class="text-merah-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="2" class="input" required maxlength="500">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg text-navy-700">3. Data Tambahan</h2>
                    <div class="mt-3 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="label" for="kontak_darurat_nama">Nama Kontak Darurat</label>
                            <input id="kontak_darurat_nama" name="kontak_darurat_nama" type="text" class="input"
                                   maxlength="120" value="{{ old('kontak_darurat_nama') }}">
                        </div>
                        <div>
                            <label class="label" for="kontak_darurat_hp">No. HP Kontak Darurat</label>
                            <input id="kontak_darurat_hp" name="kontak_darurat_hp" type="tel" class="input"
                                   maxlength="20" value="{{ old('kontak_darurat_hp') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="label" for="riwayat_penyakit">Riwayat Penyakit (jika ada)</label>
                            <input id="riwayat_penyakit" name="riwayat_penyakit" type="text" class="input"
                                   maxlength="255" placeholder="Contoh: asma, hipertensi" value="{{ old('riwayat_penyakit') }}">
                        </div>
                    </div>
                </div>

                <label class="flex items-start gap-3 rounded-xl bg-krem p-4 text-sm text-navy-900/80">
                    <input type="checkbox" name="setuju" value="1" class="mt-0.5 h-4 w-4" @checked(old('setuju'))>
                    <span>
                        Saya menyatakan data yang diisi benar, dalam kondisi sehat untuk mengikuti kegiatan lari
                        {{ config('funrun.jarak') }}, serta bersedia mengikuti seluruh ketentuan panitia.
                    </span>
                </label>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="btn-merah">Kirim Pendaftaran</button>
                    <span class="text-sm text-navy-900/60">Sisa kuota: {{ number_format($sisaKuota, 0, ',', '.') }} peserta</span>
                </div>
            </form>
        @endif
    </section>
@endsection
