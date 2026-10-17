<?php

namespace App\Http\Requests;

class BatalAntreanRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['token' => ['required', 'string', 'exists:antrean,token']];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Token antrean wajib disertakan',
            'token.exists'    => 'Tiket antrean tidak ditemukan',
        ];
    }
}