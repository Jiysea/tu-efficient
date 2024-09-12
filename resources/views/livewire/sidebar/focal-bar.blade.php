<nav>
    <!-- Show drawer on certain screen size -->
    {{-- <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
        class="xl:hidden absolute inline-flex items-center p-2 mt-2 ms-3 text-sm text-indigo-500 rounded-lg hover:bg-indigo-100 outline-none focus:ring-2 focus:ring-indigo-200">
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
                    class="-translate-x-32 opacity-0 xl:translate-x-0 xl:opacity-100 origin-left text-center text-indigo-50 text-2xl font-bold whitespace-nowrap mt-1 select-none ease-in-out duration-500">
                    TU-EFFICIENT</div>

                <div class="w-full border-t border-indigo-500 my-4"></div>


                <ul class="space-y-2 font-medium text-sm">

                    <li class="relative">
                        <a href="{{ route('focal.dashboard') }}" wire:loading.attr="disabled"
                            @mouseover="dashboardHover = true" @mouseleave="dashboardHover = false"
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
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
                                '-translate-x-10 scale-0': open === false,
                                '-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100 origin-left ms-3 transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
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
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
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
                                '-translate-x-10 scale-0': open === false,
                                '-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100 origin-left ms-3 transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
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
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="flex-shrink-0 size-6 text-indigo-50 duration-300 ease-in-out group-hover:text-indigo-300  group-focus:text-indigo-300"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M182.813 38.986 C 123.313 52.113,100.226 125.496,141.415 170.564 C 183.488 216.599,261.606 197.040,276.896 136.644 C 291.453 79.146,240.501 26.259,182.813 38.986 M278.141 204.778 C 272.904 206.868,270.880 210.858,270.342 220.156 L 269.922 227.420 264.768 229.218 C 261.934 230.206,258.146 231.841,256.351 232.849 L 253.088 234.684 248.224 229.884 C 241.216 222.970,235.198 221.459,229.626 225.214 C 221.063 230.985,221.157 239.379,229.884 248.224 L 234.684 253.088 232.849 256.351 C 231.841 258.146,230.206 261.934,229.218 264.768 L 227.420 269.922 220.156 270.313 C 208.989 270.915,204.670 274.219,204.083 282.607 C 203.466 291.419,208.211 295.523,219.675 296.094 L 227.526 296.484 228.868 300.781 C 229.606 303.145,231.177 306.971,232.359 309.285 L 234.508 313.492 230.227 317.879 C 223.225 325.054,221.747 330.343,224.976 336.671 C 229.458 345.458,239.052 345.437,248.076 336.622 L 252.794 332.014 258.233 334.683 C 261.224 336.151,265.133 337.742,266.919 338.218 L 270.167 339.083 270.435 346.830 C 270.818 357.905,274.660 362.505,283.514 362.495 C 292.220 362.485,296.084 357.523,296.090 346.344 L 296.094 339.173 300.586 337.882 C 303.057 337.171,306.997 335.559,309.341 334.298 L 313.605 332.006 318.326 336.618 C 324.171 342.328,325.413 342.969,330.613 342.966 C 344.185 342.956,347.496 329.464,336.652 318.359 L 332.075 313.672 334.421 309.022 C 335.711 306.464,337.308 302.509,337.970 300.233 L 339.173 296.094 346.276 296.094 C 357.566 296.094,362.500 292.114,362.500 283.005 C 362.500 274.700,357.650 270.809,346.830 270.435 L 339.083 270.167 338.218 266.919 C 337.742 265.133,336.151 261.224,334.683 258.233 L 332.014 252.794 336.622 248.076 C 345.259 239.234,345.423 230.021,337.028 225.208 C 330.778 221.625,325.473 222.915,318.356 229.749 L 313.432 234.478 309.255 232.344 C 306.958 231.170,303.145 229.606,300.781 228.868 L 296.484 227.526 296.094 219.675 C 295.460 206.941,288.076 200.814,278.141 204.778 M140.625 220.855 C 91.525 226.114,53.906 267.246,53.906 315.674 C 53.906 333.608,63.031 349.447,77.831 357.207 C 88.240 362.664,85.847 362.500,155.113 362.500 L 217.422 362.500 214.329 360.259 C 202.518 351.704,196.602 335.289,200.309 321.365 L 201.381 317.339 196.198 313.914 C 172.048 297.955,174.729 264.426,201.338 249.629 C 201.430 249.578,200.995 247.619,200.371 245.276 C 198.499 238.241,199.126 229.043,201.981 221.680 C 202.483 220.383,151.436 219.698,140.625 220.855 M290.207 252.760 C 316.765 259.678,323.392 292.263,301.575 308.656 C 283.142 322.507,256.557 311.347,252.282 287.964 C 248.462 267.069,269.646 247.405,290.207 252.760 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 scale-0': open === false,
                                '-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100 origin-left ms-3 transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
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
                        <a href="{{-- route('focal.activity-logs') --}}" wire:loading.attr="disabled" @mouseover="alogsHover = true"
                            @mouseleave="alogsHover = false"
                            class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 outline-none focus:bg-indigo-1000 group duration-300 ease-in-out">
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
                                '-translate-x-10 scale-0': open === false,
                                '-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100 origin-left ms-3 transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
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
                            class="flex items-center w-full px-3 py-2 text-indigo-50 rounded-lg hover:text-indigo-300 focus:text-indigo-300 hover:bg-indigo-1000 outline-none group duration-300 ease-in-out cursor-pointer">

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
                                '-translate-x-10 scale-0 opacity-0': open === false,
                                '-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100 origin-left ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 me-2"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M182.813 38.986 C 133.336 49.902,106.480 104.390,127.973 150.254 C 159.471 217.470,258.751 208.316,276.928 136.519 C 291.440 79.197,240.383 26.285,182.813 38.986 M210.547 64.172 C 234.701 68.412,253.447 91.229,253.463 116.406 C 253.494 166.508,190.733 189.409,158.901 150.910 C 126.713 111.982,160.420 55.372,210.547 64.172 M140.625 220.835 C 91.174 226.492,53.906 267.234,53.906 315.639 C 53.906 337.642,67.630 356.006,88.003 361.262 C 95.299 363.144,304.701 363.144,311.997 361.262 C 370.247 346.234,349.547 250.430,282.813 226.190 C 267.722 220.709,269.266 220.816,203.125 220.662 C 170.254 220.585,142.129 220.663,140.625 220.835 M265.524 247.911 C 296.763 256.284,318.236 281.590,320.063 312.187 C 320.759 323.829,316.827 331.310,307.821 335.478 C 302.259 338.053,97.741 338.053,92.179 335.478 C 83.173 331.310,79.241 323.829,79.937 312.187 C 81.894 279.407,107.358 251.869,140.265 246.944 C 149.590 245.549,259.886 246.400,265.524 247.911 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('settings') }}" aria-label="{{ __('Settings') }}"
                                        class=" flex items-center text-indigo-1100 px-4 justify-start py-2 hover:bg-indigo-200 hover:text-indigo-900 duration-300 ease-in-out cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 me-2"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M169.791 37.446 C 159.521 39.983,154.916 46.610,153.195 61.328 C 150.103 87.782,125.260 100.798,100.400 88.990 C 91.098 84.572,80.525 85.974,74.417 92.435 C 70.069 97.034,44.631 142.228,43.737 146.944 C 42.004 156.081,45.632 163.680,54.680 169.862 C 77.393 185.381,77.344 214.250,54.578 230.202 C 45.367 236.657,41.780 244.278,43.712 253.288 C 44.649 257.658,70.219 303.075,74.120 307.299 C 80.791 314.522,90.687 315.567,102.157 310.258 C 125.808 299.312,150.314 313.137,153.235 339.075 C 154.645 351.585,157.204 356.279,164.957 360.573 L 169.141 362.891 199.219 362.891 C 232.900 362.891,233.102 362.863,238.867 357.549 C 243.779 353.022,245.331 349.485,246.135 340.994 C 248.786 312.968,273.031 299.027,298.783 310.719 C 309.247 315.469,318.520 314.365,325.055 307.590 C 328.255 304.273,354.462 259.086,355.622 254.885 C 358.247 245.380,353.793 235.386,344.448 229.816 C 323.182 217.139,323.182 182.861,344.448 170.184 C 353.844 164.583,358.675 153.393,355.558 144.450 C 353.932 139.788,327.785 95.103,325.070 92.349 C 318.448 85.630,309.202 84.551,298.783 89.281 C 273.031 100.973,248.786 87.032,246.135 59.006 C 245.143 48.529,241.051 42.044,233.203 38.515 C 229.822 36.994,175.338 36.076,169.791 37.446 M221.282 67.578 C 225.968 105.145,269.552 130.323,304.144 115.448 C 308.801 113.445,307.517 112.022,319.091 132.008 L 328.694 148.591 320.715 156.522 C 307.590 169.569,302.760 181.266,302.760 200.000 C 302.760 218.734,307.590 230.431,320.715 243.478 L 328.694 251.409 319.091 267.992 C 307.562 287.900,308.960 286.367,304.091 284.448 C 268.225 270.311,225.979 294.785,221.282 332.422 L 220.697 337.109 200.000 337.109 L 179.303 337.109 178.718 332.422 C 174.019 294.773,131.787 270.306,95.909 284.448 C 91.040 286.367,92.438 287.900,80.909 267.992 L 71.306 251.409 79.249 243.478 C 104.134 218.632,104.134 181.368,79.249 156.522 L 71.306 148.591 80.909 132.008 C 92.483 112.022,91.199 113.445,95.856 115.448 C 119.007 125.403,150.569 116.772,166.510 96.127 C 173.107 87.583,178.906 73.442,178.906 65.901 C 178.906 62.389,178.443 62.459,200.335 62.683 L 220.697 62.891 221.282 67.578 M184.375 139.096 C 128.823 153.410,120.319 230.465,171.484 255.896 C 213.899 276.977,262.500 247.129,262.500 200.000 C 262.500 158.887,224.012 128.883,184.375 139.096 M211.338 164.847 C 237.172 173.735,245.247 205.362,226.736 225.152 C 204.024 249.432,163.281 233.283,163.281 200.000 C 163.281 175.176,188.258 156.906,211.338 164.847 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        Settings
                                    </a>
                                </li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li>

                                        <button type="submit" aria-label="{{ __('Logout') }}"
                                            class="flex items-center w-full text-indigo-1100 px-4 justify-start py-2 hover:bg-indigo-200 hover:text-indigo-900 duration-300 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 me-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M88.906 55.116 C 72.737 59.037,58.917 72.987,55.080 89.262 C 53.353 96.583,53.353 303.417,55.080 310.738 C 58.953 327.164,72.833 341.047,89.255 344.919 C 98.487 347.095,152.689 346.586,156.488 344.287 C 164.643 339.350,164.791 328.277,156.780 322.337 C 154.656 320.763,153.476 320.689,124.358 320.313 C 96.837 319.957,93.897 319.792,91.406 318.467 C 87.589 316.437,83.759 312.668,81.767 308.984 L 80.078 305.859 79.862 201.172 C 79.621 84.445,79.239 92.939,85.018 86.542 C 90.911 80.020,90.290 80.128,124.358 79.688 C 153.476 79.311,154.656 79.237,156.780 77.663 C 164.791 71.723,164.643 60.650,156.488 55.713 C 152.798 53.480,97.664 52.993,88.906 55.116 M277.734 138.410 C 272.384 141.362,269.763 146.682,270.731 152.627 C 271.205 155.538,272.821 157.434,286.971 171.680 L 302.684 187.500 224.194 187.503 C 147.250 187.506,145.653 187.536,143.157 189.058 C 135.449 193.758,135.449 206.242,143.157 210.942 C 145.653 212.464,147.250 212.494,224.194 212.497 L 302.684 212.500 286.971 228.320 C 269.762 245.646,269.137 246.639,271.027 253.660 C 272.989 260.945,283.751 265.266,289.865 261.223 C 293.732 258.665,343.117 208.903,344.584 206.086 C 346.508 202.392,346.510 197.614,344.589 193.914 C 342.648 190.177,291.719 139.416,288.816 138.326 C 285.972 137.258,279.737 137.305,277.734 138.410 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
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
