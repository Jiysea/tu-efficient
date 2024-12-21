<div x-cloak x-data="{ step: $wire.entangle('step'), errorPreviewModal: $wire.entangle('errorPreviewModal'), downloadSampleModal: $wire.entangle('downloadSampleModal') }"
    @keydown.window.escape="if(!errorPreviewModal && !downloadSampleModal) {$wire.resetImports(); importFileModal = false;}"
    x-show="importFileModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

    <!-- Modal -->
    <div x-show="importFileModal" x-trap.noscroll.noautofocus="importFileModal"
        class="relative h-full overflow-y-auto p-4 flex items-start justify-center select-none">

        <div class="w-full max-w-5xl">

            <!-- Modal content -->
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-lg font-semibold text-indigo-1100 ">
                        Import File
                    </h1>

                    <div class="flex items-center justify-center">

                        {{-- Loading State --}}
                        <div class="flex items-center justify-center me-4 z-50 text-indigo-900" wire:loading
                            wire:target="clearFiles, cancelUpload, viewError">
                            <svg class="size-6 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        {{-- Close Button --}}
                        <button type="button" @click="$wire.resetImports(); importFileModal = false;"
                            wire:loading.attr="disabled" wire:target="validateFile"
                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-200 ease-in-out">
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

                {{-- Stepper --}}
                <div class="flex justify-center items-center w-full my-4">
                    {{-- Step 1 --}}
                    <div x-show="step == 1" class="flex items-center justify-between">
                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-indigo-700 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-indigo-50"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M196.274 7.780 C 194.869 8.123,192.408 9.314,190.805 10.427 C 185.124 14.372,79.055 121.315,77.774 124.389 C 74.624 131.944,78.015 141.017,85.547 145.187 C 87.605 146.326,91.870 146.514,120.508 146.726 L 153.125 146.968 153.159 200.242 C 153.199 263.162,152.922 260.638,160.475 266.752 L 163.426 269.141 200.000 269.141 L 236.574 269.141 239.525 266.752 C 247.078 260.638,246.801 263.162,246.841 200.242 L 246.875 146.968 279.492 146.726 C 308.130 146.514,312.395 146.326,314.453 145.187 C 321.983 141.019,325.373 131.954,322.231 124.389 C 320.825 121.004,211.289 11.061,207.422 9.153 C 204.303 7.615,199.420 7.013,196.274 7.780 M16.998 269.596 C 10.751 271.069,3.628 277.436,1.254 283.669 C -1.021 289.644,-0.282 375.696,2.083 380.113 C 4.418 384.476,9.888 389.311,14.499 391.089 C 20.549 393.423,379.451 393.423,385.501 391.089 C 390.112 389.311,395.582 384.476,397.917 380.113 C 400.549 375.199,400.992 288.923,398.416 282.937 C 396.499 278.480,391.660 273.527,386.719 270.964 L 383.203 269.141 328.516 268.964 L 273.828 268.787 271.685 273.337 C 267.374 282.489,259.313 290.485,249.728 295.118 C 240.644 299.509,239.533 299.609,200.000 299.609 C 160.467 299.609,159.356 299.509,150.272 295.118 C 140.703 290.493,132.645 282.506,128.315 273.358 L 126.172 268.828 73.047 268.868 C 43.828 268.889,18.606 269.217,16.998 269.596 M300.543 334.095 C 305.665 338.240,306.641 340.147,306.641 346.013 C 306.641 362.041,286.431 366.675,279.102 352.328 C 272.367 339.146,289.150 324.874,300.543 334.095 M359.194 332.459 C 370.152 337.038,371.808 350.541,362.335 358.074 C 349.666 368.150,331.896 350.282,341.976 337.603 C 346.395 332.044,353.278 329.987,359.194 332.459 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-indigo-700 text-xs font-medium mt-1">Import</span>
                        </div>

                        <span class="border-b border-4 border-gray-100 w-12 sm:w-20 lg:w-32 mb-5"></span>

                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-gray-100 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-gray-500"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M213.281 1.564 C 102.902 18.614,43.033 135.874,93.826 235.531 C 96.792 241.350,99.219 246.704,99.219 247.428 C 99.219 248.153,79.412 268.627,55.203 292.927 C 11.520 336.776,8.206 340.404,3.949 349.035 C -11.497 380.355,19.645 411.497,50.965 396.051 C 59.596 391.794,63.224 388.480,107.073 344.797 C 131.373 320.588,151.847 300.781,152.572 300.781 C 153.296 300.781,158.650 303.208,164.469 306.174 C 288.636 369.458,427.679 259.799,395.675 123.828 C 376.531 42.491,296.336 -11.265,213.281 1.564 M254.297 44.094 C 351.710 59.291,391.460 176.109,322.871 245.620 C 253.396 316.028,134.331 276.960,119.550 178.906 C 107.927 101.800,177.570 32.124,254.297 44.094 M229.688 65.710 C 194.224 71.348,163.930 93.834,149.321 125.365 C 143.472 137.986,147.119 148.764,158.340 152.027 C 167.867 154.796,174.804 151.102,180.196 140.387 C 197.508 105.983,235.383 90.487,270.161 103.578 C 290.136 111.096,305.627 91.401,291.562 76.368 C 284.215 68.515,249.573 62.548,229.688 65.710 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-gray-500 text-xs font-medium mt-1">Validate</span>
                        </div>

                        <span class="border-b border-4 border-gray-100 w-12 sm:w-20 lg:w-32 mb-5"></span>

                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-gray-100 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-gray-500"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M180.078 0.509 C 57.151 13.726,-24.606 131.499,6.201 250.981 C 33.575 357.147,143.772 421.442,250.981 393.799 C 357.147 366.425,421.442 256.228,393.799 149.019 C 374.270 73.278,311.095 15.798,232.465 2.230 C 223.308 0.650,189.192 -0.471,180.078 0.509 M305.078 115.124 C 315.382 119.943,319.888 131.073,315.455 140.751 C 312.990 146.132,184.588 274.047,178.342 277.344 C 172.999 280.164,165.796 280.096,160.938 277.179 C 156.604 274.578,89.548 213.483,86.666 209.510 C 74.965 193.382,92.607 172.297,110.265 181.305 C 111.964 182.172,125.858 194.335,141.140 208.334 L 168.925 233.785 228.039 174.705 C 260.552 142.211,287.319 115.620,287.522 115.613 C 287.725 115.607,289.297 115.095,291.016 114.476 C 295.663 112.801,300.600 113.029,305.078 115.124 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-gray-500 text-xs font-medium mt-1">Finish</span>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div x-show="step == 2" class="flex items-center justify-between">
                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-indigo-700 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-indigo-50"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M196.274 7.780 C 194.869 8.123,192.408 9.314,190.805 10.427 C 185.124 14.372,79.055 121.315,77.774 124.389 C 74.624 131.944,78.015 141.017,85.547 145.187 C 87.605 146.326,91.870 146.514,120.508 146.726 L 153.125 146.968 153.159 200.242 C 153.199 263.162,152.922 260.638,160.475 266.752 L 163.426 269.141 200.000 269.141 L 236.574 269.141 239.525 266.752 C 247.078 260.638,246.801 263.162,246.841 200.242 L 246.875 146.968 279.492 146.726 C 308.130 146.514,312.395 146.326,314.453 145.187 C 321.983 141.019,325.373 131.954,322.231 124.389 C 320.825 121.004,211.289 11.061,207.422 9.153 C 204.303 7.615,199.420 7.013,196.274 7.780 M16.998 269.596 C 10.751 271.069,3.628 277.436,1.254 283.669 C -1.021 289.644,-0.282 375.696,2.083 380.113 C 4.418 384.476,9.888 389.311,14.499 391.089 C 20.549 393.423,379.451 393.423,385.501 391.089 C 390.112 389.311,395.582 384.476,397.917 380.113 C 400.549 375.199,400.992 288.923,398.416 282.937 C 396.499 278.480,391.660 273.527,386.719 270.964 L 383.203 269.141 328.516 268.964 L 273.828 268.787 271.685 273.337 C 267.374 282.489,259.313 290.485,249.728 295.118 C 240.644 299.509,239.533 299.609,200.000 299.609 C 160.467 299.609,159.356 299.509,150.272 295.118 C 140.703 290.493,132.645 282.506,128.315 273.358 L 126.172 268.828 73.047 268.868 C 43.828 268.889,18.606 269.217,16.998 269.596 M300.543 334.095 C 305.665 338.240,306.641 340.147,306.641 346.013 C 306.641 362.041,286.431 366.675,279.102 352.328 C 272.367 339.146,289.150 324.874,300.543 334.095 M359.194 332.459 C 370.152 337.038,371.808 350.541,362.335 358.074 C 349.666 368.150,331.896 350.282,341.976 337.603 C 346.395 332.044,353.278 329.987,359.194 332.459 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-indigo-700 text-xs font-medium mt-1">Import</span>
                        </div>

                        <span class="border-b border-4 border-indigo-700 w-12 sm:w-20 lg:w-32 mb-5"></span>

                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-indigo-700 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-indigo-50"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M213.281 1.564 C 102.902 18.614,43.033 135.874,93.826 235.531 C 96.792 241.350,99.219 246.704,99.219 247.428 C 99.219 248.153,79.412 268.627,55.203 292.927 C 11.520 336.776,8.206 340.404,3.949 349.035 C -11.497 380.355,19.645 411.497,50.965 396.051 C 59.596 391.794,63.224 388.480,107.073 344.797 C 131.373 320.588,151.847 300.781,152.572 300.781 C 153.296 300.781,158.650 303.208,164.469 306.174 C 288.636 369.458,427.679 259.799,395.675 123.828 C 376.531 42.491,296.336 -11.265,213.281 1.564 M254.297 44.094 C 351.710 59.291,391.460 176.109,322.871 245.620 C 253.396 316.028,134.331 276.960,119.550 178.906 C 107.927 101.800,177.570 32.124,254.297 44.094 M229.688 65.710 C 194.224 71.348,163.930 93.834,149.321 125.365 C 143.472 137.986,147.119 148.764,158.340 152.027 C 167.867 154.796,174.804 151.102,180.196 140.387 C 197.508 105.983,235.383 90.487,270.161 103.578 C 290.136 111.096,305.627 91.401,291.562 76.368 C 284.215 68.515,249.573 62.548,229.688 65.710 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-indigo-700 text-xs font-medium mt-1">Validate</span>
                        </div>

                        <span class="border-b border-4 border-gray-100 w-12 sm:w-20 lg:w-32 mb-5"></span>

                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-gray-100 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-gray-500"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M180.078 0.509 C 57.151 13.726,-24.606 131.499,6.201 250.981 C 33.575 357.147,143.772 421.442,250.981 393.799 C 357.147 366.425,421.442 256.228,393.799 149.019 C 374.270 73.278,311.095 15.798,232.465 2.230 C 223.308 0.650,189.192 -0.471,180.078 0.509 M305.078 115.124 C 315.382 119.943,319.888 131.073,315.455 140.751 C 312.990 146.132,184.588 274.047,178.342 277.344 C 172.999 280.164,165.796 280.096,160.938 277.179 C 156.604 274.578,89.548 213.483,86.666 209.510 C 74.965 193.382,92.607 172.297,110.265 181.305 C 111.964 182.172,125.858 194.335,141.140 208.334 L 168.925 233.785 228.039 174.705 C 260.552 142.211,287.319 115.620,287.522 115.613 C 287.725 115.607,289.297 115.095,291.016 114.476 C 295.663 112.801,300.600 113.029,305.078 115.124 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-gray-500 text-xs font-medium mt-1">Finish</span>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div x-show="step == 3" class="flex items-center justify-between">
                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-indigo-700 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-indigo-50"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M196.274 7.780 C 194.869 8.123,192.408 9.314,190.805 10.427 C 185.124 14.372,79.055 121.315,77.774 124.389 C 74.624 131.944,78.015 141.017,85.547 145.187 C 87.605 146.326,91.870 146.514,120.508 146.726 L 153.125 146.968 153.159 200.242 C 153.199 263.162,152.922 260.638,160.475 266.752 L 163.426 269.141 200.000 269.141 L 236.574 269.141 239.525 266.752 C 247.078 260.638,246.801 263.162,246.841 200.242 L 246.875 146.968 279.492 146.726 C 308.130 146.514,312.395 146.326,314.453 145.187 C 321.983 141.019,325.373 131.954,322.231 124.389 C 320.825 121.004,211.289 11.061,207.422 9.153 C 204.303 7.615,199.420 7.013,196.274 7.780 M16.998 269.596 C 10.751 271.069,3.628 277.436,1.254 283.669 C -1.021 289.644,-0.282 375.696,2.083 380.113 C 4.418 384.476,9.888 389.311,14.499 391.089 C 20.549 393.423,379.451 393.423,385.501 391.089 C 390.112 389.311,395.582 384.476,397.917 380.113 C 400.549 375.199,400.992 288.923,398.416 282.937 C 396.499 278.480,391.660 273.527,386.719 270.964 L 383.203 269.141 328.516 268.964 L 273.828 268.787 271.685 273.337 C 267.374 282.489,259.313 290.485,249.728 295.118 C 240.644 299.509,239.533 299.609,200.000 299.609 C 160.467 299.609,159.356 299.509,150.272 295.118 C 140.703 290.493,132.645 282.506,128.315 273.358 L 126.172 268.828 73.047 268.868 C 43.828 268.889,18.606 269.217,16.998 269.596 M300.543 334.095 C 305.665 338.240,306.641 340.147,306.641 346.013 C 306.641 362.041,286.431 366.675,279.102 352.328 C 272.367 339.146,289.150 324.874,300.543 334.095 M359.194 332.459 C 370.152 337.038,371.808 350.541,362.335 358.074 C 349.666 368.150,331.896 350.282,341.976 337.603 C 346.395 332.044,353.278 329.987,359.194 332.459 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-indigo-700 text-xs font-medium mt-1">Import</span>
                        </div>

                        <span class="border-b border-4 border-indigo-700 w-12 sm:w-20 lg:w-32 mb-5"></span>

                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-indigo-700 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-indigo-50"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M213.281 1.564 C 102.902 18.614,43.033 135.874,93.826 235.531 C 96.792 241.350,99.219 246.704,99.219 247.428 C 99.219 248.153,79.412 268.627,55.203 292.927 C 11.520 336.776,8.206 340.404,3.949 349.035 C -11.497 380.355,19.645 411.497,50.965 396.051 C 59.596 391.794,63.224 388.480,107.073 344.797 C 131.373 320.588,151.847 300.781,152.572 300.781 C 153.296 300.781,158.650 303.208,164.469 306.174 C 288.636 369.458,427.679 259.799,395.675 123.828 C 376.531 42.491,296.336 -11.265,213.281 1.564 M254.297 44.094 C 351.710 59.291,391.460 176.109,322.871 245.620 C 253.396 316.028,134.331 276.960,119.550 178.906 C 107.927 101.800,177.570 32.124,254.297 44.094 M229.688 65.710 C 194.224 71.348,163.930 93.834,149.321 125.365 C 143.472 137.986,147.119 148.764,158.340 152.027 C 167.867 154.796,174.804 151.102,180.196 140.387 C 197.508 105.983,235.383 90.487,270.161 103.578 C 290.136 111.096,305.627 91.401,291.562 76.368 C 284.215 68.515,249.573 62.548,229.688 65.710 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-indigo-700 text-xs font-medium mt-1">Validate</span>
                        </div>

                        <span class="border-b border-4 border-indigo-700 w-12 sm:w-20 lg:w-32 mb-5"></span>

                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-indigo-700 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-indigo-50"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M180.078 0.509 C 57.151 13.726,-24.606 131.499,6.201 250.981 C 33.575 357.147,143.772 421.442,250.981 393.799 C 357.147 366.425,421.442 256.228,393.799 149.019 C 374.270 73.278,311.095 15.798,232.465 2.230 C 223.308 0.650,189.192 -0.471,180.078 0.509 M305.078 115.124 C 315.382 119.943,319.888 131.073,315.455 140.751 C 312.990 146.132,184.588 274.047,178.342 277.344 C 172.999 280.164,165.796 280.096,160.938 277.179 C 156.604 274.578,89.548 213.483,86.666 209.510 C 74.965 193.382,92.607 172.297,110.265 181.305 C 111.964 182.172,125.858 194.335,141.140 208.334 L 168.925 233.785 228.039 174.705 C 260.552 142.211,287.319 115.620,287.522 115.613 C 287.725 115.607,289.297 115.095,291.016 114.476 C 295.663 112.801,300.600 113.029,305.078 115.124 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-indigo-700 text-xs font-medium mt-1">Finish</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Content --}}
                {{-- Step 1 --}}
                <div x-show="step == 1" class="flex flex-col items-center justify-center w-full p-4 md:p-5">

                    <div class="flex flex-col items-center justify-center w-3/4">
                        <p class="text-center whitespace-nowrap w-full text-red-500 z-10 h-2 mb-4 text-xs">
                            @error('file_path')
                                {{ $message }}
                            @enderror
                        </p>

                        {{-- File Dropzone --}}
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false; progress = 0;"
                            x-on:livewire-upload-cancel="uploading = false"
                            x-on:livewire-upload-error="uploading = false;"
                            x-on:livewire-upload-progress="progress = $event.detail.progress;"
                            class="relative flex flex-col items-center w-full justify-center">
                            {{-- Loading Black BG --}}
                            <div x-show="uploading"
                                class="absolute flex items-center justify-center rounded w-full h-full z-50 cursor-wait">
                                <div class="absolute inset-0 bg-black opacity-10 rounded size-full z-50">
                                    {{-- Darkness... --}}
                                </div>
                            </div>

                            <!-- Progress Bar && Loading Icon -->
                            <div x-show="uploading" class="absolute flex items-center justify-center w-2/6 z-40">
                                <div class="w-full bg-gray-300 rounded-lg h-2">
                                    <div class="h-full {{ $errors->has('file_path') ? 'bg-red-500' : 'bg-indigo-500' }} rounded-lg"
                                        x-bind:style="'width: ' + progress + '%'">
                                    </div>
                                </div>
                                <svg class="ms-2 size-5 {{ $errors->has('file_path') ? 'text-red-900' : 'text-indigo-900' }} animate-spin"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>

                            <label for="file_path" id="drop-zone"
                                class="flex flex-col items-center justify-center w-full h-32 border-dashed rounded border-2
                                {{ $errors->has('file_path')
                                    ? 'bg-red-50 hover:bg-orange-50 text-red-500 hover:text-orange-500 border-red-300 hover:border-orange-300'
                                    : 'text-gray-500 hover:text-indigo-500 bg-gray-50 hover:bg-indigo-50 border-gray-300 hover:border-indigo-300' }} 
                                    duration-500 ease-in-out cursor-pointer">

                                <div class="relative flex flex-col items-center justify-center py-6">
                                    {{-- Default Preview --}}
                                    <svg class="size-8 mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-xs "><span class="font-semibold">Click to
                                            upload</span> or drag and drop</p>
                                    <p class="text-xs ">XLSX or CSV</p>
                                </div>
                                <input id="file_path" wire.loading.attr="disabled" wire:model="file_path"
                                    type="file" accept=".xlsx, .csv" class="hidden" />
                            </label>

                            {{-- Cancel (X) button --}}
                            <span x-show="uploading" class="absolute top-2 right-2 inline-flex z-50">
                                <button
                                    class="text-gray-500 {{ $errors->has('file_path') ? 'hover:text-red-700' : 'hover:text-indigo-700' }} duration-200 ease-in-out"
                                    type="button" @click="$wire.cancelUpload('file_path');"><svg class="size-3"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </span>
                        </div>

                        <div class="flex flex-col items-center justify-center w-full">
                            {{-- Download Desc --}}
                            <p class="text-center w-full text-gray-500 z-10 mt-4 text-sm">
                                Please use the
                                <button type="button" @click="downloadSampleModal = !downloadSampleModal;"
                                    class="bg-indigo-100 text-indigo-700 hover:text-indigo-800 active:text-indigo-1000 
                                                rounded px-2 py-1 font-medium underline underline-offset-2 decoration-transparent hover:decoration-indigo-800 
                                                active:decoration-indigo-1000 duration-200 ease-in-out">
                                    sample XLSX format
                                </button>
                                for uploading files to avoid unnecessary errors.
                            </p>

                            @if (!$this->batch?->is_sectoral)
                                <p class="text-center w-full text-gray-500 z-10 mt-4 text-sm">
                                    Note that since this batch is non-sectoral, it will
                                    <strong>completely ignore</strong>
                                    the <span class="text-indigo-700 font-medium">District</span> and <span
                                        class="text-indigo-700 font-medium">BRGY.</span> fields as each
                                    non-sectoral batches
                                    are only tied to a single district and barangay.
                                </p>
                            @endif
                        </div>

                        {{-- File Preview --}}
                        <div class="relative flex flex-col items-center justify-start w-full mt-4 h-28">
                            @if ($file_path && !$errors->has('file_path'))
                                <span
                                    class="flex justify-between items-center font-medium rounded-md shadow z-50 text-xs p-2 w-full bg-zinc-300 hover:bg-indigo-800 text-zinc-700 hover:text-indigo-50 duration-200 ease-in-out">
                                    {{ $file_path->getClientOriginalName() }}

                                    {{-- X button --}}
                                    <button type="button" @click="$wire.clearFiles();"
                                        class="outline-none text-gray-500 hover:text-indigo-50 p-2 inline-flex justify-center items-center duration-200 ease-in-out">
                                        <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="w-full flex relative items-center justify-end">
                        <span class="flex items-center justify-center">

                            {{-- Next Button --}}
                            <button type="type" wire:loading.attr="disabled"
                                wire:target="nextStep, validateFile, file_path"
                                class="flex items-center justify-center px-3 py-1 font-bold disabled:opacity-75 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 rounded duration-200 ease-in-out"
                                wire:click="validateFile">
                                NEXT

                                {{-- Loading State for Changes --}}
                                <svg class="size-5 ms-2 animate-spin z-50" wire:loading
                                    wire:target="nextStep, validateFile" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Arrow Right --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ms-2" wire:loading.remove
                                    wire:target="nextStep, validateFile" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M295.703 104.354 C 288.091 108.313,284.738 117.130,287.918 124.830 C 288.731 126.797,298.250 136.876,317.407 156.055 L 345.695 184.375 178.190 184.375 L 10.684 184.375 7.316 186.349 C -2.632 192.179,-2.632 207.821,7.316 213.651 L 10.684 215.625 178.190 215.625 L 345.695 215.625 317.407 243.945 C 287.868 273.517,286.719 274.922,286.719 281.450 C 286.719 291.748,296.214 298.639,307.490 296.523 C 310.798 295.903,394.561 214.221,398.124 208.143 C 400.760 203.645,400.760 196.355,398.123 191.857 C 395.754 187.814,311.819 104.984,309.009 103.915 C 305.871 102.722,298.364 102.970,295.703 104.354 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div x-show="step == 2" class="flex flex-col gap-4 p-4 md:py-5 md:px-10 size-full">

                    {{-- Validation Progress --}}
                    @if ($importing && !$importFinished)
                        <div class="relative bg-white p-4 h-[50vh] min-w-full flex items-center justify-center"
                            wire:poll.1s="importProgress">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 opacity-65 animate-pulse"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p class="animate-pulse text-center">
                                    Processing the file. Validating the rows <br>
                                    Checking similarities. Please wait...
                                </p>
                            </div>
                        </div>
                    @elseif($importFinished && !$importFailed)
                        {{-- Header/Refresh Button --}}
                        <div class="w-full flex items-center justify-between">
                            <div class="flex flex-col justify-center">
                                <span class="text-sm sm:text-base font-semibold text-indigo-700">
                                    Importing Results
                                </span>
                                <span wire:poll.keep-alive.visible.1s="periodicallyCheckCache"
                                    class="text-xs sm:text-sm font-medium text-gray-500">
                                    Please note that these results will expire in
                                    {{ $cachedExpiration }}
                                    minutes. Refreshing or navigating other pages will lose this import session.
                                </span>
                            </div>

                            <button
                                class="flex items-center justify-center rounded text-indigo-700 hover:text-indigo-800 active:text-indigo-900 "
                                wire:click="refreshTime">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 animate-spin" wire:loading
                                    wire:target="refreshTime" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M184.034 33.711 C 114.672 39.820,55.552 90.170,38.271 157.852 C 34.351 173.206,40.480 183.520,53.558 183.577 C 62.787 183.617,68.089 178.632,71.401 166.797 C 102.421 55.968,246.260 30.959,309.547 125.391 L 314.259 132.422 296.387 132.813 C 279.134 133.190,278.395 133.271,275.035 135.156 C 263.607 141.568,263.607 158.432,275.035 164.844 L 278.516 166.797 316.797 166.797 L 355.078 166.797 358.768 164.628 C 361.161 163.221,363.221 161.161,364.628 158.768 L 366.797 155.078 366.797 116.797 L 366.797 78.516 364.844 75.035 C 358.432 63.608,341.534 63.608,335.171 75.035 C 333.490 78.054,333.179 79.796,332.827 88.179 L 332.422 97.841 327.228 91.694 C 292.487 50.579,238.897 28.879,184.034 33.711 M338.392 218.093 C 333.770 220.380,330.973 224.545,328.901 232.225 C 308.348 308.399,232.580 350.056,158.274 326.034 C 130.394 317.021,102.164 294.712,87.585 270.170 L 85.876 267.294 103.680 267.045 C 124.073 266.761,126.130 266.189,130.825 259.503 C 136.233 251.800,133.362 239.867,124.965 235.156 L 121.484 233.203 83.203 233.203 L 44.922 233.203 41.232 235.372 C 38.839 236.779,36.779 238.839,35.372 241.232 L 33.203 244.922 33.203 283.203 L 33.203 321.484 35.156 324.965 C 41.568 336.392,58.459 336.392,64.832 324.965 C 66.533 321.916,66.823 320.243,67.176 311.471 L 67.578 301.458 70.703 305.595 C 75.375 311.779,89.344 325.539,96.875 331.374 C 191.959 405.054,330.316 359.016,361.388 243.359 C 366.623 223.874,354.228 210.254,338.392 218.093 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" wire:loading.remove
                                    wire:target="refreshTime" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M184.034 33.711 C 114.672 39.820,55.552 90.170,38.271 157.852 C 34.351 173.206,40.480 183.520,53.558 183.577 C 62.787 183.617,68.089 178.632,71.401 166.797 C 102.421 55.968,246.260 30.959,309.547 125.391 L 314.259 132.422 296.387 132.813 C 279.134 133.190,278.395 133.271,275.035 135.156 C 263.607 141.568,263.607 158.432,275.035 164.844 L 278.516 166.797 316.797 166.797 L 355.078 166.797 358.768 164.628 C 361.161 163.221,363.221 161.161,364.628 158.768 L 366.797 155.078 366.797 116.797 L 366.797 78.516 364.844 75.035 C 358.432 63.608,341.534 63.608,335.171 75.035 C 333.490 78.054,333.179 79.796,332.827 88.179 L 332.422 97.841 327.228 91.694 C 292.487 50.579,238.897 28.879,184.034 33.711 M338.392 218.093 C 333.770 220.380,330.973 224.545,328.901 232.225 C 308.348 308.399,232.580 350.056,158.274 326.034 C 130.394 317.021,102.164 294.712,87.585 270.170 L 85.876 267.294 103.680 267.045 C 124.073 266.761,126.130 266.189,130.825 259.503 C 136.233 251.800,133.362 239.867,124.965 235.156 L 121.484 233.203 83.203 233.203 L 44.922 233.203 41.232 235.372 C 38.839 236.779,36.779 238.839,35.372 241.232 L 33.203 244.922 33.203 283.203 L 33.203 321.484 35.156 324.965 C 41.568 336.392,58.459 336.392,64.832 324.965 C 66.533 321.916,66.823 320.243,67.176 311.471 L 67.578 301.458 70.703 305.595 C 75.375 311.779,89.344 325.539,96.875 331.374 C 191.959 405.054,330.316 359.016,361.388 243.359 C 366.623 223.874,354.228 210.254,338.392 218.093 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>

                        {{-- Results || Tables --}}
                        <div
                            class="flex flex-col gap-3 h-[50vh] px-5 overflow-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            {{-- Successful Inserts --}}
                            <div x-data="{ expanded: false }" class="w-full flex flex-col gap-4">

                                <button type="button" @click="expanded = !expanded;"
                                    class="w-full flex flex-1 items-center justify-between px-4 py-3 rounded border {{ sizeof($successResults) > 0 ? 'border-indigo-300 text-indigo-1100' : 'border-gray-300 text-gray-950' }} text-xs font-medium">
                                    <p>
                                        Successful Records <span
                                            class="ms-2 rounded p-1.5 {{ sizeof($successResults) > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }} font-medium text-xs">{{ sizeof($successResults) }}</span>
                                    </p>

                                    {{-- Caret Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>


                                {{-- Table && Description --}}
                                <div x-show="expanded"
                                    class="flex flex-col pt-2 rounded border border-indigo-300 bg-indigo-50 text-indigo-1100">
                                    <span class="px-2 text-xs">
                                        These are rows that were successfully inserted into the database with no errors,
                                        perfect duplicates,
                                        and ineligible beneficiaries.
                                    </span>
                                    @if (!empty($successResults))

                                        {{-- Table --}}
                                        <div
                                            class="relative min-h-56 max-h-56 rounded-b border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                        border-indigo-300 text-indigo-1100 scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                            <table class="relative w-full text-sm text-left select-auto">
                                                <thead
                                                    class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-indigo-500 text-indigo-50">
                                                    <tr>
                                                        <th scope="col" class="p-2">
                                                            #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            ext.
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            birthdate
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            barangay
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            city / municipality
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            province
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            district
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            contact #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            occupation
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            avg. monthly income
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            type of id
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            id #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            e-payment account #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            beneficiary type
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            sex
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            civil status
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            age
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            dependent
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            interested in self or wage employment?
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            skills training needed
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            pwd
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse ext. name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs relative">
                                                    @foreach ($successResults as $key => $result)
                                                        <tr wire:key='success-{{ $key }}'
                                                            class="relative whitespace-nowrap hover:bg-indigo-100">
                                                            <td class="p-2 font-medium">
                                                                {{ $result['row'] - 11 }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['extension_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['birthdate'] ? \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['barangay_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['city_municipality'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['province'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['district'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['contact_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['occupation'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $this->checkValidAvgIncome($result['avg_monthly_income']) ? '₱' . \App\Services\MoneyFormat::mask($result['avg_monthly_income']) : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['type_of_id'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['id_number'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['e_payment_acc_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['beneficiary_type'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['sex'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['civil_status'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['age'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['dependent'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['self_employment'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['skills_training'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['is_pwd'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_extension_name'] ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div x-show="expanded"
                                            class="relative bg-white mt-2 h-56 min-w-full flex items-center justify-center">
                                            <div
                                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-indigo-500 bg-indigo-50 border-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-12 sm:size-20 mb-4 opacity-65"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                <p class="">No rows were successfully added to the database.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Perfect Duplicates --}}
                            <div x-data="{ expanded: false }" class="w-full flex flex-col gap-4">

                                <button type="button" @click="expanded = !expanded;"
                                    class="w-full flex flex-1 items-center justify-between px-4 py-3 rounded border {{ sizeof($perfectResults) > 0 ? 'border-rose-300 text-rose-950' : 'border-gray-300 text-gray-950' }} text-xs font-medium">
                                    <p>
                                        Rows with Perfect Duplicates <span
                                            class="ms-2 rounded p-1.5 {{ sizeof($perfectResults) > 0 ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-700' }} font-medium text-xs">{{ sizeof($perfectResults) }}</span>
                                    </p>

                                    {{-- Caret Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>


                                {{-- Table && Description --}}
                                <div x-show="expanded"
                                    class="flex flex-col pt-2 rounded border border-rose-300 bg-rose-50 text-rose-950">
                                    <span class="px-2 text-xs">
                                        These are rows that are detected to have perfect (100% similarity) duplicates
                                        from the database.
                                        If you think these rows are not imported by mistake, you could edit each row and
                                        add a special case to it.
                                    </span>
                                    @if (!empty($perfectResults))

                                        {{-- Table --}}
                                        <div
                                            class="relative min-h-56 max-h-56 rounded-b border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                        border-rose-300 text-rose-950 scrollbar-track-rose-50 scrollbar-thumb-rose-700">
                                            <table class="relative w-full text-sm text-left select-auto">
                                                <thead
                                                    class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-rose-500 text-rose-50">
                                                    <tr>
                                                        <th scope="col" class="p-2">
                                                            #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            ext.
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            birthdate
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            barangay
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            city / municipality
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            province
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            district
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            contact #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            occupation
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            avg. monthly income
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            type of id
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            id #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            e-payment account #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            beneficiary type
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            sex
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            civil status
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            age
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            dependent
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            interested in self or wage employment?
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            skills training needed
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            pwd
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse ext. name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs relative">
                                                    @foreach ($perfectResults as $key => $result)
                                                        <tr wire:key='perfect-{{ $key }}'
                                                            class="relative whitespace-nowrap hover:bg-rose-100">
                                                            <td class="p-2 font-medium">
                                                                {{ $result['row'] - 11 }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['extension_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['birthdate'] ? \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['barangay_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['city_municipality'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['province'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['district'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['contact_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['occupation'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $this->checkValidAvgIncome($result['avg_monthly_income']) ? '₱' . \App\Services\MoneyFormat::mask($result['avg_monthly_income']) : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['type_of_id'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['id_number'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['e_payment_acc_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['beneficiary_type'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['sex'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['civil_status'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['age'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['dependent'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['self_employment'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['skills_training'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['is_pwd'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_extension_name'] ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div x-show="expanded"
                                            class="relative bg-white mt-2 h-56 min-w-full flex items-center justify-center">
                                            <div
                                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-rose-500 bg-rose-50 border-rose-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-12 sm:size-20 mb-4 opacity-65"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                <p class="">No rows were successfully added to the database.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- With Errors --}}
                            <div x-data="{ expanded: false }" class="w-full flex flex-col gap-4">

                                <button type="button" @click="expanded = !expanded;"
                                    class="w-full flex flex-1 items-center justify-between px-4 py-3 rounded border {{ sizeof($errorResults) > 0 ? 'border-red-300 text-red-950' : 'border-gray-300 text-gray-950' }} text-xs font-medium">
                                    <p>
                                        Rows with Errors <span
                                            class="ms-2 rounded p-1.5 {{ sizeof($errorResults) > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }} font-medium text-xs">{{ sizeof($errorResults) }}</span>
                                    </p>

                                    {{-- Caret Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Table && Description --}}
                                <div x-show="expanded"
                                    class="flex flex-col pt-2 rounded border border-red-300 bg-red-50 text-red-950">
                                    <span class="px-2 text-xs">
                                        These are rows that were detected as having errors on their fields like
                                        having symbols on their name, text on average monthly salary, wrong birthdate
                                        format, etc.
                                        Make sure to follow the format in your STIF file before uploading it.
                                    </span>
                                    @if (!empty($errorResults))

                                        {{-- Table --}}
                                        <div
                                            class="relative min-h-56 max-h-56 rounded-b border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                        border-red-300 text-red-1100 scrollbar-track-red-50 scrollbar-thumb-red-700">
                                            <table class="relative w-full text-sm text-left select-auto">
                                                <thead
                                                    class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-red-500 text-red-50">
                                                    <tr>
                                                        <th scope="col" class="p-2">
                                                            #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            ext.
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            birthdate
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            barangay
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            city / municipality
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            province
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            district
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            contact #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            occupation
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            avg. monthly income
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            type of id
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            id #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            e-payment account #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            beneficiary type
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            sex
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            civil status
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            age
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            dependent
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            interested in self or wage employment?
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            skills training needed
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            pwd
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse ext. name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs relative">
                                                    @foreach ($errorResults as $key => $result)
                                                        <tr wire:key='error-{{ $key }}'
                                                            @if (
                                                                $cachedResults &&
                                                                    ($batchId ? decrypt($batchId) : null) ===
                                                                        ($cachedResults['batches_id'] ? decrypt($cachedResults['batches_id']) : null)) @dblclick="$wire.viewError('{{ encrypt($result['row']) }}'); $dispatch('openError');" @endif
                                                            class="relative whitespace-nowrap hover:bg-red-100 cursor-pointer">
                                                            <td class="p-2 font-medium">
                                                                {{ $result['row'] - 11 }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['extension_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['birthdate'] ? \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['barangay_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['city_municipality'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['province'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['district'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['contact_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['occupation'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $this->checkValidAvgIncome($result['avg_monthly_income']) ? '₱' . \App\Services\MoneyFormat::mask($result['avg_monthly_income']) : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['type_of_id'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['id_number'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['e_payment_acc_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['beneficiary_type'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['sex'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['civil_status'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['age'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['dependent'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['self_employment'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['skills_training'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['is_pwd'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_extension_name'] ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div x-show="expanded"
                                            class="relative bg-white mt-2 h-56 min-w-full flex items-center justify-center">
                                            <div
                                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-red-500 bg-red-50 border-red-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-12 sm:size-20 mb-4 opacity-65"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                <p class="">No errors found from the imported rows.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- With Similarities --}}
                            <div x-data="{ expanded: false }" class="w-full flex flex-col gap-4">

                                <button type="button" @click="expanded = !expanded;"
                                    class="w-full flex flex-1 items-center justify-between px-4 py-3 rounded border {{ sizeof($similarityResults) > 0 ? 'border-amber-300 text-amber-950' : 'border-gray-300 text-gray-950' }} text-xs font-medium">
                                    <p>
                                        Rows with Possible Duplicates <span
                                            class="ms-2 rounded p-1.5 {{ sizeof($similarityResults) > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }} font-medium text-xs">{{ sizeof($similarityResults) }}</span>
                                    </p>

                                    {{-- Caret Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Table && Description --}}
                                <div x-show="expanded"
                                    class="flex flex-col pt-2 rounded border border-amber-300 bg-amber-50 text-amber-950">
                                    <span class="px-2 text-xs">
                                        These are rows that were detected as having possible duplicates from the
                                        database on a certain threshold.
                                        It is possible to insert, delete or ignore these rows.
                                    </span>
                                    @if (!empty($similarityResults))
                                        {{-- Table --}}
                                        <div
                                            class="relative min-h-56 max-h-56 rounded-b border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                        border-amber-300 text-amber-950 scrollbar-track-amber-50 scrollbar-thumb-amber-700">
                                            <table class="relative w-full text-sm text-left select-auto">
                                                <thead
                                                    class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-amber-500 text-amber-50">
                                                    <tr>
                                                        <th scope="col" class="p-2">
                                                            #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            ext.
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            birthdate
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            barangay
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            city / municipality
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            province
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            district
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            contact #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            occupation
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            avg. monthly income
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            type of id
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            id #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            e-payment account #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            beneficiary type
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            sex
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            civil status
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            age
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            dependent
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            interested in self or wage employment?
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            skills training needed
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            pwd
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse ext. name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs relative">
                                                    @foreach ($similarityResults as $key => $result)
                                                        <tr wire:key='similarity-{{ $key }}'
                                                            class="relative whitespace-nowrap hover:bg-amber-100">
                                                            <td class="p-2 font-medium">
                                                                {{ $result['row'] - 11 }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['extension_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['birthdate'] ? \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['barangay_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['city_municipality'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['province'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['district'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['contact_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['occupation'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $this->checkValidAvgIncome($result['avg_monthly_income']) ? '₱' . \App\Services\MoneyFormat::mask($result['avg_monthly_income']) : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['type_of_id'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['id_number'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['e_payment_acc_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['beneficiary_type'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['sex'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['civil_status'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['age'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['dependent'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['self_employment'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['skills_training'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['is_pwd'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_extension_name'] ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div x-show="expanded"
                                            class="relative bg-white mt-2 h-56 min-w-full flex items-center justify-center">
                                            <div
                                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-amber-500 bg-amber-50 border-amber-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-12 sm:size-20 mb-4 opacity-65"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                <p class="">No possible duplicates detected from the imported
                                                    rows.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Ineligible Beneficiaries --}}
                            <div x-data="{ expanded: false }" class="w-full flex flex-col gap-4">

                                <button type="button" @click="expanded = !expanded;"
                                    class="w-full flex flex-1 items-center justify-between px-4 py-3 rounded border {{ sizeof($ineligibleResults) > 0 ? 'border-orange-300 text-orange-950' : 'border-gray-300 text-gray-950' }} text-xs font-medium">
                                    <p>
                                        Rows with Ineligible Beneficiaries <span
                                            class="ms-2 rounded p-1.5 {{ sizeof($ineligibleResults) > 0 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }} font-medium text-xs">{{ sizeof($ineligibleResults) }}</span>
                                    </p>

                                    {{-- Caret Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Table && Description --}}
                                <div x-show="expanded"
                                    class="flex flex-col pt-2 rounded border border-orange-300 bg-orange-50 text-orange-950">
                                    <span class="px-2 text-xs">
                                        These rows were either applied two (2) times as beneficiary this year, already
                                        under on the same
                                        implementation, or currently applied on a pending implementation project.
                                        As it stands, it is not possible to insert these rows under any circumstances.
                                    </span>
                                    @if (!empty($ineligibleResults))
                                        {{-- Table --}}
                                        <div
                                            class="relative min-h-56 max-h-56 rounded-b border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                        border-orange-300 text-orange-1100 scrollbar-track-orange-50 scrollbar-thumb-orange-700">
                                            <table class="relative w-full text-sm text-left select-auto">
                                                <thead
                                                    class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-orange-500 text-orange-50">
                                                    <tr>
                                                        <th scope="col" class="p-2">
                                                            #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            ext.
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            birthdate
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            barangay
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            city / municipality
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            province
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            district
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            contact #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            occupation
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            avg. monthly income
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            type of id
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            id #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            e-payment account #
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            beneficiary type
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            sex
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            civil status
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            age
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            dependent
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            interested in self or wage employment?
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            skills training needed
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            pwd
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse first name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse middle name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse last name
                                                        </th>
                                                        <th scope="col" class="p-2">
                                                            spouse ext. name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-xs relative">
                                                    @foreach ($ineligibleResults as $key => $result)
                                                        <tr wire:key='ineligible-{{ $key }}'
                                                            class="relative whitespace-nowrap hover:bg-orange-100">
                                                            <td class="p-2 font-medium">
                                                                {{ $result['row'] - 11 }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['extension_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['birthdate'] ? \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['barangay_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['city_municipality'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['province'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['district'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['contact_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['occupation'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $this->checkValidAvgIncome($result['avg_monthly_income']) ? '₱' . \App\Services\MoneyFormat::mask($result['avg_monthly_income']) : '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['type_of_id'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['id_number'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['e_payment_acc_num'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['beneficiary_type'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['sex'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['civil_status'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['age'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['dependent'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['self_employment'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['skills_training'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium capitalize">
                                                                {{ $result['is_pwd'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_first_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_middle_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_last_name'] ?? '-' }}
                                                            </td>
                                                            <td class="p-2 font-medium">
                                                                {{ $result['spouse_extension_name'] ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div x-show="expanded"
                                            class="relative bg-white mt-2 h-56 min-w-full flex items-center justify-center">
                                            <div
                                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-orange-500 bg-orange-50 border-orange-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-12 sm:size-20 mb-4 opacity-65"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                                <p class="">No ineligible beneficiaries found from the imported
                                                    rows.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($importFinished && $importFailed)
                        <div class="relative bg-white p-4 h-[50vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-red-500 bg-red-50 border-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 opacity-65 animate-pulse"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p class="text-center">
                                    An unknown error has occured while processing the file. <br>
                                    Please make sure you imported the correct file and try again.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Modal Footer --}}
                    <div
                        class="w-full gap-6 flex relative items-center {{ $cachedResults &&
                        ($batchId ? decrypt($batchId) : null) !== ($cachedResults['batches_id'] ? decrypt($cachedResults['batches_id']) : null)
                            ? 'justify-between'
                            : 'justify-end' }}">

                        @if (
                            $cachedResults &&
                                ($batchId ? decrypt($batchId) : null) !==
                                    ($cachedResults['batches_id'] ? decrypt($cachedResults['batches_id']) : null))
                            <span
                                class="flex items-center py-2 px-4 rounded border text-xs font-medium border-red-300 bg-red-100 text-red-700">You
                                are in a different batch. Some functions may not work. (Original Batch:
                                {{ $this->origBatch?->batch_num }})</span>
                        @endif

                        {{-- Next Button --}}
                        <span class="flex items-center justify-center">
                            <button type="button" wire:loading.attr="disabled" wire:target="nextStep"
                                @if (
                                    $importing ||
                                        $importFailed ||
                                        ($cachedResults &&
                                            ($batchId ? decrypt($batchId) : null) !==
                                                ($cachedResults['batches_id'] ? decrypt($cachedResults['batches_id']) : null))) disabled @endif
                                class="flex items-center justify-center px-3 py-1 font-bold disabled:opacity-75 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 rounded"
                                wire:click="finishImport">
                                NEXT

                                {{-- Loading State for Changes --}}
                                <svg class="size-4 ms-2 animate-spin z-50" wire:loading
                                    wire:target="nextStep, finishImport" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Arrow Right --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                    wire:loading.class="hidden" wire:target="nextStep, finishImport"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M295.703 104.354 C 288.091 108.313,284.738 117.130,287.918 124.830 C 288.731 126.797,298.250 136.876,317.407 156.055 L 345.695 184.375 178.190 184.375 L 10.684 184.375 7.316 186.349 C -2.632 192.179,-2.632 207.821,7.316 213.651 L 10.684 215.625 178.190 215.625 L 345.695 215.625 317.407 243.945 C 287.868 273.517,286.719 274.922,286.719 281.450 C 286.719 291.748,296.214 298.639,307.490 296.523 C 310.798 295.903,394.561 214.221,398.124 208.143 C 400.760 203.645,400.760 196.355,398.123 191.857 C 395.754 187.814,311.819 104.984,309.009 103.915 C 305.871 102.722,298.364 102.970,295.703 104.354 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                        </path>
                                    </g>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div x-show="step == 3" class="p-4 md:p-5">

                    {{-- Modal Content --}}
                    <div class="flex flex-col items-center justify-center gap-6 mb-20">
                        <span class="flex items-center justify-center mt-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-20 sm:size-32 text-indigo-700"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M180.078 0.509 C 57.151 13.726,-24.606 131.499,6.201 250.981 C 33.575 357.147,143.772 421.442,250.981 393.799 C 357.147 366.425,421.442 256.228,393.799 149.019 C 374.270 73.278,311.095 15.798,232.465 2.230 C 223.308 0.650,189.192 -0.471,180.078 0.509 M305.078 115.124 C 315.382 119.943,319.888 131.073,315.455 140.751 C 312.990 146.132,184.588 274.047,178.342 277.344 C 172.999 280.164,165.796 280.096,160.938 277.179 C 156.604 274.578,89.548 213.483,86.666 209.510 C 74.965 193.382,92.607 172.297,110.265 181.305 C 111.964 182.172,125.858 194.335,141.140 208.334 L 168.925 233.785 228.039 174.705 C 260.552 142.211,287.319 115.620,287.522 115.613 C 287.725 115.607,289.297 115.095,291.016 114.476 C 295.663 112.801,300.600 113.029,305.078 115.124 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </span>

                        <span class="text-base font-medium text-indigo-1100">
                            Successfully imported and validated the file!
                        </span>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="w-full flex relative items-center justify-end">

                        {{-- Next Button --}}
                        <span class="flex items-center justify-center">
                            <button type="button" wire:loading.attr="disabled"
                                class="flex items-center justify-center px-3 py-1 font-bold bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 rounded"
                                @click="$wire.resetImports(); importFileModal = false;">
                                FINISH

                                {{-- Loading State for Changes --}}
                                <svg class="size-4 ms-2 animate-spin z-50" wire:loading wire:target="resetImports"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Check Mark Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2" wire:loading.remove
                                    wire:target="resetImports" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M180.078 0.509 C 57.151 13.726,-24.606 131.499,6.201 250.981 C 33.575 357.147,143.772 421.442,250.981 393.799 C 357.147 366.425,421.442 256.228,393.799 149.019 C 374.270 73.278,311.095 15.798,232.465 2.230 C 223.308 0.650,189.192 -0.471,180.078 0.509 M305.078 115.124 C 315.382 119.943,319.888 131.073,315.455 140.751 C 312.990 146.132,184.588 274.047,178.342 277.344 C 172.999 280.164,165.796 280.096,160.938 277.179 C 156.604 274.578,89.548 213.483,86.666 209.510 C 74.965 193.382,92.607 172.297,110.265 181.305 C 111.964 182.172,125.858 194.335,141.140 208.334 L 168.925 233.785 228.039 174.705 C 260.552 142.211,287.319 115.620,287.522 115.613 C 287.725 115.607,289.297 115.095,291.016 114.476 C 295.663 112.801,300.600 113.029,305.078 115.124 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                        </path>
                                    </g>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>

                {{-- Edit Beneficiary Modal --}}
                {{-- <livewire.focal.implementations.edit-beneficiary-modal :$selectedSheetIndex /> --}}

                {{-- Error Preview Modal --}}
                <livewire:focal.implementations.error-preview-modal :$errorId />

                {{-- Download Sample Format Modal --}}
                <div x-cloak x-show="downloadSampleModal" @keydown.window.escape="downloadSampleModal = false"
                    class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto  backdrop-blur-sm z-50">

                    <!-- Modal -->
                    <div x-show="downloadSampleModal" x-trap.noscroll.noautofocus="downloadSampleModal"
                        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

                        <div class="relative">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-md shadow">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-lg font-semibold text-indigo-1100 ">
                                        Download STIF File
                                    </h1>

                                    <div class="flex items-center justify-center">
                                        {{-- Loading State --}}
                                        <div class="flex items-center justify-start me-4 z-50 text-indigo-900"
                                            wire:loading wire:target="exportSample">
                                            <svg class="size-7 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                        <button type="button" @click="downloadSampleModal = false;"
                                            class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-200 ease-in-out">
                                            <svg class="size-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                </div>

                                <hr class="">

                                <!-- Modal Body -->
                                <div class="flex flex-col items-center gap-4 w-full p-5 md:py-5 md:px-10">

                                    <div class="w-full relative pb-1">
                                        <label for="slots_allocated"
                                            class="block mb-1 font-medium text-sm text-indigo-1100">
                                            How
                                            many slots/rows
                                            you
                                            want to generate?</label>
                                        <input type="number" inputmode="numeric" id="slots_allocated"
                                            wire:model.live="slots_allocated" autocomplete="off"
                                            class="text-xs border w-full p-2.5 rounded {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                            placeholder="0">
                                        @error('slots_allocated')
                                            <p class="mt-1 text-red-500 absolute left-2 top-full z-10 text-xs">
                                                {{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Modal Footer --}}
                                    <div class="w-full flex items-center justify-end mb-2">
                                        <button type="button" wire:click="exportSample"
                                            class="flex items-center justify-center text-sm font-bold px-3 py-2 rounded duration-200 ease-in-out outline-none text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
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
</div>
@script
    <script>
        const inputFile = document.getElementById('file_path');
        const dropZone = document.getElementById('drop-zone');
        let initialError = dropZone.classList.contains('bg-red-50'); // Check if error styles exist
        const errorStyles = ['bg-red-50', 'hover:bg-orange-50', 'text-red-500', 'hover:text-orange-500', 'border-red-300',
            'hover:border-orange-300'
        ];
        const defaultStyles = ['bg-gray-50', 'hover:bg-indigo-50', 'text-gray-500', 'hover:text-indigo-500',
            'border-gray-300',
            'hover:border-indigo-300'
        ];
        const errorDragStyles = ['bg-orange-50', 'text-orange-500', 'border-orange-500'];
        const defaultDragStyles = ['bg-indigo-50', 'text-indigo-500', 'border-indigo-500'];

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            initialError = dropZone.classList.contains('bg-red-50');
            initialError ? dropZone.classList.remove(...errorStyles) : dropZone.classList.remove(...defaultStyles);
            initialError ? dropZone.classList.add(...errorDragStyles) : dropZone.classList.add(...
                defaultDragStyles);
        });

        dropZone.addEventListener('dragleave', () => {
            initialError = dropZone.classList.contains('bg-red-50') || dropZone.classList.contains('bg-orange-50');
            initialError ? dropZone.classList.add(...errorStyles) : dropZone.classList.add(...defaultStyles);
            initialError ? dropZone.classList.remove(...errorDragStyles) : dropZone.classList.remove(...
                defaultDragStyles);
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            initialError = dropZone.classList.contains('bg-red-50') || dropZone.classList.contains('bg-orange-50');
            initialError ? dropZone.classList.add(...errorStyles) : dropZone.classList.add(...defaultStyles);
            initialError ? dropZone.classList.remove(...errorDragStyles) : dropZone.classList.remove(...
                defaultDragStyles);

            files = e.dataTransfer.files;
            if (files.length) {
                inputFile.files = files;
                inputFile.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endscript
