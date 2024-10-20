<nav>
    <!-- Max SM -->
    <div x-data="{ open: false }" id="sm-sidebar" class="md:hidden">
        {{-- Collapse Button --}}
        <button type="button" @click="open = true;"
            class="flex items-center p-1 duration-200 ease-in-out outline-none rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:ring-2 focus:ring-blue-500">
            <span class="sr-only">Open sidebar</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="400" height="400" viewBox="0, 0, 400,400">
                <g>
                    <path
                        d="M51.524 83.877 C 29.294 89.872,26.521 120.983,47.299 131.283 L 51.172 133.203 165.373 133.409 L 279.574 133.614 284.123 131.944 C 306.430 123.755,304.941 90.477,281.980 84.033 C 276.187 82.407,57.523 82.259,51.524 83.877 M335.101 83.832 C 323.715 87.057,316.518 96.519,316.439 108.369 C 316.259 135.243,353.367 143.312,364.706 118.866 C 373.575 99.745,355.296 78.114,335.101 83.832 M51.524 183.877 C 29.294 189.872,26.521 220.983,47.299 231.283 L 51.172 233.203 165.373 233.409 L 279.574 233.614 284.123 231.944 C 306.430 223.755,304.941 190.477,281.980 184.033 C 276.187 182.407,57.523 182.259,51.524 183.877 M51.524 283.877 C 29.294 289.872,26.521 320.983,47.299 331.283 L 51.172 333.203 115.354 333.416 C 189.823 333.663,184.857 334.147,193.036 325.845 C 206.387 312.292,200.272 289.167,181.980 284.033 C 176.227 282.418,57.458 282.276,51.524 283.877 "
                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                </g>
            </svg>
        </button>

        {{-- Backdrop --}}
        <div x-cloak x-trap.noscroll="open" @keyup.escape.window="open = false;" @click.self="open = false;"
            :class="{
                'scale-0': !open,
                'scale-100': open,
            }"
            class="scale-0 fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

            {{-- Sidebar Panel --}}
            <div :class="{
                '-translate-x-96': !open,
                'translate-x-0': open,
            }"
                class="-translate-x-96 w-56 absolute flex flex-col justify-between left-0 top-0 z-50 h-full duration-300 ease-in-out p-4 select-none bg-blue-900">

                {{-- App Name | Navigation Content --}}
                <div class="flex flex-col gap-3">
                    <div :class="{
                        '-translate-x-96': !open,
                        'translate-x-0': open,
                    }"
                        class="-translate-x-96 inline-flex items-center justify-center gap-3">
                        {{-- Inner Collapse Button --}}
                        <button type="button" @click="open = false;"
                            class="origin-left p-1 duration-300 ease-in-out outline-none rounded bg-blue-50 hover:bg-blue-100 active:bg-blue-200 text-blue-900 focus:ring-2 focus:ring-blue-500">
                            <span class="sr-only">Open sidebar</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M51.524 83.877 C 29.294 89.872,26.521 120.983,47.299 131.283 L 51.172 133.203 165.373 133.409 L 279.574 133.614 284.123 131.944 C 306.430 123.755,304.941 90.477,281.980 84.033 C 276.187 82.407,57.523 82.259,51.524 83.877 M335.101 83.832 C 323.715 87.057,316.518 96.519,316.439 108.369 C 316.259 135.243,353.367 143.312,364.706 118.866 C 373.575 99.745,355.296 78.114,335.101 83.832 M51.524 183.877 C 29.294 189.872,26.521 220.983,47.299 231.283 L 51.172 233.203 165.373 233.409 L 279.574 233.614 284.123 231.944 C 306.430 223.755,304.941 190.477,281.980 184.033 C 276.187 182.407,57.523 182.259,51.524 183.877 M51.524 283.877 C 29.294 289.872,26.521 320.983,47.299 331.283 L 51.172 333.203 115.354 333.416 C 189.823 333.663,184.857 334.147,193.036 325.845 C 206.387 312.292,200.272 289.167,181.980 284.033 C 176.227 282.418,57.458 282.276,51.524 283.877 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </button>

                        {{-- App Name --}}
                        <span
                            class="origin-left text-center text-blue-50 text-xl font-bold whitespace-nowrap select-none duration-500 ease-in-out">
                            TU-EFFICIENT</span>
                    </div>

                    <hr class="border-blue-700">

                    {{-- Navigation Content --}}
                    <div class="flex flex-col gap-2 mt-2 text-sm font-medium">

                        {{-- Assignments --}}
                        <span x-data="{ hover: false }">
                            <a href="{{ route('coordinator.assignments') }}" wire:loading.attr="disabled"
                                @mouseover="hover = true" @mouseleave="hover = false"
                                class="flex items-center gap-3 p-2 rounded-lg outline-none duration-300 ease-in-out text-blue-50 hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 focus:bg-blue-1000 group">

                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    class="flex-shrink-0 size-5 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300 group-focus:text-blue-300"
                                    width="400" height="400" viewBox="0, 0, 400,400">

                                    <g>
                                        <path
                                            d="M52.121 19.553 C 37.751 22.005,25.538 34.811,23.456 49.609 C 22.410 57.049,22.415 289.091,23.461 296.484 C 26.029 314.621,40.470 326.563,59.835 326.563 L 67.188 326.563 67.207 333.398 C 67.260 352.385,77.874 366.295,95.093 369.944 C 101.105 371.218,243.750 371.688,243.750 370.434 C 243.750 370.070,243.064 369.152,242.225 368.393 C 236.232 362.970,226.793 344.975,223.767 333.203 C 206.640 266.590,271.906 207.052,336.719 230.165 C 340.801 231.621,344.229 232.812,344.336 232.812 C 345.560 232.814,344.345 91.728,343.086 87.684 C 338.478 72.877,326.202 63.960,309.667 63.409 L 300.975 63.119 300.594 54.802 C 299.823 37.995,290.981 25.523,276.288 20.519 C 271.900 19.024,60.558 18.114,52.121 19.553 M271.623 44.212 C 276.043 47.488,277.344 50.425,277.344 57.131 L 277.344 63.251 202.930 63.461 L 128.516 63.672 123.828 65.855 C 116.980 69.045,73.266 112.823,69.840 119.922 L 67.578 124.609 67.370 214.258 L 67.162 303.906 62.682 303.902 C 54.476 303.894,49.119 300.890,46.923 295.066 C 46.351 293.550,46.094 255.547,46.094 172.728 L 46.094 52.590 47.893 49.188 C 49.125 46.857,50.768 45.216,53.109 43.978 L 56.527 42.171 162.972 42.375 L 269.417 42.578 271.623 44.212 M142.188 107.851 C 142.188 132.309,142.099 132.786,136.920 136.306 L 134.012 138.281 112.319 138.281 L 90.625 138.281 90.625 134.212 L 90.625 130.144 111.914 108.753 C 134.200 86.361,134.553 86.067,139.258 85.987 L 142.188 85.938 142.188 107.851 M253.410 206.546 C 261.840 210.068,261.963 221.692,253.613 225.734 C 250.354 227.312,249.214 227.344,196.484 227.344 C 143.754 227.344,142.614 227.312,139.356 225.734 C 131.241 221.806,131.250 210.121,139.372 206.585 C 142.992 205.010,249.644 204.973,253.410 206.546 M296.609 243.787 C 238.982 253.459,218.722 327.056,263.336 364.658 C 305.528 400.220,370.876 374.034,376.466 319.326 C 381.120 273.784,341.452 236.261,296.609 243.787 M210.559 260.156 C 216.509 265.718,216.032 273.457,209.487 277.527 C 206.518 279.373,155.633 280.726,143.776 279.274 C 132.829 277.934,129.332 265.489,138.236 259.557 L 140.926 257.765 174.698 257.984 L 208.469 258.203 210.559 260.156 M337.222 291.519 C 342.137 294.470,343.465 301.536,339.987 306.232 C 337.180 310.022,302.341 337.430,299.700 337.925 C 291.520 339.460,271.825 316.157,274.227 307.785 C 277.130 297.663,287.953 297.247,295.587 306.965 C 300.155 312.779,297.765 313.573,313.111 301.144 C 328.774 288.458,330.786 287.655,337.222 291.519 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div :class="{
                                    '-translate-x-10 scale-0': !open,
                                    'translate-x-0 scale-100': open,
                                }"
                                    class="translate-x-0 scale-100 origin-left transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
                                    Assignments
                                </div>
                            </a>
                        </span>

                        {{-- Submissions --}}
                        <span x-data="{ hover: false }">
                            <a href="{{ route('coordinator.submissions') }}" wire:loading.attr="disabled"
                                @mouseover="hover = true" @mouseleave="hover = false"
                                class="flex items-center gap-3 p-2 rounded-lg outline-none duration-300 ease-in-out text-blue-50 hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 focus:bg-blue-1000 group">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    class="flex-shrink-0 size-5 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300 group-focus:text-blue-300"
                                    width="400" height="400" viewBox="0, 0, 400,400">

                                    <g>
                                        <path
                                            d="M140.234 33.642 C 69.424 36.276,42.271 55.293,36.283 106.445 C 35.642 111.928,35.288 111.951,41.418 106.109 C 70.846 78.058,86.204 75.051,200.000 75.051 C 313.756 75.051,330.040 78.249,358.605 106.199 C 364.445 111.914,364.546 111.870,363.330 104.121 C 356.479 60.447,334.986 41.083,287.246 35.572 C 265.335 33.042,184.780 31.985,140.234 33.642 M110.679 100.832 C 70.267 104.271,41.697 129.344,35.513 166.797 C 32.714 183.751,31.923 269.640,34.362 291.797 C 39.030 334.192,65.037 360.086,108.594 365.704 C 128.534 368.276,282.663 367.284,299.743 364.473 C 333.071 358.990,358.649 333.690,364.416 300.504 C 368.104 279.277,368.171 189.689,364.515 167.100 C 358.869 132.221,333.531 107.682,296.754 101.478 C 289.948 100.329,123.373 99.751,110.679 100.832 M206.941 173.765 C 213.857 178.558,252.130 218.235,253.160 221.680 C 255.818 230.563,247.235 238.737,237.742 236.363 C 236.382 236.022,231.180 231.483,224.066 224.429 L 212.586 213.043 212.348 249.744 L 212.109 286.445 210.031 289.585 C 204.895 297.342,193.687 296.922,189.246 288.806 C 188.024 286.573,187.867 282.709,187.652 249.686 L 187.414 213.043 175.934 224.429 C 168.820 231.483,163.618 236.022,162.258 236.363 C 152.770 238.735,144.197 230.577,146.828 221.680 C 148.004 217.704,190.471 174.444,195.313 172.290 C 198.917 170.686,203.300 171.242,206.941 173.765 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <div :class="{
                                    '-translate-x-10 scale-0': !open,
                                    'translate-x-0 scale-100': open,
                                }"
                                    class="translate-x-0 scale-100 origin-left transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
                                    Submissions
                                </div>
                            </a>
                        </span>
                    </div>
                </div>

                {{-- Account Area --}}
                <div x-data="{ account: false }" class="font-medium text-sm">

                    <div class="relative">
                        <button @click="account = !account"
                            class="flex items-center gap-2 w-full p-2 text-blue-50 rounded-lg hover:text-blue-300 focus:text-blue-300 hover:bg-blue-1000 outline-none group duration-300 ease-in-out cursor-pointer">

                            <svg class="text-center flex-shrink-0 size-6 text-blue-50 duration-300 ease-in-out group-hover:text-blue-300 group-focus:text-blue-300"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="400" height="400" viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M175.000 1.183 C 10.270 21.534,-58.998 223.923,58.539 341.461 C 176.367 459.289,378.987 389.487,398.870 224.219 C 414.431 94.879,304.345 -14.797,175.000 1.183 M219.922 89.410 C 273.284 105.349,287.192 176.084,243.717 210.429 C 204.642 241.298,147.672 223.655,133.103 176.172 C 129.899 165.731,129.896 146.790,133.096 136.328 C 144.315 99.650,183.434 78.511,219.922 89.410 M220.105 228.561 C 267.342 235.575,308.199 266.432,327.761 309.868 L 331.226 317.563 325.965 322.914 C 256.571 393.486,143.429 393.486,74.035 322.914 L 68.774 317.563 72.239 309.868 C 97.783 253.151,159.003 219.488,220.105 228.561 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <div :class="{
                                '-translate-x-10 scale-0': !open,
                                'translate-x-0 scale-100': open ===
                                    true,
                            }"
                                class="translate-x-0 scale-100 origin-left transition-transform duration-300 ease-in-out truncate select-none">
                                {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                            </div>
                        </button>

                        <!-- Profile Dropdown menu -->
                        <div x-show="account" @click.away="account = !account"
                            :class="{
                                'block': account,
                                'hidden': !account,
                            }"
                            class="hidden bottom-full mb-3 inset-x-0 mx-auto absolute text-blue-1100 bg-white shadow-lg border border-blue-100 rounded">
                            <div class="text-sm max-h-44">

                                {{-- Settings --}}
                                <span>
                                    <a href=" {{-- route('focal.settings') --}} " aria-label="{{ __('Settings') }}"
                                        class=" flex items-center text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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
                                </span>

                                {{-- Logout --}}
                                <span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" aria-label="{{ __('Logout') }}"
                                            class="flex items-center w-full text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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
                                    </form>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Min MD to XL --}}
    <div x-data="{ caretRotate: 0 }" id="logo-sidebar"
        :class="{
            'hidden md:block md:w-20': !open,
            'hidden md:block md:w-20 xl:w-64': open,
        }"
        class="fixed top-0 left-0 z-40 hidden md:block w-20 xl:w-64 h-full duration-500 ease-in-out select-none"
        aria-label="Sidebar">

        {{-- Sidebar Opener --}}
        <div :class="{
            '-right-3 hidden xl:block': open === true,
            '-right-4 hidden xl:block': open === false,
        }"
            class="absolute hidden xl:block -right-3 my-5 z-10 rounded-full place-items-center bg-white shadow-lg p-1 cursor-pointer">
            <svg wire:ignore @click="open = !open ; caretRotate += 180" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
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

        <div class="relative overflow-visible flex flex-col justify-between h-full px-3 w-full py-4 bg-blue-900">
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
                                '-translate-x-10 scale-0': open === false,
                                '-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100': open ===
                                    true,
                            }"
                                class="-translate-x-10 scale-0 xl:translate-x-0 xl:scale-100 xl:opacity-100 origin-left ms-3 transition-transform duration-300 ease-in-out whitespace-nowrap select-none">
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

                                {{-- Settings --}}
                                <li>
                                    <a aria-label="{{ __('Settings') }}"
                                        class=" flex items-center text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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

                                {{-- Logout --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" aria-label="{{ __('Logout') }}"
                                            class="flex items-center w-full text-blue-1100 px-4 justify-start py-2 hover:bg-blue-200 hover:text-blue-900 duration-300 ease-in-out cursor-pointer">
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
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
