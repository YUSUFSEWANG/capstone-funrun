<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — {{ config('funrun.nama') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:600,700,800,900" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-gradient-to-br from-langit-400 to-navy-700 p-4">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center text-white">
            <img src="{{ asset('img/logo-pgri.png') }}" alt="Logo PGRI" class="mx-auto mb-3 h-20 w-20 object-contain">
            <p class="font-display text-3xl font-black italic text-merah-400">FUN RUN</p>
            <p class="font-display text-lg font-bold">PGRI TOMINI 2026</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" class="card space-y-4">
            @csrf
            <h1 class="text-xl text-navy-700">Login Panitia</h1>

            @if ($errors->any())
                <div class="rounded-xl border border-merah-400/40 bg-merah-500/10 px-3 py-2 text-sm text-merah-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="label" for="email">Email</label>
                <input id="email" name="email" type="email" class="input" required autofocus value="{{ old('email') }}">
            </div>

            <div>
                <label class="label" for="password">Kata Sandi</label>
                <input id="password" name="password" type="password" class="input" required>
            </div>

            <label class="flex items-center gap-2 text-sm text-navy-900/70">
                <input type="checkbox" name="ingat" value="1" class="h-4 w-4"> Ingat saya
            </label>

            <button type="submit" class="btn-merah w-full">Masuk</button>

            <a href="{{ route('beranda') }}" class="block text-center text-sm text-navy-700 hover:underline">Kembali ke beranda</a>
        </form>
    </div>
</body>
</html>
