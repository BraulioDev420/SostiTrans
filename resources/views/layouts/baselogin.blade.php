<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>@yield('title', 'SostiTrans')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/flatly/bootstrap.min.css">
    {{-- Asegúrate de que este asset esté en public si no usas Vite --}}

    @stack('styles')

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    {{-- Contenido principal --}}
    <main class="container py-4">
        @yield('content')
    </main>
</body>
</html>