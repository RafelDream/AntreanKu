<?php

namespace App\Http\Requests;

class CekNikRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi dengan 16 digit angka',
            'nik.digits'   => 'NIK wajib diisi dengan 16 digit angka',
        ];
    }
}