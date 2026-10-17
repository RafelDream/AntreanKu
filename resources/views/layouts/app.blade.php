<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#08CE56">
    <title>@yield('title', 'ANTREANKU')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/antreanku.css') }}?v={{ filemtime(public_path('css/antreanku.css')) }}">
</head>
<body>
    <div class="shell @yield('shell')">
        <aside class="brand-panel">
            <p class="brand-panel__logo">ANTREANKU</p>
            <p class="brand-panel__title">Ambil nomor antrean tanpa perlu datang lebih dulu.</p>
            <p class="brand-panel__text">Daftar atau masuk, dapatkan nomor, lalu pantau giliran Anda langsung dari ponsel.</p>
        </aside>

        <main class="screen">
            @yield('content')
        </main>
    </div>

    <script>
    (function () {
        var each = Array.prototype.forEach;

        // Kolom NIK: hanya angka
        each.call(document.querySelectorAll('[data-digits-only]'), function (el) {
            el.addEventListener('input', function () { el.value = el.value.replace(/\D+/g, ''); });
        });

        // Cegah klik ganda saat form dikirim
        each.call(document.querySelectorAll('form[data-once]'), function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('button[type="submit"]');
                if (btn) { btn.disabled = true; }
            });
        });
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                each.call(document.querySelectorAll('button[type="submit"]'), function (b) { b.disabled = false; });
            }
        });

        // Popup konfirmasi: Esc = "Bukan saya"
        var modal = document.querySelector('.modal');
        if (modal) {
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { window.location.href = modal.getAttribute('data-close-url'); }
            });
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>
