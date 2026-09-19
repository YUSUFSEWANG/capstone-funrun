<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CekPendaftaranController extends Controller
{
    public function index(): View
    {
        return view('publik.cek', ['hasil' => null, 'sudahCari' => false]);
    }

    public function cari(Request $request): View
    {
        $data = $request->validate([
            'kata_kunci' => ['required', 'string', 'max:60'],
        ], [], ['kata_kunci' => 'kode pendaftaran atau nomor HP']);

        $kunci = trim($data['kata_kunci']);

        $hasil = Peserta::with('pembayaran')
            ->where('kode_daftar', $kunci)
            ->orWhere('no_hp', $kunci)
            ->orWhere('no_bib', $kunci)
            ->limit(10)
            ->get();

        return view('publik.cek', [
            'hasil' => $hasil,
            'sudahCari' => true,
            'kataKunci' => $kunci,
        ]);
    }
}
