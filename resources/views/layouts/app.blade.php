<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Portfolio')</title>

        <script>
            document.documentElement.classList.toggle('theme-light', localStorage.getItem('portfolio-theme') === 'light');
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 text-slate-100 antialiased">
        @include('layouts.navigation')

        <main class="min-h-screen">
            @yield('content')
        </main>
        <footer class="border-t border-accent/20 py-6 mt-8">
            <div class="container flex items-center justify-between">
                <div class="text-sm font-medium">FANDIE YOMBISSÉ — SOFTWARE DEVELOPER</div>
                <div class="text-sm">© 2026 · BUILT WITH CARE</div>
            </div>
        </footer>
    </body>
</html>
