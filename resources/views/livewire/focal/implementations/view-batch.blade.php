<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto backdrop-blur-sm z-50" x-show="viewBatchModal">

    <!-- Modal -->
    <div x-data="{
        accessCodeModal: $wire.entangle('accessCodeModal'),
        forceApproveModal: $wire.entangle('forceApproveModal'),
        pendBatchModal: $wire.entangle('pendBatchModal')
    }" x-show="viewBatchModal" x-trap.noscroll="viewBatchModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="relative size-full max-w-5xl">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                            View Batch

                        </h1>

                    </span>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading
                            wire:target="editBatch, liveUpdateRemainingSlots, toggleEditBatch, addToastCoordinator, removeToastCoordinator, deleteBatch, slots_allocated, barangay_name, district">
                            <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <button type="button" @click="$wire.resetViewBatch(); viewBatchModal = false;"
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
                @if ($passedBatchId)
                    <form wire:submit.prevent="editBatch" class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                            {{-- Edit Mode is ON --}}
                            @if ($editMode)

                                {{-- Batch Number --}}
                                <div class="flex flex-1 flex-col relative">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Batch Number
                                    </p>
                                    <span
                                        class="flex flex-1 text-xs rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->batch?->batch_num }}
                                    </span>
                                </div>

                                @if ($is_sectoral)
                                    {{-- Sector Title --}}
                                    <div class="flex flex-1 flex-col sm:col-span-2 relative">

                                        <label for="sector_title"
                                            class="flex items-center justify-between mb-1 font-medium text-indigo-1100 ">
                                            <p>
                                                <span class="relative">Sector Title
                                                    <span
                                                        class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                    </span>
                                                </span>
                                            </p>
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="sector_title" autocomplete="off"
                                                wire:model.live.debounce.300ms="sector_title"
                                                class="text-xs {{ $errors->has('sector_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                                placeholder="Type sector title">
                                        </div>
                                        @error('sector_title')
                                            <p class="mt-2 text-red-500 absolute left-2 top-full text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @else
                                    {{-- District --}}
                                    <div x-data="{ show: false, district: $wire.entangle('district') }" class="relative flex flex-col">
                                        @if ($isEmpty)
                                            <p class="block mb-1 font-medium text-indigo-1100 ">
                                                <span class="relative">District
                                                    <span
                                                        class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                    </span>
                                                </span>
                                            </p>

                                            {{-- District Button --}}
                                            <button type="button" id="district" @click="show = !show;"
                                                class="text-xs flex items-center justify-between px-4 {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                                <span x-text="district"></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3 duration-200 ease-in-out">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            {{-- District Dropdown --}}
                                            <div x-show="show" @click.away="if(show == true) { show = !show; }"
                                                class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-500 rounded p-3 mt-2">
                                                {{-- List of Districts --}}
                                                <ul
                                                    class="p-2 border border-indigo-300 rounded text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">
                                                    @forelse ($this->districts as $key => $dist)
                                                        <li wire:key={{ $key }}>
                                                            <button type="button"
                                                                @click="show = !show; district = '{{ $dist }}'; $wire.resetBarangays();"
                                                                wire:loading.attr="disabled"
                                                                aria-label="{{ __('Districts') }}"
                                                                class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $dist }}</button>
                                                        </li>
                                                    @empty
                                                        <div class="h-full w-full text-xs text-gray-500 p-2">
                                                            No districts found
                                                        </div>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            @error('district')
                                                <p class="text-red-500 absolute left-2 top-full text-xs">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        @else
                                            <p class="block mb-1 font-medium text-indigo-1100">
                                                District
                                            </p>
                                            <span
                                                class="flex flex-1 text-xs rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->district }}</span>
                                        @endif
                                    </div>

                                    {{-- Barangay --}}
                                    <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }" class="relative flex flex-col">
                                        @if ($isEmpty)
                                            <p class="block mb-1 font-medium text-indigo-1100 ">
                                                <span class="relative">Barangay
                                                    <span
                                                        class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                    </span>
                                                </span>
                                            </p>

                                            {{-- Barangay Button --}}
                                            <button type="button" id="barangay_name" @click="show = !show;"
                                                class="text-xs flex items-center justify-between px-4 {{ $errors->has('barangay_name') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                                @if ($barangay_name)
                                                    <span x-text="barangay_name"></span>
                                                @else
                                                    <span>Select a barangay...</span>
                                                @endif

                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3 duration-200 ease-in-out">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            {{-- Barangay Dropdown --}}
                                            <div x-show="show"
                                                @click.away=" 
                                                    if(show == true) 
                                                    { 
                                                        show = !show; 
                                                        $wire.set('searchBarangay', null);
                                                    }"
                                                class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-500 rounded p-3 mt-2">
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
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4">
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
                                                <ul
                                                    class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">
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
                                                <p class="text-red-500 absolute left-2 top-full text-xs">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        @else
                                            <p class="block mb-1 font-medium text-indigo-1100">
                                                Barangay
                                            </p>
                                            <span
                                                class="flex flex-1 text-xs rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->barangay_name }}</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Slots --}}
                                <div class="flex flex-1 flex-col relative">
                                    @if ($isEmpty)
                                        <label for="slots_allocated"
                                            class="flex items-center justify-between mb-1 font-medium text-indigo-1100 ">
                                            <p>
                                                <span class="relative">Allocated Slots
                                                    <span
                                                        class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                    </span>
                                                </span>
                                            </p>

                                            <span
                                                class="{{ $remainingSlots === 0 ? 'text-red-900 bg-red-200' : 'text-indigo-1000 bg-indigo-200' }} absolute -top-1 right-0 rounded-md pt-1 px-2 pb-2">
                                                <span class="font-normal">Remaining:</span>
                                                <span class="ps-0.5 font-semibold">{{ $remainingSlots }}
                                                </span>
                                            </span>

                                        </label>
                                        <div class="relative">
                                            <input type="number" min="0" id="slots_allocated"
                                                autocomplete="off" wire:model.live.debounce.300ms="slots_allocated"
                                                class="text-xs {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                                placeholder="Type slots allocation">
                                        </div>
                                        @error('slots_allocated')
                                            <p class="mt-2 text-red-500 absolute left-2 top-full text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Allocated Slots
                                        </p>
                                        <span
                                            class="flex flex-1 text-xs rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->slots_allocated }}</span>
                                    @endif
                                </div>

                                {{-- Coordinators --}}
                                <div x-data="{ show: false, currentCoordinator: $wire.entangle('currentCoordinator'), selectedCoordinatorKey: $wire.entangle('selectedCoordinatorKey') }" class="flex flex-col sm:col-span-2 relative">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        <span class="relative">Add Coordinator <span
                                                class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                            </span>
                                        </span>
                                    </p>

                                    <div class="flex items-center gap-6 relative h-full">

                                        {{-- Dropdown Button --}}
                                        <button type="button" id="coordinator_name" @click="show = !show;"
                                            class="size-full border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none text-xs px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                            <span x-text="currentCoordinator"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-3">
                                                <path fill-rule="evenodd"
                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        {{-- Add Coordinator button --}}
                                        <button type="button"
                                            @if ($this->coordinators->isNotEmpty()) wire:click="addToastCoordinator" @else disabled @endif
                                            class="p-2.5 rounded outline-none duration-200 ease-in-out
                                                    {{ $this->coordinators->isNotEmpty()
                                                        ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 focus:ring-indigo-700 focus:ring-2'
                                                        : 'text-gray-300 bg-gray-300' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M185.508 17.497 C 112.997 31.335,91.364 123.481,150.324 167.363 C 197.412 202.410,265.699 178.292,281.071 121.187 C 296.645 63.334,244.096 6.316,185.508 17.497 M148.828 217.621 C 91.057 226.723,48.389 277.005,50.178 333.878 C 50.910 357.134,62.844 373.715,84.375 381.392 L 89.453 383.203 157.609 383.423 C 233.023 383.666,229.021 383.926,231.535 378.627 C 233.606 374.264,233.085 371.831,227.714 360.768 C 216.193 337.036,214.139 312.579,221.575 287.660 C 227.224 268.732,238.842 251.751,255.079 238.691 C 267.401 228.781,267.383 220.875,255.034 218.143 C 247.176 216.405,159.291 215.973,148.828 217.621 M308.984 251.257 C 300.620 255.814,300.000 257.855,300.000 280.828 L 300.000 300.000 280.828 300.000 C 263.520 300.000,261.375 300.144,258.758 301.486 C 246.735 307.652,246.781 325.608,258.835 331.615 C 262.750 333.566,263.071 333.594,281.366 333.594 L 299.925 333.594 300.158 353.389 L 300.391 373.185 302.627 376.410 C 308.347 384.659,321.056 385.964,328.419 379.060 C 333.174 374.601,333.558 372.598,333.577 352.148 L 333.594 333.594 352.148 333.577 C 372.598 333.558,374.601 333.174,379.060 328.419 C 385.964 321.056,384.659 308.347,376.410 302.627 L 373.185 300.391 353.389 300.158 L 333.594 299.925 333.594 281.366 C 333.594 259.946,332.886 257.117,326.408 252.627 C 322.369 249.829,312.964 249.089,308.984 251.257 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        {{-- Dropdown Content --}}
                                        <div x-show="show"
                                            @click.away="
                                                        if(show == true) {
                                                        show = !show;
                                                        $wire.set('searchCoordinator', null);
                                                        }
                                                        "
                                            class="w-full min-w-[20rem] top-full right-0 z-50 absolute text-indigo-1100 bg-white shadow-lg border border-indigo-300 rounded p-3 mt-2">
                                            <div class="relative flex items-center justify-center py-1 group">
                                                {{-- Search Icon --}}
                                                <svg wire:loading.remove wire:target="searchCoordinator"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                    <path fill-rule="evenodd"
                                                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{-- Loading Icon --}}
                                                <svg wire:loading wire:target="searchCoordinator"
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
                                                {{-- Search Bar --}}
                                                <input id="searchCoordinator"
                                                    wire:model.live.debounce.300ms="searchCoordinator" type="text"
                                                    autocomplete="off"
                                                    class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                    placeholder="Search coordinator">
                                            </div>

                                            {{-- Available Coordinators List --}}
                                            <ul
                                                class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">

                                                @if ($this->coordinators->isNotEmpty())
                                                    @foreach ($this->coordinators as $key => $coordinator)
                                                        <li wire:key={{ $key }}>
                                                            <button type="button"
                                                                @click="show= !show; currentCoordinator = '{{ $this->getFullName($coordinator) }}'; selectedCoordinatorKey = {{ $key }};"
                                                                wire:loading.attr="disabled"
                                                                aria-label="{{ __('Coordinator') }}"
                                                                class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                                {{ $this->getFullName($coordinator) }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                @elseif($this->coordinators->isEmpty() && is_null($searchCoordinator))
                                                    <li>
                                                        <p
                                                            class="w-full flex items-center justify-start text-gray-500 px-4 py-2">
                                                            All coordinators were
                                                            assigned.</p>
                                                    </li>
                                                @else
                                                    <li>
                                                        <p
                                                            class="w-full flex items-center justify-start text-gray-500 px-4 py-2">
                                                            No coordinators found.</p>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Assigned Coordinators --}}
                                <div class="relative flex gap-4 col-span-full">

                                    {{-- Toast Area --}}
                                    <div class="relative flex flex-1 flex-col overflow-auto">
                                        <p class="block mb-1 text-indigo-1100 font-medium">
                                            Assigned Coordinators
                                        </p>
                                        <span
                                            class="flex gap-2 h-12 py-1.5 px-2 overflow-x-scroll rounded border {{ $errors->has('assigned_coordinators') ? 'border-red-300 bg-red-50 scrollbar-track-red-50 scrollbar-thumb-red-700' : 'border-indigo-300 scrollbar-track-indigo-50 scrollbar-thumb-indigo-700' }} scrollbar-thin">

                                            {{-- A Toast of Coordinators --}}
                                            @forelse ($assigned_coordinators as $key => $assignedCoordinator)
                                                <span
                                                    class="flex items-center gap-1 ps-2 rounded whitespace-nowrap duration-200 ease-in-out bg-indigo-200 text-indigo-1000">
                                                    {{ $this->getFullName($assignedCoordinator) }}

                                                    {{-- X button --}}
                                                    <button type="button"
                                                        wire:click="removeToastCoordinator({{ $key }})"
                                                        class="p-2 outline-none text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 duration-200 ease-in-out">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-2"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
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
                                            @empty
                                                <span class="text-gray-500">Added Coordinators will be shown
                                                    here!</span>
                                            @endforelse
                                        </span>
                                    </div>
                                    @error('assigned_coordinators')
                                        <p class="text-red-500 absolute left-16 top-full mt-1 text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror

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
                                            Some fields can only be editable if this
                                            batch has no beneficiaries yet.
                                        </span>
                                    @endif

                                    {{-- SAVE BUTTON --}}
                                    <div class="flex items-center justify-center">
                                        <button type="submit"
                                            class="flex flex-1 items-center justify-center gap-2 duration-200 ease-in-out px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                            SAVE
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
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

                                        {{-- X/Cancel Button --}}
                                        <button type="button" wire:click.prevent="toggleEditBatch"
                                            wire:loading.attr="disabled" wire:target="toggleEditBatch"
                                            class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-3 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50">

                                            <svg class="size-3.5" aria-hidden="true"
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
                            @if (!$editMode)

                                {{-- Batch Number OFF --}}
                                <div class="relative flex flex-col">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Batch Number
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->batch_num }}</span>
                                </div>

                                {{-- City/Municipality OFF --}}
                                <div class="relative flex flex-col">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        City/Municipality
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation?->city_municipality }}</span>
                                </div>

                                {{-- Edit/Delete Buttons OFF --}}
                                <div x-data="{ deleteBatchModal: $wire.entangle('deleteBatchModal') }" class="flex justify-center items-end">
                                    <button type="button" wire:loading.attr="disabled" wire:target="toggleEditBatch"
                                        @if ($this->batch?->approval_status !== 'approved') wire:click.prevent="toggleEditBatch" @else disabled @endif
                                        class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm disabled:border disabled:cursor-not-allowed disabled:border-gray-500 disabled:bg-gray-100 disabled:text-gray-500 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
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

                                    {{-- Delete/Trash Button --}}
                                    <button type="button"
                                        @if ($isEmpty) @click="deleteBatchModal = !deleteBatchModal;" @else disabled @endif
                                        class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
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

                                    {{-- Delete Batch Modal --}}
                                    <div x-cloak>
                                        <!-- Modal Backdrop -->
                                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                            x-show="deleteBatchModal">
                                        </div>

                                        <!-- Modal -->
                                        <div x-show="deleteBatchModal" x-trap.noscroll="deleteBatchModal"
                                            class="fixed inset-0 pt-4 px-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                            {{-- The Modal --}}
                                            <div class="relative w-full max-w-xl max-h-full">
                                                <div class="relative bg-white rounded-md shadow">
                                                    <!-- Modal Header -->
                                                    <div
                                                        class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                        <h1
                                                            class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                            Delete the Batch
                                                        </h1>

                                                        {{-- Close Button --}}
                                                        <button type="button" @click="deleteBatchModal = false;"
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
                                                            Are you sure about deleting
                                                            this batch?
                                                        </p>
                                                        <p class="text-gray-500 text-sm mb-4">
                                                            (This is action is
                                                            irreversible)
                                                        </p>
                                                        <div class="flex items-center justify-center w-full gap-2">
                                                            <button type="button" @click="deleteBatchModal = false;"
                                                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                                                CANCEL
                                                            </button>
                                                            <button type="button"
                                                                @click="$wire.deleteBatch(); deleteBatchModal = false;"
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

                                @if ($is_sectoral)
                                    {{-- Sector Title OFF --}}
                                    <div class="relative flex flex-col sm:col-span-2">
                                        <p class="mb-1 font-medium text-indigo-1100 ">
                                            Sector Title
                                        </p>
                                        <span
                                            class="min-w-0 flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium truncate">{{ $this->batch?->sector_title }}</span>
                                    </div>
                                @else
                                    {{-- District OFF --}}
                                    <div class="relative flex flex-col">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            District
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->district }}</span>
                                    </div>

                                    {{-- Barangay OFF --}}
                                    <div class="relative flex flex-col">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Barangay
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->barangay_name }}</span>
                                    </div>
                                @endif

                                {{-- Slots OFF --}}
                                <div class="relative flex flex-col">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Alloted Slots
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch?->slots_allocated }}</span>
                                </div>

                                {{-- Assigned Coordinators OFF --}}
                                <div class="relative flex flex-col col-span-full">
                                    <p class="block mb-3 text-indigo-1100 mx-auto font-semibold">
                                        Assigned Coordinators <span
                                            class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 ms-1 font-medium">{{ sizeof($this->assignedCoordinators) }}</span>
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 border border-indigo-100 text-indigo-700 font-medium overflow-x-scroll scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                        {{-- Toast Box of Coordinators --}}
                                        @foreach ($this->assignedCoordinators as $key => $assignedCoordinator)
                                            <span
                                                class="py-1 px-2 me-2 rounded whitespace-nowrap duration-200 ease-in-out bg-indigo-100 text-indigo-700 font-medium">
                                                {{ $this->getFullName($assignedCoordinator) }}
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
                                            {{ date('M d, Y @ h:i:s a', strtotime($this->batch?->created_at)) }}</span>
                                    </div>

                                    <div class="flex flex-1 items-center justify-center">
                                        <p class="font-bold text-indigo-1100">
                                            Last Updated:
                                        </p>
                                        <span
                                            class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ date('M d, Y @ h:i:s a', strtotime($this->batch?->updated_at)) }}</span>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div
                                    class="flex items-center justify-end gap-2 sm:gap-4 col-span-full relative text-sm font-bold">
                                    @if ($this->batch?->approval_status === 'pending')
                                        <button type="button"
                                            @if (!$this->isEmpty) @click="forceApproveModal = !forceApproveModal;"
                                        @else
                                        disabled @endif
                                            class="text-center px-3 py-1.5 duration-200 ease-in-out outline-none rounded-md disabled:bg-gray-300 disabled:text-gray-500 bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                            FORCE APPROVE
                                        </button>
                                    @elseif ($this->batch?->approval_status === 'approved')
                                        <button type="button" @click="pendBatchModal = !pendBatchModal;"
                                            class="text-center px-3 py-1.5 duration-200 ease-in-out outline-none rounded-md bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                            PEND BATCH
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </form>
                @endif

                {{-- Force Approve Modal --}}
                <div x-cloak>
                    <!-- Modal Backdrop -->
                    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                        x-show="forceApproveModal">
                    </div>

                    <!-- Modal -->
                    <div x-show="forceApproveModal" x-trap.noscroll="forceApproveModal"
                        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                        {{-- The Modal --}}
                        <div class="relative w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-md shadow">

                                <!-- Modal Header -->
                                <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">Force Approve Batch
                                    </h1>

                                    <div class="flex items-center justify-center">
                                        {{-- Loading State for Changes --}}
                                        <div class="z-50 text-indigo-900" wire:loading wire:target="forceApprove">
                                            <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>

                                        {{-- Close Modal --}}
                                        <button type="button" @click="forceApproveModal = false;"
                                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close Modal</span>
                                        </button>
                                    </div>
                                </div>

                                <hr class="">

                                {{-- Modal body --}}
                                <div
                                    class="flex flex-col items-center justify-center gap-4 w-full pt-5 pb-10 px-3 md:px-16 text-xs">

                                    <div class="flex flex-col items-center gap-1">
                                        <p class="font-medium text-sm ">
                                            Are you sure about force approving this batch?
                                        </p>
                                        <p class="font-normal text-xs text-gray-500">
                                            This will enable all batches to be modifiable to you and the coordinators.
                                        </p>
                                    </div>


                                    <div class="relative flex items-center justify-center w-full">
                                        <div class="flex items-center justify-center">
                                            <div class="relative me-2">
                                                <input type="password" id="password_force_approve"
                                                    wire:model.blur="password_force_approve"
                                                    class="flex flex-1 {{ $errors->has('password_force_approve') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'border-indigo-300 focus:border-indigo-500 bg-indigo-50' }} focus:ring-0 rounded outline-none border py-2.5 text-sm select-all duration-200 ease-in-out"
                                                    placeholder="Enter your password">
                                                @error('password_force_approve')
                                                    <p class="absolute top-full left-0 text-xs text-red-700">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                            <button wire:loading.attr="disabled" wire:target="forceApprove"
                                                class="flex items-center justify-center disabled:bg-red-300 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                                @click="$wire.forceApprove();">
                                                CONFIRM
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pend Batch Modal --}}
                <div x-cloak>
                    <!-- Modal Backdrop -->
                    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="pendBatchModal">
                    </div>

                    <!-- Modal -->
                    <div x-show="pendBatchModal" x-trap.noscroll="pendBatchModal"
                        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                        {{-- The Modal --}}
                        <div class="relative w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-md shadow">

                                <!-- Modal Header -->
                                <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">Set Batch to
                                        Pending
                                    </h1>

                                    <div class="flex items-center justify-center">
                                        {{-- Loading State for Changes --}}
                                        <div class="z-50 text-indigo-900" wire:loading wire:target="pendBatch">
                                            <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>

                                        {{-- Close Modal --}}
                                        <button type="button"
                                            @click="$wire.resetPasswords(); pendBatchModal = false;"
                                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close Modal</span>
                                        </button>
                                    </div>
                                </div>

                                <hr class="">

                                {{-- Modal body --}}
                                <div
                                    class="flex flex-col items-center justify-center gap-4 w-full pt-5 pb-10 px-3 md:px-16 text-xs">
                                    <div class="flex flex-col items-center gap-1">
                                        <p class="font-semibold text-sm ">
                                            Are you sure about setting this batch to pending?
                                        </p>
                                        <p class="font-normal text-xs text-gray-500">
                                            This will enable all batches to be modifiable to you and the coordinators.
                                        </p>
                                    </div>
                                    <div class="relative flex items-center justify-center w-full">
                                        <div class="flex items-center justify-center">
                                            <div class="relative me-2">
                                                <input type="password" id="password_pend_batch"
                                                    wire:model.blur="password_pend_batch"
                                                    class="flex {{ $errors->has('password_pend_batch') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'border-indigo-300 focus:border-indigo-500 bg-indigo-50' }} focus:ring-0 rounded outline-none border p-2.5 text-sm select-all duration-200 ease-in-out"
                                                    placeholder="Enter your password">
                                                @error('password_pend_batch')
                                                    <p class="absolute top-full left-0 text-xs text-red-700">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                            <button wire:loading.attr="disabled" wire:target="pendBatch"
                                                class="flex items-center justify-center disabled:bg-red-300 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                                @click="$wire.pendBatch();">
                                                CONFIRM
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
