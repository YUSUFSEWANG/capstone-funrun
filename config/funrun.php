<?php

return [
    'nama' => 'FUN RUN PGRI TOMINI 2026',
    'tagline' => 'Sehat Bersama, Solid Berkarya, Semarakkan Tomini!',
    'jarak' => '7 KM',
    'tanggal_acara' => '2026-11-24',
    'hari_acara' => 'Selasa',
    'lokasi' => 'Tomini, Kabupaten Parigi Moutong, Sulawesi Tengah',
    'pendaftaran_mulai' => '2026-09-21',
    'pendaftaran_selesai' => '2026-10-10',

    'benefit' => [
        ['judul' => 'Jersey', 'ikon' => 'shirt', 'ket' => 'Jersey eksklusif Fun Run PGRI Tomini 2026'],
        ['judul' => 'Medali', 'ikon' => 'medal', 'ket' => 'Medali finisher untuk seluruh peserta'],
        ['judul' => 'Uang Tunai', 'ikon' => 'cash', 'ket' => 'Hadiah uang tunai bagi pemenang'],
        ['judul' => 'Doorprize Menarik', 'ikon' => 'gift', 'ket' => 'Doorprize untuk peserta beruntung'],
    ],

    'bank' => [
        'nama' => 'Bank BRI',
        'rekening' => '036301026063507',
        'atas_nama' => 'M. IZHAR IL',
    ],

    'kontak' => [
        ['nama' => 'Munawir', 'hp' => '0812 4374 0109', 'wa' => '6281243740109'],
        ['nama' => 'Isfar', 'hp' => '0823 4814 8054', 'wa' => '6282348148054'],
    ],

    'upload' => [
        'max_kb' => (int) env('UPLOAD_MAX_KB', 5120),
        'mime' => ['jpg', 'jpeg', 'png', 'pdf'],
    ],

    // Disk penyimpanan bukti pembayaran; di Railway arahkan Volume ke storage/app/private.
    'disk_bukti' => env('DISK_BUKTI', 'private'),
];
