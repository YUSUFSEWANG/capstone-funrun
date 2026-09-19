<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Admin') — {{ config('funrun.nama') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:600,700,800,900" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-krem">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 bg-navy-800 text-white lg:block">
            <div class="px-6 py-6">
                <p class="font-display text-lg font-black italic text-merah-400">FUN RUN</p>
                <p class="text-sm font-semibold">PGRI TOMINI 2026</p>
            </div>
            <nav class="space-y-1 px-3">
                @foreach ([
                    ['admin.dashboard', 'Dashboard'],
                    ['admin.peserta.index', 'Data Peserta'],
                    ['admin.pengaturan.edit', 'Pengaturan'],
                ] as [$rute, $label])
                    <a href="{{ route($rute) }}"
                       class="block rounded-lg px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs(str_replace('.index', '.*', $rute)) || request()->routeIs($rute) ? 'bg-merah-500 text-white' : 'text-white/75 hover:bg-white/10' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('beranda') }}" target="_blank" class="block rounded-lg px-4 py-2.5 text-sm font-semibold text-white/75 hover:bg-white/10">
                    Lihat Situs &#8599;
                </a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center gap-4 border-b border-navy-100 bg-white px-4 py-3">
                <h1 class="font-display text-lg text-navy-700">@yield('judul', 'Dashboard')</h1>
                <div class="ml-auto flex items-center gap-3">
                    <span class="hidden text-sm text-navy-900/70 sm:block">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-800">Keluar</button>
                    </form>
                </div>
            </header>

            <nav class="flex gap-2 overflow-x-auto border-b border-navy-100 bg-white px-4 py-2 lg:hidden">
                @foreach ([
                    ['admin.dashboard', 'Dashboard'],
                    ['admin.peserta.index', 'Peserta'],
                    ['admin.pengaturan.edit', 'Pengaturan'],
                ] as [$rute, $label])
                    <a href="{{ route($rute) }}" class="whitespace-nowrap rounded-full px-4 py-1.5 text-sm font-semibold {{ request()->routeIs($rute) ? 'bg-merah-500 text-white' : 'bg-navy-50 text-navy-700' }}">{{ $label }}</a>
                @endforeach
            </nav>

            <main class="flex-1 p-4 lg:p-6">
                @if (session('sukses'))
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('sukses') }}</div>
                @endif
                @if (session('gagal'))
                    <div class="mb-4 rounded-xl border border-merah-400/40 bg-merah-500/10 px-4 py-3 text-sm font-semibold text-merah-700">{{ session('gagal') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-merah-400/40 bg-merah-500/10 px-4 py-3 text-sm text-merah-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $pesan)
                                <li>{{ $pesan }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('konten')
            </main>
        </div>
    </div>
</body>
</html>
