<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nebula Store') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased bg-dark-bg text-gray-200">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10">
        <div class="mb-8">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center">
                    <span class="text-white font-heading font-bold text-lg">N</span>
                </div>
                <span class="font-heading font-bold text-xl text-white neon-text">Nebula Store</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md glass-card p-6">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
