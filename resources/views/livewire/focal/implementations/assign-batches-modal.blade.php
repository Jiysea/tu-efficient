<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="assignBatchesModal">
    </div>

    <!-- Modal -->
    <div x-show="assignBatchesModal" x-trap.noscroll="assignBatchesModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

        {{-- The Modal --}}
        <div class="relative w-full max-w-5xl max-h-full">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t ">
                    <h1 class="text-lg font-semibold text-indigo-1100">
                        Assign and Create New Batches
                    </h1>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading
                            wire:target="addBatchRow, editBatchRow, removeBatchRow, addToastCoordinator, removeToastCoordinatorFromBatchList, removeToastCoordinator, getAllCoordinatorsForBatchList, updateCurrentCoordinator, slots_allocated, barangay_name">
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
                    <form wire:submit.prevent="saveBatches" class="py-3 px-6 text-indigo-1100 text-xs">
                        <div class="grid gap-4 grid-cols-5 text-xs">
                            <!-- Headers -->
                            <div
                                class="col-span-full bg-indigo-50 px-4 py-3 shadow-sm rounded-md flex items-center justify-between text-indigo-1100 text-sm font-medium">
                                <div
                                    class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                                    <p class="">Project Number:
                                    <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                        {{ $this->implementation->project_num }}
                                    </p>
                                    </p>
                                </div>
                                <div
                                    class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                                    <p class="">City/Municipality:
                                    <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                        {{ $this->implementation->city_municipality }}</p>
                                    </p>
                                </div>
                                <div
                                    class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                                    <p class="">District:
                                    <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                        {{ $this->implementation->district }}
                                    </p>
                                    </p>
                                </div>
                                <div
                                    class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center duration-200 ease-in-out">
                                    <p class="">Remaining Slots:
                                    <p
                                        class="{{ $remainingSlots === 0 ? 'text-red-1000 bg-red-200' : 'text-indigo-1000 bg-indigo-200' }} rounded-md py-1 px-2">
                                        {{ $remainingSlots }}</p>
                                    </p>
                                </div>
                            </div>

                            {{-- Batch Number --}}
                            <div class="relative col-span-5 sm:col-span-2 mb-4">
                                <label for="batch_num" class="block mb-1  font-medium text-indigo-1100 ">Batch
                                    Number <span class="text-red-700 font-normal text-xs">*</span><span
                                        class="text-gray-500 ms-2">prefix:
                                        {{ substr($batchNumPrefix ?? config('settings.batch_number_prefix'), 0, strlen($batchNumPrefix ?? config('settings.batch_number_prefix')) - 1) }}</span></label>
                                <input type="number" id="batch_num" wire:model.blur="batch_num" autocomplete="off"
                                    class="text-xs {{ $errors->has('batch_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full p-2.5 duration-200 ease-in-out"
                                    placeholder="Type batch number">
                                @error('batch_num')
                                    <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Barangay Name dropdown --}}
                            <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }" class="relative flex flex-col col-span-3 sm:col-span-2 mb-4">
                                <p class="block mb-1 font-medium text-indigo-1100 ">Barangay
                                    <span class="text-red-700 font-normal text-xs">*</span>
                                </p>
                                <button type="button" id="barangay_name" @click="show = !show;"
                                    class="text-xs flex items-center justify-between px-4 {{ $errors->has('barangay_name') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                    @if ($barangay_name)
                                        <span x-text="barangay_name"></span>
                                    @else
                                        <span>Select a barangay...</span>
                                    @endif

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-3 duration-200 ease-in-out">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Barangay Name content --}}
                                <div x-show="show" @click.away=" if(show == true) { show = !show; }"
                                    class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-100 rounded p-3 mt-2">
                                    <div class="relative flex items-center justify-center py-1 group">
                                        <svg wire:loading.remove wire:target="searchBarangay"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                            <path fill-rule="evenodd"
                                                d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <svg wire:loading wire:target="searchBarangay"
                                            class="absolute start-0 ms-2 size-4 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <input id="searchBarangay" wire:model.live.debounce.300ms="searchBarangay"
                                            type="text" autocomplete="off"
                                            class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                            placeholder="Search barangay">
                                    </div>
                                    <ul class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44">
                                        @forelse ($this->barangays as $key => $barangay)
                                            <li wire:key={{ $key }}>
                                                <button type="button"
                                                    @click="show = !show; barangay_name = '{{ $barangay }}'; $wire.$refresh();"
                                                    wire:loading.attr="disabled" aria-label="{{ __('Barangays') }}"
                                                    class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                            </li>
                                        @empty
                                            <div class="h-full w-full text-xs text-gray-500 p-2">
                                                Empty Set
                                            </div>
                                        @endforelse
                                    </ul>
                                </div>
                                @error('barangay_name')
                                    <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Slots --}}
                            <div class="relative col-span-2 sm:col-span-1 mb-4">
                                <label for="slots_allocated" class="block mb-1 font-medium text-indigo-1100 ">Slots
                                    <span class="text-red-700 font-normal text-xs">*</span></label>
                                <div class="relative">
                                    <input type="number" min="0" id="slots_allocated" autocomplete="off"
                                        wire:model.live.debounce.300ms="slots_allocated"
                                        class="text-xs {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                        placeholder="Type slots allocation">
                                </div>
                                @error('slots_allocated')
                                    <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Add Coordinators dropdown --}}
                            <div x-data="{ show: false, currentCoordinator: $wire.entangle('currentCoordinator'), selectedCoordinatorKey: $wire.entangle('selectedCoordinatorKey') }" class="relative flex flex-col col-span-5 sm:col-span-2 mb-4">
                                <p class="block mb-1 font-medium text-indigo-1100 ">Add
                                    Coordinator <span class="text-red-700 font-normal text-xs">*</span></p>
                                <div class="relative z-50 h-full">

                                    {{-- Current Coordinator --}}
                                    <button type="button" id="coordinator_name"
                                        @click="show = !show; $wire.set('searchCoordinator', null);"
                                        class="w-full h-full border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none text-sm px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                        <span x-text="currentCoordinator"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-4 ms-3 duration-200 ease-in-out">
                                            <path fill-rule="evenodd"
                                                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    {{-- Coordinator List Dropdown Content --}}
                                    <div x-show="show"
                                        @click.away="
                                            if(show == true) 
                                            {
                                                show = !show;
                                                $wire.set('searchCoordinator', null);
                                            }
                                            "
                                        class="w-full end-0 absolute text-indigo-1100 bg-indigo-50 shadow-lg border border-indigo-300 rounded p-3 mt-2">
                                        <div class="relative flex items-center justify-center py-1 group">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="absolute start-0 ps-2 w-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                <path fill-rule="evenodd"
                                                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <input id="searchCoordinator"
                                                wire:model.live.debounce.300ms="searchCoordinator" type="text"
                                                class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                placeholder="Search coordinator">
                                        </div>
                                        <ul
                                            class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-900">
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
                                            @elseif ($this->coordinators->isEmpty() && !is_null($this->searchCoordinator))
                                                <li>
                                                    <p
                                                        class="text-gray-500 font-medium px-4 py-2 w-full flex items-center justify-start">
                                                        No coordinators found.</p>
                                                </li>
                                            @else
                                                <li>
                                                    <p
                                                        class="text-gray-500 font-medium px-4 py-2 w-full flex items-center justify-start">
                                                        All coordinators were assigned.</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            {{-- Assigned Coordinators toast box --}}
                            <div class="relative grid grid-cols-5 flex-grow col-span-5 sm:col-span-3 mb-4">
                                <div class="relative col-span-5">
                                    <p class="block mb-1 ms-16 font-medium text-indigo-1100 ">
                                        Assigned Coordinators</p>
                                    <input type="hidden" id="assigned_coordinators"
                                        wire:model.blur="assigned_coordinators">
                                    <div class="relative flex">

                                        {{-- Arrow/Add button --}}
                                        <button type="button"
                                            @if ($this->coordinators->isNotEmpty()) wire:click="addToastCoordinator" @else disabled @endif
                                            class="me-4 px-2 flex items-center justify-center rounded border-2 outline-none duration-200 ease-in-out
                                                        {{ $this->coordinators->isNotEmpty()
                                                            ? 'text-indigo-700 hover:text-indigo-50 active:text-indigo-300 border-indigo-700 focus:ring-indigo-700 focus:ring-2 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900'
                                                            : 'text-gray-300 border-gray-300' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
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
                                        <div
                                            class="text-xs border rounded w-full ps-2 py-2.5 duration-200 ease-in-out overflow-x-scroll whitespace-nowrap scrollbar-thin 
                                                    
                                                    @if ($errors->has('assigned_coordinators')) border-red-500 bg-red-200 placeholder-red-600 scrollbar-thumb-red-600 scrollbar-track-red-200
                                                    @else
                                                    {{ $assigned_coordinators ? 'bg-indigo-50 border-indigo-300 scrollbar-thumb-indigo-700 scrollbar-track-indigo-50' : 'bg-gray-100 border-gray-400 scrollbar-thumb-gray-700 scrollbar-track-gray-100' }} @endif">
                                            @forelse ($assigned_coordinators as $key => $coordinator)
                                                <span
                                                    class="p-1 me-2 rounded duration-200 ease-in-out bg-indigo-200 text-indigo-800 font-medium">
                                                    {{ $this->getFullName($coordinator) }}
                                                    {{-- X button --}}
                                                    <button type="button"
                                                        wire:click="removeToastCoordinator({{ $key }})"
                                                        class="ms-1 text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 duration-200 ease-in-out">
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
                                                <p
                                                    class="ms-1 {{ $errors->has('assigned_coordinators') ? 'text-red-600' : 'text-gray-500' }} ">
                                                    Added coordinators will be shown here!</p>
                                            @endforelse
                                        </div>
                                        @error('assigned_coordinators')
                                            <p class="ms-14 mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                        {{-- Adding Batch button --}}
                                        <button type="button" wire:click="addBatchRow"
                                            class="flex items-center justify-center space-x-2 text-sm py-2 px-3 whitespace-nowrap rounded ms-4 font-bold bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 focus:ring-indigo-300 focus:ring-4 text-indigo-50 focus:outline-none duration-200 ease-in-out">
                                            <p>CREATE BATCH</p>
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

                                {{-- Label --}}
                                <div class="relative col-span-5 font-semibold text-base text-indigo-1100 ms-2">
                                    Batch List
                                </div>

                                {{-- Batch List Table --}}
                                <div
                                    class="relative col-span-5 min-h-[12.375rem] max-h-[12.375rem] overflow-y-auto bg-indigo-50 border border-indigo-300 rounded-md whitespace-nowrap scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">

                                    <table class="relative w-full text-sm text-left text-indigo-1100 rounded-md">
                                        <thead
                                            class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0 ">
                                            <tr>
                                                <th scope="col" class="ps-4 py-2">
                                                    batch number
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    barangay
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    coordinator/s
                                                </th>
                                                <th scope="col" class="px-2 py-2 text-center">
                                                    slots
                                                </th>
                                                <th scope="col" class="px-2 py-2">

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs relative">
                                            @foreach ($temporaryBatchesList as $keyBatch => $batch)
                                                <tr wire:key='batch-{{ $keyBatch }}'
                                                    class="relative border-b {{ $selectedBatchListRow === $keyBatch ? 'bg-indigo-100' : 'bg-indigo-50' }} whitespace-nowrap duration-200 ease-in-out">
                                                    <th scope="row"
                                                        class="z-0 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap">
                                                        {{ $batch['batch_num'] }}
                                                    </th>
                                                    <td class="px-2 py-2">
                                                        {{ $batch['barangay_name'] }}
                                                    </td>
                                                    <td class="grid-flow-row">
                                                        @foreach ($batch['assigned_coordinators'] as $keyCoordinator => $coordinator)
                                                            <span
                                                                class="p-1 mx-1 rounded duration-200 ease-in-out {{ $selectedBatchListRow === $keyBatch ? 'bg-green-300 text-green-1000' : 'bg-indigo-300 text-indigo-1000' }}">
                                                                {{ $this->getFullName($coordinator) }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    <td class="px-2 py-2 text-center">
                                                        {{ $batch['slots_allocated'] }}
                                                    </td>
                                                    <td class="py-2 flex justify-end items-center">

                                                        {{-- X button (table list) --}}
                                                        <button type="button"
                                                            wire:click="removeBatchRow({{ $keyBatch }})"
                                                            class="p-1 me-3 rounded-md text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 bg-transparent hover:bg-indigo-300 duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                {{-- Shows up on initial modal open or when there's no batches created yet --}}
                                <div
                                    class="relative col-span-5 bg-white pb-4 pt-2 h-60 min-w-full flex flex-col items-center justify-center">
                                    <div
                                        class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-gray-500 bg-red-50 border-red-300' : 'text-gray-500 bg-gray-50 border-gray-300' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-12 sm:size-20 mb-4 duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-500' : 'text-gray-500' }}"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <p>No batches found.</p>
                                        <p>Try creating a <span
                                                class="duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-700' : 'text-indigo-900' }} ">new
                                                batch</span>.</p>
                                    </div>
                                    @error('temporaryBatchesList')
                                        <div class="absolute bottom-0 flex items-center justify-center w-full">
                                            <p class="text-red-500 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        </div>
                                    @enderror
                                </div>
                            @endif

                            {{-- Modal footer --}}
                            <div class="col-span-full w-full flex items-center justify-end">

                                {{-- Loading State for Changes --}}
                                <div class="z-50 text-indigo-900" wire:loading wire:target="saveBatches">
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

                                {{-- Finish Button --}}
                                <button type="submit" wire:loading.attr="disabled" wire:target="saveBatches"
                                    class="space-x-2 text-sm rounded-md py-2 px-4 text-center text-white font-bold flex items-center duration-200 ease-in-out bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                    <p>FINISH</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
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

                @endif
            </div>
        </div>
    </div>
</div>
