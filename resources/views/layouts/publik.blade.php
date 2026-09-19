<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Beranda') — {{ config('funrun.nama') }}</title>
    <meta name="description" content="{{ config('funrun.tagline') }} Pendaftaran Fun Run 7 KM di Tomini, Kabupaten Parigi Moutong.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:600,700,800,900" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <header x-data="{ buka: false }" class="sticky top-0 z-50 border-b border-navy-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3">
            <a href="{{ route('beranda') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-navy-700 font-display text-sm font-black text-white">FR</span>
                <span class="leading-tight">
                    <span class="block font-display text-base font-extrabold italic text-merah-500">FUN RUN</span>
                    <span class="block font-display text-sm font-bold text-navy-700">PGRI TOMINI 2026</span>
                </span>
            </a>

            <nav class="ml-auto hidden items-center gap-1 lg:flex">
                @foreach ([
                    ['beranda', 'Beranda'],
                    ['tentang', 'Tentang'],
                    ['pendaftaran.create', 'Pendaftaran'],
                    ['cek.index', 'Cek Pendaftaran'],
                    ['kontak', 'Kontak'],
                ] as [$rute, $label])
                    <a href="{{ route($rute) }}"
                       class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs($rute) ? 'bg-merah-500 text-white' : 'text-navy-700 hover:bg-navy-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('admin.login') }}" class="ml-2 rounded-full bg-merah-500 px-5 py-2 text-sm font-semibold text-white hover:bg-merah-600">
                    Login Admin
                </a>
            </nav>

            <button type="button" @click="buka = !buka" class="ml-auto rounded-lg p-2 text-navy-700 lg:hidden" aria-label="Buka menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <nav x-show="buka" x-cloak class="border-t border-navy-100 bg-white px-4 py-3 lg:hidden">
            @foreach ([
                ['beranda', 'Beranda'],
                ['tentang', 'Tentang'],
                ['pendaftaran.create', 'Pendaftaran'],
                ['cek.index', 'Cek Pendaftaran'],
                ['kontak', 'Kontak'],
                ['admin.login', 'Login Admin'],
            ] as [$rute, $label])
                <a href="{{ route($rute) }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-navy-700 hover:bg-navy-50">{{ $label }}</a>
            @endforeach
        </nav>
    </header>

    <main>
        @if (session('sukses'))
            <div class="mx-auto mt-6 max-w-5xl px-4">
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('sukses') }}
                </div>
            </div>
        @endif

        @if (session('gagal'))
            <div class="mx-auto mt-6 max-w-5xl px-4">
                <div class="rounded-xl border border-merah-400/40 bg-merah-500/10 px-4 py-3 text-sm font-semibold text-merah-700">
                    {{ session('gagal') }}
                </div>
            </div>
        @endif

        @yield('konten')
    </main>

    <footer class="mt-16 bg-navy-800 text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-3">
            <div>
                <h3 class="font-display text-lg italic">{{ config('funrun.nama') }}</h3>
                <p class="mt-2 text-sm text-white/70">{{ config('funrun.tagline') }}</p>
            </div>
            <div class="text-sm text-white/80">
                <h4 class="mb-2 text-base">Lokasi & Waktu</h4>
                <p>{{ config('funrun.lokasi') }}</p>
                <p class="mt-1">{{ config('funrun.hari_acara') }}, {{ \Carbon\Carbon::parse(config('funrun.tanggal_acara'))->translatedFormat('d F Y') }}</p>
            </div>
            <div class="text-sm text-white/80">
                <h4 class="mb-2 text-base">Kontak Panitia</h4>
                @foreach (config('funrun.kontak') as $kontak)
                    <p><a class="hover:text-white" href="https://wa.me/{{ $kontak['wa'] }}" target="_blank" rel="noopener">{{ $kontak['nama'] }} — {{ $kontak['hp'] }}</a></p>
                @endforeach
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-white/60">
            Bersama M. IZHAR IL, Sehat, Solid, Berkarya &middot; &copy; {{ date('Y') }} PGRI Kecamatan Tomini
        </div>
    </footer>
</body>
</html>
