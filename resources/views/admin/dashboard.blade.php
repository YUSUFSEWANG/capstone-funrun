@extends('layouts.admin')

@section('judul', 'Dashboard')

@section('konten')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card">
            <p class="text-sm text-navy-900/60">Total Peserta</p>
            <p class="font-display text-3xl font-black text-navy-700">{{ number_format($totalPeserta, 0, ',', '.') }}</p>
            <p class="text-xs text-navy-900/60">dari kuota {{ number_format($kuota, 0, ',', '.') }} &middot; sisa {{ number_format($sisaKuota, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-navy-900/60">Perlu Verifikasi</p>
            <p class="font-display text-3xl font-black text-merah-500">{{ $perStatus['menunggu_verifikasi'] ?? 0 }}</p>
            <p class="text-xs text-navy-900/60">Menunggu bayar: {{ $perStatus['menunggu_bayar'] ?? 0 }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-navy-900/60">Dana Terverifikasi</p>
            <p class="font-display text-2xl font-black text-emerald-600">Rp{{ number_format($danaTerverifikasi, 0, ',', '.') }}</p>
            <p class="text-xs text-navy-900/60">Tertunda: Rp{{ number_format($danaTertunda, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-navy-900/60">Status Pendaftaran</p>
            <p class="font-display text-2xl font-black {{ $statusPendaftaran === 'Dibuka' ? 'text-emerald-600' : 'text-merah-500' }}">{{ $statusPendaftaran }}</p>
            <p class="text-xs text-navy-900/60">{{ $alasanTutup ?? 'Peserta dapat mendaftar saat ini.' }}</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-3">
        <div class="card">
            <h2 class="text-lg text-navy-700">Peserta per Paket</h2>
            <div class="mt-3 space-y-2 text-sm">
                @foreach (\App\Models\Peserta::PAKET as $kunci => $info)
                    <div class="flex items-center justify-between rounded-lg bg-krem px-3 py-2">
                        <span class="font-semibold text-navy-700">{{ $info['label'] }}</span>
                        <span class="font-display font-black">{{ $perPaket[$kunci] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card lg:col-span-2">
            <h2 class="text-lg text-navy-700">Stok Jersey</h2>
            <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-5">
                @foreach ($stokJersey as $ukuran => $info)
                    <div class="rounded-xl border border-navy-100 p-3 text-center">
                        <p class="font-display text-xl font-black text-navy-700">{{ $ukuran }}</p>
                        <p class="text-xs text-navy-900/60">sisa</p>
                        <p class="font-display text-lg font-bold {{ $info['sisa'] > 0 ? 'text-emerald-600' : 'text-merah-500' }}">{{ $info['sisa'] }}</p>
                        <p class="text-[11px] text-navy-900/50">{{ $info['terpakai'] }}/{{ $info['stok'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg text-navy-700">Menunggu Verifikasi Terbaru</h2>
            <a href="{{ route('admin.peserta.index', ['status' => 'menunggu_verifikasi']) }}" class="text-sm font-semibold text-merah-500 hover:underline">Lihat semua</a>
        </div>

        @forelse ($perluVerifikasi as $peserta)
            <a href="{{ route('admin.peserta.show', $peserta) }}"
               class="mt-3 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-navy-100 px-4 py-3 hover:bg-krem">
                <div>
                    <p class="font-semibold text-navy-700">{{ $peserta->nama_lengkap }}</p>
                    <p class="text-xs text-navy-900/60">{{ $peserta->kode_daftar }} &middot; {{ $peserta->label_paket }} &middot; {{ $peserta->created_at->diffForHumans() }}</p>
                </div>
                <span class="font-display font-black text-merah-500">Rp{{ number_format($peserta->biaya, 0, ',', '.') }}</span>
            </a>
        @empty
            <p class="mt-3 text-sm text-navy-900/60">Tidak ada pembayaran yang menunggu verifikasi.</p>
        @endforelse
    </div>
@endsection
