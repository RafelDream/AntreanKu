<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard petugas') | Antreanku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['"Public Sans"', 'sans-serif'] },
            colors: {
                ink: '#1F2A37', line: '#D8DCE0', muted: '#5B6570', wash: '#F4F6F7',
                brand: { DEFAULT: '#08CE56', deep: '#067A35' },
                warn: '#9A5B00', danger: '#B42318'
            }
        } } }
    </script>
    <style>:focus-visible { outline: 2px solid #1F2A37; outline-offset: 2px; }</style>
</head>
<body class="bg-white text-ink font-sans antialiased min-h-screen">

    @include('crm.dashboard.partials.header')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

</body>
</html>