<?php

namespace App\Http\Requests;

use App\Models\Peserta;
use App\Services\PendaftaranService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PendaftaranRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir' => ['required', 'date', 'before:today', 'after:1930-01-01'],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'email' => ['nullable', 'email:rfc', 'max:120'],
            'asal_instansi' => ['nullable', 'string', 'max:150'],
            'alamat' => ['required', 'string', 'max:500'],
            'paket' => ['required', Rule::in(array_keys(Peserta::PAKET))],
            'ukuran_jersey' => ['nullable', Rule::in(Peserta::UKURAN_JERSEY)],
            'kontak_darurat_nama' => ['nullable', 'string', 'max:120'],
            'kontak_darurat_hp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'riwayat_penyakit' => ['nullable', 'string', 'max:255'],
            'setuju' => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'jenis_kelamin' => 'jenis kelamin',
            'tanggal_lahir' => 'tanggal lahir',
            'no_hp' => 'nomor HP/WhatsApp',
            'asal_instansi' => 'asal instansi',
            'ukuran_jersey' => 'ukuran jersey',
            'kontak_darurat_nama' => 'nama kontak darurat',
            'kontak_darurat_hp' => 'nomor kontak darurat',
            'setuju' => 'persetujuan',
        ];
    }

    public function messages(): array
    {
        return [
            'setuju.accepted' => 'Anda harus menyetujui syarat dan ketentuan kegiatan.',
            'no_hp.regex' => 'Nomor HP/WhatsApp hanya boleh berisi angka.',
            'kontak_darurat_hp.regex' => 'Nomor kontak darurat hanya boleh berisi angka.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var PendaftaranService $service */
            $service = app(PendaftaranService::class);
            $paket = (string) $this->input('paket');
            $ukuran = $this->input('ukuran_jersey');

            if (! array_key_exists($paket, Peserta::PAKET)) {
                return;
            }

            if (! $service->butuhJersey($paket)) {
                return;
            }

            if (blank($ukuran)) {
                $validator->errors()->add('ukuran_jersey', 'Ukuran jersey wajib dipilih untuk Paket Lengkap.');

                return;
            }

            if (($service->stokJersey()[$ukuran]['sisa'] ?? 0) < 1) {
                $validator->errors()->add('ukuran_jersey', 'Stok jersey ukuran ' . $ukuran . ' sudah habis. Silakan pilih ukuran lain.');
            }
        });
    }
}
