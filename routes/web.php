<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CekPendaftaranController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/tentang', [BerandaController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [BerandaController::class, 'kontak'])->name('kontak');

Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('pendaftaran.store');

Route::get('/cek', [CekPendaftaranController::class, 'index'])->name('cek.index');
Route::post('/cek', [CekPendaftaranController::class, 'cari'])
    ->middleware('throttle:20,1')
    ->name('cek.cari');

Route::get('/status/{kode}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
Route::post('/status/{kode}/bukti', [PendaftaranController::class, 'uploadBukti'])
    ->middleware('throttle:10,1')
    ->name('pendaftaran.bukti');
Route::get('/status/{kode}/tiket', [PendaftaranController::class, 'tiket'])->name('pendaftaran.tiket');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'form'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('peserta', [PesertaController::class, 'index'])->name('peserta.index');
        Route::get('peserta/export', [PesertaController::class, 'export'])->name('peserta.export');
        Route::get('peserta/{peserta}', [PesertaController::class, 'show'])->name('peserta.show');
        Route::get('peserta/{peserta}/bukti', [PesertaController::class, 'bukti'])->name('peserta.bukti');
        Route::patch('peserta/{peserta}/verifikasi', [PesertaController::class, 'verifikasi'])->name('peserta.verifikasi');
        Route::patch('peserta/{peserta}/tolak', [PesertaController::class, 'tolak'])->name('peserta.tolak');
        Route::delete('peserta/{peserta}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

        Route::get('pengaturan', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
        Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });
});
