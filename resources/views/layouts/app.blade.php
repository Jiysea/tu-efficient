<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $title ?? 'TU-Efficient' }}
    </title>

    <!-- Fonts -->
    {{-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:100,200,300,400,500,600,700,800,900" rel="stylesheet" /> --}}

    {{-- Favicon --}}
    @isset($favicons)
        {{ $favicons ?? '' }}
    @endisset

    <!-- Scripts -->
    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="min-h-screen bg-[#E6E8EC]">
        {{-- @include('layouts.navigation') --}}
        @isset($sidebar)
            {{ $sidebar }}
        @endisset

        <!-- Page Heading -->
        @isset($header)
            <header class="">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>

</html>
