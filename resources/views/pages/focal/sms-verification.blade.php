<x-guest-layout>
    <x-slot name="title">
        Focal Verification
    </x-slot>

    <x-slot name="favicons">
        <x-f-favicons />
    </x-slot>

    <div class="px-12 py-12 mx-auto max-w-7xl md:px-12 lg:px-24 lg:py-24">
        <div class="justify-center mx-auto text-center bg-indigo-100 rounded-2xl max-w-2xl w-full">
            <div class="relative flex flex-col items-center justify-center mx-auto shadow-xl rounded-2xl">
                {{-- Absolute Image --}}
                <img class="absolute h-[85%] opacity-10" src="{{ asset('assets/f_logo.png') }}"
                    alt="TU-Efficient | Focal Logo">
                <div class="relative z-10 w-3/4 sm:w-1/2 mt-4 mb-40">
                    <div class="text-center mt-14 text-sm sm:tracking-tight md:text-base text-indigo-1000">
                        <p>Sent SMS verification to +639*****2132</p>
                    </div>
                    {{-- Forms Here --}}
                    <div class="mt-6 space-y-2">
                        <div>
                            <input type="text" name="otp" id="otp"
                                class="block w-full px-5 py-2 text-center text-sm text-indigo-1000 placeholder-indigo-700 transition duration-200 ease-in-out transform border border-transparent rounded-lg bg-indigo-200 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-indigo-100"
                                placeholder="Enter OTP here">
                        </div>

                        <div class="flex flex-col mt-4">
                            <a href="{{ route('focal.dashboard') }}"
                                class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-indigo-50 transition duration-200 ease-in-out transform bg-indigo-1000 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Verify</a>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center pt-10 drop-shadow-sm">
            <p class="text-center font text-indigo-50 text-sm px-2 pb-3">
                In partnership with DOLE. All rights reserved. 2024
            </p>
            <img class="object-contain h-10" src="{{ asset('assets/dole_logo.png') }}" alt="">
        </div>
    </div>
</x-guest-layout>
