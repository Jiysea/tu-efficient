<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="assignBatchesModal">

    <!-- Modal -->
    <div x-show="assignBatchesModal" x-trap.noscroll="assignBatchesModal"
        class="relative h-full p-4 flex items-start justify-center overflow-y-auto z-50 select-none">

        {{-- The Modal --}}
        <div class="w-full max-w-5xl">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t ">
                    <h1 class="text-lg font-semibold text-indigo-1100">
                        Assign and Create New Batches
                    </h1>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading
                            wire:target="is_sectoral, addBatchRow, editBatchRow, removeBatchRow, addToastCoordinator, removeToastCoordinatorFromBatchList, removeToastCoordinator, getAllCoordinatorsForBatchList, updateCurrentCoordinator, slots_allocated, district, barangay_name">
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

                        {{-- Close Modal --}}
                        <button type="button" @click="$wire.resetBatches(); assignBatchesModal = false;"
                            class="text-indigo-400 bg-transparent hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 inline-flex justify-center items-center outline-none duration-200 ease-in-out">
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

                {{-- Modal Body --}}
                @if ($implementationId)
                    <div class="py-3 px-6 text-indigo-1100 text-xs">
                        <!-- Headers -->
                        <div
                            class="w-full bg-indigo-50 px-4 py-3 mb-6 shadow-sm rounded-md flex items-center justify-between gap-2 flex-wrap text-indigo-1100 text-sm font-medium">
                            <div class="flex flex-col gap-2 sm:flex-row justify-center items-center">
                                <p class="">Project Number
                                <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                    {{ $this->implementation->project_num }}
                                </p>
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row justify-center items-center">
                                <p class="">City/Municipality
                                <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                    {{ $this->implementation->city_municipality }}</p>
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 w-full sm:w-auto sm:flex-row justify-center items-center">
                                <p class="">Remaining Slots
                                <p
                                    class="{{ $remainingSlots === 0 ? 'text-red-1000 bg-red-200' : 'text-indigo-1000 bg-indigo-200' }} rounded-md py-1 px-2">
                                    {{ $remainingSlots }}</p>
                                </p>
                            </div>
                        </div>

                        {{-- Body --}}
                        <form wire:submit.prevent="saveBatches">

                            <div class="grid gap-x-4 gap-y-6 grid-cols-9 text-xs">

                                {{-- Batch Number --}}
                                <div class="relative flex flex-col col-span-full md:col-span-3 lg:col-span-2">
                                    <label for="batch_num"
                                        class="relative flex items-center justify-between mb-1 font-medium text-indigo-1100 ">Batch
                                        Number
                                        <span
                                            class="absolute -top-1 right-0 bg-indigo-100 font-medium text-indigo-700 rounded px-2 pt-1 pb-2">prefix:
                                            <strong>{{ substr($batchNumPrefix ?? config('settings.project_number_prefix'), 0, strlen($batchNumPrefix ?? config('settings.project_number_prefix')) - 1) }}</strong>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input disabled type="number" id="batch_num" wire:model.blur="batch_num"
                                            autocomplete="off"
                                            class="text-xs z-10 disabled:bg-gray-50 disabled:placeholder-gray-700 disabled:text-gray-700 disabled:border-gray-300 {{ $errors->has('batch_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border block rounded w-full p-2.5 duration-200 ease-in-out"
                                            placeholder="Type batch number">

                                        {{-- Regenerator --}}
                                        <button type="button" wire:click="regenerateBatchNum"
                                            class="absolute right-0 top-0 flex items-center justify-center text-indigo-700 p-2.5">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" wire:loading.remove
                                                wire:target="regenerateBatchNum"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M184.034 33.711 C 114.672 39.820,55.552 90.170,38.271 157.852 C 34.351 173.206,40.480 183.520,53.558 183.577 C 62.787 183.617,68.089 178.632,71.401 166.797 C 102.421 55.968,246.260 30.959,309.547 125.391 L 314.259 132.422 296.387 132.813 C 279.134 133.190,278.395 133.271,275.035 135.156 C 263.607 141.568,263.607 158.432,275.035 164.844 L 278.516 166.797 316.797 166.797 L 355.078 166.797 358.768 164.628 C 361.161 163.221,363.221 161.161,364.628 158.768 L 366.797 155.078 366.797 116.797 L 366.797 78.516 364.844 75.035 C 358.432 63.608,341.534 63.608,335.171 75.035 C 333.490 78.054,333.179 79.796,332.827 88.179 L 332.422 97.841 327.228 91.694 C 292.487 50.579,238.897 28.879,184.034 33.711 M338.392 218.093 C 333.770 220.380,330.973 224.545,328.901 232.225 C 308.348 308.399,232.580 350.056,158.274 326.034 C 130.394 317.021,102.164 294.712,87.585 270.170 L 85.876 267.294 103.680 267.045 C 124.073 266.761,126.130 266.189,130.825 259.503 C 136.233 251.800,133.362 239.867,124.965 235.156 L 121.484 233.203 83.203 233.203 L 44.922 233.203 41.232 235.372 C 38.839 236.779,36.779 238.839,35.372 241.232 L 33.203 244.922 33.203 283.203 L 33.203 321.484 35.156 324.965 C 41.568 336.392,58.459 336.392,64.832 324.965 C 66.533 321.916,66.823 320.243,67.176 311.471 L 67.578 301.458 70.703 305.595 C 75.375 311.779,89.344 325.539,96.875 331.374 C 191.959 405.054,330.316 359.016,361.388 243.359 C 366.623 223.874,354.228 210.254,338.392 218.093 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                </g>
                                            </svg>

                                            {{-- Loading --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 animate-spin"
                                                wire:loading wire:target="regenerateBatchNum"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M184.034 33.711 C 114.672 39.820,55.552 90.170,38.271 157.852 C 34.351 173.206,40.480 183.520,53.558 183.577 C 62.787 183.617,68.089 178.632,71.401 166.797 C 102.421 55.968,246.260 30.959,309.547 125.391 L 314.259 132.422 296.387 132.813 C 279.134 133.190,278.395 133.271,275.035 135.156 C 263.607 141.568,263.607 158.432,275.035 164.844 L 278.516 166.797 316.797 166.797 L 355.078 166.797 358.768 164.628 C 361.161 163.221,363.221 161.161,364.628 158.768 L 366.797 155.078 366.797 116.797 L 366.797 78.516 364.844 75.035 C 358.432 63.608,341.534 63.608,335.171 75.035 C 333.490 78.054,333.179 79.796,332.827 88.179 L 332.422 97.841 327.228 91.694 C 292.487 50.579,238.897 28.879,184.034 33.711 M338.392 218.093 C 333.770 220.380,330.973 224.545,328.901 232.225 C 308.348 308.399,232.580 350.056,158.274 326.034 C 130.394 317.021,102.164 294.712,87.585 270.170 L 85.876 267.294 103.680 267.045 C 124.073 266.761,126.130 266.189,130.825 259.503 C 136.233 251.800,133.362 239.867,124.965 235.156 L 121.484 233.203 83.203 233.203 L 44.922 233.203 41.232 235.372 C 38.839 236.779,36.779 238.839,35.372 241.232 L 33.203 244.922 33.203 283.203 L 33.203 321.484 35.156 324.965 C 41.568 336.392,58.459 336.392,64.832 324.965 C 66.533 321.916,66.823 320.243,67.176 311.471 L 67.578 301.458 70.703 305.595 C 75.375 311.779,89.344 325.539,96.875 331.374 C 191.959 405.054,330.316 359.016,361.388 243.359 C 366.623 223.874,354.228 210.254,338.392 218.093 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('batch_num')
                                        <p class="mt-1 text-red-500 absolute left-2 top-full text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Sectoral or Non-Sectoral --}}
                                <div class="relative flex flex-col col-span-full md:col-span-3 lg:col-span-2">
                                    <span class="block mb-1 font-medium text-indigo-1100">Type of Batch</span>

                                    <span class="flex items-center justify-center gap-2">
                                        {{-- Sectoral --}}
                                        <label for="sectoral-radio"
                                            class="relative duration-200 ease-in-out cursor-pointer border border-transparent whitespace-nowrap flex flex-1 items-center justify-center p-2.5 rounded font-semibold {{ $is_sectoral ? 'bg-rose-700 hover:bg-rose-800 active:bg-rose-900 text-rose-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500 active:text-gray-600' }}">
                                            Sectoral
                                            <input type="radio" class="hidden absolute inset-0" id="sectoral-radio"
                                                value="1" wire:model.live="is_sectoral">
                                        </label>
                                        {{-- Non-Sectoral --}}
                                        <label for="non-sectoral-radio"
                                            class="relative duration-200 ease-in-out cursor-pointer border border-transparent whitespace-nowrap flex flex-1 items-center justify-center p-2.5 rounded font-semibold {{ !$is_sectoral ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500 active:text-gray-600' }}">
                                            Non-Sectoral
                                            <input type="radio" class="hidden absolute inset-0"
                                                id="non-sectoral-radio" value="0" wire:model.live="is_sectoral">
                                        </label>
                                    </span>
                                    @error('is_sectoral')
                                        <p class="text-red-500 absolute left-0 top-full mt-1 text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                @if ($is_sectoral)

                                    {{-- Sector Title --}}
                                    <div class="relative flex flex-col col-span-6 md:col-span-3 xl:col-span-4">
                                        <label for="sector_title" class="block mb-1 font-medium text-indigo-1100 ">
                                            <span class="relative">Sector Title
                                                <span
                                                    class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                </span>
                                            </span>
                                        </label>
                                        <input type="text" id="sector_title" wire:model.blur="sector_title"
                                            autocomplete="off"
                                            class="text-xs outline-none border block rounded w-full p-2.5 duration-200 ease-in-out {{ $errors->has('sector_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                            placeholder="Type sector title">
                                        @error('sector_title')
                                            <p class="mt-1 text-red-500 absolute left-2 top-full text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @elseif(!$is_sectoral)
                                    {{-- District --}}
                                    <div x-data="{ show: false, district: $wire.entangle('district') }"
                                        class="relative flex flex-col col-span-4 sm:col-span-3 lg:col-span-2">
                                        <p class="block mb-1 font-medium text-indigo-1100 ">
                                            <span class="relative">District
                                                <span
                                                    class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                </span>
                                            </span>
                                        </p>
                                        <button type="button" id="district" @click="show = !show;"
                                            class="text-xs flex items-center justify-between px-4 {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                            @if ($district)
                                                <span x-text="district"></span>
                                            @else
                                                <span>Select a district...</span>
                                            @endif

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-3 duration-200 ease-in-out">
                                                <path fill-rule="evenodd"
                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        {{-- District content --}}
                                        <div x-show="show" @click.away=" if(show == true) { show = !show; }"
                                            class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-300 rounded p-3 mt-2">
                                            <div
                                                class="text-xs overflow-y-auto min-h-44 max-h-44 border border-gray-300 rounded p-2">
                                                @forelse ($this->districts as $key => $dist)
                                                    <span wire:key={{ $key }}>
                                                        <button type="button"
                                                            @click="$wire.$set('district', '{{ $dist }}'); show = !show;"
                                                            wire:loading.attr="disabled"
                                                            aria-label="{{ __('Districts') }}"
                                                            class="text-left outline-none w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 focus:text-indigo-900 focus:bg-indigo-100 duration-200 ease-in-out">{{ $dist }}</button>
                                                    </span>
                                                @empty
                                                    <div
                                                        class="flex items-center justify-center size-full text-xs text-gray-500 p-2">
                                                        No districts found
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                        @error('district')
                                            <p class="mt-1 text-red-500 absolute left-2 top-full text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- Barangay --}}
                                    <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }"
                                        class="relative flex flex-col col-span-5 sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-2">
                                        <span class="block mb-1 font-medium"
                                            :class="{
                                                'text-gray-500': {{ json_encode(!isset($district) || empty($district)) }},
                                                'text-indigo-1100': {{ json_encode(!$errors->has('barangay_name') && $district) }},
                                            }">
                                            <span class="relative">Barangay
                                                <span
                                                    class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                </span>
                                            </span>
                                        </span>

                                        <button type="button" id="barangay_name"
                                            @if ($district) @click="if(show == true && $wire.searchBarangay !== null) { $wire.$set('searchBarangay', null); } show = !show;"
                                            @else
                                                disabled @endif
                                            class="text-xs whitespace-nowrap truncate flex items-center justify-between px-4 outline-none border rounded w-full py-2.5 duration-200 ease-in-out"
                                            :class="{
                                                'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600': {{ json_encode($errors->has('barangay_name')) }},
                                                'border-gray-300 bg-gray-50 text-gray-500': {{ json_encode(!isset($district) || empty($district)) }},
                                                'border-indigo-300 bg-indigo-50 focus:ring-indigo-600 focus:border-indigo-600 text-indigo-1100': {{ json_encode(!$errors->has('barangay_name') && $district) }},
                                            }">
                                            @if ($barangay_name)
                                                {{ $barangay_name }}
                                            @elseif(!$district)
                                                <span class="inline sm:hidden md:inline">Choose a district
                                                    first...</span>
                                                <span class="hidden sm:inline md:hidden">District first...</span>
                                            @else
                                                <span>Select a barangay...</span>
                                            @endif

                                            @if ($barangay_name || $district)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-3 duration-200 ease-in-out">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </button>

                                        {{-- Barangay Name content --}}
                                        <div x-show="show"
                                            @click.away="if(show == true) { show = !show; $wire.$set('searchBarangay', null); }"
                                            class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-600 rounded p-3 mt-2">

                                            {{-- Search Bar --}}
                                            <label for="searchBarangay"
                                                class="relative flex items-center justify-center duration-200 ease-in-out rounded border outline-none focus:ring-0
                                            {{ empty($this->barangays) && !$searchBarangay ? 'text-gray-500 border-gray-300' : 'text-gray-500 hover:text-indigo-700 focus-within:text-indigo-700 hover:bg-indigo-50 focus-within:bg-indigo-50 border-gray-300 hover:border-indigo-700 focus-within:border-indigo-700' }}">

                                                <div
                                                    class="absolute start-2 flex items-center justify-center pointer-events-none">
                                                    {{-- Loading Icon --}}
                                                    <svg class="size-4 animate-spin" wire:loading
                                                        wire:target="searchBarangay"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>

                                                    {{-- Search Icon --}}
                                                    <svg class="size-4" wire:loading.remove
                                                        wire:target="searchBarangay"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>

                                                <input id="searchBarangay" autocomplete="off"
                                                    wire:model.live.debounce.300ms="searchBarangay" type="text"
                                                    class="peer bg-transparent outline-none border-none focus:ring-0 rounded w-full ps-8 text-xs {{ empty($this->barangays) && !$searchBarangay ? 'placeholder-gray-500' : 'text-indigo-1100 placeholder-gray-500 hover:placeholder-indigo-700 focus:placeholder-indigo-700' }}"
                                                    placeholder="Search barangay"
                                                    @if (empty($this->barangays) && !$searchBarangay) disabled @endif>
                                            </label>

                                            {{-- List of Barangays --}}
                                            <div class="mt-2 text-xs overflow-y-auto h-44">
                                                @forelse ($this->barangays as $key => $barangay)
                                                    <span wire:key={{ $key }}>
                                                        <button type="button"
                                                            @click="$wire.$set('barangay_name', '{{ $barangay }}'); show = !show;"
                                                            wire:loading.attr="disabled"
                                                            aria-label="{{ __('Barangays') }}"
                                                            class="outline-none text-left w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 focus:text-indigo-900 focus:bg-indigo-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                                    </span>
                                                @empty
                                                    <div
                                                        class="flex items-center justify-center size-full text-xs text-gray-500 p-2">
                                                        No barangays found
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                        @error('barangay_name')
                                            <p class="mt-1 text-red-500 absolute left-2 top-full text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @endif

                                {{-- Slots --}}
                                <div
                                    class="relative {{ $is_sectoral ? 'col-span-3' : 'col-span-full' }} sm:col-span-3 md:col-span-2 lg:col-span-2 xl:col-span-1">
                                    <label for="slots_allocated" class="block mb-1 font-medium text-indigo-1100 ">
                                        <span class="relative">Slots
                                            <span
                                                class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                            </span>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" min="0" id="slots_allocated" autocomplete="off"
                                            wire:model.live.debounce.300ms="slots_allocated"
                                            class="text-xs {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                            placeholder="Type slots">
                                    </div>
                                    @error('slots_allocated')
                                        <p class="mt-1 text-red-500 absolute left-2 top-full text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Add Coordinators dropdown --}}
                                <div x-data="{ show: false, currentCoordinator: $wire.entangle('currentCoordinator'), selectedCoordinatorKey: $wire.entangle('selectedCoordinatorKey') }"
                                    class="relative flex flex-col col-span-full {{ $is_sectoral ? 'md:col-span-7 lg:col-span-full' : 'md:col-span-5 lg:col-span-7' }} xl:col-span-5">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Add Coordinator
                                    </p>
                                    <div class="relative flex gap-4 h-full">

                                        {{-- Current Coordinator --}}
                                        <button type="button" id="coordinator_name"
                                            @click="if(show == true && $wire.searchCoordinator !== null) { $wire.$set('searchCoordinator', null); } show = !show;"
                                            class="gap-3 size-full whitespace-nowrap truncate border text-xs xl:text-sm bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                            <span x-text="currentCoordinator"></span>

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-4">
                                                <path fill-rule="evenodd"
                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        {{-- Arrow/Add button --}}
                                        <button type="button"
                                            @if ($currentCoordinator !== '-') wire:click="addToastCoordinator" @else disabled @endif
                                            class="flex items-center justify-center px-2 rounded outline-none duration-200 ease-in-out
                                                {{ $currentCoordinator !== '-'
                                                    ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 focus:ring-indigo-700 focus:ring-2'
                                                    : 'bg-gray-200 text-gray-500' }}">

                                            {{-- Add Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="xl:hidden size-6"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M185.508 17.497 C 112.997 31.335,91.364 123.481,150.324 167.363 C 197.412 202.410,265.699 178.292,281.071 121.187 C 296.645 63.334,244.096 6.316,185.508 17.497 M148.828 217.621 C 91.057 226.723,48.389 277.005,50.178 333.878 C 50.910 357.134,62.844 373.715,84.375 381.392 L 89.453 383.203 157.609 383.423 C 233.023 383.666,229.021 383.926,231.535 378.627 C 233.606 374.264,233.085 371.831,227.714 360.768 C 216.193 337.036,214.139 312.579,221.575 287.660 C 227.224 268.732,238.842 251.751,255.079 238.691 C 267.401 228.781,267.383 220.875,255.034 218.143 C 247.176 216.405,159.291 215.973,148.828 217.621 M308.984 251.257 C 300.620 255.814,300.000 257.855,300.000 280.828 L 300.000 300.000 280.828 300.000 C 263.520 300.000,261.375 300.144,258.758 301.486 C 246.735 307.652,246.781 325.608,258.835 331.615 C 262.750 333.566,263.071 333.594,281.366 333.594 L 299.925 333.594 300.158 353.389 L 300.391 373.185 302.627 376.410 C 308.347 384.659,321.056 385.964,328.419 379.060 C 333.174 374.601,333.558 372.598,333.577 352.148 L 333.594 333.594 352.148 333.577 C 372.598 333.558,374.601 333.174,379.060 328.419 C 385.964 321.056,384.659 308.347,376.410 302.627 L 373.185 300.391 353.389 300.158 L 333.594 299.925 333.594 281.366 C 333.594 259.946,332.886 257.117,326.408 252.627 C 322.369 249.829,312.964 249.089,308.984 251.257 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            {{-- Arrow Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="hidden xl:inline size-6"
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

                                        {{-- Coordinator List Dropdown Content --}}
                                        <div x-show="show"
                                            @click.away="if(show == true) { show = !show; $wire.$set('searchCoordinator', null); }"
                                            class="z-50 w-full end-0 top-full absolute bg-white text-indigo-1100 shadow-lg border border-indigo-300 rounded p-3 mt-2">

                                            {{-- Search Bar --}}
                                            <label for="searchCoordinator"
                                                class="relative flex items-center justify-center duration-200 ease-in-out rounded border outline-none focus:ring-0
                                                {{ $this->coordinators->isEmpty() && !$searchCoordinator ? 'text-gray-500 border-gray-300' : 'text-gray-500 hover:text-indigo-700 focus-within:text-indigo-700 hover:bg-indigo-50 focus-within:bg-indigo-50 border-gray-300 hover:border-indigo-700 focus-within:border-indigo-700' }}">

                                                <div
                                                    class="absolute start-2 flex items-center justify-center pointer-events-none">
                                                    {{-- Loading Icon --}}
                                                    <svg class="size-4 animate-spin" wire:loading
                                                        wire:target="searchCoordinator"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>

                                                    {{-- Search Icon --}}
                                                    <svg class="size-4" wire:loading.remove
                                                        wire:target="searchCoordinator"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>

                                                <input id="searchCoordinator" autocomplete="off"
                                                    wire:model.live.debounce.300ms="searchCoordinator" type="text"
                                                    class="peer bg-transparent outline-none border-none focus:ring-0 rounded w-full ps-8 text-xs {{ $this->coordinators->isEmpty() && !$searchCoordinator ? 'placeholder-gray-500' : 'text-indigo-1100 placeholder-gray-500 hover:placeholder-indigo-700 focus:placeholder-indigo-700' }}"
                                                    placeholder="Search coordinator"
                                                    @if ($this->coordinators->isEmpty() && !$searchCoordinator) disabled @endif>
                                            </label>

                                            {{-- Coordinator List --}}
                                            <div
                                                class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-900">
                                                @if ($this->coordinators->isNotEmpty())
                                                    @foreach ($this->coordinators as $key => $coordinator)
                                                        <span wire:key={{ $key }}>
                                                            <button type="button"
                                                                @click="show= !show; currentCoordinator = '{{ $this->getFullName($coordinator) }}'; selectedCoordinatorKey = {{ $key }};"
                                                                wire:loading.attr="disabled"
                                                                aria-label="{{ __('Coordinator') }}"
                                                                class="text-left outline-none w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 focus:text-indigo-900 focus:bg-indigo-100 duration-200 ease-in-out">
                                                                {{ $this->getFullName($coordinator) }}
                                                            </button>
                                                        </span>
                                                    @endforeach
                                                @elseif ($this->coordinators->isEmpty() && !is_null($searchCoordinator))
                                                    <div
                                                        class="flex items-center justify-center size-full text-gray-500 font-medium">
                                                        <p class="">
                                                            No coordinators found.</p>
                                                    </div>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center size-full text-gray-500 font-medium">
                                                        <p class="">
                                                            All coordinators were assigned.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Assigned Coordinators toast box --}}
                                <div class="relative grid grid-cols-5 flex-grow col-span-full xl:col-span-4">
                                    <div class="relative col-span-full">
                                        <p class="block mb-1 font-medium text-indigo-1100 ">
                                            <span class="relative">Assigned Coordinators
                                                <span
                                                    class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                </span>
                                            </span>
                                        </p>
                                        <input type="hidden" id="assigned_coordinators"
                                            wire:model.blur="assigned_coordinators">
                                        <div class="relative flex">

                                            <div class="flex items-center gap-2 text-xs border rounded w-full px-2 py-2 h-[3.2rem] duration-200 ease-in-out overflow-x-scroll whitespace-nowrap scrollbar-thin"
                                                :class="{
                                                    'border-red-500 bg-red-200 placeholder-red-600 scrollbar-thumb-red-600 scrollbar-track-red-200': {{ json_encode($errors->has('assigned_coordinators')) }},
                                                    'bg-indigo-50 border-indigo-300 scrollbar-thumb-indigo-700 scrollbar-track-indigo-50': {{ json_encode($assigned_coordinators) }},
                                                    'bg-gray-100 border-gray-400 scrollbar-thumb-gray-700 scrollbar-track-gray-100': {{ json_encode(!$assigned_coordinators) }},
                                                }">
                                                @forelse ($assigned_coordinators as $key => $coordinator)
                                                    <span
                                                        class="flex items-center gap-1 ps-1 rounded duration-200 ease-in-out bg-indigo-200 text-indigo-800 font-medium">
                                                        {{ $this->getFullName($coordinator) }}

                                                        {{-- X button --}}
                                                        <button type="button"
                                                            wire:click="removeToastCoordinator({{ $key }})"
                                                            class="py-1.5 px-1 rounded-r text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 duration-200 ease-in-out">
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
                                                @empty
                                                    <p
                                                        class="ms-1 {{ $errors->has('assigned_coordinators') ? 'text-red-600' : 'text-gray-500' }} ">
                                                        Added coordinators will be shown here!</p>
                                                @endforelse
                                            </div>
                                            @error('assigned_coordinators')
                                                <p class="mt-1 text-red-500 absolute left-2 top-full text-xs">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                            {{-- Adding Batch button --}}
                                            <button type="button" wire:click="addBatchRow"
                                                class="flex items-center justify-center space-x-2 text-sm py-2 px-3 whitespace-nowrap rounded ms-4 font-bold bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 focus:ring-indigo-300 focus:ring-4 text-indigo-50 focus:outline-none duration-200 ease-in-out">
                                                <p>ADD BATCH</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M190.042 1.099 C 179.604 4.492,171.157 13.956,168.847 24.843 C 168.234 27.731,167.969 49.998,167.969 98.476 L 167.969 167.969 98.476 167.969 C 23.788 167.969,24.011 167.958,16.162 172.095 C -5.399 183.460,-5.399 216.540,16.162 227.905 C 24.011 232.042,23.788 232.031,98.476 232.031 L 167.969 232.031 167.969 301.524 C 167.969 376.212,167.958 375.989,172.095 383.838 C 183.460 405.399,216.540 405.399,227.905 383.838 C 232.042 375.989,232.031 376.212,232.031 301.524 L 232.031 232.031 301.524 232.031 C 376.212 232.031,375.989 232.042,383.838 227.905 C 405.399 216.540,405.399 183.460,383.838 172.095 C 375.989 167.958,376.212 167.969,301.524 167.969 L 232.031 167.969 232.031 98.476 C 232.031 23.788,232.042 24.011,227.905 16.162 C 221.235 3.509,203.873 -3.399,190.042 1.099 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Temporary Batches List --}}
                                @if ($temporaryBatchesList)

                                    <div class="flex col-span-full flex-col gap-2 w-full">
                                        {{-- Label --}}
                                        <span
                                            class="relative col-span-full font-semibold text-base text-indigo-1100 ms-2">
                                            Batch List
                                        </span>

                                        {{-- Batch List Table --}}
                                        <div
                                            class="relative min-h-[12.375rem] max-h-[12.375rem] overflow-y-auto bg-indigo-50 border border-indigo-300 rounded-md whitespace-nowrap scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">

                                            <table
                                                class="relative w-full text-sm text-left text-indigo-1100 rounded-md">
                                                <thead
                                                    class="text-xs text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                                    <tr>

                                                        <th scope="col" class="ps-2 py-2">

                                                        </th>
                                                        <th scope="col" class="pe-2 py-2">
                                                            batch number
                                                        </th>
                                                        <th scope="col" class="px-2 py-2">
                                                            barangay / sector
                                                        </th>
                                                        <th scope="col" class="px-2 py-2 text-center">
                                                            type
                                                        </th>
                                                        <th scope="col" class="px-2 py-2 text-center">
                                                            slots
                                                        </th>
                                                        <th scope="col" class="px-2 py-2">
                                                            coordinator/s
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs relative">
                                                    @foreach ($temporaryBatchesList as $keyBatch => $batch)
                                                        <tr wire:key='batch-{{ $keyBatch }}'
                                                            class="relative border-b {{ $selectedBatchListRow === $keyBatch ? 'bg-indigo-100' : 'bg-indigo-50' }} whitespace-nowrap duration-200 ease-in-out">
                                                            <th scope="row"
                                                                class="ps-2 py-2 flex justify-end items-center">
                                                                {{-- X button (table list) --}}
                                                                <button type="button"
                                                                    wire:click="removeBatchRow({{ $keyBatch }})"
                                                                    class="p-1 me-3 rounded-md text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 bg-transparent hover:bg-indigo-300 duration-200 ease-in-out">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="size-4"
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
                                                            </th>
                                                            <td
                                                                class="pe-2 py-2 font-medium text-indigo-1100 whitespace-nowrap">
                                                                {{ $batch['batch_num'] }}
                                                            </td>
                                                            <td
                                                                class="px-2 py-2 max-w-[200px] overflow-x-auto whitespace-nowrap scrollbar-none select-text">
                                                                {{ $batch['sector_title'] ?? $batch['barangay_name'] }}
                                                            </td>
                                                            <td class="px-2 py-1 text-center">
                                                                <span
                                                                    class="flex items-center justify-center px-3 py-1 rounded-full font-semibold {{ $batch['is_sectoral'] ? 'bg-rose-200 text-rose-900' : 'bg-indigo-200 text-indigo-900' }}">
                                                                    {{ $batch['is_sectoral'] ? 'Sectoral' : 'Non-Sectoral' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-2 py-2 text-center">
                                                                {{ $batch['slots_allocated'] }}
                                                            </td>
                                                            <td class="grid-flow-row">
                                                                @foreach ($batch['assigned_coordinators'] as $keyCoordinator => $coordinator)
                                                                    <span
                                                                        class="p-1 mx-1 rounded duration-200 ease-in-out {{ $selectedBatchListRow === $keyBatch ? 'bg-green-300 text-green-1000' : 'bg-indigo-300 text-indigo-1000' }}">
                                                                        {{ $this->getFullName($coordinator) }}
                                                                    </span>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                @else
                                    {{-- Shows up on initial modal open or when there's no batches created yet --}}
                                    <div
                                        class="relative flex flex-col items-center justify-center col-span-full bg-white h-52 min-w-full">
                                        <div
                                            class="relative flex flex-col items-center justify-center gap-4 border rounded size-full font-medium text-sm duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-gray-500 bg-red-50 border-red-300' : 'text-gray-500 bg-gray-50 border-gray-300' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-12 sm:size-20 duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-400' : 'text-gray-400' }}"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            <p class="text-center">No batches found. <br>
                                                <span>Try creating a
                                                    <span
                                                        class="duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-700' : 'text-indigo-900' }} ">
                                                        new batch
                                                    </span>.
                                                </span>
                                            </p>
                                        </div>
                                        @error('temporaryBatchesList')
                                            <div class="absolute top-full flex items-center justify-center mt-1 w-full">
                                                <p class="text-red-500 text-xs">
                                                    {{ $message }}
                                                </p>
                                            </div>
                                        @enderror
                                    </div>
                                @endif

                                {{-- Modal footer --}}
                                <div class="col-span-full w-full flex items-center justify-end">

                                    {{-- Finish Button --}}
                                    <button type="submit" wire:loading.attr="disabled" wire:target="saveBatches"
                                        class="flex items-center text-center gap-2 py-2.5 px-3 outline-none font-bold text-sm rounded duration-200 ease-in-out text-indigo-50 bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300">
                                        <p>FINISH</p>

                                        <svg class="size-4 animate-spin" wire:loading wire:target="saveBatches"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>

                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" wire:loading.remove
                                            wire:target="saveBatches" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="400" height="400" viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M176.222 16.066 C 28.153 35.847,-39.558 211.481,57.248 324.669 C 157.007 441.310,349.713 393.836,383.125 244.388 C 411.601 117.016,304.582 -1.082,176.222 16.066 M301.850 131.509 C 305.728 134.467,307.570 139.619,306.306 143.971 C 305.319 147.369,169.764 284.375,167.389 284.375 C 166.285 284.375,96.190 214.001,94.754 211.451 C 90.790 204.410,96.950 194.541,105.312 194.534 C 110.533 194.530,111.285 195.163,139.058 222.996 C 159.505 243.486,165.653 249.219,167.181 249.219 C 168.729 249.219,181.425 236.938,228.123 190.269 C 260.566 157.846,288.164 130.758,289.453 130.072 C 292.834 128.275,298.465 128.927,301.850 131.509 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
