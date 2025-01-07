<div x-cloak x-data class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="signingBeneficiariesModal"
    @keydown.window.escape="signingBeneficiariesModal = false">

    <!-- Modal -->
    <div x-show="signingBeneficiariesModal" x-trap.noautofocus.noscroll="signingBeneficiariesModal"
        class="relative h-full p-4 flex items-start justify-center overflow-y-auto z-50 select-none">

        {{-- The Modal --}}
        <div class="w-full max-w-6xl">
            <div class="relative bg-white {{ $this->switchSign ? 'text-lime-950' : 'text-red-950' }} rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold ">
                            {{ $this->switchSign ? 'Check Beneficiaries (Payroll)' : 'Check Beneficiaries (Contract of Service)' }}
                        </h1>
                    </span>

                    <div class="flex items-center justify-center gap-2">
                        {{-- Loading State for Changes --}}
                        <svg class="size-6 {{ $this->switchSign ? 'text-lime-900' : 'text-red-900' }} animate-spin"
                            wire:loading wire:target="signContract, signPayroll" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        {{-- Close Modal --}}
                        <button type="button" @click="$wire.resetSigning(); signingBeneficiariesModal = false;"
                            class="outline-none {{ $this->switchSign ? 'text-lime-400 focus:bg-lime-200 focus:text-lime-900 hover:bg-lime-200 hover:text-lime-900' : 'text-red-400 focus:bg-red-200 focus:text-red-900 hover:bg-red-200 hover:text-red-900' }} rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close Modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                {{-- Modal body --}}
                <form x-data="multiSelect($wire.entangle('selectedRows'), $wire.entangle('switchSign'))"
                    @if ($this->switchSign) wire:submit.prevent="signPayroll"
                    @else
                    wire:submit.prevent="signContract" @endif
                    class="flex flex-col justify-center w-full gap-4 py-5 px-5 md:px-12 text-xs">
                    {{-- Header --}}
                    <div class="flex flex-col gap-6 font-medium">
                        <div class="flex flex-col items-center w-full gap-4 text-xs font-medium">
                            <div class="flex flex-col justify-between gap-2">
                                <span class="flex items-center gap-1">
                                    Implementation:
                                    <span class="flex items-center gap-1 py-1 px-3 rounded duration-200 ease-in-out"
                                        :class="{
                                            'bg-red-100 text-red-700': !switchSign,
                                            'bg-lime-100 text-lime-900': switchSign,
                                        }">
                                        @if ($this->implementation?->project_title)
                                            {{ $this->implementation?->project_title }}
                                            <span class="font-normal">
                                                ({{ $this->implementation?->project_num }})
                                            </span>
                                        @else
                                            {{ $this->implementation?->project_num }}
                                        @endif
                                    </span>
                                </span>
                                <span class="flex items-center gap-1">
                                    Batch:
                                    <span class="flex items-center gap-1 py-1 px-3 rounded duration-200 ease-in-out"
                                        :class="{
                                            'bg-red-100 text-red-700': !switchSign,
                                            'bg-lime-100 text-lime-900': switchSign,
                                        }">
                                        @if ($this->batch?->is_sectoral)
                                            {{ $this->batch?->sector_title }}
                                            <span class="font-normal">
                                                ({{ $this->batch?->batch_num }})
                                            </span>
                                        @elseif(!$this->batch?->is_sectoral)
                                            {{ $this->batch?->barangay_name }}
                                            <span class="font-normal">
                                                ({{ $this->batch?->batch_num }})
                                            </span>
                                        @endif
                                    </span>

                                    <span class="p-1 rounded text-center"
                                        :class="{
                                            'bg-rose-300 text-rose-900': @json($this->batch?->is_sectoral),
                                            'bg-emerald-300 text-emerald-900': @json(!$this->batch?->is_sectoral),
                                        }">
                                        {{ $this->batch?->is_sectoral ? 'ST' : 'NS' }}
                                    </span>
                                </span>
                            </div>

                            {{-- Toggle Contract <-> Payroll --}}
                            <div class="flex flex-col items-center gap-1">
                                <span class="font-medium">Switch Sign Checking</span>
                                <label for="switchSign"
                                    class="relative flex items-center justify-center cursor-pointer rounded p-0.5 bg-gray-200">
                                    <input type="checkbox" id="switchSign" wire:model.live="switchSign"
                                        class="sr-only peer">
                                    <span x-data="{ tooltip: false }"
                                        class="relative duration-200 ease-in-out py-1 px-2 rounded-l bg-red-700 text-red-100 peer-checked:bg-transparent peer-checked:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7"
                                            @mouseleave="tooltip = false;" @mouseenter="tooltip = true;"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M66.797 26.484 C 60.111 29.587,56.737 35.079,55.824 44.350 L 55.267 50.000 74.508 50.000 L 93.750 50.000 93.733 44.336 C 93.688 29.642,79.812 20.446,66.797 26.484 M120.313 26.563 C 117.845 29.030,117.845 370.970,120.312 373.438 C 122.777 375.902,364.723 375.902,367.188 373.438 C 368.739 371.886,368.750 370.833,368.750 229.688 L 368.750 87.500 341.132 87.500 C 302.389 87.500,306.250 91.345,306.250 52.759 L 306.250 25.000 214.063 25.000 C 122.917 25.000,121.857 25.018,120.313 26.563 M318.750 51.563 L 318.750 75.000 342.378 75.000 L 366.006 75.000 342.578 51.563 C 329.693 38.672,319.060 28.125,318.950 28.125 C 318.840 28.125,318.750 38.672,318.750 51.563 M41.797 63.984 C 31.167 68.916,30.904 70.111,30.610 114.844 C 30.297 162.456,30.304 162.500,37.500 162.500 C 44.065 162.500,43.750 164.609,43.750 120.678 C 43.750 74.453,43.636 75.000,53.242 75.000 L 55.469 75.000 55.469 193.750 L 55.469 312.500 74.609 312.500 L 93.750 312.500 93.750 187.500 L 93.750 62.500 69.336 62.517 C 47.219 62.533,44.628 62.671,41.797 63.984 M253.242 77.058 C 270.155 82.367,278.281 104.579,270.617 124.551 C 270.377 125.178,272.445 126.949,276.016 129.174 C 288.806 137.146,293.677 146.582,293.735 163.502 C 293.776 175.464,295.792 175.000,243.750 175.000 C 191.708 175.000,193.708 175.460,193.776 163.502 C 193.872 146.374,198.604 137.202,211.484 129.174 C 215.055 126.949,217.123 125.178,216.883 124.551 C 205.881 95.882,226.677 68.719,253.242 77.058 M235.033 89.079 C 226.730 93.465,223.442 107.022,227.489 120.184 C 231.981 134.793,255.519 134.793,260.011 120.184 C 261.364 115.782,261.900 103.754,260.916 99.847 C 258.325 89.559,244.787 83.926,235.033 89.079 M216.716 139.394 C 208.702 144.684,206.250 148.851,206.250 157.185 L 206.250 162.500 243.750 162.500 L 281.250 162.500 281.250 157.185 C 281.250 148.764,278.809 144.670,270.593 139.315 L 264.076 135.067 261.530 136.975 C 250.811 145.012,236.716 145.030,226.029 137.020 C 223.003 134.753,224.214 134.444,216.716 139.394 M326.950 201.120 C 331.706 203.107,331.706 209.393,326.950 211.380 C 322.936 213.057,164.564 213.057,160.550 211.380 C 155.897 209.436,155.792 203.147,160.379 201.153 C 164.182 199.500,322.996 199.468,326.950 201.120 M326.950 226.120 C 331.706 228.107,331.706 234.393,326.950 236.380 C 322.936 238.057,164.564 238.057,160.550 236.380 C 155.897 234.436,155.792 228.147,160.379 226.153 C 164.182 224.500,322.996 224.468,326.950 226.120 M209.794 271.975 C 217.197 276.971,224.198 295.409,224.216 309.961 L 224.219 312.500 230.494 312.500 C 240.491 312.500,244.092 314.948,242.635 320.755 C 241.848 323.892,238.097 325.000,228.267 325.000 C 214.673 325.000,212.500 323.353,212.500 313.053 C 212.500 299.406,206.943 281.250,202.767 281.250 C 200.316 281.250,196.036 295.861,194.505 309.460 C 193.040 322.466,191.288 325.339,185.679 323.931 C 183.115 323.288,182.386 321.913,180.947 315.014 C 180.313 311.972,179.294 308.528,178.682 307.360 L 177.569 305.237 174.416 309.454 C 172.682 311.774,170.188 315.825,168.873 318.456 C 166.437 323.333,165.388 324.219,162.045 324.219 C 157.076 324.219,155.755 318.882,159.089 312.279 C 166.789 297.032,175.238 291.032,183.664 294.824 C 184.669 295.277,185.176 294.382,186.330 290.121 C 191.094 272.520,200.184 265.491,209.794 271.975 M63.218 348.927 C 69.723 374.781,69.822 375.000,75.000 375.000 C 80.178 375.000,80.277 374.781,86.782 348.927 L 92.801 325.000 75.000 325.000 L 57.199 325.000 63.218 348.927 M251.950 338.620 C 256.706 340.607,256.706 346.893,251.950 348.880 C 247.963 350.546,152.037 350.546,148.050 348.880 C 143.397 346.936,143.292 340.647,147.879 338.653 C 151.658 337.010,248.023 336.980,251.950 338.620 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>

                                        <div x-cloak x-show="tooltip" x-transition.opacity
                                            class="text-left absolute z-50 top-full mt-2 right-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-zinc-50">
                                            Contract of Service
                                        </div>
                                    </span>
                                    <span x-data="{ tooltip: false }"
                                        class="relative duration-200 ease-in-out py-1 px-2 rounded-r bg-transparent text-gray-500 peer-checked:bg-lime-700 peer-checked:text-lime-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7"
                                            @mouseleave="tooltip = false;" @mouseenter="tooltip = true;"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M125.910 31.966 C 50.310 53.483,52.936 52.598,46.837 58.594 C 40.466 64.857,38.030 70.349,37.637 79.336 L 37.323 86.493 54.292 146.567 C 63.625 179.607,71.549 207.184,71.901 207.848 C 72.484 208.949,100.874 227.610,103.496 228.616 C 104.408 228.966,104.331 228.048,103.084 223.703 C 66.619 96.595,67.910 102.546,76.011 98.901 C 84.537 95.064,87.699 89.450,87.359 78.753 C 87.083 70.057,87.162 69.974,98.931 66.620 C 187.268 41.440,179.748 42.816,183.525 51.147 C 187.407 59.707,192.595 62.670,203.224 62.397 C 209.250 62.243,210.427 62.427,211.704 63.726 C 212.719 64.758,221.344 94.063,239.037 156.597 C 253.255 206.847,265.047 248.120,265.242 248.315 C 265.437 248.510,268.503 246.813,272.056 244.543 C 275.609 242.273,280.537 239.146,283.008 237.593 C 285.479 236.040,287.500 234.349,287.500 233.835 C 287.500 230.615,231.014 33.339,228.894 29.154 C 223.529 18.565,214.506 13.070,201.953 12.748 L 194.141 12.547 125.910 31.966 M240.640 26.591 C 241.474 28.149,244.967 39.445,246.851 46.680 L 247.716 50.000 263.976 50.000 C 284.858 50.000,287.500 51.009,287.500 58.984 C 287.500 66.000,296.260 74.924,303.204 74.983 C 313.048 75.066,312.500 70.626,312.500 150.321 L 312.500 218.662 324.985 210.698 L 337.470 202.734 337.289 125.000 L 337.109 47.266 334.811 42.578 C 331.878 36.596,325.904 30.622,319.922 27.689 L 315.234 25.391 277.496 25.167 L 239.758 24.943 240.640 26.591 M135.455 68.251 L 99.426 78.516 99.329 82.469 C 99.088 92.374,93.433 102.038,84.486 107.834 L 82.643 109.028 100.781 173.069 C 110.757 208.291,119.220 237.678,119.588 238.374 C 119.956 239.069,132.413 247.456,147.269 257.011 L 174.280 274.383 181.194 270.000 C 195.895 260.680,204.105 260.680,218.805 269.999 L 225.718 274.382 240.187 265.121 C 248.145 260.027,254.663 255.552,254.672 255.176 C 254.680 254.800,243.262 214.145,229.297 164.832 C 215.332 115.519,203.906 75.045,203.906 74.890 C 203.906 74.736,201.976 74.526,199.618 74.424 C 190.625 74.035,180.539 68.457,176.065 61.398 C 173.266 56.983,177.529 56.264,135.455 68.251 M251.744 63.867 C 258.915 90.807,298.280 227.266,298.828 227.083 C 300.434 226.548,300.243 85.939,298.637 85.930 C 291.221 85.889,276.563 71.285,276.563 63.938 C 276.563 62.601,275.677 62.500,263.971 62.500 C 252.362 62.500,251.409 62.607,251.744 63.867 M181.250 141.441 C 202.554 147.641,215.954 169.904,211.377 191.496 C 204.155 225.570,161.719 237.644,137.906 212.401 C 107.629 180.306,138.898 129.116,181.250 141.441 M163.882 151.658 C 133.772 155.864,127.801 198.153,155.467 211.264 C 162.788 214.734,175.357 214.839,182.181 211.488 C 215.060 195.342,200.150 146.593,163.882 151.658 M350.000 173.833 L 350.000 194.521 355.343 191.096 C 358.282 189.212,363.819 185.649,367.648 183.178 C 371.477 180.706,375.966 177.827,377.624 176.779 L 380.639 174.873 378.406 172.484 C 376.433 170.374,369.178 165.375,354.492 156.009 L 350.000 153.144 350.000 173.833 M34.705 162.975 C 23.729 169.978,19.027 174.100,20.503 175.425 C 22.378 177.108,56.321 198.627,56.623 198.324 C 56.791 198.157,54.660 190.116,51.888 180.455 C 49.116 170.795,46.489 161.572,46.050 159.961 C 45.024 156.193,45.553 156.053,34.705 162.975 M12.895 187.695 C 12.643 189.092,12.539 229.609,12.664 277.734 L 12.891 365.234 15.128 369.794 C 16.740 373.080,17.723 374.261,18.644 374.020 C 20.152 373.625,163.245 281.822,163.244 281.250 C 163.243 280.808,14.483 185.156,13.796 185.156 C 13.552 185.156,13.146 186.299,12.895 187.695 M311.156 233.051 C 270.242 259.346,236.768 281.035,236.771 281.250 C 236.778 281.864,380.897 374.219,381.848 374.219 C 382.316 374.219,383.691 372.197,384.904 369.727 L 387.109 365.234 387.336 277.734 C 387.551 194.914,387.419 185.096,386.097 185.199 C 385.795 185.223,352.071 206.756,311.156 233.051 M192.332 276.758 C 187.341 279.477,27.403 382.362,27.372 382.874 C 27.356 383.123,29.161 384.178,31.382 385.218 L 35.420 387.109 200.000 387.109 L 364.580 387.109 368.618 385.218 C 370.839 384.178,372.648 383.123,372.638 382.874 C 372.616 382.303,209.250 277.288,206.400 276.012 C 202.826 274.412,195.970 274.776,192.332 276.758 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>

                                        <div x-cloak x-show="tooltip" x-transition.opacity
                                            class="text-left absolute z-50 top-full mt-2 left-0 rounded p-2 shadow text-xs font-normal whitespace-nowrap border bg-zinc-900 border-zinc-300 text-zinc-50">
                                            Payroll
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <span class="flex flex-col text-sm">
                            @if (!$this->switchSign)
                                Mark the beneficiaries that signed their Contract of Service
                                (COS):
                                <span class="text-xs font-normal text-gray-500">It will indicate that they are validated
                                    and legit.</span>
                            @else
                                Mark the beneficiaries that signed and claimed their Payroll:
                                <span class="text-xs font-normal text-gray-500">Beneficiaries who were only marked from
                                    COS (Contract of Service) will appear here.</span>
                            @endif
                        </span>
                    </div>

                    @if ($switchSign && $this->contractCount === 0)
                        {{-- If There are no COS signed yet... --}}
                        <div class="relative flex flex-col h-[40vh] w-full" wire:loading.remove
                            wire:target="switchSign">
                            <div
                                class="text-center flex flex-col size-full items-center justify-center gap-4 rounded border border-gray-300 bg-gray-100 text-gray-500">
                                <svg class="size-14 text-gray-400 sm:size-20" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M247.266 2.844 C 244.672 4.193,142.969 105.395,142.969 106.626 C 142.969 107.050,146.918 111.358,151.746 116.199 L 160.523 125.002 196.492 89.063 C 236.944 48.646,234.253 50.657,240.990 55.799 C 248.215 61.315,257.382 61.295,265.104 55.747 C 271.627 51.061,268.870 48.955,309.860 89.938 C 351.101 131.173,348.955 128.351,344.155 135.032 C 338.706 142.617,338.724 151.835,344.201 159.010 C 349.343 165.747,351.354 163.056,310.937 203.508 L 274.998 239.477 283.801 248.254 C 288.642 253.082,292.950 257.031,293.374 257.031 C 294.687 257.031,395.868 155.230,397.191 152.578 C 398.765 149.422,398.781 145.312,397.235 141.576 C 395.584 137.587,263.470 5.314,258.933 3.107 C 255.151 1.268,250.497 1.163,247.266 2.844 M176.172 19.972 C 85.608 32.276,18.781 108.366,18.754 199.211 L 18.750 213.657 34.269 198.153 L 49.787 182.649 50.226 177.457 C 51.999 156.482,62.011 129.089,75.159 109.241 C 78.241 104.588,80.961 100.781,81.203 100.781 C 82.380 100.781,299.509 318.858,298.828 319.355 C 276.180 335.901,246.456 347.753,222.543 349.774 L 217.351 350.213 201.839 365.798 L 186.328 381.384 203.906 381.022 C 302.298 378.996,379.008 302.286,381.024 203.906 L 381.384 186.328 365.796 201.841 L 350.209 217.355 349.691 222.923 C 347.419 247.372,335.912 276.164,319.355 298.828 C 318.858 299.509,100.781 82.380,100.781 81.203 C 100.781 80.066,118.652 69.113,127.357 64.916 C 143.317 57.219,163.000 51.448,177.457 50.226 L 182.649 49.787 198.153 34.269 L 213.657 18.750 198.821 18.831 C 190.661 18.875,180.469 19.389,176.172 19.972 M205.860 96.093 L 176.180 125.781 186.050 125.781 C 234.956 125.781,274.219 165.045,274.219 213.952 L 274.219 223.826 303.892 194.139 L 333.565 164.453 331.610 160.520 C 327.267 151.782,327.275 142.139,331.633 133.463 L 333.681 129.386 302.147 97.853 L 270.614 66.319 266.537 68.367 C 256.929 73.193,245.808 72.565,235.935 66.640 C 235.718 66.511,222.185 79.764,205.860 96.093 M283.380 108.721 C 300.312 116.703,293.885 142.969,275.000 142.969 C 259.330 142.969,251.134 123.909,261.954 112.629 C 267.969 106.358,275.466 104.991,283.380 108.721 M272.266 118.839 C 264.554 123.215,270.153 133.960,278.481 130.765 C 280.447 130.010,282.197 125.971,281.646 123.461 C 280.754 119.399,275.682 116.900,272.266 118.839 M176.367 138.039 C 175.615 138.235,175.000 138.635,175.000 138.927 C 175.000 140.080,260.915 225.283,261.492 224.703 C 263.004 223.185,262.133 199.795,260.331 193.525 C 252.581 166.562,228.892 144.245,202.181 138.741 C 196.574 137.585,179.843 137.130,176.367 138.039 M54.956 193.945 C 26.961 221.982,3.495 246.047,2.809 247.422 C 0.826 251.400,1.204 256.174,3.821 260.199 C 7.485 265.834,138.150 395.817,141.576 397.235 C 145.312 398.781,149.422 398.765,152.578 397.191 C 155.230 395.868,257.031 294.687,257.031 293.374 C 257.031 292.950,253.082 288.642,248.254 283.801 L 239.477 274.998 203.508 310.937 C 163.056 351.354,165.747 349.343,159.010 344.201 C 151.785 338.685,142.618 338.705,134.896 344.253 C 128.373 348.939,131.130 351.045,90.140 310.062 C 48.899 268.827,51.045 271.649,55.845 264.968 C 61.294 257.383,61.276 248.165,55.799 240.990 C 50.657 234.253,48.646 236.944,89.063 196.492 L 125.002 160.523 116.199 151.746 C 111.358 146.918,107.050 142.969,106.626 142.969 C 106.202 142.969,82.950 165.908,54.956 193.945 M137.992 176.758 C 137.048 180.282,137.518 196.246,138.741 202.181 C 144.602 230.625,169.661 255.549,198.211 261.330 C 204.829 262.670,223.421 262.784,224.708 261.492 C 225.273 260.925,140.089 175.000,138.961 175.000 C 138.687 175.000,138.252 175.791,137.992 176.758 M95.914 206.047 L 66.437 235.547 68.391 239.480 C 72.733 248.221,72.725 257.861,68.367 266.537 L 66.319 270.614 97.853 302.147 L 129.386 333.681 133.463 331.633 C 142.139 327.275,151.782 327.267,160.520 331.610 L 164.453 333.565 194.139 303.892 L 223.826 274.219 213.952 274.219 C 165.892 274.219,127.768 237.109,125.859 188.468 L 125.391 176.546 95.914 206.047 M132.564 258.662 C 143.644 264.026,146.432 278.625,138.046 287.373 C 126.466 299.455,106.753 291.698,106.777 275.070 C 106.797 262.036,120.941 253.036,132.564 258.662 M120.332 270.293 C 116.953 273.672,117.851 279.064,122.135 281.118 C 128.628 284.230,134.690 275.379,129.442 270.449 C 127.090 268.239,122.465 268.160,120.332 270.293 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <span class="font-medium text-sm">Only the marked beneficiaries who signed their
                                    <br><span class="text-red-500">Contract of Service</span> will appear here.</span>
                            </div>
                        </div>
                    @else
                        {{-- Table of Beneficiaries --}}
                        <div id="signing-beneficiaries-table" wire:loading.remove wire:target="switchSign"
                            class="relative max-h-[70vh] overflow-auto scrollbar-thin scrollbar-track-zinc-50"
                            :class="{
                                ' scrollbar-thumb-red-700':
                                    !switchSign,
                                'scrollbar-thumb-lime-700': switchSign,
                            }">
                            <table
                                class="flex flex-col relative w-full text-sm text-left text-zinc-950 whitespace-nowrap">
                                <thead
                                    class="sticky flex top-0 text-xs z-20 text-zinc-50 uppercase bg-zinc-500 font-bold">
                                    <tr>
                                        <th scope="col" class="p-2 text-center">
                                            <input type="checkbox"
                                                class="appearance-none duration-150 ease-in-out border border-zinc-500 rounded"
                                                :class="{
                                                    'checked:bg-red-700 checked:text-red-700 focus:ring-red-500 hover:border-red-500 text-red-700':
                                                        !switchSign,
                                                    'checked:bg-lime-700 checked:text-lime-700 focus:ring-lime-500 hover:border-lime-500 text-lime-700': switchSign,
                                                }"
                                                id="select-all-beneficiary" @click="toggleSelectAll($event)">
                                        </th>
                                        <th scope="col" class="p-2 text-center font-medium">
                                            #
                                        </th>
                                        <th scope="col" class="p-2">
                                            name
                                        </th>
                                        <th scope="col" class="p-2">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative flex flex-col size-full text-xs">
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        @if ($beneficiary->is_signed === 0 && $switchSign)
                                            {{-- Placeholder Element since @continue doesn't work properly without it --}}
                                            <span class="absolute"></span>
                                            @continue
                                        @endif
                                        <tr wire:key="signed-beneficiary-{{ $key }}"
                                            @click="toggleRowByClick({{ $key }}, $event)"
                                            class="signed-rows relative border-b whitespace-nowrap duration-200 ease-in-out cursor-pointer"
                                            :class="{
                                                'bg-zinc-100 hover:bg-zinc-200 text-red-900 hover:text-red-900': (
                                                        selectedRows[{{ $key }}] ? selectedRows[
                                                            {{ $key }}].is_signed === 1 : false) &&
                                                    !switchSign,
                                                'bg-zinc-100 hover:bg-zinc-200 text-lime-900 hover:text-lime-900': (
                                                    selectedRows[{{ $key }}] ? selectedRows[
                                                        {{ $key }}].is_paid === 1 : false) && switchSign,
                                                'hover:bg-zinc-50': (selectedRows[{{ $key }}] ? selectedRows[
                                                    {{ $key }}].is_signed !== 1 : false) && !switchSign,
                                                'hover:bg-zinc-50': (selectedRows[{{ $key }}] ? selectedRows[
                                                    {{ $key }}].is_paid !== 1 : false) && switchSign,
                                            }">
                                            <th scope="row" class="p-2 text-center">
                                                <template x-if="!switchSign">
                                                    <span>
                                                        <input type="checkbox"
                                                            class="select-checkbox appearance-none border duration-150 ease-in-out checked:bg-red-700 checked:text-red-700 focus:ring-red-500 border-zinc-500 hover:border-red-500 text-red-700 rounded"
                                                            id="check-beneficiary-{{ $key }}"
                                                            value={{ $key }}
                                                            @click.stop="toggle($event, {{ $key }})"
                                                            :checked="selectedRows[{{ $key }}] ? selectedRows[
                                                                {{ $key }}].is_signed === 1 : false">
                                                    </span>
                                                </template>

                                                <template x-if="switchSign">
                                                    <span>
                                                        <input type="checkbox"
                                                            class="select-checkbox appearance-none border duration-150 ease-in-out checked:bg-lime-700 checked:text-lime-700 focus:ring-lime-500 border-zinc-500 hover:border-lime-500 text-lime-700 rounded"
                                                            id="check-beneficiary-{{ $key }}"
                                                            value={{ $key }}
                                                            @click.stop="toggle($event, {{ $key }})"
                                                            :checked="selectedRows[{{ $key }}] ? selectedRows[
                                                                {{ $key }}].is_paid === 1 : false">
                                                    </span>
                                                </template>
                                            </th>
                                            <td class="p-2 text-center font-medium">
                                                {{ $key + 1 }}
                                            </td>
                                            <td class="p-2">
                                                {{ $this->full_name_last_first($beneficiary) }}
                                            </td>
                                            <td class="p-2">
                                                @if ($beneficiary->beneficiary_type !== 'underemployed')
                                                    <span class="rounded p-1 text-center bg-amber-300 text-amber-900">
                                                        {{ $beneficiary->beneficiary_type }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Loading Block While Switching --}}
                    <div class="relative flex flex-col h-[40vh] w-full" wire:loading wire:target="switchSign">
                        <div
                            class="flex size-full items-center justify-center gap-2 rounded border border-gray-300 bg-gray-100 text-gray-500">
                            <span class="animate-pulse font-semibold text-xl">Loading...</span>
                            {{-- Loading State for Changes --}}
                            <svg class="size-6 animate-spin"
                                :class="{
                                    'text-red-900': !switchSign,
                                    'text-lime-900': switchSign,
                                }"
                                wire:loading wire:target="switchSign" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" wire:loading.attr="disabled"
                            wire:target="switchSign, signContract, signPayroll"
                            class="flex items-center justify-center gap-2 rounded px-2.5 py-1 text-sm font-bold disabled:opacity-75"
                            :class="{
                                'bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50': !switchSign,
                                'bg-lime-700 hover:bg-lime-800 active:bg-lime-900 text-lime-50': switchSign,
                            }">
                            CONFIRM
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('multiSelect', (selectedRows, switchSign) => ({
            selectedRows,
            switchSign,
            lastChecked: null,

            // The toggle function for clicking checkboxes
            toggle(event, key) {
                const checkboxes = document.querySelectorAll('.select-checkbox');
                const currentCheckbox = event.target;

                // If Shift key is pressed and there's a last checked checkbox
                if (event.shiftKey && this.lastChecked) {
                    let inRange = false;

                    checkboxes.forEach((checkbox) => {
                        const value = parseInt(checkbox.value, 10);

                        if (checkbox === currentCheckbox || checkbox === this.lastChecked) {
                            inRange = !inRange;
                            this.toggleRow(value, checkbox.checked);
                        }

                        if (inRange) {
                            this.toggleRow(value, true); // Always check items in range
                        }
                    });
                } else {
                    this.toggleRow(key, currentCheckbox.checked);
                }

                // Update the last checked checkbox
                this.lastChecked = currentCheckbox;
            },

            // Basically pushes or pops the selected rows from the $selectedRows livewire property
            toggleRow(key, isChecked) {
                if (isChecked && !this.switchSign) {
                    this.selectedRows[key].is_signed = 1; // Mark as signed
                } else if (!isChecked && !this.switchSign) {
                    this.selectedRows[key].is_signed = 0; // Mark as unsigned
                } else if (isChecked && this.switchSign) {
                    this.selectedRows[key].is_paid = 1; // Mark as paid
                } else if (!isChecked && this.switchSign) {
                    this.selectedRows[key].is_paid = 0; // Mark as unpaid
                }
            },

            // Toggle checkbox by clicking on the table row <tr>
            toggleRowByClick(key, event) {
                const rows = document.querySelectorAll('.signed-rows');
                const currentRow = event.currentTarget;
                const checkbox = currentRow.querySelector('.select-checkbox');

                if (event.shiftKey && this.lastChecked) {
                    let inRange = false;

                    rows.forEach((row) => {
                        const rowCheckBox = row.querySelector('.select-checkbox');
                        const rowKey = parseInt(rowCheckBox.value, 10);

                        if (row === currentRow || row === this.lastChecked.closest('tr')) {
                            inRange = !inRange;
                            this.toggleRow(rowKey, true);
                            rowCheckBox.checked =
                                true; // Update the checkbox visually
                        }

                        if (inRange) {
                            this.toggleRow(rowKey, true);
                            rowCheckBox.checked = true;
                        }
                    });
                } else {
                    const isChecked = !checkbox.checked; // Toggle the state
                    this.toggleRow(key, isChecked);
                    checkbox.checked = isChecked;
                }

                this.lastChecked = currentRow.querySelector('.select-checkbox'); // Update the last checked row
            },

            // Toggle "Select All" checkbox
            toggleSelectAll(event) {
                const isChecked = event.target.checked;

                document.querySelectorAll('.select-checkbox').forEach((checkbox) => {
                    const key = parseInt(checkbox.value, 10);
                    this.toggleRow(key, isChecked);
                    checkbox.checked = isChecked; // Update checkboxes visually
                });
            },
        }));
    </script>
@endscript
