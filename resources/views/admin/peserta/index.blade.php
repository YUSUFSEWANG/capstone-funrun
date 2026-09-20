@extends('layouts.admin')

@section('judul', 'Data Peserta')

@section('konten')
    <form method="GET" action="{{ route('admin.peserta.index') }}" class="card">
        <div class="grid gap-3 md:grid-cols-5">
            <div class="md:col-span-2">
                <label class="label" for="cari">Cari</label>
                <input id="cari" name="cari" type="text" class="input" maxlength="60"
                       placeholder="Nama / kode / nomor peserta / No. HP" value="{{ $filter['cari'] ?? '' }}">
            </div>
            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="input">
                    <option value="">Semua</option>
                    @foreach ([
                        'menunggu_bayar' => 'Menunggu Pembayaran',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'terverifikasi' => 'Terverifikasi',
                        'ditolak' => 'Ditolak',
                    ] as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(($filter['status'] ?? '') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="paket">Paket</label>
                <select id="paket" name="paket" class="input">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Peserta::PAKET as $nilai => $info)
                        <option value="{{ $nilai }}" @selected(($filter['paket'] ?? '') === $nilai)>{{ $info['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="ukuran">Jersey</label>
                <select id="ukuran" name="ukuran" class="input">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Peserta::UKURAN_JERSEY as $ukuran)
                        <option value="{{ $ukuran }}" @selected(($filter['ukuran'] ?? '') === $ukuran)>{{ $ukuran }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <button type="submit" class="btn-navy">Terapkan Filter</button>
            <a href="{{ route('admin.peserta.index') }}" class="btn-outline">Reset</a>
            <a href="{{ route('admin.peserta.export', request()->query()) }}" class="btn-merah ml-auto">Export CSV / Excel</a>
        </div>
    </form>

    <div x-data="{ semua: @js($peserta->pluck('id')->map(fn ($id) => (string) $id)->all()), terpilih: [] }">
        <div class="mt-4 flex flex-wrap items-center gap-3 rounded-2xl bg-white px-5 py-3 shadow-card">
            <p class="text-sm text-navy-900/70">
                <span class="font-display font-bold text-navy-700" x-text="terpilih.length">0</span> peserta dipilih
            </p>

            <div class="ml-auto flex flex-wrap gap-2">
                <x-konfirmasi
                    judul="Hapus Peserta Terpilih"
                    pesan="Peserta yang dicentang beserta bukti pembayarannya akan dihapus permanen."
                    label="Hapus Terpilih"
                    kelas-tombol="btn-merah px-5 py-2 text-sm"
                    label-konfirmasi="Ya, Hapus"
                    form="formHapusTerpilih"
                    nonaktif="terpilih.length === 0" />

                <x-konfirmasi
                    judul="Hapus SEMUA Data Peserta"
                    pesan="Seluruh data peserta dan bukti pembayaran akan dihapus permanen. Kuota dan stok jersey kembali penuh, nomor peserta mulai lagi dari PGRI-TOMINI-001. Tindakan ini tidak dapat dibatalkan."
                    label="Hapus Semua Data"
                    kelas-tombol="btn border-2 border-merah-500 px-5 py-2 text-sm text-merah-600 hover:bg-merah-500 hover:text-white"
                    label-konfirmasi="Hapus Semua"
                    ketik="HAPUS SEMUA"
                    :aksi="route('admin.peserta.hapus-semua')" />
            </div>
        </div>

        <form id="formHapusTerpilih" method="POST" action="{{ route('admin.peserta.hapus-terpilih') }}">
            @csrf
            @method('DELETE')

            <div class="card mt-4 overflow-x-auto">
                <table class="w-full min-w-[880px] text-sm">
                    <thead>
                        <tr class="border-b border-navy-100 text-left text-navy-900/60">
                            <th class="w-10 py-2 pr-3">
                                <input type="checkbox" class="h-4 w-4 accent-red-600"
                                    @change="terpilih = $event.target.checked ? [...semua] : []"
                                    :checked="semua.length > 0 && terpilih.length === semua.length"
                                    aria-label="Pilih semua peserta di halaman ini">
                            </th>
                            <th class="py-2 pr-3">No. Peserta</th>
                            <th class="py-2 pr-3">Nama</th>
                            <th class="py-2 pr-3">Kontak</th>
                            <th class="py-2 pr-3">Paket</th>
                            <th class="py-2 pr-3">Jersey</th>
                            <th class="py-2 pr-3">Status</th>
                            <th class="py-2 pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peserta as $row)
                            <tr class="border-b border-navy-50" :class="terpilih.includes(@js((string) $row->id)) && 'bg-merah-500/5'">
                                <td class="py-2.5 pr-3">
                                    <input type="checkbox" name="ids[]" value="{{ $row->id }}" x-model="terpilih"
                                        class="h-4 w-4 accent-red-600" aria-label="Pilih {{ $row->nama_lengkap }}">
                                </td>
                                <td class="py-2.5 pr-3 font-display font-bold text-navy-700">{{ $row->no_bib }}</td>
                                <td class="py-2.5 pr-3">
                                    <p class="font-semibold text-navy-700">{{ $row->nama_lengkap }}</p>
                                    <p class="text-xs text-navy-900/50">{{ $row->kode_daftar }}</p>
                                </td>
                                <td class="py-2.5 pr-3">
                                    <a href="https://wa.me/{{ $row->wa_number }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">{{ $row->no_hp }}</a>
                                    <p class="text-xs text-navy-900/50">{{ $row->asal_instansi ?: '-' }}</p>
                                </td>
                                <td class="py-2.5 pr-3">{{ $row->label_paket }}</td>
                                <td class="py-2.5 pr-3">{{ $row->ukuran_jersey ?: '-' }}</td>
                                <td class="py-2.5 pr-3"><span class="badge {{ $row->warna_status }}">{{ $row->label_status }}</span></td>
                                <td class="py-2.5 pr-3">
                                    <a href="{{ route('admin.peserta.show', $row) }}" class="font-semibold text-merah-500 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-6 text-center text-navy-900/60">Belum ada data peserta.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <div class="mt-4">{{ $peserta->links() }}</div>
@endsection
