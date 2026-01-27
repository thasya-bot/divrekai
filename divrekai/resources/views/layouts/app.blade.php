<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'KAI DIVRE I SUMUT')</title>

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')
</head>
<body class="font-sans bg-gray-100">

    {{-- NAVBAR --}}
    <nav class="flex items-center justify-between px-10 py-0 h-20 border-b bg-white">
        <div class="flex items-center gap-2">
            <img src="{{ asset('img/logo-kai.png') }}" class="h-28">
        </div>

        @auth
        <ul class="flex gap-8 text-[#231f5c] font-semibold items-center">
            @if(auth()->user()->role->username === 'admin_pic')
                <li><a href="/dashboard-admin">Dashboard Admin</a></li>
            @endif

            @if(auth()->user()->role->username === 'admin_unit')
                <li><a href="{{route('pendapatan.index')}}">LAPORAN</a></li>
            @endif

            @if(auth()->user()->role->username === 'admin_unit')
                <li><a href="{{route('pendapatan.input')}}">INPUT PENDAPATAN</a></li>
            @endif

            @if(auth()->user()->role->username === 'pimpinan')
                <li><a href="/dashboard-pimpinan">Dashboard Pimpinan</a></li>
            @endif

            <li>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-red-600">LOGOUT</button>
                </form>
            </li>
        </ul>
        @endauth
    </nav>
  {{-- GARIS BIRU --}}
    <div class="h-3 bg-[#231f5c]"></div>

    {{-- CONTENT --}}
    <main>
        @yield('content')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </body>
    </main>

</body>
</html>