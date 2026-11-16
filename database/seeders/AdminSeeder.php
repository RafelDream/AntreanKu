<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('antreanku.admin.username');
        $password = config('antreanku.admin.password');

        if (! $username || ! $password) {
            $this->command->error('ADMIN_USERNAME dan ADMIN_PASSWORD wajib diisi di .env');
            return;
        }

        User::updateOrCreate(
            ['username' => $username],
            [
                'name'     => 'Petugas Klinik',
                'email'    => config('antreanku.admin.email'),
                'password' => Hash::make($password),
            ]
        );
    }
}