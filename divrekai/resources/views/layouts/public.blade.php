<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DIVRE KAI')</title>

    {{-- Font Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- AOS ANIMATION --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body class="font-['Montserrat'] bg-white overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3" data-aos="fade-right" data-aos-duration="800">
                <img src="{{ asset('img/logo-kai.png') }}" class="h-20" alt="KAI">
            </div>

            <div class="flex gap-8 font-semibold text-sm text-[#231f5c]"
                 data-aos="fade-left" data-aos-duration="800">
                <a href="{{ route('beranda') }}" class="hover:text-orange-600 transition">
                    BERANDA
                </a>
                <a href="{{ route('input.pendapatan') }}" class="hover:text-orange-600 transition">
                    INPUT PENDAPATAN
                </a>
                <a href="#kontak" class="hover:text-orange-600 transition">
                    KONTAK
                </a>
            </div>
        </div>

        {{-- GARIS BIRU --}}
        <div class="h-2 bg-[#231f5c]"></div>
    </nav>

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- AOS SCRIPT --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 900,
            easing: 'ease-in-out',
            once: true,
            offset: 120,
        });
    </script>

</body>
</html>
