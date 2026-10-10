<?php

namespace App\Http\Requests;

class RegistrasiPasienLamaRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_rekam_medis' => ['required', 'string'],
            'tanggal_lahir'  => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_rekam_medis.required' => 'Nomor Rekam Medis wajib diisi',
            'tanggal_lahir.required'  => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date'      => 'Format tanggal lahir tidak valid',
        ];
    }
}