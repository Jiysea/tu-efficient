<x-guest-layout>
    <x-slot name="title">
        TU-Efficient | Coordinator
    </x-slot>

    <x-slot name="favicons">
        <x-c-favicons />
    </x-slot>

    <div class="px-12 py-12 mx-auto max-w-7xl max-h-screen md:px-12 lg:px-24 lg:py-24">
        <div class="justify-center mx-auto text-center bg-blue-100 rounded-2xl sm:max-w-2xl sm:w-full">
            <div class="flex flex-col sm:grid items-center justify-center mx-auto shadow-xl sm:grid-cols-2 rounded-2xl">
                <div class="w-full px-6 py-3">
                    <div>
                        <div class="mt-3 text-center sm:mt-5">
                            <div class="items-center w-full">
                                <h3
                                    class="text-4xl sm:text-3xl md:text-4xl font-bold sm:tracking-tight text-blue-1000 leading-6 ">
                                    TU-EFFICIENT
                                </h3>
                            </div>
                            <div class="mt-4 text-sm sm:tracking-tight md:text-base text-blue-1000">
                                <p>Efficiently manage your workspace</p>
                            </div>
                        </div>
                    </div>
                    {{-- Forms Here --}}
                    <form method="POST" action="{{ route('coordinator.login') }}">
                        @csrf
                        <div class="mt-6 space-y-2">
                            <div>
                                <input type="text" name="email" id="email"
                                    class="block w-full px-5 py-2 text-sm text-blue-1000 placeholder-blue-600 transition duration-200 ease-in-out transform border border-transparent rounded-lg bg-blue-200 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-blue-100"
                                    placeholder="Email">
                            </div>
                            <div>
                                <input type="text" name="password" id="password"
                                    class="block w-full px-5 py-2 text-sm text-blue-1000 placeholder-blue-600 transition duration-200 ease-in-out transform border border-transparent rounded-lg bg-blue-200 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-blue-100"
                                    placeholder="Password">
                            </div>
                            <div class="flex flex-col mt-4">
                                <button type="submit"
                                    class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-blue-50 transition duration-200 ease-in-out transform bg-blue-1000 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Sign
                                    In</button>
                                <div class="relative my-4">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-blue-1000"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm sm:text-xs md:text-sm">
                                        <span class="px-2 text-blue-1000 bg-blue-100"> Not a Coordinator? </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-6">
                                    <a wire:navigate.hover href="/"
                                        class="grid col-span-6 gap-2 py-2 mb-4 text-sm font-medium text-center text-indigo-50 transition duration-200 ease-in-out transform bg-indigo-1000 rounded-lg hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Go
                                        Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="order-first flex items-center justify-center w-1/3 h-1/3 sm:h-full sm:w-full p-0 m-0">
                    <img class="drop-shadow-xl py-6 sm:h-3/4 md:h-[85%]" src="{{ asset('assets/c_logo.png') }}"
                        alt="TU-Efficient | Focal Logo">
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center pt-10 drop-shadow-sm">
            <p class="text-center font text-blue-50 text-sm px-2 pb-3">
                In partnership with DOLE. All rights reserved. 2024
            </p>
            <img class="object-contain h-10" src="{{ asset('assets/dole_logo.png') }}" alt="">
        </div>
    </div>
</x-guest-layout>
