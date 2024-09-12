<div x-cloak x-data="{ showModal: false }" x-init="setTimeout(() => {
    showModal = true;
}, 500);" @keydown.escape.window="showModal = false">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="showModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-trap.noscroll="showModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full"
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        <div class="relative w-full max-w-5xl max-h-full">
            <div class="relative bg-white rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h2 class="text-sm sm:text-base font-semibold text-indigo-1100">Heads Up!</h2>

                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading>
                            <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <button @click="showModal = false"
                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                <!-- Modal Body -->
                <div class="pt-5 pb-6 px-6 sm:px-16 text-indigo-1100">

                    {{-- Welcome message | Headers --}}
                    <p class="text-lg sm:text-3xl font-bold flex items-center justify-start">Welcome
                        back to
                        your workspace!
                        <span class="ms-3">
                            <img class="size-5 sm:size-10" src="{{ asset('assets/w_c.png') }}" alt="Confetti">
                        </span>
                    </p>
                    <p class="text-sm mt-3">
                        Let's start with a bunch of <strong>navigations</strong> for you to jump right in and
                        <strong>some
                            updates</strong> for you to review.
                    </p>

                    <hr class="my-7">

                    {{-- Instant Navigations --}}
                    <p class="text-sm font-semibold">
                        What would you like to do? <span class="text-gray-500 font-medium">Where do you want to
                            navigate?</span>
                    </p>
                    <div class="flex flex-wrap items-center justify-center mt-3 gap-2 sm:gap-4">

                        {{-- Dashboard --}}
                        <button type="button" @click="showModal = false"
                            class="flex-1 m-0 flex items-center justify-start px-2 py-2 border outline-none group bg-white hover:bg-indigo-50 focus:bg-indigo-50 border-gray-300 hover:border-indigo-900 focus:border-indigo-900 duration-200 ease-in-out rounded">
                            <span
                                class="grid place-items-center size-10 text-indigo-700 bg-indigo-100 duration-200 ease-in-out group-hover:bg-indigo-700 group-focus:bg-indigo-700 group-hover:text-indigo-50 group-focus:text-indigo-50 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M214.063 92.854 L 214.063 185.938 307.146 185.938 L 400.229 185.938 399.769 174.805 C 395.931 82.061,317.939 4.069,225.195 0.231 L 214.063 -0.229 214.063 92.854 M160.938 30.180 C 35.185 47.237,-37.215 182.831,18.746 296.484 C 82.311 425.583,262.490 436.265,341.589 315.625 C 359.243 288.699,371.094 250.872,371.094 221.444 L 371.094 214.063 278.516 214.063 L 185.938 214.063 185.938 121.484 L 185.938 28.906 177.539 28.987 C 172.920 29.031,165.449 29.568,160.938 30.180 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span
                                class="flex flex-col items-start ms-2 duration-200 ease-in-out group-hover:text-indigo-900 group-focus:text-indigo-900">
                                <p class="text-sm font-semibold">View Summary</p>
                                <p class="text-xs text-gray-500">This page</p>
                            </span>
                        </button>

                        <button type="button"
                            class="flex-1 m-0 flex items-center justify-start px-2 py-2 border outline-none group bg-white hover:bg-indigo-50 focus:bg-indigo-50 border-gray-300 hover:border-indigo-900 focus:border-indigo-900 duration-200 ease-in-out rounded">
                            <span
                                class="grid place-items-center size-10 text-indigo-700 bg-indigo-100 duration-200 ease-in-out group-hover:bg-indigo-700 group-focus:bg-indigo-700 group-hover:text-indigo-50 group-focus:text-indigo-50 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M168.119 1.582 C 160.206 6.405,160.206 29.439,168.119 34.401 C 173.859 38.002,182.459 35.609,185.621 29.531 C 187.253 26.394,187.140 9.327,185.464 5.794 C 182.819 0.222,173.901 -1.944,168.119 1.582 M44.531 25.552 C 34.667 29.158,35.097 39.868,45.536 50.589 C 57.152 62.519,63.782 64.463,70.373 57.873 C 76.359 51.886,75.075 45.274,66.134 36.051 C 55.809 25.400,51.146 23.134,44.531 25.552 M294.141 26.467 C 289.067 29.632,276.729 43.239,276.154 46.305 C 274.383 55.743,282.034 63.219,291.016 60.825 C 294.703 59.842,309.637 45.792,311.423 41.627 C 315.973 31.009,303.875 20.397,294.141 26.467 M163.672 62.987 C 105.459 71.816,68.358 109.377,63.174 164.733 C 59.878 199.925,71.390 229.941,99.904 260.499 C 117.421 279.272,124.934 292.448,124.988 304.492 L 125.000 307.031 150.391 307.031 L 175.781 307.031 175.786 294.727 C 175.793 274.562,178.030 267.053,186.487 258.796 L 190.977 254.412 190.733 249.124 C 190.168 236.902,193.210 231.651,211.630 213.049 C 230.977 193.511,236.428 190.248,248.891 190.745 L 254.424 190.965 258.802 186.481 C 264.674 180.466,271.397 177.325,280.717 176.243 L 287.689 175.433 287.151 167.990 C 283.463 116.909,248.576 76.272,198.507 64.737 C 191.947 63.226,169.524 62.099,163.672 62.987 M6.266 126.368 C -0.099 129.566,-2.322 139.309,2.112 144.578 C 8.138 151.740,29.208 151.248,34.164 143.830 C 40.982 133.625,33.565 125.002,17.969 125.002 C 10.973 125.002,8.383 125.305,6.266 126.368 M320.703 126.103 C 311.704 130.102,311.001 143.190,319.539 147.769 C 323.364 149.821,339.722 149.876,343.694 147.850 C 352.119 143.555,352.135 131.072,343.722 126.758 C 339.619 124.654,324.905 124.235,320.703 126.103 M279.408 199.211 C 274.658 200.698,272.344 204.263,271.543 211.328 C 270.458 220.898,270.416 221.000,267.153 221.978 C 264.228 222.854,264.063 222.792,259.231 218.971 C 247.493 209.689,246.472 210.006,228.013 228.658 C 209.921 246.940,209.739 247.556,218.971 259.231 C 222.792 264.063,222.854 264.228,221.978 267.153 C 220.996 270.430,220.771 270.522,211.183 271.548 C 199.500 272.799,198.828 274.325,198.828 299.609 C 198.828 326.745,198.912 326.857,220.867 328.891 C 223.561 329.141,222.471 335.429,218.953 339.937 C 209.709 351.785,210.008 352.749,228.658 371.205 C 246.821 389.180,247.648 389.424,259.213 380.216 L 264.453 376.044 267.188 377.160 C 268.691 377.774,270.009 378.359,270.116 378.460 C 270.223 378.561,270.684 381.603,271.141 385.220 C 272.168 393.345,273.008 395.594,275.943 398.063 L 278.245 400.000 299.083 399.997 C 325.551 399.993,326.289 399.661,327.672 387.109 C 328.517 379.435,328.869 378.393,330.935 377.452 C 334.064 376.026,335.052 376.345,340.204 380.444 C 351.458 389.400,352.494 389.084,370.561 371.205 C 389.173 352.787,389.513 351.706,380.356 340.124 C 376.344 335.049,376.030 334.055,377.452 330.935 C 378.389 328.877,379.331 328.562,387.109 327.707 C 399.637 326.329,399.993 325.538,399.997 299.083 L 400.000 278.245 398.063 275.943 C 395.594 273.008,393.345 272.168,385.220 271.141 C 381.603 270.684,378.561 270.223,378.460 270.116 C 378.359 270.009,377.774 268.691,377.160 267.188 L 376.044 264.453 380.216 259.213 C 389.479 247.579,389.102 246.362,370.561 228.013 C 352.281 209.923,351.722 209.758,339.937 218.953 C 333.281 224.147,329.336 223.599,328.503 217.365 C 325.988 198.541,326.493 198.882,300.818 198.670 C 290.270 198.583,280.636 198.826,279.408 199.211 M313.274 259.373 C 343.222 269.461,351.884 307.074,329.383 329.321 C 298.469 359.886,247.807 330.850,258.577 288.739 C 264.647 265.005,289.841 251.478,313.274 259.373 M291.016 281.549 C 272.656 290.229,277.490 317.495,297.714 319.328 C 319.509 321.305,328.091 292.007,308.686 281.869 C 303.171 278.989,296.681 278.871,291.016 281.549 M125.014 338.867 C 125.052 364.989,137.523 382.018,160.289 387.038 C 161.858 387.384,162.511 388.128,162.922 390.038 C 165.782 403.315,184.743 403.766,186.509 390.599 C 186.903 387.661,187.037 387.545,190.969 386.728 C 195.171 385.856,204.155 381.962,204.956 380.666 C 205.208 380.259,204.324 378.906,202.992 377.659 C 194.792 369.978,190.362 360.058,190.750 350.245 L 190.972 344.630 187.026 340.847 C 184.856 338.766,182.108 335.404,180.919 333.376 L 178.758 329.688 151.879 329.688 L 125.000 329.688 125.014 338.867 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span
                                class="flex flex-col items-start ms-2 duration-200 ease-in-out group-hover:text-indigo-900 group-focus:text-indigo-900">
                                <p class="text-sm font-semibold">Create Projects</p>
                                <p class="text-xs text-gray-500">Implementations</p>
                            </span>
                        </button>

                        <button type="button"
                            class="flex-1 m-0 flex items-center justify-start px-2 py-2 border outline-none group bg-white hover:bg-indigo-50 focus:bg-indigo-50 border-gray-300 hover:border-indigo-900 focus:border-indigo-900 duration-200 ease-in-out rounded">
                            <span
                                class="grid place-items-center size-10 text-indigo-700 bg-indigo-100 duration-200 ease-in-out group-hover:bg-indigo-700 group-focus:bg-indigo-700 group-hover:text-indigo-50 group-focus:text-indigo-50 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M168.202 1.531 C 161.498 5.619,161.101 14.791,167.336 21.555 L 170.512 25.000 167.092 25.002 C 104.479 25.042,41.548 78.493,28.173 142.992 C 21.877 173.356,24.450 186.705,36.601 186.716 C 45.624 186.724,48.448 182.664,49.300 168.458 C 52.229 119.642,81.383 78.133,126.178 59.000 C 138.432 53.766,157.347 49.241,167.044 49.223 L 169.636 49.219 166.771 52.714 C 160.003 60.974,163.118 72.217,172.619 73.822 C 178.567 74.827,180.610 73.479,195.569 58.691 C 217.461 37.048,217.463 37.017,197.731 16.912 C 180.295 -0.853,175.882 -3.152,168.202 1.531 M314.063 1.236 C 286.770 8.437,273.756 39.329,287.220 64.952 C 292.225 74.475,304.167 83.851,313.485 85.572 C 317.100 86.240,318.100 87.500,315.015 87.500 C 295.076 87.500,268.703 107.041,257.768 129.917 C 248.721 148.844,247.845 179.383,256.189 184.958 L 258.862 186.744 326.293 186.536 L 393.724 186.328 396.667 183.384 L 399.609 180.439 399.609 164.243 C 399.608 124.014,376.002 94.249,339.145 88.004 L 333.203 86.997 338.704 85.322 C 351.721 81.360,363.253 69.976,367.202 57.190 C 377.534 23.741,347.726 -7.645,314.063 1.236 M63.281 214.471 C 20.982 226.091,19.593 284.646,61.286 298.589 C 66.580 300.359,67.145 300.700,64.844 300.739 C 46.621 301.047,21.206 317.746,9.946 336.811 C -1.641 356.430,-4.398 392.197,5.189 398.517 C 8.772 400.880,139.681 400.808,143.562 398.442 C 152.568 392.951,151.414 361.868,141.458 341.797 C 130.452 319.609,104.653 301.180,83.984 300.743 C 82.278 300.706,83.348 300.121,87.923 298.589 C 127.047 285.484,128.670 231.543,90.405 216.083 C 83.414 213.258,70.491 212.490,63.281 214.471 M357.231 214.566 C 352.613 216.843,351.412 219.982,350.698 231.641 C 346.856 294.411,298.189 344.996,236.372 350.474 L 230.165 351.024 233.129 347.407 C 239.995 339.028,236.954 327.795,227.381 326.178 C 221.433 325.173,219.390 326.521,204.431 341.309 C 182.176 363.311,182.178 363.423,205.194 386.012 C 219.308 399.865,219.499 400.001,224.910 399.997 C 236.837 399.989,241.301 387.815,232.770 378.560 L 229.700 375.230 236.920 374.722 C 312.501 369.409,375.026 303.388,374.997 228.927 C 374.992 215.947,367.203 209.650,357.231 214.566 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span
                                class="flex flex-col items-start ms-2 duration-200 ease-in-out group-hover:text-indigo-900 group-focus:text-indigo-900">
                                <p class="text-sm font-semibold">Manage Users</p>
                                <p class="text-xs text-gray-500">User Management</p>
                            </span>
                        </button>

                        <button type="button"
                            class="flex-1 m-0 flex items-center justify-start px-2 py-2 border outline-none group bg-white hover:bg-indigo-50 focus:bg-indigo-50 border-gray-300 hover:border-indigo-900 focus:border-indigo-900 duration-200 ease-in-out rounded">
                            <span
                                class="grid place-items-center size-10 text-indigo-700 bg-indigo-100 duration-200 ease-in-out group-hover:bg-indigo-700 group-focus:bg-indigo-700 group-hover:text-indigo-50 group-focus:text-indigo-50 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M184.508 0.818 C 162.276 2.524,127.999 11.215,119.324 17.346 C 114.697 20.617,113.029 29.211,116.193 33.482 C 121.216 40.266,125.550 40.431,142.107 34.468 C 253.849 -5.773,374.400 79.747,374.345 199.219 C 374.289 322.154,252.266 407.235,137.846 364.118 C 123.172 358.588,114.663 361.679,114.663 372.541 C 114.663 380.675,118.750 383.768,137.500 389.823 C 266.948 431.628,398.545 335.767,398.624 199.609 C 398.691 83.851,299.735 -8.023,184.508 0.818 M81.316 39.501 C 73.472 42.904,48.608 66.953,40.495 78.983 C 34.082 88.493,39.809 99.080,50.932 98.275 C 56.293 97.888,55.724 98.368,68.579 83.375 C 72.137 79.225,78.443 72.918,82.593 69.360 C 97.747 56.368,97.143 57.095,97.460 51.460 C 98.007 41.773,89.907 35.772,81.316 39.501 M189.844 59.450 C 93.068 65.270,31.516 166.508,70.033 256.511 C 116.844 365.895,274.475 368.896,326.161 261.387 C 368.729 172.845,306.946 64.327,211.328 59.692 C 207.031 59.484,202.461 59.223,201.172 59.112 C 199.883 59.001,194.785 59.153,189.844 59.450 M205.050 108.519 C 210.704 111.696,210.547 110.280,210.547 158.203 C 210.547 181.836,210.289 202.087,209.975 203.206 C 209.220 205.891,161.587 265.506,158.386 267.773 C 151.492 272.655,141.425 267.365,141.411 258.853 C 141.404 254.119,140.446 255.499,165.322 224.402 L 188.280 195.703 188.286 155.078 C 188.291 116.871,188.378 114.323,189.736 112.262 C 193.431 106.659,199.222 105.244,205.050 108.519 M22.454 115.935 C 17.201 117.855,16.251 119.257,11.408 132.234 C 5.438 148.231,0.081 174.184,1.590 179.791 C 3.818 188.063,15.038 191.307,21.209 185.463 C 24.236 182.596,24.688 181.379,26.174 172.078 C 27.835 161.682,31.286 148.753,35.101 138.628 C 36.852 133.982,38.281 128.807,38.281 127.115 C 38.281 118.883,30.133 113.128,22.454 115.935 M10.531 211.694 C -0.411 214.175,-1.178 224.873,7.418 255.078 C 14.275 279.174,17.810 284.555,26.782 284.555 C 37.526 284.555,40.608 275.985,35.109 261.393 C 31.154 250.897,27.790 238.188,26.114 227.413 C 24.111 214.529,19.325 209.701,10.531 211.694 M43.119 303.144 C 32.662 309.519,36.629 319.509,58.169 341.049 C 79.710 362.590,89.700 366.556,96.075 356.100 C 100.835 348.293,98.858 344.529,82.680 330.598 C 78.525 327.021,72.198 320.694,68.620 316.539 C 54.690 300.361,50.926 298.384,43.119 303.144 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                            <span
                                class="flex flex-col items-start ms-2 duration-200 ease-in-out group-hover:text-indigo-900 group-focus:text-indigo-900">
                                <p class="text-sm font-semibold">Audit Activities</p>
                                <p class="text-xs text-gray-500">Activity Logs</p>
                            </span>
                        </button>
                    </div>

                    {{-- Counters --}}
                    <p class="text-sm mt-10">
                        Here are some <strong>updates</strong> and <strong>activities</strong> that happened while you
                        were
                        away:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 items-center justify-center my-3 gap-2 sm:gap-4">

                        {{-- Activities Table --}}
                        <div class="flex flex-col justify-center items-start">
                            <p class="text-indigo-1100 text-lg font-bold mb-2"><span
                                    class="text-indigo-700 me-2">#</span>Activity Logs</p>
                            <div id="activities-table"
                                class="relative h-[28vh] w-full rounded overflow-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                <table class="relative text-xs w-full text-left text-indigo-1100 whitespace-nowrap">
                                    <thead class="z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                        <tr>
                                            <th scope="col" class="pe-2 ps-4 py-2">
                                                datetime
                                            </th>
                                            <th scope="col" class="pe-6 py-2">
                                                log description
                                            </th>
                                            <th scope="col" class="pe-2 py-2">
                                                sender
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-gray-100">
                                        @foreach ($this->activities as $activity)
                                            <tr class="">
                                                <td class="pe-2 ps-4 py-2">{{ $activity['log_timestamp'] }}</td>
                                                <td class="pe-6 py-2">
                                                    {{ fake()->sentence($nbWords = mt_rand(4, 9), $variableNbWords = true) }}
                                                </td>
                                                <td class="pe-6 py-2">{{ $activity['user'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Batches Counts --}}
                        <div class="flex flex-col justify-center items-start">
                            <p class="text-indigo-1100 text-lg font-bold mb-2"><span
                                    class="text-indigo-700 me-2">#</span>Batch Updates</p>
                            <div
                                class="relative flex flex-col items-center justify-center gap-2 sm:gap-4 h-[28vh] w-full">
                                {{-- New Submitted Batches --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-green-1100 hover:bg-green-50 focus:bg-green-50 border-green-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold h-[90%] aspect-square text-green-50 bg-green-700 duration-200 ease-in-out rounded">
                                        13
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">New Submitted Batches</p>
                                    </span>
                                </div>

                                {{-- New Opened Batches --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-indigo-1100 hover:bg-indigo-50 focus:bg-indigo-50 border-indigo-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold size-8 text-indigo-50 bg-indigo-700 duration-200 ease-in-out rounded">
                                        5
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">New Opened Batches</p>
                                    </span>
                                </div>

                                {{-- New Opened Batches --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-red-950 hover:bg-red-50 focus:bg-red-50 border-red-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold size-8 text-red-50 bg-red-700 duration-200 ease-in-out rounded">
                                        4
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">Revalidating Submissions</p>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
