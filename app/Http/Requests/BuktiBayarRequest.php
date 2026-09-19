<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuktiBayarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_pengirim' => ['required', 'string', 'max:120'],
            'bank_pengirim' => ['nullable', 'string', 'max:60'],
            'nominal' => ['required', 'integer', 'min:1000', 'max:100000000'],
            'tanggal_transfer' => ['required', 'date', 'before_or_equal:today'],
            'file_bukti' => [
                'required',
                'file',
                'mimes:' . implode(',', config('funrun.upload.mime')),
                'max:' . config('funrun.upload.max_kb'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_pengirim' => 'nama pengirim',
            'bank_pengirim' => 'bank pengirim',
            'tanggal_transfer' => 'tanggal transfer',
            'file_bukti' => 'file bukti transfer',
        ];
    }

    public function messages(): array
    {
        return [
            'file_bukti.mimes' => 'Bukti transfer harus berformat JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran file maksimal ' . round(config('funrun.upload.max_kb') / 1024, 1) . ' MB.',
        ];
    }
}
