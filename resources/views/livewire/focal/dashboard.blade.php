<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, isAboveBreakpoint: true, showExportModal: $wire.entangle('showExportModal'), isMobile: window.innerWidth < 768 }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});
window.addEventListener('resize', () => {
    isMobile = window.innerWidth < 768;
    $wire.$dispatchSelf('init-reload');
});">

    <style>
        /* Mobile screens */
        @media (max-width: 639px) {
            .apexcharts-legend-text {
                font-size: 0.5rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.5rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 0.625rem !important;
            }
        }

        /* Small screens */
        @media (min-width: 640px) and (max-width: 767px) {
            .apexcharts-legend-text {
                font-size: 0.625rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.625rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 0.75rem !important;
            }
        }

        /* Medium screens */
        @media (min-width: 768px) and (max-width: 1023px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1rem !important;
            }
        }

        /* large screens */
        @media (min-width: 1024px) and (max-width: 1279px) {
            .apexcharts-legend-text {
                font-size: 0.625rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.625rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 0.75rem !important;
            }
        }

        /* XL screens */
        @media (min-width: 1280px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1rem !important;
            }
        }
    </style>


    @if (session('heads-up'))
        <livewire:focal.dashboard.heads-up-modal />
    @endif

    <div :class="{
        'md:ml-20': !open,
        'md:ml-20 xl:ml-64': open,
    }"
        class="md:ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Date Range --}}
            <div class="relative flex items-center justify-between w-full my-2 lg:my-0 lg:h-[8vh] gap-2">
                <div class="flex items-center gap-2">
                    <livewire:sidebar.focal-bar />

                    <h1 class="text-xl font-semibold sm:font-bold xl:ms-2">Dashboard</h1>

                    {{-- Date Range picker --}}
                    <template x-if="!isMobile">
                        <span class="relative inline-flex items-center gap-1 sm:gap-2" x-data="{ pop: false }">
                            <div id="dashboard-date-range" date-rangepicker datepicker-autohide
                                class="flex items-center gap-1 sm:gap-2 text-xs">

                                {{-- Start --}}
                                <div class="relative" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                    <div
                                        class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <input type="text" readonly id="start-date" wire:model.change="calendarStart"
                                        @change-date.camel="$wire.$set('calendarStart', $el.value);" name="start"
                                        value="{{ $calendarStart }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date start">
                                </div>

                                <span class="text-indigo-1100">to</span>

                                {{-- End --}}
                                <div class="relative" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                    <div
                                        class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <input type="text" readonly id="end-date" wire:model.change="calendarEnd"
                                        @change-date.camel="$wire.$set('calendarEnd', $el.value);" name="end"
                                        value="{{ $calendarEnd }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date end">
                                </div>
                            </div>

                            {{-- Tooltip Content --}}
                            <div x-cloak x-show="pop" x-transition.opacity
                                class="absolute z-50 top-full mt-2 right-0 rounded p-2 shadow text-xs text-center font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                This sets the <span class="text-indigo-400">date range</span> of what records to show
                                <br>
                                based on the its creation date (start to end)
                            </div>
                        </span>
                    </template>
                </div>

                {{-- Loading State --}}
                <template x-if="!isMobile">
                    <svg class="text-indigo-900 size-6 animate-spin" wire:loading
                        wire:target="calendarStart, calendarEnd, printSummary, showExport, previousPage, nextPage, selectImplementation"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </template>

                {{-- MD:Date Range Picker --}}
                <template x-data="{ show: false }" x-if="isMobile">
                    <div class="relative flex items-center justify-center gap-2 h-full">

                        {{-- MD:Loading State --}}
                        <svg class="text-indigo-900 size-6 animate-spin" wire:loading
                            wire:target="calendarStart, calendarEnd, printSummary, showExport, previousPage, nextPage, selectImplementation"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        {{-- Show Left Dates --}}
                        <span class="relative" x-data="{ pop: false }">
                            <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;"
                                @click="show = !show;"
                                class="flex items-center justify-center p-1 rounded duration-200 ease-in-out hover:bg-indigo-100 text-zinc-500 hover:text-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M93.157 14.058 C 88.540 16.873,87.506 19.624,87.503 29.102 L 87.500 37.500 65.304 37.500 C 36.969 37.500,32.063 38.825,22.483 49.067 C 12.153 60.111,12.432 57.893,12.681 126.869 L 12.891 185.269 16.126 188.286 C 21.669 193.457,28.330 193.457,33.874 188.287 L 37.109 185.270 37.337 161.385 L 37.565 137.500 200.046 137.500 L 362.527 137.500 362.318 245.117 C 362.112 351.136,362.086 352.774,360.547 355.387 C 358.537 358.800,355.734 360.861,351.893 361.752 C 347.818 362.697,52.182 362.697,48.107 361.752 C 39.092 359.661,37.977 356.783,37.500 334.375 C 37.082 314.738,36.969 314.164,32.807 310.662 C 27.942 306.569,21.186 306.994,16.126 311.713 L 12.891 314.729 12.659 335.554 C 12.465 352.942,12.636 357.109,13.697 360.806 C 17.046 372.482,26.754 382.410,38.352 386.020 C 45.124 388.127,353.807 388.358,360.991 386.261 C 372.544 382.889,382.437 373.161,386.020 361.648 C 388.332 354.218,388.332 70.782,386.020 63.352 C 382.437 51.839,372.544 42.111,360.991 38.739 C 357.560 37.737,352.514 37.500,334.624 37.500 L 312.500 37.500 312.497 29.102 C 312.493 16.846,309.225 12.506,300.000 12.506 C 290.775 12.506,287.507 16.846,287.503 29.102 L 287.500 37.500 200.000 37.500 L 112.500 37.500 112.497 29.102 C 112.492 14.820,103.447 7.784,93.157 14.058 M87.503 64.648 C 87.507 67.570,90.074 71.562,93.157 73.442 C 100.677 78.027,112.486 72.658,112.497 64.648 L 112.500 62.500 200.000 62.500 L 287.500 62.500 287.503 64.648 C 287.514 72.658,299.323 78.027,306.843 73.442 C 309.926 71.562,312.493 67.570,312.497 64.648 L 312.500 62.500 330.664 62.519 C 362.294 62.551,361.983 62.258,362.363 92.383 L 362.617 112.500 200.000 112.500 L 37.383 112.500 37.637 92.383 C 38.017 62.236,37.514 62.715,68.945 62.580 L 87.500 62.500 87.503 64.648 M81.641 175.896 C 79.207 177.217,14.882 241.534,13.621 243.907 C 13.004 245.067,12.500 247.809,12.500 250.000 C 12.500 255.994,12.363 255.834,47.410 290.739 C 82.912 326.097,81.626 325.001,87.644 324.997 C 95.270 324.992,99.992 320.270,99.997 312.644 C 100.001 306.721,100.366 307.180,77.071 283.789 L 55.870 262.500 130.083 262.497 C 202.759 262.494,204.350 262.462,206.843 260.942 C 214.551 256.242,214.551 243.758,206.843 239.058 C 204.350 237.538,202.759 237.506,130.083 237.503 L 55.870 237.500 77.071 216.211 C 100.366 192.820,100.001 193.279,99.997 187.356 C 99.991 178.049,89.611 171.569,81.641 175.896 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>

                            {{-- Tooltip Content --}}
                            <div x-cloak x-show="!show && pop" x-transition.opacity
                                class="absolute z-50 top-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                Toggles the <span class="text-indigo-400">date range</span> menu
                            </div>
                        </span>

                        <div x-show="show" x-transition.opacity
                            class="absolute right-full top-0 me-2 flex flex-col items-center justify-center gap-2 rounded p-2 z-40 border border-indigo-500 bg-white">

                            <span class="text-indigo-1100 text-xs font-medium">
                                Date Range (Start to End)
                            </span>

                            <div id="dashboard-date-range" date-rangepicker datepicker-autohide
                                class="flex items-center gap-1 sm:gap-2 text-xs">

                                {{-- Start --}}
                                <div class="relative">
                                    <div
                                        class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <input type="text" readonly id="start-date"
                                        @change-date.camel="$wire.$set('calendarStart', $el.value); show = false;"
                                        wire:model.change="calendarStart" name="start" value="{{ $calendarStart }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date start">
                                </div>

                                <span class="text-indigo-1100">-></span>

                                {{-- End --}}
                                <div class="relative">
                                    <div
                                        class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <input type="text" readonly id="end-date" wire:model.change="calendarEnd"
                                        @change-date.camel="$wire.$set('calendarEnd', $el.value); show = false;"
                                        name="end" value="{{ $calendarEnd }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date end">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Body --}}
            <div class="flex flex-col gap-2">

                {{-- Top Counters --}}
                <div class="flex flex-col gap-2 w-full">
                    {{-- Project Status Counters --}}
                    <div class="flex flex-col lg:flex-row gap-2 w-full">
                        <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                            class="bg-white text-indigo-700 relative flex flex-1 items-center justify-start rounded shadow py-2 h-full">
                            <svg class="size-4 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M53.906 51.221 C 39.995 54.908,27.658 65.001,21.471 77.756 C 16.088 88.855,16.357 81.997,16.588 202.133 L 16.797 310.547 18.890 316.277 C 24.292 331.065,34.839 341.735,49.811 347.560 L 55.078 349.609 184.312 349.816 C 327.345 350.045,317.483 350.378,328.516 344.950 C 338.363 340.105,346.265 332.146,351.257 322.041 C 355.003 314.462,379.688 193.540,379.688 182.774 C 379.688 169.710,372.772 158.686,360.938 152.881 L 355.859 150.391 246.154 150.185 C 124.740 149.957,132.449 149.656,121.484 155.050 C 111.485 159.969,103.795 167.734,98.691 178.063 C 96.032 183.444,94.854 188.707,82.556 250.137 L 69.290 316.406 67.262 316.402 C 61.198 316.389,54.702 312.188,51.838 306.427 C 49.582 301.889,49.582 98.111,51.838 93.573 C 56.558 84.079,58.687 83.603,96.471 83.598 L 127.709 83.594 151.263 99.279 C 164.218 107.907,176.177 115.450,177.838 116.042 C 180.289 116.916,190.903 117.125,234.057 117.153 L 287.254 117.188 290.697 119.005 C 294.922 121.236,298.406 125.998,298.988 130.339 L 299.424 133.594 316.509 133.594 L 333.594 133.594 333.594 130.203 C 333.594 111.647,318.357 91.911,298.996 85.388 L 292.578 83.227 240.149 82.824 L 187.719 82.422 163.671 66.406 L 139.624 50.391 98.913 50.236 C 65.372 50.109,57.446 50.283,53.906 51.221 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div class="flex flex-col items-start justify-center">
                                <h1 class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                    {{ $this->implementationCounters?->total_projects ?? 0 }}
                                </h1>
                                <span class="whitespace-nowrap text-xs font-semibold leading-none">Total
                                    Projects
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                            class="bg-white text-indigo-700 relative flex flex-1 items-center justify-start rounded shadow py-2 h-full">
                            <svg class="size-4 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M40.234 1.095 C 32.362 3.592,24.968 9.448,20.878 16.428 C 16.117 24.551,16.372 14.536,16.594 185.205 L 16.797 341.016 18.638 345.581 C 22.136 354.254,30.757 362.493,39.415 365.436 C 43.006 366.657,49.643 366.798,113.477 367.006 L 183.594 367.235 183.596 341.235 C 183.602 291.586,188.322 272.599,206.862 247.656 C 229.692 216.943,260.189 201.970,303.320 200.296 L 317.188 199.758 317.183 116.090 C 317.180 66.114,316.875 30.570,316.425 27.823 C 314.417 15.558,305.915 5.613,293.906 1.482 C 288.410 -0.409,46.143 -0.778,40.234 1.095 M232.813 66.406 L 232.813 82.813 150.000 82.813 L 67.188 82.813 67.188 66.406 L 67.188 50.000 150.000 50.000 L 232.813 50.000 232.813 66.406 M266.406 133.594 L 266.406 150.000 166.797 150.000 L 67.188 150.000 67.188 133.594 L 67.188 117.188 166.797 117.188 L 266.406 117.188 266.406 133.594 M166.406 200.000 L 166.406 216.406 116.797 216.406 L 67.188 216.406 67.188 200.000 L 67.188 183.594 116.797 183.594 L 166.406 183.594 166.406 200.000 M289.844 233.662 C 215.567 243.186,190.056 337.657,249.419 383.356 C 274.676 402.799,310.753 405.549,339.643 390.232 C 385.376 365.987,397.745 304.930,365.172 264.219 C 347.212 241.771,318.243 230.020,289.844 233.662 M132.813 266.406 L 132.813 282.813 100.000 282.813 L 67.188 282.813 67.188 266.406 L 67.188 250.000 100.000 250.000 L 132.813 250.000 132.813 266.406 M312.500 285.938 L 312.500 304.688 331.250 304.688 L 350.000 304.688 350.000 316.797 L 350.000 328.906 318.750 328.906 L 287.500 328.906 287.500 298.047 L 287.500 267.188 300.000 267.188 L 312.500 267.188 312.500 285.938 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div class="flex flex-col items-start justify-center">
                                <h1 class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                    {{ $this->implementationCounters?->total_pending ?? 0 }}
                                </h1>
                                <span class="whitespace-nowrap text-xs font-semibold leading-none">Pending
                                    Projects
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                            class="bg-white text-indigo-700 relative flex flex-1 items-center justify-start rounded shadow py-2 h-full">
                            <svg class="size-4 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M173.772 22.661 C 133.159 28.811,99.266 46.359,70.257 76.254 C 51.757 95.319,47.958 104.153,55.261 111.120 C 62.030 117.578,68.981 115.915,76.644 106.005 C 126.714 41.251,216.877 25.957,285.156 70.637 C 328.337 98.893,355.469 148.784,355.469 199.931 L 355.469 206.955 352.148 205.448 C 341.783 200.743,331.644 210.580,335.874 221.238 C 337.007 224.094,359.109 248.678,361.908 250.195 C 365.131 251.943,370.025 251.943,373.250 250.195 C 376.061 248.672,398.177 224.079,399.291 221.238 C 403.455 210.619,393.376 200.740,383.127 205.395 L 379.925 206.848 379.429 194.245 C 375.193 86.613,279.372 6.668,173.772 22.661 M171.109 82.618 C 165.661 85.355,164.845 87.761,164.836 101.092 L 164.829 112.891 158.720 115.829 C 155.359 117.444,150.285 120.326,147.444 122.231 L 142.278 125.696 132.682 120.270 C 114.911 110.222,114.314 110.562,96.877 140.650 C 79.286 171.005,79.293 171.977,97.205 182.119 L 106.329 187.286 106.485 200.186 L 106.641 213.087 98.497 217.676 C 84.168 225.751,80.932 230.691,84.844 238.515 C 90.391 249.612,110.131 282.444,112.109 283.864 C 116.927 287.323,122.407 286.371,132.942 280.245 L 142.055 274.945 147.787 278.684 C 150.939 280.740,156.067 283.637,159.181 285.122 L 164.844 287.823 164.844 299.483 C 164.844 319.606,164.012 319.141,200.000 319.141 C 235.988 319.141,235.156 319.606,235.156 299.483 L 235.156 287.823 240.819 285.122 C 243.933 283.637,249.061 280.740,252.213 278.684 L 257.945 274.945 267.058 280.245 C 277.582 286.365,283.062 287.319,287.891 283.875 C 289.868 282.464,309.474 249.844,315.153 238.515 C 319.072 230.697,315.833 225.751,301.503 217.676 L 293.359 213.087 293.515 200.186 L 293.671 187.286 302.795 182.119 C 320.709 171.976,320.717 171.000,303.125 140.655 C 285.672 110.550,285.002 110.169,267.324 120.297 L 257.805 125.751 252.535 122.191 C 249.637 120.233,244.542 117.340,241.213 115.761 L 235.161 112.891 235.159 101.092 C 235.156 87.760,234.340 85.356,228.891 82.618 C 224.770 80.548,175.230 80.548,171.109 82.618 M26.839 150.466 C 24.425 151.657,1.697 176.855,0.647 179.505 C -3.518 190.013,6.696 200.009,16.873 195.387 L 20.075 193.933 20.571 206.537 C 26.802 364.868,218.340 438.618,328.966 325.282 C 348.309 305.465,352.161 296.741,344.709 289.632 C 337.973 283.206,331.011 284.883,323.345 294.776 C 266.395 368.272,160.239 376.413,92.682 312.464 C 62.763 284.143,44.531 241.882,44.531 200.850 L 44.531 193.826 47.852 195.334 C 58.217 200.038,68.327 190.234,64.134 179.543 C 63.020 176.702,40.904 152.109,38.094 150.586 C 34.990 148.904,30.112 148.852,26.839 150.466 M208.063 166.793 C 234.970 173.802,243.584 206.341,223.600 225.488 C 201.766 246.409,165.625 230.552,165.625 200.053 C 165.625 177.833,186.784 161.251,208.063 166.793 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div class="flex flex-col items-start justify-center">
                                <h1 class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                    {{ $this->implementationCounters?->total_implementing ?? 0 }}
                                </h1>
                                <span class="whitespace-nowrap text-xs font-semibold leading-none">Implementing
                                    Projects
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                            class="bg-white text-indigo-700 relative flex flex-1 items-center justify-start rounded shadow py-2 h-full">
                            <svg class="size-4 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M43.658 1.034 C 34.370 4.500,28.457 11.593,26.634 21.457 C 26.036 24.688,25.781 72.433,25.781 181.003 L 25.781 335.938 52.353 335.938 L 78.925 335.938 78.720 177.930 C 78.489 -0.791,79.164 16.431,72.082 8.555 C 65.428 1.154,52.502 -2.266,43.658 1.034 M102.931 112.695 C 103.039 163.936,103.215 218.220,103.322 233.327 L 103.516 260.795 235.876 201.491 C 308.674 168.874,368.915 141.573,369.745 140.822 C 375.385 135.718,374.874 125.533,368.750 120.975 C 367.107 119.753,104.820 19.531,103.263 19.531 C 102.972 19.531,102.823 61.455,102.931 112.695 M25.781 367.044 C 25.781 388.967,34.462 399.609,52.344 399.609 C 69.770 399.609,77.925 390.487,78.708 370.117 L 79.091 360.156 52.436 360.156 L 25.781 360.156 25.781 367.044 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div class="flex flex-col items-start justify-center">
                                <h1 class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                    {{ $this->implementationCounters?->total_concluded ?? 0 }}
                                </h1>
                                <span class="whitespace-nowrap text-xs font-semibold leading-none">Concluded
                                    Projects
                                </span>
                            </div>
                        </a>
                    </div>

                    {{-- Batch Approval && Submission Status Counters --}}
                    <div class="flex flex-col lg:flex-row gap-2 w-full lg:justify-between">
                        <div class="flex flex-1 gap-2">
                            <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                                class="bg-white text-indigo-500 relative flex flex-1 items-center justify-start rounded shadow py-1 h-full">
                                <svg class="size-3 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M137.109 10.047 C 133.498 12.278,133.085 12.900,118.359 38.281 C 110.756 51.387,103.954 62.773,103.244 63.584 C 101.102 66.032,98.377 66.763,69.208 72.721 C 29.037 80.927,32.121 76.705,36.747 117.164 L 40.117 146.643 38.613 149.447 C 37.786 150.989,29.551 160.945,20.313 171.570 C -1.134 196.237,-0.001 194.653,0.005 199.956 C 0.012 205.405,-0.940 204.053,20.313 228.783 C 42.665 254.792,40.780 248.504,36.717 283.517 L 33.373 312.333 35.069 315.836 C 37.636 321.138,39.974 321.941,71.094 328.205 C 88.604 331.729,99.746 334.339,101.318 335.286 C 103.236 336.441,107.128 342.475,118.286 361.594 C 139.465 397.882,134.865 396.377,172.120 379.207 C 193.699 369.262,199.044 367.084,201.052 367.419 C 202.407 367.645,215.005 373.135,229.047 379.618 C 256.453 392.272,257.984 392.729,263.175 389.807 C 266.571 387.896,265.949 388.829,282.403 360.938 C 296.460 337.110,296.990 336.322,300.037 334.747 C 301.133 334.179,314.318 331.194,329.336 328.113 C 360.255 321.769,362.419 321.025,364.904 315.891 L 366.621 312.345 363.242 283.130 C 359.179 248.009,356.970 255.116,380.425 227.846 C 400.999 203.926,400.000 205.356,400.000 199.835 C 400.000 194.669,401.311 196.493,379.259 170.984 C 367.961 157.915,360.854 149.053,360.546 147.652 C 360.273 146.409,361.508 132.837,363.291 117.492 C 368.012 76.864,370.898 80.847,330.828 72.704 C 295.882 65.602,299.043 67.302,288.874 50.133 C 263.273 6.909,265.096 9.395,258.555 8.767 C 255.095 8.434,253.072 9.228,228.374 20.611 C 213.813 27.322,201.045 32.812,200.000 32.812 C 198.955 32.812,186.276 27.363,171.825 20.703 C 143.808 7.790,141.774 7.166,137.109 10.047 M263.898 134.317 C 267.899 136.394,280.140 148.972,281.609 152.514 C 284.818 160.258,286.345 158.412,230.198 214.699 C 177.047 267.983,177.929 267.188,172.031 267.188 C 166.758 267.188,165.803 266.391,140.499 240.906 C 112.554 212.760,112.472 212.537,125.282 199.322 C 140.564 183.557,142.852 183.723,160.931 201.903 L 172.253 213.288 211.322 174.301 C 256.275 129.442,255.558 129.987,263.898 134.317 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div class="flex flex-col items-start justify-center">
                                    <h1
                                        class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                        {{ $this->batchCounters?->total_approval ?? 0 }}
                                    </h1>
                                    <p class="whitespace-nowrap text-xs font-semibold leading-none">
                                        Approved
                                        Batches
                                    </p>
                                </div>
                            </a>
                            <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                                class="bg-white text-indigo-500 relative flex flex-1 items-center justify-start rounded shadow py-1 h-full">
                                <svg class="size-3 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M187.109 17.895 C 176.022 20.924,28.703 93.653,23.692 98.571 C 13.626 108.450,14.276 127.050,25.000 135.973 C 28.589 138.960,174.387 211.235,182.626 214.112 C 193.239 217.817,206.667 217.837,217.251 214.162 C 225.547 211.281,371.365 139.013,375.000 135.981 C 385.832 126.944,386.330 108.346,376.010 98.270 C 374.189 96.492,368.347 93.046,360.933 89.376 C 274.024 46.356,221.185 20.613,216.406 18.963 C 208.372 16.188,195.123 15.705,187.109 17.895 M29.507 177.389 C 14.796 185.157,11.979 206.795,24.143 218.582 C 27.179 221.523,172.845 293.991,183.342 297.782 C 201.067 304.184,230.911 296.432,243.049 282.274 C 266.912 254.438,303.027 236.273,341.890 232.557 L 352.734 231.521 363.045 226.322 C 368.821 223.410,374.576 219.903,376.131 218.348 C 388.056 206.423,385.026 184.850,370.376 177.376 C 364.474 174.365,366.676 173.438,290.039 211.177 C 211.948 249.632,212.576 249.367,199.638 249.307 C 186.459 249.246,190.949 251.167,105.590 209.059 C 33.224 173.359,35.413 174.271,29.507 177.389 M307.813 252.840 C 249.072 264.053,229.627 336.879,274.904 376.089 C 299.747 397.603,340.096 397.603,364.939 376.089 C 390.496 353.957,397.488 318.136,381.855 289.430 C 372.882 272.953,356.642 259.574,339.540 254.570 C 334.087 252.974,313.105 251.830,307.813 252.840 M31.834 259.727 C 16.024 265.269,11.212 288.514,23.281 301.050 C 26.536 304.431,36.061 309.294,125.781 353.386 C 185.417 382.692,187.620 383.594,199.614 383.594 L 206.009 383.594 206.490 373.242 C 207.126 359.570,209.064 348.860,213.848 332.592 C 214.215 331.346,213.900 331.264,210.840 331.811 C 203.357 333.148,189.834 332.381,183.984 330.287 C 180.977 329.210,147.051 312.846,108.594 293.923 C 33.689 257.065,36.217 258.191,31.834 259.727 M322.708 275.809 C 324.146 276.578,324.219 277.663,324.219 298.296 L 324.219 319.975 328.496 324.200 C 330.849 326.523,332.610 328.588,332.409 328.789 C 331.861 329.337,333.681 331.288,334.353 330.873 C 335.379 330.239,346.094 341.915,346.094 343.667 C 346.094 345.596,343.504 348.438,341.746 348.438 C 339.766 348.438,337.412 346.868,337.989 345.934 C 338.283 345.459,338.177 345.285,337.755 345.546 C 337.333 345.806,334.916 343.927,332.385 341.369 C 329.854 338.812,328.079 336.719,328.440 336.719 C 328.802 336.719,328.663 336.195,328.132 335.555 C 327.601 334.916,326.915 334.548,326.608 334.737 C 325.755 335.264,317.059 326.170,317.752 325.477 C 318.086 325.143,317.832 324.987,317.188 325.130 C 315.275 325.555,315.026 277.374,316.934 275.977 C 318.556 274.789,320.686 274.727,322.708 275.809 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div class="flex flex-col items-start justify-center">
                                    <h1
                                        class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                        {{ $this->batchCounters?->total_pending ?? 0 }}
                                    </h1>
                                    <p class="whitespace-nowrap text-xs font-semibold leading-none">
                                        Pending
                                        Batches
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="flex flex-1 gap-2">
                            <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                                class="bg-white text-indigo-500 relative flex flex-1 items-center justify-start rounded shadow py-1 h-full">
                                <svg class="size-3 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M369.531 10.866 C 363.260 12.920,70.279 124.198,67.969 125.404 C 58.660 130.261,57.438 143.382,65.651 150.293 C 68.738 152.890,60.750 151.110,144.887 167.950 L 217.508 182.485 232.387 256.721 C 241.960 304.486,247.747 331.688,248.617 333.005 C 254.591 342.058,268.999 341.933,274.141 332.784 C 276.058 329.373,388.951 32.104,389.975 27.770 C 392.761 15.976,381.744 6.866,369.531 10.866 M79.688 217.166 C 75.990 219.157,22.178 273.255,21.138 276.027 C 16.260 289.025,29.502 301.684,41.640 295.627 C 45.206 293.848,98.203 240.889,100.136 237.174 C 106.709 224.539,92.257 210.399,79.688 217.166 M152.734 227.280 C 149.548 228.774,12.723 365.086,11.125 368.359 C 5.435 380.013,15.884 392.845,28.575 389.790 C 33.594 388.582,170.837 252.379,173.157 246.304 C 178.046 233.502,165.130 221.471,152.734 227.280 M162.891 299.720 C 159.472 301.499,106.416 354.556,104.634 357.977 C 98.198 370.337,110.703 383.842,123.973 378.862 C 126.969 377.737,180.943 323.859,182.952 319.986 C 189.373 307.612,175.212 293.307,162.891 299.720 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div class="flex flex-col items-start justify-center">
                                    <h1
                                        class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                        {{ $this->batchCounters?->total_submitted ?? 0 }}
                                    </h1>
                                    <p class="whitespace-nowrap text-xs font-semibold leading-none">
                                        Submitted
                                        Batches
                                    </p>
                                </div>
                            </a>
                            <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                                class="bg-white text-indigo-500 relative flex flex-1 items-center justify-start rounded shadow py-1 h-full">
                                <svg class="size-3 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M99.023 39.655 C 44.561 60.113,0.000 77.073,0.000 77.344 C -0.000 77.954,198.376 152.366,200.000 152.365 C 201.563 152.364,275.000 124.849,275.000 124.264 C 275.000 124.013,258.376 116.959,238.059 108.589 L 201.117 93.370 225.619 82.965 L 250.121 72.561 286.975 89.111 C 307.244 98.213,324.225 105.676,324.710 105.695 C 326.069 105.748,400.000 77.913,400.000 77.348 C 400.000 76.596,201.410 2.312,199.609 2.390 C 198.750 2.428,153.486 19.197,99.023 39.655 M0.000 215.091 L 0.000 327.837 93.164 362.758 C 144.404 381.964,186.592 397.673,186.914 397.667 C 187.236 397.661,187.500 346.920,187.500 284.909 L 187.500 172.163 94.336 137.242 C 43.096 118.036,0.908 102.327,0.586 102.333 C 0.264 102.339,0.000 153.080,0.000 215.091 M361.523 116.214 L 325.000 129.968 325.000 161.278 L 325.000 192.587 300.521 201.762 C 287.057 206.809,275.807 210.938,275.521 210.938 C 275.234 210.938,275.000 197.051,275.000 180.078 C 275.000 163.105,274.736 149.214,274.414 149.209 C 274.092 149.204,260.029 154.364,243.164 160.675 L 212.500 172.152 212.500 284.904 C 212.500 346.918,212.764 397.661,213.086 397.667 C 213.408 397.673,255.596 381.964,306.836 362.758 L 400.000 327.837 400.000 215.091 C 400.000 125.383,399.800 102.356,399.023 102.401 C 398.486 102.433,381.611 108.649,361.523 116.214 M75.521 292.769 L 100.000 301.944 100.000 314.727 C 100.000 324.616,99.779 327.434,99.023 327.169 C 98.486 326.982,87.241 322.813,74.035 317.906 L 50.022 308.984 50.011 296.289 C 50.005 289.307,50.234 283.594,50.521 283.594 C 50.807 283.594,62.057 287.723,75.521 292.769 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div class="flex flex-col items-start justify-center">
                                    <h1
                                        class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                        {{ $this->batchCounters?->total_unopened ?? 0 }}
                                    </h1>
                                    <p class="whitespace-nowrap text-xs font-semibold leading-none">
                                        Unopened
                                        Batches
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="flex flex-1 gap-2">
                            <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                                class="bg-white text-indigo-500 relative flex flex-1 items-center justify-start rounded shadow py-1 h-full">
                                <svg class="size-3 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M218.157 39.058 C 206.513 46.158,211.758 61.047,226.645 63.152 C 242.747 65.429,249.972 75.200,249.994 94.727 L 250.000 100.000 152.876 100.000 C 66.522 100.000,55.282 100.137,51.509 101.239 C 39.956 104.611,30.063 114.339,26.480 125.852 C 24.186 133.222,24.186 266.778,26.480 274.148 C 30.063 285.661,39.956 295.389,51.509 298.761 C 55.282 299.863,66.522 300.000,152.876 300.000 L 250.000 300.000 249.994 305.273 C 249.972 324.725,242.663 334.583,226.574 336.858 C 216.390 338.298,212.506 341.927,212.506 350.000 C 212.506 366.486,238.302 366.994,257.617 350.889 L 262.500 346.818 267.260 350.780 C 286.808 367.047,312.494 366.605,312.494 350.000 C 312.494 341.909,308.613 338.299,298.355 336.848 C 282.253 334.571,275.028 324.800,275.006 305.273 L 275.000 300.000 309.624 300.000 C 351.467 300.000,353.080 299.701,363.433 290.017 C 375.429 278.796,375.000 282.132,375.000 200.000 C 375.000 117.868,375.429 121.204,363.433 109.983 C 353.080 100.299,351.467 100.000,309.624 100.000 L 275.000 100.000 275.006 94.727 C 275.028 75.200,282.253 65.429,298.355 63.152 C 310.746 61.400,316.495 52.266,310.942 43.157 C 304.904 33.255,281.407 36.699,266.602 49.657 L 262.500 53.246 258.398 49.657 C 247.017 39.696,226.132 34.196,218.157 39.058 M250.000 200.000 L 250.000 275.000 156.836 274.981 C 62.965 274.963,59.307 274.856,55.529 272.041 C 50.064 267.967,50.391 272.544,50.391 200.000 C 50.391 127.412,50.059 132.037,55.565 127.933 C 59.195 125.227,61.541 125.161,156.445 125.080 L 250.000 125.000 250.000 200.000 M342.887 126.953 C 350.129 131.219,349.963 129.504,349.963 200.000 C 349.963 261.790,349.795 265.775,347.041 269.471 C 343.191 274.635,343.559 274.585,307.617 274.844 L 275.000 275.079 275.000 200.000 L 275.000 124.921 307.617 125.156 C 338.598 125.379,340.367 125.469,342.887 126.953 M80.657 151.558 C 72.949 156.258,72.949 168.742,80.657 173.442 C 82.780 174.736,84.600 174.995,91.602 174.997 L 100.000 175.000 100.003 208.398 C 100.006 240.347,100.073 241.907,101.558 244.343 C 106.258 252.051,118.742 252.051,123.442 244.343 C 124.927 241.907,124.994 240.347,124.997 208.398 L 125.000 175.000 133.398 174.997 C 140.400 174.995,142.220 174.736,144.343 173.442 C 152.051 168.742,152.051 156.258,144.343 151.558 C 140.401 149.155,84.599 149.155,80.657 151.558 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div class="flex flex-col items-start justify-center">
                                    <h1
                                        class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                        {{ $this->batchCounters?->total_encoding ?? 0 }}
                                    </h1>
                                    <p class="whitespace-nowrap text-xs font-semibold leading-none">
                                        Encoding
                                        Batches
                                    </p>
                                </div>
                            </a>
                            <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                                class="bg-white text-indigo-500 relative flex flex-1 items-center justify-start rounded shadow py-1 h-full">
                                <svg class="size-3 sm:size-5 m-1.5" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M161.328 51.270 C 122.331 56.989,89.750 74.415,66.570 101.953 C -8.847 191.547,44.224 332.733,159.354 348.791 C 171.009 350.417,175.294 349.185,179.062 343.125 C 181.187 339.707,182.324 290.485,180.440 283.487 C 179.161 278.737,174.798 274.582,170.310 273.841 C 104.494 262.968,85.030 174.672,140.431 138.302 C 188.284 106.887,260.591 148.646,255.036 204.489 C 254.152 213.374,255.687 212.500,240.970 212.500 C 220.400 212.500,213.643 218.482,219.788 231.250 C 221.464 234.733,283.522 299.929,286.902 301.758 C 289.924 303.393,295.190 303.529,298.658 302.061 C 301.431 300.888,365.021 234.611,367.051 230.778 C 369.891 225.416,367.815 217.817,362.538 214.260 C 360.057 212.589,359.184 212.500,345.197 212.500 L 330.469 212.500 330.469 201.199 C 330.469 130.070,279.678 68.650,208.257 53.411 C 199.102 51.458,169.315 50.099,161.328 51.270 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div class="flex flex-col items-start justify-center">
                                    <h1
                                        class="text-xs sm:text-sm md:text-base text-indigo-950 font-bold leading-normal">
                                        {{ $this->batchCounters?->total_revalidate ?? 0 }}
                                    </h1>
                                    <p class="whitespace-nowrap text-xs font-semibold leading-none">
                                        Revlidate
                                        Batches
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Center to Bottom --}}
                <div class="flex flex-col lg:grid lg:grid-cols-3 gap-2 lg:h-[72vh]">

                    {{-- Left Side --}}
                    <div class="flex flex-col gap-2 lg:col-span-2 h-full">

                        {{-- Summary of Beneficiaries Dropdown --}}
                        <div class="flex items-center justify-between w-full lg:h-[10%]">
                            <h1 class="hidden text-xl md:block font-bold ms-3">
                                Summary of Beneficiaries
                            </h1>
                            <h1 class="text-base md:hidden font-bold ms-3">
                                Summary
                            </h1>

                            {{-- Projects Dropdown --}}
                            <div x-data="{ show: false }" class="relative z-30">

                                {{-- Button --}}
                                <button type="button"
                                    @if ($this->implementations->isEmpty() && !$searchProjects) disabled
                                @else
                                @click="show = !show;" @endif
                                    class="flex items-center justify-between gap-2 whitespace-nowrap w-full border-2 outline-none text-xs sm:text-sm font-bold px-3 py-2 rounded
                                    disabled:bg-gray-50 disabled:text-gray-500 disabled:border-gray-300 
                                    bg-indigo-100 hover:bg-indigo-800 active:bg-indigo-900 
                                    text-indigo-700 hover:text-indigo-50 active:text-indigo-50
                                    border-indigo-700 hover:border-transparent active:border-transparent duration-200 ease-in-out">
                                    {{ $currentImplementation }}

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-3.5 md:size-5">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Content --}}
                                <div x-show="show" @click.away="show = !show; $wire.set('searchProjects', null);"
                                    :class="{
                                        'block': show,
                                        'hidden': !show,
                                    }"
                                    class="hidden end-0 absolute rounded p-3 mt-2 shadow-lg border border-indigo-100 text-indigo-1100 bg-white">

                                    {{-- Search Bar --}}
                                    <label for="searchProjects"
                                        class="relative flex items-center justify-center duration-200 ease-in-out rounded border outline-none focus:ring-0
                                                {{ $this->implementations->isEmpty() && !$searchProjects ? 'text-gray-500 border-gray-300' : 'text-gray-500 hover:text-indigo-700 focus-within:text-indigo-700 hover:bg-indigo-50 focus-within:bg-indigo-50 border-gray-300 hover:border-indigo-700 focus-within:border-indigo-700' }}">
                                        <div
                                            class="absolute start-2 flex items-center justify-center pointer-events-none">
                                            {{-- Loading Icon --}}
                                            <svg class="size-4 animate-spin" wire:loading wire:target="searchProjects"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Search Icon --}}
                                            <svg class="size-4" wire:loading.remove wire:target="searchProjects"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>

                                        <input id="searchProjects" autofocus autocomplete="off"
                                            wire:model.live.debounce.300ms="searchProjects" type="text"
                                            class="peer bg-transparent outline-none border-none focus:ring-0 rounded w-full ps-8 text-xs {{ $this->implementations->isEmpty() && !$searchProjects ? 'placeholder-gray-500' : 'text-indigo-1100 placeholder-gray-500 hover:placeholder-indigo-700 focus:placeholder-indigo-700' }}"
                                            placeholder="Search for projects">
                                    </label>

                                    <div
                                        class="mt-2 text-xs overflow-y-auto h-44 w-56 border border-transparent scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                        @if ($this->implementations->isNotEmpty())
                                            @foreach ($this->implementations as $key => $implementation)
                                                <span wire:key={{ $key }}>
                                                    <button type="button"
                                                        wire:click="selectImplementation('{{ encrypt($implementation->id) }}')"
                                                        @click="show= !show;" wire:loading.attr="disabled"
                                                        aria-label="{{ __('Implementation') }}"
                                                        class="text-left font-medium w-full flex items-center justify-start gap-2 px-3 py-1.5 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                        <span class="p-1 rounded font-semibold"
                                                            :class="{
                                                                'bg-amber-200 text-amber-800': {{ json_encode($implementation?->status === 'pending') }},
                                                                'bg-lime-200 text-lime-800': {{ json_encode($implementation?->status === 'implementing') }},
                                                                'bg-sky-200 text-sky-800': {{ json_encode($implementation?->status === 'concluded') }},
                                                            }">
                                                            @if ($implementation?->status === 'pending')
                                                                PEN
                                                            @elseif($implementation?->status === 'implementing')
                                                                IMP
                                                            @elseif($implementation?->status === 'concluded')
                                                                CLD
                                                            @endif
                                                        </span>
                                                        @if ($implementation->project_title)
                                                            <span>
                                                                {{ $implementation->project_num }}
                                                                <span
                                                                    class="text-gray-500">({{ $implementation->project_title }})</span>
                                                            </span>
                                                        @else
                                                            <span>
                                                                {{ $implementation->project_num }}
                                                            </span>
                                                        @endif
                                                    </button>
                                                </span>
                                            @endforeach
                                        @else
                                            <div
                                                class="flex flex-col font-medium flex-1 items-center justify-center text-center size-full border border-gray-300 bg-gray-100 text-gray-500 rounded p-2">
                                                @if ($searchProjects)
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd"></path>
                                                        </g>
                                                    </svg>
                                                    <p>No projects found.</p>
                                                    <p>Try a different <span class=" text-indigo-900">search
                                                            term</span>.</p>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd"></path>
                                                        </g>
                                                    </svg>
                                                    <p>No projects found.</p>
                                                    <p>Try creating a <span class=" text-indigo-900">new
                                                            project</span> in Implementations page.</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Per Implementation | Print && Export | Donut Charts --}}
                        <div class="flex flex-col w-full bg-white rounded shadow p-3 lg:h-[60%]">

                            {{-- Headers --}}
                            <div class="flex justify-between mb-3">

                                {{-- Per Implementation --}}
                                <div class="flex justify-center items-center gap-2">

                                    {{-- Title of the Chart --}}
                                    <div class="flex items-center justify-center gap-2 text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M174.219 1.229 C 54.472 18.124,-24.443 135.741,6.311 251.484 C 9.642 264.022,18.559 287.500,19.989 287.500 C 20.159 287.500,25.487 284.951,31.829 281.836 C 38.171 278.721,43.450 276.139,43.562 276.100 C 43.673 276.060,42.661 273.599,41.313 270.631 C 20.301 224.370,21.504 168.540,44.499 122.720 C 91.474 29.119,207.341 -2.229,294.805 55.000 L 303.283 60.547 296.563 60.773 L 289.844 60.998 289.844 75.030 L 289.844 89.063 316.041 89.063 C 356.109 89.062,354.775 90.537,350.877 50.558 C 349.488 36.310,348.202 24.504,348.019 24.321 C 347.676 23.978,328.468 25.531,323.192 26.328 L 320.212 26.778 320.757 33.742 L 321.302 40.706 315.480 36.529 C 276.374 8.472,220.985 -5.369,174.219 1.229 M146.501 97.750 C 118.151 111.473,94.683 122.973,94.351 123.305 C 94.019 123.637,117.528 137.000,146.593 153.000 L 199.439 182.092 252.454 153.019 C 281.612 137.028,305.456 123.743,305.440 123.496 C 305.396 122.820,200.285 72.645,199.085 72.727 C 198.514 72.766,174.851 84.026,146.501 97.750 M367.815 118.385 L 356.334 124.187 358.736 129.476 C 379.696 175.622,378.473 231.507,355.501 277.280 C 308.659 370.616,191.853 402.240,105.195 345.048 L 96.718 339.453 103.828 339.228 L 110.938 339.004 110.938 324.971 L 110.938 310.938 83.858 310.938 L 56.778 310.937 53.464 312.880 C 49.750 315.056,46.875 319.954,46.875 324.105 C 46.875 327.673,51.612 375.310,52.006 375.704 C 52.327 376.025,69.823 374.588,76.418 373.699 L 79.790 373.245 79.242 366.245 L 78.695 359.245 84.074 363.146 C 180.358 432.973,317.505 400.914,375.933 294.922 C 405.531 241.229,408.161 173.609,382.825 117.732 C 379.977 111.450,381.685 111.375,367.815 118.385 M75.190 209.482 L 75.391 269.080 129.223 295.087 C 158.831 309.391,183.177 321.094,183.325 321.094 C 183.473 321.094,183.585 295.869,183.574 265.039 L 183.554 208.984 130.305 179.688 C 101.018 163.574,76.591 150.277,76.023 150.137 C 75.172 149.928,75.026 160.392,75.190 209.482 M269.139 179.604 L 215.234 209.207 215.034 265.236 C 214.844 318.400,214.904 321.239,216.206 320.749 C 216.961 320.466,241.562 308.738,270.876 294.687 L 324.174 269.141 324.197 209.570 C 324.209 176.807,323.954 150.000,323.631 150.000 C 323.307 150.000,298.786 163.322,269.139 179.604 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h1 class="text-base md:text-xl font-bold leading-none">
                                            Per Project
                                        </h1>
                                        @if ($this->implementation?->status)
                                            <span class="text-xs md:text-sm px-3 py-1 rounded font-semibold capitalize"
                                                :class="{
                                                    'bg-amber-200 text-amber-800': {{ json_encode($this->implementation?->status === 'pending') }},
                                                    'bg-lime-200 text-lime-800': {{ json_encode($this->implementation?->status === 'implementing') }},
                                                    'bg-indigo-200 text-indigo-800': {{ json_encode($this->implementation?->status === 'concluded') }},
                                                }">
                                                {{ $this->implementation?->status }}
                                            </span>
                                        @endif
                                    </div>

                                    <span class="relative" x-data="{ tooltip: false }">
                                        {{-- The "?" Popover --}}
                                        <svg @mouseleave="tooltip = false;" @mouseenter="tooltip = true;"
                                            class="size-3.5 text-gray-500 hover:text-indigo-700 outline-none duration-300 ease-in-out cursor-pointer"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                        </svg>

                                        {{-- Popover for `Per Implementation` --}}
                                        <div x-cloak x-show="tooltip" x-transition.opacity role="tooltip"
                                            class="text-left absolute z-50 top-full mt-2 -left-32 md:left-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-zinc-50">
                                            <div class="flex flex-col text-xs sm:text-sm gap-3">
                                                <div>
                                                    <h3 class="font-medium text-indigo-400">Per Implementation
                                                    </h3>
                                                    <p>These charts represent the total number <br>
                                                        of Males and Females per implementation. <br>
                                                        In each implementation, it is also divided <br>
                                                        by people with disability and senior citizens.
                                                    </p>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-indigo-400">Print and Export</h3>
                                                    <p>The information presented here can be <br>
                                                        can be printed as A4 paper size and also <br>
                                                        exported as XLSX or CSV files.
                                                    </p>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-indigo-400">Pending (PEN), Implementing
                                                        (IMP) and
                                                        Concluded (CLD)
                                                    </h3>
                                                    <p>It indicates the status of the implementation <br>
                                                        project whether it is still pending, <br>
                                                        currently in the implementing phase,<br>
                                                        or the implementation project has concluded.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>

                                {{-- Print && Export button --}}
                                <div class="flex items-center justify-center gap-2">

                                    <span class="relative" x-data="{ pop: false }">
                                        {{-- Print Button --}}
                                        <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;"
                                            @if ($this->batches->isNotEmpty()) wire:click="printSummary"
                                            @else
                                                disabled @endif
                                            class="inline-flex items-center justify-center p-1.5 duration-200 ease-in-out outline-none rounded-md disabled:bg-white disabled:text-gray-500 text-indigo-700 hover:text-indigo-50 focus:text-indigo-50 active:text-indigo-50 hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:ring-2 focus:ring-indigo-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M124.097 38.581 C 108.307 44.266,104.391 51.875,104.050 77.539 L 103.804 96.094 200.000 96.094 L 296.196 96.094 295.950 77.539 C 295.628 53.341,293.166 47.530,280.275 40.539 L 275.391 37.891 201.172 37.722 C 141.440 37.586,126.396 37.753,124.097 38.581 M73.438 121.531 C 57.106 125.052,42.955 138.554,38.653 154.723 C 36.900 161.309,36.966 255.134,38.728 261.934 C 43.270 279.462,57.931 292.606,76.367 295.678 L 79.688 296.231 79.688 270.936 C 79.688 238.426,80.677 234.235,90.771 224.007 C 102.288 212.337,100.272 212.548,200.000 212.548 C 299.759 212.548,297.603 212.321,309.330 224.049 C 319.253 233.972,320.312 238.495,320.312 270.936 L 320.313 296.231 323.633 295.678 C 342.069 292.606,356.730 279.462,361.272 261.934 C 363.034 255.134,363.100 161.309,361.347 154.723 C 356.995 138.368,342.831 124.986,326.172 121.488 C 318.193 119.813,81.221 119.853,73.438 121.531 M289.059 156.342 C 297.012 160.812,297.255 171.147,289.543 176.905 C 285.733 179.749,263.827 179.322,259.899 176.327 C 252.667 170.811,253.131 160.836,260.827 156.373 C 265.105 153.892,284.662 153.871,289.059 156.342 M109.277 239.405 C 103.650 243.229,103.834 241.304,104.078 293.917 L 104.297 341.016 106.375 345.449 C 109.153 351.374,113.792 356.243,119.725 359.461 L 124.609 362.109 200.000 362.109 L 275.391 362.109 280.275 359.461 C 286.208 356.243,290.847 351.374,293.625 345.449 L 295.703 341.016 295.922 293.960 C 296.167 241.112,296.342 242.837,290.362 239.141 L 287.706 237.500 199.894 237.500 L 112.081 237.500 109.277 239.405 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            <span class="sr-only">Print this Implementation</span>
                                        </button>

                                        {{-- Popover --}}
                                        <div x-cloak x-show="pop"
                                            class="absolute z-50 top-full mt-1 whitespace-nowrap mx-auto right-0 lg:right-auto lg:left-1/2 lg:transform lg:-translate-x-1/2 rounded p-2 shadow text-xs lg:text-sm font-normal text-center border bg-zinc-900 border-zinc-300 text-zinc-50">
                                            @if ($this->batches->isNotEmpty())
                                                Print this Implementation
                                            @else
                                                You may able to <span class="text-indigo-400">print</span> the summary
                                                <br>
                                                when there are beneficiaries <br>
                                                in any batches or barangays.
                                            @endif
                                        </div>
                                    </span>

                                    <span class="relative" x-data="{ pop: false }">
                                        {{-- Export Button --}}
                                        <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;"
                                            @if ($this->beneficiaryTotalByImplementation > 0) wire:click="showExport"
                                        @else
                                        disabled @endif
                                            class="inline-flex items-center justify-center p-1.5 duration-200 ease-in-out outline-none rounded-md disabled:bg-white disabled:text-gray-500 text-indigo-700 hover:text-indigo-50 focus:text-indigo-50 active:text-indigo-50 hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:ring-2 focus:ring-indigo-300">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M205.223 139.115 C 208.456 140.341,259.848 191.840,261.742 195.752 C 266.646 205.882,255.514 216.701,245.595 211.446 C 244.365 210.794,236.504 203.379,228.125 194.967 L 212.891 179.672 212.500 242.123 C 212.115 303.671,212.086 304.605,210.499 306.731 C 204.772 314.399,195.433 314.184,190.039 306.258 L 188.281 303.675 188.281 241.528 L 188.281 179.380 172.461 195.051 C 160.663 206.736,155.883 210.967,153.660 211.688 C 144.244 214.742,135.529 205.084,139.108 195.559 C 139.978 193.241,188.052 144.418,193.281 140.540 C 196.591 138.086,201.092 137.549,205.223 139.115 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            <span class="sr-only">Export Options</span>
                                        </button>

                                        {{-- Popover --}}
                                        <div x-cloak x-show="pop"
                                            class="absolute z-50 top-full mt-1 whitespace-nowrap mx-auto right-0 lg:right-auto lg:left-1/2 lg:transform lg:-translate-x-1/2 rounded p-2 shadow text-xs lg:text-sm font-normal text-center border bg-zinc-900 border-zinc-300 text-zinc-50">
                                            @if ($this->beneficiaryTotalByImplementation > 0)
                                                Export Options
                                            @else
                                                You may able to <span class="text-indigo-400">export</span> the summary
                                                <br>
                                                when there are beneficiaries on <br>the implementation.
                                            @endif
                                        </div>
                                    </span>
                                </div>
                            </div>

                            <!-- Donut Charts -->
                            <div class="relative grid grid-cols-5 grow place-items-center place-content-center h-full">

                                {{-- If all series are null... --}}
                                @if ($this->implementationTotalByYear === 0)
                                    <div
                                        class="bg-white absolute text-gray-400 col-span-full z-20 size-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-16 sm:size-24 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            <span>You haven't made any implementation projects this year yet.</span>
                                            <br>
                                            Try a creating a new one in the <span
                                                class="text-indigo-700">Implementations</span> page.
                                        </h2>
                                    </div>
                                @elseif ($this->batches->total() === 0)
                                    <div
                                        class="bg-white absolute text-gray-400 col-span-full z-20 size-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-16 sm:size-24 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            There are no <span class="text-indigo-700">batches</span> yet. <br>
                                            Assign a batch in the <span class="text-indigo-700">Implementations</span>
                                            page.
                                        </h2>
                                    </div>
                                @elseif ($this->implementations->isEmpty() && !$searchProjects && $this->isDateRangeChanged)
                                    <div
                                        class="bg-white absolute text-gray-400 col-span-full z-20 size-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-16 sm:size-24 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            <span>No implementations found.</span> <br>
                                            Try a different <span class="text-indigo-700">date range</span>.
                                        </h2>
                                    </div>
                                @elseif (is_null($this->total_beneficiaries) &&
                                        is_null($this->total_pwds) &&
                                        is_null($this->total_seniors) &&
                                        is_null($this->total_contract) &&
                                        is_null($this->total_payroll))
                                    <div
                                        class="bg-white absolute text-gray-400 col-span-full z-20 size-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-16 sm:size-24 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            No <span class="text-indigo-700">beneficiaries</span> found in any batches.
                                        </h2>
                                    </div>
                                @endif

                                {{-- Overall Chart --}}
                                <div class="relative">
                                    @if ($this->total_beneficiaries === 0)
                                        <div
                                            class="bg-white text-gray-300 absolute z-10 size-full flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-12 sm:size-20 mb-4 text-zinc-300"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            <h2 class="text-gray-500 text-xs font-medium text-center">
                                                Not enough data <br> for overall values.
                                            </h2>
                                        </div>
                                    @endif

                                    {{-- Overall Chart --}}
                                    <div wire:ignore id="overall-chart"></div>
                                </div>

                                {{-- PWDs Chart --}}
                                <div class="relative">
                                    @if ($this->total_pwds === 0)
                                        <div
                                            class="bg-white text-gray-300 absolute z-10 size-full flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-12 sm:size-20 mb-4 text-zinc-300"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            <h2 class="text-gray-500 text-xs font-medium text-center">
                                                Not enough data <br> for <span
                                                    class="font-semibold text-indigo-700">PWDs</span>.
                                            </h2>
                                        </div>
                                    @endif

                                    {{-- PWD Chart --}}
                                    <div wire:ignore id="pwd-chart"></div>
                                </div>

                                {{-- Senior Citizens Chart --}}
                                <div class="relative">
                                    @if ($this->total_seniors === 0)
                                        <div
                                            class="bg-white text-gray-300 absolute z-10 size-full flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-12 sm:size-20 mb-4 text-zinc-300"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            <h2 class="text-gray-500 text-xs font-medium text-center">
                                                Not enough data for <br> <span
                                                    class="font-semibold text-indigo-700">senior citizens</span>.
                                            </h2>
                                        </div>
                                    @endif

                                    {{-- Senior Citizen Chart --}}
                                    <div wire:ignore id="senior-chart"></div>
                                </div>

                                {{-- Contract of Service Chart --}}
                                <div class="relative">
                                    @if ($this->total_contract === 0)
                                        <div
                                            class="bg-white text-gray-300 absolute z-10 size-full flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-12 sm:size-20 mb-4 text-zinc-300"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            <h2 class="text-gray-500 text-xs font-medium text-center">
                                                Not enough data for <br> <span
                                                    class="font-semibold text-indigo-700">COS</span>.
                                            </h2>
                                        </div>
                                    @endif

                                    {{-- Contract of Service Chart --}}
                                    <div wire:ignore id="contract-chart"></div>
                                </div>

                                {{-- Payroll Chart --}}
                                <div class="relative">
                                    @if ($this->total_payroll === 0)
                                        <div
                                            class="bg-white text-gray-300 absolute z-10 size-full flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-12 sm:size-20 mb-4 text-zinc-300"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>

                                            <h2 class="text-gray-500 text-xs font-medium text-center">
                                                Not enough data for <br> <span
                                                    class="font-semibold text-indigo-700">Payroll</span>.
                                            </h2>
                                        </div>
                                    @endif

                                    {{-- Payroll Chart --}}
                                    <div wire:ignore id="payroll-chart"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Total Counts || Total Contract and Payroll --}}
                        <div class="flex gap-3 w-full lg:h-[30%] text-xs sm:text-sm">
                            {{-- Total Counters --}}
                            <div class="flex flex-col gap-3 p-3 w-[60%] rounded shadow bg-white">
                                <div class="flex flex-1 items-center justify-between bg-indigo-50 rounded px-2">
                                    <div class="flex items-center gap-2 text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-6">
                                            <path fill-rule="evenodd"
                                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                                clip-rule="evenodd" />
                                            <path
                                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                                        </svg>
                                        <p class="font-bold">Total Beneficiaries</p>
                                    </div>
                                    <div class="flex text-indigo-1100">
                                        <p>{{ $this->totalCounters->total_beneficiaries ?? 0 }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-1 items-center justify-between bg-indigo-50 rounded px-2">
                                    <div class="flex items-center gap-2 text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" class="size-6"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M185.156 14.470 C 159.115 19.814,137.967 38.772,129.837 64.063 C 125.915 76.262,124.843 107.436,128.320 108.137 C 134.997 109.481,134.718 109.577,136.190 105.432 C 144.244 82.756,178.498 81.434,189.432 103.377 L 191.416 107.357 200.053 107.393 L 208.690 107.430 210.161 104.105 C 220.111 81.610,255.662 82.489,263.810 105.432 C 265.282 109.577,265.003 109.481,271.680 108.137 C 275.157 107.436,274.085 76.262,270.163 64.063 C 258.753 28.571,221.721 6.967,185.156 14.470 M119.922 46.856 C 109.885 49.437,104.118 55.725,101.896 66.513 C 99.671 77.311,99.470 114.063,101.636 114.063 C 102.033 114.063,103.788 113.026,105.537 111.759 C 107.286 110.492,109.920 109.059,111.390 108.573 L 114.063 107.691 114.063 92.604 C 114.063 73.252,116.143 62.017,121.767 50.993 C 123.504 47.589,123.853 46.037,122.852 46.174 C 122.529 46.218,121.211 46.525,119.922 46.856 M276.563 46.926 C 276.563 47.362,277.314 49.192,278.233 50.993 C 283.857 62.017,285.938 73.252,285.938 92.604 L 285.938 107.691 288.610 108.573 C 290.080 109.059,292.714 110.492,294.463 111.759 C 298.858 114.943,299.013 114.832,299.647 108.065 C 302.143 81.438,297.890 57.494,289.596 51.465 C 284.603 47.835,276.563 45.035,276.563 46.926 M155.152 101.771 C 140.569 110.112,144.976 131.868,161.553 133.371 C 180.469 135.086,187.875 110.124,171.004 101.516 C 166.740 99.341,159.188 99.462,155.152 101.771 M228.828 101.555 C 212.115 110.389,219.634 135.077,238.516 133.364 C 255.140 131.857,259.401 109.148,244.487 101.540 C 240.463 99.487,232.727 99.495,228.828 101.555 M115.316 120.343 C 101.470 124.486,104.271 144.656,119.015 146.987 C 125.115 147.951,125.487 148.649,128.128 164.091 C 132.356 188.816,135.767 194.069,157.190 208.832 C 192.189 232.952,207.811 232.952,242.810 208.832 C 264.233 194.069,267.644 188.816,271.872 164.091 C 274.514 148.645,274.884 147.951,280.995 146.985 C 293.363 145.030,298.064 132.364,289.657 123.648 C 286.008 119.864,283.191 119.214,275.153 120.299 C 266.652 121.446,265.625 121.736,265.625 122.995 C 265.625 125.648,259.822 135.320,256.408 138.357 C 240.024 152.932,213.347 145.169,208.411 124.389 C 207.389 120.085,206.430 119.531,200.000 119.531 C 194.085 119.531,192.402 120.253,191.959 122.980 C 187.294 151.725,146.800 153.793,135.456 125.866 L 133.676 121.484 127.190 120.576 C 119.416 119.488,118.249 119.465,115.316 120.343 M222.432 155.564 C 239.773 161.923,246.526 191.054,232.296 198.120 C 227.690 200.407,172.310 200.407,167.704 198.120 C 153.694 191.164,159.906 162.774,176.953 155.848 C 183.735 153.092,215.156 152.896,222.432 155.564 M180.821 167.505 C 175.749 169.714,172.669 175.565,172.661 183.008 L 172.656 186.719 200.000 186.719 L 227.344 186.719 227.339 183.008 C 227.331 175.455,224.216 169.648,219.007 167.472 C 215.440 165.981,184.256 166.009,180.821 167.505 M173.008 236.015 C 177.916 267.945,222.489 268.275,226.939 236.415 C 227.428 232.911,227.354 232.647,226.041 233.211 C 206.867 241.453,193.159 241.450,173.935 233.202 C 172.601 232.629,172.523 232.864,173.008 236.015 M151.563 244.710 C 147.480 246.008,144.035 247.160,143.907 247.271 C 143.423 247.689,146.255 258.509,148.915 266.406 C 157.573 292.106,175.943 319.939,195.335 336.739 L 199.863 340.661 205.887 335.370 C 223.052 320.289,239.614 296.125,248.735 272.849 C 251.402 266.046,256.250 250.036,256.250 248.035 C 256.250 247.259,241.647 242.188,239.412 242.188 C 238.992 242.188,237.993 244.033,237.192 246.289 C 225.568 279.022,180.421 281.717,164.851 250.608 C 163.128 247.166,161.719 243.864,161.719 243.269 C 161.719 241.767,160.095 241.998,151.563 244.710 M113.133 257.386 C 77.601 269.331,60.076 283.848,49.109 310.417 C 41.690 328.392,36.625 379.665,41.761 384.801 L 43.679 386.719 200.000 386.719 L 356.321 386.719 358.239 384.801 C 361.842 381.198,359.590 341.076,354.712 321.970 C 348.562 297.884,331.481 276.940,308.768 265.635 C 303.287 262.907,268.970 251.053,268.387 251.686 C 268.252 251.833,267.192 255.469,266.033 259.766 C 257.916 289.836,242.427 316.270,219.444 339.273 C 201.210 357.523,198.832 357.547,180.947 339.667 C 157.896 316.624,142.445 290.445,134.174 260.419 C 132.832 255.548,131.361 251.591,130.906 251.626 C 130.451 251.661,122.453 254.253,113.133 257.386 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>

                                        <p class="font-bold">Total Senior Citizens</p>
                                    </div>
                                    <div class="flex text-indigo-1100">
                                        <p>{{ $this->totalCounters->total_senior_citizen_beneficiaries ?? 0 }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-1 items-center justify-between bg-indigo-50 rounded px-2">
                                    <div class="flex items-center gap-2 text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" class="size-6"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M105.550 1.197 C 79.174 8.537,66.622 41.670,81.581 64.471 C 105.561 101.024,162.106 82.337,159.156 38.834 C 157.362 12.372,131.257 -5.957,105.550 1.197 M111.872 95.232 C 103.825 98.012,99.659 104.094,99.779 112.891 C 99.851 118.204,134.834 280.406,136.202 281.775 C 136.502 282.075,164.031 274.670,197.378 265.321 C 230.725 255.971,258.263 248.612,258.572 248.966 C 258.882 249.320,265.605 272.018,273.513 299.405 C 284.585 337.753,288.170 349.203,289.108 349.210 C 291.864 349.230,354.481 332.566,356.641 331.238 C 373.157 321.081,366.128 296.027,346.784 296.108 C 344.901 296.116,336.852 297.825,328.898 299.906 L 314.436 303.689 299.627 252.601 L 284.819 201.512 282.839 201.961 C 281.750 202.207,254.844 209.707,223.047 218.627 C 191.250 227.546,165.106 234.528,164.948 234.141 C 164.791 233.755,162.835 225.052,160.601 214.802 L 156.541 196.166 192.138 195.935 C 226.375 195.712,227.869 195.641,231.250 194.066 C 244.995 187.667,246.134 168.538,233.245 160.568 L 229.421 158.203 188.693 157.982 L 147.965 157.760 142.596 133.763 C 136.151 104.960,135.341 102.511,131.040 98.829 C 125.422 94.021,118.914 92.800,111.872 95.232 M94.132 188.946 C 26.843 225.633,13.366 313.960,66.890 367.485 C 132.997 433.592,247.417 393.276,257.421 300.352 C 257.876 296.123,250.946 267.873,248.968 265.895 C 248.765 265.692,241.882 267.392,233.673 269.672 L 218.747 273.819 219.334 277.730 C 226.373 324.581,184.582 367.776,137.916 361.882 C 69.977 353.301,48.041 266.552,103.969 227.631 C 106.634 225.776,108.951 224.122,109.118 223.955 C 109.285 223.788,107.653 215.273,105.492 205.033 C 103.331 194.793,101.563 186.307,101.563 186.176 C 101.563 185.354,98.899 186.347,94.132 188.946 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                        <p class="font-bold">Total PWDs</p>
                                    </div>
                                    <div class="flex text-indigo-1100">
                                        <p>{{ $this->totalCounters->total_pwd_beneficiaries ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Contract || Payroll --}}
                            <div class="flex flex-col gap-3 p-3 w-[40%] rounded shadow bg-white">
                                <div class="flex flex-1 items-center justify-between bg-indigo-50 rounded px-2">
                                    <div class="flex items-center gap-2 text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                            @mouseleave="tooltip = false;" @mouseenter="tooltip = true;"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M66.797 26.484 C 60.111 29.587,56.737 35.079,55.824 44.350 L 55.267 50.000 74.508 50.000 L 93.750 50.000 93.733 44.336 C 93.688 29.642,79.812 20.446,66.797 26.484 M120.313 26.563 C 117.845 29.030,117.845 370.970,120.312 373.438 C 122.777 375.902,364.723 375.902,367.188 373.438 C 368.739 371.886,368.750 370.833,368.750 229.688 L 368.750 87.500 341.132 87.500 C 302.389 87.500,306.250 91.345,306.250 52.759 L 306.250 25.000 214.063 25.000 C 122.917 25.000,121.857 25.018,120.313 26.563 M318.750 51.563 L 318.750 75.000 342.378 75.000 L 366.006 75.000 342.578 51.563 C 329.693 38.672,319.060 28.125,318.950 28.125 C 318.840 28.125,318.750 38.672,318.750 51.563 M41.797 63.984 C 31.167 68.916,30.904 70.111,30.610 114.844 C 30.297 162.456,30.304 162.500,37.500 162.500 C 44.065 162.500,43.750 164.609,43.750 120.678 C 43.750 74.453,43.636 75.000,53.242 75.000 L 55.469 75.000 55.469 193.750 L 55.469 312.500 74.609 312.500 L 93.750 312.500 93.750 187.500 L 93.750 62.500 69.336 62.517 C 47.219 62.533,44.628 62.671,41.797 63.984 M253.242 77.058 C 270.155 82.367,278.281 104.579,270.617 124.551 C 270.377 125.178,272.445 126.949,276.016 129.174 C 288.806 137.146,293.677 146.582,293.735 163.502 C 293.776 175.464,295.792 175.000,243.750 175.000 C 191.708 175.000,193.708 175.460,193.776 163.502 C 193.872 146.374,198.604 137.202,211.484 129.174 C 215.055 126.949,217.123 125.178,216.883 124.551 C 205.881 95.882,226.677 68.719,253.242 77.058 M235.033 89.079 C 226.730 93.465,223.442 107.022,227.489 120.184 C 231.981 134.793,255.519 134.793,260.011 120.184 C 261.364 115.782,261.900 103.754,260.916 99.847 C 258.325 89.559,244.787 83.926,235.033 89.079 M216.716 139.394 C 208.702 144.684,206.250 148.851,206.250 157.185 L 206.250 162.500 243.750 162.500 L 281.250 162.500 281.250 157.185 C 281.250 148.764,278.809 144.670,270.593 139.315 L 264.076 135.067 261.530 136.975 C 250.811 145.012,236.716 145.030,226.029 137.020 C 223.003 134.753,224.214 134.444,216.716 139.394 M326.950 201.120 C 331.706 203.107,331.706 209.393,326.950 211.380 C 322.936 213.057,164.564 213.057,160.550 211.380 C 155.897 209.436,155.792 203.147,160.379 201.153 C 164.182 199.500,322.996 199.468,326.950 201.120 M326.950 226.120 C 331.706 228.107,331.706 234.393,326.950 236.380 C 322.936 238.057,164.564 238.057,160.550 236.380 C 155.897 234.436,155.792 228.147,160.379 226.153 C 164.182 224.500,322.996 224.468,326.950 226.120 M209.794 271.975 C 217.197 276.971,224.198 295.409,224.216 309.961 L 224.219 312.500 230.494 312.500 C 240.491 312.500,244.092 314.948,242.635 320.755 C 241.848 323.892,238.097 325.000,228.267 325.000 C 214.673 325.000,212.500 323.353,212.500 313.053 C 212.500 299.406,206.943 281.250,202.767 281.250 C 200.316 281.250,196.036 295.861,194.505 309.460 C 193.040 322.466,191.288 325.339,185.679 323.931 C 183.115 323.288,182.386 321.913,180.947 315.014 C 180.313 311.972,179.294 308.528,178.682 307.360 L 177.569 305.237 174.416 309.454 C 172.682 311.774,170.188 315.825,168.873 318.456 C 166.437 323.333,165.388 324.219,162.045 324.219 C 157.076 324.219,155.755 318.882,159.089 312.279 C 166.789 297.032,175.238 291.032,183.664 294.824 C 184.669 295.277,185.176 294.382,186.330 290.121 C 191.094 272.520,200.184 265.491,209.794 271.975 M63.218 348.927 C 69.723 374.781,69.822 375.000,75.000 375.000 C 80.178 375.000,80.277 374.781,86.782 348.927 L 92.801 325.000 75.000 325.000 L 57.199 325.000 63.218 348.927 M251.950 338.620 C 256.706 340.607,256.706 346.893,251.950 348.880 C 247.963 350.546,152.037 350.546,148.050 348.880 C 143.397 346.936,143.292 340.647,147.879 338.653 C 151.658 337.010,248.023 336.980,251.950 338.620 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <p class="font-bold">Total COS Signed</p>
                                    </div>
                                    <div class="flex text-indigo-950">
                                        <p>{{ $this->totalCounters->total_contract_beneficiaries ?? 0 }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-1 items-center justify-between bg-indigo-50 rounded px-2">
                                    <div class="flex items-center gap-2 text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                            @mouseleave="tooltip = false;" @mouseenter="tooltip = true;"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M125.910 31.966 C 50.310 53.483,52.936 52.598,46.837 58.594 C 40.466 64.857,38.030 70.349,37.637 79.336 L 37.323 86.493 54.292 146.567 C 63.625 179.607,71.549 207.184,71.901 207.848 C 72.484 208.949,100.874 227.610,103.496 228.616 C 104.408 228.966,104.331 228.048,103.084 223.703 C 66.619 96.595,67.910 102.546,76.011 98.901 C 84.537 95.064,87.699 89.450,87.359 78.753 C 87.083 70.057,87.162 69.974,98.931 66.620 C 187.268 41.440,179.748 42.816,183.525 51.147 C 187.407 59.707,192.595 62.670,203.224 62.397 C 209.250 62.243,210.427 62.427,211.704 63.726 C 212.719 64.758,221.344 94.063,239.037 156.597 C 253.255 206.847,265.047 248.120,265.242 248.315 C 265.437 248.510,268.503 246.813,272.056 244.543 C 275.609 242.273,280.537 239.146,283.008 237.593 C 285.479 236.040,287.500 234.349,287.500 233.835 C 287.500 230.615,231.014 33.339,228.894 29.154 C 223.529 18.565,214.506 13.070,201.953 12.748 L 194.141 12.547 125.910 31.966 M240.640 26.591 C 241.474 28.149,244.967 39.445,246.851 46.680 L 247.716 50.000 263.976 50.000 C 284.858 50.000,287.500 51.009,287.500 58.984 C 287.500 66.000,296.260 74.924,303.204 74.983 C 313.048 75.066,312.500 70.626,312.500 150.321 L 312.500 218.662 324.985 210.698 L 337.470 202.734 337.289 125.000 L 337.109 47.266 334.811 42.578 C 331.878 36.596,325.904 30.622,319.922 27.689 L 315.234 25.391 277.496 25.167 L 239.758 24.943 240.640 26.591 M135.455 68.251 L 99.426 78.516 99.329 82.469 C 99.088 92.374,93.433 102.038,84.486 107.834 L 82.643 109.028 100.781 173.069 C 110.757 208.291,119.220 237.678,119.588 238.374 C 119.956 239.069,132.413 247.456,147.269 257.011 L 174.280 274.383 181.194 270.000 C 195.895 260.680,204.105 260.680,218.805 269.999 L 225.718 274.382 240.187 265.121 C 248.145 260.027,254.663 255.552,254.672 255.176 C 254.680 254.800,243.262 214.145,229.297 164.832 C 215.332 115.519,203.906 75.045,203.906 74.890 C 203.906 74.736,201.976 74.526,199.618 74.424 C 190.625 74.035,180.539 68.457,176.065 61.398 C 173.266 56.983,177.529 56.264,135.455 68.251 M251.744 63.867 C 258.915 90.807,298.280 227.266,298.828 227.083 C 300.434 226.548,300.243 85.939,298.637 85.930 C 291.221 85.889,276.563 71.285,276.563 63.938 C 276.563 62.601,275.677 62.500,263.971 62.500 C 252.362 62.500,251.409 62.607,251.744 63.867 M181.250 141.441 C 202.554 147.641,215.954 169.904,211.377 191.496 C 204.155 225.570,161.719 237.644,137.906 212.401 C 107.629 180.306,138.898 129.116,181.250 141.441 M163.882 151.658 C 133.772 155.864,127.801 198.153,155.467 211.264 C 162.788 214.734,175.357 214.839,182.181 211.488 C 215.060 195.342,200.150 146.593,163.882 151.658 M350.000 173.833 L 350.000 194.521 355.343 191.096 C 358.282 189.212,363.819 185.649,367.648 183.178 C 371.477 180.706,375.966 177.827,377.624 176.779 L 380.639 174.873 378.406 172.484 C 376.433 170.374,369.178 165.375,354.492 156.009 L 350.000 153.144 350.000 173.833 M34.705 162.975 C 23.729 169.978,19.027 174.100,20.503 175.425 C 22.378 177.108,56.321 198.627,56.623 198.324 C 56.791 198.157,54.660 190.116,51.888 180.455 C 49.116 170.795,46.489 161.572,46.050 159.961 C 45.024 156.193,45.553 156.053,34.705 162.975 M12.895 187.695 C 12.643 189.092,12.539 229.609,12.664 277.734 L 12.891 365.234 15.128 369.794 C 16.740 373.080,17.723 374.261,18.644 374.020 C 20.152 373.625,163.245 281.822,163.244 281.250 C 163.243 280.808,14.483 185.156,13.796 185.156 C 13.552 185.156,13.146 186.299,12.895 187.695 M311.156 233.051 C 270.242 259.346,236.768 281.035,236.771 281.250 C 236.778 281.864,380.897 374.219,381.848 374.219 C 382.316 374.219,383.691 372.197,384.904 369.727 L 387.109 365.234 387.336 277.734 C 387.551 194.914,387.419 185.096,386.097 185.199 C 385.795 185.223,352.071 206.756,311.156 233.051 M192.332 276.758 C 187.341 279.477,27.403 382.362,27.372 382.874 C 27.356 383.123,29.161 384.178,31.382 385.218 L 35.420 387.109 200.000 387.109 L 364.580 387.109 368.618 385.218 C 370.839 384.178,372.648 383.123,372.638 382.874 C 372.616 382.303,209.250 277.288,206.400 276.012 C 202.826 274.412,195.970 274.776,192.332 276.758 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <p class="font-bold">Total Payroll Claimed</p>
                                    </div>
                                    <div class="flex text-indigo-950">
                                        <p>{{ $this->totalCounters->total_payroll_beneficiaries ?? 0 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side --}}
                    <div
                        class="col-span-1 flex flex-col shadow rounded justify-between max-lg:h-[72vh] h-full bg-white">

                        {{-- Batches --}}
                        <div class="flex flex-col text-indigo-1100 h-full">
                            <div class="flex flex-row items-center justify-between my-2 mx-4">
                                <div class="flex items-center justify-center gap-2 text-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 md:size-5"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p class="text-base md:text-xl font-bold">
                                        By Batch
                                    </p>
                                </div>
                                <p
                                    class="text-sm  md:text-base px-4 rounded-lg font-bold
                                    {{ $this->batches->total() <= 0 || $this->batches->total() === null ? 'bg-red-100 text-red-900' : 'bg-indigo-100 text-indigo-900' }} ">
                                    {{ $this->batches->total() }}
                                </p>
                            </div>

                            @if ($this->batches->isNotEmpty())
                                <div class="flex flex-col gap-4">
                                    @foreach ($this->batches as $key => $batch)
                                        <div wire:key="{{ $key }}"
                                            class="flex flex-row items-center rounded-lg bg-indigo-50 shadow-sm p-2 mx-4 my-2">
                                            <div class="flex flex-col w-full text-xs">

                                                {{-- Sectoral Title || Barangay Name --}}
                                                <span class="flex items-center gap-2 mx-1 mb-2">
                                                    @if ($batch->is_sectoral)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M174.877 1.190 C 150.230 4.553,145.323 7.118,145.315 16.640 C 145.307 26.849,151.399 30.195,165.022 27.466 C 235.741 13.300,306.821 42.988,346.982 103.464 C 394.840 175.532,381.979 273.808,317.169 331.266 L 311.494 336.298 312.155 331.626 C 313.932 319.082,310.321 313.281,300.737 313.281 C 291.377 313.281,290.080 315.708,285.491 341.797 C 279.828 373.988,278.913 372.711,311.789 378.500 C 335.304 382.641,337.028 382.601,341.231 377.815 C 348.164 369.919,343.315 359.191,332.031 357.457 C 325.345 356.430,325.282 356.361,328.629 353.676 C 398.280 297.789,420.248 193.555,379.705 111.328 C 342.140 35.140,257.930 -10.140,174.877 1.190 M63.753 18.780 C 50.680 22.692,54.137 40.576,68.359 42.606 C 74.490 43.480,74.610 43.845,69.981 47.539 C 1.836 101.915,-19.878 207.319,20.306 288.672 C 60.277 369.595,148.022 413.201,237.722 396.720 C 251.001 394.280,254.682 391.347,254.682 383.203 C 254.682 373.806,248.046 369.648,237.016 372.133 C 164.554 388.459,87.265 354.512,48.417 289.295 C 5.781 217.721,20.650 123.307,83.208 68.384 L 88.502 63.737 87.832 68.392 C 86.012 81.038,89.622 86.719,99.479 86.719 C 108.691 86.719,109.696 84.796,114.481 58.033 C 120.189 26.105,121.001 27.251,88.634 21.552 C 66.884 17.722,67.178 17.755,63.753 18.780 M180.469 80.061 C 128.992 88.795,87.063 130.739,80.096 180.469 C 79.825 182.402,79.419 184.951,79.193 186.133 L 78.782 188.281 133.532 188.281 L 188.281 188.281 188.281 133.594 C 188.281 90.322,188.077 78.923,187.305 78.986 C 186.768 79.030,183.691 79.514,180.469 80.061 M211.719 137.293 L 211.719 195.709 252.619 236.603 L 293.519 277.498 296.280 274.101 C 324.959 238.804,329.302 183.391,306.647 141.797 C 288.913 109.237,253.587 84.324,219.141 80.083 C 216.992 79.819,214.443 79.439,213.477 79.239 L 211.719 78.876 211.719 137.293 M79.193 213.867 C 79.419 215.049,79.825 217.598,80.096 219.531 C 82.636 237.663,96.013 267.615,105.342 276.057 C 106.609 277.203,108.970 275.021,139.481 244.504 L 172.259 211.719 125.520 211.719 L 78.782 211.719 79.193 213.867 M161.053 254.963 L 122.502 293.519 125.900 296.280 C 134.974 303.653,149.914 311.490,162.519 315.489 C 200.346 327.490,245.191 319.770,274.100 296.280 L 277.498 293.519 238.947 254.963 C 217.744 233.757,200.218 216.406,200.000 216.406 C 199.782 216.406,182.256 233.757,161.053 254.963 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M194.141 46.901 C 158.281 51.676,144.428 96.671,171.188 121.446 C 191.560 140.307,225.988 134.509,238.286 110.147 C 254.478 78.071,229.439 42.200,194.141 46.901 M120.365 57.918 C 109.608 59.702,98.078 68.313,93.195 78.210 C 89.275 86.153,89.276 86.253,93.242 88.982 C 103.342 95.931,109.956 106.799,112.854 121.208 L 113.270 123.275 119.078 121.431 C 130.268 117.877,139.313 118.293,150.678 122.883 L 155.375 124.780 157.861 122.215 L 160.347 119.650 158.790 117.442 C 150.160 105.212,147.585 86.895,152.526 72.878 L 154.513 67.240 152.061 65.371 C 143.287 58.685,131.893 56.006,120.365 57.918 M266.797 57.843 C 259.714 59.295,251.048 63.790,247.260 67.975 C 245.881 69.499,245.876 69.747,247.153 73.796 C 251.146 86.465,249.598 102.176,243.295 112.929 C 240.911 116.998,240.946 117.753,243.668 121.036 L 245.887 123.713 252.244 121.612 C 260.740 118.804,268.298 118.246,275.781 119.876 C 279.004 120.577,282.522 121.341,283.600 121.573 C 285.472 121.976,285.603 121.759,286.570 116.661 C 288.740 105.213,297.836 92.169,307.721 86.328 C 312.433 83.544,312.375 83.878,309.180 77.854 C 301.335 63.064,283.168 54.487,266.797 57.843 M321.395 88.321 C 307.641 92.681,297.763 103.007,294.181 116.766 C 292.453 123.402,292.589 126.304,294.678 127.422 C 302.838 131.790,311.116 144.022,314.400 156.566 L 316.016 162.742 320.313 164.206 C 350.793 174.595,380.846 144.272,370.335 113.734 C 363.371 93.504,341.261 82.023,321.395 88.321 M61.328 88.265 C 25.461 93.987,15.227 141.033,45.526 160.904 C 58.379 169.333,83.249 169.475,84.717 161.128 C 86.865 148.921,96.081 134.256,104.867 129.066 C 107.589 127.458,107.723 124.304,105.394 116.684 C 99.487 97.362,80.611 85.189,61.328 88.265 M121.484 128.096 C 83.787 139.471,84.056 191.917,121.875 204.318 C 132.254 207.721,154.688 203.702,154.688 198.439 C 154.688 192.588,162.098 178.761,168.699 172.295 C 173.360 167.729,173.809 164.573,171.131 155.207 C 165.068 134.006,142.585 121.729,121.484 128.096 M255.469 128.340 C 234.842 135.399,220.775 160.335,232.370 169.289 C 240.517 175.581,247.403 186.634,249.461 196.723 C 250.912 203.836,254.970 205.801,267.969 205.684 C 298.479 205.411,316.557 174.099,302.178 146.435 C 294.111 130.915,272.457 122.527,255.469 128.340 M172.852 135.699 C 169.782 136.107,169.796 135.783,172.649 140.378 C 175.513 144.990,178.024 151.967,178.586 156.874 C 179.476 164.635,178.846 164.226,185.737 161.518 C 195.722 157.593,205.947 157.553,219.301 161.388 C 220.678 161.784,221.095 161.514,221.382 160.043 C 223.158 150.950,223.965 148.346,226.703 142.875 C 228.428 139.429,229.600 136.371,229.309 136.078 C 228.729 135.498,176.974 135.150,172.852 135.699 M190.926 168.790 C 162.114 177.924,152.969 213.737,174.064 234.831 C 198.337 259.105,239.436 243.694,241.952 209.375 C 243.931 182.374,216.614 160.646,190.926 168.790 M315.613 170.426 C 315.578 178.478,310.295 191.425,304.247 198.279 C 302.238 200.556,300.725 202.515,300.883 202.633 C 301.042 202.751,302.754 203.433,304.688 204.148 C 328.660 213.018,343.750 236.189,343.750 264.131 L 343.750 272.656 347.901 272.656 C 360.979 272.656,382.777 267.994,396.289 262.307 L 400.000 260.745 400.000 236.005 C 400.000 201.142,396.494 190.873,380.469 178.794 C 368.911 170.082,362.997 168.641,336.914 168.181 L 315.625 167.805 315.613 170.426 M39.144 170.739 C 24.808 173.849,11.662 184.308,5.223 197.725 C 0.261 208.064,0.000 210.054,0.000 237.523 L -0.000 261.687 2.930 263.173 C 12.853 268.208,41.036 274.219,54.717 274.219 C 55.877 274.219,56.091 272.820,56.480 262.695 C 57.564 234.458,73.009 212.707,97.874 204.402 C 99.070 204.003,98.769 203.333,95.711 199.577 C 89.391 191.819,84.421 179.747,84.387 172.070 L 84.375 169.531 64.258 169.593 C 50.235 169.637,42.627 169.984,39.144 170.739 M108.984 208.357 C 90.552 210.802,73.470 225.302,67.748 243.359 C 66.102 248.554,66.016 250.136,66.016 274.975 L 66.016 301.123 73.828 303.315 C 88.950 307.558,106.596 310.873,120.508 312.084 L 125.781 312.544 125.809 304.123 C 125.900 275.786,140.754 252.905,164.844 243.992 C 166.777 243.277,168.476 242.608,168.618 242.506 C 168.760 242.404,167.264 240.340,165.292 237.920 C 159.307 230.572,155.214 221.159,154.233 212.486 L 153.704 207.813 132.516 207.930 C 120.863 207.994,110.273 208.186,108.984 208.357 M250.768 209.497 C 250.709 217.388,245.538 230.296,239.435 237.789 C 235.606 242.489,235.388 242.969,237.077 242.969 C 239.110 242.969,251.213 249.622,255.943 253.339 C 270.483 264.765,277.687 280.204,278.675 302.049 L 279.157 312.713 286.478 312.198 C 301.860 311.117,320.331 306.963,330.273 302.348 L 335.156 300.082 335.156 276.338 C 335.156 243.228,333.241 236.397,320.314 223.399 C 307.411 210.425,301.249 208.442,272.461 208.000 L 250.781 207.666 250.768 209.497 M178.906 249.175 C 158.169 252.154,141.821 266.535,136.228 286.719 C 135.011 291.110,134.817 295.172,134.793 316.756 L 134.766 341.716 142.188 343.909 C 185.461 356.695,235.935 356.310,265.356 342.971 L 270.502 340.637 270.108 316.217 C 269.682 289.788,269.319 287.091,264.927 277.734 C 258.759 264.594,246.659 254.534,231.850 250.233 C 227.179 248.876,186.628 248.066,178.906 249.175 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>
                                                    @endif
                                                    <p
                                                        class="text-sm w-[85vw] lg:w-[25vw] xl:w-[23vw] sm:text-base font-bold whitespace-nowrap overflow-x-auto scrollbar-none select-text">
                                                        {{ $batch->is_sectoral ? $batch->sector_title : $batch->barangay_name }}
                                                    </p>
                                                    <span
                                                        class="text-center rounded font-semibold text-xs p-1.5 {{ $batch->is_sectoral ? 'bg-rose-200 text-rose-800' : 'bg-emerald-200 text-emerald-800' }}">{{ $batch->is_sectoral ? 'ST' : 'NS' }}</span>
                                                </span>

                                                <div
                                                    class="flex flex-row items-center justify-between font-semibold mb-1 gap-x-2 px-2">
                                                    <div class="flex justify-between w-full">
                                                        <p class="text-[#e74c3c]">Total Male</p>
                                                        <p>{{ $batch->total_male }}</p>
                                                    </div>
                                                    <div class="flex justify-between w-full">
                                                        <p class="text-[#d4ac0d]">Total Female</p>
                                                        <p>{{ $batch->total_female }}</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex flex-row items-center justify-between mb-1 gap-x-2 px-2">
                                                    <div class="flex justify-between w-full">
                                                        <p>(M) Seniors</p>
                                                        <p>{{ $batch->total_senior_male }}</p>
                                                    </div>
                                                    <div class="flex justify-between w-full">
                                                        <p>(F) Seniors</p>
                                                        <p>{{ $batch->total_senior_female }}</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex flex-row items-center justify-between mb-1 gap-x-2 px-2">
                                                    <div class="flex justify-between w-full">
                                                        <p>(M) PWDs</p>
                                                        <p>{{ $batch->total_pwd_male }}</p>
                                                    </div>
                                                    <div class="flex justify-between w-full">
                                                        <p class="">(F) PWDs</p>
                                                        <p class="">{{ $batch->total_pwd_female }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between mb-1 gap-x-2 px-2">
                                                    <div class="flex justify-between w-full">
                                                        <p class="">(M) COS signed</p>
                                                        <p class="">{{ $batch->total_contract_male }}</p>
                                                    </div>
                                                    <div class="flex justify-between w-full">
                                                        <p class="">(F) COS signed</p>
                                                        <p class="">{{ $batch->total_contract_female }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between mb-1 gap-x-2 px-2">
                                                    <div class="flex justify-between w-full">
                                                        <p class="">(M) Payroll claimed</p>
                                                        <p class="">{{ $batch->total_payroll_male }}</p>
                                                    </div>
                                                    <div class="flex justify-between w-full">
                                                        <p class="">(F) Payroll claimed</p>
                                                        <p class="">{{ $batch->total_payroll_female }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="z-10 flex flex-1 rounded flex-col items-center justify-center">
                                    @if ($this->implementationTotalByYear === 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            <span>You haven't made any implementation<br> projects this year yet.</span>
                                            <br>
                                            Try a creating a new one<br> in the <span
                                                class="text-indigo-700">Implementations</span> page.
                                        </h2>
                                    @elseif ($this->batches->total() === 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            There are no <span class="text-indigo-700">batches</span> yet. <br>
                                            Assign a batch in the <span class="text-indigo-700">Implementations</span>
                                            page.
                                        </h2>
                                    @elseif (
                                        \Carbon\Carbon::parse($start)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultStart)->format('Y-m-d') ||
                                            \Carbon\Carbon::parse($end)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultEnd)->format('Y-m-d'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            <span>No implementations found.</span> <br>
                                            Try a different <span class="text-indigo-700">date range</span>.
                                        </h2>
                                    @elseif (is_null($this->total_beneficiaries) &&
                                            is_null($this->total_pwds) &&
                                            is_null($this->total_seniors) &&
                                            is_null($this->total_contract) &&
                                            is_null($this->total_payroll))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4 text-zinc-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>

                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            No <span class="text-indigo-700">beneficiaries</span> found <br
                                                class="hidden lg:inline"> in any batches
                                        </h2>
                                    @endif
                                </div>
                            @endif
                        </div>
                        {{ $this->batches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Export Summary Modal --}}
    <div x-cloak x-show="showExportModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
        @keydown.escape.window="$wire.resetExport(); showExportModal = false;">

        <!-- Modal -->
        <div x-show="showExportModal" x-trap.noautofocus.noscroll="showExportModal"
            class="relative h-full p-4 flex items-start justify-center overflow-y-auto z-50 select-none">

            {{-- The Modal --}}
            <div class="w-full max-w-screen-md">
                <div class="relative w-full bg-white rounded-md shadow">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                            Export Summary of Beneficiaries
                        </h1>

                        <div class="flex items-center justify-end gap-2">

                            {{-- Loading State --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-indigo-900 animate-spin"
                                wire:loading
                                wire:target="exportSummary, showExport, exportChoice, exportFormat, defaultExportStart, defaultExportEnd, selectExportImplementationRow"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Close Button --}}
                            <button type="button" @click="$wire.resetExport(); showExportModal = false;"
                                class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close Modal</span>
                            </button>
                        </div>
                    </div>

                    <hr class="">

                    {{-- Modal body --}}
                    <div class="w-full pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">

                        <div class="flex flex-col items-center justify-center w-full gap-4">

                            {{-- Export Options --}}
                            <div class="relative w-full flex items-center gap-2">
                                <span class="text-sm font-medium">Export Options:</span>
                                {{-- Date Range --}}
                                <label for="date_range"
                                    class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold {{ $exportChoice === 'date_range' ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500 active:text-gray-600' }}">
                                    By Date Range
                                    <input type="radio" class="hidden absolute inset-0" id="date_range"
                                        value="date_range" wire:model.live="exportChoice">
                                </label>
                                {{-- Selected Project --}}
                                <label for="selected_project"
                                    class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold {{ $exportChoice === 'selected_project' ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500 active:text-gray-600' }}">
                                    By Selected Project
                                    <input type="radio" class="hidden absolute inset-0" id="selected_project"
                                        value="selected_project" wire:model.live="exportChoice">
                                </label>
                            </div>

                            {{-- File Format --}}
                            <div class="relative w-full flex items-center gap-2">
                                <span class="text-sm font-medium">File Format:</span>
                                {{-- Date Range --}}
                                <label for="xlsx-radio"
                                    class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold {{ $exportFormat === 'xlsx' ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500 active:text-gray-600' }}">
                                    XLSX
                                    <input type="radio" class="hidden absolute inset-0" id="xlsx-radio"
                                        value="xlsx" wire:model.live="exportFormat">
                                </label>
                                {{-- Selected Project --}}
                                <label for="csv-radio"
                                    class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold {{ $exportFormat === 'csv' ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500 active:text-gray-600' }}">
                                    CSV
                                    <input type="radio" class="hidden absolute inset-0" id="csv-radio"
                                        value="csv" wire:model.live="exportFormat">
                                </label>
                            </div>

                            <hr class="w-full my-2">

                            {{-- Body --}}
                            <div x-data="{ exportChoice: $wire.entangle('exportChoice') }"
                                class="w-full flex flex-col justify-center gap-4 text-indigo-1100">

                                {{-- Date Range option --}}
                                <div x-show="exportChoice === 'date_range'"
                                    class="w-full flex flex-col justify-center gap-4">
                                    {{-- Date Range picker --}}
                                    <div class="flex flex-col gap-2 mb-4">

                                        {{-- Header --}}
                                        <h1
                                            class="flex items-center gap-1.5 mb-2 text-sm font-semibold text-indigo-900">
                                            Range
                                            of
                                            Projects to Export <span
                                                class="text-gray-500 font-normal">(MM/DD/YYYY)</span></h1>


                                        <div id="export-date-range" date-rangepicker datepicker-autohide
                                            class="flex items-center gap-1 sm:gap-2 text-xs">

                                            {{-- Start --}}
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs font-medium">Start Date:</span>
                                                <div class="relative">
                                                    <div
                                                        class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-3.5 sm:size-5"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <input id="export-start-date" type="text" name="export_start"
                                                        wire:model.change="defaultExportStart" readonly
                                                        @change-date.camel="$wire.$set('defaultExportStart', $el.value);"
                                                        value="{{ $defaultExportStart }}"
                                                        class="border selection:bg-transparent cursor-pointer bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500 text-xs rounded w-40 py-1.5 ps-7 sm:ps-8"
                                                        placeholder="Select date start">
                                                </div>
                                            </div>

                                            <span class="text-base">-></span>

                                            {{-- End --}}
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs font-medium">End Date:</span>
                                                <div class="relative">
                                                    <div
                                                        class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-3.5 sm:size-5"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <input id="export-end-date" type="text" name="export_end"
                                                        wire:model.change="defaultExportEnd" readonly
                                                        @change-date.camel="$wire.$set('defaultExportEnd', $el.value);"
                                                        value="{{ $defaultExportEnd }}"
                                                        class="border selection:bg-transparent cursor-pointer bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500 text-xs rounded w-40 py-1.5 ps-7 sm:ps-8"
                                                        placeholder="Select date end">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer --}}
                                    <div class="flex items-center justify-between w-full gap-2">

                                        {{-- Implementations to Export --}}
                                        <h2 class="flex items-center gap-2">Implementations to Export<span
                                                class="grid place-self-center py-1 px-2 rounded text-xs font-semibold {{ $this->exportImplementations->isNotEmpty() ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">{{ count($this->exportImplementations ?? 0) }}</span>
                                        </h2>

                                        {{-- Confirm Button --}}
                                        <button type="button"
                                            @if ($this->exportImplementations->isNotEmpty()) wire:click="exportSummary"
                                            @else
                                            disabled @endif
                                            class="duration-200 ease-in-out flex items-center justify-center px-3 py-2 rounded outline-none font-bold text-sm disabled:bg-gray-300 disabled:text-gray-500 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">CONFIRM</button>
                                    </div>
                                </div>

                                {{-- Selected Project Option --}}
                                <div x-show="exportChoice === 'selected_project'"
                                    class="w-full flex flex-col justify-center gap-4">

                                    <div x-data="{ show: false }"
                                        class="flex items-center justify-between w-full gap-2">

                                        {{-- Implementation Projects Dropdown --}}
                                        <div class="flex items-center w-full gap-2">
                                            <h2 class="text-sm font-medium">
                                                Choose Another Project
                                            </h2>

                                            {{-- Projects Dropdown --}}
                                            <div x-data="{ show: false, currentExportImplementation: $wire.entangle('currentExportImplementation') }" class="relative z-30">

                                                {{-- Button --}}
                                                <button type="button" @click="show = !show;"
                                                    class="flex items-center justify-between gap-2 border-2 max-w-md outline-none text-xs font-semibold px-2 py-1 rounded
                                                    disabled:bg-gray-50 disabled:text-gray-500 disabled:border-gray-300 
                                                    bg-indigo-100 hover:bg-indigo-800 active:bg-indigo-900 
                                                    text-indigo-700 hover:text-indigo-50 active:text-indigo-50
                                                    border-indigo-700 hover:border-transparent active:border-transparent duration-200 ease-in-out">

                                                    <span x-text="currentExportImplementation"></span>

                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="size-3">
                                                        <path fill-rule="evenodd"
                                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>

                                                {{-- Content --}}
                                                <div x-show="show"
                                                    @click.away="if(show == true) { $wire.set('searchExportProject', null); } show = false;"
                                                    class="absolute right-0 text-indigo-1100 bg-white w-60 shadow-lg border border-indigo-100 rounded text-xs p-3 mt-2">

                                                    <label for="searchExportProject"
                                                        class="relative flex items-center justify-center duration-200 ease-in-out rounded border outline-none focus:ring-0
                                                        text-gray-500 hover:text-indigo-700 focus-within:text-indigo-700 hover:bg-indigo-50 focus-within:bg-indigo-50 border-gray-300 hover:border-indigo-700 focus-within:border-indigo-700">

                                                        <div
                                                            class="absolute start-2 flex items-center justify-center pointer-events-none">
                                                            {{-- Loading Icon --}}
                                                            <svg class="size-4 animate-spin" wire:loading
                                                                wire:target="searchExportProject"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4">
                                                                </circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                </path>
                                                            </svg>

                                                            {{-- Search Icon --}}
                                                            <svg class="size-4" wire:loading.remove
                                                                wire:target="searchExportProject"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>

                                                        <input type="text" id="searchExportProject" autofocus
                                                            autocomplete="off"
                                                            wire:model.live.debounce.300ms="searchExportProject"
                                                            class="peer bg-transparent outline-none border-none focus:ring-0 rounded w-full ps-8 text-xs text-indigo-1100 placeholder-gray-500 hover:placeholder-indigo-700 focus:placeholder-indigo-700"
                                                            placeholder="Search project">
                                                    </label>

                                                    {{-- List of Implementations for Export --}}
                                                    <div
                                                        class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                                        @if ($this->exportImplementations->isNotEmpty())
                                                            @foreach ($this->exportImplementations as $key => $implementation)
                                                                <span wire:key={{ $key }}>
                                                                    <button type="button"
                                                                        wire:click="selectExportImplementationRow('{{ encrypt($implementation->id) }}')"
                                                                        @click="show= !show; currentExportImplementation = '{{ $implementation->project_title ? $implementation->project_title . ' / ' . $implementation->project_num : $implementation->project_num }}'"
                                                                        wire:loading.attr="disabled"
                                                                        aria-label="{{ __('Implementation') }}"
                                                                        class="outline-none text-left w-full flex items-center px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 focus:text-indigo-900 focus:bg-indigo-100 duration-200 ease-in-out">
                                                                        {{ $implementation->project_title ? $implementation->project_title . ' (' . $implementation->project_num . ')' : $implementation->project_num }}
                                                                    </button>
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <div
                                                                class="flex flex-1 items-center justify-center size-full text-sm border border-gray-300 bg-gray-100 text-gray-500 rounded p-2">
                                                                No implementations found
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- Date Range Picker for Selected Projects --}}
                                        <div class="relative flex items-center justify-center gap-2">

                                            {{-- Show Left Dates --}}
                                            <span class="relative" x-data="{ pop: false }">
                                                <button type="button" @mouseleave="pop = false;"
                                                    @mouseenter="pop = true;" @click="show = !show;"
                                                    class="flex items-center justify-center p-1 rounded duration-200 ease-in-out hover:bg-indigo-100 text-zinc-500 hover:text-indigo-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M93.157 14.058 C 88.540 16.873,87.506 19.624,87.503 29.102 L 87.500 37.500 65.304 37.500 C 36.969 37.500,32.063 38.825,22.483 49.067 C 12.153 60.111,12.432 57.893,12.681 126.869 L 12.891 185.269 16.126 188.286 C 21.669 193.457,28.330 193.457,33.874 188.287 L 37.109 185.270 37.337 161.385 L 37.565 137.500 200.046 137.500 L 362.527 137.500 362.318 245.117 C 362.112 351.136,362.086 352.774,360.547 355.387 C 358.537 358.800,355.734 360.861,351.893 361.752 C 347.818 362.697,52.182 362.697,48.107 361.752 C 39.092 359.661,37.977 356.783,37.500 334.375 C 37.082 314.738,36.969 314.164,32.807 310.662 C 27.942 306.569,21.186 306.994,16.126 311.713 L 12.891 314.729 12.659 335.554 C 12.465 352.942,12.636 357.109,13.697 360.806 C 17.046 372.482,26.754 382.410,38.352 386.020 C 45.124 388.127,353.807 388.358,360.991 386.261 C 372.544 382.889,382.437 373.161,386.020 361.648 C 388.332 354.218,388.332 70.782,386.020 63.352 C 382.437 51.839,372.544 42.111,360.991 38.739 C 357.560 37.737,352.514 37.500,334.624 37.500 L 312.500 37.500 312.497 29.102 C 312.493 16.846,309.225 12.506,300.000 12.506 C 290.775 12.506,287.507 16.846,287.503 29.102 L 287.500 37.500 200.000 37.500 L 112.500 37.500 112.497 29.102 C 112.492 14.820,103.447 7.784,93.157 14.058 M87.503 64.648 C 87.507 67.570,90.074 71.562,93.157 73.442 C 100.677 78.027,112.486 72.658,112.497 64.648 L 112.500 62.500 200.000 62.500 L 287.500 62.500 287.503 64.648 C 287.514 72.658,299.323 78.027,306.843 73.442 C 309.926 71.562,312.493 67.570,312.497 64.648 L 312.500 62.500 330.664 62.519 C 362.294 62.551,361.983 62.258,362.363 92.383 L 362.617 112.500 200.000 112.500 L 37.383 112.500 37.637 92.383 C 38.017 62.236,37.514 62.715,68.945 62.580 L 87.500 62.500 87.503 64.648 M81.641 175.896 C 79.207 177.217,14.882 241.534,13.621 243.907 C 13.004 245.067,12.500 247.809,12.500 250.000 C 12.500 255.994,12.363 255.834,47.410 290.739 C 82.912 326.097,81.626 325.001,87.644 324.997 C 95.270 324.992,99.992 320.270,99.997 312.644 C 100.001 306.721,100.366 307.180,77.071 283.789 L 55.870 262.500 130.083 262.497 C 202.759 262.494,204.350 262.462,206.843 260.942 C 214.551 256.242,214.551 243.758,206.843 239.058 C 204.350 237.538,202.759 237.506,130.083 237.503 L 55.870 237.500 77.071 216.211 C 100.366 192.820,100.001 193.279,99.997 187.356 C 99.991 178.049,89.611 171.569,81.641 175.896 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd"></path>
                                                        </g>
                                                    </svg>
                                                </button>

                                                {{-- Tooltip Content --}}
                                                <div x-cloak x-show="!show && pop" x-transition.opacity
                                                    class="absolute z-50 top-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                                    Toggles the <span class="text-indigo-400">date range</span> filter
                                                </div>
                                            </span>

                                            <div x-show="show" x-transition.opacity
                                                class="absolute right-full top-0 me-2 flex flex-col items-center justify-center gap-2 rounded p-2 z-40 border border-indigo-500 bg-white">

                                                <span class="text-indigo-900 text-sm font-bold">
                                                    Date Range Filter
                                                </span>

                                                <div id="selected-date-range" date-rangepicker datepicker-autohide
                                                    class="flex items-center gap-1 sm:gap-2 text-xs">

                                                    {{-- Start --}}
                                                    <div class="flex flex-col gap-1">
                                                        <span class="text-xs font-medium">Start Date:</span>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="size-3.5 sm:size-5"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                            </div>
                                                            <input type="text" readonly id="selected-start-date"
                                                                @change-date.camel="$wire.$set('defaultExportStart', $el.value); show = false;"
                                                                wire:model.change="defaultExportStart" name="start"
                                                                value="{{ $defaultExportStart }}"
                                                                class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                                                placeholder="Select date start">
                                                        </div>
                                                    </div>

                                                    <span class="text-indigo-1100">-></span>

                                                    {{-- End --}}
                                                    <div class="flex flex-col gap-1">
                                                        <span class="text-xs font-medium">End Date:</span>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="size-3.5 sm:size-5"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                            </div>
                                                            <input type="text" readonly id="selected-end-date"
                                                                wire:model.change="defaultExportEnd"
                                                                @change-date.camel="$wire.$set('defaultExportEnd', $el.value); show = false;"
                                                                name="end" value="{{ $defaultExportEnd }}"
                                                                class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                                                placeholder="Select date end">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Export Overview --}}
                                    @if ($this->exportImplementation->isNotEmpty())

                                        <div class="flex flex-col gap-2 rounded p-2 bg-indigo-50 text-xs">

                                            <h1 class="flex items-center text-sm mb-2 font-bold text-indigo-900">
                                                Export Overview
                                            </h1>
                                            <div class="flex items-center justify-between">
                                                <p class="flex items-center gap-1.5 font-medium text-xs">
                                                    Project to Export:
                                                    <span
                                                        class="rounded font-semibold px-2 py-1 bg-indigo-100 text-indigo-900">
                                                        @if ($this->exportImplementation->isNotEmpty())
                                                            {{ $this->exportImplementation[0]->project_title ?? '' }}
                                                            <span
                                                                class="{{ $this->exportImplementation[0]->project_title ? 'text-gray-500' : '' }}">
                                                                {{ $this->exportImplementation[0]->project_title ? ' (' . $this->exportImplementation[0]->project_num . ')' : $this->exportImplementation[0]->project_num }}
                                                            </span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </span>
                                                </p>
                                                <p class="flex items-center gap-1.5 font-medium text-xs">
                                                    Total Slots:
                                                    <span
                                                        class="rounded font-semibold px-2 py-1 bg-indigo-100 text-indigo-900">
                                                        {{ $this->exportImplementation->isNotEmpty() ? $this->exportBeneficiaryCountByImplementation . ' / ' . $this->exportImplementation[0]->total_slots : 'N/A' }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="flex flex-col gap-1 font-medium text-xs">
                                                Batches Included:
                                                <div
                                                    class="flex flex-col gap-1 max-h-36 overflow-y-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                                    @foreach ($this->exportBatchesInfo as $count => $batch)
                                                        <div
                                                            class="flex items-center justify-between gap-1.5 me-1.5 rounded font-semibold px-2 py-1 bg-indigo-100 text-indigo-900">

                                                            {{-- Row Number || Sector or Barangay || Batch Number --}}
                                                            <span class="flex items-center justify-between gap-1.5">
                                                                <span class="text-indigo-1100">
                                                                    {{ '#' . $count + 1 }}
                                                                </span>
                                                                {{ $batch->is_sectoral ? $batch->sector_title : 'Brgy. ' . $batch->barangay_name }}
                                                                <span
                                                                    class="text-gray-500">{{ ' (' . $batch->batch_num . ')' }}
                                                                </span>
                                                            </span>

                                                            {{-- Beneficiaries Count --}}
                                                            <span
                                                                class="flex items-center justify-center font-medium gap-1 rounded px-2 py-1
                                                            {{ $this->exportBeneficiaryCount(encrypt($batch->id)) === $batch->slots_allocated ? 'bg-indigo-200 text-indigo-900' : 'bg-amber-200 text-amber-900' }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="size-3.5"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M96.875 42.643 C 52.219 54.424,52.561 118.254,97.341 129.707 C 111.583 133.349,116.540 131.561,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.616 67.550,148.905 66.535,145.219 60.791 C 135.687 45.938,114.514 37.989,96.875 42.643 M280.938 42.600 C 270.752 45.179,260.204 52.464,254.763 60.678 C 251.061 66.267,251.383 67.401,258.836 75.011 C 272.214 88.670,280.835 105.931,282.526 122.444 C 283.253 129.539,284.941 131.255,291.175 131.236 C 330.920 131.117,351.409 84.551,324.504 55.491 C 313.789 43.917,296.242 38.725,280.938 42.600 M189.063 75.494 C 134.926 85.627,123.780 159.908,172.566 185.433 C 216.250 208.290,267.190 170.135,257.471 121.839 C 251.236 90.860,220.007 69.703,189.063 75.494 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M283.058 149.743 C 282.139 150.542,280.658 153.753,279.696 157.031 C 276.119 169.218,270.328 179.314,261.225 189.234 C 253.482 197.670,254.234 200.382,265.191 203.537 C 288.694 210.306,307.108 223.950,319.474 243.758 C 324.516 251.833,323.991 251.565,334.706 251.543 C 362.465 251.487,376.780 236.149,375.520 207.813 C 374.261 179.527,360.172 159.904,334.766 151.051 C 326.406 148.137,286.076 147.117,283.058 149.743 M150.663 223.858 C 119.731 229.560,95.455 253.370,88.566 284.766 C 80.747 320.396,94.564 350.121,122.338 357.418 C 129.294 359.246,270.706 359.246,277.662 357.418 C 300.848 351.327,312.868 333.574,312.837 305.469 C 312.790 264.161,291.822 235.385,254.043 224.786 C 246.270 222.606,161.583 221.845,150.663 223.858 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                                {{ $this->exportBeneficiaryCount(encrypt($batch->id)) . ' / ' . $batch->slots_allocated }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="flex flex-col items-center justify-center gap-2 rounded p-2 border border-gray-300 bg-gray-50 text-gray-500 font-medium h-40 text-xs sm:text-sm">
                                            <p class="text-center">
                                                No implementation project and batches found. <br>
                                                Try a different <span class="text-indigo-700">date range</span> or
                                                <span class="text-indigo-700">add at least one beneficiary</span>.
                                            </p>
                                        </div>
                                    @endif
                                    {{-- Footer --}}
                                    <div class="flex items-center justify-end w-full gap-4">

                                        {{-- Confirm Button --}}
                                        <button type="button"
                                            @if ($this->exportBatchesInfo->isNotEmpty()) wire:click="exportSummary"
                                            @else
                                            disabled @endif
                                            class="duration-200 ease-in-out flex items-center justify-center px-3 py-2 rounded outline-none font-bold text-sm disabled:bg-gray-300 disabled:text-gray-500 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">CONFIRM</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Bar --}}
    <div x-data="{
        successShow: $wire.entangle('showAlert'),
        successMessage: $wire.entangle('alertMessage'),
        init() {
            window.addEventListener('show-alert', () => {
                setTimeout(() => { $wire.showAlert = false; }, 3000);
            });
        },
    }" x-cloak x-show="successShow"
        x-transition:enter="transition ease-in-out duration-300 origin-left"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="origin-left transition ease-in-out duration-500"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="fixed left-6 bottom-6 z-50 flex items-center bg-indigo-200 text-indigo-1000 border border-indigo-500 rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
        role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                clip-rule="evenodd" />
        </svg>
        <p x-text="successMessage"></p>
    </div>
</div>

@script
    <script data-navigate-once>
        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });

        $wire.on('openPrintWindow', (data) => {
            setTimeout(() => {
                const printFrame = document.createElement('iframe');
                printFrame.onload = function() {
                    const closePrint = () => {
                        document.body.removeChild(this);
                    };

                    const afterPrinting = () => {
                        document.body.removeChild(this);
                        document.title = "Dashboard | TU-Efficient";
                    }

                    this.contentWindow.onbeforeupload = closePrint;
                    this.contentWindow.onafterprint = afterPrinting;
                    document.title = "Summary-Report (" + data.currentDate + ")";
                    this.contentWindow.print();
                }

                printFrame.style.display = "none"; // hide iframe
                printFrame.src = data.url;
                document.body.appendChild(printFrame);
            }, 1);
        });

        window.onafterprint = function() {
            document.title = "Dashboard | TU-Efficient";
        }

        document.addEventListener('livewire:navigated', () => {

            let data = @json($this->summaryCount);

            let overallValues = [parseInt(data.total_male), parseInt(data.total_female)];
            let pwdValues = [parseInt(data.total_pwd_male), parseInt(data.total_pwd_female)];
            let seniorValues = [parseInt(data.total_senior_male), parseInt(data.total_senior_female)];
            let contractValues = [parseInt(data.total_contract_male), parseInt(data.total_contract_female)];
            let payrollValues = [parseInt(data.total_payroll_male), parseInt(data.total_payroll_female)];

            const options = (labelName, seriesValues, my_id) => {
                return {
                    series: seriesValues,
                    colors: ["#C0392B", "#F9A825"],
                    chart: {
                        id: my_id,
                        fontFamily: "Inter, sans-serif",
                        height: 250,
                        width: "100%",
                        type: "donut",
                    },
                    stroke: {
                        colors: ["transparent"],
                        lineCap: "",
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: false,
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        offsetY: 12,
                                    },
                                    total: {
                                        showAlways: true,
                                        show: true,
                                        label: labelName,
                                        formatter: function(w) {
                                            const sum = w.globals.seriesTotals.reduce(
                                                (a, b) => {
                                                    return a + b;
                                                },
                                                0
                                            );
                                            return sum;
                                        },
                                    },
                                    value: {
                                        show: true,
                                        offsetY: -20,
                                        formatter: function(value) {
                                            return value;
                                        },
                                    },
                                },
                                size: "80%",
                            },
                        },
                    },
                    grid: {
                        padding: {
                            top: -2,
                        },
                    },
                    labels: ["Male", "Female"],
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        position: "bottom",
                        fontFamily: "Inter, sans-serif",
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            },
                        },
                    },
                    xaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            },
                        },
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                };
            };

            let overall = new ApexCharts(
                document.getElementById("overall-chart"),
                options("Overall", overallValues, "overallDonut")
            );

            let pwd = new ApexCharts(
                document.getElementById("pwd-chart"),
                options("PWDs", pwdValues, "pwdDonut")
            );

            let senior = new ApexCharts(
                document.getElementById("senior-chart"),
                options("Seniors", seniorValues, "seniorDonut")
            );

            let contract = new ApexCharts(
                document.getElementById("contract-chart"),
                options("COS", contractValues, "contractDonut")
            );

            let payroll = new ApexCharts(
                document.getElementById("payroll-chart"),
                options("Payroll", payrollValues, "payrollDonut")
            );

            overall.render();
            pwd.render();
            senior.render();
            contract.render();
            payroll.render();

            $wire.on('series-change', (data) => {

                // Total Beneficiaries overall series data
                if (data.overallValues == null || data.overallValues == 0) {
                    overall.updateOptions({
                        labels: ['None'],
                        series: [0],
                        colors: ['#f0f0f0'],
                    });
                } else {
                    overall.updateOptions({
                        labels: ['Male', 'Female'],
                        colors: ['#C0392B', '#F9A825'],
                    });

                    overall.updateSeries([parseInt(data.overallValues['male']), parseInt(data.overallValues[
                        'female'])]);
                }

                // Total Beneficiaries PWD series data
                if (data.pwdValues == null || data.pwdValues == 0) {
                    pwd.updateOptions({
                        labels: ['None'],
                        series: [0],
                        colors: ['#f0f0f0'],
                    });
                } else {
                    pwd.updateOptions({
                        labels: ['Male', 'Female'],
                        colors: ['#C0392B', '#F9A825'],
                    });

                    pwd.updateSeries([parseInt(data.pwdValues['male']), parseInt(data.pwdValues[
                        'female'])]);
                }

                // Total Beneficiaries senior citizen series data
                if (data.seniorValues == null || data.seniorValues == 0) {
                    senior.updateOptions({
                        labels: ['None'],
                        series: [0],
                        colors: ['#f0f0f0'],
                    });
                } else {
                    senior.updateOptions({
                        labels: ['Male', 'Female'],
                        colors: ['#C0392B', '#F9A825'],
                    });
                    senior.updateSeries([parseInt(data.seniorValues['male']), parseInt(data.seniorValues[
                        'female'])]);
                }

                // Total Beneficiaries contract series data
                if (data.contractValues == null || data.contractValues == 0) {
                    contract.updateOptions({
                        labels: ['None'],
                        series: [0],
                        colors: ['#f0f0f0'],
                    });
                } else {
                    contract.updateOptions({
                        labels: ['Male', 'Female'],
                        colors: ['#C0392B', '#F9A825'],
                    });
                    contract.updateSeries([parseInt(data.contractValues['male']), parseInt(data
                        .contractValues[
                            'female'])]);
                }

                // Total Beneficiaries payroll series data
                if (data.payrollValues == null || data.payrollValues == 0) {
                    payroll.updateOptions({
                        labels: ['None'],
                        series: [0],
                        colors: ['#f0f0f0'],
                    });
                } else {
                    payroll.updateOptions({
                        labels: ['Male', 'Female'],
                        colors: ['#C0392B', '#F9A825'],
                    });
                    payroll.updateSeries([parseInt(data.payrollValues['male']), parseInt(data
                        .payrollValues[
                            'female'])]);
                }
            });

        }, {
            once: true
        });
    </script>
@endscript
