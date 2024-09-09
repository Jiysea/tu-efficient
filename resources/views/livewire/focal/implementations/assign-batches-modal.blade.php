<div wire:ignore.self id="assign-batches-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div x-data="{
        init() {
            window.addEventListener('assign-create-batches', () => {
                const modal = FlowbiteInstances.getInstance('Modal', 'assign-batches-modal');
                modal.hide();
            });
        },
    }" class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div x-data="{
            show: false,
            rotation: 0,
        }" class="relative bg-white rounded-md shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 rounded-t ">
                <h1 class="text-lg font-semibold text-indigo-1100">
                    Assign and Create New Batches
                </h1>
                <div class="flex items-center justify-center">
                    {{-- Loading State for Changes --}}
                    <div class="z-50 text-indigo-900" wire:loading
                        wire:target="addBatchRow, editBatchRow, removeBatchRow, addToastCoordinator, addToastCoordinatorInBatchList, removeToastCoordinatorFromBatchList, removeToastCoordinator, getAllCoordinatorsForBatchList, updateCurrentCoordinator, liveUpdateRemainingSlots">
                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <button type="button" data-modal-toggle="assign-batches-modal" @click="trapAssign = false"
                        class="text-indigo-400 bg-transparent hover:bg-indigo-200 hover:text-indigo-900 rounded  w-8 h-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-300 ease-in-out">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
            </div>
            <!-- Modal body -->
            <div
                class="bg-indigo-50 mx-4 px-4 py-3 shadow-sm rounded-md flex items-center justify-between text-indigo-1100 text-sm font-medium">
                <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                    <p class="">Project Number:
                    <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">{{ $implementation->project_num }}
                    </p>
                    </p>
                </div>
                <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                    <p class="">District:
                    <p class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">{{ $implementation->district }}</p>
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
            <form wire:submit.prevent="saveBatches" class="p-4 md:pt-3 md:px-4 md:pb-4">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-5 text-xs">
                    {{-- Batch Number --}}
                    <div class="relative col-span-5 sm:col-span-2 mb-4">
                        <label for="batch_num" class="block mb-1  font-medium text-indigo-1100 ">Batch
                            Number</label>
                        <input type="text" id="batch_num" wire:model.live="batch_num" autocomplete="off"
                            class="text-xs {{ $errors->has('batch_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-indigo-50 border border-indigo-300 text-indigo-1100 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"
                            placeholder="Type batch number">
                        @error('batch_num')
                            <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Barangay Name --}}
                    <div class="relative col-span-3 sm:col-span-2 mb-4">
                        <label for="barangay_name" class="block mb-1  font-medium text-indigo-1100 ">Barangay
                        </label>
                        <input type="text" id="barangay_name" wire:model.live="barangay_name" autocomplete="off"
                            class="text-xs {{ $errors->has('barangay_name') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-indigo-50 border border-indigo-300 text-indigo-1100  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type barangay name">
                        @error('barangay_name')
                            <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Slots --}}
                    <div class="relative col-span-2 sm:col-span-1 mb-4">
                        <label for="slots_allocated" class="block mb-1 font-medium text-indigo-1100 ">Slots</label>
                        <div class="relative">
                            <input type="number" inputmode="numeric" min="0" id="slots_allocated"
                                autocomplete="off" wire:model.live="slots_allocated"
                                @input="$wire.liveUpdateRemainingSlots();"
                                class="text-xs {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-indigo-50 border border-indigo-300 text-indigo-1100 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full py-2.5"
                                placeholder="Type slots allocation">
                        </div>
                        @error('slots_allocated')
                            <p class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    {{-- Add Coordinators dropdown --}}
                    <div class="relative flex flex-col col-span-5 sm:col-span-2 mb-4">
                        <p class="block mb-1 font-medium text-indigo-1100 ">Add Coordinator</p>
                        <div class="relative z-50 h-full">
                            <div id="coordinator_name"
                                @if ($coordinators) @click="show = !show ; rotation += 180" @endif
                                class="w-full h-full border {{ $coordinators ? 'bg-indigo-50 border-indigo-300 text-indigo-1100 cursor-pointer' : 'bg-gray-50 border-gray-300 text-gray-500' }} text-sm px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                <p> {{ $currentCoordinator }}</p>
                                <svg @if ($coordinators) :class="{
                                    'rotate-0': rotation % 360 === 0,
                                    'rotate-180': rotation % 360 === 180,
                                }" @endif
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-4 ms-3 duration-200 ease-in-out">
                                    <path fill-rule="evenodd"
                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            @if ($coordinators)
                                <div x-show="show" @click.away="show = !show; rotation += 180"
                                    :class="{
                                        'block': show === true,
                                        'hidden': show === false,
                                    }"
                                    class="hidden end-0 absolute text-indigo-1100 bg-indigo-50 shadow-lg border border-indigo-300 rounded p-3 mt-2">
                                    <div class="relative flex items-center justify-center py-1 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="absolute start-0 ps-2 w-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                            <path fill-rule="evenodd"
                                                d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <input id="searchCoordinator" wire:model.live="searchCoordinator"
                                            type="text"
                                            class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                            placeholder="Search coordinator">
                                    </div>
                                    <ul
                                        class="mt-2 text-xs overflow-y-auto max-h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-900">
                                        @foreach ($coordinators as $key => $coordinator)
                                            <li wire:key={{ $key }}>
                                                <button wire:click="updateCurrentCoordinator({{ $key }})"
                                                    type="button" @click="show= !show ; rotation += 180"
                                                    wire:loading.attr="disabled" aria-label="{{ __('Coordinator') }}"
                                                    class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                    @php
                                                        $fullName = '';
                                                        $first = $this->coordinators[$key]['first_name'];
                                                        $middle = $this->coordinators[$key]['middle_name'];
                                                        $last = $this->coordinators[$key]['last_name'];
                                                        $ext = $this->coordinators[$key]['extension_name'];

                                                        if ($ext === null && $middle === null) {
                                                            $fullName = $first . ' ' . $last;
                                                        } elseif ($middle === null && $ext !== null) {
                                                            $fullName = $first . ' ' . $last . ' ' . $ext;
                                                        } elseif ($middle !== null && $ext === null) {
                                                            $fullName = $first . ' ' . $middle . ' ' . $last;
                                                        } else {
                                                            $fullName =
                                                                $first . ' ' . $middle . ' ' . $last . ' ' . $ext;
                                                        }
                                                    @endphp

                                                    {{ $fullName }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- Assigned Coordinators toast box --}}
                    <div class="relative grid grid-cols-5 flex-grow col-span-5 sm:col-span-3 mb-4">
                        <div class="relative col-span-5">
                            <p class="block mb-1 ms-16 font-medium text-indigo-1100 ">Assigned Coordinators</p>
                            <input type="hidden" id="assigned_coordinators" wire:model.live="assigned_coordinators">
                            <div class="relative flex">
                                {{-- Arrow button --}}
                                <button type="button"
                                    @if ($coordinators) wire:click="addToastCoordinator" @else disabled @endif
                                    class="me-4 px-2 flex items-center justify-center rounded border-2 focus:outline-none duration-200 ease-in-out
                                        {{ $coordinators
                                            ? 'text-indigo-700 hover:text-indigo-50 active:text-indigo-300 border-indigo-700 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900'
                                            : 'text-gray-300 border-gray-300' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M277.913 100.212 C 268.376 103.320,263.354 115.296,267.916 124.055 C 268.746 125.649,281.931 139.434,297.217 154.688 L 325.008 182.422 176.371 182.813 L 27.734 183.203 24.044 185.372 C 11.976 192.467,13.880 212.729,26.953 216.320 C 29.173 216.930,72.861 217.180,177.711 217.183 L 325.343 217.188 296.350 246.289 C 268.003 274.743,267.339 275.480,266.516 279.416 C 263.782 292.490,275.629 303.458,288.672 299.926 C 292.603 298.862,379.406 212.826,382.053 207.371 C 383.922 203.517,384.072 197.196,382.390 193.139 C 380.867 189.467,295.574 103.760,291.158 101.464 C 287.389 99.505,281.724 98.970,277.913 100.212 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
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
                                            class="p-1 me-2 rounded duration-200 ease-in-out bg-indigo-300 text-indigo-1000">
                                            {{ $coordinator['last_name'] }}
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
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
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
                                    class="flex items-center justify-center space-x-2 text-sm py-2 px-3 whitespace-nowrap rounded ms-4 font-bold uppercase bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 focus:outline-none duration-200 ease-in-out">
                                    <p>create batch</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
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

                    {{-- Modal body 2 (Table Area) --}}
                    @if ($temporaryBatchesList)
                        {{-- Dropdown Area --}}
                        @foreach ($temporaryBatchesList as $keyBatch => $batch)
                            @if ($selectedBatchRow === $keyBatch)
                                @if ($batchListCoordinators)
                                    <div wire:key="batchListDropdownHoorah-{{ $keyBatch }}"
                                        x-data="{
                                            openBatchDropdown: false,
                                            dropdownStyles: '',
                                            init() {
                                                window.addEventListener('resize', () => {
                                                    if (this.openBatchDropdown) {
                                                        this.updateDropdownPosition();
                                                    }
                                                });
                                            },
                                        
                                            updateDropdownPosition() {
                                                const button = document.querySelector('#batchListRowButton-{{ $keyBatch }}');
                                                const dropdown = this.$refs.dropdownContent{{ $keyBatch }};
                                                if (button && dropdown) {
                                                    const rect = button.getBoundingClientRect();
                                                    this.dropdownStyles = `top: ${rect.top - dropdown.offsetHeight - 10}px; left: ${rect.right - dropdown.offsetWidth}px;`;
                                                }
                                        
                                            },
                                            toggleDropdown() {
                                                this.openBatchDropdown = !this.openBatchDropdown;
                                        
                                                this.updateDropdownPosition();
                                            }
                                        }" x-init="init()"
                                        @toggle-batchlistrowdropdown.window="
                                if ($event.detail.id === {{ $keyBatch }}) {
                                    toggleDropdown();
                                }"
                                        class="z-50 fixed" :style="dropdownStyles" x-show="openBatchDropdown"
                                        @click.outside="openBatchDropdown = false">
                                        <div x-ref="dropdownContent{{ $keyBatch }}"
                                            class="relative z-50 text-indigo-1100 bg-indigo-50 shadow-lg border border-indigo-300 rounded p-3">
                                            <ul
                                                class="text-xs overflow-y-auto min-h-44 max-h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">
                                                @foreach ($batchListCoordinators as $keyCoordinator => $coordinator)
                                                    <li wire:key={{ $keyCoordinator }}>
                                                        <button
                                                            wire:click="selectCurrentCoordinator({{ $keyCoordinator }})"
                                                            type="button" wire:loading.attr="disabled"
                                                            aria-label="{{ __('Coordinator') }}"
                                                            class="w-full flex items-center justify-start px-4 py-2 {{ $selectedCoordinatorKeyInBatchListDropdown === $keyCoordinator ? 'text-indigo-50 hover:text-indigo-100 bg-indigo-900 hover:bg-indigo-800' : 'text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100' }} duration-200 ease-in-out">
                                                            @php
                                                                $fullName = '';
                                                                $first =
                                                                    $this->batchListCoordinators[$keyCoordinator][
                                                                        'first_name'
                                                                    ];
                                                                $middle =
                                                                    $this->batchListCoordinators[$keyCoordinator][
                                                                        'middle_name'
                                                                    ];
                                                                $last =
                                                                    $this->batchListCoordinators[$keyCoordinator][
                                                                        'last_name'
                                                                    ];
                                                                $ext =
                                                                    $this->batchListCoordinators[$keyCoordinator][
                                                                        'extension_name'
                                                                    ];

                                                                if ($ext === '-' && $middle === '-') {
                                                                    $fullName = $first . ' ' . $last;
                                                                } elseif ($middle === '-' && $ext !== '-') {
                                                                    $fullName = $first . ' ' . $last . ' ' . $ext;
                                                                } elseif ($middle !== '-' && $ext === '-') {
                                                                    $fullName = $first . ' ' . $middle . ' ' . $last;
                                                                } else {
                                                                    $fullName =
                                                                        $first .
                                                                        ' ' .
                                                                        $middle .
                                                                        ' ' .
                                                                        $last .
                                                                        ' ' .
                                                                        $ext;
                                                                }
                                                            @endphp

                                                            {{ $fullName }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="relative flex items-center justify-center w-full">
                                                <button wire:click="addToastCoordinatorInBatchList"
                                                    class="text-sm font-semibold w-full p-2 mt-2 rounded border-2 duration-200 ease-in-out hover:bg-indigo-800 active:bg-indigo-900 text-indigo-700 hover:text-indigo-50 active:text-indigo-100 border-indigo-700 hover:border-transparent focus:outline-none"
                                                    type="button">
                                                    ADD
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        {{-- Label --}}
                        <div class="relative col-span-5 font-semibold text-base text-indigo-1100 ms-2">
                            Batch List
                        </div>
                        {{-- Batch List Table --}}
                        <div
                            class="relative col-span-5 min-h-[12.375rem] max-h-[12.375rem] overflow-y-auto bg-indigo-50 border border-indigo-300 rounded-md whitespace-nowrap scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">

                            <table class="relative w-full text-sm text-left text-indigo-1100 rounded-md">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0 ">
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
                                            class="relative border-b {{ $selectedBatchRow === $keyBatch ? 'bg-indigo-100' : 'bg-indigo-50' }} whitespace-nowrap duration-200 ease-in-out">
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
                                                        class="p-1 mx-1 rounded duration-200 ease-in-out {{ $selectedBatchRow === $keyBatch ? 'bg-green-300 text-green-1000' : 'bg-indigo-300 text-indigo-1000' }}">
                                                        {{ $coordinator['last_name'] }}
                                                        @if ($selectedBatchRow === $keyBatch && count($batch['assigned_coordinators']) !== 1)
                                                            {{-- x button near coordinator names --}}
                                                            <button type="button"
                                                                wire:click="removeToastCoordinatorFromBatchList({{ $keyBatch }}, {{ $keyCoordinator }})"
                                                                class="ms-1 text-green-1100 hover:text-green-900 active:text-green-1000 duration-200 ease-in-out">
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
                                                        @endif
                                                    </span>
                                                @endforeach
                                                @if ($selectedBatchRow === $keyBatch)
                                                    @if ($batchListCoordinators)
                                                        {{-- Get all the assigned_coordinators users_ids for ignore --}}
                                                        @php
                                                            $ignoredIDs = [];
                                                            foreach (
                                                                $batch['assigned_coordinators']
                                                                as $coordinatorId
                                                            ) {
                                                                $ignoredIDs[] = $coordinatorId['users_id'];
                                                            }
                                                        @endphp
                                                        {{-- + button Coordinator button --}}
                                                        <button type="button"
                                                            @click="$wire.getAllCoordinatorsForBatchList({{ json_encode($ignoredIDs) }})
                                                                .then(() => {
                                                                    $nextTick(() => {
                                                                        $dispatch('toggle-batchlistrowdropdown', { id: {{ $keyBatch }} })
                                                                    });
                                                                });"
                                                            id="batchListRowButton-{{ $keyBatch }}"
                                                            class="p-1.5 mx-1 rounded bg-green-300 text-center text-green-1100 hover:text-green-900 active:text-green-1000 duration-200 ease-in-out">
                                                            <svg class="size-2" xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="400" height="400"
                                                                viewBox="0, 0, 400,400">
                                                                <g>
                                                                    <path
                                                                        d="M192.319 1.634 C 190.381 2.618,188.044 4.697,186.850 6.501 L 184.766 9.651 184.560 97.013 L 184.355 184.375 97.893 184.375 L 11.431 184.375 8.008 186.032 C -2.600 191.167,-2.600 208.833,8.008 213.968 L 11.431 215.625 97.903 215.625 L 184.375 215.625 184.375 302.097 L 184.375 388.569 186.032 391.992 C 191.167 402.600,208.833 402.600,213.968 391.992 L 215.625 388.569 215.625 302.097 L 215.625 215.625 302.097 215.625 L 388.569 215.625 391.992 213.968 C 402.600 208.833,402.600 191.167,391.992 186.032 L 388.569 184.375 302.097 184.375 L 215.625 184.375 215.625 97.903 L 215.625 11.431 213.968 8.008 C 210.474 0.790,200.036 -2.284,192.319 1.634 "
                                                                        stroke="none" fill="currentColor"
                                                                        fill-rule="evenodd"></path>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-2 py-2 text-center">
                                                {{ $batch['slots_allocated'] }}
                                            </td>
                                            <td class="py-2 flex justify-end items-center">
                                                {{-- Edit/Pen Button --}}
                                                <button type="button"
                                                    @click.stop="$wire.editBatchRow({{ $keyBatch }});"
                                                    class="p-1 mx-1 rounded-md duration-200 ease-in-out {{ $selectedBatchRow === $keyBatch ? 'bg-green-900 hover:bg-green-800 text-green-50 hover:text-green-100 active:text-green-200' : 'bg-transparent text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                                        class="size-4" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        width="400" height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M290.902 25.773 C 282.111 27.619,285.512 24.408,166.940 142.847 C 105.144 204.574,53.902 256.015,53.067 257.160 C 51.317 259.563,25.000 358.841,25.000 363.041 C 25.000 368.254,29.474 373.565,34.690 374.544 C 38.001 375.165,138.652 350.045,142.578 347.617 C 143.867 346.821,195.416 295.615,257.131 233.826 C 384.580 106.225,374.609 117.250,374.609 103.924 C 374.609 92.089,375.556 93.377,342.272 59.919 C 307.303 24.769,304.513 22.914,290.902 25.773 M350.134 105.165 C 349.684 105.977,336.624 119.297,321.112 134.766 L 292.908 162.891 265.264 134.766 L 237.621 106.641 264.709 79.413 C 279.607 64.438,292.852 51.520,294.141 50.707 L 296.484 49.228 323.718 76.459 C 347.514 100.252,350.849 103.876,350.134 105.165 M202.152 253.545 L 128.516 327.012 109.965 331.665 L 91.414 336.319 77.738 322.660 C 70.217 315.148,64.063 308.567,64.063 308.036 C 64.063 307.505,66.200 298.888,68.813 288.887 L 73.564 270.703 146.740 197.462 L 219.916 124.221 247.853 152.149 L 275.789 180.078 202.152 253.545 M63.281 342.997 C 63.281 343.423,56.324 345.313,54.757 345.313 C 54.431 345.313,54.713 343.208,55.383 340.635 L 56.602 335.958 59.942 339.235 C 61.778 341.038,63.281 342.731,63.281 342.997 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>

                                                {{-- X button (table list) --}}
                                                <button type="button"
                                                    wire:click="removeBatchRow({{ $keyBatch }})"
                                                    class="p-1 me-3 rounded-md text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 bg-transparent hover:bg-indigo-300 duration-200 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Shows up on initial modal open or when there's no batches created yet --}}
                    @else
                        <div
                            class="relative col-span-5 bg-white px-4 pb-6 pt-2 h-60 min-w-full flex flex-col items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-gray-500 bg-red-50 border-red-300' : 'text-gray-500 bg-gray-50 border-gray-300' }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-300' : 'text-gray-300' }}"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
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
                </div>

                {{-- Modal footer --}}
                <div class="w-full flex relative items-center justify-end">
                    {{-- Loading State for Changes --}}
                    <div class="z-50 text-indigo-900" wire:loading wire:target="saveBatches">
                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
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
            </form>
        </div>
    </div>
</div>
