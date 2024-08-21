<nav>
    <!-- Show drawer on certain screen size -->
    {{-- <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
        class="xl:hidden absolute inline-flex items-center p-2 mt-2 ms-3 text-sm text-indigo-500 rounded-lg hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button> --}}

    <aside id="logo-sidebar"
        :class="{
            'w-20': open === false,
            'w-20 xl:w-64': open === true,
        }"
        class="fixed top-0 left-0 z-40 w-20 xl:w-64 h-screen duration-500 ease-in-out select-none" aria-label="Sidebar">

        {{-- Sidebar Opener --}}
        <div :class="{
            '-right-3 hidden xl:block': open === true,
            '-right-4 hidden xl:block': open === false,
        }"
            class="absolute hidden xl:block -right-3 my-5 z-10 rounded-full place-items-center bg-white shadow-lg p-1 cursor-pointer">
            <svg wire:ignore @click="open = !open ; caretRotate += 180" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                :class="{
                    'size-4 xl:size-5': open === true,
                    'size-4': open === false,
                    'rotate-0': caretRotate % 360 === 0,
                    '-rotate-180': caretRotate % 360 === 180,
                }"
                class="size-4 xl:size-5 text-indigo-1100 w-full duration-500 ease-in-out">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
            </svg>
        </div>

        <div class="relative overflow-visible  flex flex-col justify-between h-full px-3 w-full py-4 bg-indigo-900">
            <div class="">
                <img :class="{
                    'translate-x-0 opacity-100 xl:-translate-x-16 xl:opacity-0': open === true,
                    'translate-x-0 opacity-100': open === false,
                }"
                    class="translate-x-0 opacity-100 xl:-translate-x-16 xl:opacity-0 absolute top-3 z-10 bg-indigo-100 p-1 rounded-lg object-contain size-10 ms-2 duration-500 ease-in-out select-none"
                    src="{{ asset('assets/f_logo.png') }}" alt="Focal logo">

                <div :class="{
                    '-translate-x-32 opacity-0': open === false,
                    '-translate-x-32 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                }"
                    class="-translate-x-32 opacity-0 xl:translate-x-0 xl:opacity-100 origin-right text-center text-indigo-50 text-2xl font-bold whitespace-nowrap mt-1 select-none ease-in-out duration-500">
                    TU-EFFICIENT</div>

                <div class="w-full border-t border-indigo-500 my-4"></div>


                <ul class="space-y-2 font-medium text-sm">

                    <li class="relative">
                        <a href="{{ route('focal.dashboard') }}" wire:loading.attr="disabled"
                            @mouseover="dashboardHover = true" @mouseleave="dashboardHover = false"
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-indigo-50 duration-300 ease-in-out group-hover:text-indigo-300 group-focus:text-indigo-300"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M19.012 1.437 C 11.395 4.085,4.454 10.951,1.479 18.780 C 0.018 22.627,-0.039 24.744,0.171 67.845 L 0.391 112.891 3.039 117.775 C 6.257 123.708,11.126 128.347,17.051 131.125 L 21.484 133.203 91.797 133.203 L 162.109 133.203 166.787 130.907 C 172.745 127.981,177.997 122.729,180.914 116.780 L 183.203 112.109 183.203 66.797 L 183.203 21.484 181.125 17.051 C 178.347 11.126,173.708 6.257,167.775 3.039 L 162.891 0.391 92.969 0.212 C 27.452 0.045,22.793 0.122,19.012 1.437 M236.597 1.080 C 228.602 3.960,222.381 9.575,218.856 17.094 L 216.797 21.484 216.797 116.797 L 216.797 212.109 219.093 216.787 C 222.019 222.745,227.271 227.997,233.220 230.914 L 237.891 233.203 308.203 233.203 L 378.516 233.203 382.949 231.125 C 388.874 228.347,393.743 223.708,396.961 217.775 L 399.609 212.891 399.609 116.797 L 399.609 20.703 397.486 16.380 C 394.776 10.862,389.138 5.224,383.620 2.514 L 379.297 0.391 309.375 0.221 C 253.179 0.085,238.892 0.254,236.597 1.080 M20.536 167.531 C 12.785 169.974,6.632 175.263,2.566 182.980 L 0.391 187.109 0.391 283.203 L 0.391 379.297 2.514 383.620 C 5.224 389.138,10.862 394.776,16.380 397.486 L 20.703 399.609 91.797 399.609 L 162.891 399.609 167.775 396.961 C 173.708 393.743,178.347 388.874,181.125 382.949 L 183.203 378.516 183.203 283.203 L 183.203 187.891 180.907 183.213 C 177.981 177.255,172.745 172.019,166.787 169.093 L 162.109 166.797 92.969 166.645 C 37.339 166.523,23.185 166.697,20.536 167.531 M236.092 267.944 C 230.294 270.043,225.117 273.983,221.651 278.935 C 216.283 286.604,216.330 286.088,216.576 334.857 L 216.797 378.516 218.875 382.949 C 221.653 388.874,226.292 393.743,232.225 396.961 L 237.109 399.609 308.203 399.609 L 379.297 399.609 383.620 397.486 C 389.138 394.776,394.776 389.138,397.486 383.620 L 399.609 379.297 399.609 333.203 L 399.609 287.109 396.961 282.225 C 393.743 276.292,388.874 271.653,382.949 268.875 L 378.516 266.797 309.375 266.621 C 242.928 266.452,240.073 266.504,236.092 267.944 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 opacity-0': open === false,
                                '-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                            }"
                                class="-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100 origin-right ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                Dashboard
                            </div>
                        </a>
                        <div x-show="dashboardHover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-indigo-50 border border-indigo-200 rounded shadow-lg">
                            <div class="px-3 py-2 font-semibold text-indigo-1100">
                                <p>Dashboard</p>
                            </div>
                        </div>
                    </li>
                    <li class="relative">
                        <a href="{{ route('focal.implementations') }}" wire:loading.attr="disabled"
                            @mouseover="implementationsHover = true" @mouseleave="implementationsHover = false"
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-indigo-50 duration-300 ease-in-out group-hover:text-indigo-300 group-focus:text-indigo-300"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M53.906 51.221 C 39.995 54.908,27.658 65.001,21.471 77.756 C 16.088 88.855,16.357 81.997,16.588 202.133 L 16.797 310.547 18.890 316.277 C 24.292 331.065,34.839 341.735,49.811 347.560 L 55.078 349.609 184.312 349.816 C 327.345 350.045,317.483 350.378,328.516 344.950 C 338.363 340.105,346.265 332.146,351.257 322.041 C 355.003 314.462,379.688 193.540,379.688 182.774 C 379.688 169.710,372.772 158.686,360.938 152.881 L 355.859 150.391 246.154 150.185 C 124.740 149.957,132.449 149.656,121.484 155.050 C 111.485 159.969,103.795 167.734,98.691 178.063 C 96.032 183.444,94.854 188.707,82.556 250.137 L 69.290 316.406 67.262 316.402 C 61.198 316.389,54.702 312.188,51.838 306.427 C 49.582 301.889,49.582 98.111,51.838 93.573 C 56.558 84.079,58.687 83.603,96.471 83.598 L 127.709 83.594 151.263 99.279 C 164.218 107.907,176.177 115.450,177.838 116.042 C 180.289 116.916,190.903 117.125,234.057 117.153 L 287.254 117.188 290.697 119.005 C 294.922 121.236,298.406 125.998,298.988 130.339 L 299.424 133.594 316.509 133.594 L 333.594 133.594 333.594 130.203 C 333.594 111.647,318.357 91.911,298.996 85.388 L 292.578 83.227 240.149 82.824 L 187.719 82.422 163.671 66.406 L 139.624 50.391 98.913 50.236 C 65.372 50.109,57.446 50.283,53.906 51.221 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 opacity-0': open === false,
                                '-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                            }"
                                class="-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100 origin-right ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                Implementations
                            </div>
                        </a>
                        <div x-show="implementationsHover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-indigo-50 border border-indigo-200 rounded shadow-lg">
                            <div class="px-3 py-2 font-semibold text-indigo-1100">
                                <p>Implementations</p>
                            </div>
                        </div>
                    </li>
                    <li class="relative">
                        <a href="{{ route('focal.user-management') }}" wire:loading.attr="disabled"
                            @mouseover="umanagementHover = true" @mouseleave="umanagementHover = false"
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">

                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-indigo-50 duration-300 ease-in-out group-hover:text-indigo-300  group-focus:text-indigo-300"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M179.297 21.169 C 104.101 31.859,73.033 123.800,126.530 177.329 C 179.392 230.224,269.545 201.449,282.429 127.570 C 292.969 67.125,240.054 12.533,179.297 21.169 M276.172 205.112 C 218.214 215.924,187.133 278.009,213.222 330.859 C 245.879 397.017,342.184 394.911,371.981 327.388 C 400.252 263.323,344.634 192.342,276.172 205.112 M169.434 218.751 C 99.783 223.928,44.377 249.569,26.728 284.793 C 21.264 295.699,20.703 299.077,20.703 321.094 L 20.703 341.016 23.168 346.218 C 27.442 355.239,34.901 360.883,44.639 362.463 C 49.816 363.303,215.625 363.677,215.625 362.849 C 215.625 362.611,213.734 360.062,211.422 357.185 C 179.719 317.717,180.502 261.313,213.269 224.087 C 215.518 221.532,217.185 219.268,216.973 219.056 C 216.239 218.322,178.472 218.080,169.434 218.751 M300.880 241.001 C 309.269 246.514,310.030 258.978,302.400 265.872 C 291.856 275.398,275.781 268.311,275.781 254.135 C 275.781 241.042,289.789 233.712,300.880 241.001 M296.873 280.859 C 298.119 281.503,300.123 283.321,301.327 284.899 L 303.516 287.769 303.516 312.577 C 303.516 340.299,303.673 339.465,297.664 343.555 C 292.503 347.067,284.623 344.897,281.342 339.059 C 278.788 334.515,278.782 290.496,281.335 285.954 C 284.392 280.514,291.622 278.143,296.873 280.859 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 opacity-0': open === false,
                                '-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                            }"
                                class="-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100 origin-right ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                User
                                Management
                            </div>
                        </a>
                        <div x-show="umanagementHover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-indigo-50 border border-indigo-200 rounded shadow-lg">
                            <div class="px-3 py-2 whitespace-nowrap font-semibold text-indigo-1100">
                                <p>User Management</p>
                            </div>
                        </div>
                    </li>
                    <li class="relative">
                        <a href="{{ route('focal.activity-logs') }}" wire:loading.attr="disabled"
                            @mouseover="alogsHover = true" @mouseleave="alogsHover = false"
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-indigo-50 duration-300 ease-in-out group-hover:text-indigo-300  group-focus:text-indigo-300"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M180.078 1.302 C 143.059 5.833,111.262 17.691,84.961 36.774 L 79.687 40.601 79.670 37.293 C 79.603 24.218,63.660 18.996,57.027 29.876 C 54.847 33.451,54.641 68.722,56.773 73.216 C 61.119 82.375,69.392 81.756,81.011 71.404 C 169.962 -7.854,304.451 17.105,357.083 122.640 C 414.658 238.086,329.418 375.060,200.000 375.060 C 73.940 375.060,-11.873 243.556,39.639 129.316 C 46.135 114.911,42.971 106.250,31.214 106.250 C 22.808 106.250,18.856 112.135,10.603 136.944 C -41.117 292.418,107.579 440.985,263.213 389.336 C 411.906 339.991,447.615 143.382,325.781 44.849 C 285.737 12.463,228.547 -4.630,180.078 1.302 M178.689 60.594 C 92.178 74.057,38.789 161.705,66.460 244.838 C 90.958 318.441,170.855 358.172,244.838 333.541 C 314.548 310.333,354.574 236.970,336.588 165.373 C 318.993 95.334,249.932 49.506,178.689 60.594 M205.597 120.117 C 211.461 123.568,211.328 122.542,211.328 164.453 C 211.328 194.426,211.114 202.462,210.259 204.486 C 209.289 206.785,151.556 254.108,146.272 256.936 C 139.328 260.652,130.478 255.004,130.472 246.853 C 130.467 241.412,131.828 240.071,160.938 216.834 L 187.891 195.318 188.281 161.136 C 188.714 123.265,188.640 123.799,193.916 120.421 C 197.102 118.381,202.413 118.243,205.597 120.117 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 opacity-0': open === false,
                                '-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                            }"
                                class="-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100 origin-right ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                Activity
                                Logs
                            </div>
                        </a>
                        <div x-show="alogsHover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-indigo-50 border border-indigo-200 rounded shadow-lg">
                            <div class="px-3 py-2 whitespace-nowrap font-semibold text-indigo-1100">
                                <p>Activity Logs</p>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
            <div>
                <ul class="font-medium text-sm">
                    <li class="relative">
                        <button @click="profileShow = !profileShow"
                            class="flex items-center w-full px-3 py-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 focus:outline-none group duration-300 ease-in-out cursor-pointer">

                            <svg class="text-center flex-shrink-0 size-8 text-indigo-50 duration-300 ease-in-out group-hover:text-indigo-300 group-focus:text-indigo-300"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="400" height="400" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M175.000 1.183 C 10.270 21.534,-58.998 223.923,58.539 341.461 C 176.367 459.289,378.987 389.487,398.870 224.219 C 414.431 94.879,304.345 -14.797,175.000 1.183 M219.922 89.410 C 273.284 105.349,287.192 176.084,243.717 210.429 C 204.642 241.298,147.672 223.655,133.103 176.172 C 129.899 165.731,129.896 146.790,133.096 136.328 C 144.315 99.650,183.434 78.511,219.922 89.410 M220.105 228.561 C 267.342 235.575,308.199 266.432,327.761 309.868 L 331.226 317.563 325.965 322.914 C 256.571 393.486,143.429 393.486,74.035 322.914 L 68.774 317.563 72.239 309.868 C 97.783 253.151,159.003 219.488,220.105 228.561 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 opacity-0': open === false,
                                '-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                            }"
                                class="-translate-x-10 opacity-0 xl:translate-x-0 xl:opacity-100 origin-right ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                            </div>
                        </button>
                        <!-- Profile Dropdown menu -->
                        <div x-show="profileShow" @click.away="profileShow = !profileShow"
                            :class="{
                                'block': profileShow === true,
                                'hidden': profileShow === false,
                            }"
                            class="hidden -bottom-0 left-full xl:bottom-full xl:left-1/4 xl:mb-3 absolute text-indigo-1100 bg-white shadow-lg border border-indigo-100 rounded">
                            <ul class="text-sm max-h-44">
                                <li>
                                    <a aria-label="{{ __('Profile') }}"
                                        class="flex items-center text-indigo-1100 px-4 justify-start py-2 hover:bg-indigo-200 hover:text-indigo-900 duration-300 ease-in-out cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-5 me-2">
                                            <path fill-rule="evenodd"
                                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a aria-label="{{ __('Settings') }}"
                                        class=" flex items-center text-indigo-1100 px-4 justify-start py-2 hover:bg-indigo-200 hover:text-indigo-900 duration-300 ease-in-out cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-5 me-2">
                                            <path fill-rule="evenodd"
                                                d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Settings
                                    </a>
                                </li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li>

                                        <button type="submit" aria-label="{{ __('Logout') }}"
                                            class="flex items-center w-full text-indigo-1100 px-4 justify-start py-2 hover:bg-indigo-200 hover:text-indigo-900 duration-300 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 me-2">
                                                <path fill-rule="evenodd"
                                                    d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Logout
                                        </button>

                                    </li>
                                </form>

                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</nav>
