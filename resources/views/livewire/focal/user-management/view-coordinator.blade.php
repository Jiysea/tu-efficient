<div x-cloak x-show="viewCoordinatorModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
    @keydown.escape.window="viewCoordinatorModal">

    <!-- Modal -->
    <div x-show="viewCoordinatorModal" x-trap.noautofocus.noscroll="viewCoordinatorModal"
        class="relative h-full overflow-y-auto p-4 flex items-start sm:items-center justify-center select-none">

        <!-- Modal content -->
        <div x-data="{ deleteCoordinatorModal: $wire.entangle('deleteCoordinatorModal') }" class="w-full sm:w-auto">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-lg font-semibold text-indigo-1100 ">
                        {{ $editMode ? 'Edit Coordinator' : 'View Coordinator' }}
                    </h1>
                    <div class="flex items-center justify-center gap-2">

                        {{-- Loading State for Changes --}}
                        <svg class="size-6 text-indigo-900 animate-spin" wire:loading
                            wire:target="deleteCoordinator, toggleEdit" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <button type="button"
                            class="text-indigo-400 bg-transparent focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 outline-none rounded size-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-200 ease-in-out"
                            @click="$wire.resetView(); viewCoordinatorModal = false;">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
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
                <div class="px-10 py-5">
                    <div class="whitespace-nowrap grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 text-xs">
                        {{-- IF Edit Mode is ON --}}
                        @if ($editMode)
                            <div @edit-values.window="$wire.setValues();"
                                class="col-span-full whitespace-nowrap grid gap-4 sm:grid-cols-4 lg:grid-cols-8 text-xs">

                                {{-- First Name --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="first_name" class="block mb-1 font-medium text-indigo-1100 ">First
                                        Name</label>
                                    <input type="text" id="first_name" autocomplete="off"
                                        wire:model.blur="first_name"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                        placeholder="Type first name">
                                    @error('first_name')
                                        <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Middle Name --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="middle_name" class="block mb-1  font-medium text-indigo-1100 ">Middle
                                        Name</label>
                                    <input type="text" id="middle_name" autocomplete="off"
                                        wire:model.blur="middle_name"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                        placeholder="(optional)">
                                    @error('middle_name')
                                        <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Last Name --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="last_name" class="block mb-1  font-medium text-indigo-1100 ">Last
                                        Name</label>
                                    <input type="text" id="last_name" autocomplete="off" wire:model.blur="last_name"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                        placeholder="Type last name">
                                    @error('last_name')
                                        <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}
                                        </p>
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
                                        <p class="text-red-500 absolute left-0 top-full z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                @if (!$this->isEmailVerified)
                                    {{-- Email --}}
                                    <div class="relative col-span-full sm:col-span-4 mb-4 pb-1">
                                        <label for="email"
                                            class="block mb-1  font-medium text-indigo-1100 ">Email</label>
                                        <div class="relative">
                                            <div
                                                class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('email') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="currentColor"
                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-mail">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M22 7.535v9.465a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-9.465l9.445 6.297l.116 .066a1 1 0 0 0 .878 0l.116 -.066l9.445 -6.297z" />
                                                    <path
                                                        d="M19 4c1.08 0 2.027 .57 2.555 1.427l-9.555 6.37l-9.555 -6.37a2.999 2.999 0 0 1 2.354 -1.42l.201 -.007h14z" />
                                                </svg>
                                            </div>
                                            <input type="text" id="email" autocomplete="off"
                                                wire:model.blur="email"
                                                class="text-xs ps-10 border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('email') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                                placeholder="email@example.com">
                                        </div>
                                        @error('email')
                                            <p class="text-red-500 absolute left-0 top-full z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @else
                                    <div class="relative col-span-full sm:col-span-4 mb-4 pb-1">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Email
                                        </p>
                                        <span
                                            class="text-xs border outline-none rounded block w-full p-2 bg-gray-100 border-gray-300 text-gray-500">
                                            {{ $this->user->email }}
                                        </span>
                                    </div>
                                @endif

                                @if (!$this->isMobileVerified)
                                    {{-- Contact Number --}}
                                    <div class="relative col-span-full sm:col-span-4 mb-4 pb-1">
                                        <label for="contact_num"
                                            class="block mb-1 font-medium text-indigo-1100 ">Contact
                                            Number</label>
                                        <div {{-- x-effect="console.log(unmaskedBudget)" --}} class="relative">
                                            <div
                                                class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('contact_num') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                                <p
                                                    class="flex text-center w-full relative items-center justify-center font-medium">
                                                    +63
                                                </p>
                                            </div>
                                            <input x-mask="99999999999" type="text" inputmode="numeric"
                                                min="0" autocomplete="off" id="contact_num"
                                                wire:model="contact_num" @input="$wire.set('contact_num', $el.value);"
                                                class="text-xs outline-none border ps-12 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('contact_num') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50  border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                                placeholder="ex. 09123456789">
                                        </div>
                                        @error('contact_num')
                                            <p class="text-red-500 absolute left-0 top-full z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @else
                                    <div class="relative col-span-full sm:col-span-4 mb-4 pb-1">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Contact Number
                                        </p>
                                        <span
                                            class="text-xs border outline-none rounded block w-full p-2 bg-gray-100 border-gray-300 text-gray-500">
                                            {{ $this->user->contact_num }}
                                        </span>
                                    </div>
                                @endif

                                <div
                                    class="col-span-full flex items-center {{ $this->isEmailVerified || $this->isMobileVerified ? 'justify-between' : 'justify-end' }}">

                                    @if ($this->isEmailVerified || $this->isMobileVerified)
                                        <span
                                            class="bg-amber-100 text-amber-900 border-amber-300 border rounded px-6 py-2">
                                            Some fields cannot be modified if the user is already verified.
                                        </span>
                                    @endif

                                    <div class="flex items-center justify-center gap-2.5">

                                        {{-- Save Button --}}
                                        <button type="button" wire:loading.attr="disabled"
                                            wire:target="editCoordinator" wire:click="editCoordinator"
                                            class="py-2 px-4 font-bold flex items-center justify-center gap-1.5 rounded-md disabled:opacity-75 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                            <p>SAVE</p>

                                            {{-- Loading Icon --}}
                                            <svg class="size-5 animate-spin" wire:loading
                                                wire:target="editCoordinator" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Check Icon --}}
                                            <svg class="size-5" wire:loading.remove wire:target="editCoordinator"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M179.372 38.390 C 69.941 52.432,5.211 171.037,53.012 269.922 C 112.305 392.582,285.642 393.654,346.071 271.735 C 403.236 156.402,307.211 21.986,179.372 38.390 M273.095 139.873 C 278.022 142.919,280.062 149.756,277.522 154.718 C 275.668 158.341,198.706 250.583,194.963 253.668 C 189.575 258.110,180.701 259.035,173.828 255.871 C 168.508 253.422,123.049 207.486,121.823 203.320 C 119.042 193.868,129.809 184.732,138.528 189.145 C 139.466 189.620,149.760 199.494,161.402 211.088 L 182.569 232.168 220.917 186.150 C 242.008 160.840,260.081 139.739,261.078 139.259 C 264.132 137.789,270.227 138.101,273.095 139.873 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        {{-- Cancel/X Button --}}
                                        <button type="button" wire:click="toggleEdit"
                                            class="py-2 px-4 font-bold flex items-center justify-center gap-1.5 rounded-md disabled:opacity-75 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 focus:ring-4 focus:outline-none focus:ring-red-300">

                                            {{-- X Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                    </div>
                                </div>

                            </div>
                        @endif

                        {{-- If Edit Mode is OFF --}}
                        @if (!$editMode)
                            @if (!$this->isEmailVerified)
                                <div
                                    class="whitespace-normal flex flex-1 items-center col-span-full rounded px-3 py-1.5 text-xs font-medium border border-red-300 bg-red-50 text-red-700">
                                    This coordinator cannot be assigned to any batches until the email is verified.
                                </div>
                            @endif
                            {{-- First Name OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    First Name
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->user?->first_name }}</span>
                            </div>

                            {{-- Middle Name OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    Middle Name
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->user?->middle_name ?? '-' }}</span>
                            </div>

                            {{-- Last Name OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    Last Name
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->user?->last_name }}</span>
                            </div>

                            {{-- Extension Name OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    Extension Name
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->user?->extension_name ?? '-' }}</span>
                            </div>

                            {{-- Email OFF --}}
                            <div class="relative lg:col-span-2 flex flex-col">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    Email Address
                                </p>
                                <div
                                    class="flex flex-1 text-sm rounded p-2.5 {{ $this->isEmailVerified ? 'bg-indigo-50 text-indigo-700' : 'bg-red-50 text-red-700' }} font-medium">
                                    <span class="flex w-full items-center justify-between">
                                        {{ $this->user?->email }}
                                        @if ($this->isEmailVerified)
                                            <svg data-popover-target="email_verified" data-popover-trigger="hover"
                                                xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M190.234 1.053 C 183.006 3.443,179.748 6.015,162.891 22.637 C 137.131 48.038,136.175 48.438,101.156 48.438 C 71.648 48.438,66.707 49.653,57.762 59.111 C 49.525 67.820,48.437 72.809,48.437 101.880 C 48.438 136.116,47.952 137.261,22.557 162.961 C 1.288 184.486,0.391 185.985,0.391 200.000 C 0.391 213.983,1.272 215.457,22.513 237.010 C 47.993 262.863,48.438 263.925,48.438 298.909 C 48.438 328.249,49.672 333.312,58.975 342.110 C 67.946 350.594,72.232 351.562,100.798 351.562 C 136.076 351.563,137.208 352.034,162.990 377.469 C 184.541 398.730,186.011 399.609,200.000 399.609 C 213.989 399.609,215.459 398.730,237.010 377.469 C 262.792 352.034,263.924 351.562,299.202 351.563 C 327.768 351.563,332.054 350.594,341.025 342.110 C 350.306 333.332,351.563 328.215,351.563 299.202 C 351.563 263.924,352.034 262.792,377.469 237.010 C 398.730 215.459,399.609 213.989,399.609 200.000 C 399.609 186.011,398.730 184.541,377.469 162.990 C 352.034 137.208,351.563 136.076,351.563 100.798 C 351.563 71.785,350.306 66.668,341.025 57.890 C 332.054 49.406,327.768 48.438,299.202 48.437 C 263.924 48.437,262.792 47.966,237.010 22.531 C 216.042 1.845,213.983 0.563,201.172 0.226 C 196.248 0.096,192.204 0.402,190.234 1.053 M236.530 211.517 L 173.833 274.217 137.307 237.698 L 100.781 201.178 112.109 189.851 L 123.436 178.524 148.439 203.517 L 173.441 228.510 224.802 177.150 L 276.162 125.790 287.694 137.303 L 299.227 148.817 236.530 211.517 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @else
                                            <svg data-popover-target="email_verified" data-popover-trigger="hover"
                                                xmlns="http://www.w3.org/2000/svg" class="size-5 text-red-500"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @endif
                                    </span>
                                </div>
                                {{-- is Email Verified? Popover. --}}
                                <div data-popover id="email_verified" role="tooltip"
                                    class="absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                    <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                        <p class="flex items-center justify-center">
                                            @if ($this->isEmailVerified)
                                                <span class="flex items-center justify-center gap-1.5 text-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M190.234 1.053 C 183.006 3.443,179.748 6.015,162.891 22.637 C 137.131 48.038,136.175 48.438,101.156 48.438 C 71.648 48.438,66.707 49.653,57.762 59.111 C 49.525 67.820,48.437 72.809,48.437 101.880 C 48.438 136.116,47.952 137.261,22.557 162.961 C 1.288 184.486,0.391 185.985,0.391 200.000 C 0.391 213.983,1.272 215.457,22.513 237.010 C 47.993 262.863,48.438 263.925,48.438 298.909 C 48.438 328.249,49.672 333.312,58.975 342.110 C 67.946 350.594,72.232 351.562,100.798 351.562 C 136.076 351.563,137.208 352.034,162.990 377.469 C 184.541 398.730,186.011 399.609,200.000 399.609 C 213.989 399.609,215.459 398.730,237.010 377.469 C 262.792 352.034,263.924 351.562,299.202 351.563 C 327.768 351.563,332.054 350.594,341.025 342.110 C 350.306 333.332,351.563 328.215,351.563 299.202 C 351.563 263.924,352.034 262.792,377.469 237.010 C 398.730 215.459,399.609 213.989,399.609 200.000 C 399.609 186.011,398.730 184.541,377.469 162.990 C 352.034 137.208,351.563 136.076,351.563 100.798 C 351.563 71.785,350.306 66.668,341.025 57.890 C 332.054 49.406,327.768 48.438,299.202 48.437 C 263.924 48.437,262.792 47.966,237.010 22.531 C 216.042 1.845,213.983 0.563,201.172 0.226 C 196.248 0.096,192.204 0.402,190.234 1.053 M236.530 211.517 L 173.833 274.217 137.307 237.698 L 100.781 201.178 112.109 189.851 L 123.436 178.524 148.439 203.517 L 173.441 228.510 224.802 177.150 L 276.162 125.790 287.694 137.303 L 299.227 148.817 236.530 211.517 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                    Email is verified
                                                </span>
                                            @else
                                                <span class="flex items-center justify-center gap-1.5 text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                    Email is not yet verified
                                                </span>
                                            @endif

                                        </p>
                                    </div>
                                    <div data-popper-arrow></div>
                                </div>
                            </div>

                            {{-- Contact Number OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    Contact Number
                                </p>
                                <div data-popover-target="mobile_verified" data-popover-trigger="hover"
                                    class="flex flex-1 text-sm rounded p-2.5 {{ $this->isMobileVerified ? 'bg-indigo-50 text-indigo-700' : 'bg-red-50 text-red-700' }} font-medium">
                                    <span>
                                        {{ $this->user?->contact_num }}
                                    </span>
                                </div>

                                {{-- is Contact Number Verified? Popover. --}}
                                <div data-popover id="mobile_verified" role="tooltip"
                                    class="absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                    <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                        <p class="flex items-center justify-center">
                                            @if ($this->isMobileVerified)
                                                <span class="flex items-center justify-center gap-1.5 text-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M190.234 1.053 C 183.006 3.443,179.748 6.015,162.891 22.637 C 137.131 48.038,136.175 48.438,101.156 48.438 C 71.648 48.438,66.707 49.653,57.762 59.111 C 49.525 67.820,48.437 72.809,48.437 101.880 C 48.438 136.116,47.952 137.261,22.557 162.961 C 1.288 184.486,0.391 185.985,0.391 200.000 C 0.391 213.983,1.272 215.457,22.513 237.010 C 47.993 262.863,48.438 263.925,48.438 298.909 C 48.438 328.249,49.672 333.312,58.975 342.110 C 67.946 350.594,72.232 351.562,100.798 351.562 C 136.076 351.563,137.208 352.034,162.990 377.469 C 184.541 398.730,186.011 399.609,200.000 399.609 C 213.989 399.609,215.459 398.730,237.010 377.469 C 262.792 352.034,263.924 351.562,299.202 351.563 C 327.768 351.563,332.054 350.594,341.025 342.110 C 350.306 333.332,351.563 328.215,351.563 299.202 C 351.563 263.924,352.034 262.792,377.469 237.010 C 398.730 215.459,399.609 213.989,399.609 200.000 C 399.609 186.011,398.730 184.541,377.469 162.990 C 352.034 137.208,351.563 136.076,351.563 100.798 C 351.563 71.785,350.306 66.668,341.025 57.890 C 332.054 49.406,327.768 48.438,299.202 48.437 C 263.924 48.437,262.792 47.966,237.010 22.531 C 216.042 1.845,213.983 0.563,201.172 0.226 C 196.248 0.096,192.204 0.402,190.234 1.053 M236.530 211.517 L 173.833 274.217 137.307 237.698 L 100.781 201.178 112.109 189.851 L 123.436 178.524 148.439 203.517 L 173.441 228.510 224.802 177.150 L 276.162 125.790 287.694 137.303 L 299.227 148.817 236.530 211.517 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                    Contact number is verified
                                                </span>
                                            @else
                                                <span class="flex items-center justify-center gap-1.5 text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                    Contact number is not yet verified
                                                </span>
                                            @endif

                                        </p>
                                    </div>
                                    <div data-popper-arrow></div>
                                </div>
                            </div>

                            {{-- Edit && Delete Buttons --}}
                            <div class="relative flex items-end justify-end gap-2 col-span-full lg:col-span-1">
                                <button type="button" wire:loading.attr="disabled" wire:target="toggleEdit"
                                    wire:click="" @click="$wire.toggleEdit(); $dispatch('edit-values');"
                                    class="flex flex-1 items-center justify-center px-4 py-2.5 rounded outline-none font-bold text-sm duration-200 ease-in-out bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                    EDIT
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd">
                                            </path>
                                        </g>
                                    </svg>
                                </button>

                                @if (!$this->isEmailVerified)
                                    <button type="button" @click="deleteCoordinatorModal = !deleteCoordinatorModal;"
                                        wire:loading.attr="disabled"
                                        class="flex items-center justify-center px-4 py-2.5 rounded outline-none font-bold text-sm duration-200 ease-in-out bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                    </button>
                                @endif

                                {{-- Delete Project Modal --}}
                                <div x-cloak>
                                    <!-- Modal Backdrop -->
                                    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                        x-show="deleteCoordinatorModal">
                                    </div>

                                    <!-- Modal -->
                                    <div x-trap.inert="deleteCoordinatorModal" x-show="deleteCoordinatorModal"
                                        x-trap.noscroll="deleteCoordinatorModal"
                                        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                        {{-- The Modal --}}
                                        <div class="relative w-full max-w-xl max-h-full">
                                            <div class="relative bg-white rounded-md shadow">
                                                <!-- Modal Header -->
                                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                        Delete a Coordinator
                                                    </h1>

                                                    {{-- Close Button --}}
                                                    <button type="button" @click="deleteCoordinatorModal = false;"
                                                        class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                                        <svg class="size-3" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close Modal</span>
                                                    </button>
                                                </div>

                                                <hr class="">

                                                {{-- Modal body --}}
                                                <div
                                                    class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                                    <p class="font-medium text-sm mb-1">
                                                        Are you sure about deleting this coordinator?
                                                    </p>
                                                    <p class="text-gray-500 text-sm mb-4">
                                                        (This is action is irreversible)
                                                    </p>
                                                    <div class="flex items-center justify-center w-full gap-4">
                                                        <button type="button"
                                                            @click="deleteCoordinatorModal = false;"
                                                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                                            CANCEL
                                                        </button>
                                                        <button type="button"
                                                            @click="$wire.deleteCoordinator(); deleteCoordinatorModal = false;"
                                                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                                            CONFIRM
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="col-span-full w-full my-4">

                            <div class="flex items-center justify-between gap-4 col-span-full">
                                {{-- Regional Office OFF --}}
                                <div class="relative flex flex-1 flex-col">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Regional Office
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->user?->regional_office }}</span>
                                </div>

                                {{-- Field Office OFF --}}
                                <div class="relative flex flex-1 flex-col">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Field Office
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->user?->field_office }}</span>
                                </div>
                            </div>

                            {{-- Date Created && Last Updated OFF --}}
                            <div
                                class="flex flex-col sm:flex-row items-center justify-between col-span-full gap-2 sm:gap-4">
                                <div class="flex flex-1 items-center justify-center">
                                    <p class="font-bold text-indigo-1100">
                                        Date Created:
                                    </p>
                                    <span
                                        class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->user ? date('M d, Y @ h:i:s a', strtotime($this->user->created_at)) : null }}</span>
                                </div>

                                <div class="flex flex-1 items-center justify-center">
                                    <p class="font-bold text-indigo-1100">
                                        Last Updated:
                                    </p>
                                    <span
                                        class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->user ? date('M d, Y @ h:i:s a', strtotime($this->user->updated_at)) : null }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });
    </script>
@endscript
