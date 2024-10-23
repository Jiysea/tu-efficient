<x-slot name="favicons">
    <x-f-favicons />
</x-slot>

<div class="relative flex flex-col px-12 py-12 items-center sm:max-h-screen md:px-12 lg:px-24 lg:py-24 select-none">

    {{-- White Box --}}
    <div class="flex justify-center items-center text-center bg-indigo-100 rounded-2xl sm:max-w-2xl sm:w-full">

        {{-- Contents --}}
        <div class="flex flex-col items-center gap-4 size-full px-6 py-10">

            <span>Please verify your account</span>

            <!-- Code input for verification -->
            <div class="relative flex flex-1 items-center w-full justify-center gap-2">
                <input type="text" id="verification_code" wire:model.blur="verification_code"
                    class="text-sm duration-200 ease-in-out border rounded-lg outline-none flex flex-1 px-5 py-2 
                    {{ $errors->has('verification_code')
                        ? 'bg-red-200 border-red-500 focus:ring-red-500 focus:border-red-600 text-red-900 placeholder-red-600'
                        : 'bg-indigo-200 border-indigo-500 focus:ring-indigo-500 focus:border-indigo-600 text-indigo-1000 placeholder-indigo-600' }}"
                    placeholder="Verification Code">
                @error('verification_code')
                    <span class="text-red-500 absolute bottom-0 left-1 z-10 text-xs">{{ $message }}</span>
                @enderror

                <!-- Button to send verification code -->
                <button type="button"
                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-center duration-200 ease-in-out rounded-lg border border-indigo-700 hover:border-transparent active:border-transparent focus:border-transparent text-indigo-700 hover:text-indigo-50 active:text-indigo-50 focus:text-indigo-50 hover:bg-indigo-800 active:bg-indigo-900 focus:bg-indigo-900 outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Resend
                    Code
                </button>
            </div>

            <!-- Button to verify the code -->
            <span class="flex flex-1 w-full">
                <button type="button"
                    class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-indigo-50 duration-200 ease-in-out bg-indigo-700 rounded-lg hover:bg-indigo-800 active:bg-indigo-900 outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Verify
                </button>
            </span>
        </div>

    </div>

    {{-- Footer --}}
    <div class="relative flex flex-col items-center justify-center pt-10">
        <p class="text-center font text-indigo-50 text-sm px-2 pb-3">
            In partnership with DOLE. All rights reserved. 2024
        </p>
        <img class="object-contain size-10 drop-shadow" src="{{ asset('assets/dole_logo.png') }}" alt="">
    </div>

    {{-- Alert Notifications --}}
    <div x-data="{
        alerts: $wire.entangle('alerts'),
        init() {
            window.addEventListener('show-alert', () => {
                this.alerts.forEach(alert => {
                    setTimeout(() => {
                        this.removeAlert(alert.id);
                    }, 3000);
                });
            });
        },
        removeAlert(id) {
            $wire.removeAlert(id);
        },
    }" class="fixed left-6 bottom-6 z-50 flex flex-col space-y-4">
        {{-- Loop through alerts --}}
        <template x-for="alert in alerts" :key="alert.id">
            <div x-show="true" x-transition:enter="transition ease-in-out duration-300 origin-left"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="origin-left transition ease-in-out duration-500"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                class="flex items-center bg-indigo-200 text-indigo-1000 border border-indigo-500 rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>
                <p x-text="alert.message"></p>
            </div>
        </template>
    </div>
</div>
