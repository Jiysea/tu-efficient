<div>
    @if (session()->has('access-code'))
        <div class="absolute bottom-6 left-6 flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 "
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                {{ session('access-code') }}
            </div>
        </div>
    @endif

    <form wire:submit.prevent="checkAccess">
        @csrf
        <div class="mt-6 space-y-2">
            <div class="relative">
                @error('accessCode')
                    <p class="text-red-500 absolute -top-5 z-10 text-xs">Please type a valid access code.</p>
                @enderror
                <input type="text" wire:model.live="accessCode" id="access_code" required
                    class="block w-full px-5 py-2 text-sm text-center text-green-1000 placeholder-green-700 transition duration-200 ease-in-out transform border border-transparent rounded-lg bg-green-200 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-green-100"
                    placeholder="Access Code">
            </div>

            <div class="flex flex-col mt-4">
                <button type="submit"
                    class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-green-50 transition duration-200 ease-in-out transform bg-green-1000 rounded-lg hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Confirm</button>
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-green-1000"></div>
                    </div>
                    <div class="relative flex justify-center text-sm sm:text-xs md:text-sm">
                        <span class="px-2 text-green-1000 bg-green-100"> Not a Barangay? </span>
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
