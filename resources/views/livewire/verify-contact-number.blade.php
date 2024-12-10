<x-slot name="favicons">
    <x-f-favicons />
</x-slot>

<div class="relative flex flex-col px-12 py-12 items-center min-h-screen md:px-12 lg:px-24 lg:py-24 select-none">

    {{-- White Box --}}
    <div class="flex justify-center items-center bg-indigo-100 rounded-2xl sm:max-w-xl sm:w-full">

        {{-- Contents --}}
        <div class="relative flex flex-col items-center text-center gap-4 size-full text-indigo-1100 px-6 py-10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" aria-label="{{ __('Back To Login') }}"
                    class="font-semibold text-xs absolute left-5 top-5 flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                    BACK TO LOGIN </button>
            </form>

            <img class="drop-shadow sm:drop-shadow-xl w-40" src="{{ asset('assets/f_logo.png') }}"
                alt="TU-Efficient | Focal Logo">

            <div class="flex flex-col justify-center">
                <span class="text-lg font-medium">A verification code is sent to <strong
                        class="text-indigo-700">{{ $this->maskedContact }}</strong></span>
                <span class="text-sm">Please check your messages and enter the verification code.</span>
            </div>

            {{-- Resend --}}
            <div class="text-xs font-medium text-gray-600 relative flex flex-1 items-center justify-center mb-2">
                <span class="relative flex items-center justify-center">Didn't receive any text?
                    {{-- Resend Button --}}
                    <button type="button" wire:click="sendVerificationCode"
                        class="flex items-center justify-center p-1 font-semibold text-center duration-200 ease-in-out rounded-md outline-none underline-offset-4 hover:underline text-red-700 decoration-red-800">
                        Resend Code
                    </button>
                    {{-- Loading State --}}
                    <svg class="ms-2 absolute left-full text-indigo-900 size-4 animate-spin" wire:loading
                        wire:target="sendVerificationCode" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </div>

            <!-- Code input for verification -->
            <form wire:submit.prevent="verifyCode"
                class="relative flex flex-1 flex-col items-center justify-center w-full gap-2">
                @error('verification_code')
                    <span class="text-red-500 absolute bottom-full mb-1 left-1 z-10 text-xs">{{ $message }}</span>
                @enderror
                <input type="text" id="verification_code" wire:model.blur="verification_code" autocomplete="off"
                    autofocus
                    class="text-sm duration-200 ease-in-out border rounded-lg outline-none flex w-full px-5 py-2 
                    {{ $errors->has('verification_code')
                        ? 'bg-red-200 border-red-500 focus:ring-red-500 focus:border-red-600 text-red-900 placeholder-red-600'
                        : 'bg-indigo-200 border-indigo-500 focus:ring-indigo-500 focus:border-indigo-600 text-indigo-1000 placeholder-indigo-600' }}"
                    placeholder="Verification Code">

                <!-- Button to verify the code -->
                <span class="relative flex flex-1 w-full">
                    <button type="submit"
                        class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-indigo-50 duration-200 ease-in-out bg-indigo-700 rounded-lg hover:bg-indigo-800 active:bg-indigo-900 outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">

                        <p wire:loading.remove wire:target="verifyCode">Verify</p>

                        {{-- Loading State --}}
                        <svg class="text-indigo-50 size-5 animate-spin" wire:loading wire:target="verifyCode"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                    </button>
                </span>
            </form>
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
    <div x-data="{ alerts: $wire.entangle('alerts') }"
        x-effect="
        if (Array.isArray(alerts) && alerts.length > 0) {
            alerts.forEach(alert => {
                setTimeout(() => {
                    $wire.removeAlert(alert.id);
                }, 3000);
            });
        }"
        class="fixed left-6 bottom-6 z-50 flex flex-col space-y-4">
        {{-- Loop through alerts --}}
        <template x-for="alert in alerts" :key="alert.id">
            <div x-show="show" x-data="{ show: false }" x-init="$nextTick(() => { show = true });"
                x-transition:enter="transition ease-in-out duration-300 origin-left"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="flex items-center border rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
                :class="{
                    'bg-indigo-200 text-indigo-1000 border-indigo-500': alert.color === 'indigo',
                    'bg-red-200 text-red-900 border-red-500': alert.color === 'red',
                }"
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
