@extends('layouts.admin')

@section('judul', 'Data Peserta')

@section('konten')
    <form method="GET" action="{{ route('admin.peserta.index') }}" class="card">
        <div class="grid gap-3 md:grid-cols-5">
            <div class="md:col-span-2">
                <label class="label" for="cari">Cari</label>
                <input id="cari" name="cari" type="text" class="input" maxlength="60"
                       placeholder="Nama / kode / BIB / No. HP" value="{{ $filter['cari'] ?? '' }}">
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

    <div class="card mt-4 overflow-x-auto">
        <table class="w-full min-w-[820px] text-sm">
            <thead>
                <tr class="border-b border-navy-100 text-left text-navy-900/60">
                    <th class="py-2 pr-3">BIB</th>
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
                    <tr class="border-b border-navy-50">
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
                    <tr><td colspan="7" class="py-6 text-center text-navy-900/60">Belum ada data peserta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $peserta->links() }}</div>
@endsection
