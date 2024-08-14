<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, show: false, profileShow: false, rotation: 0, caretRotate: 0, dashboardHover: false, implementationsHover: false, umanagementHover: false, alogsHover: false, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    <style>
        /* Small screens */
        @media (max-width: 639px) {
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
        @media (min-width: 640px) and (max-width: 767px) {
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

        /* Large screens */
        @media (min-width: 768px) and (max-width: 1023px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 1rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1.125rem !important;
            }
        }

        /* Extra large screens */
        @media (min-width: 1024px) and (max-width: 1279px) {
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

        /* 2XL screens */
        @media (min-width: 1280px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 1rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1.25rem !important;
            }
        }
    </style>

    <livewire:sidebar.focal-bar wire:key="{{ str()->random(50) }}" />

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Time Dropdown --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Dashboard</h1>

                <div id="date-range-picker" date-rangepicker datepicker-autohide class="flex items-center">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-indigo-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="start-date" name="start" type="text" value="{{ $defaultStart }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date start">
                    </div>
                    <span class="mx-4 text-indigo-1100">to</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-indigo-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="end-date" name="end" type="text" value="{{ $defaultEnd }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date end">
                    </div>
                </div>
            </div>

            {{-- Project Counters --}}
            <div class="relative grid grid-cols-3 gap-x-2">
                <div class="relative flex items-center justify-start h-24 rounded bg-white">

                    <div class="flex items-center">
                        <div class="p-3 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="w-5 sm:w-8 h-5 sm:h-8" width="400" height="400" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M67.188 42.493 C 55.268 45.143,44.664 55.893,42.634 67.383 L 42.117 70.313 97.255 70.313 C 164.870 70.313,154.814 67.527,175.511 91.992 L 187.573 106.250 259.622 106.250 C 346.654 106.250,344.691 105.965,358.360 120.578 C 371.736 134.877,371.094 129.284,371.094 231.410 L 371.094 316.477 374.023 315.994 C 383.483 314.433,392.610 307.205,397.179 297.656 L 399.609 292.578 399.609 196.875 L 399.609 101.172 397.184 96.094 C 392.859 87.036,383.797 79.941,374.236 78.128 C 371.652 77.638,342.654 77.344,296.963 77.344 L 223.828 77.344 210.937 61.962 C 203.848 53.502,196.951 45.416,195.611 43.993 L 193.175 41.406 132.330 41.472 C 87.447 41.520,70.357 41.788,67.188 42.493 M20.832 85.503 C 11.892 89.184,6.577 94.211,2.560 102.789 L 0.391 107.422 0.187 219.384 C 0.008 318.163,0.126 331.832,1.184 335.474 C 4.454 346.720,14.411 355.508,26.477 357.796 C 32.285 358.898,325.528 358.898,331.335 357.796 C 343.404 355.508,353.359 346.720,356.630 335.469 C 357.686 331.834,357.806 320.017,357.626 237.348 L 357.422 143.359 354.927 138.093 C 351.698 131.277,346.067 125.645,339.251 122.417 L 333.984 119.922 257.749 119.531 L 181.515 119.141 166.684 101.563 L 151.853 83.984 88.622 83.805 L 25.391 83.626 20.832 85.503 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="flex flex-col items-start justify-center">
                            <h1 class="text-lg sm:text-xl text-indigo-1100 font-bold leading-tight">
                                {{ $projectCounters['total_implementations'] ?? 0 }}
                            </h1>
                            <p class="text-xs md:text-sm text-indigo-900 font-bold leading-tight">
                                Total Implementations
                            </p>
                        </div>
                    </div>
                </div>
                <div class="relative flex items-center justify-start h-24 rounded bg-white">

                    <div class="flex items-center">
                        <div class="p-3 text-green-900">

                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="400" class="w-5 sm:w-8 h-5 sm:h-8" height="400" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M137.109 10.047 C 133.498 12.278,133.085 12.900,118.359 38.281 C 110.756 51.387,103.954 62.773,103.244 63.584 C 101.102 66.032,98.377 66.763,69.208 72.721 C 29.037 80.927,32.121 76.705,36.747 117.164 L 40.117 146.643 38.613 149.447 C 37.786 150.989,29.551 160.945,20.313 171.570 C -1.134 196.237,-0.001 194.653,0.005 199.956 C 0.012 205.405,-0.940 204.053,20.313 228.783 C 42.665 254.792,40.780 248.504,36.717 283.517 L 33.373 312.333 35.069 315.836 C 37.636 321.138,39.974 321.941,71.094 328.205 C 88.604 331.729,99.746 334.339,101.318 335.286 C 103.236 336.441,107.128 342.475,118.286 361.594 C 139.465 397.882,134.865 396.377,172.120 379.207 C 193.699 369.262,199.044 367.084,201.052 367.419 C 202.407 367.645,215.005 373.135,229.047 379.618 C 256.453 392.272,257.984 392.729,263.175 389.807 C 266.571 387.896,265.949 388.829,282.403 360.938 C 296.460 337.110,296.990 336.322,300.037 334.747 C 301.133 334.179,314.318 331.194,329.336 328.113 C 360.255 321.769,362.419 321.025,364.904 315.891 L 366.621 312.345 363.242 283.130 C 359.179 248.009,356.970 255.116,380.425 227.846 C 400.999 203.926,400.000 205.356,400.000 199.835 C 400.000 194.669,401.311 196.493,379.259 170.984 C 367.961 157.915,360.854 149.053,360.546 147.652 C 360.273 146.409,361.508 132.837,363.291 117.492 C 368.012 76.864,370.898 80.847,330.828 72.704 C 295.882 65.602,299.043 67.302,288.874 50.133 C 263.273 6.909,265.096 9.395,258.555 8.767 C 255.095 8.434,253.072 9.228,228.374 20.611 C 213.813 27.322,201.045 32.812,200.000 32.812 C 198.955 32.812,186.276 27.363,171.825 20.703 C 143.808 7.790,141.774 7.166,137.109 10.047 M263.898 134.317 C 267.899 136.394,280.140 148.972,281.609 152.514 C 284.818 160.258,286.345 158.412,230.198 214.699 C 177.047 267.983,177.929 267.188,172.031 267.188 C 166.758 267.188,165.803 266.391,140.499 240.906 C 112.554 212.760,112.472 212.537,125.282 199.322 C 140.564 183.557,142.852 183.723,160.931 201.903 L 172.253 213.288 211.322 174.301 C 256.275 129.442,255.558 129.987,263.898 134.317 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="flex flex-col items-start justify-center">
                            <h1 class="text-lg sm:text-xl text-green-1100 font-bold leading-tight">
                                {{ $projectCounters['total_approved_assignments'] ?? 0 }}
                            </h1>
                            <p class="text-xs md:text-sm text-green-900 font-bold leading-tight">
                                Approved Assignments
                            </p>
                        </div>
                    </div>
                </div>
                <div class="relative flex items-center justify-start h-24 rounded bg-white">

                    <div class="flex items-center">
                        <div class="p-3 text-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="w-5 sm:w-8 h-5 sm:h-8" width="400" height="400" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M48.047 9.108 C 37.410 14.706,34.908 27.246,42.806 35.375 C 46.964 39.654,51.056 40.625,64.929 40.625 L 76.450 40.625 76.965 45.117 C 83.052 98.258,114.362 151.879,164.610 195.220 L 170.236 200.073 166.954 202.948 C 117.086 246.636,86.224 299.067,78.900 352.539 L 77.964 359.375 66.077 359.375 C 51.908 359.375,47.422 360.391,43.906 364.395 C 36.929 372.341,39.030 386.260,47.851 390.530 L 51.275 392.188 199.219 392.188 L 347.163 392.187 350.586 390.530 C 360.144 385.903,361.596 369.550,353.023 363.078 C 349.029 360.063,345.230 359.384,332.316 359.379 L 320.492 359.375 319.556 352.930 C 313.469 311.001,295.040 272.173,264.397 236.712 C 256.964 228.110,235.652 207.130,230.279 203.125 C 225.758 199.755,225.556 200.497,232.514 194.922 C 277.754 158.669,311.734 100.950,319.556 47.070 L 320.492 40.625 331.979 40.625 C 339.820 40.625,344.696 40.260,347.340 39.475 C 361.460 35.281,362.431 15.591,348.828 9.294 C 344.345 7.218,51.981 7.038,48.047 9.108 M288.635 43.555 C 286.560 55.768,279.736 78.425,274.341 91.016 L 271.997 96.484 200.000 96.484 L 128.003 96.484 125.659 91.016 C 120.264 78.425,113.440 55.768,111.365 43.555 L 110.867 40.625 200.000 40.625 L 289.133 40.625 288.635 43.555 M207.931 227.450 C 221.130 237.526,253.125 271.913,253.125 276.023 C 253.125 276.320,228.855 276.563,199.191 276.563 C 148.059 276.563,145.297 276.491,146.013 275.195 C 152.155 264.075,194.882 221.875,200.000 221.875 C 200.345 221.875,203.914 224.384,207.931 227.450 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="flex flex-col items-start justify-center">
                            <h1 class="text-lg sm:text-xl text-blue-1100 font-bold leading-tight">
                                {{ $projectCounters['total_pending_assignments'] ?? 0 }}
                            </h1>
                            <p class="text-xs md:text-sm text-blue-900 font-bold leading-tight">
                                Pending Assignments
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative grid w-full gap-x-2 lg:grid-cols-3">
                <div class="relative grid lg:col-span-2">

                    {{-- Summary of Beneficiaries Dropdown --}}
                    <div class="flex items-center sm:h-10 justify-between my-2 w-full">
                        <h1 class="text-xl font-bold ms-3">Summary of Beneficiaries
                        </h1>
                        <div class="relative w-52 z-20">
                            <div @click="show = !show ; rotation += 180"
                                class="w-full bg-indigo-900 text-indigo-50 hover:bg-indigo-800 text-sm font-semibold px-4 py-2 rounded-lg flex items-center justify-between transition duration-150 ease-in-out cursor-pointer">
                                {!! $currentImplementation !!}
                                <svg :class="{
                                    'rotate-0': rotation % 360 === 0,
                                    'rotate-180': rotation % 360 === 180,
                                }"
                                    class="w-2.5 h-2.5 ms-3 transition duration-300 ease-in-out" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </div>
                            <div x-show="show" @click.away="show = !show; rotation += 180"
                                :class="{
                                    'block': show === true,
                                    'hidden': show === false,
                                }"
                                class="hidden end-0 absolute text-indigo-1100 bg-white w-60 shadow-lg border border-indigo-100 rounded p-3 mt-2">
                                <div class="relative flex items-center justify-center py-1 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="absolute start-0 ps-2 w-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-500 ease-in-out pointer-events-none">
                                        <path fill-rule="evenodd"
                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <input id="searchProject" wire:model.live="searchProject" type="text"
                                        class="rounded w-full ps-8 text-sm text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-500 ease-in-out"
                                        placeholder="Search project number">
                                </div>
                                <ul class="mt-2 text-sm overflow-y-auto max-h-44">
                                    @foreach ($implementations as $key => $implementation)
                                        <li wire:key={{ $key }}>
                                            <a wire:click.prevent="updateCurrentImplementation({{ $key }})"
                                                @click="show= !show ; rotation += 180" wire:loading.attr="disabled"
                                                aria-label="{{ __('Implementation') }}"
                                                class="block px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:font-medium hover:bg-indigo-100 duration-300 ease-in-out cursor-pointer">{{ $implementation['project_num'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full sm:h-72 bg-white rounded p-3 mb-2">

                        <div class="flex justify-between mb-3">
                            <div class="flex justify-center items-center">

                                {{-- Title of the Chart --}}
                                <h5 class="text-xl font-bold leading-none text-gray-900  pe-1">Per Implementation</h5>

                                {{-- The "?" Popover --}}
                                <svg data-popover-target="chart-info" data-popover-placement="bottom" tabindex="-1"
                                    class="w-3.5 h-3.5 text-indigo-900  hover:text-indigo-600 focus:outline-none transition duration-150 ease-in-out  cursor-pointer ms-1"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                </svg>

                                {{-- Popover for the Info of this Chart --}}
                                <div data-popover id="chart-info" role="tooltip"
                                    class="absolute z-20 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72">
                                    <div class="p-3 space-y-2">
                                        <h3 class="font-semibold text-gray-900 ">Per Implementation (Male and Female)
                                        </h3>
                                        <p>These charts represent the total number of Males and Females per
                                            implementation. In each
                                            implementation, it is also divided by people with disability and senior
                                            citizens.
                                        </p>
                                        <h3 class="font-semibold text-gray-900 ">Download CSV</h3>
                                        <p>All the data represented here can be downloaded as CSV file format.</p>
                                    </div>
                                    <div data-popper-arrow></div>
                                </div>
                            </div>
                            <div>
                                <button type="button" data-tooltip-target="data-tooltip"
                                    data-tooltip-placement="bottom"
                                    class="hidden lg:inline-flex items-center justify-center text-gray-500 w-8 h-8  hover:bg-gray-100  focus:outline-none focus:ring-4 focus:ring-gray-200  rounded-lg text-sm"><svg
                                        class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 16 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3" />
                                    </svg><span class="sr-only">Download data</span>
                                </button>
                                <div id="data-tooltip" role="tooltip"
                                    class="absolute z-20 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip ">
                                    Download CSV
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>

                        <!-- Donut Charts -->
                        <div class="grid grid-cols-3 place-items-center place-content-center">
                            <div class="relative">
                                @if ($implementationCount['total_male'] === null && $implementationCount['total_female'] === null)
                                    <div
                                        class="bg-white absolute z-10 min-h-full min-w-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-12 sm:size-20 mb-4 text-gray-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            Empty set in the <br> overall values.
                                        </h2>
                                    </div>
                                @endif
                                <div wire:ignore id="overall-chart"></div>
                            </div>
                            <div class="relative">
                                @if ($implementationCount['total_pwd_male'] === null && $implementationCount['total_pwd_female'] === null)
                                    <div
                                        class="bg-white absolute z-10 min-h-full min-w-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-12 sm:size-20 mb-4 text-gray-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>

                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            Empty set in the <br> PWD values.
                                        </h2>
                                    </div>
                                @endif
                                <div wire:ignore id="pwd-chart"></div>
                            </div>
                            <div class="relative">
                                @if ($implementationCount['total_senior_male'] === null && $implementationCount['total_senior_female'] === null)
                                    <div
                                        class="bg-white absolute z-10 min-h-full min-w-full flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="size-12 sm:size-20 mb-4 text-gray-300"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>

                                        <h2 class="text-gray-500 text-xs sm:text-sm font-medium text-center">
                                            Empty set in the <br> senior citizen values.
                                        </h2>
                                    </div>
                                @endif
                                <div wire:ignore id="senior-chart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-2 sm:h-32 w-full">

                        {{-- Total Beneficiaries Count --}}
                        <div class="flex items-center justify-center h-32 rounded bg-white">

                            <div class="relative grid grid-rows-3 h-full w-full mx-3 text-xs sm:text-sm">
                                <div class="flex flex-row items-center justify-between ">
                                    <div class="flex items-center text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-6">
                                            <path fill-rule="evenodd"
                                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                                clip-rule="evenodd" />
                                            <path
                                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                                        </svg>
                                        <p class="mx-2 font-bold">Total Beneficiaries</p>
                                    </div>
                                    <div class="flex text-indigo-1100">
                                        <p>{{ $beneficiaryCounters['total_beneficiaries'] ?? 0 }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-row items-center justify-between">
                                    <div class="flex items-center text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" class="size-6"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M185.156 14.470 C 159.115 19.814,137.967 38.772,129.837 64.063 C 125.915 76.262,124.843 107.436,128.320 108.137 C 134.997 109.481,134.718 109.577,136.190 105.432 C 144.244 82.756,178.498 81.434,189.432 103.377 L 191.416 107.357 200.053 107.393 L 208.690 107.430 210.161 104.105 C 220.111 81.610,255.662 82.489,263.810 105.432 C 265.282 109.577,265.003 109.481,271.680 108.137 C 275.157 107.436,274.085 76.262,270.163 64.063 C 258.753 28.571,221.721 6.967,185.156 14.470 M119.922 46.856 C 109.885 49.437,104.118 55.725,101.896 66.513 C 99.671 77.311,99.470 114.063,101.636 114.063 C 102.033 114.063,103.788 113.026,105.537 111.759 C 107.286 110.492,109.920 109.059,111.390 108.573 L 114.063 107.691 114.063 92.604 C 114.063 73.252,116.143 62.017,121.767 50.993 C 123.504 47.589,123.853 46.037,122.852 46.174 C 122.529 46.218,121.211 46.525,119.922 46.856 M276.563 46.926 C 276.563 47.362,277.314 49.192,278.233 50.993 C 283.857 62.017,285.938 73.252,285.938 92.604 L 285.938 107.691 288.610 108.573 C 290.080 109.059,292.714 110.492,294.463 111.759 C 298.858 114.943,299.013 114.832,299.647 108.065 C 302.143 81.438,297.890 57.494,289.596 51.465 C 284.603 47.835,276.563 45.035,276.563 46.926 M155.152 101.771 C 140.569 110.112,144.976 131.868,161.553 133.371 C 180.469 135.086,187.875 110.124,171.004 101.516 C 166.740 99.341,159.188 99.462,155.152 101.771 M228.828 101.555 C 212.115 110.389,219.634 135.077,238.516 133.364 C 255.140 131.857,259.401 109.148,244.487 101.540 C 240.463 99.487,232.727 99.495,228.828 101.555 M115.316 120.343 C 101.470 124.486,104.271 144.656,119.015 146.987 C 125.115 147.951,125.487 148.649,128.128 164.091 C 132.356 188.816,135.767 194.069,157.190 208.832 C 192.189 232.952,207.811 232.952,242.810 208.832 C 264.233 194.069,267.644 188.816,271.872 164.091 C 274.514 148.645,274.884 147.951,280.995 146.985 C 293.363 145.030,298.064 132.364,289.657 123.648 C 286.008 119.864,283.191 119.214,275.153 120.299 C 266.652 121.446,265.625 121.736,265.625 122.995 C 265.625 125.648,259.822 135.320,256.408 138.357 C 240.024 152.932,213.347 145.169,208.411 124.389 C 207.389 120.085,206.430 119.531,200.000 119.531 C 194.085 119.531,192.402 120.253,191.959 122.980 C 187.294 151.725,146.800 153.793,135.456 125.866 L 133.676 121.484 127.190 120.576 C 119.416 119.488,118.249 119.465,115.316 120.343 M222.432 155.564 C 239.773 161.923,246.526 191.054,232.296 198.120 C 227.690 200.407,172.310 200.407,167.704 198.120 C 153.694 191.164,159.906 162.774,176.953 155.848 C 183.735 153.092,215.156 152.896,222.432 155.564 M180.821 167.505 C 175.749 169.714,172.669 175.565,172.661 183.008 L 172.656 186.719 200.000 186.719 L 227.344 186.719 227.339 183.008 C 227.331 175.455,224.216 169.648,219.007 167.472 C 215.440 165.981,184.256 166.009,180.821 167.505 M173.008 236.015 C 177.916 267.945,222.489 268.275,226.939 236.415 C 227.428 232.911,227.354 232.647,226.041 233.211 C 206.867 241.453,193.159 241.450,173.935 233.202 C 172.601 232.629,172.523 232.864,173.008 236.015 M151.563 244.710 C 147.480 246.008,144.035 247.160,143.907 247.271 C 143.423 247.689,146.255 258.509,148.915 266.406 C 157.573 292.106,175.943 319.939,195.335 336.739 L 199.863 340.661 205.887 335.370 C 223.052 320.289,239.614 296.125,248.735 272.849 C 251.402 266.046,256.250 250.036,256.250 248.035 C 256.250 247.259,241.647 242.188,239.412 242.188 C 238.992 242.188,237.993 244.033,237.192 246.289 C 225.568 279.022,180.421 281.717,164.851 250.608 C 163.128 247.166,161.719 243.864,161.719 243.269 C 161.719 241.767,160.095 241.998,151.563 244.710 M113.133 257.386 C 77.601 269.331,60.076 283.848,49.109 310.417 C 41.690 328.392,36.625 379.665,41.761 384.801 L 43.679 386.719 200.000 386.719 L 356.321 386.719 358.239 384.801 C 361.842 381.198,359.590 341.076,354.712 321.970 C 348.562 297.884,331.481 276.940,308.768 265.635 C 303.287 262.907,268.970 251.053,268.387 251.686 C 268.252 251.833,267.192 255.469,266.033 259.766 C 257.916 289.836,242.427 316.270,219.444 339.273 C 201.210 357.523,198.832 357.547,180.947 339.667 C 157.896 316.624,142.445 290.445,134.174 260.419 C 132.832 255.548,131.361 251.591,130.906 251.626 C 130.451 251.661,122.453 254.253,113.133 257.386 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>

                                        <p class="mx-2 font-bold">Total Senior Citizens</p>
                                    </div>
                                    <div class="flex text-indigo-1100">
                                        <p>{{ $beneficiaryCounters['total_senior_citizen_beneficiaries'] ?? 0 }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-row items-center justify-between">
                                    <div class="flex items-center text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" class="size-6"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M105.550 1.197 C 79.174 8.537,66.622 41.670,81.581 64.471 C 105.561 101.024,162.106 82.337,159.156 38.834 C 157.362 12.372,131.257 -5.957,105.550 1.197 M111.872 95.232 C 103.825 98.012,99.659 104.094,99.779 112.891 C 99.851 118.204,134.834 280.406,136.202 281.775 C 136.502 282.075,164.031 274.670,197.378 265.321 C 230.725 255.971,258.263 248.612,258.572 248.966 C 258.882 249.320,265.605 272.018,273.513 299.405 C 284.585 337.753,288.170 349.203,289.108 349.210 C 291.864 349.230,354.481 332.566,356.641 331.238 C 373.157 321.081,366.128 296.027,346.784 296.108 C 344.901 296.116,336.852 297.825,328.898 299.906 L 314.436 303.689 299.627 252.601 L 284.819 201.512 282.839 201.961 C 281.750 202.207,254.844 209.707,223.047 218.627 C 191.250 227.546,165.106 234.528,164.948 234.141 C 164.791 233.755,162.835 225.052,160.601 214.802 L 156.541 196.166 192.138 195.935 C 226.375 195.712,227.869 195.641,231.250 194.066 C 244.995 187.667,246.134 168.538,233.245 160.568 L 229.421 158.203 188.693 157.982 L 147.965 157.760 142.596 133.763 C 136.151 104.960,135.341 102.511,131.040 98.829 C 125.422 94.021,118.914 92.800,111.872 95.232 M94.132 188.946 C 26.843 225.633,13.366 313.960,66.890 367.485 C 132.997 433.592,247.417 393.276,257.421 300.352 C 257.876 296.123,250.946 267.873,248.968 265.895 C 248.765 265.692,241.882 267.392,233.673 269.672 L 218.747 273.819 219.334 277.730 C 226.373 324.581,184.582 367.776,137.916 361.882 C 69.977 353.301,48.041 266.552,103.969 227.631 C 106.634 225.776,108.951 224.122,109.118 223.955 C 109.285 223.788,107.653 215.273,105.492 205.033 C 103.331 194.793,101.563 186.307,101.563 186.176 C 101.563 185.354,98.899 186.347,94.132 188.946 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        <p class="mx-2 font-bold">Total PWDs</p>
                                    </div>
                                    <div class="flex text-indigo-1100">
                                        <p>{{ $beneficiaryCounters['total_pwd_beneficiaries'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="flex items-center justify-center h-32 rounded bg-white">

                            <div class="flex flex-col w-full h-full justify-evenly items-center">
                                <button
                                    class="w-32 sm:w-52 py-3 rounded bg-[#f1c40f] text-[#352B03] font-bold hover:bg-[#F3CC2D] focus:outline-none focus:border-transparent focus:ring focus:ring-[#F5D75F] active:bg-[#A0820A] active:text-[#504105] transition ease-in-out duration-150">
                                    PRINT
                                </button>

                                <button
                                    class="w-32 sm:w-52 py-3 rounded bg-[#e74c3c] text-[#33100D] font-bold hover:bg-[#EA6658] focus:outline-none focus:border-transparent focus:ring focus:ring-[#EF877D] active:bg-[#9A3228] active:text-[#4D1914] transition ease-in-out duration-150">
                                    EXPORT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Beneficiaries by Barangay --}}
                <div class="relative gap-x-2 mt-2 w-full">
                    <div class="flex flex-col h-[29.5rem] w-full rounded justify-between bg-white">
                        <div class="relative h-[90%]">
                            <div class="flex flex-row items-center justify-between my-2 mx-4">
                                <p class="text-xl font-bold text-indigo-1100">
                                    By Barangay
                                </p>

                                <p class="text-base px-4 rounded-lg font-bold bg-indigo-100 text-indigo-900">
                                    {{ $batchesCount }}
                                </p>
                            </div>

                            @if ($batchesCount !== 0)
                                @foreach ($batches as $key => $batch)
                                    <div wire:key="{{ $key }}"
                                        class="flex flex-row items-center rounded-lg bg-indigo-50 shadow-sm p-2 mx-4 my-2">
                                        <div class="flex flex-col w-full">
                                            {{-- Barangay Name --}}
                                            <p class="text-md mx-1 mb-2 font-bold">{{ $batch->barangay_name }}</p>
                                            <div class="flex flex-row items-center justify-between font-semibold mb-1">
                                                <div class="text-xs flex justify-between w-full mx-2">
                                                    <p class="text-[#e74c3c]">Total Male</p>
                                                    <p class="">{{ $batch->total_male }}</p>
                                                </div>
                                                <div class="text-xs flex justify-between w-full mx-2">
                                                    <p class="text-[#d4ac0d]">Total Female</p>
                                                    <p class="">{{ $batch->total_female }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-row items-center justify-between mb-1">
                                                <div class="text-xs flex justify-between w-full mx-2">
                                                    <p class="">Senior Citizens</p>
                                                    <p class="">{{ $batch->total_senior_male }}</p>
                                                </div>
                                                <div class="text-xs flex justify-between w-full mx-2">
                                                    <p class="">Senior Citizens</p>
                                                    <p class="">{{ $batch->total_senior_female }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-row items-center justify-between mb-1">
                                                <div class="text-xs flex justify-between w-full mx-2">
                                                    <p class="">PWDs</p>
                                                    <p class="">{{ $batch->total_pwd_male }}</p>
                                                </div>
                                                <div class="text-xs flex justify-between w-full mx-2">
                                                    <p class="">PWDs</p>
                                                    <p class="">{{ $batch->total_pwd_female }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class="bg-white absolute z-10 min-h-full min-w-full flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4 text-gray-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>

                                    <h2 class="text-gray-500 text-sm font-medium text-center">
                                        No batches or barangays <br> found in the records.
                                    </h2>
                                </div>
                            @endif
                        </div>
                        {{ $batches->links() }}
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
            console.log(event.target.value);
            $wire.dispatchSelf('start-change', {
                value: datepickerStart.value
            });
        });

        datepickerEnd.addEventListener('changeDate', function(event) {
            console.log(event.target.value);
            $wire.dispatchSelf('end-change', {
                value: datepickerEnd.value
            });
        });

        const anchorTags = document.querySelectorAll('#implementations-list a');
        anchorTags.forEach(anchor => {
            anchor.addEventListener('click', (event) => {
                event.preventDefault();
                // const value = anchor.getAttribute('data-value');
            });
        });

        let data = @json($implementationCount);

        let overallValues = [parseInt(data.total_male), parseInt(data.total_female)];
        let pwdValues = [parseInt(data.total_pwd_male), parseInt(data.total_pwd_female)];
        let seniorValues = [parseInt(data.total_senior_male), parseInt(data.total_senior_female)];

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
                                    offsetY: 20,
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
            options("Senior Citizens", seniorValues, "seniorDonut")
        );

        overall.render();
        pwd.render();
        senior.render();

        $wire.on('series-change', (data) => {
            const overallValues = [parseInt(data.overallValues[0]), parseInt(data.overallValues[1])];
            const pwdValues = [parseInt(data.pwdValues[0]), parseInt(data.pwdValues[1])];
            const seniorValues = [parseInt(data.seniorValues[0]), parseInt(data.seniorValues[1])];

            if (data.overallValues[0] == null || data.overallValues[1] == null) {
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
                overall.updateSeries(overallValues);
            }

            if (data.pwdValues[0] == null || data.pwdValues[1] == null) {
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
                pwd.updateSeries(pwdValues);
            }

            if (data.seniorValues[0] == null || data.seniorValues[1] == null) {
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
                senior.updateSeries(seniorValues);
            }
        });
    </script>
@endscript
