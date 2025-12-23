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

<body class="font-sans antialiased site-dark">
    <div class="min-h-screen bg-[#0b1220] flex items-center justify-center px-4 py-10">
        {{-- Optional: Logo --}}
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center justify-center">
                    <!-- <x-application-logo class="w-16 h-16 fill-current text-white/70" /> -->
                </a>
            </div>

            {{-- Slot Card (matches your admin look) --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-6 shadow-xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
