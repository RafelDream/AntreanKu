<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    private const MAKS_GAGAL = 5;
    private const LOCKOUT_MENIT = 10;

    public function login(string $username, string $password, string $ip): User
    {
        $key = "login_gagal_{$ip}";

        if (Cache::get($key, 0) >= self::MAKS_GAGAL) {
            abort(429, 'Terlalu banyak percobaan gagal, coba lagi dalam ' . self::LOCKOUT_MENIT . ' menit');
        }

        if (! Auth::attempt(['username' => $username, 'password' => $password])) {
            Cache::put($key, Cache::get($key, 0) + 1, now()->addMinutes(self::LOCKOUT_MENIT));
            abort(401, 'Username atau password salah');
        }

        Cache::forget($key); // reset: hitungannya "berturut-turut", bukan kumulatif

        return Auth::user();
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}