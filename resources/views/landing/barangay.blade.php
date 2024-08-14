<x-guest-layout>
    <x-slot name="title">
        TU-Efficient | Barangay
    </x-slot>

    <x-slot name="favicons">
        <x-b-favicons />
    </x-slot>

    <div class="px-12 py-12 mx-auto max-w-7xl sm:max-h-screen md:px-12 lg:px-24 lg:py-24">
        <div class="justify-center mx-auto text-center bg-green-100 rounded-2xl sm:max-w-2xl sm:w-full">
            <div class="flex flex-col sm:grid items-center justify-center mx-auto shadow-xl sm:grid-cols-2 rounded-2xl">
                <div class="w-full px-6 py-3">
                    <div>
                        <div class="mt-3 text-center sm:mt-5">
                            <div class="items-center w-full">
                                <h3
                                    class="text-4xl sm:text-3xl md:text-4xl font-bold tracking-tight text-green-1000 leading-6 ">
                                    TU-EFFICIENT
                                </h3>
                            </div>
                            <div class="mt-4 text-sm tracking-tight md:text-base text-green-1000">
                                <p>Efficiently manage your workspace</p>
                            </div>
                        </div>
                    </div>
                    {{-- Forms Here --}}
                    <livewire:login.barangay-access />
                </div>
                <div class="order-first flex items-center justify-center w-1/3 h-1/3 sm:h-full sm:w-full p-0 m-0">
                    <img class="drop-shadow-xl py-6 sm:h-3/4 md:h-[85%]" src="{{ asset('assets/b_logo.png') }}"
                        alt="TU-Efficient | Barangay Logo">
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center pt-10 drop-shadow-sm">
            <p class="text-center font text-green-50 text-sm px-2 pb-3">
                In partnership with DOLE. All rights reserved. 2024
            </p>
            <img class="object-contain h-10" src="{{ asset('assets/dole_logo.png') }}" alt="">
        </div>
    </div>
</x-guest-layout>
