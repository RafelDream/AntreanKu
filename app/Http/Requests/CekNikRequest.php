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
            'nik'    => ['required', 'digits:16'],
            // Checkbox S&K hanya diwajibkan di form web; API (Postman) tidak terpengaruh
            'setuju' => $this->is('api/*') ? ['nullable'] : ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'    => 'NIK wajib diisi dengan 16 digit angka',
            'nik.digits'      => 'NIK wajib diisi dengan 16 digit angka',
            'setuju.accepted' => 'Centang persetujuan Syarat dan Ketentuan serta Kebijakan Privasi',
        ];
    }
}
