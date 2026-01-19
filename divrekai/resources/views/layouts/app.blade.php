<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('KAI DIVRE I SUMUT')</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans">

    {{-- NAVBAR --}}
    <nav class="flex items-center justify-between px-10 py-4 border-b">
        <div class="flex items-center gap-2">
            <img src="{{ asset('img/logo-kai.png') }}" class="h-10">
            <span class="font-bold text-blue-900">DIVRE I SUMUT</span>
        </div>
        <ul class="flex gap-8 text-blue-900 font-semibold">
            <li><a href="/" class="hover:text-orange-500">BERANDA</a></li>
            <li><a href="/pendapatan">INPUT PENDAPATAN</a></li>
            <li><a href="/kontak">KONTAK</a></li>
        </ul>
    </nav>

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

</body>
</html>