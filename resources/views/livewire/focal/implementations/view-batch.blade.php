<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="openBatchModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="openBatchModal" x-trap.noscroll="openBatchModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full"
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        {{-- The Modal --}}
        <div class="relative w-full max-w-4xl max-h-full">
            <div class="relative bg-white rounded-md shadow">

                <form wire:submit.prevent="saveProject">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                        <span class="flex items-center justify-center">
                            <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">View Batch

                            </h1>

                        </span>
                        <div class="flex items-center justify-center">
                            {{-- Loading State for Changes --}}
                            <div class="z-50 text-indigo-900" wire:loading>
                                <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                            <button type="button" @click="$wire.resetEverything(); openBatchModal = false;"
                                class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close Modal</span>
                            </button>
                        </div>
                    </div>

                    <hr class="">

                    {{-- Modal body --}}
                    @if ($this->batch)
                        <div class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                                {{-- Edit Mode is ON --}}
                                @if ($edit)
                                    {{-- Batch Number --}}
                                    <div class="flex flex-1 flex-col relative mb-4">
                                        @if ($isEmpty)
                                            <label for="batch_num"
                                                class="block mb-1  font-medium text-indigo-1100 ">Batch
                                                Number <span class="text-red-700 font-normal text-xs">*</span> <span
                                                    class="text-gray-500 ms-2">prefix:
                                                    <strong>{{ substr($batchNumPrefix ?? config('settings.batch_number_prefix'), 0, strlen($batchNumPrefix ?? config('settings.batch_number_prefix')) - 1) }}</strong></span></label>
                                            <input type="text" id="batch_num" autocomplete="off"
                                                wire:model.blur="batch_num"
                                                class="text-xs duration-200 {{ $errors->has('batch_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5 "
                                                placeholder="Type project number">
                                            @error('batch_num')
                                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        @else
                                            <p class="block mb-1 font-medium text-indigo-1100">
                                                Batch Number
                                            </p>
                                            <span
                                                class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->batch_num }}</span>
                                        @endif
                                    </div>

                                    {{-- Barangay --}}
                                    <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }" class="relative flex flex-col mb-4">
                                        {{-- Barangay Button --}}
                                        <p class="block mb-1 font-medium text-indigo-1100 ">Barangay <span
                                                class="text-red-700 font-normal text-xs">*</span>
                                        </p>
                                        <button type="button" id="barangay_name" @click="show = !show;"
                                            class="text-xs flex items-center justify-between px-4 {{ $errors->has('barangay_name') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                            <span x-text="barangay_name"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-3 duration-200 ease-in-out">
                                                <path fill-rule="evenodd"
                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        {{-- Barangay Content --}}
                                        <div x-show="show" @click.away=" if(show == true) { show = !show; }"
                                            class="end-0 top-full absolute text-indigo-1100 bg-white w-60 shadow-lg border z-50 border-indigo-100 rounded p-3 mt-2">
                                            <div class="relative flex items-center justify-center py-1 group">
                                                {{-- Search Icon --}}
                                                <svg wire:loading.remove wire:target="searchBarangay"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                    <path fill-rule="evenodd"
                                                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{-- Loading Icon --}}
                                                <svg wire:loading wire:target="searchBarangay"
                                                    class="absolute start-0 ms-2 size-4 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                {{-- Search Input Box --}}
                                                <input id="searchBarangay"
                                                    wire:model.live.debounce.500ms="searchBarangay" type="text"
                                                    autocomplete="off"
                                                    class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                    placeholder="Search barangay">
                                            </div>
                                            {{-- List of Barangays --}}
                                            <ul class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44">
                                                @forelse ($this->barangays as $key => $barangay)
                                                    <li wire:key={{ $key }}>
                                                        <button type="button"
                                                            @click="show = !show; barangay_name = '{{ $barangay }}'; $wire.$refresh();"
                                                            wire:loading.attr="disabled"
                                                            aria-label="{{ __('Barangays') }}"
                                                            class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                                    </li>
                                                @empty
                                                    <div class="h-full w-full text-xs text-gray-500 p-2">
                                                        No barangays found
                                                    </div>
                                                @endforelse
                                            </ul>
                                        </div>
                                        @error('barangay_name')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- Slots --}}
                                    <div class="flex flex-1 flex-col relative mb-4">
                                        <label for="slots_allocated"
                                            class="block mb-1 font-medium text-indigo-1100 ">Slots
                                            <span class="text-red-700 font-normal text-xs">*</span></label>
                                        <div class="relative">
                                            <input type="number" inputmode="numeric" min="0"
                                                id="slots_allocated" autocomplete="off"
                                                @input="$wire.set('slots_allocated', $el.value)"
                                                class="text-xs {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                                placeholder="Type slots allocation">
                                        </div>
                                        @error('slots_allocated')
                                            <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- Coordinators --}}
                                    <div x-data="{ show: false, currentCoordinator: $wire.entangle('currentCoordinator'), coordinatorKey: $wire.entangle('coordinatorKey') }" class="flex flex-col relative mb-4">
                                        <p class="block mb-1 font-medium text-indigo-1100">Add Coordinator <span
                                                class="text-red-700 font-normal text-xs">*</span></p>
                                        <div class="relative z-50 h-full">
                                            <button type="button" id="coordinator_name" @click="show = !show;"
                                                class="w-full h-full border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none text-xs px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                                <span x-text="currentCoordinator"></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-4 ms-3 duration-200 ease-in-out">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            {{-- Dropdown Content --}}
                                            <div x-show="show"
                                                @click.away="
                                                    if(show == true) {
                                                    show = !show;
                                                    }
                                                    "
                                                class="end-0 absolute text-indigo-1100 bg-white shadow-lg border border-indigo-300 rounded p-1.5 mt-2">
                                                <div class="relative flex items-center justify-center py-1 group">
                                                    {{-- Search Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        class="absolute start-0 ps-2 w-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                        <path fill-rule="evenodd"
                                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    {{-- Search Bar --}}
                                                    <input id="searchCoordinator"
                                                        wire:model.live.debounce.500ms="searchCoordinator"
                                                        type="text" autocomplete="off"
                                                        class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                        placeholder="Search coordinator">
                                                </div>
                                                {{-- Available Coordinators List --}}
                                                <ul
                                                    class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">
                                                    @forelse ($this->coordinators as $key => $coordinator)
                                                        <li wire:key={{ $key }}>
                                                            <button type="button"
                                                                @click="show= !show; currentCoordinator = '{{ $this->getFullName($key) }}'; coordinatorKey = {{ $key }}; console.log(currentCoordinator);"
                                                                wire:loading.attr="disabled"
                                                                aria-label="{{ __('Coordinator') }}"
                                                                class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                                {{ $this->getFullName($key) }}
                                                            </button>
                                                        </li>
                                                    @empty
                                                        <li class="w-full h-full">
                                                            No coordinators found.
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Assigned Coordinators --}}
                                    <div class="relative flex mb-4 sm:col-span-2">
                                        {{-- Arrow button --}}
                                        <button type="button"
                                            @if ($this->coordinators->isNotEmpty()) wire:click="addToastCoordinator" @else disabled @endif
                                            class="absolute z-40 p-1.5 grid place-items-center place-self-end rounded border-2 outline-none duration-200 ease-in-out
                                            {{ $this->coordinators->isNotEmpty()
                                                ? 'text-indigo-700 hover:text-indigo-50 active:text-indigo-300 border-indigo-700 focus:ring-indigo-700 focus:ring-2 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900'
                                                : 'text-gray-300 border-gray-300' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M277.913 100.212 C 268.376 103.320,263.354 115.296,267.916 124.055 C 268.746 125.649,281.931 139.434,297.217 154.688 L 325.008 182.422 176.371 182.813 L 27.734 183.203 24.044 185.372 C 11.976 192.467,13.880 212.729,26.953 216.320 C 29.173 216.930,72.861 217.180,177.711 217.183 L 325.343 217.188 296.350 246.289 C 268.003 274.743,267.339 275.480,266.516 279.416 C 263.782 292.490,275.629 303.458,288.672 299.926 C 292.603 298.862,379.406 212.826,382.053 207.371 C 383.922 203.517,384.072 197.196,382.390 193.139 C 380.867 189.467,295.574 103.760,291.158 101.464 C 287.389 99.505,281.724 98.970,277.913 100.212 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        {{-- Toast Area --}}
                                        <span
                                            class="flex flex-col flex-1 ms-14 overflow-x-scroll scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                            <p class="block mb-1 ms-4 text-indigo-1100 font-semibold">
                                                Assigned Coordinators
                                            </p>

                                            {{-- The Coordinators in a Toast --}}
                                            <span
                                                class="flex flex-1 text-xs rounded px-2.5 py-1 bg-white text-indigo-700 ">

                                                {{-- Toast Box of Coordinators --}}
                                                @foreach ($assigned_coordinators as $key => $assignedCoordinator)
                                                    <span
                                                        class=" py-1 px-2 me-2 rounded whitespace-nowrap duration-200 ease-in-out bg-indigo-100 text-indigo-700">
                                                        {{ $this->getFullNameByFull($assignedCoordinator['first_name'], $assignedCoordinator['middle_name'], $assignedCoordinator['last_name'], $assignedCoordinator['extension_name']) }}
                                                        {{-- X button --}}
                                                        <button type="button"
                                                            wire:click="removeToastCoordinator({{ $key }})"
                                                            class="ms-1 text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-2"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="400" height="400"
                                                                viewBox="0, 0, 400,400">
                                                                <g>
                                                                    <path
                                                                        d="M361.328 24.634 C 360.898 24.760,359.492 25.090,358.203 25.367 C 356.430 25.748,336.886 44.832,277.930 103.751 L 200.000 181.630 122.461 104.141 C 63.729 45.447,44.353 26.531,42.578 26.152 C 41.289 25.876,39.757 25.501,39.174 25.318 C 34.894 23.974,27.311 29.477,25.821 35.008 C 23.781 42.584,18.944 37.183,104.155 122.463 L 181.634 200.004 104.179 277.541 C 20.999 360.810,24.999 356.511,25.003 362.644 C 25.008 370.270,29.730 374.992,37.356 374.997 C 43.489 375.001,39.190 379.002,122.461 295.819 L 200.000 218.362 277.539 295.819 C 360.929 379.120,356.496 375.000,362.724 375.000 C 371.964 375.000,378.326 365.021,374.228 356.953 C 373.704 355.922,338.420 320.186,295.819 277.539 L 218.362 200.000 295.819 122.461 C 338.420 79.814,373.664 44.154,374.138 43.215 C 378.302 34.974,369.518 22.233,361.328 24.634 "
                                                                        stroke="none" fill="currentColor"
                                                                        fill-rule="evenodd">
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </span>
                                                @endforeach
                                            </span>
                                        </span>
                                    </div>

                                    {{-- Save & Cancel Buttons --}}
                                    <div
                                        class="flex items-center {{ $isEmpty ? 'justify-end' : 'justify-between' }} col-span-full gap-2 sm:gap-4">
                                        @if (!$isEmpty)
                                            <span
                                                class="flex flex-1 items-center justify-start font-medium border bg-red-100 border-red-300 text-red-950 rounded text-xs p-3 outline-none">
                                                <svg class="size-3.5 me-2" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                </svg>
                                                Some fields can only be editable if this project has no batches yet.
                                            </span>
                                        @endif

                                        <div class="flex items-center justify-center">
                                            <button type="submit" wire:click="$parent.editBatch();"
                                                class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                                SAVE
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2"
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

                                            <button type="button" wire:click.prevent="toggleEdit"
                                                wire:loading.attr="disabled" wire:target="toggleEdit"
                                                class="duration-200 ease-in-out flex flex-1 items-center justify-center ms-2 px-2 py-2.5 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50">
                                                CANCEL
                                                <svg class="size-2.5 ms-2" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                {{-- Edit Mode is OFF --}}
                                @if (!$edit)
                                    {{-- City/Municipality --}}
                                    <div class="relative flex flex-col mb-4">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            City/Municipality
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->city_municipality }}</span>
                                    </div>

                                    {{-- District --}}
                                    <div class="relative flex flex-col mb-4">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            District
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->district }}</span>
                                    </div>

                                    {{-- Edit/Trash Buttons OFF --}}
                                    <div x-data="{ batchDeleteModal: $wire.entangle('batchDeleteModal') }" class="flex justify-center items-center">
                                        <button type="button" wire:loading.attr="disabled" wire:target="toggleEdit"
                                            wire:click.prevent="toggleEdit"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                            EDIT
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        {{-- Delete/Trash Button --}}
                                        <button type="button"
                                            @if ($isEmpty) @click="batchDeleteModal = !batchDeleteModal;" @else disabled @endif
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border {{ $isEmpty ? 'border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50' : ' bg-gray-100 text-gray-300' }} ">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        <livewire:focal.implementations.confirmations.delete-batch-modal />
                                    </div>

                                    {{-- Batch Number OFF --}}
                                    <div class="relative flex flex-col mb-4">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Batch Number
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->batch_num }}</span>
                                    </div>

                                    {{-- Barangay OFF --}}
                                    <div class="relative flex flex-col mb-4">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Barangay
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->barangay_name }}</span>
                                    </div>

                                    {{-- Slots OFF --}}
                                    <div class="relative flex flex-col mb-4">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Alloted Slots
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->slots_allocated }}</span>
                                    </div>

                                    {{-- Assigned Coordinators OFF --}}
                                    <div class="relative flex flex-col mb-4 col-span-full">
                                        <p class="block mb-1 text-indigo-1100 mx-auto font-semibold">
                                            Assigned Coordinators <span
                                                class="px-2 py-1 rounded bg-indigo-100 text-indigo-700 ms-1 font-normal">{{ sizeof($this->assignedCoordinators) }}</span>
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-white text-indigo-700 font-medium overflow-x-scroll scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                            {{-- Toast Box of Coordinators --}}
                                            @foreach ($this->assignedCoordinators as $key => $assignedCoordinator)
                                                <span
                                                    class="py-1 px-2 me-2 rounded whitespace-nowrap duration-200 ease-in-out bg-indigo-100 text-indigo-700 font-medium">
                                                    {{ $this->getFullNameByFull($assignedCoordinator['first_name'], $assignedCoordinator['middle_name'], $assignedCoordinator['last_name'], $assignedCoordinator['extension_name']) }}
                                                </span>
                                            @endforeach
                                        </span>
                                    </div>

                                    {{-- Date created && Last updated --}}
                                    <div
                                        class="flex flex-col sm:flex-row items-center justify-between col-span-full gap-2 sm:gap-4">
                                        <div class="flex flex-1 items-center justify-center">
                                            <p class="font-bold text-indigo-1100">
                                                Date of Creation:
                                            </p>
                                            <span
                                                class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                                {{ date('M d, Y @ h:i:s a', strtotime($this->batch->created_at)) }}</span>
                                        </div>

                                        <div class="flex flex-1 items-center justify-center">
                                            <p class="font-bold text-indigo-1100">
                                                Last Updated:
                                            </p>
                                            <span
                                                class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                                {{ date('M d, Y @ h:i:s a', strtotime($this->batch->updated_at)) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
