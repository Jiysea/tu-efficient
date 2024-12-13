<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto backdrop-blur-sm z-50"
    x-show="addCoordinatorsModal" @keydown.escape.window="addCoordinatorsModal">

    <div x-show="addCoordinatorsModal" x-trap.noscroll.noautofocus="addCoordinatorsModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="w-full sm:w-auto">

            <!-- Modal content -->
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-lg font-semibold text-indigo-1100 ">
                        Add New Coordinator
                    </h1>
                    <div class="flex items-center justify-center gap-2">

                        {{-- Loading State for Changes --}}
                        <div class="text-indigo-900" wire:loading
                            wire:target="first_name, last_name, email, password, contact_num">
                            <svg class="size-6 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        {{-- Close Button --}}
                        <button type="button" @click="$wire.resetCoordinators(); addCoordinatorsModal = false;"
                            class="text-indigo-400 outline-none focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                <!-- Modal body -->
                <form wire:submit.prevent="saveUser" class="px-10 py-5">
                    <div class="grid gap-4 mb-4 sm:grid-cols-4 lg:grid-cols-8 text-xs">
                        {{-- First Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="first_name" class="block mb-1 font-medium text-indigo-1100 ">First Name</label>
                            <input type="text" id="first_name" autocomplete="off" wire:model.blur="first_name"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type first name">
                            @error('first_name')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Middle Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="middle_name" class="block mb-1  font-medium text-indigo-1100 ">Middle
                                Name</label>
                            <input type="text" id="middle_name" autocomplete="off" wire:model.blur="middle_name"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="(optional)">
                            @error('middle_name')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Last Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="last_name" class="block mb-1  font-medium text-indigo-1100 ">Last Name</label>
                            <input type="text" id="last_name" autocomplete="off" wire:model.blur="last_name"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type last name">
                            @error('last_name')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Extension Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="extension_name" class="block mb-1 font-medium text-indigo-1100 ">Ext.
                                Name</label>
                            <input type="text" id="extension_name" autocomplete="off"
                                wire:model.blur="extension_name"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="III, Sr., etc.">
                            @error('extension_name')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Email --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="email" class="block mb-1  font-medium text-indigo-1100 ">Email</label>
                            <div class="relative">
                                <div
                                    class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('email') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-mail">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M22 7.535v9.465a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-9.465l9.445 6.297l.116 .066a1 1 0 0 0 .878 0l.116 -.066l9.445 -6.297z" />
                                        <path
                                            d="M19 4c1.08 0 2.027 .57 2.555 1.427l-9.555 6.37l-9.555 -6.37a2.999 2.999 0 0 1 2.354 -1.42l.201 -.007h14z" />
                                    </svg>
                                </div>
                                <input type="text" id="email" autocomplete="off" wire:model.blur="email"
                                    class="text-xs ps-10 border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('email') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                    placeholder="email@example.com">
                            </div>
                            @error('email')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Password --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="password" class="block mb-1  font-medium text-indigo-1100 ">Password</label>
                            <input type="password" id="password" autocomplete="off" wire:model.blur="password"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('password') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type password">
                            @error('password')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Confirm Password --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="password_confirmation"
                                class="block mb-1  font-medium text-indigo-1100 ">Confirm
                                Password</label>
                            <input type="password" id="password_confirmation" autocomplete="off"
                                wire:model.blur="password_confirmation"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('password_confirmation') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Confirm your password">
                            @error('password_confirmation')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Contact Number --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="contact_num" class="block mb-1 font-medium text-indigo-1100 ">Contact
                                Number</label>
                            <div {{-- x-effect="console.log(unmaskedBudget)" --}} class="relative">
                                <div
                                    class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('contact_num') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                    <p
                                        class="flex text-center w-full relative items-center justify-center font-medium">
                                        +63
                                    </p>
                                </div>
                                <input x-mask="99999999999" type="text" inputmode="numeric" min="0"
                                    autocomplete="off" id="contact_num" @input="$wire.set('contact_num', $el.value);"
                                    class="text-xs outline-none border ps-12 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('contact_num') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50  border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                    placeholder="ex. 09123456789">
                            </div>
                            @error('contact_num')
                                <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="w-full flex relative items-center justify-end">

                        <button type="submit" wire:loading.attr="disabled" wire:target="saveUser"
                            class="flex items-center justify-center gap-2 py-2 px-4 rounded-md text-sm font-bold text-indigo-50 bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                            <p>ADD NEW COORDINATOR</p>

                            {{-- Loading State for Changes --}}
                            <svg class="text-indigo-50 size-5 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" wire:loading wire:target="saveUser">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Add Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" wire:loading.remove
                                wire:target="saveUser" xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                height="400" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M87.232 51.235 C 70.529 55.279,55.160 70.785,51.199 87.589 C 49.429 95.097,49.415 238.777,51.184 245.734 C 55.266 261.794,68.035 275.503,84.375 281.371 L 89.453 283.195 164.063 283.423 C 247.935 283.680,244.564 283.880,256.471 277.921 C 265.327 273.488,273.488 265.327,277.921 256.471 C 283.880 244.564,283.680 247.935,283.423 164.063 L 283.195 89.453 281.371 84.375 C 275.503 68.035,261.794 55.266,245.734 51.184 C 239.024 49.478,94.296 49.525,87.232 51.235 M326.172 101.100 C 323.101 102.461,320.032 105.395,318.240 108.682 C 316.870 111.194,316.777 115.490,316.406 193.359 L 316.016 275.391 313.810 281.633 C 308.217 297.460,296.571 308.968,280.859 314.193 L 275.391 316.012 193.359 316.404 L 111.328 316.797 108.019 318.693 C 97.677 324.616,97.060 340.415,106.903 347.255 L 110.291 349.609 195.575 349.609 L 280.859 349.609 287.500 347.798 C 317.300 339.669,339.049 318.056,347.783 287.891 L 349.592 281.641 349.816 196.680 C 350.060 104.007,350.312 109.764,345.807 104.807 C 341.717 100.306,332.072 98.485,326.172 101.100 M172.486 118.401 C 180.422 121.716,182.772 126.649,182.795 140.039 L 182.813 150.000 190.518 150.000 C 209.679 150.000,219.220 157.863,215.628 170.693 C 213.075 179.810,207.578 182.771,193.164 182.795 L 182.813 182.813 182.795 193.164 C 182.771 207.578,179.810 213.075,170.693 215.628 C 157.863 219.220,150.000 209.679,150.000 190.518 L 150.000 182.813 140.039 182.795 C 123.635 182.767,116.211 176.839,117.378 164.698 C 118.318 154.920,125.026 150.593,139.970 150.128 L 150.000 149.815 150.000 142.592 C 150.000 122.755,159.204 112.853,172.486 118.401 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
