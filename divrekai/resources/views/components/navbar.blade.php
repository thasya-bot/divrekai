<nav class="bg-white border-b shadow">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">

            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo-kai.png') }}" class="h-10">
                <span class="font-bold text-blue-900 text-sm">
                    DIVRE I SUMUT
                </span>
            </div>

            {{-- MENU --}}
            <ul class="flex items-center gap-8 text-sm font-semibold text-blue-900">
                <li>
                    <a href="/" class="hover:text-orange-500">
                        BERANDA
                    </a>
                </li>

                @auth
                    @if(auth()->user()->role->username === 'admin_unit')
                        <li>
                            <a href="/pendapatan" class="hover:text-orange-500">
                                INPUT PENDAPATAN
                            </a>
                        </li>
                    @endif
                @endauth

                <li>
                    <a href="/kontak" class="hover:text-orange-500">
                        KONTAK
                    </a>
                </li>
            </ul>

            {{-- LOGIN / LOGOUT --}}
            <div>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-red-600 hover:underline">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="text-sm text-blue-900 hover:underline">
                        Login
                    </a>
                @endauth
            </div>

        </div>
    </div>
</nav>