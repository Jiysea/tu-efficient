<x-slot:favicons>
    <x-f-favicons />
    </x-slot>

    <div x-data="{
    open: true,
    isAboveBreakpoint: true,
    isMobile: window.innerWidth < 768,
    promptRestoreModal: $wire.entangle('promptRestoreModal'),
    promptDeleteModal: $wire.entangle('promptDeleteModal')
}" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
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
    }" class="md:ml-20 xl:ml-64 duration-500 ease-in-out">
            <div class="p-2 min-h-screen select-none">

                {{-- Nav Title and Date Dropdown --}}
                <div class="relative flex items-center justify-between w-full my-2 lg:my-0 lg:h-[7.5vh] gap-2">

                    <div class="flex items-center gap-2">
                        <livewire:sidebar.focal-bar />

                        <h1 class="text-xl font-semibold sm:font-bold xl:ms-2">Archives</h1>

                        {{-- Date Range picker --}}
                        <template x-if="!isMobile">
                            <div id="archives-date-range" date-rangepicker datepicker-autohide class="flex items-center gap-1 sm:gap-2 text-xs">
                                <span class="relative inline-flex items-center gap-1 sm:gap-2" x-data="{ pop: false }">

                                    {{-- Start --}}
                                    <div class="relative" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                        <div class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                </g>
                                            </svg>
                                        </div>
                                        <input type="text" readonly id="start-date" wire:model.change="calendarStart" @change-date.camel="$wire.$set('calendarStart', $el.value);" name="start" value="{{ $calendarStart }}" class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8" placeholder="Select date start">
                                    </div>

                                    <span class="text-indigo-1100">to</span>

                                    {{-- End --}}
                                    <div class="relative" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                        <div class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                </g>
                                            </svg>
                                        </div>
                                        <input type="text" readonly id="end-date" wire:model.change="calendarEnd" @change-date.camel="$wire.$set('calendarEnd', $el.value);" name="end" value="{{ $calendarEnd }}" class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8" placeholder="Select date end">
                                    </div>

                                    {{-- Tooltip Content --}}
                                    <div x-cloak x-show="pop" x-transition.opacity class="absolute z-50 top-full mt-2 right-0 rounded p-2 shadow text-xs text-center font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                        This sets the <span class="text-indigo-500">date range</span> of what records to
                                        show <br>
                                        based on the its creation date (start to end)
                                    </div>
                                </span>
                            </div>
                        </template>
                    </div>

                    {{-- Loading State --}}
                    <template x-if="!isMobile">
                        <svg class="size-6 text-indigo-900 animate-spin" wire:loading.flex wire:target="calendarStart, calendarEnd, selectRestore, selectDelete, selectRow, restoreRow, permanentlyDelete" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </template>

                    {{-- MD:Date Range Picker --}}
                    <template x-data="{ show: false }" x-if="isMobile">
                        <div class="relative flex items-center justify-center gap-2 h-full">

                            {{-- MD:Loading State --}}
                            <svg class="text-indigo-900 size-6 animate-spin" wire:loading wire:target="calendarStart, calendarEnd, selectRestore, selectDelete, selectRow, restoreRow, permanentlyDelete" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Show Left Dates --}}
                            <span class="relative" x-data="{ pop: false }">
                                <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;" @click="show = !show;" class="flex items-center justify-center p-1 rounded duration-200 ease-in-out hover:bg-indigo-100 text-zinc-500 hover:text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path d="M93.157 14.058 C 88.540 16.873,87.506 19.624,87.503 29.102 L 87.500 37.500 65.304 37.500 C 36.969 37.500,32.063 38.825,22.483 49.067 C 12.153 60.111,12.432 57.893,12.681 126.869 L 12.891 185.269 16.126 188.286 C 21.669 193.457,28.330 193.457,33.874 188.287 L 37.109 185.270 37.337 161.385 L 37.565 137.500 200.046 137.500 L 362.527 137.500 362.318 245.117 C 362.112 351.136,362.086 352.774,360.547 355.387 C 358.537 358.800,355.734 360.861,351.893 361.752 C 347.818 362.697,52.182 362.697,48.107 361.752 C 39.092 359.661,37.977 356.783,37.500 334.375 C 37.082 314.738,36.969 314.164,32.807 310.662 C 27.942 306.569,21.186 306.994,16.126 311.713 L 12.891 314.729 12.659 335.554 C 12.465 352.942,12.636 357.109,13.697 360.806 C 17.046 372.482,26.754 382.410,38.352 386.020 C 45.124 388.127,353.807 388.358,360.991 386.261 C 372.544 382.889,382.437 373.161,386.020 361.648 C 388.332 354.218,388.332 70.782,386.020 63.352 C 382.437 51.839,372.544 42.111,360.991 38.739 C 357.560 37.737,352.514 37.500,334.624 37.500 L 312.500 37.500 312.497 29.102 C 312.493 16.846,309.225 12.506,300.000 12.506 C 290.775 12.506,287.507 16.846,287.503 29.102 L 287.500 37.500 200.000 37.500 L 112.500 37.500 112.497 29.102 C 112.492 14.820,103.447 7.784,93.157 14.058 M87.503 64.648 C 87.507 67.570,90.074 71.562,93.157 73.442 C 100.677 78.027,112.486 72.658,112.497 64.648 L 112.500 62.500 200.000 62.500 L 287.500 62.500 287.503 64.648 C 287.514 72.658,299.323 78.027,306.843 73.442 C 309.926 71.562,312.493 67.570,312.497 64.648 L 312.500 62.500 330.664 62.519 C 362.294 62.551,361.983 62.258,362.363 92.383 L 362.617 112.500 200.000 112.500 L 37.383 112.500 37.637 92.383 C 38.017 62.236,37.514 62.715,68.945 62.580 L 87.500 62.500 87.503 64.648 M81.641 175.896 C 79.207 177.217,14.882 241.534,13.621 243.907 C 13.004 245.067,12.500 247.809,12.500 250.000 C 12.500 255.994,12.363 255.834,47.410 290.739 C 82.912 326.097,81.626 325.001,87.644 324.997 C 95.270 324.992,99.992 320.270,99.997 312.644 C 100.001 306.721,100.366 307.180,77.071 283.789 L 55.870 262.500 130.083 262.497 C 202.759 262.494,204.350 262.462,206.843 260.942 C 214.551 256.242,214.551 243.758,206.843 239.058 C 204.350 237.538,202.759 237.506,130.083 237.503 L 55.870 237.500 77.071 216.211 C 100.366 192.820,100.001 193.279,99.997 187.356 C 99.991 178.049,89.611 171.569,81.641 175.896 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>

                                {{-- Tooltip Content --}}
                                <div x-cloak x-show="!show && pop" x-transition.opacity class="absolute z-50 top-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                    Toggles the <span class="text-indigo-400">date range</span> menu
                                </div>
                            </span>

                            <div x-show="show" x-transition.opacity class="absolute right-full top-0 me-2 flex flex-col items-center justify-center gap-2 rounded p-2 z-40 border border-indigo-500 bg-white">

                                <span class="text-indigo-1100 text-xs font-medium">
                                    Date Range (Start to End)
                                </span>

                                <div id="archives-date-range" date-rangepicker datepicker-autohide class="flex items-center gap-1 sm:gap-2 text-xs">

                                    {{-- Start --}}
                                    <div class="relative">
                                        <div class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                </g>
                                            </svg>
                                        </div>
                                        <input type="text" readonly id="start-date" @change-date.camel="$wire.$set('calendarStart', $el.value); show = false;" wire:model.change="calendarStart" name="start" value="{{ $calendarStart }}" class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8" placeholder="Select date start">
                                    </div>

                                    <span class="text-indigo-1100">-></span>

                                    {{-- End --}}
                                    <div class="relative">
                                        <div class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                </g>
                                            </svg>
                                        </div>
                                        <input type="text" readonly id="end-date" wire:model.change="calendarEnd" @change-date.camel="$wire.$set('calendarEnd', $el.value); show = false;" name="end" value="{{ $calendarEnd }}" class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8" placeholder="Select date end">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Body --}}
                <div class="relative grid grid-cols-1 w-full lg:h-[90vh] lg:grid-cols-6 gap-4">

                    {{-- List of Archives --}}
                    <div class="relative lg:col-span-3 size-full rounded bg-white shadow">

                        {{-- Upper/Header --}}
                        <div class="relative h-10 flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 mx-2 text-indigo-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path d="M38.095 39.017 C 26.775 42.636,17.071 52.593,13.739 64.009 C 11.775 70.738,11.949 105.121,13.980 111.648 C 17.563 123.161,27.456 132.889,39.009 136.261 C 46.193 138.358,354.876 138.127,361.648 136.020 C 381.167 129.945,387.500 118.058,387.500 87.500 C 387.500 56.942,381.167 45.055,361.648 38.980 C 354.347 36.708,45.208 36.743,38.095 39.017 M37.500 246.538 C 37.500 331.069,37.519 332.138,39.123 336.939 C 43.122 348.907,52.220 357.599,64.773 361.445 C 71.475 363.498,330.734 362.952,336.939 360.871 C 348.502 356.994,357.022 348.476,360.877 336.939 C 362.481 332.138,362.500 331.069,362.500 246.538 L 362.500 160.996 357.227 161.553 C 350.339 162.280,49.661 162.280,42.773 161.553 L 37.500 160.996 37.500 246.538 M242.966 214.495 C 251.357 219.425,251.357 230.575,242.966 235.505 C 238.699 238.011,161.301 238.011,157.034 235.505 C 147.216 229.737,149.283 215.951,160.371 213.241 C 166.757 211.681,240.069 212.794,242.966 214.495 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <h1 class="max-[480px]:hidden text-base font-semibold sm:font-bold">
                                    <span class="hidden sm:inline">
                                        List of
                                    </span>
                                    Records
                                </h1>
                                <span class="py-1 px-2 text-xs font-medium text-indigo-700 bg-indigo-100 rounded">
                                    {{ count($this->archives) }}
                                </span>
                            </div>
                            {{-- Search and Add Button | and Slots (for lower lg) --}}
                            <div class="mx-2 flex items-center justify-end">

                                {{-- General Search Box --}}
                                <div class="relative me-2">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $this->archivesCount > 0 || $this->archives->isNotEmpty() || $searchArchives ? 'text-indigo-800' : 'text-zinc-400' }}">

                                        {{-- Loading Icon --}}
                                        <svg class="size-3 animate-spin" wire:loading wire:target="searchArchives" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>

                                        {{-- Search Icon --}}
                                        <svg class="size-3" wire:loading.remove wire:target="searchArchives" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>

                                    {{-- Search Input Bar --}}
                                    <input type="text" id="searchArchives" maxlength="100" autocomplete="off" @if (($this->archivesCount <= 0 || $this->archives->isEmpty()) && !$searchArchives) disabled @endif
                                        wire:model.live.debounce.300ms="searchArchives"
                                        class="{{ $this->archivesCount > 0 || $this->archives->isNotEmpty() || $searchArchives
                                        ? 'selection:bg-indigo-700 selection:text-indigo-50 text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full"
                                        placeholder="Search for records">
                                </div>
                            </div>
                        </div>

                        @if ($this->archives->isNotEmpty())
                        {{-- List of Archived Records --}}
                        <div id="archived-records-table" class="relative min-h-[84vh] max-h-[84vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="absolute h-full w-1 left-0">
                                            {{-- Selected Row Indicator --}}
                                        </th>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="pr-6 py-2">
                                            full name
                                        </th>
                                        <th scope="col" class="p-2">
                                            archived at
                                        </th>
                                        <th scope="col" class="p-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($this->archives as $key => $archive)
                                    <tr wire:key="archive-{{ $key }}" wire:loading.class="pointer-events-none" wire:target="" wire:click="selectRow({{ $key }}, '{{ encrypt($archive['id']) }}')" class="relative border-b whitespace-nowrap duration-200 cursor-pointer ease-in-out
                                                {{ $selectedRowKey === $key ? 'bg-indigo-100 hover:bg-indigo-50 text-indigo-900' : 'hover:bg-gray-50 text-indigo-1100' }}">
                                        <td class="absolute h-full w-1 left-0
                                                {{ $selectedRowKey === $key ? 'bg-indigo-700' : 'bg-transparent' }}">
                                            {{-- Selected Row Indicator --}}
                                        </td>
                                        <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="pr-6 py-2">
                                            {{ $this->full_name($archive['data']) }}
                                        </td>
                                        <td class="p-2">
                                            {{ \Carbon\Carbon::parse($archive['archived_at'])->format('M d, Y @ h:i:s A') }}
                                        </td>

                                        {{-- User Dropdown --}}
                                        <td class="flex items-center p-1">
                                            {{-- Restore Button --}}
                                            <button type="button" @click.stop="$wire.selectRestore('{{ encrypt($archive['id']) }}');" id="restoreButton-{{ $key }}" class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out 
                                                    {{ $selectedRowKey === $key
                                                        ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50'
                                                        : 'text-indigo-1100 hover:bg-indigo-200 hover:text-indigo-700 focus:bg-indigo-200 focus:text-indigo-700' }}">

                                                {{-- Restore Icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path d="M194.141 25.896 C 191.706 27.218,139.880 79.035,138.621 81.407 C 136.962 84.528,137.191 91.280,139.058 94.343 C 142.216 99.523,143.991 99.994,160.352 99.997 L 175.000 100.000 175.000 125.000 L 175.000 150.000 200.000 150.000 L 225.000 150.000 225.000 125.000 L 225.000 100.000 239.648 99.997 C 256.009 99.994,257.784 99.523,260.942 94.343 C 262.687 91.480,263.067 84.613,261.637 81.785 C 260.408 79.356,208.636 27.471,206.093 26.121 C 203.624 24.809,196.392 24.673,194.141 25.896 M101.574 151.221 C 53.247 170.990,53.798 170.734,51.528 174.457 C 50.051 176.880,50.006 178.916,50.006 243.750 C 50.006 317.624,49.725 313.392,54.846 316.700 C 59.773 319.883,183.148 373.505,185.041 373.286 L 187.109 373.047 187.309 298.750 L 187.508 224.452 142.582 203.086 C 117.873 191.335,97.657 181.527,97.658 181.290 C 97.659 181.053,110.457 175.638,126.099 169.256 C 141.740 162.874,155.629 156.989,156.963 156.177 C 164.845 151.384,164.436 138.603,156.262 134.259 C 150.600 131.250,149.739 131.517,101.574 151.221 M243.888 134.127 C 235.723 138.151,235.152 151.382,242.939 156.118 C 244.220 156.897,258.110 162.779,273.806 169.190 C 289.502 175.601,302.344 181.051,302.344 181.302 C 302.344 181.552,282.127 191.367,257.418 203.113 L 212.492 224.470 212.691 298.758 L 212.891 373.047 214.966 373.287 C 216.881 373.509,340.347 319.857,345.154 316.714 C 350.277 313.365,349.994 317.625,349.994 243.750 C 349.994 178.916,349.949 176.880,348.472 174.457 C 346.201 170.732,346.743 170.983,298.325 151.201 C 250.430 131.633,249.526 131.349,243.888 134.127 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                                    </g>
                                                </svg>
                                            </button>

                                            {{-- Permanently Delete Button --}}
                                            <button type="button" @click.stop="$wire.selectDelete('{{ encrypt($archive['id']) }}');" id="deleteButton-{{ $key }}" class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out 
                                                    {{ $selectedRowKey === $key
                                                        ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50'
                                                        : 'text-indigo-1100 hover:bg-indigo-200 hover:text-indigo-700 focus:bg-indigo-200 focus:text-indigo-700' }}">

                                                {{-- Delete Icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path d="M147.853 6.332 C 143.390 8.541,141.189 11.347,136.456 20.863 L 132.456 28.906 85.292 28.906 C 24.056 28.906,29.297 26.603,29.297 53.516 C 29.297 72.816,29.634 74.095,35.454 76.857 C 39.559 78.805,360.441 78.805,364.546 76.857 C 370.366 74.095,370.703 72.816,370.703 53.516 C 370.703 26.603,375.944 28.906,314.708 28.906 L 267.544 28.906 263.544 20.863 C 254.873 3.429,259.819 4.687,199.949 4.688 C 151.950 4.689,151.119 4.715,147.853 6.332 M53.458 108.398 C 53.730 111.729,57.465 170.703,61.758 239.453 C 66.050 308.203,69.924 366.020,70.368 367.935 C 72.653 377.801,80.578 387.326,90.406 392.019 L 96.484 394.922 200.000 394.922 L 303.516 394.922 309.594 392.019 C 319.422 387.326,327.347 377.801,329.632 367.935 C 330.076 366.020,333.950 308.203,338.242 239.453 C 342.535 170.703,346.270 111.729,346.542 108.398 L 347.038 102.344 200.000 102.344 L 52.962 102.344 53.458 108.398 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
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
                        <div class="relative bg-white px-4 pb-4 pt-2 h-[84vh] min-w-full flex items-center justify-center">
                            <div class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($this->archivesCount <= 0) <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-zinc-300" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path d="M194.922 29.970 C 193.633 30.569,191.523 32.180,190.234 33.549 L 187.891 36.039 187.686 74.465 C 187.488 111.728,187.529 112.968,189.046 115.437 C 193.783 123.146,206.242 123.146,210.942 115.437 C 212.434 112.989,212.494 111.432,212.497 75.311 L 212.500 37.731 210.742 35.148 C 207.041 29.711,200.257 27.490,194.922 29.970 M95.847 80.003 C 90.219 82.076,87.500 85.931,87.500 91.835 C 87.500 99.196,87.584 99.272,117.810 119.421 L 145.517 137.891 150.045 137.891 C 157.571 137.891,162.500 132.857,162.500 125.172 C 162.500 118.054,162.061 117.657,132.247 97.819 C 103.089 78.417,101.826 77.799,95.847 80.003 M295.574 79.645 C 290.041 82.015,240.096 116.438,238.703 118.842 C 233.509 127.804,240.986 139.236,251.281 138.076 C 256.425 137.496,309.227 102.364,311.199 98.209 C 316.208 87.654,305.785 75.271,295.574 79.645 M277.332 145.197 C 266.604 150.897,268.653 164.942,280.869 169.440 C 295.553 174.847,296.412 175.238,294.873 175.820 C 248.788 193.246,200.992 210.938,200.000 210.938 C 199.013 210.938,149.602 192.648,105.174 175.837 C 104.075 175.422,105.416 174.638,110.517 172.712 C 123.476 167.821,127.983 158.647,121.318 150.726 C 115.557 143.880,112.591 144.204,84.437 154.752 C 54.358 166.022,59.528 160.597,38.239 203.227 C 19.619 240.510,19.293 241.359,21.522 246.694 C 23.679 251.857,25.179 252.702,46.161 260.572 L 66.406 268.166 66.406 288.014 C 66.406 326.845,64.235 324.865,136.627 352.014 L 187.109 370.947 187.500 324.731 L 187.891 278.516 194.046 266.411 L 200.201 254.306 206.155 266.020 L 212.109 277.734 212.500 324.341 L 212.891 370.947 263.373 352.014 C 316.504 332.089,318.573 331.186,324.304 325.426 C 332.264 317.426,333.550 312.152,333.575 287.403 L 333.594 268.166 353.839 260.572 C 366.066 255.986,374.715 252.307,375.677 251.283 C 377.780 249.044,379.688 244.373,379.688 241.461 C 379.688 237.547,344.461 167.807,341.253 165.371 C 337.539 162.551,286.997 143.750,283.129 143.750 C 281.437 143.750,278.829 144.401,277.332 145.197 M128.654 212.293 L 181.917 232.270 171.815 252.463 C 166.259 263.569,161.447 272.656,161.121 272.656 C 158.254 272.656,53.337 236.333,51.716 234.779 C 50.875 233.973,73.170 191.438,74.217 191.851 C 74.862 192.107,99.359 201.305,128.654 212.293 M348.244 234.829 C 346.746 236.327,241.732 272.656,238.899 272.656 C 238.563 272.656,233.751 263.589,228.206 252.506 L 218.125 232.356 272.539 211.944 L 326.953 191.533 337.741 213.039 C 343.674 224.867,348.400 234.673,348.244 234.829 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                    </svg>
                                    <p>No records found.</p>
                                    <p>
                                        Archived records will be shown here.
                                    </p>
                                    @elseif ($this->isDateChanged)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-zinc-300" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path d="M194.922 29.970 C 193.633 30.569,191.523 32.180,190.234 33.549 L 187.891 36.039 187.686 74.465 C 187.488 111.728,187.529 112.968,189.046 115.437 C 193.783 123.146,206.242 123.146,210.942 115.437 C 212.434 112.989,212.494 111.432,212.497 75.311 L 212.500 37.731 210.742 35.148 C 207.041 29.711,200.257 27.490,194.922 29.970 M95.847 80.003 C 90.219 82.076,87.500 85.931,87.500 91.835 C 87.500 99.196,87.584 99.272,117.810 119.421 L 145.517 137.891 150.045 137.891 C 157.571 137.891,162.500 132.857,162.500 125.172 C 162.500 118.054,162.061 117.657,132.247 97.819 C 103.089 78.417,101.826 77.799,95.847 80.003 M295.574 79.645 C 290.041 82.015,240.096 116.438,238.703 118.842 C 233.509 127.804,240.986 139.236,251.281 138.076 C 256.425 137.496,309.227 102.364,311.199 98.209 C 316.208 87.654,305.785 75.271,295.574 79.645 M277.332 145.197 C 266.604 150.897,268.653 164.942,280.869 169.440 C 295.553 174.847,296.412 175.238,294.873 175.820 C 248.788 193.246,200.992 210.938,200.000 210.938 C 199.013 210.938,149.602 192.648,105.174 175.837 C 104.075 175.422,105.416 174.638,110.517 172.712 C 123.476 167.821,127.983 158.647,121.318 150.726 C 115.557 143.880,112.591 144.204,84.437 154.752 C 54.358 166.022,59.528 160.597,38.239 203.227 C 19.619 240.510,19.293 241.359,21.522 246.694 C 23.679 251.857,25.179 252.702,46.161 260.572 L 66.406 268.166 66.406 288.014 C 66.406 326.845,64.235 324.865,136.627 352.014 L 187.109 370.947 187.500 324.731 L 187.891 278.516 194.046 266.411 L 200.201 254.306 206.155 266.020 L 212.109 277.734 212.500 324.341 L 212.891 370.947 263.373 352.014 C 316.504 332.089,318.573 331.186,324.304 325.426 C 332.264 317.426,333.550 312.152,333.575 287.403 L 333.594 268.166 353.839 260.572 C 366.066 255.986,374.715 252.307,375.677 251.283 C 377.780 249.044,379.688 244.373,379.688 241.461 C 379.688 237.547,344.461 167.807,341.253 165.371 C 337.539 162.551,286.997 143.750,283.129 143.750 C 281.437 143.750,278.829 144.401,277.332 145.197 M128.654 212.293 L 181.917 232.270 171.815 252.463 C 166.259 263.569,161.447 272.656,161.121 272.656 C 158.254 272.656,53.337 236.333,51.716 234.779 C 50.875 233.973,73.170 191.438,74.217 191.851 C 74.862 192.107,99.359 201.305,128.654 212.293 M348.244 234.829 C 346.746 236.327,241.732 272.656,238.899 272.656 C 238.563 272.656,233.751 263.589,228.206 252.506 L 218.125 232.356 272.539 211.944 L 326.953 191.533 337.741 213.039 C 343.674 224.867,348.400 234.673,348.244 234.829 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No records found.</p>
                                    <p>Maybe try a different <span class="text-indigo-700">date range</span>.
                                    </p>
                                    @elseif ($searchArchives)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-zinc-300" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path d="M194.922 29.970 C 193.633 30.569,191.523 32.180,190.234 33.549 L 187.891 36.039 187.686 74.465 C 187.488 111.728,187.529 112.968,189.046 115.437 C 193.783 123.146,206.242 123.146,210.942 115.437 C 212.434 112.989,212.494 111.432,212.497 75.311 L 212.500 37.731 210.742 35.148 C 207.041 29.711,200.257 27.490,194.922 29.970 M95.847 80.003 C 90.219 82.076,87.500 85.931,87.500 91.835 C 87.500 99.196,87.584 99.272,117.810 119.421 L 145.517 137.891 150.045 137.891 C 157.571 137.891,162.500 132.857,162.500 125.172 C 162.500 118.054,162.061 117.657,132.247 97.819 C 103.089 78.417,101.826 77.799,95.847 80.003 M295.574 79.645 C 290.041 82.015,240.096 116.438,238.703 118.842 C 233.509 127.804,240.986 139.236,251.281 138.076 C 256.425 137.496,309.227 102.364,311.199 98.209 C 316.208 87.654,305.785 75.271,295.574 79.645 M277.332 145.197 C 266.604 150.897,268.653 164.942,280.869 169.440 C 295.553 174.847,296.412 175.238,294.873 175.820 C 248.788 193.246,200.992 210.938,200.000 210.938 C 199.013 210.938,149.602 192.648,105.174 175.837 C 104.075 175.422,105.416 174.638,110.517 172.712 C 123.476 167.821,127.983 158.647,121.318 150.726 C 115.557 143.880,112.591 144.204,84.437 154.752 C 54.358 166.022,59.528 160.597,38.239 203.227 C 19.619 240.510,19.293 241.359,21.522 246.694 C 23.679 251.857,25.179 252.702,46.161 260.572 L 66.406 268.166 66.406 288.014 C 66.406 326.845,64.235 324.865,136.627 352.014 L 187.109 370.947 187.500 324.731 L 187.891 278.516 194.046 266.411 L 200.201 254.306 206.155 266.020 L 212.109 277.734 212.500 324.341 L 212.891 370.947 263.373 352.014 C 316.504 332.089,318.573 331.186,324.304 325.426 C 332.264 317.426,333.550 312.152,333.575 287.403 L 333.594 268.166 353.839 260.572 C 366.066 255.986,374.715 252.307,375.677 251.283 C 377.780 249.044,379.688 244.373,379.688 241.461 C 379.688 237.547,344.461 167.807,341.253 165.371 C 337.539 162.551,286.997 143.750,283.129 143.750 C 281.437 143.750,278.829 144.401,277.332 145.197 M128.654 212.293 L 181.917 232.270 171.815 252.463 C 166.259 263.569,161.447 272.656,161.121 272.656 C 158.254 272.656,53.337 236.333,51.716 234.779 C 50.875 233.973,73.170 191.438,74.217 191.851 C 74.862 192.107,99.359 201.305,128.654 212.293 M348.244 234.829 C 346.746 236.327,241.732 272.656,238.899 272.656 C 238.563 272.656,233.751 263.589,228.206 252.506 L 218.125 232.356 272.539 211.944 L 326.953 191.533 337.741 213.039 C 343.674 224.867,348.400 234.673,348.244 234.829 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No records found.</p>
                                    <p>Try a different <span class="text-indigo-700">search term</span>.
                                    </p>
                                    @elseif(!$defaultArchive)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-zinc-300" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path d="M194.922 29.970 C 193.633 30.569,191.523 32.180,190.234 33.549 L 187.891 36.039 187.686 74.465 C 187.488 111.728,187.529 112.968,189.046 115.437 C 193.783 123.146,206.242 123.146,210.942 115.437 C 212.434 112.989,212.494 111.432,212.497 75.311 L 212.500 37.731 210.742 35.148 C 207.041 29.711,200.257 27.490,194.922 29.970 M95.847 80.003 C 90.219 82.076,87.500 85.931,87.500 91.835 C 87.500 99.196,87.584 99.272,117.810 119.421 L 145.517 137.891 150.045 137.891 C 157.571 137.891,162.500 132.857,162.500 125.172 C 162.500 118.054,162.061 117.657,132.247 97.819 C 103.089 78.417,101.826 77.799,95.847 80.003 M295.574 79.645 C 290.041 82.015,240.096 116.438,238.703 118.842 C 233.509 127.804,240.986 139.236,251.281 138.076 C 256.425 137.496,309.227 102.364,311.199 98.209 C 316.208 87.654,305.785 75.271,295.574 79.645 M277.332 145.197 C 266.604 150.897,268.653 164.942,280.869 169.440 C 295.553 174.847,296.412 175.238,294.873 175.820 C 248.788 193.246,200.992 210.938,200.000 210.938 C 199.013 210.938,149.602 192.648,105.174 175.837 C 104.075 175.422,105.416 174.638,110.517 172.712 C 123.476 167.821,127.983 158.647,121.318 150.726 C 115.557 143.880,112.591 144.204,84.437 154.752 C 54.358 166.022,59.528 160.597,38.239 203.227 C 19.619 240.510,19.293 241.359,21.522 246.694 C 23.679 251.857,25.179 252.702,46.161 260.572 L 66.406 268.166 66.406 288.014 C 66.406 326.845,64.235 324.865,136.627 352.014 L 187.109 370.947 187.500 324.731 L 187.891 278.516 194.046 266.411 L 200.201 254.306 206.155 266.020 L 212.109 277.734 212.500 324.341 L 212.891 370.947 263.373 352.014 C 316.504 332.089,318.573 331.186,324.304 325.426 C 332.264 317.426,333.550 312.152,333.575 287.403 L 333.594 268.166 353.839 260.572 C 366.066 255.986,374.715 252.307,375.677 251.283 C 377.780 249.044,379.688 244.373,379.688 241.461 C 379.688 237.547,344.461 167.807,341.253 165.371 C 337.539 162.551,286.997 143.750,283.129 143.750 C 281.437 143.750,278.829 144.401,277.332 145.197 M128.654 212.293 L 181.917 232.270 171.815 252.463 C 166.259 263.569,161.447 272.656,161.121 272.656 C 158.254 272.656,53.337 236.333,51.716 234.779 C 50.875 233.973,73.170 191.438,74.217 191.851 C 74.862 192.107,99.359 201.305,128.654 212.293 M348.244 234.829 C 346.746 236.327,241.732 272.656,238.899 272.656 C 238.563 272.656,233.751 263.589,228.206 252.506 L 218.125 232.356 272.539 211.944 L 326.953 191.533 337.741 213.039 C 343.674 224.867,348.400 234.673,348.244 234.829 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No records found.</p>
                                    <p>
                                        Toggle <span class="text-indigo-700">default archive</span> from settings<br>
                                        to enable this feature.
                                    </p>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-zinc-300" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path d="M194.922 29.970 C 193.633 30.569,191.523 32.180,190.234 33.549 L 187.891 36.039 187.686 74.465 C 187.488 111.728,187.529 112.968,189.046 115.437 C 193.783 123.146,206.242 123.146,210.942 115.437 C 212.434 112.989,212.494 111.432,212.497 75.311 L 212.500 37.731 210.742 35.148 C 207.041 29.711,200.257 27.490,194.922 29.970 M95.847 80.003 C 90.219 82.076,87.500 85.931,87.500 91.835 C 87.500 99.196,87.584 99.272,117.810 119.421 L 145.517 137.891 150.045 137.891 C 157.571 137.891,162.500 132.857,162.500 125.172 C 162.500 118.054,162.061 117.657,132.247 97.819 C 103.089 78.417,101.826 77.799,95.847 80.003 M295.574 79.645 C 290.041 82.015,240.096 116.438,238.703 118.842 C 233.509 127.804,240.986 139.236,251.281 138.076 C 256.425 137.496,309.227 102.364,311.199 98.209 C 316.208 87.654,305.785 75.271,295.574 79.645 M277.332 145.197 C 266.604 150.897,268.653 164.942,280.869 169.440 C 295.553 174.847,296.412 175.238,294.873 175.820 C 248.788 193.246,200.992 210.938,200.000 210.938 C 199.013 210.938,149.602 192.648,105.174 175.837 C 104.075 175.422,105.416 174.638,110.517 172.712 C 123.476 167.821,127.983 158.647,121.318 150.726 C 115.557 143.880,112.591 144.204,84.437 154.752 C 54.358 166.022,59.528 160.597,38.239 203.227 C 19.619 240.510,19.293 241.359,21.522 246.694 C 23.679 251.857,25.179 252.702,46.161 260.572 L 66.406 268.166 66.406 288.014 C 66.406 326.845,64.235 324.865,136.627 352.014 L 187.109 370.947 187.500 324.731 L 187.891 278.516 194.046 266.411 L 200.201 254.306 206.155 266.020 L 212.109 277.734 212.500 324.341 L 212.891 370.947 263.373 352.014 C 316.504 332.089,318.573 331.186,324.304 325.426 C 332.264 317.426,333.550 312.152,333.575 287.403 L 333.594 268.166 353.839 260.572 C 366.066 255.986,374.715 252.307,375.677 251.283 C 377.780 249.044,379.688 244.373,379.688 241.461 C 379.688 237.547,344.461 167.807,341.253 165.371 C 337.539 162.551,286.997 143.750,283.129 143.750 C 281.437 143.750,278.829 144.401,277.332 145.197 M128.654 212.293 L 181.917 232.270 171.815 252.463 C 166.259 263.569,161.447 272.656,161.121 272.656 C 158.254 272.656,53.337 236.333,51.716 234.779 C 50.875 233.973,73.170 191.438,74.217 191.851 C 74.862 192.107,99.359 201.305,128.654 212.293 M348.244 234.829 C 346.746 236.327,241.732 272.656,238.899 272.656 C 238.563 272.656,233.751 263.589,228.206 252.506 L 218.125 232.356 272.539 211.944 L 326.953 191.533 337.741 213.039 C 343.674 224.867,348.400 234.673,348.244 234.829 " stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No records found.</p>
                                    <p>
                                        Archived records will be shown here.
                                    </p>
                                    @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Beneficiary Preview --}}
                    <div class="relative lg:col-span-3 flex flex-col size-full rounded bg-white shadow">

                        <livewire:focal.archives.record-preview :$archiveId />

                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Notifications --}}
        <div x-data="{
        alerts: $wire.entangle('alerts'),
        timeouts: {},
        removeAlert(id) {
            clearTimeout(this.timeouts[id]);
            this.timeouts[id];
            $wire.removeAlert(id);
        },
        setupTimeouts() {
            if (Array.isArray(this.alerts) && this.alerts.length > 0) {
                this.alerts.forEach(alert => {
                    if (!this.timeouts[alert.id]) {
                        this.timeouts[alert.id] = setTimeout(() => {
                            this.removeAlert(alert.id);
                        }, 3000);
                    }
                });
            }
        }
    }" x-effect="setupTimeouts()" class="fixed left-6 bottom-6 z-50 flex flex-col gap-y-3">
            {{-- Loop through alerts --}}
            <template x-for="alert in alerts" :key="alert.id">
                <div x-show="show" x-data="{ show: false }" x-init="$nextTick(() => { show = true });" x-transition:enter="transition ease-in-out duration-300 origin-left" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="flex items-center gap-2 border rounded-lg text-sm sm:text-md font-bold p-3 select-none" :class="`bg-${alert.color}-200 text-${alert.color}-900 border-${alert.color}-500`" role="alert">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current size-4">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                    </svg>
                    <p x-text="alert.message"></p>
                    <button type="button" @click="removeAlert(alert.id)" class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400">
                            <g>
                                <path d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 " stroke="none" fill="currentColor" fill-rule="evenodd">
                                </path>
                            </g>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- Modals --}}
        <livewire:focal.archives.prompt-restore-modal :$actionId />
        <livewire:focal.archives.prompt-delete-modal :$actionId />

    </div>
    @script
    <script>
        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });

        $wire.on('scroll-top-archives', () => {
            const archivesTable = document.getElementById('archived-records-table');
            if (archivesTable) {
                archivesTable.scrollTo({
                    top: 0
                    , behavior: 'smooth'
                });
            }
        });

    </script>
    @endscript
