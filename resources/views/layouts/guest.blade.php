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
    <body class="font-sans antialiased" style="background: radial-gradient(circle at top, rgba(82,165,255,0.18), transparent 28%), linear-gradient(180deg, #04131d 0%, #0a1d2c 50%, #071a27 100%); color: #e5edf3;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/">
                    <x-application-logo class="w-auto h-20" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-0 px-6 py-6 bg-slate-900/80 border border-amber-400/20 shadow-[0_18px_50px_rgba(0,0,0,0.35)] overflow-hidden sm:rounded-2xl backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
