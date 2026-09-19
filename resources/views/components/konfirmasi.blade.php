@props([
    'judul' => 'Konfirmasi',
    'pesan' => '',
    'label' => 'Hapus',
    'kelasTombol' => 'btn-merah',
    'kelasKonfirmasi' => 'btn-merah',
    'labelKonfirmasi' => 'Ya, Hapus',
    'ketik' => null,
    'aksi' => null,
    'method' => 'DELETE',
    'form' => null,
    'nonaktif' => null,
])

<div x-data="{ buka: false, teks: '' }" @keydown.escape.window="buka = false" class="contents">
    <button type="button" @click="buka = true; teks = ''" class="{{ $kelasTombol }}"
        @if ($nonaktif) :disabled="{{ $nonaktif }}" :class="({{ $nonaktif }}) && 'cursor-not-allowed opacity-40'" @endif>
        {{ $label }}
    </button>

    {{-- Diteleport ke body agar tidak membuat form bersarang di dalam tabel. --}}
    <template x-teleport="body">
        <div x-show="buka" x-cloak class="fixed inset-0 z-[80] grid place-items-center bg-navy-900/60 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-card" @click.outside="buka = false">
                <div class="flex items-start gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-merah-500/10">
                        <svg class="h-5 w-5 text-merah-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-lg text-navy-700">{{ $judul }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-navy-900/70">{{ $pesan }}</p>
                    </div>
                </div>

                @if ($ketik)
                    <label class="label mt-5">
                        Ketik <span class="rounded bg-krem px-1.5 py-0.5 font-mono text-merah-600">{{ $ketik }}</span> untuk melanjutkan
                    </label>
                    <input type="text" x-model="teks" class="input" autocomplete="off" spellcheck="false">
                @endif

                <div class="mt-6 flex flex-wrap justify-end gap-2">
                    <button type="button" @click="buka = false" class="btn-outline px-5 py-2 text-sm">Batal</button>

                    @if ($aksi)
                        <form method="POST" action="{{ $aksi }}">
                            @csrf
                            @method($method)
                            {{ $slot }}
                            <button type="submit" class="{{ $kelasKonfirmasi }} px-5 py-2 text-sm"
                                @if ($ketik) :disabled="teks !== @js($ketik)" :class="teks !== @js($ketik) && 'cursor-not-allowed opacity-50'" @endif>
                                {{ $labelKonfirmasi }}
                            </button>
                        </form>
                    @else
                        <button type="submit" form="{{ $form }}" class="{{ $kelasKonfirmasi }} px-5 py-2 text-sm"
                            @if ($ketik) :disabled="teks !== @js($ketik)" :class="teks !== @js($ketik) && 'cursor-not-allowed opacity-50'" @endif>
                            {{ $labelKonfirmasi }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </template>
</div>
