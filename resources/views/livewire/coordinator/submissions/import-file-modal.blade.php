<div wire:ignore.self id="import-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div x-on:download-options-confirmed.window="$wire.export($event.detail.slots_allocated);"
        class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-md shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                <h1 class="text-lg font-semibold text-blue-1100 ">
                    Import File
                </h1>

                <div class="flex items-center justify-center">
                    {{-- Loading State --}}
                    <div class="flex items-center justify-center me-4 z-50 text-blue-900" wire:loading
                        wire:target="export">
                        <svg class="size-8 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <button type="button" @click=""
                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-200 ease-in-out">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
            </div>

            <hr class="">

            <div class="flex justify-center items-center w-full my-4">
                {{-- Stepper --}}
                @if ($step === 1)
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center">
                            <span
                                class="flex items-center justify-center size-9 sm:size-12 bg-blue-100 rounded-full shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-6 text-blue-700"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M196.274 7.780 C 194.869 8.123,192.408 9.314,190.805 10.427 C 185.124 14.372,79.055 121.315,77.774 124.389 C 74.624 131.944,78.015 141.017,85.547 145.187 C 87.605 146.326,91.870 146.514,120.508 146.726 L 153.125 146.968 153.159 200.242 C 153.199 263.162,152.922 260.638,160.475 266.752 L 163.426 269.141 200.000 269.141 L 236.574 269.141 239.525 266.752 C 247.078 260.638,246.801 263.162,246.841 200.242 L 246.875 146.968 279.492 146.726 C 308.130 146.514,312.395 146.326,314.453 145.187 C 321.983 141.019,325.373 131.954,322.231 124.389 C 320.825 121.004,211.289 11.061,207.422 9.153 C 204.303 7.615,199.420 7.013,196.274 7.780 M16.998 269.596 C 10.751 271.069,3.628 277.436,1.254 283.669 C -1.021 289.644,-0.282 375.696,2.083 380.113 C 4.418 384.476,9.888 389.311,14.499 391.089 C 20.549 393.423,379.451 393.423,385.501 391.089 C 390.112 389.311,395.582 384.476,397.917 380.113 C 400.549 375.199,400.992 288.923,398.416 282.937 C 396.499 278.480,391.660 273.527,386.719 270.964 L 383.203 269.141 328.516 268.964 L 273.828 268.787 271.685 273.337 C 267.374 282.489,259.313 290.485,249.728 295.118 C 240.644 299.509,239.533 299.609,200.000 299.609 C 160.467 299.609,159.356 299.509,150.272 295.118 C 140.703 290.493,132.645 282.506,128.315 273.358 L 126.172 268.828 73.047 268.868 C 43.828 268.889,18.606 269.217,16.998 269.596 M300.543 334.095 C 305.665 338.240,306.641 340.147,306.641 346.013 C 306.641 362.041,286.431 366.675,279.102 352.328 C 272.367 339.146,289.150 324.874,300.543 334.095 M359.194 332.459 C 370.152 337.038,371.808 350.541,362.335 358.074 C 349.666 368.150,331.896 350.282,341.976 337.603 C 346.395 332.044,353.278 329.987,359.194 332.459 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="text-blue-700 text-xs mt-1">Import</span>
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
                            <span class="text-xs text-gray-500 mt-1">Validate</span>
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
                            <span class="text-xs text-gray-500 mt-1">Finish</span>
                        </div>
                    </div>
                @elseif($step === 2)

                @elseif($step === 3)
                @endif
            </div>
            <!-- Modal Body -->
            @if ($step === 1)
                <div class="p-4 md:p-5">
                    <p class="text-center whitespace-nowrap w-full text-red-500 z-10 h-2 mb-4 text-xs">
                        @error('file_path')
                            {{ $message }}
                        @enderror
                    </p>
                    {{-- File Dropzone --}}
                    <div x-data="{
                        uploading: false,
                        progress: 0,
                    }" x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false; progress = 0;"
                        x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false;"
                        x-on:livewire-upload-progress="progress = $event.detail.progress;"
                        class="relative flex flex-col items-center justify-center">
                        {{-- Loading Black BG --}}
                        <div x-show="uploading"
                            class="absolute flex items-center justify-center rounded w-1/2 h-full z-50 cursor-wait">
                            <div class="absolute inset-0 bg-black opacity-10 rounded size-full z-50">
                                {{-- Darkness... --}}
                            </div>
                        </div>

                        <!-- Progress Bar && Loading Icon -->
                        <div x-show="uploading" class="absolute flex items-center justify-center w-2/6 z-40">
                            <div class="w-full bg-gray-300 rounded-lg h-2">
                                <div class=" h-full bg-blue-500 rounded-lg" x-bind:style="'width: ' + progress + '%'">
                                </div>
                            </div>
                            <svg class="ms-2 size-5 text-blue-900 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <label for="file_path"
                            class="flex flex-col items-center justify-center w-1/2 h-32 border-dashed rounded border-2
                                {{ $errors->has('file_path')
                                    ? 'bg-red-50 text-red-500 border-red-300'
                                    : 'text-gray-500 hover:text-blue-500 bg-gray-50 hover:bg-blue-50
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        border-gray-300 hover:border-blue-300' }}
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
                            <input id="file_path" wire.loading.attr="disabled" wire:model="file_path" type="file"
                                accept=".xlsx, .csv" class="hidden" />
                        </label>

                        {{-- Cancel (X) button --}}
                        <span x-show="uploading" class="absolute top-2 right-[26%] inline-flex z-50">
                            <button class="text-gray-500 hover:text-blue-900 duration-200 ease-in-out" type="button"
                                @click="$wire.cancelUpload('file_path');"><svg class="size-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </span>
                    </div>

                    {{-- Download Desc --}}
                    <p class="text-center whitespace-nowrap w-full text-gray-500 z-10 h-6 mt-4 text-sm">
                        Please use the
                        <button type="button" @click="trapDownload=true" data-modal-target="download-options-alert"
                            data-modal-toggle="download-options-alert"
                            class="text-blue-700 hover:text-blue-800 active:text-blue-1000 
                            font-medium underline underline-offset-2 decoration-transparent hover:decoration-blue-800 
                            active:decoration-blue-1000 duration-200 ease-in-out">
                            sample format
                        </button>
                        for uploading files to avoid unnecessary errors.
                    </p>

                    <div class="relative flex flex-col items-center justify-start mt-4 h-40">
                        {{-- File Preview --}}
                        @if ($file_path && !$errors->has('file_path'))
                            <span
                                class="flex justify-between items-center z-50 text-sm p-2 w-1/2 rounded-md bg-gray-50 hover:bg-blue-50 text-gray-500 hover:text-blue-500 border border-gray-500 hover:border-blue-500 duration-500 ease-in-out">
                                {{ $file_path->getClientOriginalName() }}

                                {{-- X button --}}
                                <button type="button" @click="$wire.clearFiles();"
                                    class="outline-none text-gray-500 hover:text-blue-900 ms-4 inline-flex justify-center items-center duration-200 ease-in-out"
                                    data-modal-toggle="import-modal">
                                    <svg class="size-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </span>
                        @endif
                    </div>
                    {{-- Modal Footer --}}
                    <div class="w-full flex relative items-center justify-end">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-blue-900" wire:loading wire:target="validateFile">
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

                        <button type="button" wire:loading.attr="disabled" wire:target="validateFile"
                            class="flex items-center justify-center px-3 py-1 font-bold bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 rounded"
                            wire:click="export">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M295.703 104.354 C 288.091 108.313,284.738 117.130,287.918 124.830 C 288.731 126.797,298.250 136.876,317.407 156.055 L 345.695 184.375 178.190 184.375 L 10.684 184.375 7.316 186.349 C -2.632 192.179,-2.632 207.821,7.316 213.651 L 10.684 215.625 178.190 215.625 L 345.695 215.625 317.407 243.945 C 287.868 273.517,286.719 274.922,286.719 281.450 C 286.719 291.748,296.214 298.639,307.490 296.523 C 310.798 295.903,394.561 214.221,398.124 208.143 C 400.760 203.645,400.760 196.355,398.123 191.857 C 395.754 187.814,311.819 104.984,309.009 103.915 C 305.871 102.722,298.364 102.970,295.703 104.354 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </button>


                    </div>
                </div>
            @elseif($step === 2)
                <form wire:submit.prevent="uploadFile" class="p-4 md:p-5">
                    {{-- Modal Footer --}}
                    <div class="w-full flex relative items-center justify-end">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-blue-900" wire:loading wire:target="uploadFile">
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
                        <button type="submit" wire:loading.attr="disabled" wire:target="uploadFile"
                            class="space-x-2 py-2 px-4 text-center text-white font-bold flex items-center bg-blue-700 disabled:opacity-75 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-md">
                            <p>NEXT</p>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M87.232 51.235 C 70.529 55.279,55.160 70.785,51.199 87.589 C 49.429 95.097,49.415 238.777,51.184 245.734 C 55.266 261.794,68.035 275.503,84.375 281.371 L 89.453 283.195 164.063 283.423 C 247.935 283.680,244.564 283.880,256.471 277.921 C 265.327 273.488,273.488 265.327,277.921 256.471 C 283.880 244.564,283.680 247.935,283.423 164.063 L 283.195 89.453 281.371 84.375 C 275.503 68.035,261.794 55.266,245.734 51.184 C 239.024 49.478,94.296 49.525,87.232 51.235 M326.172 101.100 C 323.101 102.461,320.032 105.395,318.240 108.682 C 316.870 111.194,316.777 115.490,316.406 193.359 L 316.016 275.391 313.810 281.633 C 308.217 297.460,296.571 308.968,280.859 314.193 L 275.391 316.012 193.359 316.404 L 111.328 316.797 108.019 318.693 C 97.677 324.616,97.060 340.415,106.903 347.255 L 110.291 349.609 195.575 349.609 L 280.859 349.609 287.500 347.798 C 317.300 339.669,339.049 318.056,347.783 287.891 L 349.592 281.641 349.816 196.680 C 350.060 104.007,350.312 109.764,345.807 104.807 C 341.717 100.306,332.072 98.485,326.172 101.100 M172.486 118.401 C 180.422 121.716,182.772 126.649,182.795 140.039 L 182.813 150.000 190.518 150.000 C 209.679 150.000,219.220 157.863,215.628 170.693 C 213.075 179.810,207.578 182.771,193.164 182.795 L 182.813 182.813 182.795 193.164 C 182.771 207.578,179.810 213.075,170.693 215.628 C 157.863 219.220,150.000 209.679,150.000 190.518 L 150.000 182.813 140.039 182.795 C 123.635 182.767,116.211 176.839,117.378 164.698 C 118.318 154.920,125.026 150.593,139.970 150.128 L 150.000 149.815 150.000 142.592 C 150.000 122.755,159.204 112.853,172.486 118.401 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </button>
                    </div>
                </form>
            @elseif($step === 3)
            @endif
        </div>
    </div>
</div>
