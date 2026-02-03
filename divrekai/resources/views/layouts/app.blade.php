<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'KAI DIVRE I SUMUT')</title>

    {{-- TAILWIND + JS VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- DATATABLES CSS --}}
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body class="font-sans bg-gray-100">

    {{-- NAVBAR --}}
    <nav class="flex items-center justify-between px-10 py-0 h-20 border-b bg-white">
        <div class="flex items-center gap-2">
            <img src="{{ asset('img/logo-kai.png') }}" class="h-28">
        </div>

            {{-- MENU --}}
            @auth
            <ul class="flex items-center gap-10 text-sm font-semibold text-[#231f5c]">

                {{-- ADMIN UNIT --}}
                @if(auth()->user()->role->username === 'admin_unit')
                    <li>
                        <a href="{{ route('pendapatan.index') }}"
                        class="hover:text-orange-500 transition">
                            LAPORAN
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pendapatan.input') }}"
                        class="hover:text-orange-500 transition">
                            INPUT PENDAPATAN
                        </a>
                    </li>
                @endif
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
                <li><a href="/pimpinan/beranda">BERANDA</a></li>
            @endif
            @if(auth()->user()->role->username === 'pimpinan')
            <li class="relative group">

                {{-- BUTTON --}}
                <button class="flex items-center gap-1 hover:text-orange-600">
                    LAPORAN UNIT
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- DROPDOWN --}}
                <ul
                    class="absolute left-0 mt-3 w-56 bg-white border rounded shadow-lg
                        max-h-40 overflow-y-auto
                        opacity-0 invisible group-hover:opacity-100 group-hover:visible
                        transition-all duration-200 z-50">

                    {{-- SEMUA UNIT --}}
                    <li class="border-b sticky top-0 bg-white z-10">
                        <a href="{{ route('pimpinan.beranda') }}"
                        class="block px-4 py-2 hover:bg-gray-100 font-semibold">
                            Semua Unit
                        </a>
                    </li>

                    {{-- LIST UNIT --}}
                    @foreach($navbarUnits as $unit)
                        <li>
                            <a href="{{ route('pimpinan.beranda', ['unit_id' => $unit->id]) }}"
                            class="block px-4 py-2 hover:bg-gray-100">
                                {{ $unit->nama_unit }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
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
    <main class="pt-0 pb-6">
        @yield('content')
      
    </body>
    </main>

    {{-- JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        timer: 1800,
        width: 250,
        showConfirmButton: false,
        customClass: {
            popup: 'rounded-lg px-3 py-2',
            title: 'text-sm font-semibold text-[#231f5c]',
            htmlContainer: 'text-xs text-gray-600'
        }
    });
    </script>
    @endif

    {{-- DATATABLES JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
