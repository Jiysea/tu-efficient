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
                class="size-4 xl:size-5 text-blue-1100 w-full duration-500 ease-in-out">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
            </svg>
        </div>

        <div class="relative overflow-visible  flex flex-col justify-between h-full px-3 w-full py-4 bg-blue-900">
            <div class="">
                <img :class="{
                    'translate-x-0 opacity-100 xl:-translate-x-16 xl:opacity-0': open === true,
                    'translate-x-0 opacity-100': open === false,
                }"
                    class="translate-x-0 opacity-100 xl:-translate-x-16 xl:opacity-0 absolute top-3 z-10 bg-blue-100 p-1 rounded-lg object-contain size-10 ms-2 duration-500 ease-in-out select-none"
                    src="{{ asset('assets/c_logo.png') }}" alt="Focal logo">

                <div :class="{
                    '-translate-x-32 opacity-0': open === false,
                    '-translate-x-32 opacity-0 xl:translate-x-0 xl:opacity-100': open === true,
                }"
                    class="-translate-x-32 opacity-0 xl:translate-x-0 xl:opacity-100 origin-left text-center text-blue-50 text-2xl font-bold whitespace-nowrap mt-1 select-none ease-in-out duration-500">
                    TU-EFFICIENT</div>

                <div class="w-full border-t border-blue-500 my-4"></div>


                <ul class="space-y-2 font-medium text-sm">

                    <li x-data="{ hover: false }" class="relative">
                        <a href="{{ route('coordinator.assignments') }}" wire:loading.attr="disabled"
                            @mouseover="hover = true" @mouseleave="hover = false"
                            class="flex items-center mx-2 p-2 text-blue-50 rounded-lg hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 focus:outline-none focus:bg-blue-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300 group-focus:text-blue-300"
                                width="400" height="400" viewBox="0, 0, 400,400">

                                <g>
                                    <path
                                        d="M52.121 19.553 C 37.751 22.005,25.538 34.811,23.456 49.609 C 22.410 57.049,22.415 289.091,23.461 296.484 C 26.029 314.621,40.470 326.563,59.835 326.563 L 67.188 326.563 67.207 333.398 C 67.260 352.385,77.874 366.295,95.093 369.944 C 101.105 371.218,243.750 371.688,243.750 370.434 C 243.750 370.070,243.064 369.152,242.225 368.393 C 236.232 362.970,226.793 344.975,223.767 333.203 C 206.640 266.590,271.906 207.052,336.719 230.165 C 340.801 231.621,344.229 232.812,344.336 232.812 C 345.560 232.814,344.345 91.728,343.086 87.684 C 338.478 72.877,326.202 63.960,309.667 63.409 L 300.975 63.119 300.594 54.802 C 299.823 37.995,290.981 25.523,276.288 20.519 C 271.900 19.024,60.558 18.114,52.121 19.553 M271.623 44.212 C 276.043 47.488,277.344 50.425,277.344 57.131 L 277.344 63.251 202.930 63.461 L 128.516 63.672 123.828 65.855 C 116.980 69.045,73.266 112.823,69.840 119.922 L 67.578 124.609 67.370 214.258 L 67.162 303.906 62.682 303.902 C 54.476 303.894,49.119 300.890,46.923 295.066 C 46.351 293.550,46.094 255.547,46.094 172.728 L 46.094 52.590 47.893 49.188 C 49.125 46.857,50.768 45.216,53.109 43.978 L 56.527 42.171 162.972 42.375 L 269.417 42.578 271.623 44.212 M142.188 107.851 C 142.188 132.309,142.099 132.786,136.920 136.306 L 134.012 138.281 112.319 138.281 L 90.625 138.281 90.625 134.212 L 90.625 130.144 111.914 108.753 C 134.200 86.361,134.553 86.067,139.258 85.987 L 142.188 85.938 142.188 107.851 M253.410 206.546 C 261.840 210.068,261.963 221.692,253.613 225.734 C 250.354 227.312,249.214 227.344,196.484 227.344 C 143.754 227.344,142.614 227.312,139.356 225.734 C 131.241 221.806,131.250 210.121,139.372 206.585 C 142.992 205.010,249.644 204.973,253.410 206.546 M296.609 243.787 C 238.982 253.459,218.722 327.056,263.336 364.658 C 305.528 400.220,370.876 374.034,376.466 319.326 C 381.120 273.784,341.452 236.261,296.609 243.787 M210.559 260.156 C 216.509 265.718,216.032 273.457,209.487 277.527 C 206.518 279.373,155.633 280.726,143.776 279.274 C 132.829 277.934,129.332 265.489,138.236 259.557 L 140.926 257.765 174.698 257.984 L 208.469 258.203 210.559 260.156 M337.222 291.519 C 342.137 294.470,343.465 301.536,339.987 306.232 C 337.180 310.022,302.341 337.430,299.700 337.925 C 291.520 339.460,271.825 316.157,274.227 307.785 C 277.130 297.663,287.953 297.247,295.587 306.965 C 300.155 312.779,297.765 313.573,313.111 301.144 C 328.774 288.458,330.786 287.655,337.222 291.519 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 scale-0 opacity-0': open === false,
                                '-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100 origin-left ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                Assignments
                            </div>
                        </a>
                        <div x-show="hover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-blue-50 border border-blue-200 rounded shadow-lg">
                            <div class="px-3 py-2 font-semibold text-blue-1100">
                                <p>Assignments</p>
                            </div>
                        </div>
                    </li>
                    <li x-data="{ hover: false }" class="relative">
                        <a href="{{ route('coordinator.submissions') }}" wire:loading.attr="disabled"
                            @mouseover="hover = true" @mouseleave="hover = false"
                            class="flex items-center mx-2 p-2 text-blue-50 rounded-lg hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 focus:outline-none focus:bg-blue-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300 group-focus:text-blue-300"
                                width="400" height="400" viewBox="0, 0, 400,400">

                                <g>
                                    <path
                                        d="M140.234 33.642 C 69.424 36.276,42.271 55.293,36.283 106.445 C 35.642 111.928,35.288 111.951,41.418 106.109 C 70.846 78.058,86.204 75.051,200.000 75.051 C 313.756 75.051,330.040 78.249,358.605 106.199 C 364.445 111.914,364.546 111.870,363.330 104.121 C 356.479 60.447,334.986 41.083,287.246 35.572 C 265.335 33.042,184.780 31.985,140.234 33.642 M110.679 100.832 C 70.267 104.271,41.697 129.344,35.513 166.797 C 32.714 183.751,31.923 269.640,34.362 291.797 C 39.030 334.192,65.037 360.086,108.594 365.704 C 128.534 368.276,282.663 367.284,299.743 364.473 C 333.071 358.990,358.649 333.690,364.416 300.504 C 368.104 279.277,368.171 189.689,364.515 167.100 C 358.869 132.221,333.531 107.682,296.754 101.478 C 289.948 100.329,123.373 99.751,110.679 100.832 M206.941 173.765 C 213.857 178.558,252.130 218.235,253.160 221.680 C 255.818 230.563,247.235 238.737,237.742 236.363 C 236.382 236.022,231.180 231.483,224.066 224.429 L 212.586 213.043 212.348 249.744 L 212.109 286.445 210.031 289.585 C 204.895 297.342,193.687 296.922,189.246 288.806 C 188.024 286.573,187.867 282.709,187.652 249.686 L 187.414 213.043 175.934 224.429 C 168.820 231.483,163.618 236.022,162.258 236.363 C 152.770 238.735,144.197 230.577,146.828 221.680 C 148.004 217.704,190.471 174.444,195.313 172.290 C 198.917 170.686,203.300 171.242,206.941 173.765 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 scale-0 opacity-0': open === false,
                                '-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100 origin-left ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                Submissions
                            </div>
                        </a>
                        <div x-show="hover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-blue-50 border border-blue-200 rounded shadow-lg">
                            <div class="px-3 py-2 font-semibold text-blue-1100">
                                <p>Submissions</p>
                            </div>
                        </div>
                    </li>
                    <li x-data="{ hover: false }" class="relative">
                        <a href="{{ route('coordinator.forms') }}" wire:loading.attr="disabled"
                            @mouseover="hover = true" @mouseleave="hover = false"
                            class="flex items-center mx-2 p-2 text-blue-50 rounded-lg hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 focus:outline-none focus:bg-blue-1000 group duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="flex-shrink-0 size-6 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300  group-focus:text-blue-300"
                                width="400" height="400" viewBox="0, 0, 400,400">

                                <g>
                                    <path
                                        d="M61.719 23.090 C 47.695 26.102,33.835 36.985,27.449 50.000 C 22.466 60.155,21.904 64.163,21.889 89.648 L 21.875 111.719 200.000 111.719 L 378.125 111.719 378.111 89.648 C 378.096 64.163,377.534 60.155,372.551 50.000 C 366.080 36.813,352.238 26.048,337.891 23.046 C 329.909 21.376,69.506 21.417,61.719 23.090 M142.200 62.384 C 150.641 66.870,150.679 78.728,142.266 83.456 C 137.622 86.066,82.753 85.804,79.206 83.155 C 71.538 77.427,71.754 68.089,79.680 62.695 C 83.361 60.190,137.562 59.920,142.200 62.384 M269.539 62.695 C 275.962 67.067,277.474 74.344,273.139 80.027 C 265.920 89.491,251.563 84.776,251.563 72.941 C 251.563 63.622,262.087 57.624,269.539 62.695 M320.320 62.695 C 326.743 67.067,328.255 74.344,323.920 80.027 C 316.676 89.524,302.344 84.773,302.344 72.875 C 302.344 63.596,312.879 57.631,320.320 62.695 M21.889 234.180 C 21.904 343.880,21.628 338.124,27.441 350.000 C 33.847 363.086,47.845 373.969,62.109 376.954 C 70.321 378.672,329.679 378.672,337.891 376.954 C 352.238 373.952,366.080 363.187,372.551 350.000 C 378.373 338.135,378.096 343.898,378.111 234.180 L 378.125 135.938 200.000 135.938 L 21.875 135.938 21.889 234.180 M121.484 177.396 C 140.156 186.210,140.832 211.806,122.656 221.813 C 106.374 230.778,86.468 218.807,86.377 199.995 C 86.290 182.026,105.329 169.770,121.484 177.396 M319.558 189.258 C 328.677 193.861,328.677 206.139,319.558 210.742 L 316.075 212.500 251.592 212.496 C 182.784 212.491,182.932 212.499,178.958 208.525 C 172.529 202.096,175.253 190.846,183.840 188.361 C 185.259 187.950,214.195 187.611,251.202 187.570 L 316.075 187.500 319.558 189.258 M121.566 278.940 C 140.168 287.916,140.793 313.390,122.656 323.376 C 106.374 332.341,86.468 320.369,86.377 301.558 C 86.290 283.600,105.558 271.215,121.566 278.940 M316.361 289.831 C 327.650 293.068,328.342 308.379,317.408 313.000 C 313.662 314.583,185.512 314.571,181.811 312.987 C 171.418 308.540,171.817 292.971,182.402 289.891 C 185.742 288.919,312.983 288.862,316.361 289.831 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>

                            <div :class="{
                                '-translate-x-10 scale-0 opacity-0': open === false,
                                '-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 opacity-0 xl:translate-x-0 xl:scale-100 xl:opacity-100 origin-left ms-3 duration-500 ease-in-out whitespace-nowrap select-none">
                                Forms
                            </div>
                        </a>
                        <div x-show="hover" x-transition
                            :class="{
                                'hidden': (open === true && isAboveBreakpoint === true),
                                'inline-block': (open === false && isAboveBreakpoint === true),
                            }"
                            class="absolute hidden left-full top-0 z-40 text-sm text-gray-500 bg-blue-50 border border-blue-200 rounded shadow-lg">
                            <div class="px-3 py-2 whitespace-nowrap font-semibold text-blue-1100">
                                <p>Forms</p>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
            <div>
                <ul class="font-medium text-sm">
                    <li x-data="{ show: false }" class="relative">
                        <button @click="show = !show"
                            class="flex items-center w-full px-3 py-2 text-blue-50 rounded-lg hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 focus:outline-none group duration-300 ease-in-out cursor-pointer">

                            <svg class="text-center flex-shrink-0 size-8 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300 group-focus:text-blue-300"
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
                        <div x-show="show" @click.away="show = !show"
                            :class="{
                                'block': show === true,
                                'hidden': show === false,
                            }"
                            class="hidden -bottom-0 left-full xl:bottom-full xl:left-1/4 xl:mb-3 absolute text-blue-1100 bg-white shadow-lg border border-blue-100 rounded">
                            <ul class="text-sm max-h-44">
                                <li>
                                    <a aria-label="{{ __('Profile') }}"
                                        class="flex items-center text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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
                                        class=" flex items-center text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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
                                            class="flex items-center w-full text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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
