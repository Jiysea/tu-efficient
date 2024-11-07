<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{
    change_password: $wire.entangle('change_password'),
}" class="flex justify-center items-center min-h-screen">
    <div class="relative flex flex-col text-indigo-50">

        {{-- Navigation --}}
        <nav
            class="hidden lg:w-6ms-64 lg:flex fixed left-0 top-0 flex-col items-start overflow-y-auto  scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700 py-10 px-5">
            <span class="flex items-center justify-center mb-4">
                <a href="{{ route('focal.dashboard') }}"
                    class="flex items-center justify-center text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 rounded p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="400" viewBox="0, 0, 400,400">
                        <g>
                            <path
                                d="M90.363 104.248 C 86.973 106.080,4.008 188.228,1.874 191.866 C -0.758 196.350,-0.758 203.647,1.872 208.134 C 5.431 214.206,89.211 295.904,92.513 296.524 C 103.786 298.639,113.281 291.747,113.281 281.450 C 113.281 274.922,112.132 273.517,82.593 243.945 L 54.305 215.625 221.810 215.625 L 389.316 215.625 392.684 213.651 C 402.632 207.821,402.632 192.179,392.684 186.349 L 389.316 184.375 221.810 184.375 L 54.305 184.375 82.593 156.055 C 112.132 126.483,113.281 125.078,113.281 118.550 C 113.281 107.627,99.742 99.178,90.363 104.248 "
                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                        </g>
                    </svg>
                </a>
                <h1 class="whitespace-nowrap font-bold ms-3 text-xl">Personal Settings</h1>
            </span>
            <ol class="text-lg font-bold">
                <li class="mt-1">
                    <a href="#general">
                        General
                    </a>
                    <ol class="text-gray-500 text-xs font-normal ms-6 mt-2 mb-6">
                        <li class="mt-1">
                            <a href="#email">
                                Personal Information
                            </a>
                        </li>
                    </ol>
                    <a href="#technical">
                        Technical
                    </a>
                    <ol class="text-gray-500 text-xs font-normal ms-6 mt-2 mb-6">
                        <li class="mt-1">
                            <a href="#minimum_wage">
                                Minimum Wage
                            </a>
                        </li>
                        <li class="mt-1">
                            <a href="#duplication_threshold">
                                Duplication Threshold
                            </a>
                        </li>
                        <li class="mt-1">
                            <a href="#project_number_prefix">
                                Project Number Prefix
                            </a>
                        </li>
                        <li class="mt-1">
                            <a href="#batch_number_prefix">
                                Batch Number Prefix
                            </a>
                        </li>
                        <li class="mt-1">
                            <a href="#maximum_income">
                                Maximum Income
                            </a>
                        </li>
                    </ol>
                </li>
            </ol>
        </nav>

        {{-- Body --}}
        <div class="lg:ms-64 p-10 relative flex flex-col gap-10 text-sm">

            {{-- # General --}}
            <div class="relative w-full flex flex-col">
                <h1 id="general" class="flex gap-3 font-semibold text-3xl mb-10">
                    <span class="font-normal text-indigo-700 text-3xl">#</span>
                    General
                </h1>

                <div class="flex flex-col size-full text-sm px-5">
                    <span class="flex items-center gap-2 font-medium text-indigo-300 p-2">Personal
                        Information</span>
                    <div class="flex flex-col gap-2 p-5 bg-zinc-800 rounded-md">

                        {{-- Full Name --}}
                        <div id="full_name"
                            class="flex items-center justify-between mb-4 rounded text-xl font-semibold text-zinc-50">
                            {{ $this->full_name(auth()->user()) }}
                            <span
                                class="capitalize text-sm font-semibold py-1 px-3 rounded text-center bg-[#212121] text-indigo-700 select-none">
                                {{ auth()->user()->user_type }}
                            </span>
                        </div>

                        {{-- Email & Contact Number --}}
                        <div class="flex flex-col md:flex-row gap-2 justify-between">

                            {{-- Email --}}
                            <span id="email" class="flex items-center justify-between gap-4">
                                <h1 class="font-medium text-indigo-300">Email Address</h1>

                                <span
                                    class="flex items-center gap-2 text-zinc-500 selection:bg-indigo-900 selection:text-indigo-50 outline-none text-xs">
                                    {{ auth()->user()->email }}
                                    <span class="flex items-center justify-center">
                                        @if (auth()->user()->isEmailVerified())
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-green-700"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M190.234 1.053 C 183.006 3.443,179.748 6.015,162.891 22.637 C 137.131 48.038,136.175 48.438,101.156 48.438 C 71.648 48.438,66.707 49.653,57.762 59.111 C 49.525 67.820,48.437 72.809,48.437 101.880 C 48.438 136.116,47.952 137.261,22.557 162.961 C 1.288 184.486,0.391 185.985,0.391 200.000 C 0.391 213.983,1.272 215.457,22.513 237.010 C 47.993 262.863,48.438 263.925,48.438 298.909 C 48.438 328.249,49.672 333.312,58.975 342.110 C 67.946 350.594,72.232 351.562,100.798 351.562 C 136.076 351.563,137.208 352.034,162.990 377.469 C 184.541 398.730,186.011 399.609,200.000 399.609 C 213.989 399.609,215.459 398.730,237.010 377.469 C 262.792 352.034,263.924 351.562,299.202 351.563 C 327.768 351.563,332.054 350.594,341.025 342.110 C 350.306 333.332,351.563 328.215,351.563 299.202 C 351.563 263.924,352.034 262.792,377.469 237.010 C 398.730 215.459,399.609 213.989,399.609 200.000 C 399.609 186.011,398.730 184.541,377.469 162.990 C 352.034 137.208,351.563 136.076,351.563 100.798 C 351.563 71.785,350.306 66.668,341.025 57.890 C 332.054 49.406,327.768 48.438,299.202 48.437 C 263.924 48.437,262.792 47.966,237.010 22.531 C 216.042 1.845,213.983 0.563,201.172 0.226 C 196.248 0.096,192.204 0.402,190.234 1.053 M236.530 211.517 L 173.833 274.217 137.307 237.698 L 100.781 201.178 112.109 189.851 L 123.436 178.524 148.439 203.517 L 173.441 228.510 224.802 177.150 L 276.162 125.790 287.694 137.303 L 299.227 148.817 236.530 211.517 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-red-600"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @endif
                                    </span>
                                </span>
                            </span>

                            {{-- Mobile/Contact Number --}}
                            <span id="contact_num" class="flex items-center justify-between gap-4">
                                <h1 class="font-medium text-indigo-300">Contact Number</h1>

                                <span
                                    class="flex items-center gap-2 text-zinc-500 selection:bg-indigo-900 selection:text-indigo-50 outline-none text-xs">
                                    {{ auth()->user()->contact_num }}
                                    <span class="flex items-center justify-center">
                                        @if (auth()->user()->isMobileVerified())
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-green-700"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M190.234 1.053 C 183.006 3.443,179.748 6.015,162.891 22.637 C 137.131 48.038,136.175 48.438,101.156 48.438 C 71.648 48.438,66.707 49.653,57.762 59.111 C 49.525 67.820,48.437 72.809,48.437 101.880 C 48.438 136.116,47.952 137.261,22.557 162.961 C 1.288 184.486,0.391 185.985,0.391 200.000 C 0.391 213.983,1.272 215.457,22.513 237.010 C 47.993 262.863,48.438 263.925,48.438 298.909 C 48.438 328.249,49.672 333.312,58.975 342.110 C 67.946 350.594,72.232 351.562,100.798 351.562 C 136.076 351.563,137.208 352.034,162.990 377.469 C 184.541 398.730,186.011 399.609,200.000 399.609 C 213.989 399.609,215.459 398.730,237.010 377.469 C 262.792 352.034,263.924 351.562,299.202 351.563 C 327.768 351.563,332.054 350.594,341.025 342.110 C 350.306 333.332,351.563 328.215,351.563 299.202 C 351.563 263.924,352.034 262.792,377.469 237.010 C 398.730 215.459,399.609 213.989,399.609 200.000 C 399.609 186.011,398.730 184.541,377.469 162.990 C 352.034 137.208,351.563 136.076,351.563 100.798 C 351.563 71.785,350.306 66.668,341.025 57.890 C 332.054 49.406,327.768 48.438,299.202 48.437 C 263.924 48.437,262.792 47.966,237.010 22.531 C 216.042 1.845,213.983 0.563,201.172 0.226 C 196.248 0.096,192.204 0.402,190.234 1.053 M236.530 211.517 L 173.833 274.217 137.307 237.698 L 100.781 201.178 112.109 189.851 L 123.436 178.524 148.439 203.517 L 173.441 228.510 224.802 177.150 L 276.162 125.790 287.694 137.303 L 299.227 148.817 236.530 211.517 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-red-600"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @endif
                                    </span>
                                </span>
                            </span>
                        </div>

                        {{-- Offices --}}
                        <div class="flex flex-col md:flex-row gap-2 justify-between">

                            {{-- Regional Office --}}
                            <span id="regional_office" class="flex items-center justify-between gap-4">
                                <h1 class="font-medium text-indigo-300">Regional Office</h1>

                                <span
                                    class="flex items-center justify-between text-zinc-500 selection:bg-indigo-900 selection:text-indigo-50 outline-none text-xs">
                                    {{ auth()->user()->regional_office }}
                                </span>
                            </span>

                            {{-- Field Office --}}
                            <span id="field_office" class="flex items-center justify-between gap-4">
                                <h1 class="font-medium text-indigo-300">Field Office</h1>

                                <span
                                    class="flex items-center justify-between text-zinc-500 selection:bg-indigo-900 selection:text-indigo-50 outline-none text-xs">
                                    {{ auth()->user()->field_office }}
                                </span>
                            </span>
                        </div>

                        {{-- Change Password --}}
                        <div class="flex flex-1 gap-2 w-full">
                            <button x-show="!change_password" type="button"
                                @click="change_password = !change_password;"
                                class="text-center px-3 py-1 rounded font-bold text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900">
                                Change Password
                            </button>

                            <div x-cloak x-show="change_password"
                                class="flex flex-1 flex-col md:flex-row items-center justify-center gap-6 md:gap-2 bg-[#1A1A1C] m-2 px-2 py-6 md:pt-2 md:pb-6 rounded-lg">

                                {{-- Password --}}
                                <span class="relative flex flex-col gap-1">
                                    <label for="password" class="text-indigo-300 text-sm font-medium">New
                                        Password</label>
                                    <input type="password" autocomplete="off" id="password"
                                        wire:model.blur="password"
                                        class="{{ $errors->has('password') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-[#212121] placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none px-3 py-1 text-sm duration-200 ease-in-out rounded"
                                        placeholder="Type new password">
                                    @error('password')
                                        <p class="absolute top-full left-0 mt-1 text-red-500 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </span>

                                {{-- Confirm --}}
                                <span class="relative flex items-end gap-2">
                                    <span class="relative flex flex-col gap-1">
                                        <label for="password_confirmation"
                                            class="text-indigo-300 text-sm font-medium">Confirm
                                            Password</label>
                                        <input type="password" autocomplete="off" id="password_confirmation"
                                            wire:model.blur="password_confirmation"
                                            class="{{ $errors->has('password_confirmation') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-[#212121] placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none px-3 py-1 text-sm duration-200 ease-in-out rounded"
                                            placeholder="Repeat the password">
                                        @error('password_confirmation')
                                            <p class="absolute top-full left-0 mt-1 text-red-500 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </span>
                                </span>

                                {{-- Buttons --}}
                                <span class="relative flex h-full items-end gap-2">
                                    <button type="button" wire:click="changePassword"
                                        class="text-center duration-200 ease-in-out w-20 py-1 rounded font-semibold text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900">
                                        <p wire:loading.remove wire:target="changePassword">Save</p>

                                        {{-- Loading State --}}
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-4 text-indigo-50 animate-spin" wire:loading
                                            wire:target="changePassword" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="resetPassword"
                                        class="text-center duration-200 ease-in-out w-20 py-1 rounded font-semibold text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900">
                                        <p wire:loading.remove wire:target="resetPassword">Cancel</p>

                                        {{-- Loading State --}}
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-4 text-indigo-50 animate-spin" wire:loading
                                            wire:target="resetPassword" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- # Technical --}}
            <div class="relative w-full flex flex-col">

                <h1 id="technical" class="flex gap-3 font-semibold text-3xl mb-10">
                    <span class="font-normal text-indigo-700 text-3xl">#</span>
                    Technical
                </h1>

                <div class="flex flex-col gap-10 size-full text-sm px-5">

                    {{-- Minimum Wage --}}
                    <span id="minimum_wage" class="relative flex items-center justify-between gap-10 w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Minimum Wage</h1>
                            <p class="text-xs text-zinc-500">It's the default minimum wage for calculating the total
                                slots on implementation projects.</p>
                        </span>

                        <div class="relative">
                            <span class="relative">
                                <span
                                    class="absolute left-0 text-xs md:text-sm px-3 py-1.5 rounded-l pointer-events-none {{ $errors->has('minimum_wage') ? 'bg-[#2D1E20] text-red-500' : 'bg-zinc-900 text-zinc-500' }} select-none">
                                    ₱
                                </span>
                                <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric"
                                    min="0" autocomplete="off" id="minimum_wage"
                                    wire:model.live.debounce.500ms="minimum_wage"
                                    class="{{ $errors->has('minimum_wage') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-40 md:w-56 text-xs md:text-sm ps-11 pe-3 py-1.5 duration-200 ease-in-out rounded"
                                    placeholder="0.00">
                            </span>
                            @error('minimum_wage')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror

                            {{-- State Indicator --}}
                            <div x-data="{ show: false, timeoutId: null }"
                                @minimum-wage-save.window="
                                    show = true;
                                    clearTimeout(timeoutId);
                                    timeoutId = setTimeout(() => show = false, 3000);"
                                class="absolute left-full top-0 flex items-center gap-2 h-full ms-2">

                                {{-- Loading Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class=" size-4 text-indigo-50 animate-spin"
                                    wire:loading wire:target="minimum_wage" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Indicator --}}
                                <span x-cloak x-show="show" x-transition.opacity
                                    class="flex items-center gap-2 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M362.500 56.340 C 352.317 58.043,357.949 52.810,246.679 163.959 L 143.749 266.778 96.679 219.844 C 44.257 167.573,46.207 169.193,34.480 168.209 C 8.309 166.015,-9.487 195.204,4.658 217.122 C 9.282 224.286,124.867 338.751,129.688 340.939 C 139.095 345.209,148.860 345.099,158.506 340.613 C 166.723 336.791,393.119 110.272,397.035 101.953 C 408.174 78.291,388.288 52.026,362.500 56.340 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </span>

                    {{-- Duplication Threshold --}}
                    <span id="duplication_threshold" class="flex items-center justify-between gap-10 w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Duplication Threshold</h1>
                            <p class="text-xs text-zinc-500">It's the default threshold for the similarity percentage
                                of
                                duplicates.</p>
                        </span>

                        <div class="relative">
                            <span class="relative">
                                <input type="text" inputmode="numeric" min="0" max="100"
                                    autocomplete="off" id="duplication_threshold"
                                    wire:model.live.debounce.500ms="duplication_threshold"
                                    class="{{ $errors->has('duplication_threshold') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} text-right border-none focus:ring-0 outline-none w-40 md:w-56 text-xs md:text-sm ps-3 pe-11 py-1.5 duration-200 ease-in-out rounded"
                                    placeholder="1-100">
                                <span
                                    class="absolute right-0 text-xs md:text-sm px-3 py-1.5 rounded-r pointer-events-none {{ $errors->has('duplication_threshold') ? 'bg-[#2D1E20] text-red-500' : 'bg-zinc-900 text-zinc-500' }} select-none">
                                    %
                                </span>
                            </span>
                            @error('duplication_threshold')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror

                            {{-- State Indicator --}}
                            <div x-data="{ show: false, timeoutId: null }"
                                @duplication-threshold-save.window="
                                    show = true;
                                    clearTimeout(timeoutId);
                                    timeoutId = setTimeout(() => show = false, 3000);"
                                class="absolute left-full top-0 flex items-center gap-2 h-full ms-2">

                                {{-- Loading Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class=" size-4 text-indigo-50 animate-spin"
                                    wire:loading wire:target="duplication_threshold" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Indicator --}}
                                <span x-cloak x-show="show" x-transition.opacity
                                    class="flex items-center gap-2 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M362.500 56.340 C 352.317 58.043,357.949 52.810,246.679 163.959 L 143.749 266.778 96.679 219.844 C 44.257 167.573,46.207 169.193,34.480 168.209 C 8.309 166.015,-9.487 195.204,4.658 217.122 C 9.282 224.286,124.867 338.751,129.688 340.939 C 139.095 345.209,148.860 345.099,158.506 340.613 C 166.723 336.791,393.119 110.272,397.035 101.953 C 408.174 78.291,388.288 52.026,362.500 56.340 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </span>

                    {{-- Project Number Prefix --}}
                    <span id="project_number_prefix" class="flex items-center justify-between gap-10 w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Project Number Prefix</h1>
                            <p class="text-xs text-red-300">This will affect all of the existing projects.</p>
                        </span>

                        <div class="relative">
                            <input type="text" autocomplete="off" id="project_number_prefix"
                                wire:model.blur="project_number_prefix"
                                class="{{ $errors->has('project_number_prefix') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-40 md:w-56 text-xs md:text-sm ps-3 pe-11 py-1.5 duration-200 ease-in-out rounded"
                                placeholder="Type prefix">

                            <button type="button" wire:click="saveProject" wire:loading.attr="disabled"
                                @if ($this->settings->get('project_number_prefix', config('settings.project_number_prefix')) === $project_number_prefix) disabled @endif
                                class="absolute flex items-center justify-center top-0 right-0 p-2 rounded duration-200 ease-in-out {{ $this->settings->get('project_number_prefix', config('settings.project_number_prefix')) !== $project_number_prefix ? 'text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900' : 'bg-zinc-900 text-zinc-500' }}">

                                {{-- Loading State --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 md:size-4 animate-spin"
                                    wire:loading wire:target="saveProject" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 md:size-4" wire:loading.remove
                                    wire:target="saveProject" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M38.095 14.017 C 26.775 17.636,17.071 27.593,13.739 39.009 C 11.642 46.193,11.873 354.876,13.980 361.648 C 19.802 380.355,32.269 387.500,59.086 387.500 L 74.919 387.500 75.172 328.320 C 75.419 270.395,75.459 269.037,77.083 264.259 C 81.184 252.188,90.624 243.010,102.734 239.319 C 110.072 237.083,291.047 237.108,297.656 239.346 C 309.831 243.469,318.888 252.402,322.917 264.259 C 324.541 269.037,324.581 270.395,324.828 328.320 L 325.081 387.500 340.987 387.500 C 367.924 387.500,380.708 380.015,386.261 360.991 C 388.052 354.858,388.165 97.267,386.379 93.907 C 385.025 91.358,308.141 14.589,305.715 13.363 C 304.653 12.826,298.546 12.500,289.541 12.500 L 275.072 12.500 274.805 64.648 C 274.607 103.235,274.287 117.544,273.576 119.670 C 271.146 126.930,264.002 133.923,256.768 136.121 C 252.067 137.550,147.936 137.551,143.236 136.122 C 135.869 133.883,128.898 127.062,126.424 119.670 C 125.713 117.544,125.393 103.235,125.195 64.648 L 124.928 12.500 83.753 12.542 C 46.038 12.580,42.201 12.704,38.095 14.017 M150.000 62.500 L 150.000 112.500 200.000 112.500 L 250.000 112.500 250.000 62.500 L 250.000 12.500 200.000 12.500 L 150.000 12.500 150.000 62.500 M105.657 264.058 C 99.653 267.719,100.006 263.657,100.003 329.102 L 100.000 387.500 200.000 387.500 L 300.000 387.500 299.997 329.102 C 299.994 263.657,300.347 267.719,294.343 264.058 C 290.342 261.619,109.658 261.619,105.657 264.058 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                            @error('project_number_prefix')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror

                            {{-- State Indicator --}}
                            <div x-data="{ show: false, timeoutId: null }"
                                @project-number-prefix-save.window="
                                    show = true;
                                    clearTimeout(timeoutId);
                                    timeoutId = setTimeout(() => show = false, 3000);"
                                class="absolute left-full top-0 flex items-center gap-2 h-full ms-2">

                                {{-- Loading Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class=" size-4 text-indigo-50 animate-spin"
                                    wire:loading wire:target="saveProject" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Indicator --}}
                                <span x-cloak x-show="show" x-transition.opacity
                                    class="flex items-center gap-2 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M362.500 56.340 C 352.317 58.043,357.949 52.810,246.679 163.959 L 143.749 266.778 96.679 219.844 C 44.257 167.573,46.207 169.193,34.480 168.209 C 8.309 166.015,-9.487 195.204,4.658 217.122 C 9.282 224.286,124.867 338.751,129.688 340.939 C 139.095 345.209,148.860 345.099,158.506 340.613 C 166.723 336.791,393.119 110.272,397.035 101.953 C 408.174 78.291,388.288 52.026,362.500 56.340 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </span>

                    {{-- Batch Number Prefix --}}
                    <span id="batch_number_prefix" class="flex items-center justify-between gap-10 w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Batch Number Prefix</h1>
                            <p class="text-xs text-red-300">This will affect all of the existing batches.</p>
                        </span>

                        <div class="relative">
                            <input type="text" autocomplete="off" id="batch_number_prefix"
                                wire:model.blur="batch_number_prefix"
                                class="{{ $errors->has('batch_number_prefix') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-40 md:w-56 text-xs md:text-sm ps-3 pe-11 py-1.5 duration-200 ease-in-out rounded"
                                placeholder="Type prefix">

                            <button type="button" wire:click="saveBatch" wire:loading.attr="disabled"
                                @if ($this->settings->get('batch_number_prefix', config('settings.batch_number_prefix')) === $batch_number_prefix) disabled @endif
                                class="absolute flex items-center justify-center right-0 top-0 p-2 rounded duration-200 ease-in-out {{ $this->settings->get('batch_number_prefix', config('settings.batch_number_prefix')) !== $batch_number_prefix ? 'text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900' : 'bg-zinc-900 text-zinc-500' }}">

                                {{-- Loading State --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 md:size-4 animate-spin"
                                    wire:loading wire:target="saveBatch" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 md:size-4" wire:loading.remove
                                    wire:target="saveBatch" xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                    height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M38.095 14.017 C 26.775 17.636,17.071 27.593,13.739 39.009 C 11.642 46.193,11.873 354.876,13.980 361.648 C 19.802 380.355,32.269 387.500,59.086 387.500 L 74.919 387.500 75.172 328.320 C 75.419 270.395,75.459 269.037,77.083 264.259 C 81.184 252.188,90.624 243.010,102.734 239.319 C 110.072 237.083,291.047 237.108,297.656 239.346 C 309.831 243.469,318.888 252.402,322.917 264.259 C 324.541 269.037,324.581 270.395,324.828 328.320 L 325.081 387.500 340.987 387.500 C 367.924 387.500,380.708 380.015,386.261 360.991 C 388.052 354.858,388.165 97.267,386.379 93.907 C 385.025 91.358,308.141 14.589,305.715 13.363 C 304.653 12.826,298.546 12.500,289.541 12.500 L 275.072 12.500 274.805 64.648 C 274.607 103.235,274.287 117.544,273.576 119.670 C 271.146 126.930,264.002 133.923,256.768 136.121 C 252.067 137.550,147.936 137.551,143.236 136.122 C 135.869 133.883,128.898 127.062,126.424 119.670 C 125.713 117.544,125.393 103.235,125.195 64.648 L 124.928 12.500 83.753 12.542 C 46.038 12.580,42.201 12.704,38.095 14.017 M150.000 62.500 L 150.000 112.500 200.000 112.500 L 250.000 112.500 250.000 62.500 L 250.000 12.500 200.000 12.500 L 150.000 12.500 150.000 62.500 M105.657 264.058 C 99.653 267.719,100.006 263.657,100.003 329.102 L 100.000 387.500 200.000 387.500 L 300.000 387.500 299.997 329.102 C 299.994 263.657,300.347 267.719,294.343 264.058 C 290.342 261.619,109.658 261.619,105.657 264.058 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                            @error('batch_number_prefix')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror

                            {{-- State Indicator --}}
                            <div x-data="{ show: false, timeoutId: null }"
                                @batch-number-prefix-save.window="
                                    show = true;
                                    clearTimeout(timeoutId);
                                    timeoutId = setTimeout(() => show = false, 3000);"
                                class="absolute left-full top-0 flex items-center gap-2 h-full ms-2">

                                {{-- Loading Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class=" size-4 text-indigo-50 animate-spin"
                                    wire:loading wire:target="saveBatch" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Indicator --}}
                                <span x-cloak x-show="show" x-transition.opacity
                                    class="flex items-center gap-2 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M362.500 56.340 C 352.317 58.043,357.949 52.810,246.679 163.959 L 143.749 266.778 96.679 219.844 C 44.257 167.573,46.207 169.193,34.480 168.209 C 8.309 166.015,-9.487 195.204,4.658 217.122 C 9.282 224.286,124.867 338.751,129.688 340.939 C 139.095 345.209,148.860 345.099,158.506 340.613 C 166.723 336.791,393.119 110.272,397.035 101.953 C 408.174 78.291,388.288 52.026,362.500 56.340 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </span>

                    {{-- Maximum Income --}}
                    <span id="maximum_income" class="flex items-center justify-between gap-10 w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Maximum Income</h1>
                            <p class="text-xs text-zinc-500">It's the default ceiling for the average monthly income.
                            </p>
                        </span>

                        <div class="relative">
                            <span class="relative">
                                <span
                                    class="absolute left-0 text-xs md:text-sm px-3 py-1.5 rounded-l pointer-events-none {{ $errors->has('maximum_income') ? 'bg-[#2D1E20] text-red-500' : 'bg-zinc-900 text-zinc-500' }} select-none">
                                    ₱
                                </span>
                                <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric"
                                    min="0" autocomplete="off" id="maximum_income"
                                    wire:model.live.debounce.500ms="maximum_income"
                                    class="{{ $errors->has('maximum_income') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-40 md:w-56 text-xs md:text-sm ps-11 pe-3 py-1.5 duration-200 ease-in-out rounded"
                                    placeholder="Type minimum wage">
                            </span>
                            @error('maximum_income')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror

                            {{-- State Indicator --}}
                            <div x-data="{ show: false, timeoutId: null }"
                                @maximum-income-save.window="
                                    show = true;
                                    clearTimeout(timeoutId);
                                    timeoutId = setTimeout(() => show = false, 3000);"
                                class="absolute left-full top-0 flex items-center gap-2 h-full ms-2">

                                {{-- Loading Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class=" size-4 text-indigo-50 animate-spin"
                                    wire:loading wire:target="maximum_income" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Indicator --}}
                                <span x-cloak x-show="show" x-transition.opacity
                                    class="flex items-center gap-2 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M362.500 56.340 C 352.317 58.043,357.949 52.810,246.679 163.959 L 143.749 266.778 96.679 219.844 C 44.257 167.573,46.207 169.193,34.480 168.209 C 8.309 166.015,-9.487 195.204,4.658 217.122 C 9.282 224.286,124.867 338.751,129.688 340.939 C 139.095 345.209,148.860 345.099,158.506 340.613 C 166.723 336.791,393.119 110.272,397.035 101.953 C 408.174 78.291,388.288 52.026,362.500 56.340 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
