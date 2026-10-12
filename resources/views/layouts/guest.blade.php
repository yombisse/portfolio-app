<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
            </div>

            <div class="relative w-full max-w-md space-y-8">
                <div class="flex items-center justify-between gap-4">
                    <a href="/" class="flex items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-slate-950">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-accent/30 bg-accent/10 text-lg font-bold text-accent">FY</span>
                        <span>
                            <span class="block text-sm font-semibold tracking-wide text-white">Fandie Yombissé</span>
                            <span class="block text-xs text-slate-400">Portfolio professionnel</span>
                        </span>
                    </a>
                    <a href="/" class="text-sm text-slate-400 hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent rounded">
                        Retour au portfolio
                    </a>
                </div>

                <section class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/80 p-6 shadow-2xl shadow-black/30 backdrop-blur sm:p-8">
                    {{ $slot }}
                </section>

                <p class="text-center text-xs text-slate-500">
                    © {{ date('Y') }} Fandie Yombissé · Accès réservé à l’administration
                </p>
            </div>
        </div>
    </body>
</html>
