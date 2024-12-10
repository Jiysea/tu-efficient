<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $title ?? 'TU-Efficient' }}
    </title>

    {{-- Favicon --}}
    @isset($favicons)
        {{ $favicons }}
    @endisset

    <!-- Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="min-h-screen bg-[#212121]">
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScriptConfig
</body>

</html>
