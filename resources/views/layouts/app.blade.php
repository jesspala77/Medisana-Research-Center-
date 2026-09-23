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
    <body class="font-sans antialiased" style="background: radial-gradient(circle at top, rgba(82,165,255,0.18), transparent 32%), linear-gradient(180deg, #04131d 0%, #071b2b 30%, #0b1f2b 100%); color: #e2e8f0;">
        <div class="min-h-screen">
            <div class="mx-auto max-w-[1600px] px-3 py-3 sm:px-5 lg:px-6">
                <div class="overflow-hidden rounded-[24px] border border-amber-400/20 bg-slate-950/75 shadow-[0_20px_60px_rgba(0,0,0,0.38)] backdrop-blur-sm">
                    @include('layouts.navigation')

                    @isset($header)
                        <header class="border-b border-white/10 bg-white/[0.02]">
                            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="min-h-[calc(100vh-120px)]">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
