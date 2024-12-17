<x-slot:favicons>
    <x-c-favicons />
</x-slot>


<div x-cloak x-data="{ open: true, isAboveBreakpoint: true, isMobile: window.innerWidth < 768, viewBatchModal: $wire.entangle('viewBatchModal'), scrollToTop() { document.getElementById('batches-table').scrollTo({ top: 0, behavior: 'smooth' }); } }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});
window.addEventListener('resize', () => {
    isMobile = window.innerWidth < 768;
    $wire.$dispatchSelf('init-reload');
});">

    <div :class="{
        'md:ml-20': !open,
        'md:ml-20 xl:ml-64': open,
    }"
        class="md:ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">

            {{-- Title --}}
            <div class="relative flex items-center justify-between my-2 gap-2">

                <div class="flex items-center gap-2">
                    <livewire:sidebar.coordinator-bar />

                    <h1 class="text-xl font-semibold sm:font-bold xl:ms-2">Assignments</h1>

                    {{-- Date range picker --}}
                    <template x-if="!isMobile">
                        <div id="assignments-date-range" date-rangepicker datepicker-autohide
                            class="flex items-center gap-1 sm:gap-2 text-xs">
                            <span class="relative inline-flex items-center gap-1 sm:gap-2" x-data="{ pop: false }">
                                {{-- Start --}}
                                <div class="relative" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                    <div
                                        class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
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
                                        @change-date.camel="$wire.$set('calendarStart', $el.value); " name="start"
                                        value="{{ $calendarStart }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-blue-300 text-xs text-blue-1100 rounded focus:ring-blue-500 focus:border-blue-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date start">
                                </div>

                                <span class="text-blue-1100">to</span>

                                {{-- End --}}
                                <div class="relative" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                    <div
                                        class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
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
                                        @change-date.camel="$wire.$set('calendarEnd', $el.value); " name="end"
                                        value="{{ $calendarEnd }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-blue-300 text-xs text-blue-1100 rounded focus:ring-blue-500 focus:border-blue-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date end">
                                </div>

                                {{-- Tooltip Content --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 top-full mt-2 right-0 rounded p-2 shadow text-xs text-center font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-blue-50">
                                    This sets the <span class="text-blue-500">date range</span> of what records to
                                    show <br>
                                    based on the its creation date (start to end)
                                </div>
                            </span>
                        </div>
                    </template>
                </div>

                {{-- Loading State --}}
                <template x-if="!isMobile">
                    <svg class="text-blue-900 size-6 animate-spin" wire:loading
                        wire:target="calendarStart, calendarEnd, selectBatchRow, applyFilter, viewList, viewAssignment"
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
                        <svg class="text-blue-900 size-6 animate-spin" wire:loading
                            wire:target="calendarStart, calendarEnd, selectBatchRow, applyFilter, viewList, viewAssignment"
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
                                class="flex items-center justify-center p-1 rounded duration-200 ease-in-out hover:bg-blue-100 text-zinc-500 hover:text-blue-700">
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
                                class="absolute z-50 top-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-blue-50">
                                Toggles the <span class="text-blue-400">date range</span> menu
                            </div>
                        </span>

                        <div x-show="show" x-transition.opacity
                            class="absolute right-full top-0 me-2 flex flex-col items-center justify-center gap-2 rounded p-2 z-40 border border-blue-500 bg-white">

                            <span class="text-blue-1100 text-xs font-medium">
                                Date Range (Start to End)
                            </span>

                            <div id="assignments-date-range" date-rangepicker datepicker-autohide
                                class="flex items-center gap-1 sm:gap-2 text-xs">

                                {{-- Start --}}
                                <div class="relative">
                                    <div
                                        class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
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
                                        @change-date.camel="$wire.$set('calendarStart', $el.value);  show = false;"
                                        wire:model.change="calendarStart" name="start" value="{{ $calendarStart }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-blue-300 text-xs text-blue-1100 rounded focus:ring-blue-500 focus:border-blue-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date start">
                                </div>

                                <span class="text-blue-1100">-></span>

                                {{-- End --}}
                                <div class="relative">
                                    <div
                                        class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
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
                                        @change-date.camel="$wire.$set('calendarEnd', $el.value);  show = false;"
                                        name="end" value="{{ $calendarEnd }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-blue-300 text-xs text-blue-1100 rounded focus:ring-blue-500 focus:border-blue-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date end">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Content / Body --}}
            <div class="relative flex flex-col lg:grid lg:grid-cols-5 size-full gap-4">

                {{-- List of Batches --}}
                <div class="relative flex flex-col lg:col-span-3 w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 my-2 px-2 flex items-center justify-between w-full">

                        <div class="inline-flex items-center gap-2 text-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>

                            <h1 class="hidden md:block text-base font-bold">List of Batches</h1>
                            <h1 class="block md:hidden text-sm font-bold">Batches</h1>

                            <span
                                class="font-medium text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">{{ $this->batchesCount ?? 0 }}</span>
                        </div>

                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="flex items-center justify-end gap-2">

                            {{-- General Search Box --}}
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $this->batches->isNotEmpty() || $searchBatches ? 'text-blue-800' : 'text-zinc-400' }}">

                                    {{-- Loading Icon --}}
                                    <svg class="size-3.5 animate-spin" wire:loading wire:target="searchBatches"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Search Icon --}}
                                    <svg class="size-3.5" wire:loading.remove wire:target="searchBatches"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>

                                {{-- Search Input Bar --}}
                                <input type="text" id="searchBatches" maxlength="100" autocomplete="off"
                                    @if ($this->batches->isEmpty() && !$searchBatches) disabled @endif
                                    wire:model.live.debounce.300ms="searchBatches"
                                    class="{{ $this->batches->isNotEmpty() || $searchBatches
                                        ? 'selection:bg-blue-700 selection:text-blue-50 text-blue-1100 placeholder-blue-500 border-blue-300 bg-blue-50 focus:ring-blue-500 focus:border-blue-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1.5 text-xs border rounded w-full"
                                    placeholder="Search for batches">
                            </div>

                            {{-- Filter Button --}}
                            <div x-cloak x-data="{ open: false }" class="relative" @keydown.escape="open = false;">
                                <!-- Button -->
                                <button x-ref="button" @click="open = !open" :aria-expanded="open" type="button"
                                    class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 hover:text-blue-100 active:text-blue-200 focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500 rounded px-3 py-1 text-sm font-bold duration-200 ease-in-out">
                                    FILTER
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M55.859 51.091 C 37.210 57.030,26.929 76.899,32.690 95.866 C 35.051 103.642,34.376 102.847,97.852 172.610 L 156.250 236.794 156.253 298.670 C 156.256 359.035,156.294 360.609,157.808 363.093 C 161.323 368.857,170.292 370.737,175.953 366.895 C 184.355 361.193,241.520 314.546,242.553 312.549 C 243.578 310.566,243.750 304.971,243.750 273.514 L 243.750 236.794 302.148 172.610 C 365.624 102.847,364.949 103.642,367.310 95.866 C 372.533 78.673,364.634 60.468,348.673 52.908 L 343.359 50.391 201.172 50.243 C 87.833 50.126,58.350 50.298,55.859 51.091 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open" @click.outside="open = false;"
                                    x-trap.inert.noautofocus.noscroll="open"
                                    class="absolute flex flex-col flex-1 gap-4 text-xs right-0 mt-2 p-4 z-50 rounded bg-white shadow-lg border border-gray-300">

                                    {{-- Approval Status --}}
                                    <div class="whitespace-nowrap">
                                        <h2 class="text-sm font-medium mb-1">
                                            Filter for Approval Status
                                        </h2>
                                        <div x-data="{ approved: $wire.entangle('approvalStatuses.approved'), pending: $wire.entangle('approvalStatuses.pending'), }" class="flex items-center gap-3">

                                            <label tabindex="0" @keydown.enter.self="$refs.approved.click()"
                                                class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                :class="{
                                                    'bg-blue-100 text-blue-700 focus:outline-blue-300': approved,
                                                    'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                        approved,
                                                }"
                                                for="approvedStatus">
                                                <input id="approvedStatus" type="checkbox" x-ref="approved"
                                                    tabindex="-1" wire:model="approvalStatuses.approved"
                                                    class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                    :class="{
                                                        'border-blue-300 text-blue-700': approved,
                                                        'border-gray-300': !approved,
                                                    }">
                                                Approved
                                            </label>

                                            <label tabindex="0" @keydown.enter.self="$refs.pending.click()"
                                                class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                :class="{
                                                    'bg-blue-100 text-blue-700 focus:outline-blue-300': pending,
                                                    'bg-gray-50 text-gray-700 focus:outline-gray-300': !pending,
                                                }"
                                                for="pendingStatus">
                                                <input id="pendingStatus" type="checkbox" x-ref="pending"
                                                    tabindex="-1" wire:model="approvalStatuses.pending"
                                                    class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                    :class="{
                                                        'border-blue-300 text-blue-700': pending,
                                                        'border-gray-300': !pending,
                                                    }">
                                                Pending
                                            </label>

                                        </div>
                                    </div>

                                    {{-- Submission Status --}}
                                    <div class="whitespace-nowrap">
                                        <h2 class="text-sm font-medium mb-1">
                                            Filter for Submission Status
                                        </h2>
                                        <div x-data="{ submitted: $wire.entangle('submissionStatuses.submitted'), encoding: $wire.entangle('submissionStatuses.encoding'), unopened: $wire.entangle('submissionStatuses.unopened'), revalidate: $wire.entangle('submissionStatuses.revalidate') }" class="flex flex-col justify-center gap-2">
                                            <div class="flex items-center gap-3">

                                                <label tabindex="0" @keydown.enter.self="$refs.submitted.click()"
                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                    :class="{
                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': submitted,
                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                            submitted,
                                                    }"
                                                    for="submittedStatus">
                                                    <input id="submittedStatus" type="checkbox" x-ref="submitted"
                                                        tabindex="-1" wire:model="submissionStatuses.submitted"
                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                        :class="{
                                                            'border-blue-300 text-blue-700': submitted,
                                                            'border-gray-300': !submitted,
                                                        }">
                                                    Submitted
                                                </label>

                                                <label tabindex="0" @keydown.enter.self="$refs.encoding.click()"
                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                    :class="{
                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': encoding,
                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                            encoding,
                                                    }"
                                                    for="encodingStatus">
                                                    <input id="encodingStatus" type="checkbox" x-ref="encoding"
                                                        tabindex="-1" wire:model="submissionStatuses.encoding"
                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                        :class="{
                                                            'border-blue-300 text-blue-700': encoding,
                                                            'border-gray-300': !encoding,
                                                        }">
                                                    Encoding
                                                </label>

                                            </div>
                                            <div class="flex items-center gap-3">

                                                <label tabindex="0" @keydown.enter.self="$refs.unopened.click()"
                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                    :class="{
                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': unopened,
                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                            unopened,
                                                    }"
                                                    for="unopenedStatus">
                                                    <input id="unopenedStatus" type="checkbox" x-ref="unopened"
                                                        tabindex="-1" wire:model="submissionStatuses.unopened"
                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                        :class="{
                                                            'border-blue-300 text-blue-700': unopened,
                                                            'border-gray-300': !unopened,
                                                        }">
                                                    Unopened
                                                </label>


                                                <label tabindex="0" @keydown.enter.self="$refs.revalidate.click()"
                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                    :class="{
                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': revalidate,
                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                            revalidate,
                                                    }"
                                                    for="revalidateStatus">
                                                    <input id="revalidateStatus" type="checkbox" x-ref="revalidate"
                                                        tabindex="-1" wire:model="submissionStatuses.revalidate"
                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                        :class="{
                                                            'border-blue-300 text-blue-700': revalidate,
                                                            'border-gray-300': !revalidate,
                                                        }">
                                                    Revalidate
                                                </label>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Apply Filter Button --}}
                                    <span class="w-full flex items-center justify-end">
                                        <button @click="$wire.applyFilter(); open = false;"
                                            class="w-full flex items-center justify-center px-3 py-1.5 font-bold text-sm rounded bg-blue-700 text-blue-50 hover:bg-blue-800 active:bg-blue-900 focus:outline-1 focus:outline-blue-900">APPLY
                                            FILTER</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($this->batches->isNotEmpty())
                        {{-- Batches Table --}}
                        <div id="batches-table"
                            class="relative min-h-[82.5vh] max-h-[82.5vh] w-full overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            <table class="relative w-full text-sm text-left text-blue-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-blue-50 uppercase bg-blue-600 sticky top-0">
                                    <tr>
                                        <td class="absolute h-full w-1 left-0">
                                            {{-- Selected Row Indicator --}}
                                        </td>
                                        <th scope="col" class="ps-4 pe-2 py-2">
                                            batch #
                                        </th>
                                        <th scope="col" class="pr-2 py-2">
                                            barangay/sector
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            slots
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            approval
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            submission
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody x-data="{ count: 0 }" class="relative text-xs">
                                    @foreach ($this->batches as $key => $batch)
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:loading.class="pointer-events-none"
                                            wire:target="selectBatchRow, viewAssignment" @click="count++;"
                                            @click.ctrl="if($event.ctrlKey) {$wire.selectBatchRow({{ $key }}, '{{ encrypt($batch->id) }}'); count = 0;}"
                                            @click.debounce.350ms="if(!$event.ctrlKey && count === 1) {$wire.selectBatchRow({{ $key }}, '{{ encrypt($batch->id) }}'); count = 0;}"
                                            @dblclick="if(!$event.ctrlKey) {$wire.viewAssignment({{ $key }}, '{{ encrypt($batch->id) }}'); count = 0}"
                                            class="relative border-b whitespace-nowrap duration-200 ease-in-out cursor-pointer {{ $selectedBatchRow === $key ? 'bg-gray-100 hover:bg-gray-200 text-blue-1000 hover:text-blue-900' : 'hover:bg-gray-50' }}">
                                            <td
                                                class="absolute h-full w-1 left-0 {{ $selectedBatchRow === $key ? 'bg-blue-700' : '' }}">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            <td class="ps-4 pe-2 py-2">
                                                {{ $batch->batch_num }}
                                            </td>
                                            <td class="pr-2 py-2">
                                                @if ($batch->is_sectoral)
                                                    {{ $batch->sector_title }}
                                                @else
                                                    {{ $batch->barangay_name }}
                                                @endif
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $this->beneficiarySlots[$key] }}
                                                /
                                                {{ $batch->slots_allocated }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                @if ($batch->approval_status === 'approved')
                                                    <span
                                                        class="bg-green-300 text-green-1000 rounded-full text-xs py-1 px-2 uppercase font-semibold">{{ $batch->approval_status }}</span>
                                                @elseif($batch->approval_status === 'pending')
                                                    <span
                                                        class="bg-amber-300 text-amber-900 rounded-full text-xs py-1 px-2 uppercase font-semibold">{{ $batch->approval_status }}</span>
                                                @endif
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                @if ($batch->submission_status === 'unopened')
                                                    <span
                                                        class="bg-amber-200 text-amber-900 rounded-full text-xs py-1 px-2 uppercase font-semibold">{{ $batch->submission_status }}</span>
                                                @elseif($batch->submission_status === 'encoding')
                                                    <span
                                                        class="bg-sky-200 text-sky-900 rounded-full text-xs py-1 px-2 uppercase font-semibold">{{ $batch->submission_status }}</span>
                                                @elseif($batch->submission_status === 'submitted')
                                                    <span
                                                        class="bg-green-200 text-green-1000 rounded-full text-xs py-1 px-2 uppercase font-semibold">{{ $batch->submission_status }}</span>
                                                @elseif($batch->submission_status === 'revalidate')
                                                    <span
                                                        class="bg-red-200 text-red-900 rounded-full text-xs py-1 px-2 uppercase font-semibold">{{ $batch->submission_status }}</span>
                                                @endif
                                            </td>

                                            {{-- Batch View --}}
                                            <td class="py-1">
                                                <button type="button" wire:loading.attr="disabled"
                                                    @click.stop="$wire.viewAssignment({{ $key }}, '{{ encrypt($batch->id) }}');"
                                                    id="assignmentRowButton-{{ $key }}"
                                                    aria-label="{{ __('View Assignment') }}"
                                                    class="flex items-center justify-center z-0 p-1 outline-none rounded duration-200 ease-in-out"
                                                    :class="{
                                                        'hover:bg-blue-700 focus:bg-blue-700 text-blue-900 hover:text-blue-50 focus:text-blue-50': row ==
                                                            {{ $key }},
                                                        'text-gray-900 hover:text-blue-900 focus:text-blue-900 hover:bg-gray-300 focus:bg-gray-300': row !=
                                                            {{ $key }},
                                                    }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M196.094 28.629 C 195.449 28.884,154.668 44.553,105.469 63.450 C -5.207 105.958,5.050 101.718,2.258 106.121 C -2.113 113.013,-0.475 121.858,5.978 126.207 C 11.439 129.887,195.785 200.000,200.000 200.000 C 204.215 200.000,388.561 129.887,394.022 126.207 C 402.510 120.487,401.990 106.158,393.130 101.648 C 391.538 100.837,348.398 84.081,297.266 64.412 C 207.418 29.852,199.805 27.159,196.094 28.629 M270.092 85.625 C 308.670 100.491,341.237 113.019,342.463 113.463 C 345.218 114.462,202.811 169.873,199.219 169.200 C 198.145 168.999,165.351 156.563,126.345 141.564 L 55.424 114.293 127.517 86.499 C 167.168 71.211,199.686 58.679,199.779 58.649 C 199.873 58.618,231.513 70.758,270.092 85.625 M27.734 178.937 C 8.335 186.462,5.574 187.749,3.334 190.309 C -2.881 197.416,-0.344 209.612,8.118 213.305 C 34.431 224.791,197.646 286.063,201.099 285.752 C 204.384 285.456,376.320 220.179,391.882 213.319 C 400.350 209.586,402.878 197.424,396.666 190.302 C 394.417 187.724,391.728 186.476,372.085 178.892 L 350.029 170.377 330.733 177.806 C 320.120 181.893,310.950 185.509,310.354 185.843 C 309.658 186.232,315.440 188.805,326.508 193.029 C 335.988 196.648,343.743 199.785,343.740 200.000 C 343.737 200.215,311.394 212.816,271.867 228.003 L 200.000 255.614 128.133 228.003 C 88.606 212.816,56.263 200.215,56.260 200.000 C 56.257 199.785,64.002 196.652,73.470 193.038 C 82.938 189.424,90.408 186.230,90.069 185.941 C 89.518 185.472,53.654 171.648,50.781 170.798 C 50.137 170.607,39.766 174.269,27.734 178.937 M28.200 264.467 C 1.675 274.836,-0.000 276.085,0.000 285.509 C 0.000 292.897,2.730 296.701,10.265 299.816 C 49.494 316.032,196.246 371.435,200.000 371.445 C 203.950 371.456,381.599 304.222,393.130 298.352 C 399.546 295.086,402.301 284.114,398.224 278.064 C 395.451 273.950,393.030 272.722,370.793 264.156 L 349.950 256.126 330.249 263.690 C 319.413 267.850,310.240 271.441,309.865 271.670 C 309.490 271.898,317.177 275.154,326.947 278.904 C 343.410 285.223,344.546 285.782,342.472 286.533 C 341.241 286.980,308.701 299.497,270.159 314.349 L 200.084 341.354 127.712 313.514 L 55.339 285.673 72.942 278.969 C 82.624 275.282,90.300 272.037,89.999 271.757 C 89.162 270.976,50.655 256.239,49.682 256.327 C 49.213 256.369,39.546 260.032,28.200 264.467 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <tr x-data x-intersect.once="$wire.loadMoreBatches()">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[82.5vh] w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded size-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if (array_diff(Arr::flatten($this->filter), Arr::flatten($this->oldFilter)))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No batches found.</p>
                                    <p>Try a different <span class=" text-blue-900">filter</span>.</p>
                                @elseif (
                                    \Carbon\Carbon::parse($start)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultStart)->format('Y-m-d') ||
                                        \Carbon\Carbon::parse($end)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultEnd)->format('Y-m-d'))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No batches found.</p>
                                    <p>Maybe try adjusting the <span class=" text-blue-900">date range</span>.</p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No batches found.</p>
                                    <p>Ask your focal to assign a <span class=" text-blue-900">new
                                            batch</span>.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- View Batch Modal --}}
                    <livewire:coordinator.assignments.view-batch-modal :$batchId />
                </div>

                {{-- List Overview & Beneficiaries Table --}}
                <div class="relative flex flex-col w-full col-span-2 gap-4 md:gap-2">

                    {{-- List Overview --}}
                    <div
                        class="relative flex flex-col h-[18.5vh] bg-white p-2 rounded shadow text-blue-1100 text-xs font-semibold">
                        @if ($this->location)
                            <div class="flex items-center text-blue-900 pb-2 ms-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 me-2"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M66.406 33.352 C 52.411 35.695,40.203 45.981,34.982 59.829 C 32.634 66.058,31.781 146.480,33.963 155.890 C 37.164 169.690,47.591 181.098,60.707 185.149 C 67.256 187.172,150.189 187.586,157.525 185.633 C 170.642 182.140,182.140 170.642,185.633 157.525 C 187.586 150.189,187.172 67.256,185.149 60.707 C 181.135 47.712,169.738 37.212,156.177 34.015 C 151.429 32.896,72.609 32.314,66.406 33.352 M243.464 34.034 C 230.101 37.322,218.726 47.980,214.805 60.887 C 213.279 65.912,213.229 67.781,213.447 111.823 L 213.672 157.542 216.940 164.123 C 222.283 174.882,231.611 182.740,242.475 185.633 C 249.811 187.586,332.744 187.172,339.293 185.149 C 352.342 181.119,362.853 169.619,366.037 155.890 C 368.206 146.536,367.367 66.059,365.036 59.882 C 360.638 48.226,351.786 39.362,340.171 34.982 C 334.189 32.725,252.053 31.920,243.464 34.034 M149.574 67.673 C 152.997 68.986,153.125 70.529,153.125 110.568 L 153.125 148.558 150.841 150.841 L 148.558 153.125 110.568 153.125 C 70.529 153.125,68.986 152.997,67.673 149.574 C 67.406 148.879,67.188 130.920,67.188 109.666 L 67.188 71.023 69.105 69.105 L 71.023 67.188 109.666 67.188 C 130.920 67.188,148.879 67.406,149.574 67.673 M330.895 69.105 L 332.813 71.023 332.813 109.666 C 332.813 130.920,332.594 148.879,332.327 149.574 C 331.014 152.997,329.471 153.125,289.432 153.125 L 251.442 153.125 249.159 150.841 L 246.875 148.558 246.875 110.568 C 246.875 71.051,247.035 68.975,250.179 67.710 C 250.725 67.491,268.678 67.283,290.075 67.249 L 328.977 67.188 330.895 69.105 M59.978 215.113 C 46.935 219.558,37.120 230.499,33.963 244.110 C 31.794 253.464,32.633 333.941,34.964 340.118 C 39.363 351.774,48.216 360.640,59.829 365.018 C 66.058 367.366,146.480 368.219,155.890 366.037 C 169.690 362.836,181.098 352.409,185.149 339.293 C 187.172 332.744,187.586 249.811,185.633 242.475 C 182.140 229.358,170.642 217.860,157.525 214.367 C 149.564 212.247,66.523 212.882,59.978 215.113 M242.119 214.410 C 231.545 217.326,222.191 225.302,216.940 235.877 L 213.672 242.458 213.447 288.177 C 213.229 332.219,213.279 334.088,214.805 339.113 C 218.800 352.266,230.305 362.835,244.110 366.037 C 253.520 368.219,333.942 367.366,340.171 365.018 C 351.784 360.640,360.637 351.774,365.036 340.118 C 367.367 333.941,368.206 253.464,366.037 244.110 C 362.836 230.310,352.409 218.902,339.293 214.851 C 332.975 212.900,248.976 212.518,242.119 214.410 M150.841 249.159 L 153.125 251.442 153.125 289.432 C 153.125 329.471,152.997 331.014,149.574 332.327 C 148.879 332.594,130.920 332.813,109.666 332.813 L 71.023 332.813 69.105 330.895 L 67.188 328.977 67.188 290.029 C 67.188 249.051,67.131 249.731,70.703 247.777 C 71.772 247.192,84.313 246.915,110.412 246.899 L 148.558 246.875 150.841 249.159 M329.632 248.018 C 332.892 249.704,332.813 248.641,332.813 290.334 L 332.813 328.977 330.895 330.895 L 328.977 332.813 290.334 332.813 C 269.080 332.813,251.121 332.594,250.426 332.327 C 247.003 331.014,246.875 329.471,246.875 289.432 L 246.875 251.442 249.159 249.159 L 251.442 246.875 289.432 246.875 C 321.245 246.875,327.781 247.061,329.632 248.018 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p class="inline-flex text-base font-bold">
                                    List Overview
                                </p>
                            </div>
                            <div
                                class="flex flex-col h-full overflow-y-auto scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700">
                                <div class="flex items-start justify-start px-2 my-1">
                                    @if ($this->location->is_sectoral)
                                        Sector Title: <p class="font-normal ps-2 truncate">
                                            {{ $this->location->sector_title }}
                                        </p>
                                    @else
                                        Location: <p class="font-normal ps-2 whitespace-normal">Brgy.
                                            {{ $this->location->barangay_name . ', ' . $this->location->district . ', ' . $this->location->city_municipality }}
                                        </p>
                                    @endif

                                </div>
                                <div class="flex items-center justify-start px-2 my-1">
                                    Access Code: <p class="ps-2 font-normal">
                                        @if ($this->accessCode)
                                            <span
                                                class="bg-blue-300 text-blue-1000 rounded py-1 px-2 select-all">{{ $this->accessCode->access_code }}</span>
                                        @else
                                            <span
                                                class="font-semibold bg-amber-300 text-amber-950 rounded py-1 px-2 uppercase">not
                                                accessible</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center justify-start px-2 my-1">
                                    Submissions: <p class="font-normal ps-2">
                                        {{ $this->submissions }}</p>
                                </div>
                            </div>
                        @else
                            <div class="relative bg-white p-2 h-[18.5vh] min-w-full flex items-center justify-center">
                                <div
                                    class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">

                                    <p>Try <span class="underline underline-offset-2">clicking a row</span> from the
                                        <span class="text-blue-900">list of
                                        </span>
                                    </p>
                                    <p><span class="text-blue-900">batches</span> to show an overview.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- View List --}}
                    <div class="flex items-center justify-end h-[1.5rem] w-full text-sm">

                        <button type="button" wire:click="viewList"
                            @if (!$batchId) disabled @endif
                            class="flex items-center justify-center rounded px-3 py-1 text-sm font-bold duration-200 ease-in-out disabled:bg-gray-300 disabled:text-gray-500 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 hover:text-blue-100 active:text-blue-200 focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500">

                            VIEW LIST

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-5 ms-2">
                                <path fill-rule="evenodd"
                                    d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    {{-- Beneficiaries Table --}}
                    @if ($this->beneficiaries->isNotEmpty())
                        <div id="beneficiaries-table"
                            class="relative h-[64vh] overflow-y-auto rounded shadow whitespace-nowrap bg-white scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            <table class="w-full text-sm text-left text-blue-1100">
                                <thead class="text-xs text-blue-50 uppercase bg-blue-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="ps-3 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            full name
                                        </th>
                                        <th scope="col" class="py-2 text-center">
                                            birthdate
                                        </th>
                                        <th scope="col" class="py-2 text-center">
                                            contact #
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        <tr
                                            class="border-b whitespace-nowrap
                                            {{ $beneficiary->beneficiary_type === 'special case' ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'hover:bg-gray-50 text-blue-1100' }}">
                                            <th scope="row" class="ps-3 py-2 font-semibold whitespace-nowrap">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="px-2 py-2">
                                                {{ $this->full_last_first($beneficiary) }}
                                            </td>
                                            <td class="px-2 py-2 text-center">
                                                {{ $beneficiary->birthdate }}
                                            </td>
                                            <td class="px-2 py-2 text-center">
                                                {{ $beneficiary->contact_num }}
                                            </td>
                                        </tr>
                                        @if (count($this->beneficiaries) >= 12 && $loop->last)
                                            <tr x-data x-intersect.once="$wire.loadMoreBeneficiaries()">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white p-4 h-[64vh] rounded shadow size-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded size-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($selectedBatchRow === -1)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class="text-blue-900">batches</span> row.
                                    </p>
                                @elseif ($this->beneficiaries->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try adding <span class="text-blue-900">new
                                            beneficiaries</span>.</p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Ask your focal to assign a <span class="text-blue-900">new
                                            batch</span>.</p>
                                @endif
                            </div>
                        </div>
                    @endif
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
            class="fixed left-6 bottom-6 z-50 flex items-center bg-blue-200 text-blue-1000 border border-blue-500 rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
            role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                    clip-rule="evenodd" />
            </svg>
            <p x-text="successMessage"></p>
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

        $wire.on('scroll-top-beneficiaries', () => {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

        });

        $wire.on('scroll-top-batches', () => {
            const batchesTable = document.getElementById('batches-table');
            if (batchesTable) {
                batchesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    </script>
@endscript
