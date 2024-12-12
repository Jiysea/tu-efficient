<x-slot:favicons>
    <x-c-favicons />
</x-slot>

<div x-cloak x-data="{
    open: true,
    isAboveBreakpoint: true,
    isMobile: window.innerWidth < 768,
    addBeneficiariesModal: $wire.entangle('addBeneficiariesModal'),
    editBeneficiaryModal: $wire.entangle('editBeneficiaryModal'),
    deleteBeneficiaryModal: $wire.entangle('deleteBeneficiaryModal'),
    viewCredentialsModal: $wire.entangle('viewCredentialsModal'),
    approveSubmissionModal: $wire.entangle('approveSubmissionModal'),
    importFileModal: $wire.entangle('importFileModal'),
    showExportModal: $wire.entangle('showExportModal'),
}" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});
window.addEventListener('resize', () => {
    isMobile = window.innerWidth < 768;
    $wire.$dispatchSelf('init-reload');
});">

    {{-- <livewire:coordinator.submissions.import-file-modal :$batchId />
    <livewire:coordinator.submissions.download-options-alert /> --}}

    <div :class="{
        'md:ml-20': !open,
        'md:ml-20 xl:ml-64': open,
    }"
        class="md:ml-20 xl:ml-64 duration-500 ease-in-out">
        <div x-data="{}" class="p-2 min-h-screen select-none">

            {{-- Submissions Header --}}
            <div class="relative flex flex-col lg:flex-row items-center lg:justify-between my-2 gap-2">

                {{-- Page Name | Date Range | Loading Icon --}}
                <div class="relative flex items-center justify-between gap-2 w-full lg:w-auto">
                    <div class="flex items-center gap-2">
                        <livewire:sidebar.coordinator-bar />

                        <h1 class="text-xl font-semibold sm:font-bold xl:ms-2">Submissions</h1>

                        {{-- Date range picker --}}
                        <template x-if="!isMobile">
                            <div id="submissions-date-range" date-rangepicker datepicker-autohide
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
                            wire:target="calendarStart, calendarEnd, showExport, selectBatchRow, selectBeneficiaryRow, applyFilter, deleteBeneficiary, loadMoreBeneficiaries, approveSubmission"
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
                                wire:target="calendarStart, calendarEnd, showExport, selectBatchRow, selectBeneficiaryRow, applyFilter, deleteBeneficiary, loadMoreBeneficiaries, approveSubmission"
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

                                <div id="submissions-date-range" date-rangepicker datepicker-autohide
                                    class="flex items-center gap-1 sm:gap-2 text-xs">

                                    {{-- Start --}}
                                    <div class="relative">
                                        <div
                                            class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </div>
                                        <input type="text" readonly id="start-date"
                                            @change-date.camel="$wire.$set('calendarStart', $el.value);  show = false;"
                                            wire:model.change="calendarStart" name="start"
                                            value="{{ $calendarStart }}"
                                            class="cursor-pointer selection:bg-white bg-white border border-blue-300 text-xs text-blue-1100 rounded focus:ring-blue-500 focus:border-blue-500 w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                            placeholder="Select date start">
                                    </div>

                                    <span class="text-blue-1100">-></span>

                                    {{-- End --}}
                                    <div class="relative">
                                        <div
                                            class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
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

                {{-- Buttons on Top --}}
                <div class="flex items-center justify-end gap-2 w-full lg:w-auto">

                    {{-- Import Button --}}
                    <button type="button"
                        @if (
                            $batchId &&
                                $this->beneficiarySlots['num_of_beneficiaries'] < $this->beneficiarySlots['slots_allocated'] &&
                                $this->batch->approval_status !== 'approved') @click="importFileModal = !importFileModal;" @else disabled @endif
                        class="duration-200 ease-in-out flex items-center gap-2 justify-center px-3 py-1.5 rounded-md text-xs sm:text-sm font-bold outline-none disabled:bg-gray-300 disabled:text-gray-500 text-blue-50 bg-blue-700 hover:bg-blue-800 active:bg-blue-900">
                        IMPORT
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M206.688 139.873 C 212.751 143.620,212.500 140.621,212.500 209.231 C 212.500 242.826,212.767 270.313,213.093 270.313 C 213.420 270.313,220.714 263.272,229.304 254.667 C 248.566 235.371,251.875 233.906,259.339 241.370 C 267.556 249.587,267.098 250.354,234.514 283.031 C 204.767 312.862,204.216 313.301,197.927 312.154 C 194.787 311.582,142.095 260.408,139.398 255.312 C 136.012 248.916,140.354 240.015,147.563 238.573 C 153.629 237.360,154.856 238.189,171.509 254.750 C 180.116 263.309,187.411 270.313,187.720 270.313 C 188.029 270.313,188.281 242.680,188.281 208.907 C 188.281 140.478,188.004 144.025,193.652 140.187 C 197.275 137.725,202.990 137.588,206.688 139.873 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>

                    {{-- Export Button --}}
                    <button type="button"
                        @if ($batchId && $this->beneficiarySlots['num_of_beneficiaries'] > 0) wire:click="showExport" @else disabled @endif
                        class="duration-200 ease-in-out flex items-center gap-2 justify-center px-3 py-1.5 rounded-md text-xs sm:text-sm font-bold outline-none disabled:bg-gray-300 disabled:text-gray-500 text-blue-50 bg-blue-700 hover:bg-blue-800 active:bg-blue-900">
                        EXPORT
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M205.223 139.115 C 208.456 140.341,259.848 191.840,261.742 195.752 C 266.646 205.882,255.514 216.701,245.595 211.446 C 244.365 210.794,236.504 203.379,228.125 194.967 L 212.891 179.672 212.500 242.123 C 212.115 303.671,212.086 304.605,210.499 306.731 C 204.772 314.399,195.433 314.184,190.039 306.258 L 188.281 303.675 188.281 241.528 L 188.281 179.380 172.461 195.051 C 160.663 206.736,155.883 210.967,153.660 211.688 C 144.244 214.742,135.529 205.084,139.108 195.559 C 139.978 193.241,188.052 144.418,193.281 140.540 C 196.591 138.086,201.092 137.549,205.223 139.115 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>

                    {{-- Approve Button --}}
                    <button type="button"
                        @if (
                            $batchId &&
                                $this->beneficiarySlots['num_of_beneficiaries'] > 0 &&
                                $this->batch->approval_status !== 'approved' &&
                                ($this->batch->submission_status === 'submitted' || $this->batch->submission_status === 'unopened')) @click="approveSubmissionModal = !approveSubmissionModal;" @else disabled @endif
                        class="duration-200 ease-in-out flex items-center gap-2 justify-center px-3 py-1.5 rounded-md text-xs sm:text-sm font-bold outline-none disabled:bg-gray-300 disabled:text-gray-500 text-green-50 bg-green-700 hover:bg-green-800 active:bg-green-900">
                        MARK AS APPROVED
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M137.109 10.047 C 133.498 12.278,133.085 12.900,118.359 38.281 C 110.756 51.387,103.954 62.773,103.244 63.584 C 101.102 66.032,98.377 66.763,69.208 72.721 C 29.037 80.927,32.121 76.705,36.747 117.164 L 40.117 146.643 38.613 149.447 C 37.786 150.989,29.551 160.945,20.313 171.570 C -1.134 196.237,-0.001 194.653,0.005 199.956 C 0.012 205.405,-0.940 204.053,20.313 228.783 C 42.665 254.792,40.780 248.504,36.717 283.517 L 33.373 312.333 35.069 315.836 C 37.636 321.138,39.974 321.941,71.094 328.205 C 88.604 331.729,99.746 334.339,101.318 335.286 C 103.236 336.441,107.128 342.475,118.286 361.594 C 139.465 397.882,134.865 396.377,172.120 379.207 C 193.699 369.262,199.044 367.084,201.052 367.419 C 202.407 367.645,215.005 373.135,229.047 379.618 C 256.453 392.272,257.984 392.729,263.175 389.807 C 266.571 387.896,265.949 388.829,282.403 360.938 C 296.460 337.110,296.990 336.322,300.037 334.747 C 301.133 334.179,314.318 331.194,329.336 328.113 C 360.255 321.769,362.419 321.025,364.904 315.891 L 366.621 312.345 363.242 283.130 C 359.179 248.009,356.970 255.116,380.425 227.846 C 400.999 203.926,400.000 205.356,400.000 199.835 C 400.000 194.669,401.311 196.493,379.259 170.984 C 367.961 157.915,360.854 149.053,360.546 147.652 C 360.273 146.409,361.508 132.837,363.291 117.492 C 368.012 76.864,370.898 80.847,330.828 72.704 C 295.882 65.602,299.043 67.302,288.874 50.133 C 263.273 6.909,265.096 9.395,258.555 8.767 C 255.095 8.434,253.072 9.228,228.374 20.611 C 213.813 27.322,201.045 32.812,200.000 32.812 C 198.955 32.812,186.276 27.363,171.825 20.703 C 143.808 7.790,141.774 7.166,137.109 10.047 M263.898 134.317 C 267.899 136.394,280.140 148.972,281.609 152.514 C 284.818 160.258,286.345 158.412,230.198 214.699 C 177.047 267.983,177.929 267.188,172.031 267.188 C 166.758 267.188,165.803 266.391,140.499 240.906 C 112.554 212.760,112.472 212.537,125.282 199.322 C 140.564 183.557,142.852 183.723,160.931 201.903 L 172.253 213.288 211.322 174.301 C 256.275 129.442,255.558 129.987,263.898 134.317 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Content --}}
            <div class="relative grid grid-cols-1 size-full gap-4 lg:grid-cols-7">

                {{-- List of Beneficiaries --}}
                <div class="relative lg:col-span-3 h-[89vh] size-full rounded bg-white shadow">

                    {{-- Table Header --}}
                    <div class="flex flex-col gap-2 p-2">

                        {{-- 1st Row --}}
                        <div class="flex items-center justify-end">

                            {{-- Title --}}
                            <div class="flex flex-1 items-center gap-2 text-blue-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M96.875 42.643 C 52.219 54.424,52.561 118.254,97.341 129.707 C 111.583 133.349,116.540 131.561,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.616 67.550,148.905 66.535,145.219 60.791 C 135.687 45.938,114.514 37.989,96.875 42.643 M280.938 42.600 C 270.752 45.179,260.204 52.464,254.763 60.678 C 251.061 66.267,251.383 67.401,258.836 75.011 C 272.214 88.670,280.835 105.931,282.526 122.444 C 283.253 129.539,284.941 131.255,291.175 131.236 C 330.920 131.117,351.409 84.551,324.504 55.491 C 313.789 43.917,296.242 38.725,280.938 42.600 M189.063 75.494 C 134.926 85.627,123.780 159.908,172.566 185.433 C 216.250 208.290,267.190 170.135,257.471 121.839 C 251.236 90.860,220.007 69.703,189.063 75.494 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M283.058 149.743 C 282.139 150.542,280.658 153.753,279.696 157.031 C 276.119 169.218,270.328 179.314,261.225 189.234 C 253.482 197.670,254.234 200.382,265.191 203.537 C 288.694 210.306,307.108 223.950,319.474 243.758 C 324.516 251.833,323.991 251.565,334.706 251.543 C 362.465 251.487,376.780 236.149,375.520 207.813 C 374.261 179.527,360.172 159.904,334.766 151.051 C 326.406 148.137,286.076 147.117,283.058 149.743 M150.663 223.858 C 119.731 229.560,95.455 253.370,88.566 284.766 C 80.747 320.396,94.564 350.121,122.338 357.418 C 129.294 359.246,270.706 359.246,277.662 357.418 C 300.848 351.327,312.868 333.574,312.837 305.469 C 312.790 264.161,291.822 235.385,254.043 224.786 C 246.270 222.606,161.583 221.845,150.663 223.858 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <h1 class="hidden sm:inline lg:hidden 2xl:inline font-bold text-base">
                                    Beneficiaries</h1>

                                {{-- Beneficiary Count --}}
                                @if ($this->batches->isNotEmpty())
                                    <span class="rounded px-2 py-1 text-xs font-medium"
                                        :class="{
                                            'bg-green-200 text-green-900': {{ json_encode($this->beneficiarySlots['num_of_beneficiaries'] === $this->beneficiarySlots['slots_allocated']) }},
                                            'bg-amber-100 text-amber-700': {{ json_encode($this->beneficiarySlots['num_of_beneficiaries'] >= 0 && $this->beneficiarySlots['num_of_beneficiaries'] < $this->beneficiarySlots['slots_allocated']) }},
                                        }">
                                        {{ $this->beneficiarySlots['num_of_beneficiaries'] . ' / ' . $this->beneficiarySlots['slots_allocated'] }}</span>
                                @endif
                            </div>

                            {{-- Batches Dropdown --}}
                            <div class="flex items-center gap-2">
                                <div x-data="{ open: false }" class="relative flex items-center gap-2 text-blue-900">

                                    {{-- Button --}}
                                    <button type="button" @click="open = !open;"
                                        class="{{ $this->batches->isNotEmpty() ? 'bg-blue-50 border-blue-700 hover:border-transparent hover:bg-blue-800 active:bg-blue-900 text-blue-700 hover:text-blue-50 active:text-blue-50' : 'border-gray-300 text-gray-500 hover:bg-gray-500 active:bg-gray-600 hover:text-gray-50 active:text-gray-50' }} flex items-center gap-2 py-1 px-2 text-sm outline-none font-semibold rounded border duration-200 ease-in-out">
                                        {{ $this->currentBatch }}
                                        @if ($this->batches->isNotEmpty())
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @endif
                                    </button>

                                    {{-- Panel --}}
                                    <div id="batchDropdownContent" x-cloak x-show="open"
                                        @click.away="$wire.resetFilter(); $wire.set('searchBatches', null); open = false;"
                                        :class="{
                                            'block': open === true,
                                            'hidden': open === false,
                                        }"
                                        class="absolute top-full right-0 mt-2 z-50 p-2 w-[20.5rem] bg-white border rounded shadow">

                                        {{-- Header / Search Batches / Counter / Filter --}}
                                        <div class="mb-2 flex w-full items-center justify-center gap-2">

                                            {{-- Batches Count --}}
                                            <span
                                                class="flex items-center gap-2 rounded {{ $this->batches->isNotEmpty() ? 'text-blue-900 bg-blue-100' : 'text-red-900 bg-red-100' }} py-1.5 px-2 text-xs select-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                {{ $this->batchesCount }}
                                            </span>

                                            {{-- Search Box --}}
                                            <label for="searchBatches"
                                                class="relative flex flex-1 items-center justify-center duration-200 ease-in-out rounded border box-border focus:ring-0 outline-none
                                                {{ $this->batches->isEmpty() && (!isset($searchBatches) || empty($searchBatches)) ? 'text-gray-500 border-gray-300' : 'border-blue-300 hover:border-blue-500 focus-within:border-blue-500 text-blue-500 hover:text-blue-700 focus-within:text-blue-700 hover:bg-blue-50 focus-within:bg-blue-50' }}">

                                                <div
                                                    class="absolute start-2 flex items-center justify-center pointer-events-none">
                                                    {{-- Loading Icon --}}
                                                    <svg class="size-3 animate-spin" wire:loading
                                                        wire:target="searchBatches" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>

                                                    {{-- Search Icon --}}
                                                    <svg class="size-3" wire:loading.remove
                                                        wire:target="searchBatches" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>

                                                <input id="searchBatches" autofocus autocomplete="off"
                                                    wire:model.live.debounce.300ms="searchBatches" type="text"
                                                    class="peer bg-transparent outline-none border-none focus:ring-0 rounded w-full py-1.5 ps-6 text-xs disabled:placeholder-gray-300 selection:text-blue-100 selection:bg-blue-700 text-blue-1100 placeholder-blue-500 hover:placeholder-blue-700 focus:placeholder-blue-700"
                                                    placeholder="Search batches"
                                                    @if ($this->batches->isEmpty() && (!isset($searchBatches) || empty($searchBatches))) disabled @endif>
                                            </label>

                                            {{-- Filter Button --}}
                                            <div x-data="{ open: false }" class="relative">

                                                <!-- Button -->
                                                <button x-ref="button" @click="open = !open" :aria-expanded="open"
                                                    type="button"
                                                    class="flex items-center outline-none rounded p-1.5 text-sm font-bold duration-200 ease-in-out bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:bg-blue-700 focus:text-blue-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M55.859 51.091 C 37.210 57.030,26.929 76.899,32.690 95.866 C 35.051 103.642,34.376 102.847,97.852 172.610 L 156.250 236.794 156.253 298.670 C 156.256 359.035,156.294 360.609,157.808 363.093 C 161.323 368.857,170.292 370.737,175.953 366.895 C 184.355 361.193,241.520 314.546,242.553 312.549 C 243.578 310.566,243.750 304.971,243.750 273.514 L 243.750 236.794 302.148 172.610 C 365.624 102.847,364.949 103.642,367.310 95.866 C 372.533 78.673,364.634 60.468,348.673 52.908 L 343.359 50.391 201.172 50.243 C 87.833 50.126,58.350 50.298,55.859 51.091 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>

                                                <!-- Panel -->
                                                <div x-show="open" @click.outside="open = false;"
                                                    x-trap.inert.noautofocus.noscroll="open"
                                                    class="absolute flex flex-col flex-1 gap-4 text-xs right-0 mt-2 p-4 z-50 rounded shadow-lg border bg-white text-blue-1100 border-gray-300">

                                                    {{-- Approval Status --}}
                                                    <div class="whitespace-nowrap">
                                                        <h2 class="text-sm font-medium mb-1">
                                                            Filter for Approval Status
                                                        </h2>
                                                        <div x-data="{ approved: $wire.entangle('approvalStatuses.approved'), pending: $wire.entangle('approvalStatuses.pending'), }" class="flex items-center gap-3">

                                                            <label tabindex="0"
                                                                @keydown.enter.self="$refs.approved.click()"
                                                                class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                                :class="{
                                                                    'bg-blue-100 text-blue-700 focus:outline-blue-300': approved,
                                                                    'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                                        approved,
                                                                }"
                                                                for="approvedStatus">
                                                                <input id="approvedStatus" type="checkbox"
                                                                    x-ref="approved" tabindex="-1"
                                                                    wire:model="approvalStatuses.approved"
                                                                    class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                                    :class="{
                                                                        'border-blue-300 text-blue-700': approved,
                                                                        'border-gray-300': !approved,
                                                                    }">
                                                                Approved
                                                            </label>

                                                            <label tabindex="0"
                                                                @keydown.enter.self="$refs.pending.click()"
                                                                class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                                :class="{
                                                                    'bg-blue-100 text-blue-700 focus:outline-blue-300': pending,
                                                                    'bg-gray-50 text-gray-700 focus:outline-gray-300': !
                                                                        pending,
                                                                }"
                                                                for="pendingStatus">
                                                                <input id="pendingStatus" type="checkbox"
                                                                    x-ref="pending" tabindex="-1"
                                                                    wire:model="approvalStatuses.pending"
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
                                                        <div x-data="{ submitted: $wire.entangle('submissionStatuses.submitted'), encoding: $wire.entangle('submissionStatuses.encoding'), unopened: $wire.entangle('submissionStatuses.unopened'), revalidate: $wire.entangle('submissionStatuses.revalidate') }"
                                                            class="flex flex-col justify-center gap-2">
                                                            <div class="flex items-center gap-3">

                                                                <label tabindex="0"
                                                                    @keydown.enter.self="$refs.submitted.click()"
                                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                                    :class="{
                                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': submitted,
                                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300':
                                                                            !
                                                                            submitted,
                                                                    }"
                                                                    for="submittedStatus">
                                                                    <input id="submittedStatus" type="checkbox"
                                                                        x-ref="submitted" tabindex="-1"
                                                                        wire:model="submissionStatuses.submitted"
                                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                                        :class="{
                                                                            'border-blue-300 text-blue-700': submitted,
                                                                            'border-gray-300': !submitted,
                                                                        }">
                                                                    Submitted
                                                                </label>

                                                                <label tabindex="0"
                                                                    @keydown.enter.self="$refs.encoding.click()"
                                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                                    :class="{
                                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': encoding,
                                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300':
                                                                            !
                                                                            encoding,
                                                                    }"
                                                                    for="encodingStatus">
                                                                    <input id="encodingStatus" type="checkbox"
                                                                        x-ref="encoding" tabindex="-1"
                                                                        wire:model="submissionStatuses.encoding"
                                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                                        :class="{
                                                                            'border-blue-300 text-blue-700': encoding,
                                                                            'border-gray-300': !encoding,
                                                                        }">
                                                                    Encoding
                                                                </label>

                                                            </div>
                                                            <div class="flex items-center gap-3">

                                                                <label tabindex="0"
                                                                    @keydown.enter.self="$refs.unopened.click()"
                                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                                    :class="{
                                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': unopened,
                                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300':
                                                                            !
                                                                            unopened,
                                                                    }"
                                                                    for="unopenedStatus">
                                                                    <input id="unopenedStatus" type="checkbox"
                                                                        x-ref="unopened" tabindex="-1"
                                                                        wire:model="submissionStatuses.unopened"
                                                                        class="size-3 rounded outline-none focus:ring-transparent focus:ring-offset-transparent appearance-none"
                                                                        :class="{
                                                                            'border-blue-300 text-blue-700': unopened,
                                                                            'border-gray-300': !unopened,
                                                                        }">
                                                                    Unopened
                                                                </label>


                                                                <label tabindex="0"
                                                                    @keydown.enter.self="$refs.revalidate.click()"
                                                                    class="flex flex-1 items-center gap-1 rounded px-2 py-1 focus:outline-1"
                                                                    :class="{
                                                                        'bg-blue-100 text-blue-700 focus:outline-blue-300': revalidate,
                                                                        'bg-gray-50 text-gray-700 focus:outline-gray-300':
                                                                            !
                                                                            revalidate,
                                                                    }"
                                                                    for="revalidateStatus">
                                                                    <input id="revalidateStatus" type="checkbox"
                                                                        x-ref="revalidate" tabindex="-1"
                                                                        wire:model="submissionStatuses.revalidate"
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

                                        {{-- List of Batches --}}
                                        <ul class="px-2 text-sm text-blue-1100 overflow-y-auto h-48 scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700"
                                            aria-labelledby="batchButton">
                                            @if ($this->batches->isNotEmpty())
                                                @foreach ($this->batches as $key => $batch)
                                                    <li wire:key="batch-{{ $key }}">
                                                        <button type="button"
                                                            @click="open = !open; $wire.dispatchSelf('scroll-top-beneficiaries');"
                                                            wire:loading.class="pointer-events-none"
                                                            wire:click="selectBatchRow({{ $key }}, '{{ encrypt($batch->id) }}')"
                                                            class="flex items-center gap-2 w-full px-1 py-2 text-xs hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out cursor-pointer">
                                                            <span
                                                                class="font-medium rounded px-1.5 py-0.5 uppercase {{ $batch->is_sectoral ? 'bg-rose-200 text-rose-800' : 'bg-emerald-200 text-emerald-800' }}">
                                                                {{ $batch->is_sectoral ? 'ST' : 'NS' }}
                                                            </span>

                                                            <span class="text-left">{{ $batch->batch_num }} /
                                                                {{ $batch->barangay_name ?? $batch->sector_title }}
                                                            </span>

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

                                                        </button>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li
                                                    class="flex flex-col items-center justify-center h-full w-full font-medium border rounded bg-gray-50 border-gray-300 text-gray-500">
                                                    @if (isset($searchBatches) && !empty($searchBatches))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-12 mb-4 text-blue-900 opacity-65"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>
                                                        <p>No batches found.</p>
                                                        <p>Maybe try a different <span class=" text-blue-900">search
                                                                term</span>?
                                                        </p>
                                                    @elseif (in_array(false,
                                                            array_values(array_unique(array_merge($this->filter['approval_status'], $this->filter['submission_status']),
                                                                    SORT_REGULAR))))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-12 mb-4 text-blue-900 opacity-65"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>
                                                        <p>No batches found.</p>
                                                        <p>Try adjusting the <span
                                                                class=" text-blue-900">filters</span>.
                                                        </p>
                                                    @elseif ($start !== $defaultStart || $end !== $defaultEnd)
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-12 mb-4 text-blue-900 opacity-65"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>
                                                        <p>No batches found.</p>
                                                        <p>Try adjusting the <span class=" text-blue-900">date
                                                                range</span>.
                                                        </p>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-12 mb-4 text-blue-900 opacity-65"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd"></path>
                                                            </g>
                                                        </svg>
                                                        <p>No batches found.</p>
                                                        <p>Ask your focal to <span class=" text-blue-900">assign
                                                                a batch</span> for you.
                                                        </p>
                                                    @endif
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- 2nd Row --}}
                        <div class="flex items-center justify-between w-full text-xs">

                            @if ($this->batch)
                                {{-- Barangay or Sector --}}
                                <div class="flex items-center gap-1">

                                    <span class="text-blue-1100">
                                        {{ $this->batch?->is_sectoral ? 'Sector:' : 'Barangay:' }}
                                    </span>

                                    <span
                                        class="font-medium rounded px-2 py-1 {{ $this->batch?->is_sectoral ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $this->batch?->is_sectoral ? $this->batch?->sector_title : $this->batch?->barangay_name }}
                                    </span>

                                    <span
                                        class="font-medium rounded p-1 uppercase {{ $this->batch?->is_sectoral ? 'bg-rose-200 text-rose-900' : 'bg-emerald-200 text-emerald-900' }}">
                                        {{ $this->batch?->is_sectoral ? 'ST' : 'NS' }}
                                    </span>

                                </div>

                                {{-- Approval && Submission Statuses  --}}
                                <div class="flex items-center gap-1">
                                    <span class="font-semibold rounded px-2 py-1 uppercase"
                                        :class="{
                                            'bg-green-300 text-green-1000': {{ json_encode($this->batch?->approval_status === 'approved') }},
                                            'bg-amber-300 text-amber-950': {{ json_encode($this->batch?->approval_status === 'pending') }},
                                        }">
                                        {{ $this->batch?->approval_status }}
                                    </span>

                                    <span class="font-semibold rounded px-2 py-1 uppercase"
                                        :class="{
                                            'bg-green-200 text-green-1000': {{ json_encode($this->batch?->submission_status === 'submitted') }},
                                            'bg-amber-200 text-amber-950': {{ json_encode($this->batch?->submission_status === 'unopened') }},
                                            'bg-blue-200 text-blue-950': {{ json_encode($this->batch?->submission_status === 'encoding') }},
                                            'bg-red-200 text-red-950': {{ json_encode($this->batch?->submission_status === 'revalidate') }},
                                        }">
                                        {{ $this->batch?->submission_status }}
                                    </span>
                                </div>
                            @else
                                <div
                                    class="flex items-center justify-center font-medium rounded px-2 py-1 text-zinc-700 bg-zinc-100">
                                    No Batch Available
                                </div>
                            @endif
                        </div>

                        {{-- 3rd Row --}}
                        <div class="flex flex-1 w-full items-center">

                            {{-- Special Cases Count | Search | Add --}}
                            <div class="flex flex-1 w-full items-center gap-2">

                                {{-- Special Cases --}}
                                <div class="relative flex items-center gap-1 text-xs font-medium">
                                    <p class="{{ $this->specialCases > 0 ? 'text-red-900' : 'text-gray-700' }}">
                                        Special Cases: </p>
                                    <span
                                        class="py-1 px-2 rounded {{ $this->specialCases > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }} ">{{ $this->specialCases }}</span>
                                </div>

                                {{-- Search Box --}}
                                <label for="searchBeneficiaries"
                                    class="relative flex flex-1 items-center justify-center duration-200 ease-in-out rounded border box-border focus:ring-0 outline-none
                                                {{ $this->checkBeneficiaryCount <= 0 ? 'text-gray-500 border-gray-300' : 'border-blue-300 hover:border-blue-500 focus-within:border-blue-500 text-blue-500 hover:text-blue-700 focus-within:text-blue-700 hover:bg-blue-50 focus-within:bg-blue-50' }}">

                                    <div class="absolute start-2 flex items-center justify-center pointer-events-none">
                                        {{-- Loading Icon --}}
                                        <svg class="size-4 animate-spin" wire:loading
                                            wire:target="searchBeneficiaries" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>

                                        {{-- Search Icon --}}
                                        <svg class="size-4" wire:loading.remove wire:target="searchBeneficiaries"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <input id="searchBeneficiaries" autocomplete="off"
                                        wire:model.live.debounce.300ms="searchBeneficiaries" type="text"
                                        class="peer bg-transparent outline-none border-none focus:ring-0 rounded w-full py-1.5 ps-8 text-xs disabled:placeholder-gray-300 selection:text-blue-100 selection:bg-blue-700 text-blue-1100 placeholder-blue-500 hover:placeholder-blue-700 focus:placeholder-blue-700"
                                        placeholder="Search for beneficiaries"
                                        @if ($this->checkBeneficiaryCount <= 0) disabled @endif>
                                </label>

                                {{-- Add Button --}}
                                <button
                                    @if (
                                        $batchId &&
                                            $this->beneficiarySlots['slots_allocated'] > $this->beneficiarySlots['num_of_beneficiaries'] &&
                                            $this->batch->approval_status !== 'approved') @click="addBeneficiariesModal = !addBeneficiariesModal;" @else disabled @endif
                                    class="flex items-center gap-2 disabled:bg-gray-300 disabled:text-gray-500 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500 rounded px-4 py-1 text-sm font-bold duration-200 ease-in-out">
                                    ADD
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
                            </div>
                        </div>
                    </div>

                    {{-- Beneficiaries Table --}}
                    @if ($this->beneficiaries->isNotEmpty())
                        <div id="beneficiaries-table"
                            class="relative h-[72.9vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            <table class="relative w-full text-sm text-left text-blue-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-blue-50 uppercase bg-blue-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="absolute h-full w-1 left-0">
                                            {{-- Selected Row Indicator --}}
                                        </th>
                                        <th scope="col" class="ps-4 pe-2 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="ps-2">
                                            full name
                                        </th>
                                        <th scope="col" class="ps-2 text-center">
                                            sex
                                        </th>
                                        <th scope="col" class="ps-2 text-center">
                                            birthdate
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:loading.class="pointer-events-none"
                                            wire:click.prevent='selectBeneficiaryRow({{ $key }}, "{{ encrypt($beneficiary->id) }}")'
                                            class="relative border-b whitespace-nowrap duration-200 ease-in-out cursor-pointer"
                                            :class="{
                                                'bg-gray-100 hover:bg-gray-200 text-blue-1000 hover:text-blue-900': {{ json_encode($beneficiary->beneficiary_type !== 'special case' && $selectedBeneficiaryRow === $key) }},
                                                'hover:bg-gray-50': {{ json_encode($beneficiary->beneficiary_type !== 'special case' && $selectedBeneficiaryRow !== $key) }},
                                                'bg-red-200 text-red-900 hover:bg-red-300': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow === $key) }},
                                                'bg-red-100 text-red-700 hover:bg-red-200': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow !== $key) }},
                                            }">
                                            <td class="absolute h-full w-1 left-0"
                                                :class="{
                                                    'bg-blue-700': {{ json_encode($beneficiary->beneficiary_type !== 'special case' && $selectedBeneficiaryRow === $key) }},
                                                    '': {{ json_encode($beneficiary->beneficiary_type !== 'special case' && $selectedBeneficiaryRow !== $key) }},
                                                    'bg-red-700': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow === $key) }},
                                                    '': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow !== $key) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            <th scope="row" class="ps-4 pe-2 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="p-2">
                                                {{ $this->full_last_first($beneficiary) }}

                                            </td>
                                            <td class="p-2 text-center uppercase">
                                                {{ $beneficiary->sex }}
                                            </td>
                                            <td class="p-2 text-center">
                                                {{ $beneficiary->birthdate }}
                                            </td>
                                        </tr>
                                        @if (count($this->beneficiaries) >= 15 && $loop->last)
                                            <tr x-data x-intersect.once="$wire.loadMoreBeneficiaries()">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative h-[72.9vh] bg-white px-4 pb-4 pt-2 min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if (isset($searchBeneficiaries) && !empty($searchBeneficiaries))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try a different <span class=" text-blue-900">search term</span>.</p>
                                @elseif ($this->batches->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p class="text-center">Maybe clear your <span class="text-blue-900">
                                            batch</span> search and filters <br>
                                        or <span class="text-blue-900">ask your focal</span>
                                        for any batch assignments?</p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try adding a <span class=" text-blue-900">new
                                            beneficiary</span>.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Add Beneficiaries Modal --}}
                    <livewire:coordinator.submissions.add-beneficiaries-modal :$batchId />
                </div>

                {{-- Beneficiary Preview --}}
                <div class="relative flex flex-col lg:col-span-4 h-[89vh] w-full rounded bg-white shadow">

                    <div
                        class="flex-1 text-xs overflow-y-auto scrollbar-thin scrollbar-thumb-blue-700 scrollbar-track-blue-50">

                        @if ($beneficiaryId)
                            {{-- Whole Thing --}}
                            <div class="grid grid-cols-11 gap-2 p-4">

                                {{-- Left Side --}}
                                <div
                                    class="hidden sm:flex flex-col items-center col-span-full sm:col-span-3 order-2 sm:order-none text-blue-1100 gap-2">

                                    {{-- Identity Information --}}
                                    <div class="flex flex-col items-center text-blue-1100">

                                        {{-- ID Image --}}
                                        <div
                                            class="flex flex-col items-center justify-center bg-gray-50 text-gray-400 border-gray-300 border rounded mb-2 size-20 aspect-square">

                                            @if ($this->identity)
                                                <button tabindex="-1"
                                                    class="relative flex items-center justify-center rounded size-20 aspect-square outline-none"
                                                    wire:click="viewCredential('identity')">
                                                    <img class="size-[90%] object-contain"
                                                        src="{{ route('credentials.show', ['filename' => $this->identity]) }}">

                                                    <div class="absolute bg-black opacity-10 rounded size-full flex items-center justify-center"
                                                        wire:loading wire:target="viewCredential('identity')">
                                                        {{-- Darkness... --}}
                                                    </div>

                                                    <svg class="absolute flex items-center justify-center m-0 size-6 text-blue-900 animate-spin z-10"
                                                        wire:loading wire:target="viewCredential('identity')"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-[50%]"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M32.422 11.304 C 31.992 11.457,30.680 11.794,29.507 12.052 C 24.028 13.260,19.531 19.766,19.531 26.487 C 19.531 32.602,20.505 34.096,32.052 45.703 L 42.932 56.641 34.864 64.939 C 15.117 85.248,8.104 102.091,3.189 141.016 C -3.142 191.153,0.379 261.277,10.675 290.108 C 22.673 323.703,54.885 351.747,88.994 358.293 C 140.763 368.227,235.891 369.061,300.224 360.143 C 314.334 358.187,325.014 355.166,333.980 350.595 L 337.882 348.606 356.803 367.237 C 377.405 387.523,378.751 388.534,385.156 388.534 C 396.064 388.534,402.926 378.158,399.161 367.358 C 398.216 364.648,45.323 14.908,41.621 13.013 C 39.365 11.859,33.779 10.821,32.422 11.304 M173.685 26.603 C 149.478 27.530,105.181 31.289,103.940 32.521 C 103.744 32.716,109.721 38.980,117.221 46.441 L 130.859 60.008 143.750 58.937 C 190.711 55.035,239.415 56.114,289.049 62.156 C 323.242 66.318,344.750 80.309,357.596 106.748 C 367.951 128.058,373.239 201.260,367.335 241.563 L 366.797 245.235 356.492 231.797 C 310.216 171.453,298.664 162.344,271.006 164.387 C 260.988 165.127,245.312 170.115,245.313 172.562 C 245.313 173.401,380.320 307.031,381.167 307.031 C 382.090 307.031,388.660 292.643,390.518 286.555 C 403.517 243.958,402.683 139.537,389.046 102.170 C 377.740 71.192,349.876 45.280,318.284 36.368 C 294.697 29.713,221.504 24.771,173.685 26.603 M88.547 101.394 L 98.578 111.490 94.406 113.848 C 74.760 124.952,71.359 153.827,87.859 169.432 C 104.033 184.729,130.241 181.325,141.915 162.410 L 144.731 157.848 146.780 159.342 C 147.906 160.164,161.448 173.480,176.871 188.934 L 204.915 217.032 200.234 222.774 C 194.483 229.829,171.825 260.177,171.304 261.523 C 170.623 263.286,169.872 262.595,162.828 253.726 C 153.432 241.895,140.224 226.635,137.217 224.134 C 126.063 214.861,107.616 213.280,93.162 220.358 C 85.033 224.339,70.072 241.107,47.047 272.044 L 40.234 281.197 39.314 279.023 C 32.914 263.906,28.466 201.412,31.263 165.934 C 34.978 118.821,40.622 102.197,58.912 84.488 L 64.848 78.741 71.682 85.019 C 75.440 88.472,83.030 95.841,88.547 101.394 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Type of ID --}}
                                        <p
                                            class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 font-semibold select-text text-center">
                                            {{ $this->getIdType }}
                                        </p>

                                        {{-- ID Number --}}
                                        <p
                                            class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 text-center select-text">
                                            {{ $this->beneficiary?->id_number }}
                                        </p>
                                    </div>

                                    {{-- Address Information --}}
                                    <div class="flex flex-col w-full text-blue-1100 gap-1">

                                        {{-- Header --}}
                                        <p
                                            class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                            address</p>

                                        {{-- Body --}}
                                        <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                                            {{-- Province --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    province </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->province }}</span>
                                            </div>

                                            {{-- City/Municipality --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    city / municipality </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->city_municipality }}</span>
                                            </div>

                                            {{-- District --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    district </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->district }}</span>
                                            </div>

                                            {{-- Barangay --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    barangay </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->barangay_name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Spouse Information --}}
                                    <div class="flex flex-col w-full text-blue-1100 gap-1">

                                        {{-- Header --}}
                                        <p
                                            class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                            spouse info</p>

                                        {{-- Body --}}
                                        <div class="flex flex-1 flex-col px-2 py-1 gap-2">

                                            {{-- Spouse First Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    first name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->spouse_first_name ?? '-' }}</span>
                                            </div>

                                            {{-- Spouse Middle Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    middle name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->spouse_middle_name ?? '-' }}</span>
                                            </div>

                                            {{-- Spouse Last Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    last name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->spouse_last_name ?? '-' }}</span>
                                            </div>

                                            {{-- Spouse Extension Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    ext. name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->spouse_extension_name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Identity Information --}}
                                <div
                                    class="sm:hidden flex flex-col items-center col-span-full sm:col-span-3 order-1 sm:order-none text-blue-1100">

                                    {{-- ID Image --}}
                                    <div
                                        class="flex flex-col items-center justify-center bg-gray-50 text-gray-400 border-gray-300 border rounded mb-2 size-20 aspect-square">

                                        @if ($this->identity)
                                            <button tabindex="-1"
                                                class="relative flex items-center justify-center rounded size-20 aspect-square outline-none"
                                                wire:click="viewCredential('identity')">
                                                <img class="size-[90%] object-contain"
                                                    src="{{ route('credentials.show', ['filename' => $this->identity]) }}">

                                                <div class="absolute bg-black opacity-10 rounded size-full flex items-center justify-center"
                                                    wire:loading wire:target="viewCredential('identity')">
                                                    {{-- Darkness... --}}
                                                </div>

                                                <svg class="absolute flex items-center justify-center m-0 size-6 text-blue-900 animate-spin z-10"
                                                    wire:loading wire:target="viewCredential('identity')"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </button>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-[50%]"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M32.422 11.304 C 31.992 11.457,30.680 11.794,29.507 12.052 C 24.028 13.260,19.531 19.766,19.531 26.487 C 19.531 32.602,20.505 34.096,32.052 45.703 L 42.932 56.641 34.864 64.939 C 15.117 85.248,8.104 102.091,3.189 141.016 C -3.142 191.153,0.379 261.277,10.675 290.108 C 22.673 323.703,54.885 351.747,88.994 358.293 C 140.763 368.227,235.891 369.061,300.224 360.143 C 314.334 358.187,325.014 355.166,333.980 350.595 L 337.882 348.606 356.803 367.237 C 377.405 387.523,378.751 388.534,385.156 388.534 C 396.064 388.534,402.926 378.158,399.161 367.358 C 398.216 364.648,45.323 14.908,41.621 13.013 C 39.365 11.859,33.779 10.821,32.422 11.304 M173.685 26.603 C 149.478 27.530,105.181 31.289,103.940 32.521 C 103.744 32.716,109.721 38.980,117.221 46.441 L 130.859 60.008 143.750 58.937 C 190.711 55.035,239.415 56.114,289.049 62.156 C 323.242 66.318,344.750 80.309,357.596 106.748 C 367.951 128.058,373.239 201.260,367.335 241.563 L 366.797 245.235 356.492 231.797 C 310.216 171.453,298.664 162.344,271.006 164.387 C 260.988 165.127,245.312 170.115,245.313 172.562 C 245.313 173.401,380.320 307.031,381.167 307.031 C 382.090 307.031,388.660 292.643,390.518 286.555 C 403.517 243.958,402.683 139.537,389.046 102.170 C 377.740 71.192,349.876 45.280,318.284 36.368 C 294.697 29.713,221.504 24.771,173.685 26.603 M88.547 101.394 L 98.578 111.490 94.406 113.848 C 74.760 124.952,71.359 153.827,87.859 169.432 C 104.033 184.729,130.241 181.325,141.915 162.410 L 144.731 157.848 146.780 159.342 C 147.906 160.164,161.448 173.480,176.871 188.934 L 204.915 217.032 200.234 222.774 C 194.483 229.829,171.825 260.177,171.304 261.523 C 170.623 263.286,169.872 262.595,162.828 253.726 C 153.432 241.895,140.224 226.635,137.217 224.134 C 126.063 214.861,107.616 213.280,93.162 220.358 C 85.033 224.339,70.072 241.107,47.047 272.044 L 40.234 281.197 39.314 279.023 C 32.914 263.906,28.466 201.412,31.263 165.934 C 34.978 118.821,40.622 102.197,58.912 84.488 L 64.848 78.741 71.682 85.019 C 75.440 88.472,83.030 95.841,88.547 101.394 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        @endif
                                    </div>

                                    {{-- Type of ID --}}
                                    <p
                                        class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 font-semibold select-text text-center">
                                        {{ $this->getIdType }}
                                    </p>

                                    {{-- ID Number --}}
                                    <p
                                        class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 text-center select-text">
                                        {{ $this->beneficiary?->id_number }}
                                    </p>
                                </div>

                                {{-- Address Information --}}
                                <div
                                    class="sm:hidden flex flex-col w-full col-span-full sm:col-span-3 sm:col-start-1 order-3 text-blue-1100 gap-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                        address</p>

                                    {{-- Body --}}
                                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                                        {{-- Province --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                province </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->province }}</span>
                                        </div>

                                        {{-- City/Municipality --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                city / municipality </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->city_municipality }}</span>
                                        </div>

                                        {{-- District --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                district </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->district }}</span>
                                        </div>

                                        {{-- Barangay --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                barangay </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->barangay_name }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Spouse Information --}}
                                <div
                                    class="sm:hidden flex flex-col w-full col-span-full sm:col-span-3 order-4 sm:order-none text-blue-1100 gap-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                        spouse info</p>

                                    {{-- Body --}}
                                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">

                                        {{-- Spouse First Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                first name </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->spouse_first_name ?? '-' }}</span>
                                        </div>

                                        {{-- Spouse Middle Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                middle name </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->spouse_middle_name ?? '-' }}</span>
                                        </div>

                                        {{-- Spouse Last Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                last name </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->spouse_last_name ?? '-' }}</span>
                                        </div>

                                        {{-- Spouse Extension Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p
                                                class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                ext. name </p>
                                            <span
                                                class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                {{ $this->beneficiary?->spouse_extension_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right Side --}}
                                <div class="flex flex-col col-span-full sm:col-span-8 order-2 text-blue-1100 gap-1">

                                    {{-- Header --}}
                                    <p class="font-bold text-sm bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                        basic
                                        information</p>

                                    {{-- Body --}}
                                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- First Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    first name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->first_name }}</span>
                                            </div>

                                            {{-- Middle Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    middle name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->middle_name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- Last Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    last name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->last_name }}</span>
                                            </div>

                                            {{-- Extension Name --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    ext. name </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->extension_name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- Birthdate --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    birthdate </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ Carbon\Carbon::parse($this->beneficiary?->birthdate)->format('M. d, Y') }}</span>
                                            </div>

                                            {{-- Age --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    age </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->age }}
                                                </span>
                                            </div>

                                            {{-- Sex --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    sex </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ $this->beneficiary?->sex }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- Civil Status --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    civil status </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ $this->beneficiary?->civil_status }}</span>
                                            </div>

                                            {{-- Contact Number --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    contact number </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->contact_num }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- Occupation --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    occupation </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->occupation ?? 'None' }}</span>
                                            </div>

                                            {{-- Avg Monthly Income --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium capitalize">
                                                    avg. monthly income </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    @if ($this->beneficiary?->avg_monthly_income === null || $this->beneficiary?->avg_monthly_income === 0)
                                                        {{ '-' }}
                                                    @else
                                                        {{ '₱' . number_format($this->beneficiary?->avg_monthly_income / 100, 2) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- Type of Beneficiary --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium">
                                                    Type of Beneficiary </p>

                                                @if ($this->beneficiary?->beneficiary_type === 'special case')
                                                    <button type="button" @click="$wire.viewCredential('special');"
                                                        class="relative flex items-center justify-between whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 rounded capitalize px-2 py-0.5 outline-none bg-red-100 active:bg-red-200 text-red-950 hover:text-red-700 duration-200 ease-in-out">
                                                        {{ $this->beneficiary?->beneficiary_type }}

                                                        {{-- Loading Icon --}}
                                                        <svg class="absolute right-2 size-4 animate-spin" wire:loading
                                                            wire:target="viewCredential('special')"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>

                                                        {{-- Eye Icon --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="absolute right-2 size-4" wire:loading.remove
                                                            wire:target="viewCredential('special')"
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
                                                @else
                                                    <span
                                                        class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 rounded px-2 py-0.5 bg-blue-50 text-blue-1000 capitalize select-text">
                                                        {{ $this->beneficiary?->beneficiary_type }}
                                                    </span>
                                                @endif

                                            </div>

                                            {{-- Dependent --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    dependent </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->dependent ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between">
                                            {{-- Interested in Self Employment or Wage Employment --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    interested in self employment or wage employment </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ $this->beneficiary?->self_employment }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between">
                                            {{-- Skills Training --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    skills training </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->skills_training ?? '-' }}
                                                </span>
                                            </div>

                                            {{-- e-Payment Account Number --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium">
                                                    e-Payment Account Number </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-text">
                                                    {{ $this->beneficiary?->e_payment_acc_num ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- is PWD --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    Person w/ Disability </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ $this->beneficiary?->is_pwd }}</span>
                                            </div>

                                            {{-- is Senior Citizen --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    Senior Citizen </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ $this->beneficiary?->is_senior_citizen }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="hidden sm:flex items-center whitespace-nowrap justify-between gap-2">
                                            {{-- Date Added --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    Date Added </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ \Carbon\Carbon::parse($this->beneficiary?->created_at)->format('M d, Y @ h:i:sa') }}</span>
                                            </div>

                                            {{-- Last Updated --}}
                                            <div class="flex flex-1 flex-col justify-center">
                                                <p
                                                    class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                                    Last Updated </p>
                                                <span
                                                    class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                                    {{ \Carbon\Carbon::parse($this->beneficiary?->updated_at)->format('M d, Y @ h:i:sa') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="hidden sm:flex flex-1 px-2 py-1 gap-2">
                                        <div
                                            class="relative max-[430px]:flex-col flex flex-1 items-center justify-end gap-2">

                                            {{-- Edit Button --}}
                                            <button
                                                @if ($this->batch?->approval_status !== 'approved') @click="$wire.openEdit(); $dispatch('openEdit');"
                                                @else
                                                disabled @endif
                                                class="rounded text-sm font-bold flex flex-1 gap-2 items-center justify-center px-3 py-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:bg-blue-800 focus:ring-2 focus:ring-blue-300 duration-200 ease-in-out">
                                                EDIT

                                                {{-- Loading Icon --}}
                                                <svg class="size-5 animate-spin" wire:loading wire:target="openEdit"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>

                                                {{-- Edit Icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                    wire:loading.remove wire:target="openEdit"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M182.813 38.986 C 123.313 52.113,100.226 125.496,141.415 170.564 C 183.488 216.599,261.606 197.040,276.896 136.644 C 291.453 79.146,240.501 26.259,182.813 38.986 M278.141 204.778 C 272.904 206.868,270.880 210.858,270.342 220.156 L 269.922 227.420 264.768 229.218 C 261.934 230.206,258.146 231.841,256.351 232.849 L 253.088 234.684 248.224 229.884 C 241.216 222.970,235.198 221.459,229.626 225.214 C 221.063 230.985,221.157 239.379,229.884 248.224 L 234.684 253.088 232.849 256.351 C 231.841 258.146,230.206 261.934,229.218 264.768 L 227.420 269.922 220.156 270.313 C 208.989 270.915,204.670 274.219,204.083 282.607 C 203.466 291.419,208.211 295.523,219.675 296.094 L 227.526 296.484 228.868 300.781 C 229.606 303.145,231.177 306.971,232.359 309.285 L 234.508 313.492 230.227 317.879 C 223.225 325.054,221.747 330.343,224.976 336.671 C 229.458 345.458,239.052 345.437,248.076 336.622 L 252.794 332.014 258.233 334.683 C 261.224 336.151,265.133 337.742,266.919 338.218 L 270.167 339.083 270.435 346.830 C 270.818 357.905,274.660 362.505,283.514 362.495 C 292.220 362.485,296.084 357.523,296.090 346.344 L 296.094 339.173 300.586 337.882 C 303.057 337.171,306.997 335.559,309.341 334.298 L 313.605 332.006 318.326 336.618 C 324.171 342.328,325.413 342.969,330.613 342.966 C 344.185 342.956,347.496 329.464,336.652 318.359 L 332.075 313.672 334.421 309.022 C 335.711 306.464,337.308 302.509,337.970 300.233 L 339.173 296.094 346.276 296.094 C 357.566 296.094,362.500 292.114,362.500 283.005 C 362.500 274.700,357.650 270.809,346.830 270.435 L 339.083 270.167 338.218 266.919 C 337.742 265.133,336.151 261.224,334.683 258.233 L 332.014 252.794 336.622 248.076 C 345.259 239.234,345.423 230.021,337.028 225.208 C 330.778 221.625,325.473 222.915,318.356 229.749 L 313.432 234.478 309.255 232.344 C 306.958 231.170,303.145 229.606,300.781 228.868 L 296.484 227.526 296.094 219.675 C 295.460 206.941,288.076 200.814,278.141 204.778 M140.625 220.855 C 91.525 226.114,53.906 267.246,53.906 315.674 C 53.906 333.608,63.031 349.447,77.831 357.207 C 88.240 362.664,85.847 362.500,155.113 362.500 L 217.422 362.500 214.329 360.259 C 202.518 351.704,196.602 335.289,200.309 321.365 L 201.381 317.339 196.198 313.914 C 172.048 297.955,174.729 264.426,201.338 249.629 C 201.430 249.578,200.995 247.619,200.371 245.276 C 198.499 238.241,199.126 229.043,201.981 221.680 C 202.483 220.383,151.436 219.698,140.625 220.855 M290.207 252.760 C 316.765 259.678,323.392 292.263,301.575 308.656 C 283.142 322.507,256.557 311.347,252.282 287.964 C 248.462 267.069,269.646 247.405,290.207 252.760 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button
                                                @if ($this->batch?->approval_status !== 'approved') @click="deleteBeneficiaryModal = !deleteBeneficiaryModal;"
                                                @else
                                                disabled @endif
                                                class="rounded text-sm font-bold flex items-center justify-center p-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 focus:bg-red-800 focus:ring-2 focus:ring-red-300 duration-200 ease-in-out">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
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
                                        </div>
                                    </div>
                                </div>

                                {{-- Dates (on max-sm) --}}
                                <div
                                    class="sm:hidden flex items-center justify-between col-span-full order-5 whitespace-nowrap px-2 gap-2">
                                    {{-- Date Added --}}
                                    <div class="flex flex-1 flex-col justify-center">
                                        <p
                                            class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                            Date Added </p>
                                        <span
                                            class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                            {{ \Carbon\Carbon::parse($this->beneficiary?->created_at)->format('M d, Y @ h:i:sa') }}</span>
                                    </div>

                                    {{-- Last Updated --}}
                                    <div class="flex flex-1 flex-col justify-center">
                                        <p
                                            class="select-all selection:bg-blue-700 selection:text-blue-50 font-medium  capitalize">
                                            Last Updated </p>
                                        <span
                                            class="whitespace-nowrap overflow-x-auto scrollbar-none selection:bg-blue-700 selection:text-blue-50 bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-text">
                                            {{ \Carbon\Carbon::parse($this->beneficiary?->updated_at)->format('M d, Y @ h:i:sa') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="sm:hidden flex col-span-full order-6 px-2 py-1 gap-2">
                                    <div
                                        class="relative max-[430px]:flex-col flex flex-1 items-center justify-end gap-2">

                                        {{-- Edit Button --}}
                                        <button
                                            @if ($this->batch?->approval_status !== 'approved') @click="$wire.openEdit(); $dispatch('openEdit');"
                                            @else
                                            disabled @endif
                                            class="rounded text-sm font-bold flex flex-1 gap-2 items-center justify-center px-3 py-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:bg-blue-800 focus:ring-2 focus:ring-blue-300 duration-200 ease-in-out">
                                            EDIT

                                            {{-- Loading Icon --}}
                                            <svg class="size-5 animate-spin" wire:loading wire:target="openEdit"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Edit Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" wire:loading.remove
                                                wire:target="openEdit" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="400" height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M182.813 38.986 C 123.313 52.113,100.226 125.496,141.415 170.564 C 183.488 216.599,261.606 197.040,276.896 136.644 C 291.453 79.146,240.501 26.259,182.813 38.986 M278.141 204.778 C 272.904 206.868,270.880 210.858,270.342 220.156 L 269.922 227.420 264.768 229.218 C 261.934 230.206,258.146 231.841,256.351 232.849 L 253.088 234.684 248.224 229.884 C 241.216 222.970,235.198 221.459,229.626 225.214 C 221.063 230.985,221.157 239.379,229.884 248.224 L 234.684 253.088 232.849 256.351 C 231.841 258.146,230.206 261.934,229.218 264.768 L 227.420 269.922 220.156 270.313 C 208.989 270.915,204.670 274.219,204.083 282.607 C 203.466 291.419,208.211 295.523,219.675 296.094 L 227.526 296.484 228.868 300.781 C 229.606 303.145,231.177 306.971,232.359 309.285 L 234.508 313.492 230.227 317.879 C 223.225 325.054,221.747 330.343,224.976 336.671 C 229.458 345.458,239.052 345.437,248.076 336.622 L 252.794 332.014 258.233 334.683 C 261.224 336.151,265.133 337.742,266.919 338.218 L 270.167 339.083 270.435 346.830 C 270.818 357.905,274.660 362.505,283.514 362.495 C 292.220 362.485,296.084 357.523,296.090 346.344 L 296.094 339.173 300.586 337.882 C 303.057 337.171,306.997 335.559,309.341 334.298 L 313.605 332.006 318.326 336.618 C 324.171 342.328,325.413 342.969,330.613 342.966 C 344.185 342.956,347.496 329.464,336.652 318.359 L 332.075 313.672 334.421 309.022 C 335.711 306.464,337.308 302.509,337.970 300.233 L 339.173 296.094 346.276 296.094 C 357.566 296.094,362.500 292.114,362.500 283.005 C 362.500 274.700,357.650 270.809,346.830 270.435 L 339.083 270.167 338.218 266.919 C 337.742 265.133,336.151 261.224,334.683 258.233 L 332.014 252.794 336.622 248.076 C 345.259 239.234,345.423 230.021,337.028 225.208 C 330.778 221.625,325.473 222.915,318.356 229.749 L 313.432 234.478 309.255 232.344 C 306.958 231.170,303.145 229.606,300.781 228.868 L 296.484 227.526 296.094 219.675 C 295.460 206.941,288.076 200.814,278.141 204.778 M140.625 220.855 C 91.525 226.114,53.906 267.246,53.906 315.674 C 53.906 333.608,63.031 349.447,77.831 357.207 C 88.240 362.664,85.847 362.500,155.113 362.500 L 217.422 362.500 214.329 360.259 C 202.518 351.704,196.602 335.289,200.309 321.365 L 201.381 317.339 196.198 313.914 C 172.048 297.955,174.729 264.426,201.338 249.629 C 201.430 249.578,200.995 247.619,200.371 245.276 C 198.499 238.241,199.126 229.043,201.981 221.680 C 202.483 220.383,151.436 219.698,140.625 220.855 M290.207 252.760 C 316.765 259.678,323.392 292.263,301.575 308.656 C 283.142 322.507,256.557 311.347,252.282 287.964 C 248.462 267.069,269.646 247.405,290.207 252.760 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        {{-- Delete Button --}}
                                        <button
                                            @if ($this->batch?->approval_status !== 'approved') @click="deleteBeneficiaryModal = !deleteBeneficiaryModal;"
                                            @else
                                            disabled @endif
                                            class="rounded text-sm font-bold flex items-center justify-center p-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 focus:bg-red-800 focus:ring-2 focus:ring-red-300 duration-200 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
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
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="rounded relative bg-white p-4 h-full w-full flex items-center justify-center">
                                <div
                                    class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No preview.</p>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class="text-blue-900">beneficiaries</span> row.
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- View Credentials Modal --}}
                        <livewire:coordinator.submissions.view-credentials-modal :$passedCredentialId />

                        {{-- Edit Beneficiary Modal --}}
                        <livewire:coordinator.submissions.edit-beneficiary-modal :$beneficiaryId />

                        {{-- Delete Beneficiary Modal --}}
                        <div x-cloak class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50"
                            x-show="deleteBeneficiaryModal">

                            <!-- Modal -->
                            <div x-show="deleteBeneficiaryModal" x-trap.noscroll.noautofocus="deleteBeneficiaryModal"
                                class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

                                {{-- The Modal --}}
                                <div class="relative size-full max-w-xl">
                                    <div class="relative bg-white rounded-md shadow">
                                        <!-- Modal Header -->
                                        <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                            <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                                                {{ $this->defaultArchive ? 'Archive' : 'Delete' }} this Beneficiary
                                            </h1>

                                            {{-- Close Button --}}
                                            <button type="button" @click="deleteBeneficiaryModal = false;"
                                                class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                                            class="grid w-full place-items-center py-5 px-3 md:px-16 text-blue-1100 text-xs">

                                            @if ($this->defaultArchive)
                                                <p class="font-medium text-sm mb-2">
                                                    Are you sure about archiving this beneficiary?
                                                </p>
                                                <p class="text-gray-500 text-xs font-normal mb-4">
                                                    You could restore this beneficiary back from the Archives page
                                                </p>
                                            @else
                                                <p class="font-medium text-sm mb-2">
                                                    Are you sure about deleting this beneficiary?
                                                </p>
                                                <p class="text-gray-500 text-xs font-normal mb-4">
                                                    This is action is irreversible
                                                </p>
                                            @endif

                                            <div class="flex items-center justify-center w-full gap-2">

                                                <button @click="deleteBeneficiaryModal = false;"
                                                    class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-blue-700 hover:border-transparent active:border-transparent hover:bg-blue-800 active:bg-blue-900 text-blue-700 hover:text-blue-50 active:text-blue-50">
                                                    CANCEL
                                                </button>
                                                <button type="button"
                                                    @click="$wire.deleteBeneficiary(); deleteBeneficiaryModal = false;"
                                                    class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
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

            {{-- Import File Modal --}}
            <livewire:coordinator.submissions.import-file-modal :$batchId />

            {{-- Approve Submission Modal --}}
            <div x-cloak>
                <!-- Modal Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                    x-show="approveSubmissionModal">
                </div>

                <!-- Modal -->
                <div x-show="approveSubmissionModal" x-trap.noautofocus.noscroll="approveSubmissionModal"
                    class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                    {{-- The Modal --}}
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-md shadow">

                            <!-- Modal Header -->
                            <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                                <h1 class="text-sm sm:text-base font-semibold text-blue-1100">Approve Submission
                                </h1>

                                <div class="flex items-center justify-center">
                                    {{-- Loading State for Changes --}}
                                    <div class="z-50 text-blue-900" wire:loading
                                        wire:target="password, approveSubmission">
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

                                    {{-- Close Modal --}}
                                    <button type="button"
                                        @click="$wire.resetPassword(); approveSubmissionModal = false;"
                                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                            <div class="grid w-full place-items-center pt-5 pb-10 px-3 md:px-16 text-xs">

                                <p class="mb-2 text-sm font-medium text-blue-1100">Are you sure about approving this
                                    submission?
                                </p>
                                <p class="mb-4 text-xs font-medium text-gray-500">You won't be able to modify this
                                    batch until it is <span
                                        class="rounded-full bg-amber-300 text-amber-900 px-2 py-1 font-semibold">PENDING</span>
                                    again.
                                </p>

                                <div class="relative flex items-center justify-center w-full gap-2">
                                    <div class="relative">
                                        <input type="password" id="password_approve"
                                            wire:model.blur="password_approve"
                                            class="flex flex-1 {{ $errors->has('password_approve') ? 'caret-red-900 border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'caret-blue-900 border-blue-300 focus:border-blue-500 bg-blue-50 focus:ring-0' }} rounded outline-none border py-2.5 text-sm select-text duration-200 ease-in-out"
                                            placeholder="Enter your password">
                                        @error('password_approve')
                                            <p class="absolute top-full left-0 mt-1 text-xs text-red-700">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    <button wire:loading.attr="disabled" wire:target="approveSubmission"
                                        class="flex items-center justify-center disabled:bg-blue-300 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 py-2.5 px-2 rounded text-sm font-bold duration-200 ease-in-out"
                                        wire:click="approveSubmission">
                                        CONFIRM
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Export Summary Modal --}}
            <div x-cloak>
                <!-- Modal Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="showExportModal">
                </div>

                <!-- Modal -->
                <div x-show="showExportModal" x-trap.noautofocus.noscroll="showExportModal"
                    class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none">

                    {{-- The Modal --}}
                    <div class="flex items-center justify-center w-full max-w-3xl">
                        <div class="relative w-full bg-white rounded-md shadow">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                                    Export Annex
                                </h1>

                                <div class="flex items-center justify-end gap-2">

                                    {{-- Loading State --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-blue-900 animate-spin"
                                        wire:loading
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
                                    <button type="button" @click="$wire.resetExport(); showExportModal = false;"
                                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                                class="w-full flex flex-col items-center justify-center gap-4 pt-5 pb-6 px-3 md:px-12 text-blue-1100 text-xs">

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
                                                    'bg-blue-700 text-blue-50': e1_x,
                                                    'bg-gray-200 text-gray-500': !e1_x,
                                                }">
                                                Annex E-1 (COS)
                                                <input type="checkbox" class="hidden absolute inset-0" id="annex_e1"
                                                    wire:model.live="exportType.annex_e1">
                                            </label>
                                            {{-- Annex E-2 (COS - Co-Partner) --}}
                                            <label for="annex_e2"
                                                class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                :class="{
                                                    'bg-blue-700 text-blue-50': e2_x,
                                                    'bg-gray-200 text-gray-500': !e2_x,
                                                }">
                                                Annex E-2 (COS - Co-Partner)
                                                <input type="checkbox" class="hidden absolute inset-0" id="annex_e2"
                                                    wire:model.live="exportType.annex_e2">
                                            </label>
                                            {{-- Annex J-2 (Attendance Sheet) --}}
                                            <label for="annex_j2"
                                                class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                :class="{
                                                    'bg-blue-700 text-blue-50': j2_x,
                                                    'bg-gray-200 text-gray-500': !j2_x,
                                                }">
                                                Annex J-2 (Attendance Sheet)
                                                <input type="checkbox" class="hidden absolute inset-0" id="annex_j2"
                                                    wire:model.live="exportType.annex_j2">
                                            </label>
                                        </span>
                                        <span class="flex items-center flex-wrap gap-2">
                                            {{-- Annex L (Payroll) --}}
                                            <label for="annex_l"
                                                class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                :class="{
                                                    'bg-blue-700 text-blue-50': l_x,
                                                    'bg-gray-200 text-gray-500': !l_x,
                                                }">
                                                Annex L (Payroll)
                                                <input type="checkbox" class="hidden absolute inset-0" id="annex_l"
                                                    wire:model.live="exportType.annex_l">
                                            </label>
                                            {{-- Annex L (Payroll w/ Sign) --}}
                                            <label for="annex_l_sign"
                                                class="relative duration-200 ease-in-out cursor-pointer whitespace-nowrap flex items-center justify-center px-3 py-2 rounded font-semibold"
                                                :class="{
                                                    'bg-blue-700 text-blue-50': l_sign_x,
                                                    'bg-gray-200 text-gray-500': !l_sign_x,
                                                }">
                                                Annex L (Payroll w/ Sign)
                                                <input type="checkbox" class="hidden absolute inset-0"
                                                    id="annex_l_sign" wire:model.live="exportType.annex_l_sign">
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
                                                    'bg-blue-700 text-blue-50': csv_type === 'annex_e1',
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
                                                    'bg-blue-700 text-blue-50': csv_type === 'annex_e2',
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
                                                    'bg-blue-700 text-blue-50': csv_type === 'annex_j2',
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
                                                    'bg-blue-700 text-blue-50': csv_type === 'annex_l',
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
                                                    'bg-blue-700 text-blue-50': csv_type === 'annex_l_sign',
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
                                            'bg-blue-700 text-blue-50': exportFormat === 'xlsx',
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
                                            'bg-blue-700 text-blue-50': exportFormat === 'csv',
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
                                        class="flex items-center gap-1 sm:gap-2 pb-4 text-xs text-blue-1100">

                                        {{-- Start --}}
                                        <span class="text-sm font-medium">Filter Date:</span>
                                        <div class="relative">
                                            <span
                                                class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
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
                                            <input id="export-start-date" name="start" type="text" readonly
                                                wire:model.change="defaultExportStart"
                                                @change-date.camel="$wire.$set('defaultExportStart', $el.value);"
                                                value="{{ $defaultExportStart }}"
                                                class="border bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-500 focus:border-blue-500 text-xs rounded w-40 py-1.5 ps-7 sm:ps-8"
                                                placeholder="Select date start">
                                        </div>

                                        <span class="text-sm">-></span>

                                        {{-- End --}}
                                        <div class="relative">
                                            <span
                                                class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
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
                                            <input id="export-end-date" name="end" type="text" readonly
                                                wire:model.change="defaultExportEnd"
                                                @change-date.camel="$wire.$set('defaultExportEnd', $el.value);"
                                                value="{{ $defaultExportEnd }}"
                                                class="border bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-500 focus:border-blue-500 text-xs rounded w-40 py-1.5 ps-7 sm:ps-8"
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
                                                bg-blue-100 hover:bg-blue-800 active:bg-blue-900 
                                                text-blue-700 hover:text-blue-50 active:text-blue-50
                                                border-blue-700 hover:border-transparent active:border-transparent duration-200 ease-in-out">

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
                                                class="absolute -left-20 sm:inset-x-0 bottom-full p-3 mb-2 max-w-96 text-blue-1100 bg-white shadow-lg border border-blue-100 rounded text-xs">

                                                {{-- Batches Count | Search Bar --}}
                                                <div class="flex items-center w-full gap-2">
                                                    {{-- Batches Count --}}
                                                    <span
                                                        class="flex items-center gap-2 rounded {{ $this->exportBatches->isNotEmpty() ? 'text-blue-900 bg-blue-100' : 'text-red-900 bg-red-100' }} py-1.5 px-2 text-xs select-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            width="400" height="400" viewBox="0, 0, 400,400">
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
                                                        class="relative flex flex-1 items-center justify-center py-1 text-blue-700">

                                                        {{-- Icons --}}
                                                        <div class="absolute flex items-center justify-center left-2">
                                                            {{-- Loading State --}}
                                                            <svg class="size-4 animate-spin duration-200 ease-in-out pointer-events-none"
                                                                wire:loading wire:target="searchExportBatch"
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
                                                            <svg class="size-4 duration-200 ease-in-out pointer-events-none"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" wire:loading.remove
                                                                wire:target="searchExportBatch" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>

                                                        {{-- Search Bar --}}
                                                        <input id="searchExportBatch"
                                                            wire:model.live.debounce.350ms="searchExportBatch"
                                                            type="text" autocomplete="off"
                                                            class="rounded w-full ps-8 py-1.5 text-xs text-blue-1100 border-blue-200 hover:placeholder-blue-500 hover:border-blue-500 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none duration-200 ease-in-out"
                                                            placeholder="Search batch number">
                                                    </div>
                                                </div>

                                                {{-- Batches List --}}
                                                <div
                                                    class="mt-2 text-xs overflow-y-auto h-40 scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700">
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
                                                                    class="text-left outline-none w-full whitespace-nowrap overflow-x-auto scrollbar-none select-text flex items-center gap-2 ps-1 pe-4 py-2 text-blue-1100 hover:text-blue-900 hover:bg-blue-100 focus:text-blue-900 focus:bg-blue-100 duration-200 ease-in-out">

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
                                                                    class="size-12 mb-4 text-blue-900 opacity-65"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                                <p>No batches found.</p>
                                                                <p>Maybe try a different <span
                                                                        class=" text-blue-900">search
                                                                        term</span>?
                                                                </p>
                                                            @elseif ($calendarStart !== $defaultExportStart || $calendarEnd !== $defaultExportEnd)
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="size-12 mb-4 text-blue-900 opacity-65"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                                <p>No batches found.</p>
                                                                <p>Try adjusting the <span
                                                                        class=" text-blue-900">filter
                                                                        date</span>.
                                                                </p>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="size-12 mb-4 text-blue-900 opacity-65"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                                <p>No batches found.</p>
                                                                <p>Try assigning a <span class="text-blue-900">
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
                                            class="duration-200 ease-in-out flex items-center justify-center px-3 py-2 rounded outline-none font-bold text-sm disabled:bg-gray-300 disabled:text-gray-500 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">CONFIRM</button>
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
        class="fixed left-6 bottom-6 z-50 flex items-center border bg-blue-200 text-blue-1000 border-blue-300 rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
        role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                clip-rule="evenodd" />
        </svg>
        <p x-text="successMessage"></p>
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
