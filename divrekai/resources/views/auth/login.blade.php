<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | DIVRE KAI</title>

    {{-- Font Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-['Montserrat'] min-h-screen flex items-center justify-center">

<div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    {{-- LEFT IMAGE --}}
    <div class="hidden md:block">
        <img
            src="{{ asset('img/login-kai.png') }}"
            alt="KAI"
            class="w-full h-full object-cover object-[22%_75%]"
        >
    </div>

    {{-- RIGHT FORM --}}
    <div class="p-6 flex flex-col justify-center">
        <div class="mb-8 text-center">
            <img src="{{ asset('img/logo-kai.png') }}" class="mx-auto mb-2 w-40 md:w-44 h-auto object-contain" alt="KAI Logo">
            <p class="text-sm text-gray-500 mt-1">LOGIN</p>
        </div>

        {{-- SESSION STATUS --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- USERNAME --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Username
                </label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    class="w-full rounded-lg bg-gray-100 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
                @error('username')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg bg-gray-100 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
                @error('password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BUTTON --}}
            <button
                type="submit"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg transition"
            >
                LOGIN
            </button>
        </form>
    </div>

</div>

</body>
</html>
