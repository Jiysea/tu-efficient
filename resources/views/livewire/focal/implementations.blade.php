<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, isAboveBreakpoint: true, isMobile: window.innerWidth < 768, promptMultiDeleteModal: $wire.entangle('promptMultiDeleteModal') }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});
window.addEventListener('resize', () => {
    isMobile = window.innerWidth < 768;
    $wire.$dispatchSelf('init-reload');
});">

    <div :class="{
        'md:ml-20': open === false,
        'md:ml-20 xl:ml-64': open === true,
    }"
        class="md:ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Date Dropdown --}}
            <div class="relative flex items-center justify-between w-full gap-2 my-2 lg:my-0 lg:h-[7.5vh]">
                <div class="flex items-center gap-2">
                    <livewire:sidebar.focal-bar />

                    <h1 class="text-xl font-semibold sm:font-bold xl:ms-2">Implementations
                    </h1>

                    {{-- Date Range picker --}}
                    <template x-if="!isMobile">
                        <div id="implementations-date-range" date-rangepicker datepicker-autohide
                            class="flex items-center gap-1 sm:gap-2 text-xs">
                            <span class="relative inline-flex items-center gap-1 sm:gap-2" x-data="{ pop: false }">
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
                                        @change-date.camel="$wire.$set('calendarStart', $el.value); " name="start"
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
                                        @change-date.camel="$wire.$set('calendarEnd', $el.value); " name="end"
                                        value="{{ $calendarEnd }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date end">
                                </div>

                                {{-- Tooltip Content --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 top-full mt-2 right-0 rounded p-2 shadow text-xs text-center font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
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
                    <svg class="text-indigo-900 size-6 animate-spin" wire:loading
                        wire:target="calendarStart, calendarEnd, selectImplementationRow, viewImplementation, selectBatchRow, viewBatch, selectBeneficiaryRow, selectedBeneficiaryRow, selectShiftBeneficiary, viewBeneficiary, loadMoreImplementations, loadMoreBeneficiaries, saveProject, editProject, deleteProject, saveBatches, editBatch, deleteBatch, saveBeneficiaries, editBeneficiary, deleteBeneficiary, archiveBeneficiary, removeBeneficiaries, showExport"
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
                            wire:target="calendarStart, calendarEnd, selectImplementationRow, viewImplementation, selectBatchRow, viewBatch, selectBeneficiaryRow, selectedBeneficiaryRow, selectShiftBeneficiary, viewBeneficiary, loadMoreImplementations, loadMoreBeneficiaries, saveProject, editProject, deleteProject, saveBatches, editBatch, deleteBatch, saveBeneficiaries, editBeneficiary, deleteBeneficiary, archiveBeneficiary, removeBeneficiaries, showExport"
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

                            <div id="implementations-date-range" date-rangepicker datepicker-autohide
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
                                        @change-date.camel="$wire.$set('calendarStart', $el.value);  show = false;"
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
                                        @change-date.camel="$wire.$set('calendarEnd', $el.value);  show = false;"
                                        name="end" value="{{ $calendarEnd }}"
                                        class="cursor-pointer selection:bg-white bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                        placeholder="Select date end">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="relative grid grid-cols-1 w-full lg:h-[90vh] gap-4 lg:grid-cols-5">
                {{-- List of Projects --}}
                <div class="relative lg:col-span-3 size-full rounded bg-white shadow" x-data="{ createProjectModal: $wire.entangle('createProjectModal'), viewProjectModal: $wire.entangle('viewProjectModal') }">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 flex items-center justify-between">
                        <div class="inline-flex my-2 items-center text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M174.219 1.229 C 54.472 18.124,-24.443 135.741,6.311 251.484 C 9.642 264.022,18.559 287.500,19.989 287.500 C 20.159 287.500,25.487 284.951,31.829 281.836 C 38.171 278.721,43.450 276.139,43.562 276.100 C 43.673 276.060,42.661 273.599,41.313 270.631 C 20.301 224.370,21.504 168.540,44.499 122.720 C 91.474 29.119,207.341 -2.229,294.805 55.000 L 303.283 60.547 296.563 60.773 L 289.844 60.998 289.844 75.030 L 289.844 89.063 316.041 89.063 C 356.109 89.062,354.775 90.537,350.877 50.558 C 349.488 36.310,348.202 24.504,348.019 24.321 C 347.676 23.978,328.468 25.531,323.192 26.328 L 320.212 26.778 320.757 33.742 L 321.302 40.706 315.480 36.529 C 276.374 8.472,220.985 -5.369,174.219 1.229 M146.501 97.750 C 118.151 111.473,94.683 122.973,94.351 123.305 C 94.019 123.637,117.528 137.000,146.593 153.000 L 199.439 182.092 252.454 153.019 C 281.612 137.028,305.456 123.743,305.440 123.496 C 305.396 122.820,200.285 72.645,199.085 72.727 C 198.514 72.766,174.851 84.026,146.501 97.750 M367.815 118.385 L 356.334 124.187 358.736 129.476 C 379.696 175.622,378.473 231.507,355.501 277.280 C 308.659 370.616,191.853 402.240,105.195 345.048 L 96.718 339.453 103.828 339.228 L 110.938 339.004 110.938 324.971 L 110.938 310.938 83.858 310.938 L 56.778 310.937 53.464 312.880 C 49.750 315.056,46.875 319.954,46.875 324.105 C 46.875 327.673,51.612 375.310,52.006 375.704 C 52.327 376.025,69.823 374.588,76.418 373.699 L 79.790 373.245 79.242 366.245 L 78.695 359.245 84.074 363.146 C 180.358 432.973,317.505 400.914,375.933 294.922 C 405.531 241.229,408.161 173.609,382.825 117.732 C 379.977 111.450,381.685 111.375,367.815 118.385 M75.190 209.482 L 75.391 269.080 129.223 295.087 C 158.831 309.391,183.177 321.094,183.325 321.094 C 183.473 321.094,183.585 295.869,183.574 265.039 L 183.554 208.984 130.305 179.688 C 101.018 163.574,76.591 150.277,76.023 150.137 C 75.172 149.928,75.026 160.392,75.190 209.482 M269.139 179.604 L 215.234 209.207 215.034 265.236 C 214.844 318.400,214.904 321.239,216.206 320.749 C 216.961 320.466,241.562 308.738,270.876 294.687 L 324.174 269.141 324.197 209.570 C 324.209 176.807,323.954 150.000,323.631 150.000 C 323.307 150.000,298.786 163.322,269.139 179.604 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="hidden lg:inline-block ms-2 font-bold">List of Projects</h1>
                            <h1 class="max-[460px]:hidden ms-2 font-bold text-sm lg:hidden">Projects</h1>
                            <span
                                class="{{ $this->totalImplementations ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700 ' }} rounded ms-2 px-2 py-1 font-medium text-xs">{{ $this->totalImplementations ?? 0 }}</span>
                        </div>

                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="relative mx-2 flex items-center justify-end">

                            {{-- General Search Box --}}
                            <div class="relative me-2">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $this->implementations->isNotEmpty() || $searchProjects ? 'text-indigo-800' : 'text-zinc-400' }}">

                                    {{-- Loading Icon --}}
                                    <svg class="size-3 animate-spin" wire:loading wire:target="searchProjects"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Search Icon --}}
                                    <svg class="size-3" wire:loading.remove wire:target="searchProjects"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>

                                {{-- Search Input Bar --}}
                                <input type="text" id="searchProjects" maxlength="100" autocomplete="off"
                                    @if ($this->implementations->isEmpty() && !$searchProjects) disabled @endif
                                    wire:model.live.debounce.300ms="searchProjects"
                                    class="{{ $this->implementations->isNotEmpty() || $searchProjects
                                        ? 'selection:bg-indigo-700 selection:text-indigo-50 text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full"
                                    placeholder="Search for projects">
                            </div>

                            <span class="relative" x-data="{ pop: false }">
                                <button @mouseleave="pop = false;" @mouseenter="pop = true;"
                                    @click="createProjectModal = !createProjectModal;"
                                    class="flex items-center bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500 duration-200 ease-in-out">
                                    CREATE
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ml-2"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M87.232 51.235 C 70.529 55.279,55.160 70.785,51.199 87.589 C 49.429 95.097,49.415 238.777,51.184 245.734 C 55.266 261.794,68.035 275.503,84.375 281.371 L 89.453 283.195 164.063 283.423 C 247.935 283.680,244.564 283.880,256.471 277.921 C 265.327 273.488,273.488 265.327,277.921 256.471 C 283.880 244.564,283.680 247.935,283.423 164.063 L 283.195 89.453 281.371 84.375 C 275.503 68.035,261.794 55.266,245.734 51.184 C 239.024 49.478,94.296 49.525,87.232 51.235 M326.172 101.100 C 323.101 102.461,320.032 105.395,318.240 108.682 C 316.870 111.194,316.777 115.490,316.406 193.359 L 316.016 275.391 313.810 281.633 C 308.217 297.460,296.571 308.968,280.859 314.193 L 275.391 316.012 193.359 316.404 L 111.328 316.797 108.019 318.693 C 97.677 324.616,97.060 340.415,106.903 347.255 L 110.291 349.609 195.575 349.609 L 280.859 349.609 287.500 347.798 C 317.300 339.669,339.049 318.056,347.783 287.891 L 349.592 281.641 349.816 196.680 C 350.060 104.007,350.312 109.764,345.807 104.807 C 341.717 100.306,332.072 98.485,326.172 101.100 M172.486 118.401 C 180.422 121.716,182.772 126.649,182.795 140.039 L 182.813 150.000 190.518 150.000 C 209.679 150.000,219.220 157.863,215.628 170.693 C 213.075 179.810,207.578 182.771,193.164 182.795 L 182.813 182.813 182.795 193.164 C 182.771 207.578,179.810 213.075,170.693 215.628 C 157.863 219.220,150.000 209.679,150.000 190.518 L 150.000 182.813 140.039 182.795 C 123.635 182.767,116.211 176.839,117.378 164.698 C 118.318 154.920,125.026 150.593,139.970 150.128 L 150.000 149.815 150.000 142.592 C 150.000 122.755,159.204 112.853,172.486 118.401 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>
                                {{-- Popover --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 bottom-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                    Create an Implementation Project
                                </div>
                            </span>
                        </div>
                    </div>

                    @if (!$this->implementations->isEmpty())
                        {{-- List of Projects Table --}}
                        <div id="implementations-table"
                            class="relative h-[36vh] overflow-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="absolute h-full w-1 left-0">
                                            {{-- Selected Row Indicator --}}
                                        </th>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            project #
                                        </th>
                                        <th scope="col" class="pr-6 py-2">
                                            project title
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            total slots
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            status
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody x-data="{ count: 0 }" class="relative text-xs">
                                    @foreach ($this->implementations as $key => $implementation)
                                        <tr wire:key="implementation-{{ $key }}"
                                            wire:loading.class="pointer-events-none"
                                            wire:target="selectImplementationRow, viewImplementation"
                                            @click="count++;"
                                            @click.debounce.350ms="if(!$event.ctrlKey && count === 1) {$wire.selectImplementationRow({{ $key }}, '{{ encrypt($implementation->id) }}'); count = 0;}"
                                            @click.ctrl="if($event.ctrlKey) {$wire.selectImplementationRow({{ $key }}, '{{ encrypt($implementation->id) }}'); count = 0;}"
                                            @dblclick="if(!$event.ctrlKey) {$wire.viewImplementation({{ $key }}, '{{ encrypt($implementation->id) }}'); count = 0}"
                                            class="relative border-b duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'bg-gray-100 text-indigo-900 hover:bg-gray-50' : ' hover:bg-gray-50' }} whitespace-nowrap cursor-pointer">
                                            <td class="absolute h-full w-1 left-0"
                                                :class="{
                                                    'bg-indigo-700': {{ json_encode($selectedImplementationRow === $key) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $implementation->project_num }}
                                            </th>
                                            <td class="pr-6 py-2">
                                                {{ $implementation->project_title ?? '-' }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $implementation->total_slots }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                <span class="rounded-full px-2 py-1 text-xs font-semibold uppercase"
                                                    :class="{
                                                        'bg-amber-200 text-amber-800': {{ json_encode($implementation?->status === 'pending') }},
                                                        'bg-lime-200 text-lime-800': {{ json_encode($implementation?->status === 'signing') }},
                                                        'bg-indigo-200 text-indigo-800': {{ json_encode($implementation?->status === 'concluded') }},
                                                    }">
                                                    {{ $implementation->status }}
                                                </span>
                                            </td>
                                            <td class="py-1">

                                                {{-- View Button --}}
                                                <button type="button"
                                                    @click.stop="$wire.viewImplementation({{ $key }}, '{{ encrypt($implementation->id) }}');"
                                                    id="implementationRowButton-{{ $key }}"
                                                    aria-label="{{ __('View Project') }}"
                                                    class="flex items-center justify-center z-0 p-1 outline-none rounded duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">

                                                    {{-- View Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($this->implementations->count() > 5 && $loop->last)
                                            <tr x-data x-intersect.full.once="$wire.loadMoreImplementations();">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[36vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if (
                                    \Carbon\Carbon::parse($start)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultStart)->format('Y-m-d') ||
                                        \Carbon\Carbon::parse($end)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultEnd)->format('Y-m-d'))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No projects found.</p>
                                    <p>Maybe try adjusting the <span class=" text-indigo-700">date
                                            range</span>.
                                    </p>
                                @elseif (isset($searchProjects) && !empty($searchProjects))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No projects found.</p>
                                    <p>Try a different <span class=" text-indigo-700">search term</span>.</p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No projects found.</p>
                                    <p>Try creating a <span class=" text-indigo-700">new project</span>.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Create Project Modal --}}
                    <livewire:focal.implementations.create-project-modal />

                    {{-- View Project Modal --}}
                    <livewire:focal.implementations.view-project :$implementationId />

                </div>

                {{-- List of Batches --}}
                <div class="relative lg:col-span-2 size-full rounded bg-white shadow" x-data="{ assignBatchesModal: $wire.entangle('assignBatchesModal'), viewBatchModal: $wire.entangle('viewBatchModal') }">

                    {{-- Upper/Header --}}
                    <div class="relative flex justify-between max-h-12 items-center">
                        <div class="inline-flex items-center my-2 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="max-[460px]:hidden ms-2 font-bold text-sm sm:text-base">Batches</h1>
                        </div>
                        {{-- Assign Button --}}
                        <div class="relative mx-2 flex items-center">
                            @if (isset($this->remainingBatchSlots))
                                <p class="text-xs text-indigo-1100 capitalize font-light me-1">unallocated slots:</p>
                                <div
                                    class="{{ $this->remainingBatchSlots > 0 ? 'bg-amber-100 text-amber-700' : 'bg-green-200 text-green-900' }} rounded-md py-1 px-2 text-xs me-2">
                                    {{ $this->remainingBatchSlots }}</div>
                            @endif
                            <span class="relative" x-data="{ pop: false }">
                                <button @mouseleave="pop = false;" @mouseenter="pop = true;"
                                    @if (!$this->remainingBatchSlots) disabled 
                                @else
                                    @click="assignBatchesModal = !assignBatchesModal;" @endif
                                    class="flex items-center rounded-md px-3 py-1 text-sm font-bold duration-200 ease-in-out {{ $this->remainingBatchSlots > 0 ? 'bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500' : 'bg-indigo-300 text-indigo-50' }}">
                                    ASSIGN
                                    <svg class="size-4 ml-2" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M175.781 26.530 C 119.992 38.462,92.306 102.931,122.082 151.572 C 157.176 208.903,244.031 202.905,269.988 141.358 C 296.391 78.756,241.936 12.381,175.781 26.530 M107.813 191.177 C 85.230 195.102,68.383 210.260,61.975 232.422 C 59.986 239.301,59.428 318.137,61.292 328.937 C 65.057 350.758,80.886 368.049,102.049 373.462 C 107.795 374.931,110.968 375.000,173.282 375.000 L 238.502 375.000 229.212 365.425 C 219.425 355.339,216.440 350.863,214.479 343.332 C 205.443 308.642,247.642 282.676,274.554 306.365 L 278.297 309.660 291.520 296.252 C 306.255 281.311,310.725 278.355,321.367 276.518 L 326.718 275.594 326.363 256.352 C 325.910 231.742,323.949 224.404,314.486 211.897 C 303.479 197.348,289.113 191.080,266.681 191.040 L 253.285 191.016 250.200 193.359 C 248.504 194.648,244.688 197.549,241.722 199.806 C 212.635 221.931,168.906 220.569,140.934 196.668 C 134.265 190.970,133.021 190.608,120.533 190.731 C 114.611 190.790,108.887 190.991,107.813 191.177 M322.312 301.147 C 320.008 301.982,314.069 307.424,298.707 322.778 L 278.180 343.293 267.765 333.054 C 253.176 318.711,244.359 317.212,238.868 328.141 C 234.924 335.991,236.251 338.322,255.671 357.670 C 279.723 381.632,275.648 382.438,311.465 346.621 C 334.644 323.443,338.278 319.491,339.020 316.655 C 341.715 306.359,332.231 297.556,322.312 301.147 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>
                                {{-- Popover --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 bottom-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                    Assign Batches to Coordinators
                                </div>
                            </span>
                        </div>
                    </div>

                    @if ($this->implementationId && $this->batches->isNotEmpty())

                        {{-- Batches Table --}}
                        <div id="batches-table"
                            class="relative h-[36vh] overflow-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">

                            <table class="relative w-full text-sm text-left text-indigo-1100">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="absolute h-full w-1 left-0">
                                            {{-- Selected Row Indicator --}}
                                        </th>
                                        <th scope="col" class="ps-4 py-2">
                                            barangay / sector
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">
                                            slots
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">
                                            type
                                        </th>
                                        <th scope="col" class="px-2 py-2">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody x-data="{ count: 0 }" class="text-xs relative">
                                    @foreach ($this->batches as $key => $batch)
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:loading.class="pointer-events-none"
                                            wire:target="selectBatchRow, viewBatch" @click="count++;"
                                            @click.ctrl="if($event.ctrlKey) {$wire.selectBatchRow({{ $key }}, '{{ encrypt($batch->id) }}'); count = 0;}"
                                            @click.debounce.350ms="if(!$event.ctrlKey && count === 1) {$wire.selectBatchRow({{ $key }}, '{{ encrypt($batch->id) }}'); count = 0;}"
                                            @dblclick="if(!$event.ctrlKey) {$wire.viewBatch({{ $key }}, '{{ encrypt($batch->id) }}'); count = 0}"
                                            class="relative border-b whitespace-nowrap duration-200 ease-in-out cursor-pointer {{ $selectedBatchRow === $key ? 'bg-gray-100 text-indigo-900 hover:bg-gray-50' : ' hover:bg-gray-50' }}">
                                            <td class="absolute h-full w-1 left-0"
                                                :class="{
                                                    'bg-indigo-700': {{ json_encode($selectedBatchRow === $key) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            <th scope="row" class="ps-4 py-2 font-medium">
                                                @if ($batch->approval_status === 'approved')
                                                    <span class="flex items-center justify-start gap-1.5">
                                                        <span
                                                            class="flex items-center justify-center px-1.5 py-0.5 rounded font-semibold text-2xs 2xl:text-xs bg-green-300 text-green-1100">A</span>
                                                        {{ $batch->is_sectoral ? $batch->sector_title : $batch->barangay_name }}
                                                    </span>
                                                @elseif($batch->approval_status === 'pending')
                                                    <span class="flex items-center justify-start gap-1.5">
                                                        <span
                                                            class="flex items-center justify-center px-1.5 py-0.5 rounded font-semibold text-2xs 2xl:text-xs bg-amber-300 text-amber-950">P</span>
                                                        {{ $batch->is_sectoral ? $batch->sector_title : $batch->barangay_name }}
                                                    </span>
                                                @endif

                                            </th>
                                            <td class="px-2 py-2 text-center">
                                                {{ $batch->current_slots . ' / ' . $batch->slots_allocated }}
                                            </td>
                                            <td class="py-2 text-center">
                                                <span
                                                    class="px-3 py-1 text-xs rounded-full font-semibold uppercase 
                                                    {{ $batch->is_sectoral ? 'bg-rose-200 text-rose-900' : 'bg-emerald-200 text-emerald-900' }}">
                                                    {{ $batch->is_sectoral ? 'SECTORAL' : 'NON-SECTORAL' }}
                                                </span>
                                            </td>
                                            <td class="py-1 ps-2">

                                                {{-- View Button --}}
                                                <button
                                                    @click.stop="$wire.viewBatch({{ $key }}, '{{ encrypt($batch->id) }}');"
                                                    id="batchRowButton-{{ $key }}"
                                                    class="flex justify-center items-center z-0 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBatchRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">

                                                    {{-- View Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
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
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[36vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if (
                                    \Carbon\Carbon::parse($start)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultStart)->format('Y-m-d') ||
                                        \Carbon\Carbon::parse($end)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultEnd)->format('Y-m-d'))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No assignments found.</p>
                                    <p>Maybe try adjusting the <span class=" text-indigo-700">date
                                            range</span>.
                                    </p>
                                @elseif (isset($searchProjects) && !empty($searchProjects))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No assignments found.</p>
                                    <p>Try a different <span class=" text-indigo-700">search term</span> for the
                                        project.</p>
                                @elseif ($this->implementations->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try creating a <span class=" text-indigo-700">new project</span>.
                                    </p>
                                @elseif (!$implementationId)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class=" text-indigo-700">projects</span> row.
                                    </p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No assignments found.</p>
                                    <p>Try assigning a <span class=" text-indigo-700">new batch</span>.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif

                    {{-- Assign Batches Modal --}}
                    <livewire:focal.implementations.assign-batches-modal :$implementationId />

                    {{-- View Batch Modal --}}
                    <livewire:focal.implementations.view-batch :$batchId />
                </div>

                {{-- List of Beneficiaries --}}
                <div class="relative lg:col-span-5 size-full rounded bg-white shadow" x-data="{ addBeneficiariesModal: $wire.entangle('addBeneficiariesModal'), viewBeneficiaryModal: $wire.entangle('viewBeneficiaryModal'), importFileModal: $wire.entangle('importFileModal'), showExportModal: $wire.entangle('showExportModal') }">

                    {{-- Upper/Header --}}
                    <div class="relative flex items-center justify-center">
                        <div class="inline-flex items-center my-2 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="hidden lg:inline-block ms-2 font-bold">List of Beneficiaries</h1>
                            <h1 class="max-[500px]:hidden ms-2 font-bold text-sm lg:hidden">Beneficiaries</h1>

                            <span
                                class="{{ $batchId ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700' }} rounded px-2 py-1 ms-2 text-xs font-medium">
                                {{ $batchId ? ($this->beneficiarySlots['num_of_beneficiaries'] ?? 0) . ' / ' . $this->beneficiarySlots['batch_slots_allocated'] : 'N / A' }}</span>
                            @if ($batchId)
                                <h2 class="hidden lg:block ms-2 font-medium text-xs text-indigo-1100">Special Cases:
                                </h2><span
                                    class="{{ $this->specialCasesCount !== 0 ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-500' }} rounded px-2 py-1 ms-2 text-xs font-medium">
                                    {{ $this->specialCasesCount }}
                                </span>
                            @endif
                        </div>

                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="relative px-2 flex flex-1 items-center justify-end gap-2">

                            {{-- General Search Box --}}
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $this->beneficiarySlots['num_of_beneficiaries'] || $searchBeneficiaries ? 'text-indigo-800' : 'text-zinc-400' }}">

                                    {{-- Loading Icon --}}
                                    <svg class="size-3 animate-spin" wire:loading wire:target="searchBeneficiaries"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Search Icon --}}
                                    <svg class="size-3" wire:loading.remove wire:target="searchBeneficiaries"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>

                                {{-- Search Input Bar --}}
                                <input type="text" id="searchBeneficiaries" maxlength="100" autocomplete="off"
                                    @if (!$this->beneficiarySlots['num_of_beneficiaries'] && !$searchBeneficiaries) disabled @endif
                                    wire:model.live.debounce.300ms="searchBeneficiaries"
                                    class="{{ $this->beneficiarySlots['num_of_beneficiaries'] || $searchBeneficiaries
                                        ? 'selection:bg-indigo-700 selection:text-indigo-50 text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full"
                                    placeholder="Search for beneficiaries">
                            </div>

                            <span class="relative" x-data="{ pop: false }">

                                {{-- Import Button --}}
                                <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;"
                                    @if ($batchId && $this->beneficiarySlots['batch_slots_allocated'] > $this->beneficiarySlots['num_of_beneficiaries']) @click="importFileModal = !importFileModal;" @else disabled @endif
                                    class="flex items-center gap-2 disabled:bg-gray-300 disabled:text-gray-500 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500 rounded-md px-2 py-1 text-sm font-bold duration-200 ease-in-out">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M206.688 139.873 C 212.751 143.620,212.500 140.621,212.500 209.231 C 212.500 242.826,212.767 270.313,213.093 270.313 C 213.420 270.313,220.714 263.272,229.304 254.667 C 248.566 235.371,251.875 233.906,259.339 241.370 C 267.556 249.587,267.098 250.354,234.514 283.031 C 204.767 312.862,204.216 313.301,197.927 312.154 C 194.787 311.582,142.095 260.408,139.398 255.312 C 136.012 248.916,140.354 240.015,147.563 238.573 C 153.629 237.360,154.856 238.189,171.509 254.750 C 180.116 263.309,187.411 270.313,187.720 270.313 C 188.029 270.313,188.281 242.680,188.281 208.907 C 188.281 140.478,188.004 144.025,193.652 140.187 C 197.275 137.725,202.990 137.588,206.688 139.873 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>

                                {{-- Tooltip Content --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 bottom-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                    @if ($batchId && $this->beneficiarySlots['batch_slots_allocated'] > $this->beneficiarySlots['num_of_beneficiaries'])
                                        Import Beneficiaries from STIF File
                                    @else
                                        You may able to <span class="text-indigo-400">import</span> beneficiaries <br>
                                        when you select a batch or the batch is not full slotted
                                    @endif
                                </div>

                            </span>

                            <span class="relative" x-data="{ pop: false }">
                                {{-- Export Button --}}
                                <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;"
                                    @if ($batchId && $this->beneficiarySlots['num_of_beneficiaries'] > 0) wire:click="showExport" @else disabled @endif
                                    class="duration-200 ease-in-out flex items-center gap-2 justify-center px-2 py-1 rounded-md text-xs sm:text-sm font-bold outline-none disabled:bg-gray-300 disabled:text-gray-500 text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M205.223 139.115 C 208.456 140.341,259.848 191.840,261.742 195.752 C 266.646 205.882,255.514 216.701,245.595 211.446 C 244.365 210.794,236.504 203.379,228.125 194.967 L 212.891 179.672 212.500 242.123 C 212.115 303.671,212.086 304.605,210.499 306.731 C 204.772 314.399,195.433 314.184,190.039 306.258 L 188.281 303.675 188.281 241.528 L 188.281 179.380 172.461 195.051 C 160.663 206.736,155.883 210.967,153.660 211.688 C 144.244 214.742,135.529 205.084,139.108 195.559 C 139.978 193.241,188.052 144.418,193.281 140.540 C 196.591 138.086,201.092 137.549,205.223 139.115 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>

                                {{-- Tooltip Content --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 bottom-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                    @if ($batchId && $this->beneficiarySlots['num_of_beneficiaries'] > 0)
                                        Export Beneficiaries to Annex File
                                    @else
                                        You may able to <span class="text-indigo-400">export</span> an Annex <br>
                                        when there are beneficiaries on the batch
                                    @endif
                                </div>
                            </span>

                            <span class="relative" x-data="{ pop: false }">
                                {{-- Add Button --}}
                                <button type="button" @mouseleave="pop = false;" @mouseenter="pop = true;"
                                    @if ($batchId && $this->beneficiarySlots['batch_slots_allocated'] > $this->beneficiarySlots['num_of_beneficiaries']) @click="addBeneficiariesModal = !addBeneficiariesModal;" @else disabled @endif
                                    class="flex items-center gap-2 disabled:bg-gray-300 disabled:text-gray-500 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500 rounded-md px-2 py-1 text-sm font-bold duration-200 ease-in-out">

                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M181.716 13.755 C 102.990 27.972,72.357 125.909,128.773 183.020 C 181.183 236.074,272.696 214.609,295.333 143.952 C 318.606 71.310,256.583 0.235,181.716 13.755 M99.463 202.398 C 60.552 222.138,32.625 260.960,26.197 304.247 C 24.209 317.636,24.493 355.569,26.629 361.939 C 30.506 373.502,39.024 382.022,50.561 385.877 C 55.355 387.479,56.490 387.500,136.304 387.500 L 217.188 387.500 209.475 379.883 C 171.918 342.791,164.644 284.345,192.232 241.338 C 195.148 236.792,195.136 236.719,191.484 236.719 C 169.055 236.719,137.545 223.179,116.259 204.396 L 108.691 197.717 99.463 202.398 M269.531 213.993 C 176.853 234.489,177.153 366.574,269.922 386.007 C 337.328 400.126,393.434 333.977,369.538 268.559 C 355.185 229.265,310.563 204.918,269.531 213.993 M293.788 265.042 C 298.143 267.977,299.417 271.062,299.832 279.675 L 300.199 287.301 307.825 287.668 C 319.184 288.215,324.219 292.002,324.219 300.000 C 324.219 307.998,319.184 311.785,307.825 312.332 L 300.199 312.699 299.832 320.325 C 299.285 331.684,295.498 336.719,287.500 336.719 C 279.502 336.719,275.715 331.684,275.168 320.325 L 274.801 312.699 267.175 312.332 C 255.816 311.785,250.781 307.998,250.781 300.000 C 250.781 292.002,255.816 288.215,267.175 287.668 L 274.801 287.301 275.168 279.675 C 275.715 268.316,279.502 263.281,287.500 263.281 C 290.019 263.281,291.997 263.835,293.788 265.042 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>

                                {{-- Tooltip Content --}}
                                <div x-cloak x-show="pop" x-transition.opacity
                                    class="absolute z-50 bottom-full mb-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-indigo-50">
                                    @if ($batchId && $this->beneficiarySlots['batch_slots_allocated'] > $this->beneficiarySlots['num_of_beneficiaries'])
                                        Add Beneficiaries
                                    @else
                                        You may able to <span class="text-indigo-400">add</span> beneficiaries <br>
                                        when you select a batch or the batch is not full slotted
                                    @endif
                                </div>
                            </span>
                        </div>
                    </div>

                    @if ($batchId && !$this->beneficiaries->isEmpty())
                        {{-- Beneficiaries Table --}}
                        <div id="beneficiaries-table"
                            class="relative h-[38.5vh] overflow-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100">
                                <thead
                                    class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0 whitespace-nowrap">
                                    <tr>
                                        <th scope="col" class="absolute h-full w-1 left-0">
                                            {{-- Selected Row Indicator --}}
                                        </th>

                                        @if (count($selectedBeneficiaryRow) > 0)
                                            <th scope="col" class="ps-1 py-2 pe-2 text-center">
                                                {{-- Trash Bin/Delete Icon --}}
                                                <button type="button"
                                                    @if ($this->batch?->approval_status !== 'approved') @click="promptMultiDeleteModal = true;"
                                                    @else
                                                    disabled @endif
                                                    class="duration-200 ease-in-out flex shrink items-center justify-center p-0.5 rounded outline-none font-bold text-sm disabled:cursor-not-allowed disabled:bg-gray-300 disabled:text-gray-500 bg-white hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50 active:text-red-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </th>
                                        @else
                                            <th scope="col" class="px-4 py-2 text-center">
                                                #
                                            </th>
                                        @endif
                                        <th scope="col" class="px-2 py-2">
                                            first name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            middle name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            last name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            ext.
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            birthdate
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            contact #
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            type of id
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            id number
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            sex
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            civil status
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            age
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            senior citizen
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            pwd
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            occupation
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            avg monthly income
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            e-payment acc num
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            beneficiary type
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            dependent
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            interested in s.e
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            skills training
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse first name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse middle name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse last name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse ext. name
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody x-data="{ count: 0 }" class="text-xs">
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        <tr wire:key="beneficiary-{{ $key }}" {{-- @if ($this->nameCheck($beneficiary)[$key]['coEfficient'] * 100 > $duplicationThreshold) data-popover-target="beneficiary-pop-{{ $key }}"
                                            data-popover-trigger="hover" @endif --}}
                                            @click="count++"
                                            @click.ctrl="if($event.ctrlKey) {$wire.selectBeneficiaryRow({{ $key }}, '{{ encrypt($beneficiary->id) }}'); count = 0}"
                                            @click.debounce.350ms="if(!$event.ctrlKey && !$event.shiftKey && count === 1) {$wire.selectBeneficiaryRow({{ $key }}, '{{ encrypt($beneficiary->id) }}'); count = 0;}"
                                            @click.shift="if($event.shiftKey) {$wire.selectShiftBeneficiary({{ $key }}, '{{ encrypt($beneficiary->id) }}'); count = 0}"
                                            @dblclick="if(!$event.ctrlKey && !$event.shiftKey) {$wire.viewBeneficiary({{ $key }}, '{{ encrypt($beneficiary->id) }}'); count = 0}"
                                            class="relative border-b divide-x whitespace-nowrap cursor-pointer"
                                            :class="{
                                                'bg-gray-200 text-indigo-900 hover:bg-gray-300': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && in_array($key, $selectedBeneficiaryRow)) }},
                                                'hover:bg-gray-50': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && !in_array($key, $selectedBeneficiaryRow)) }},
                                                'bg-red-200 text-red-900 hover:bg-red-300': {{ json_encode($beneficiary->beneficiary_type === 'special case' && in_array($key, $selectedBeneficiaryRow)) }},
                                                'bg-red-100 text-red-700 hover:bg-red-200': {{ json_encode($beneficiary->beneficiary_type === 'special case' && !in_array($key, $selectedBeneficiaryRow)) }},
                                            }">
                                            <td class="absolute h-full w-1 left-0"
                                                :class="{
                                                    'bg-indigo-700': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && in_array($key, $selectedBeneficiaryRow)) }},
                                                    '': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && !in_array($key, $selectedBeneficiaryRow)) }},
                                                    'bg-red-700': {{ json_encode($beneficiary->beneficiary_type === 'special case' && in_array($key, $selectedBeneficiaryRow)) }},
                                                    '': {{ json_encode($beneficiary->beneficiary_type === 'special case' && !in_array($key, $selectedBeneficiaryRow)) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            @if (count($selectedBeneficiaryRow) > 0)
                                                <th scope="row" class="p-2 text-center">
                                                    <label tabindex="0" @click.stop
                                                        class="relative flex flex-1 items-center gap-1 rounded p-1 outline-none border-2 cursor-pointer"
                                                        :class="{
                                                            'bg-indigo-700 border-indigo-700 text-indigo-50': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && in_array($key, $selectedBeneficiaryRow)) }},
                                                            'bg-red-700 border-red-700 text-red-50': {{ json_encode($beneficiary->beneficiary_type === 'special case' && in_array($key, $selectedBeneficiaryRow)) }},
                                                            'border-zinc-300 text-transparent': {{ json_encode(!in_array($key, $selectedBeneficiaryRow)) }},
                                                        }"
                                                        for="check-beneficiary-{{ $key }}">

                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M362.500 56.340 C 352.317 58.043,357.949 52.810,246.679 163.959 L 143.749 266.778 96.679 219.844 C 44.257 167.573,46.207 169.193,34.480 168.209 C 8.309 166.015,-9.487 195.204,4.658 217.122 C 9.282 224.286,124.867 338.751,129.688 340.939 C 139.095 345.209,148.860 345.099,158.506 340.613 C 166.723 336.791,393.119 110.272,397.035 101.953 C 408.174 78.291,388.288 52.026,362.500 56.340 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>

                                                        <input id="check-beneficiary-{{ $key }}"
                                                            type="checkbox" tabindex="-1" value={{ $key }}
                                                            wire:click.prevent="selectBeneficiaryRow({{ $key }}, '{{ encrypt($beneficiary->id) }}', 'checkbox')"
                                                            class="absolute hidden inset-0">
                                                    </label>
                                                </th>
                                            @else
                                                <th scope="row" class="px-4 py-2 font-medium text-center">
                                                    {{ $key + 1 }}
                                                </th>
                                            @endif
                                            <td class="px-2 ">
                                                {{ $beneficiary->first_name }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->middle_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->last_name }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->extension_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ \Carbon\Carbon::parse($beneficiary->birthdate)->format('M d, Y') }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->contact_num }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->type_of_id }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->id_number }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->sex }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->civil_status }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->age }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->is_senior_citizen }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->is_pwd }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->occupation ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ !is_null($beneficiary->avg_monthly_income) || intval($beneficiary->avg_monthly_income) !== 0 ? '₱' . \App\Services\MoneyFormat::mask(intval($beneficiary->avg_monthly_income)) : '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->e_payment_acc_num ?? '-' }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->beneficiary_type }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->dependent ?? '-' }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->self_employment }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->skills_training ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->spouse_first_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->spouse_middle_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->spouse_last_name ?? '-' }}
                                            </td>
                                            <td class="px-2">
                                                {{ $beneficiary->spouse_extension_name ?? '-' }}
                                            </td>
                                            <td class="py-1">

                                                {{-- View Button --}}
                                                <button type="button"
                                                    @click.stop="$wire.viewBeneficiary({{ $key }}, '{{ encrypt($beneficiary->id) }}');"
                                                    id="beneficiaryRowButton-{{ $key }}"
                                                    class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out"
                                                    :class="{
                                                        'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && in_array($key, $selectedBeneficiaryRow)) }},
                                                        'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && !in_array($key, $selectedBeneficiaryRow)) }},
                                                        'hover:bg-red-700 focus:bg-red-700 text-red-900 hover:text-red-50 focus:text-red-50': {{ json_encode($beneficiary->beneficiary_type === 'special case' && in_array($key, $selectedBeneficiaryRow)) }},
                                                        'text-red-700 hover:text-red-900 focus:text-red-900 hover:bg-red-300 focus:bg-red-300': {{ json_encode($beneficiary->beneficiary_type === 'special case' && !in_array($key, $selectedBeneficiaryRow)) }},
                                                    
                                                    }">

                                                    {{-- View Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if (isset($this->beneficiaries) && count($this->beneficiaries) > 5 && $loop->last)
                                            <tr x-data x-intersect.full.once="$wire.loadMoreBeneficiaries()">
                                            </tr>
                                        @endif

                                        {{--
                                        @if ($this->nameCheck($beneficiary)[$key]['coEfficient'] * 100 > $duplicationThreshold)
                                            <div data-popover id="value" role="tooltip"
                                                class="absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                    <p>
                                                    <div class="flex items-center gap-2"><span
                                                            class="p-1.5 bg-red-500"></span>
                                                        <span>Exactly the same as input</span>
                                                    </div>
                                                    <div class="flex items-center gap-2"><span
                                                            class="p-1.5 bg-amber-500"></span>
                                                        <span>Not the
                                                            same as input</span>
                                                    </div>
                                                    </p>
                                                </div>
                                                <div data-popper-arrow></div>
                                            </div>
                                        @endif
                                        --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[38.5vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if (
                                    \Carbon\Carbon::parse($start)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultStart)->format('Y-m-d') ||
                                        \Carbon\Carbon::parse($end)->format('Y-m-d') !== \Carbon\Carbon::parse($defaultEnd)->format('Y-m-d'))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Maybe try adjusting the <span class=" text-indigo-700">date
                                            range</span>.
                                    </p>
                                @elseif (isset($searchProjects) && !empty($searchProjects))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try a different <span class=" text-indigo-700">search term</span> for the
                                        project.</p>
                                @elseif ($this->implementations->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try creating a <span class=" text-indigo-700">new project</span>.
                                    </p>
                                @elseif (!$implementationId)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class=" text-indigo-700">projects</span> row.
                                    </p>
                                @elseif ($this->batches->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try assigning a <span class=" text-indigo-700">new batch</span>.
                                    </p>
                                @elseif (!$batchId)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class=" text-indigo-700">batches</span> row.
                                    </p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try adding a <span class=" text-indigo-700">new beneficiary</span>.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif

                    {{-- Add Beneficiaries Modal --}}
                    <livewire:focal.implementations.add-beneficiaries-modal :$batchId />

                    {{-- View Beneficiaries Modal --}}
                    <livewire:focal.implementations.view-beneficiary :$beneficiaryId />

                    {{-- Import File Modal --}}
                    <livewire:focal.implementations.import-file-modal :$batchId />

                    {{-- Export Summary Modal --}}
                    <div x-cloak @keydown.escape.window="$wire.resetExport(); showExportModal = false;">
                        <!-- Modal Backdrop -->
                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                            x-show="showExportModal">
                        </div>

                        <!-- Modal -->
                        <div x-show="showExportModal" x-trap.noautofocus.noscroll="showExportModal"
                            class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none">

                            {{-- The Modal --}}
                            <div class="flex items-center justify-center w-full max-w-3xl">
                                <div class="relative w-full bg-white rounded-md shadow">
                                    <!-- Modal Header -->
                                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                                            Export Annex
                                        </h1>

                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Loading State --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-6 text-indigo-900 animate-spin" wire:loading
                                                wire:target="exportType, exportTypeCsv, exportAnnex, showExport, exportFormat, defaultExportStart, defaultExportEnd, selectExportBatchRow"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Close Button --}}
                                            <button type="button"
                                                @click="$wire.resetExport(); showExportModal = false;"
                                                class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                                    </div>

                                    <hr class="">

                                    {{-- Modal body --}}
                                    <div
                                        class="w-full flex flex-col items-center justify-center gap-4 pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">

                                        {{-- Annex Type --}}
                                        <div x-data="exporting" class="relative w-full flex items-center gap-2">
                                            <span class="text-sm font-medium whitespace-nowrap">Annex Type:</span>

                                            {{-- For XLSX --}}
                                            <div x-show="exportFormat === 'xlsx'" class="flex flex-col gap-2">
                                                <span class="flex items-center flex-wrap gap-2">
                                                    {{-- Annex E-1 (COS) --}}
                                                    <label for="annex_e1"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': e1_x,
                                                            'bg-gray-200 text-gray-500': !e1_x,
                                                        }">
                                                        Annex E-1 (COS)
                                                        <input type="checkbox" class="hidden absolute inset-0"
                                                            id="annex_e1" wire:model.live="exportType.annex_e1">
                                                    </label>
                                                    {{-- Annex E-2 (COS - Co-Partner) --}}
                                                    <label for="annex_e2"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': e2_x,
                                                            'bg-gray-200 text-gray-500': !e2_x,
                                                        }">
                                                        Annex E-2 (COS - Co-Partner)
                                                        <input type="checkbox" class="hidden absolute inset-0"
                                                            id="annex_e2" wire:model.live="exportType.annex_e2">
                                                    </label>
                                                    {{-- Annex J-2 (Attendance Sheet) --}}
                                                    <label for="annex_j2"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': j2_x,
                                                            'bg-gray-200 text-gray-500': !j2_x,
                                                        }">
                                                        Annex J-2 (Attendance Sheet)
                                                        <input type="checkbox" class="hidden absolute inset-0"
                                                            id="annex_j2" wire:model.live="exportType.annex_j2">
                                                    </label>
                                                </span>
                                                <span class="flex items-center flex-wrap gap-2">
                                                    {{-- Annex L (Payroll) --}}
                                                    <label for="annex_l"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': l_x,
                                                            'bg-gray-200 text-gray-500': !l_x,
                                                        }">
                                                        Annex L (Payroll)
                                                        <input type="checkbox" class="hidden absolute inset-0"
                                                            id="annex_l" wire:model.live="exportType.annex_l">
                                                    </label>
                                                    {{-- Annex L (Payroll w/ Sign) --}}
                                                    <label for="annex_l_sign"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': l_sign_x,
                                                            'bg-gray-200 text-gray-500': !l_sign_x,
                                                        }">
                                                        Annex L (Payroll w/ Sign)
                                                        <input type="checkbox" class="hidden absolute inset-0"
                                                            id="annex_l_sign"
                                                            wire:model.live="exportType.annex_l_sign">
                                                    </label>
                                                </span>
                                            </div>

                                            {{-- For CSV --}}
                                            <div x-show="exportFormat === 'csv'" class="flex flex-col gap-2">
                                                <span class="flex items-center flex-wrap gap-2">
                                                    {{-- Annex E-1 (COS) --}}
                                                    <label for="annex_e1_csv"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': csv_type === 'annex_e1',
                                                            'bg-gray-200 text-gray-500': csv_type !== 'annex_e1',
                                                        }">
                                                        Annex E-1 (COS)
                                                        <input type="radio" class="hidden absolute inset-0"
                                                            id="annex_e1_csv" value="annex_e1"
                                                            wire:model.live="exportTypeCsv">
                                                    </label>
                                                    {{-- Annex E-2 (COS - Co-Partner) --}}
                                                    <label for="annex_e2_csv"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': csv_type === 'annex_e2',
                                                            'bg-gray-200 text-gray-500': csv_type !== 'annex_e2',
                                                        }">
                                                        Annex E-2 (COS - Co-Partner)
                                                        <input type="radio" class="hidden absolute inset-0"
                                                            id="annex_e2_csv" value="annex_e2"
                                                            wire:model.live="exportTypeCsv">
                                                    </label>
                                                    {{-- Annex J-2 (Attendance Sheet) --}}
                                                    <label for="annex_j2_csv"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': csv_type === 'annex_j2',
                                                            'bg-gray-200 text-gray-500': csv_type !== 'annex_j2',
                                                        }">
                                                        Annex J-2 (Attendance Sheet)
                                                        <input type="radio" class="hidden absolute inset-0"
                                                            id="annex_j2_csv" value="annex_j2"
                                                            wire:model.live="exportTypeCsv">
                                                    </label>
                                                </span>
                                                <span class="flex items-center flex-wrap gap-2">
                                                    {{-- Annex L (Payroll) --}}
                                                    <label for="annex_l_csv"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': csv_type === 'annex_l',
                                                            'bg-gray-200 text-gray-500': csv_type !== 'annex_l',
                                                        }">
                                                        Annex L (Payroll)
                                                        <input type="radio" class="hidden absolute inset-0"
                                                            id="annex_l_csv" value="annex_l"
                                                            wire:model.live="exportTypeCsv">
                                                    </label>
                                                    {{-- Annex L (Payroll w/ Sign) --}}
                                                    <label for="annex_l_sign_csv"
                                                        class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                        :class="{
                                                            'bg-indigo-700 text-indigo-50': csv_type === 'annex_l_sign',
                                                            'bg-gray-200 text-gray-500': csv_type !== 'annex_l_sign',
                                                        }">
                                                        Annex L (Payroll w/ Sign)
                                                        <input type="radio" class="hidden absolute inset-0"
                                                            id="annex_l_sign_csv" value="annex_l_sign"
                                                            wire:model.live="exportTypeCsv">
                                                    </label>
                                                </span>
                                            </div>


                                        </div>

                                        {{-- File Format --}}
                                        <div x-data="exporting" class="relative w-full flex items-center gap-2">
                                            <span class="text-sm font-medium">File Format:</span>
                                            {{-- XLSX --}}
                                            <label for="xlsx-radio"
                                                class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                :class="{
                                                    'bg-indigo-700 text-indigo-50': exportFormat === 'xlsx',
                                                    'bg-gray-200 text-gray-500': exportFormat !== 'xlsx',
                                                }">
                                                XLSX
                                                <input type="radio" class="hidden absolute inset-0" id="xlsx-radio"
                                                    value="xlsx" wire:model.live="exportFormat">
                                            </label>
                                            {{-- CSV --}}
                                            <label for="csv-radio"
                                                class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                :class="{
                                                    'bg-indigo-700 text-indigo-50': exportFormat === 'csv',
                                                    'bg-gray-200 text-gray-500': exportFormat !== 'csv',
                                                }">
                                                CSV
                                                <input type="radio" class="hidden absolute inset-0" id="csv-radio"
                                                    value="csv" wire:model.live="exportFormat">
                                            </label>
                                        </div>

                                        <hr class="my-2">

                                        {{-- Body --}}
                                        <div class="w-full flex flex-col justify-center gap-4">
                                            {{-- Date Range --}}
                                            <div id="export-date-range" datepicker-orientation="top" date-rangepicker
                                                datepicker-autohide
                                                class="flex items-center gap-1 sm:gap-2 pb-4 text-xs text-indigo-1100">

                                                {{-- Start --}}
                                                <span class="text-sm font-medium">Filter Date:</span>
                                                <div class="relative">
                                                    <span
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
                                                    </span>
                                                    <input id="export-start-date" name="start" type="text"
                                                        readonly wire:model.change="defaultExportStart"
                                                        @change-date.camel="$wire.$set('defaultExportStart', $el.value);"
                                                        value="{{ $defaultExportStart }}"
                                                        class="border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500 text-xs rounded w-40 py-1.5 ps-7 sm:ps-8"
                                                        placeholder="Select date start">
                                                </div>

                                                <span class="text-sm">-></span>

                                                {{-- End --}}
                                                <div class="relative">
                                                    <span
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
                                                    </span>
                                                    <input id="export-end-date" name="end" type="text"
                                                        readonly wire:model.change="defaultExportEnd"
                                                        @change-date.camel="$wire.$set('defaultExportEnd', $el.value);"
                                                        value="{{ $defaultExportEnd }}"
                                                        class="border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500 text-xs rounded w-40 py-1.5 ps-7 sm:ps-8"
                                                        placeholder="Select date end">
                                                </div>
                                            </div>

                                            {{-- Batches Dropdown --}}
                                            <div class="flex items-center w-full gap-2">
                                                <h2 class="text-sm font-medium">
                                                    Choose Batch:
                                                </h2>

                                                {{-- Batches Dropdown --}}
                                                <div x-data="{ show: false, currentBatch: $wire.entangle('currentExportBatch') }" class="relative z-30">

                                                    {{-- Button --}}
                                                    <button type="button" @click="show = !show;"
                                                        class="flex items-center justify-between w-64 sm:w-96 gap-2 border-2 outline-none text-xs font-semibold px-2 py-1 rounded
                                                        disabled:bg-gray-50 disabled:text-gray-500 disabled:border-gray-300 
                                                        bg-indigo-100 hover:bg-indigo-800 active:bg-indigo-900 
                                                        text-indigo-700 hover:text-indigo-50 active:text-indigo-50
                                                        border-indigo-700 hover:border-transparent active:border-transparent duration-200 ease-in-out">

                                                        <span x-text="currentBatch"></span>

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor" class="size-3 rotate-180">
                                                            <path fill-rule="evenodd"
                                                                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>

                                                    {{-- Content --}}
                                                    <div x-show="show"
                                                        @click.away="show = false; if(show == true) { $wire.set('searchExportBatch', null);} "
                                                        class="absolute -left-20 sm:inset-x-0 bottom-full p-3 mb-2 max-w-96 text-indigo-1100 bg-white shadow-lg border border-indigo-100 rounded text-xs">

                                                        {{-- Batches Count | Search Bar --}}
                                                        <div class="flex items-center w-full gap-2">
                                                            {{-- Batches Count --}}
                                                            <span
                                                                class="flex items-center gap-2 rounded {{ $this->exportBatches->isNotEmpty() ? 'text-indigo-900 bg-indigo-100' : 'text-red-900 bg-red-100' }} py-1.5 px-2 text-xs select-none">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                                {{ count($this->exportBatches) }}
                                                            </span>

                                                            {{-- Search Bar --}}
                                                            <div
                                                                class="relative flex flex-1 items-center justify-center py-1 text-indigo-700">

                                                                {{-- Icons --}}
                                                                <div
                                                                    class="absolute flex items-center justify-center left-2">
                                                                    {{-- Loading State --}}
                                                                    <svg class="size-4 animate-spin duration-200 ease-in-out pointer-events-none"
                                                                        wire:loading wire:target="searchExportBatch"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12"
                                                                            cy="12" r="10"
                                                                            stroke="currentColor" stroke-width="4">
                                                                        </circle>
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                        </path>
                                                                    </svg>

                                                                    {{-- Search Icon --}}
                                                                    <svg class="size-4 duration-200 ease-in-out pointer-events-none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" wire:loading.remove
                                                                        wire:target="searchExportBatch"
                                                                        fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>

                                                                {{-- Search Bar --}}
                                                                <input id="searchExportBatch"
                                                                    wire:model.live.debounce.350ms="searchExportBatch"
                                                                    type="text" autocomplete="off"
                                                                    class="rounded w-full ps-8 py-1.5 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                                    placeholder="Search batch number">
                                                            </div>
                                                        </div>

                                                        {{-- Batches List --}}
                                                        <div
                                                            class="mt-2 text-xs overflow-y-auto h-40 scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                                            @if ($this->exportBatches->isNotEmpty())
                                                                @foreach ($this->exportBatches as $key => $batch)
                                                                    <span
                                                                        class="relative flex items-center justify-between gap-2"
                                                                        wire:key={{ $key }}>

                                                                        {{-- Type of Batch --}}
                                                                        <span
                                                                            class="sticky flex items-center justify-center font-semibold p-1 rounded {{ $batch->is_sectoral ? 'bg-rose-200 text-rose-900' : 'bg-emerald-200 text-emerald-900' }}">
                                                                            {{ $batch->is_sectoral ? 'ST' : 'NS' }}
                                                                        </span>

                                                                        {{-- Row Button --}}
                                                                        <button type="button"
                                                                            wire:click="selectExportBatchRow('{{ encrypt($batch->id) }}')"
                                                                            @click="show= !show; currentBatch = '{{ ($batch->sector_title ?? $batch->barangay_name) . ' / ' . $batch->batch_num }}'"
                                                                            wire:loading.attr="disabled"
                                                                            aria-label="{{ __('Batch') }}"
                                                                            class="text-left outline-none w-full whitespace-nowrap overflow-x-auto scrollbar-none select-text flex items-center gap-2 ps-1 pe-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 focus:text-indigo-900 focus:bg-indigo-100 duration-200 ease-in-out">

                                                                            {{ ($batch->sector_title ?? $batch->barangay_name) . ' / ' . $batch->batch_num }}

                                                                        </button>

                                                                        {{-- Statuses --}}
                                                                        <span class="sticky flex items-center gap-2">
                                                                            {{-- Approval Status --}}
                                                                            @if ($batch->approval_status === 'approved')
                                                                                <span
                                                                                    class="bg-green-300 text-green-1000 rounded px-1.5 py-0.5 uppercase font-semibold">{{ substr($batch->approval_status, 0, 1) }}</span>
                                                                            @elseif($batch->approval_status === 'pending')
                                                                                <span
                                                                                    class="bg-amber-300 text-amber-900 rounded px-1.5 py-0.5 uppercase font-semibold">{{ substr($batch->approval_status, 0, 1) }}</span>
                                                                            @endif

                                                                            {{-- Submission Status --}}
                                                                            @if ($batch->submission_status === 'unopened')
                                                                                <span
                                                                                    class="bg-amber-200 text-amber-900 rounded px-1.5 py-0.5 uppercase font-semibold">{{ substr($batch->submission_status, 0, 1) }}</span>
                                                                            @elseif($batch->submission_status === 'encoding')
                                                                                <span
                                                                                    class="bg-sky-200 text-sky-900 rounded px-1.5 py-0.5 uppercase font-semibold">{{ substr($batch->submission_status, 0, 1) }}</span>
                                                                            @elseif($batch->submission_status === 'submitted')
                                                                                <span
                                                                                    class="bg-green-200 text-green-1000 rounded px-1.5 py-0.5 uppercase font-semibold">{{ substr($batch->submission_status, 0, 1) }}</span>
                                                                            @elseif($batch->submission_status === 'revalidate')
                                                                                <span
                                                                                    class="bg-red-200 text-red-900 rounded px-1.5 py-0.5 uppercase font-semibold">{{ substr($batch->submission_status, 0, 1) }}</span>
                                                                            @endif
                                                                        </span>
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <div
                                                                    class="flex flex-col flex-1 items-center justify-center size-full text-sm border border-gray-300 bg-gray-100 text-gray-500 rounded p-2">
                                                                    @if (isset($searchExportBatch) && !empty($searchExportBatch))
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="size-12 mb-4 text-indigo-900 opacity-65"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                            width="400" height="400"
                                                                            viewBox="0, 0, 400,400">
                                                                            <g>
                                                                                <path
                                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                                    stroke="none"
                                                                                    fill="currentColor"
                                                                                    fill-rule="evenodd"></path>
                                                                            </g>
                                                                        </svg>
                                                                        <p>No batches found.</p>
                                                                        <p>Maybe try a different <span
                                                                                class=" text-indigo-900">search
                                                                                term</span>?
                                                                        </p>
                                                                    @elseif ($calendarStart !== $defaultExportStart || $calendarEnd !== $defaultExportEnd)
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="size-12 mb-4 text-indigo-900 opacity-65"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                            width="400" height="400"
                                                                            viewBox="0, 0, 400,400">
                                                                            <g>
                                                                                <path
                                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                                    stroke="none"
                                                                                    fill="currentColor"
                                                                                    fill-rule="evenodd"></path>
                                                                            </g>
                                                                        </svg>
                                                                        <p>No batches found.</p>
                                                                        <p>Try adjusting the <span
                                                                                class=" text-indigo-900">filter
                                                                                date</span>.
                                                                        </p>
                                                                    @else
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="size-12 mb-4 text-indigo-900 opacity-65"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                            width="400" height="400"
                                                                            viewBox="0, 0, 400,400">
                                                                            <g>
                                                                                <path
                                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                                    stroke="none"
                                                                                    fill="currentColor"
                                                                                    fill-rule="evenodd"></path>
                                                                            </g>
                                                                        </svg>
                                                                        <p>No batches found.</p>
                                                                        <p>Try assigning a <span
                                                                                class="text-indigo-900">
                                                                                a new batch</span>.
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- Confirmation --}}
                                            <div class="flex items-center justify-end w-full gap-4">

                                                {{-- Confirm Button --}}
                                                <button type="button"
                                                    @if (
                                                        $this->exportBatches->isNotEmpty() &&
                                                            ((in_array(true, $exportType, true) === true && $exportFormat === 'xlsx') || $exportFormat === 'csv')) wire:click="exportAnnex"
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

            {{-- Modals --}}
            <livewire:focal.implementations.prompt-multi-delete-modal :$beneficiaryIds />
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
    }" x-effect="setupTimeouts()"
        class="fixed left-6 bottom-6 z-50 flex flex-col gap-y-3">
        {{-- Loop through alerts --}}
        <template x-for="alert in alerts" :key="alert.id">
            <div x-show="show" x-data="{ show: false }" x-init="$nextTick(() => { show = true });"
                x-transition:enter="transition ease-in-out duration-300 origin-left"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="flex items-center gap-2 border rounded-lg text-sm sm:text-md font-bold p-3 select-none"
                :class="`bg-${alert.color}-200 text-${alert.color}-900 border-${alert.color}-500`" role="alert">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current size-4">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>
                <p x-text="alert.message"></p>
                <button type="button" @click="removeAlert(alert.id)" class="p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                        viewBox="0, 0, 400,400">
                        <g>
                            <path
                                d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                stroke="none" fill="currentColor" fill-rule="evenodd">
                            </path>
                        </g>
                    </svg>
                </button>
            </div>
        </template>
    </div>
</div>

@script
    <script>
        $wire.on('scroll-top-implementations', () => {
            const implementations = document.getElementById('implementations-table');
            if (implementations) {
                implementations.scrollTo({
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

        $wire.on('scroll-top-beneficiaries', () => {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

        });

        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });

        Alpine.data('exporting', () => ({
            exportFormat: $wire.entangle('exportFormat'),
            csv_type: $wire.entangle('exportTypeCsv'),
            e1_x: $wire.entangle('exportType.annex_e1'),
            e2_x: $wire.entangle('exportType.annex_e2'),
            j2_x: $wire.entangle('exportType.annex_j2'),
            l_x: $wire.entangle('exportType.annex_l'),
            l_sign_x: $wire.entangle('exportType.annex_l_sign'),
        }));
    </script>
@endscript
