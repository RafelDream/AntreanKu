<?php

namespace App\Http\Requests;

class RegistrasiPasienBaruRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap'  => ['required', 'string', 'max:150'],
            'nik'           => ['required', 'digits:16', 'unique:pasien,nik'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'no_telepon'    => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string'],
            // Checkbox S&K hanya diwajibkan di form web; API (Postman) tidak terpengaruh
            'setuju'        => $this->is('api/*') ? ['nullable'] : ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required'         => 'Nama lengkap wajib diisi',
            'nik.required'                  => 'NIK wajib diisi dengan 16 digit angka',
            'nik.digits'                    => 'NIK wajib diisi dengan 16 digit angka',
            'nik.unique'                    => 'NIK sudah terdaftar, silakan gunakan jalur pasien lama',
            'tanggal_lahir.required'        => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date'            => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini',
            'setuju.accepted'               => 'Centang persetujuan Syarat dan Ketentuan serta Kebijakan Privasi',
        ];
    }
}
