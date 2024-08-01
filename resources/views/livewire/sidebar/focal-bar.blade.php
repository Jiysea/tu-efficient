<nav>
    <!-- Show drawer on certain screen size -->
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-indigo-500 rounded-lg lg:hidden hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-indigo-900">
            <div class="text-center text-indigo-50 text-3xl font-bold whitespace-nowrap mt-3 mb-5">TU-EFFICIENT</div>

            <ul class="space-y-2 font-medium">
                <li>
                    <a href="#" wire:click="setCurrentPage('dashboard')" wire:loading.attr="disabled"
                        class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-200 focus:text-indigo-200 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 transition duration-75 ease-in-out group">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="flex-shrink-0 w-5 h-5 text-indigo-50 group-hover:text-indigo-200 group-focus:text-indigo-200"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M19.012 1.437 C 11.395 4.085,4.454 10.951,1.479 18.780 C 0.018 22.627,-0.039 24.744,0.171 67.845 L 0.391 112.891 3.039 117.775 C 6.257 123.708,11.126 128.347,17.051 131.125 L 21.484 133.203 91.797 133.203 L 162.109 133.203 166.787 130.907 C 172.745 127.981,177.997 122.729,180.914 116.780 L 183.203 112.109 183.203 66.797 L 183.203 21.484 181.125 17.051 C 178.347 11.126,173.708 6.257,167.775 3.039 L 162.891 0.391 92.969 0.212 C 27.452 0.045,22.793 0.122,19.012 1.437 M236.597 1.080 C 228.602 3.960,222.381 9.575,218.856 17.094 L 216.797 21.484 216.797 116.797 L 216.797 212.109 219.093 216.787 C 222.019 222.745,227.271 227.997,233.220 230.914 L 237.891 233.203 308.203 233.203 L 378.516 233.203 382.949 231.125 C 388.874 228.347,393.743 223.708,396.961 217.775 L 399.609 212.891 399.609 116.797 L 399.609 20.703 397.486 16.380 C 394.776 10.862,389.138 5.224,383.620 2.514 L 379.297 0.391 309.375 0.221 C 253.179 0.085,238.892 0.254,236.597 1.080 M20.536 167.531 C 12.785 169.974,6.632 175.263,2.566 182.980 L 0.391 187.109 0.391 283.203 L 0.391 379.297 2.514 383.620 C 5.224 389.138,10.862 394.776,16.380 397.486 L 20.703 399.609 91.797 399.609 L 162.891 399.609 167.775 396.961 C 173.708 393.743,178.347 388.874,181.125 382.949 L 183.203 378.516 183.203 283.203 L 183.203 187.891 180.907 183.213 C 177.981 177.255,172.745 172.019,166.787 169.093 L 162.109 166.797 92.969 166.645 C 37.339 166.523,23.185 166.697,20.536 167.531 M236.092 267.944 C 230.294 270.043,225.117 273.983,221.651 278.935 C 216.283 286.604,216.330 286.088,216.576 334.857 L 216.797 378.516 218.875 382.949 C 221.653 388.874,226.292 393.743,232.225 396.961 L 237.109 399.609 308.203 399.609 L 379.297 399.609 383.620 397.486 C 389.138 394.776,394.776 389.138,397.486 383.620 L 399.609 379.297 399.609 333.203 L 399.609 287.109 396.961 282.225 C 393.743 276.292,388.874 271.653,382.949 268.875 L 378.516 266.797 309.375 266.621 C 242.928 266.452,240.073 266.504,236.092 267.944 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                        <span class="flex ms-3 whitespace-nowrap ">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" wire:click="setCurrentPage('implementations')" wire:loading.attr="disabled"
                        class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-200 focus:text-indigo-200 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 transition duration-75 ease-in-out group">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="flex-shrink-0 w-6 h-6 text-indigo-50 group-hover:text-indigo-200 group-focus:text-indigo-200"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M53.906 51.221 C 39.995 54.908,27.658 65.001,21.471 77.756 C 16.088 88.855,16.357 81.997,16.588 202.133 L 16.797 310.547 18.890 316.277 C 24.292 331.065,34.839 341.735,49.811 347.560 L 55.078 349.609 184.312 349.816 C 327.345 350.045,317.483 350.378,328.516 344.950 C 338.363 340.105,346.265 332.146,351.257 322.041 C 355.003 314.462,379.688 193.540,379.688 182.774 C 379.688 169.710,372.772 158.686,360.938 152.881 L 355.859 150.391 246.154 150.185 C 124.740 149.957,132.449 149.656,121.484 155.050 C 111.485 159.969,103.795 167.734,98.691 178.063 C 96.032 183.444,94.854 188.707,82.556 250.137 L 69.290 316.406 67.262 316.402 C 61.198 316.389,54.702 312.188,51.838 306.427 C 49.582 301.889,49.582 98.111,51.838 93.573 C 56.558 84.079,58.687 83.603,96.471 83.598 L 127.709 83.594 151.263 99.279 C 164.218 107.907,176.177 115.450,177.838 116.042 C 180.289 116.916,190.903 117.125,234.057 117.153 L 287.254 117.188 290.697 119.005 C 294.922 121.236,298.406 125.998,298.988 130.339 L 299.424 133.594 316.509 133.594 L 333.594 133.594 333.594 130.203 C 333.594 111.647,318.357 91.911,298.996 85.388 L 292.578 83.227 240.149 82.824 L 187.719 82.422 163.671 66.406 L 139.624 50.391 98.913 50.236 C 65.372 50.109,57.446 50.283,53.906 51.221 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                        <span class="flex ms-3 whitespace-nowrap ">Implementations</span>
                    </a>
                </li>
                <li>
                    <a href="#" wire:click="setCurrentPage('user-management')" wire:loading.attr="disabled"
                        class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-200 focus:text-indigo-200 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 transition duration-75 ease-in-out group">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="flex-shrink-0 w-6 h-6 text-indigo-50 group-hover:text-indigo-200  group-focus:text-indigo-200"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M179.297 21.169 C 104.101 31.859,73.033 123.800,126.530 177.329 C 179.392 230.224,269.545 201.449,282.429 127.570 C 292.969 67.125,240.054 12.533,179.297 21.169 M276.172 205.112 C 218.214 215.924,187.133 278.009,213.222 330.859 C 245.879 397.017,342.184 394.911,371.981 327.388 C 400.252 263.323,344.634 192.342,276.172 205.112 M169.434 218.751 C 99.783 223.928,44.377 249.569,26.728 284.793 C 21.264 295.699,20.703 299.077,20.703 321.094 L 20.703 341.016 23.168 346.218 C 27.442 355.239,34.901 360.883,44.639 362.463 C 49.816 363.303,215.625 363.677,215.625 362.849 C 215.625 362.611,213.734 360.062,211.422 357.185 C 179.719 317.717,180.502 261.313,213.269 224.087 C 215.518 221.532,217.185 219.268,216.973 219.056 C 216.239 218.322,178.472 218.080,169.434 218.751 M300.880 241.001 C 309.269 246.514,310.030 258.978,302.400 265.872 C 291.856 275.398,275.781 268.311,275.781 254.135 C 275.781 241.042,289.789 233.712,300.880 241.001 M296.873 280.859 C 298.119 281.503,300.123 283.321,301.327 284.899 L 303.516 287.769 303.516 312.577 C 303.516 340.299,303.673 339.465,297.664 343.555 C 292.503 347.067,284.623 344.897,281.342 339.059 C 278.788 334.515,278.782 290.496,281.335 285.954 C 284.392 280.514,291.622 278.143,296.873 280.859 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                        <span class="flex ms-3 whitespace-nowrap ">User Management</span>
                    </a>
                </li>
                <li>
                    <a href="#" wire:click="setCurrentPage('logs')" wire:loading.attr="disabled"
                        class="flex items-center mx-2 p-2 text-indigo-50 rounded-lg hover:text-indigo-200 focus:text-indigo-200 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 transition duration-75 ease-in-out group">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="flex-shrink-0 w-6 h-6 text-indigo-50 group-hover:text-indigo-200  group-focus:text-indigo-200"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M180.078 1.302 C 143.059 5.833,111.262 17.691,84.961 36.774 L 79.687 40.601 79.670 37.293 C 79.603 24.218,63.660 18.996,57.027 29.876 C 54.847 33.451,54.641 68.722,56.773 73.216 C 61.119 82.375,69.392 81.756,81.011 71.404 C 169.962 -7.854,304.451 17.105,357.083 122.640 C 414.658 238.086,329.418 375.060,200.000 375.060 C 73.940 375.060,-11.873 243.556,39.639 129.316 C 46.135 114.911,42.971 106.250,31.214 106.250 C 22.808 106.250,18.856 112.135,10.603 136.944 C -41.117 292.418,107.579 440.985,263.213 389.336 C 411.906 339.991,447.615 143.382,325.781 44.849 C 285.737 12.463,228.547 -4.630,180.078 1.302 M178.689 60.594 C 92.178 74.057,38.789 161.705,66.460 244.838 C 90.958 318.441,170.855 358.172,244.838 333.541 C 314.548 310.333,354.574 236.970,336.588 165.373 C 318.993 95.334,249.932 49.506,178.689 60.594 M205.597 120.117 C 211.461 123.568,211.328 122.542,211.328 164.453 C 211.328 194.426,211.114 202.462,210.259 204.486 C 209.289 206.785,151.556 254.108,146.272 256.936 C 139.328 260.652,130.478 255.004,130.472 246.853 C 130.467 241.412,131.828 240.071,160.938 216.834 L 187.891 195.318 188.281 161.136 C 188.714 123.265,188.640 123.799,193.916 120.421 C 197.102 118.381,202.413 118.243,205.597 120.117 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                        <span class="flex ms-3 whitespace-nowrap ">Logs</span>
                    </a>
                </li>
            </ul>
            <ul class="absolute left-0 bottom-0 w-full font-medium">
                <li>
                    <a href="#"
                        class="flex items-center justify-center py-2 text-indigo-50 hover:text-indigo-200 focus:text-indigo-200 hover:bg-indigo-1000 focus:outline-none focus:bg-indigo-1000 transition duration-75 ease-in-out group">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="flex-shrink-0 w-9 h-9 text-indigo-50 transition duration-75 group-hover:text-indigo-200  group-focus:text-indigo-200">
                            <path fill-rule="evenodd"
                                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                clip-rule="evenodd" />
                        </svg>


                        <span class="flex whitespace-nowrap mx-2 text-sm">Jerecho Suico</span>
                    </a>
                </li>

            </ul>
        </div>
    </aside>
</nav>
