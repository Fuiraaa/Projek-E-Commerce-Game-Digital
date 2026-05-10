<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Neboostla') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-body antialiased bg-dark-bg text-gray-200 scrollbar-hide">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10">
        <div class="mb-8 flex justify-center">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Neboostla Logo" class="w-12 h-12 drop-shadow-[0_0_10px_rgba(0,240,255,0.5)]">
                <span class="font-heading font-bold text-xl text-white neon-text">Neboostla</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md glass-card p-6">
            {{ $slot }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.2/lib/anime.min.js"></script>
    <script src="{{ asset('js/page-transition.js') }}"></script>
</body>
</html>
