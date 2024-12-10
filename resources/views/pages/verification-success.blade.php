<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>
        Verification Success
    </title>

    <x-f-favicons />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="min-h-screen bg-[#212121]">
        <main>
            <div
                class="relative flex flex-col px-12 py-12 items-center sm:max-h-screen md:px-12 lg:px-24 lg:py-24 select-none">

                {{-- White Box --}}
                <div
                    class="flex justify-center items-center text-center bg-indigo-100 rounded-2xl sm:max-w-2xl sm:w-full">

                    {{-- Contents --}}
                    <div class="relative flex flex-col items-center gap-6 size-full text-indigo-1100 px-6 py-10">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" aria-label="{{ __('Back To Login') }}"
                                class="font-semibold text-xs absolute left-5 top-5 flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                BACK TO LOGIN </button>
                        </form>

                        <img class="drop-shadow sm:drop-shadow-xl w-40" src="{{ asset('assets/f_logo.png') }}"
                            alt="TU-Efficient | Focal Logo">

                        <div class="flex items-center justify-center">
                            <span class="text-lg font-medium">Your email is now verified. You can close this
                                window.</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="relative flex flex-col items-center justify-center pt-10">
                    <p class="text-center font text-indigo-50 text-sm px-2 pb-3">
                        In partnership with DOLE. All rights reserved. 2024
                    </p>
                    <img class="object-contain size-10 drop-shadow" src="{{ asset('assets/dole_logo.png') }}"
                        alt="">
                </div>
            </div>
        </main>
    </div>
</body>

</html>
