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
    <nav class="bg-white border-b-8 border-[#231f5c]">
        <div class="flex items-center justify-between px-10 h-20">

            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo-kai.png') }}" alt="KAI" class="h-20">
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

                {{-- ADMIN PIC --}}
                @if(auth()->user()->role->username === 'admin_pic')
                    <li>
                        <a href="{{ route('admin.pic.unit.index') }}"
                        class="hover:text-orange-500 transition">
                            KELOLA DATA UNIT
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pic.users.index') }}"
                            class="hover:text-orange-500 transition">
                            KELOLA PENGGUNA
                        </a>

                    </li>
                @endif

                {{-- PIMPINAN --}}
                @if(auth()->user()->role->username === 'pimpinan')
                    <li>
                        <a href="{{ route('pimpinan.dashboard') }}"
                        class="hover:text-orange-500 transition">
                            DASHBOARD
                        </a>
                    </li>
                @endif

                {{-- LOGOUT --}}
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:text-red-600 transition">
                            LOGOUT
                        </button>
                    </form>
                </li>

            </ul>
            @endauth
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="pt-0 pb-6">
        @yield('content')
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
