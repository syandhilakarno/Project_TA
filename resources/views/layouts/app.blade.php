<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Jika menggunakan Vite + Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tambahan CSS khusus per halaman -->
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans">
    @yield('content')

    <!-- Tambahan JS khusus per halaman -->
    @stack('scripts')
</body>

</html>