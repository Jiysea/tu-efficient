<x-slot name="favicons">
    <x-f-favicons />
</x-slot>

<div class="relative flex flex-col px-12 py-12 items-center sm:max-h-screen md:px-12 lg:px-24 lg:py-24 select-none">
    <div class="flex justify-center items-center text-center bg-indigo-100 rounded-2xl sm:max-w-2xl sm:w-full">
        <div class="flex flex-col sm:grid items-center justify-center sm:grid-cols-2 size-full">
            <div class="pt-6 px-6 pb-2 sm:py-6 sm:ps-6 sm:pe-1 flex items-center justify-center size-2/5 sm:size-full">
                <img class="drop-shadow sm:drop-shadow-xl h-[85%]" src="{{ asset('assets/f_logo.png') }}"
                    alt="TU-Efficient | Focal Logo">
            </div>
            <div class="w-full px-6 py-3">

                <div class="mt-1 text-center sm:mt-5">
                    <div class="items-center w-full">
                        <h3 class="text-3xl md:text-4xl font-bold sm:tracking-tight text-indigo-1000 leading-6">
                            TU-EFFICIENT
                        </h3>
                    </div>
                    <div class="mt-2 text-sm sm:tracking-tight md:text-base text-indigo-900">
                        <p>Efficiently manage your workspace</p>
                    </div>
                </div>

                {{-- Forms Here --}}

                <div class="mt-6 space-y-2">
                    <form wire:submit.prevent="login" class="space-y-2">
                        <div class="relative pt-4">
                            @error('email')
                                <p class="text-red-500 absolute top-0 right-1 z-10 text-xs">{{ $message }}</p>
                            @enderror
                            <input type="text" wire:model.blur="email" id="email" autocomplete="off"
                                class="text-sm duration-200 ease-in-out border rounded-lg outline-none block w-full px-5 py-2 
                                {{ $errors->has('email')
                                    ? 'bg-red-200 border-red-500 focus:ring-red-500 focus:border-red-600 text-red-900 placeholder-red-600'
                                    : 'bg-indigo-200 border-indigo-500 focus:ring-indigo-500 focus:border-indigo-600 text-indigo-1000 placeholder-indigo-600' }}"
                                placeholder="Email">
                        </div>
                        <div class="relative pb-4">
                            <input type="password" wire:model.blur="password" id="password"
                                @keyup.enter="$wire.login();"
                                class="text-sm duration-200 ease-in-out border rounded-lg outline-none block w-full px-5 py-2 
                            {{ $errors->has('password')
                                ? 'bg-red-200 border-red-500 focus:ring-red-500 focus:border-red-600 text-red-900 placeholder-red-600'
                                : 'bg-indigo-200 border-indigo-500 focus:ring-indigo-500 focus:border-indigo-600 text-indigo-1000 placeholder-indigo-600' }}"
                                placeholder="Password">
                            @error('password')
                                <p class="text-red-500 absolute bottom-0 left-1 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col mt-4">
                            <button type="submit"
                                class="flex items-center justify-center w-full py-2 text-sm font-medium text-center text-indigo-50 duration-200 ease-in-out bg-indigo-700 rounded-lg hover:bg-indigo-800 active:bg-indigo-900 outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Sign
                                In
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ms-2"
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
                    </form>
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-indigo-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm my-4">
                            <span class="px-2 text-indigo-600 bg-indigo-100"> OR </span>
                        </div>
                    </div>
                    <form wire:submit.prevent="access">
                        @csrf
                        <div class="grid grid-cols-9 w-full place-items-stretch space-x-2">
                            <div class="relative col-span-7 flex w-full">
                                <span
                                    class="absolute h-full w-[20%] rounded-s-lg flex items-center justify-center pointer-events-none
                                    {{ $errors->has('access_code') ? 'bg-red-500 text-red-50' : 'bg-green-600 text-green-50' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M172.917 1.113 C 141.175 7.122,115.344 29.956,105.450 60.752 C 102.502 69.926,102.500 69.886,106.367 75.855 C 124.156 103.313,140.540 139.096,148.109 167.020 C 148.841 169.721,149.729 171.831,150.081 171.708 C 150.433 171.585,166.904 157.070,186.683 139.453 C 215.874 113.452,223.712 106.871,228.315 104.498 C 242.273 97.301,255.776 99.645,269.373 111.626 C 272.844 114.685,275.881 117.188,276.123 117.188 C 277.222 117.188,276.530 79.776,275.299 72.599 C 267.113 24.908,220.211 -7.841,172.917 1.113 M333.804 1.461 C 276.346 18.404,287.876 103.215,347.656 103.357 C 393.636 103.467,416.817 47.584,384.348 14.904 C 371.622 2.097,350.604 -3.494,333.804 1.461 M60.547 67.083 C 50.936 71.463,20.236 127.299,9.302 160.287 C -11.261 222.326,7.541 303.062,47.121 322.675 L 53.906 326.037 53.906 350.909 L 53.906 375.781 31.836 375.798 C 7.037 375.818,4.775 376.236,1.536 381.400 C -1.222 385.798,-0.362 392.971,3.372 396.705 L 6.276 399.609 200.000 399.609 L 393.724 399.609 396.628 396.705 C 399.264 394.069,399.558 393.348,399.808 388.893 C 400.359 379.115,395.747 375.826,381.445 375.798 L 372.656 375.781 372.656 321.026 L 372.656 266.271 377.377 270.216 C 387.590 278.749,399.219 274.889,399.219 262.966 C 399.219 257.291,398.178 256.045,379.715 239.607 L 362.109 223.933 361.719 191.849 C 361.252 153.478,360.817 151.930,350.254 151.020 C 339.330 150.078,337.976 153.137,337.500 179.826 L 337.109 201.729 293.554 163.026 C 248.589 123.072,248.714 123.170,243.134 123.250 C 238.400 123.317,242.554 119.829,162.871 190.653 C 88.409 256.836,88.281 256.960,88.281 262.966 C 88.281 274.889,99.910 278.749,110.123 270.216 L 114.844 266.271 114.844 321.026 L 114.844 375.781 96.484 375.781 L 78.125 375.781 78.125 350.753 L 78.125 325.726 81.836 324.137 C 83.877 323.263,87.040 321.606,88.866 320.454 L 92.185 318.359 92.186 307.593 C 92.187 298.028,92.035 296.776,90.820 296.383 C 69.671 289.527,59.560 266.606,69.206 247.384 C 72.191 241.436,73.783 239.856,102.344 214.499 C 117.168 201.338,129.414 190.036,129.557 189.384 C 131.629 179.959,113.626 133.015,96.427 102.994 C 76.758 68.662,70.602 62.501,60.547 67.083 M211.427 268.539 C 216.568 272.206,216.406 270.353,216.406 325.704 L 216.406 375.781 205.078 375.781 L 193.750 375.781 193.750 332.422 L 193.750 289.063 181.641 289.063 L 169.531 289.063 169.531 332.422 L 169.531 375.781 158.187 375.781 L 146.842 375.781 147.054 324.805 L 147.266 273.828 148.999 271.399 C 152.520 266.462,153.024 266.390,182.422 266.604 C 208.495 266.793,209.029 266.829,211.427 268.539 M331.062 267.965 C 336.789 271.456,336.713 270.949,336.713 305.859 C 336.713 349.341,340.756 345.306,297.186 345.310 C 253.775 345.314,257.701 349.358,257.995 304.939 C 258.277 262.339,254.089 266.408,297.656 266.410 C 327.072 266.412,328.635 266.485,331.062 267.965 M280.469 305.859 L 280.469 322.656 297.266 322.656 L 314.063 322.656 314.063 305.859 L 314.063 289.063 297.266 289.063 L 280.469 289.063 280.469 305.859 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </span>

                                <input type="text" wire:model.blur="access_code" id="access_code" autocomplete="off"
                                    class="text-sm w-full duration-200 ease-in-out border rounded-lg outline-none block ps-16 sm:ps-14 py-2
                                {{ $errors->has('access_code')
                                    ? 'bg-red-200 border-red-500 focus:ring-red-500 focus:border-red-600 text-red-900 placeholder-red-600'
                                    : 'bg-green-200 border-green-600 focus:ring-green-600 focus:border-green-600 text-green-1100 placeholder-green-900' }}"
                                    placeholder="Access Code">
                            </div>
                            <button type="submit"
                                class="col-span-2 flex justify-center items-center w-full px-2 py-2 text-green-50 duration-200 ease-in-out bg-green-700 rounded-lg hover:bg-green-800 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M92.969 91.786 C 5.279 104.861,-30.960 211.652,30.433 276.069 C 77.754 325.720,161.600 318.486,199.531 261.480 C 203.750 255.139,211.685 239.639,211.708 237.695 C 211.716 236.927,220.136 236.719,251.172 236.719 L 290.625 236.719 290.625 273.047 L 290.625 309.375 327.344 309.375 L 364.063 309.375 364.063 273.047 L 364.063 236.719 382.031 236.719 L 400.000 236.719 400.000 200.000 L 400.000 163.281 305.859 163.281 C 231.074 163.281,211.716 163.080,211.708 162.305 C 211.701 161.768,209.931 157.657,207.773 153.170 C 186.706 109.359,140.567 84.689,92.969 91.786 M115.657 164.850 C 145.035 170.421,154.937 208.166,132.047 227.331 C 108.910 246.702,73.438 230.161,73.438 200.000 C 73.438 177.717,93.858 160.716,115.657 164.850 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                            <div class="relative flex col-span-full h-5 w-full">
                                @error('access_code')
                                    <p class="text-red-500 z-50 pt-1 text-xs">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="relative flex flex-col items-center justify-center pt-10">
        <p class="text-center font text-indigo-50 text-sm px-2 pb-3">
            In partnership with DOLE. All rights reserved. 2024
        </p>
        <img class="object-contain size-10 drop-shadow" src="{{ asset('assets/dole_logo.png') }}" alt="">
    </div>
</div>
