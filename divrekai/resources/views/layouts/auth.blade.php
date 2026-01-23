<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Login | DIVRE KAI')</title>

    {{-- Font Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind / Vite --}}
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gray-100 font-['Montserrat'] flex items-center justify-center">

    {{-- CONTENT --}}
    @yield('content')

</body>
</html>
