<x-slot:favicons>
    <x-c-favicons />
</x-slot>

<div x-cloak x-data="{ open: true, show: false, trapImport: false, trapDownload: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <livewire:sidebar.coordinator-bar />

    @if ($batchId)
        <livewire:coordinator.submissions.import-file-modal :$batchId />
        <livewire:coordinator.submissions.download-options-alert />
    @endif

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">
            {{-- Submissions Header --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Submissions</h1>

                {{-- Date Range picker --}}
                <div id="implementations-date-range" date-rangepicker datepicker-autohide class="flex items-center">

                    {{-- Start --}}
                    <div class="relative w-36 z-10">
                        <div class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <input id="start-date" name="start" type="text" value="{{ $defaultStart }}"
                            class="bg-white w-full border border-blue-300 text-blue-1100 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block ps-10"
                            placeholder="Select date start">
                    </div>

                    <span class="mx-2 text-blue-1100 text-sm">to</span>

                    {{-- End --}}
                    <div class="relative w-36 z-10">
                        <div
                            class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <input id="end-date" name="end" type="text" value="{{ $defaultEnd }}"
                            class="bg-white w-full border border-blue-300 text-blue-1100 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block ps-10"
                            placeholder="Select date end">
                    </div>
                </div>

                {{-- Buttons on Top & Loading --}}
                <div class="absolute w-full z-0 flex items-center justify-end">

                    {{-- Loading State --}}
                    <div class="flex items-center justify-start z-50 text-blue-900" wire:loading>
                        <svg class="w-8 h-8 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>

                    <button type="button"
                        @if ($batchId) data-modal-target="import-modal" data-modal-toggle="import-modal" @else disabled @endif
                        @click="trapImport=true"
                        class="flex items-center justify-center px-3 py-1.5 mx-2 rounded-md text-sm font-bold outline-none text-blue-50 bg-blue-700 hover:bg-blue-800 active:bg-blue-900">
                        IMPORT FILE
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ml-2"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M206.688 139.873 C 212.751 143.620,212.500 140.621,212.500 209.231 C 212.500 242.826,212.767 270.313,213.093 270.313 C 213.420 270.313,220.714 263.272,229.304 254.667 C 248.566 235.371,251.875 233.906,259.339 241.370 C 267.556 249.587,267.098 250.354,234.514 283.031 C 204.767 312.862,204.216 313.301,197.927 312.154 C 194.787 311.582,142.095 260.408,139.398 255.312 C 136.012 248.916,140.354 240.015,147.563 238.573 C 153.629 237.360,154.856 238.189,171.509 254.750 C 180.116 263.309,187.411 270.313,187.720 270.313 C 188.029 270.313,188.281 242.680,188.281 208.907 C 188.281 140.478,188.004 144.025,193.652 140.187 C 197.275 137.725,202.990 137.588,206.688 139.873 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>

                    <button type="button"
                        class="flex items-center justify-center px-3 py-1.5 mx-2 rounded-md text-sm font-bold outline-none text-blue-50 bg-green-700 hover:bg-green-800 active:bg-green-900">
                        APPROVE SUBMISSION
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ml-2"
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
            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-7">

                {{-- List of Beneficiaries --}}
                <div class="relative lg:col-span-3 h-[89vh] w-full rounded bg-white shadow">
                    {{-- Table Header --}}
                    <div class="relative max-h-12 my-2 flex items-center justify-between">
                        <div x-data="{ open: false }" class="relative text-blue-900">

                            {{-- Batches Dropdown Button --}}
                            <button id="batchDropdownButton"
                                @if ($this->batches->isNotEmpty()) @click="open = !open;"
                            @else
                            disabled @endif
                                class="flex items-center ms-4 py-1 px-2 text-xs outline-none font-semibold rounded {{ $this->batches->isNotEmpty() ? 'bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50' : 'bg-blue-300 text-blue-50' }} duration-200 ease-in-out">
                                {{ $this->currentBatch }}
                                @if ($this->batches->isNotEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3 ms-2"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                @endif
                            </button>

                            {{-- Batch Dropdown Content --}}
                            <div id="batchDropdownContent" x-cloak x-show="open" @click.away="open = !open;"
                                :class="{
                                    'block': open === true,
                                    'hidden': open === false,
                                }"
                                class="absolute top-7 left-4 z-50 p-2 w-[20.5rem] bg-white border rounded shadow">
                                {{-- Header / Search Batches / Counter / Filter --}}
                                <div class="mx-4 mb-2 flex items-center justify-center">
                                    <span
                                        class="flex items-center rounded text-blue-700 bg-blue-100 py-1 px-2 text-xs me-2 select-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 me-1.5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        {{ $this->batchesCount }}</span>
                                    <div class="relative flex items-center">
                                        <div
                                            class="absolute z-50 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg class="size-3 text-blue-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="batch-search" maxlength="100" autocomplete="off"
                                            wire:model.live.debounce.300ms="searchBatches"
                                            class="duration-200 outline-none ease-in-out ps-6 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Search for batch numbers">
                                    </div>
                                    {{-- Filter Button --}}
                                    <div x-data="{
                                        open: false,
                                        toggle() {
                                            this.open = !this.open;
                                        },
                                    
                                        selectOption(option) {
                                            this.type_of_id = option;
                                            this.toggle(); // Close the dropdown after selecting an option
                                        }
                                    }" x-id="['button']" class="relative"
                                        x-on:click.outside="open = false">
                                        <!-- Button -->
                                        <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                                            :aria-controls="$id('button')" type="button"
                                            class="flex items-center outline-none rounded p-1 ms-2 text-sm font-bold duration-200 ease-in-out border-2 border-blue-700 hover:border-transparent active:border-transparent hover:bg-blue-700 active:bg-blue-900 text-blue-900 hover:text-blue-100 active:text-blue-200 focus:bg-blue-700 focus:text-blue-50 focus:border-transparent">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M55.859 51.091 C 37.210 57.030,26.929 76.899,32.690 95.866 C 35.051 103.642,34.376 102.847,97.852 172.610 L 156.250 236.794 156.253 298.670 C 156.256 359.035,156.294 360.609,157.808 363.093 C 161.323 368.857,170.292 370.737,175.953 366.895 C 184.355 361.193,241.520 314.546,242.553 312.549 C 243.578 310.566,243.750 304.971,243.750 273.514 L 243.750 236.794 302.148 172.610 C 365.624 102.847,364.949 103.642,367.310 95.866 C 372.533 78.673,364.634 60.468,348.673 52.908 L 343.359 50.391 201.172 50.243 C 87.833 50.126,58.350 50.298,55.859 51.091 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        <!-- Panel -->
                                        <div x-ref="panel" x-show="open" x-transition.origin.top
                                            :id="$id('button')" style="display: none;"
                                            class="absolute text-xs left-0 mt-2 h-40 w-40 z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                            {{-- <button type="button" x-on:click="selectOption('e-Card / UMID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                                    e-Card / UMID
                                                    </button> --}}
                                            Insert filters here
                                        </div>
                                    </div>
                                </div>
                                <ul class="px-2 text-sm text-blue-1100 overflow-y-auto h-48 scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700"
                                    aria-labelledby="batchButton">
                                    @forelse ($this->batches as $key => $batch)
                                        <li wire:key="batch-{{ $key }}">
                                            <button type="button" @click="open = !open;"
                                                wire:loading.class="pointer-events-none"
                                                wire:click="selectBatchRow({{ $key }}, '{{ encrypt($batch->id) }}')"
                                                class="flex items-center w-full px-1 py-2 text-xs hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out cursor-pointer">
                                                {{ $batch->batch_num }} / {{ $batch->barangay_name }}
                                            </button>
                                        </li>
                                    @empty
                                        <li
                                            class="flex items-center justify-center h-full w-full border rounded bg-gray-50 border-gray-300 text-gray-500">
                                            <p>Nothing to see here...</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- Search Beneficiaries --}}
                        <div class="me-4 flex items-center justify-end">

                            {{-- Beneficiary Count --}}
                            <span
                                class="flex items-center font-medium rounded {{ $this->beneficiarySlots === 0 ? 'text-red-700 bg-red-100 ' : 'text-blue-700 bg-blue-100 ' }} py-1 px-2 text-xs me-2 select-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 me-1.5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M96.875 42.643 C 52.219 54.424,52.561 118.254,97.341 129.707 C 111.583 133.349,116.540 131.561,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.616 67.550,148.905 66.535,145.219 60.791 C 135.687 45.938,114.514 37.989,96.875 42.643 M280.938 42.600 C 270.752 45.179,260.204 52.464,254.763 60.678 C 251.061 66.267,251.383 67.401,258.836 75.011 C 272.214 88.670,280.835 105.931,282.526 122.444 C 283.253 129.539,284.941 131.255,291.175 131.236 C 330.920 131.117,351.409 84.551,324.504 55.491 C 313.789 43.917,296.242 38.725,280.938 42.600 M189.063 75.494 C 134.926 85.627,123.780 159.908,172.566 185.433 C 216.250 208.290,267.190 170.135,257.471 121.839 C 251.236 90.860,220.007 69.703,189.063 75.494 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M283.058 149.743 C 282.139 150.542,280.658 153.753,279.696 157.031 C 276.119 169.218,270.328 179.314,261.225 189.234 C 253.482 197.670,254.234 200.382,265.191 203.537 C 288.694 210.306,307.108 223.950,319.474 243.758 C 324.516 251.833,323.991 251.565,334.706 251.543 C 362.465 251.487,376.780 236.149,375.520 207.813 C 374.261 179.527,360.172 159.904,334.766 151.051 C 326.406 148.137,286.076 147.117,283.058 149.743 M150.663 223.858 C 119.731 229.560,95.455 253.370,88.566 284.766 C 80.747 320.396,94.564 350.121,122.338 357.418 C 129.294 359.246,270.706 359.246,277.662 357.418 C 300.848 351.327,312.868 333.574,312.837 305.469 C 312.790 264.161,291.822 235.385,254.043 224.786 C 246.270 222.606,161.583 221.845,150.663 223.858 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                {{ $this->beneficiarySlots }}</span>

                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 text-blue-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="beneficiary-search" maxlength="100" autocomplete="off"
                                    @input.debounce.300ms="$wire.searchBeneficiaries = $el.value; $wire.$refresh();"
                                    class="duration-200 outline-none ease-in-out ps-7 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search for beneficiaries">
                            </div>
                        </div>
                    </div>

                    {{-- Beneficiaries Table --}}
                    @if ($this->beneficiaries->isNotEmpty())
                        <div id="beneficiaries-table"
                            class="relative min-h-[82.5vh] max-h-[82.5vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            <table class="relative w-full text-sm text-left text-blue-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-blue-50 uppercase bg-blue-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pr-2 ps-4 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="pr-2 py-2">
                                            full name
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            sex
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            birthdate
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:loading.class="pointer-events-none"
                                            wire:click.prevent='selectBeneficiaryRow({{ $key }}, "{{ encrypt($beneficiary->id) }}")'
                                            class="relative border-b {{ $selectedBeneficiaryRow === $key ? 'bg-gray-100 hover:bg-gray-200 text-blue-1000 hover:text-blue-900' : 'hover:bg-gray-50' }} whitespace-nowrap duration-200 ease-in-out cursor-pointer">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="pr-2 py-2">
                                                {{ $this->getFullName($key) }}
                                            </td>
                                            <td class="pr-2 py-2 text-center uppercase">
                                                {{ $beneficiary->sex }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $beneficiary->birthdate }}
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <tr x-data x-intersect.full="$wire.loadMoreBeneficiaries()">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[82.5vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($this->batches->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class=" size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
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
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class=" size-12 sm:size-20 mb-4 text-blue-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
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

                    {{-- Create Button | Main Modal --}}
                    {{-- <livewire:focal.batchs.create-project-modal /> --}}
                </div>

                {{-- Beneficiary Preview | Special Cases --}}
                <div class="relative flex flex-col lg:col-span-4 size-full">

                    {{-- Beneficiary Preview --}}
                    <div class="grid grid-rows-5 h-[55%] w-full rounded bg-white shadow text-xs select-text">

                        @if ($beneficiaryId)
                            {{-- Upper --}}
                            <div class="row-span-3 flex items-start justify-between border-b border-gray-300">

                                {{-- ID --}}
                                <div class="flex flex-col text-blue-1100 ps-2 pt-2">
                                    <div
                                        class="bg-blue-100 text-blue-900 border-blue-300 border-dashed border-2 rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-32"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M107.422 50.878 C 79.094 54.549,57.713 74.036,51.814 101.563 C 49.620 111.800,49.620 288.200,51.814 298.438 C 57.220 323.662,76.338 342.780,101.563 348.186 C 107.773 349.517,114.149 349.609,200.000 349.609 C 285.851 349.609,292.227 349.517,298.438 348.186 C 323.662 342.780,342.780 323.662,348.186 298.438 C 350.380 288.200,350.380 111.800,348.186 101.563 C 342.861 76.716,324.200 57.775,299.219 51.860 C 292.608 50.294,118.792 49.405,107.422 50.878 M283.372 84.383 C 295.540 85.460,299.847 87.205,306.321 93.679 C 315.819 103.176,316.386 107.330,316.398 167.420 L 316.406 208.669 313.086 206.393 C 290.258 190.744,266.010 193.819,243.963 215.159 C 238.678 220.274,234.240 224.317,234.100 224.144 C 220.448 207.251,185.837 166.529,182.862 163.858 C 168.386 150.865,145.748 148.079,127.547 157.051 C 119.004 161.262,114.813 165.299,98.040 185.480 L 83.984 202.389 83.754 165.062 C 83.406 108.493,84.139 103.218,93.679 93.679 C 99.894 87.463,104.758 85.373,115.176 84.442 C 125.621 83.509,272.912 83.457,283.372 84.383 M227.937 133.924 C 211.002 139.968,213.315 164.176,231.085 166.867 C 241.190 168.397,250.000 160.541,250.000 150.000 C 250.000 137.987,239.004 129.974,227.937 133.924 M156.764 187.447 C 159.428 188.657,164.587 194.405,185.420 219.379 C 212.037 251.287,213.239 252.533,220.736 255.991 C 235.489 262.795,247.798 259.174,264.151 243.218 C 281.729 226.068,285.035 226.261,304.492 245.576 L 316.406 257.403 316.398 265.616 C 316.363 301.864,308.764 313.369,283.372 315.617 C 271.802 316.641,128.198 316.641,116.628 315.617 C 91.083 313.356,83.659 302.019,83.606 265.193 L 83.594 256.558 111.781 222.744 C 144.714 183.237,145.732 182.438,156.764 187.447 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <p class="font-semibold select-all text-center">
                                        {{ $this->getIdType }}</p>
                                    <p class="text-center select-all text-2xs">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->id_number }}</p>
                                </div>

                                {{-- Basic Information --}}
                                <div class="flex flex-col text-blue-1100 mx-2 mt-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm bg-blue-900 text-blue-50 rounded uppercase m-1 px-2 py-1">
                                        basic
                                        information</p>

                                    {{-- Body --}}
                                    {{-- First Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            first name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->first_name }}</p>
                                    </span>

                                    {{-- Middle Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            middle name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->middle_name ?? '-' }}</p>
                                    </span>

                                    {{-- Last Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            last name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->last_name }}</p>
                                    </span>

                                    {{-- Extension Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            extension name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->extension_name ?? '-' }}
                                        </p>
                                    </span>

                                    {{-- Birthdate --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            birthdate: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ Carbon\Carbon::parse($this->beneficiaries[$selectedBeneficiaryRow]->birthdate)->format('M. d, Y') }}
                                        </p>
                                    </span>

                                    {{-- Contact Number --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            contact #: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->contact_num }}</p>
                                    </span>

                                    {{-- Civil Status --}}
                                    <span class="select-all flex items-center mx-2">
                                        <p class="font-semibold text-center capitalize text-blue-1000">
                                            civil status: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->civil_status }}</p>
                                    </span>

                                    {{-- Sex --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            sex: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->sex }}</p>
                                    </span>

                                    {{-- Age --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            age: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->age }}</p>
                                    </span>
                                </div>

                                {{-- Additional Information --}}
                                <div class="flex flex-col text-blue-1100 mx-2 mt-1">
                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm bg-blue-900 text-blue-50 rounded uppercase m-1 px-2 py-1">
                                        additional
                                        information</p>

                                    {{-- Body --}}
                                    {{-- Occupation --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            occupation: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->occupation ?? 'None' }}
                                        </p>
                                    </span>

                                    {{-- Type of Beneficiary --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center text-blue-1000">
                                            Type of Beneficiary: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->beneficiary_type }}</p>
                                    </span>

                                    {{-- Dependent --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            dependent: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->dependent ?? '-' }}
                                        </p>
                                    </span>

                                    {{-- Interested in Self-Employment --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center text-blue-1000">
                                            Interested in Self-Employment: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->self_employment }}
                                        </p>
                                    </span>

                                    {{-- Avg. Monthly Income --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold capitalize text-center text-blue-1000">
                                            avg. monthly income: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            @if (
                                                $this->beneficiaries[$selectedBeneficiaryRow]->avg_monthly_income === null ||
                                                    $this->beneficiaries[$selectedBeneficiaryRow]->avg_monthly_income === 0)
                                                -
                                            @else
                                                ₱{{ number_format($this->beneficiaries[$selectedBeneficiaryRow]->avg_monthly_income / 100, 2) }}
                                            @endif
                                        </p>
                                    </span>

                                    {{-- Skills Training --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            skills training: </p>
                                        <p class="text-center select-all ms-1 capitalize">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->skills_training }}
                                        </p>
                                    </span>

                                    {{-- e-Payment Account # --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center text-blue-1000">
                                            e-Payment Account #: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->e_payment_acc_num ?? '-' }}
                                        </p>
                                    </span>

                                    {{-- is PWD --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center text-blue-1000">
                                            Person w/ Disability: </p>
                                        <p class="text-center select-all capitalize ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->is_pwd }}
                                        </p>
                                    </span>

                                    {{-- is Senior Citizen --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center text-blue-1000">
                                            Senior Citizen: </p>
                                        <p class="text-center select-all capitalize ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->is_senior_citizen }}
                                        </p>
                                    </span>
                                </div>
                            </div>

                            {{-- Lower --}}
                            <div class="row-span-2 flex items-start justify-start">
                                {{-- Address --}}
                                <div class="flex flex-col text-blue-1100 mx-2 mt-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm bg-blue-900 text-blue-50 rounded uppercase m-1 px-2 py-1">
                                        address</p>

                                    {{-- Body --}}
                                    {{-- Province --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            province: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->province }}
                                        </p>
                                    </span>

                                    {{-- City/Municipality --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center text-blue-1000">
                                            City/Municipality: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->city_municipality }}
                                        </p>
                                    </span>

                                    {{-- District --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            district: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->district }}
                                        </p>
                                    </span>
                                </div>

                                {{-- Spouse Information --}}
                                <div class="flex flex-col text-blue-1100 mx-2 mt-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm bg-blue-900 text-blue-50 rounded uppercase m-1 px-2 py-1">
                                        spouse information</p>

                                    {{-- Body --}}
                                    {{-- Spouse First Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            spouse first name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_first_name ?? '-' }}
                                        </p>
                                    </span>

                                    {{-- Spouse Middle Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            spouse middle name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_middle_name ?? '-' }}
                                        </p>
                                    </span>

                                    {{-- Spouse Last Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            spouse last name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_last_name ?? '-' }}
                                        </p>
                                    </span>

                                    {{-- Spouse Extension Name --}}
                                    <span class="flex items-center mx-2">
                                        <p class="select-all font-semibold text-center capitalize text-blue-1000">
                                            spouse extension name: </p>
                                        <p class="text-center select-all ms-1">
                                            {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_extension_name ?? '-' }}
                                        </p>
                                    </span>
                                </div>
                            </div>
                        @else
                            <div
                                class="rounded relative bg-white p-4 row-span-full min-w-full flex items-center justify-center">
                                <div
                                    class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
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
                                    <p>No preview.</p>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class="text-blue-900">beneficiaries</span> row.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Special Cases --}}
                    <div class="flex flex-col h-[45%] w-full">
                        <p class="text-base text-amber-950 my-2 font-bold ms-3">Special Cases</p>
                        @if (false)
                            {{-- Special Cases Table --}}
                            <div id="special-cases-table"
                                class="relative h-[40vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                    <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                        <tr>
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
                                                days of work
                                            </th>
                                            <th scope="col" class="px-2 py-2 text-center">

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="relative text-xs">
                                        @foreach ($this->implementations as $key => $implementation)
                                            @php
                                                $encryptedId = Crypt::encrypt($implementation->id);
                                            @endphp
                                            <tr wire:key="implementation-{{ $key }}"
                                                wire:click.prevent='selectImplementationRow({{ $key }}, "{{ $encryptedId }}")'
                                                class="relative border-b duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'bg-gray-200 text-indigo-900 hover:bg-gray-300' : ' hover:bg-gray-50' }}  whitespace-nowrap cursor-pointer">
                                                <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                    {{ $implementation->project_num }}
                                                </th>
                                                <td class="pr-6 py-2">
                                                    {{ $implementation->project_title }}
                                                </td>
                                                <td class="pr-2 py-2 text-center">
                                                    {{ $implementation->total_slots }}
                                                </td>
                                                <td class="pr-2 py-2 text-center">
                                                    {{ $implementation->days_of_work }}
                                                </td>
                                                {{-- Special Cases Dropdown --}}
                                                <td x-data="iDropdownRotation({{ $key }})" class="py-2 flex">
                                                    <button @click.stop="handleClick()"
                                                        id="implementationRowButton-{{ $key }}"
                                                        data-dropdown-placement="left"
                                                        data-dropdown-toggle="implementationRowDropdown-{{ $key }}"
                                                        class="z-0 mx-1 p-1 outline-none rounded duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor"
                                                            :class="{
                                                                'rotate-0': !isVisible(),
                                                                'rotate-90': isVisible(),
                                                            }"
                                                            class="size-4 transition-transform duration-200 ease-in-out">
                                                            <path fill-rule="evenodd"
                                                                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            @if ($this->implementations->count() > 5 && $loop->last)
                                                <tr x-data x-intersect.full="$wire.loadMoreImplementations();">

                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Special Cases Dropdown Content --}}
                            @foreach ($this->implementations as $key => $implementation)
                                <div wire:key="implementationRowDropdown-{{ $key }}"
                                    id="implementationRowDropdown-{{ $key }}"
                                    class="absolute z-50 hidden bg-white border rounded-md shadow">
                                    <ul class="text-sm text-indigo-1100"
                                        aria-labelledby="implementationRowButton-{{ $key }}">
                                        <li>
                                            <a aria-label="{{ __('View Project') }}"
                                                class="rounded-t-md flex items-center outline-none ring-0 justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M196.484 30.192 C 193.112 30.921,90.341 91.036,88.733 93.221 C 87.153 95.366,87.099 96.484,86.719 134.490 L 86.328 173.554 75.781 177.394 C -33.739 217.272,-22.890 302.011,95.768 333.518 L 98.958 334.365 96.759 344.331 C 92.647 362.961,95.217 369.852,106.250 369.785 C 109.346 369.766,162.422 349.406,171.174 344.880 C 176.624 342.062,178.462 332.569,174.580 327.300 C 171.295 322.843,127.874 279.583,125.926 278.827 C 115.728 274.870,110.733 279.883,106.915 297.906 L 104.297 310.265 101.953 309.811 C 89.267 307.353,63.261 296.301,50.195 287.815 C 7.311 259.963,19.068 225.316,79.297 202.054 L 86.328 199.339 86.719 210.412 C 86.956 217.124,87.493 222.155,88.084 223.188 C 90.857 228.032,194.289 287.103,200.000 287.103 C 203.857 287.104,294.205 236.696,308.801 226.401 C 312.664 223.676,313.281 221.391,313.281 209.815 L 313.281 199.241 317.773 200.904 C 419.850 238.702,380.629 302.630,244.766 319.905 C 229.129 321.893,227.054 322.693,224.706 327.642 C 221.919 333.513,223.930 340.377,229.378 343.594 C 237.632 348.466,295.155 337.825,328.450 325.266 C 426.552 288.262,424.383 213.945,324.134 177.371 L 313.672 173.554 313.281 134.490 C 312.840 90.412,313.185 92.724,306.590 89.523 C 304.899 88.702,282.070 75.661,255.859 60.541 C 197.763 27.030,201.731 29.058,196.484 30.192 M237.634 78.266 C 258.615 90.375,275.773 100.482,275.763 100.727 C 275.732 101.488,201.314 144.085,200.016 144.085 C 196.374 144.085,123.224 100.464,124.947 99.320 C 127.149 97.858,198.718 56.371,199.158 56.302 C 199.339 56.273,216.653 66.157,237.634 78.266 M149.688 143.951 L 187.891 166.027 188.092 209.967 C 188.203 234.134,188.063 253.906,187.780 253.906 C 187.497 253.906,170.092 243.986,149.102 231.860 L 110.938 209.814 110.938 165.845 C 110.938 141.661,111.061 121.875,111.211 121.875 C 111.362 121.875,128.676 131.809,149.688 143.951 M289.063 165.858 L 289.063 209.842 250.888 231.874 C 229.893 243.992,212.487 253.906,212.210 253.906 C 211.933 253.906,211.797 234.134,211.908 209.967 L 212.109 166.028 250.353 143.952 C 271.387 131.810,288.701 121.875,288.829 121.875 C 288.958 121.875,289.063 141.668,289.063 165.858 M142.969 329.731 C 142.969 330.434,124.202 337.744,123.696 337.238 C 123.329 336.871,126.469 320.979,127.870 316.114 C 128.254 314.783,142.969 328.053,142.969 329.731 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                View Project
                                            </a>
                                        </li>
                                        <li>
                                            <a aria-label="{{ __('Modify Project') }}"
                                                class="rounded-b-md flex items-center outline-none ring-0 justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M73.654 21.493 C 57.244 25.059,43.944 37.290,38.832 53.516 L 37.109 58.984 37.109 200.000 L 37.109 341.016 38.832 346.484 C 44.001 362.890,57.287 374.996,74.011 378.540 C 78.301 379.449,89.313 379.663,132.452 379.674 L 185.606 379.687 189.089 377.930 C 197.816 373.525,198.464 361.986,190.325 355.908 C 188.207 354.326,187.188 354.290,133.342 353.906 C 71.185 353.463,74.722 353.845,68.479 346.914 C 62.361 340.123,62.891 354.043,62.891 200.000 L 62.891 62.109 64.687 58.462 C 65.675 56.456,68.015 53.403,69.887 51.678 C 76.184 45.873,72.988 46.094,150.881 46.094 L 220.229 46.094 220.466 76.777 L 220.703 107.459 223.261 112.653 C 230.648 127.651,235.336 129.222,273.633 129.529 L 303.906 129.772 303.906 158.334 C 303.906 185.979,303.959 186.991,305.561 189.841 C 310.258 198.199,323.311 198.051,327.941 189.587 C 329.656 186.454,330.450 126.522,328.885 118.359 C 327.048 108.771,326.294 107.889,283.368 65.114 C 241.714 23.606,241.100 23.073,232.650 21.124 C 227.078 19.839,79.693 20.181,73.654 21.493 M267.077 103.726 C 243.712 103.997,246.094 106.362,246.094 82.893 L 246.094 64.465 265.632 83.990 L 285.170 103.516 267.077 103.726 M96.971 103.891 C 86.128 106.520,83.634 120.880,92.931 127.146 L 95.544 128.906 137.434 128.906 L 179.325 128.906 182.129 127.001 C 189.779 121.802,189.297 109.849,181.247 105.120 C 178.324 103.403,103.484 102.312,96.971 103.891 M92.931 164.260 C 85.267 169.425,85.267 180.575,92.931 185.740 L 95.544 187.500 174.920 187.497 C 252.748 187.494,254.347 187.464,256.843 185.942 C 264.551 181.242,264.551 168.758,256.843 164.058 C 254.347 162.536,252.748 162.506,174.920 162.503 L 95.544 162.500 92.931 164.260 M264.844 204.939 C 256.120 206.628,254.875 207.872,249.987 219.775 C 245.390 230.969,243.780 231.875,231.016 230.453 C 216.581 228.844,213.893 230.325,204.750 244.928 C 193.227 263.331,192.908 269.027,202.697 281.565 C 209.871 290.751,209.841 292.493,202.353 302.168 C 192.905 314.377,193.082 318.102,204.018 337.215 C 212.564 352.152,217.046 354.770,230.895 352.910 L 239.989 351.689 243.389 353.742 C 246.413 355.569,247.106 356.574,249.673 362.859 C 255.097 376.137,256.621 377.371,269.563 378.962 C 292.950 381.838,302.199 378.265,308.140 364.063 C 311.296 356.520,311.854 355.692,315.086 353.778 L 318.601 351.696 326.683 352.852 C 342.157 355.064,348.387 350.573,358.197 330.132 C 364.864 316.242,364.674 313.537,356.268 302.585 C 348.565 292.550,348.587 290.936,356.569 280.362 C 365.509 268.520,364.633 261.091,352.126 242.653 C 343.300 229.642,340.951 228.575,325.618 230.617 C 315.060 232.023,312.874 230.627,308.393 219.621 C 304.614 210.342,301.807 206.940,296.772 205.535 C 291.765 204.138,270.986 203.750,264.844 204.939 M94.141 221.871 C 85.580 226.138,84.599 238.340,92.371 243.884 L 94.922 245.703 129.044 245.703 C 162.289 245.703,163.222 245.662,165.324 244.092 C 173.464 238.015,172.816 226.475,164.089 222.070 C 158.952 219.477,99.285 219.307,94.141 221.871 M287.291 236.719 C 290.896 245.459,292.223 247.189,297.090 249.497 C 299.335 250.562,302.500 252.337,304.123 253.442 C 311.171 258.239,313.421 258.547,326.953 256.566 C 330.062 256.110,330.091 256.130,332.733 260.509 L 335.387 264.910 332.946 268.197 C 331.604 270.005,329.236 273.290,327.684 275.497 L 324.862 279.511 324.805 290.732 C 324.738 304.126,324.764 304.223,330.624 311.922 L 335.239 317.985 332.716 322.720 C 330.609 326.672,329.925 327.379,328.573 327.004 C 327.682 326.756,323.262 326.337,318.750 326.073 L 310.547 325.593 306.589 328.335 C 304.413 329.843,300.722 332.034,298.386 333.203 C 292.195 336.302,291.065 337.646,287.461 346.186 L 284.203 353.906 279.168 353.906 L 274.132 353.906 272.441 349.805 C 267.996 339.021,266.240 336.445,261.719 334.082 C 259.355 332.846,255.208 330.421,252.502 328.693 L 247.583 325.550 239.612 326.039 C 235.228 326.307,230.892 326.741,229.978 327.002 C 228.590 327.398,227.913 326.772,225.876 323.213 C 222.786 317.811,222.686 318.423,227.718 311.946 C 233.417 304.611,233.594 303.984,233.591 291.160 C 233.588 278.746,233.645 278.923,226.665 270.030 L 223.070 265.451 225.476 261.046 C 227.717 256.944,228.066 256.651,230.543 256.788 C 246.958 257.696,247.881 257.603,252.459 254.573 C 254.826 253.007,258.535 250.827,260.701 249.729 C 265.917 247.086,267.839 244.636,271.101 236.470 L 273.828 229.645 279.190 229.862 L 284.551 230.078 287.291 236.719 M271.892 271.841 C 258.218 276.561,253.528 294.319,263.025 305.414 C 270.970 314.695,287.426 314.994,295.024 305.994 C 309.248 289.146,292.669 264.669,271.892 271.841 M93.913 280.196 C 84.324 284.971,85.045 299.439,95.080 303.632 C 100.082 305.722,159.760 305.115,164.089 302.930 C 172.816 298.525,173.464 286.986,165.325 280.908 C 162.300 278.648,98.303 278.010,93.913 280.196 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                Modify Project
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <div
                                class="rounded relative bg-white p-4 h-[35vh] min-w-full flex items-center justify-center">
                                <div
                                    class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-amber-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M27.764 31.055 C 17.988 51.181,17.305 58.177,24.560 63.884 C 35.333 72.358,48.707 63.533,46.309 49.532 C 45.507 44.849,34.777 20.433,33.483 20.346 C 33.208 20.328,30.634 25.146,27.764 31.055 M193.963 32.233 C 185.507 50.066,184.773 53.748,188.391 60.185 C 196.635 74.852,216.718 65.103,212.806 48.333 C 211.812 44.073,200.951 20.318,200.000 20.324 C 199.785 20.325,197.069 25.684,193.963 32.233 M334.201 31.469 C 324.484 52.021,324.056 57.298,331.568 63.894 C 341.996 73.049,356.270 62.685,352.805 48.474 C 351.622 43.620,341.224 20.895,340.021 20.532 C 339.709 20.437,337.090 25.359,334.201 31.469 M88.282 83.318 C 78.208 104.223,77.429 109.478,83.448 115.917 C 93.429 126.591,109.296 117.281,106.338 102.485 C 105.587 98.728,95.524 75.977,93.714 73.943 C 93.388 73.577,90.943 77.795,88.282 83.318 M267.001 86.133 C 257.324 106.588,257.438 114.277,267.492 119.047 C 278.792 124.410,289.236 114.225,286.002 100.996 C 285.105 97.327,274.219 73.509,273.418 73.462 C 273.192 73.448,270.304 79.150,267.001 86.133 M193.136 125.445 C 162.213 150.186,139.844 204.462,139.844 254.750 C 139.844 255.966,140.691 255.758,146.325 253.155 C 178.550 238.267,221.255 238.115,252.988 252.777 C 260.705 256.343,260.378 256.543,259.781 248.633 C 255.519 192.123,237.356 149.841,206.864 125.445 C 199.155 119.276,200.845 119.276,193.136 125.445 M169.531 121.610 C 151.282 124.441,137.513 127.501,134.570 129.382 C 132.855 130.477,132.813 130.843,132.813 144.425 C 132.813 162.138,133.113 161.244,119.377 184.384 C 104.598 209.283,105.466 207.395,105.486 214.610 C 105.512 223.371,107.534 246.925,108.319 247.604 C 108.685 247.921,110.742 248.816,112.891 249.595 C 115.039 250.374,119.102 252.160,121.919 253.564 L 127.041 256.117 127.988 243.662 C 131.701 194.821,147.477 154.390,173.514 126.979 L 179.105 121.094 175.295 121.200 C 173.199 121.259,170.605 121.443,169.531 121.610 M226.390 126.858 C 252.507 154.446,268.285 194.795,271.972 243.427 C 272.490 250.265,272.996 255.969,273.095 256.104 C 273.195 256.239,276.141 254.908,279.643 253.147 C 309.070 238.352,352.729 238.333,381.990 253.104 L 386.950 255.608 386.419 251.046 C 382.904 220.863,362.219 186.558,333.984 164.090 C 314.799 148.824,283.706 132.813,273.244 132.813 C 268.403 132.813,263.464 131.302,259.285 128.544 C 255.010 125.722,240.339 122.493,226.563 121.341 L 220.703 120.851 226.390 126.858 M64.453 165.449 C 37.775 187.782,20.038 216.116,14.518 245.218 C 12.428 256.232,12.172 255.877,19.727 252.407 C 36.721 244.600,46.767 242.578,68.554 242.578 C 78.115 242.578,85.935 242.402,85.933 242.188 C 85.931 241.973,81.329 235.820,75.706 228.516 C 59.275 207.169,59.338 207.647,70.117 185.633 C 78.462 168.591,78.710 167.276,74.381 163.049 C 70.756 159.509,71.871 159.239,64.453 165.449 M293.750 163.689 L 298.047 166.318 300.391 164.112 C 308.092 156.864,316.892 163.235,310.591 171.496 L 308.682 173.999 315.575 181.113 C 322.025 187.769,322.613 188.185,324.718 187.581 C 330.678 185.872,335.037 192.274,331.218 197.129 L 329.624 199.156 331.218 202.281 C 333.276 206.315,333.228 208.023,330.983 210.634 C 327.577 214.594,323.135 213.345,320.206 207.605 C 319.019 205.277,318.341 204.692,317.334 205.124 C 310.314 208.137,304.229 201.180,309.230 195.858 L 310.908 194.072 305.130 188.333 L 299.353 182.594 297.227 184.266 C 291.902 188.454,285.060 182.582,288.272 176.580 C 288.924 175.362,288.501 174.819,285.552 173.091 C 279.835 169.741,278.948 166.244,282.832 162.360 C 285.662 159.530,287.275 159.726,293.750 163.689 M192.969 305.330 C 192.969 360.898,193.039 359.997,188.261 365.794 C 177.222 379.185,156.397 372.308,153.871 354.437 C 152.086 341.811,139.267 343.498,140.005 356.261 C 141.835 387.857,185.789 398.278,202.115 370.986 C 207.036 362.760,207.031 362.826,207.031 306.086 L 207.031 254.688 200.000 254.688 L 192.969 254.688 192.969 305.330 M67.001 326.063 C 60.153 340.571,58.844 345.126,60.140 349.939 C 64.187 364.970,86.719 362.712,86.719 347.274 C 86.719 341.415,75.832 313.601,73.431 313.327 C 73.213 313.302,70.319 319.033,67.001 326.063 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No special cases found.</p>
                                    <p>Only <span class=" text-amber-900">calamity victims</span> can be listed here.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@script
    <script>
        const datepickerStart = document.getElementById('start-date');
        const datepickerEnd = document.getElementById('end-date');

        datepickerStart.addEventListener('changeDate', function(event) {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            $wire.dispatchSelf('start-change', {
                value: datepickerStart.value
            });
        });

        datepickerEnd.addEventListener('changeDate', function(event) {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            $wire.dispatchSelf('end-change', {
                value: datepickerEnd.value
            });
        });

        $wire.on('scroll-to-top', () => {
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
    </script>
@endscript
