<?php

// Entrypoint Vercel: aset statis dilayani langsung, sisanya diteruskan ke Laravel.
$publicPath = realpath(__DIR__ . '/../public');
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$berkas = realpath($publicPath . $uri);
$ekstensi = strtolower(pathinfo((string) $berkas, PATHINFO_EXTENSION));

$tipe = [
    'css' => 'text/css',
    'js' => 'text/javascript',
    'mjs' => 'text/javascript',
    'svg' => 'image/svg+xml',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'webp' => 'image/webp',
    'gif' => 'image/gif',
    'ico' => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'txt' => 'text/plain',
    'json' => 'application/json',
    'map' => 'application/json',
];

if (
    $uri !== '/'
    && $berkas !== false
    && is_file($berkas)
    && str_starts_with($berkas, $publicPath . DIRECTORY_SEPARATOR)
    && isset($tipe[$ekstensi])
) {
    header('Content-Type: ' . $tipe[$ekstensi]);
    header('Cache-Control: public, max-age=31536000, immutable');
    readfile($berkas);
    exit;
}

require $publicPath . '/index.php';
