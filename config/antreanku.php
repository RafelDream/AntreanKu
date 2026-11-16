<?php

return [
    
    'admin' => [
        'username' => env('ADMIN_USERNAME'),
        'password' => env('ADMIN_PASSWORD'),
        'email'    => env('ADMIN_EMAIL', 'petugas@antreanku.test'),
    ],
    // Nomor telepon klinik untuk tautan "Hubungi Klinik" di halaman utama (format bebas, mis. 0812-3456-7890).
    // Isi lewat .env: KLINIK_TELEPON=...
    'telepon_klinik' => env('KLINIK_TELEPON'),
];
