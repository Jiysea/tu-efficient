<div>
    @if (session()->has('email'))
        @foreach (session('email') as $message)
            <div x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeSuccessMessage('email', '{{ $loop->index }}');
            }, 2000)" x-show="show" x-transition:enter="fade-enter"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="fade-leave-active" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed left-6 bottom-6 flex items-center bg-red-300 text-red-900 rounded-lg text-sm sm:text-md font-bold px-4 py-3"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>
                <p>{{ $message }}</p>
            </div>
        @endforeach
    @endif

    <form wire:submit.prevent="login">
        @csrf
        <div class="mt-6 space-y-2">
            <div class="relative">
                @error('email')
                    <p class="text-red-500 absolute -top-5 z-10 text-xs">Please type a valid email.</p>
                @enderror
                @error('password')
                    <p class="text-red-500 absolute -top-5 z-10 text-xs">Please type a valid password.</p>
                @enderror
                <input type="text" wire:model.live="email" id="email" required autocomplete="off"
                    class="{{ $errors->has('email') ? 'border-red-500 border-2 bg-red-200 focus:ring-red-500 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} block w-full px-5 py-2 text-sm text-indigo-900 placeholder-indigo-600 transition duration-200 ease-in-out transform border border-transparent rounded-lg bg-indigo-200 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-indigo-100"
                    placeholder="Email">
            </div>
            <div class="relative">
                <input type="password" wire:model.live="password" id="password" required
                    class="{{ $errors->has('password') ? 'border-red-500 border-2 bg-red-200 focus:ring-red-500 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} block w-full px-5 py-2 text-sm text-indigo-900 placeholder-indigo-600 transition duration-200 ease-in-out transform border border-transparent rounded-lg bg-indigo-200 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-indigo-100"
                    placeholder="Password">

            </div>
            <div class="flex flex-col mt-4">
                <button type="submit"
                    class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-indigo-50 transition duration-200 ease-in-out transform bg-indigo-900 rounded-lg hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Sign
                    In</button>
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-indigo-900"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 text-indigo-900 bg-indigo-100"> access to </span>
                    </div>
                </div>
                <div class="flex w-full items-center justify-center">
                    <a wire:navigate.hover href="{{ route('barangay') }}"
                        class="py-2 w-full mb-4 text-sm font-medium text-center text-green-50 transition duration-200 ease-in-out transform bg-green-1000 rounded-lg hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Barangay</a>
                </div>
            </div>
        </div>
    </form>
</div>
