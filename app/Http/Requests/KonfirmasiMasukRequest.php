<?php

namespace App\Http\Requests;

class KonfirmasiMasukRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pasien_id' => ['required', 'exists:pasien,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'pasien_id.required' => 'Data pasien tidak ditemukan, silakan ulangi dari awal',
            'pasien_id.exists'   => 'Data pasien tidak ditemukan, silakan ulangi dari awal',
        ];
    }
}