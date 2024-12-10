<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, isAboveBreakpoint: true, addCoordinatorsModal: $wire.entangle('addCoordinatorsModal'), viewCoordinatorModal: $wire.entangle('viewCoordinatorModal') }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <div :class="{
        'md:ml-20': !open,
        'md:ml-20 xl:ml-64': open,
    }"
        class="md:ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Date Dropdown --}}
            <div class="relative flex items-center w-full my-2 gap-2">

                <div class="flex items-center gap-2">
                    <livewire:sidebar.focal-bar />
                    <h1 class="text-xl font-semibold sm:font-bold xl:ms-2">User Management</h1>
                </div>

                {{-- Loading State --}}
                <svg class="absolute right-0 size-6 text-indigo-900 animate-spin" wire:loading.flex
                    wire:target="selectedAllRows, updateCoordinators, viewCoordinator" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            <div class="relative grid grid-cols-1 size-full lg:grid-cols-5">

                {{-- List of Coordinators --}}
                <div class="relative lg:col-span-full size-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative h-10 flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 mx-2 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="max-[480px]:hidden text-base font-semibold sm:font-bold">
                                <span class="hidden sm:inline">
                                    List of
                                </span>
                                Coordinators
                            </h1>
                            <span class="py-1 px-2 text-xs font-medium text-indigo-700 bg-indigo-100 rounded">
                                {{ sizeof($this->users) }}
                            </span>
                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="mx-2 flex items-center justify-end">
                            {{-- Loading State --}}

                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">

                                    {{-- Loading Icon --}}
                                    <svg class="size-3 text-indigo-500 animate-spin" wire:loading
                                        wire:target="searchUsers" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Search Icon --}}
                                    <svg class="size-3 text-indigo-500" wire:loading.remove wire:target="searchUsers"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="user-search" maxlength="100"
                                    wire:model.live.debounce.300ms="searchUsers" autocomplete="off"
                                    class="ps-7 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search for coordinators">
                            </div>
                            <button @click="addCoordinatorsModal = !addCoordinatorsModal;"
                                class="flex items-center bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500 duration-200 ease-in-out">
                                <span class="md:block hidden">ADD COORDINATORS</span>
                                <span class="block md:hidden">ADD</span>
                                <svg class="size-4 ml-2" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M181.716 13.755 C 102.990 27.972,72.357 125.909,128.773 183.020 C 181.183 236.074,272.696 214.609,295.333 143.952 C 318.606 71.310,256.583 0.235,181.716 13.755 M99.463 202.398 C 60.552 222.138,32.625 260.960,26.197 304.247 C 24.209 317.636,24.493 355.569,26.629 361.939 C 30.506 373.502,39.024 382.022,50.561 385.877 C 55.355 387.479,56.490 387.500,136.304 387.500 L 217.188 387.500 209.475 379.883 C 171.918 342.791,164.644 284.345,192.232 241.338 C 195.148 236.792,195.136 236.719,191.484 236.719 C 169.055 236.719,137.545 223.179,116.259 204.396 L 108.691 197.717 99.463 202.398 M269.531 213.993 C 176.853 234.489,177.153 366.574,269.922 386.007 C 337.328 400.126,393.434 333.977,369.538 268.559 C 355.185 229.265,310.563 204.918,269.531 213.993 M293.788 265.042 C 298.143 267.977,299.417 271.062,299.832 279.675 L 300.199 287.301 307.825 287.668 C 319.184 288.215,324.219 292.002,324.219 300.000 C 324.219 307.998,319.184 311.785,307.825 312.332 L 300.199 312.699 299.832 320.325 C 299.285 331.684,295.498 336.719,287.500 336.719 C 279.502 336.719,275.715 331.684,275.168 320.325 L 274.801 312.699 267.175 312.332 C 255.816 311.785,250.781 307.998,250.781 300.000 C 250.781 292.002,255.816 288.215,267.175 287.668 L 274.801 287.301 275.168 279.675 C 275.715 268.316,279.502 263.281,287.500 263.281 C 290.019 263.281,291.997 263.835,293.788 265.042 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($this->users->isNotEmpty())
                        {{-- List of Coordinators --}}
                        <div id="users-table"
                            class="relative min-h-[84vh] max-h-[84vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="pr-6 py-2 text-sm">
                                            coordinator
                                        </th>
                                        <th scope="col" class="p-2 text-sm">
                                            email
                                        </th>
                                        <th scope="col" class="p-2 text-sm">
                                            contact #
                                        </th>
                                        <th scope="col" class="p-2 text-center">
                                            assignments <br>approved / pending / total
                                        </th>
                                        <th scope="col" class="p-2 text-sm text-center">
                                            last login
                                        </th>
                                        <th scope="col" class="p-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($this->users as $key => $user)
                                        <tr wire:key="user-{{ $key }}"
                                            wire:loading.class="pointer-events-none" wire:target="viewCoordinator"
                                            @dblClick="$wire.viewCoordinator('{{ encrypt($user->id) }}');"
                                            class="relative border-b whitespace-nowrap duration-200 cursor-pointer ease-in-out"
                                            :class="{
                                                'bg-red-300 hover:bg-red-400 text-red-700 hover:text-red-900': {{ json_encode(is_null($user->email_verified_at)) }},
                                                'hover:bg-indigo-50 text-indigo-1100': {{ json_encode(!is_null($user->email_verified_at)) }}
                                            }">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="pr-6 py-2">
                                                <span
                                                    class="select-all cursor-text selection:bg-indigo-700 selection:text-indigo-50">
                                                    {{ $this->full_name($user) }}
                                                </span>
                                            </td>
                                            <td class="p-2">
                                                <span
                                                    class="select-all cursor-text selection:bg-indigo-700 selection:text-indigo-50">
                                                    {{ $user->email }}
                                                    @if (is_null($user->email_verified_at))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="inline ms-1 size-4 text-red-700"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                            height="400" viewBox="0, 0, 400,400">
                                                            <g>
                                                                <path
                                                                    d="M175.606 13.697 C 34.401 32.950,-35.779 196.532,48.155 310.766 C 138.823 434.164,332.291 403.043,378.795 257.579 C 420.628 126.725,311.070 -4.774,175.606 13.697 M211.301 90.122 C 220.712 94.430,227.454 104.096,227.963 114.011 C 228.365 121.843,219.818 221.239,218.463 224.481 C 211.721 240.618,188.279 240.618,181.537 224.481 C 180.112 221.072,171.620 121.497,172.086 113.672 C 173.216 94.715,194.031 82.215,211.301 90.122 M209.801 265.203 C 225.439 272.153,229.266 292.609,217.188 304.688 C 205.345 316.530,185.671 313.291,178.162 298.264 C 168.018 277.964,189.055 255.982,209.801 265.203 "
                                                                    stroke="none" fill="currentColor"
                                                                    fill-rule="evenodd">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="p-2 ">
                                                <span
                                                    class="select-all cursor-text selection:bg-indigo-700 selection:text-indigo-50">
                                                    {{ $user->contact_num }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">
                                                <span
                                                    class="{{ $this->approvedCount($user) === 0 ? 'bg-gray-200 text-gray-700 selection:bg-gray-700 selection:text-gray-50' : 'bg-green-300 text-green-950 selection:bg-green-700 selection:text-green-50' }} select-all cursor-text rounded py-0.5 px-1.5 mx-1.5 font-medium">{{ $this->approvedCount($user) }}</span>
                                                /
                                                <span
                                                    class="{{ $this->pendingCount($user) === 0 ? 'bg-gray-200 text-gray-700 selection:bg-gray-700 selection:text-gray-50' : 'bg-amber-300 text-amber-950 selection:bg-amber-700 selection:text-amber-50' }} select-all cursor-text rounded py-0.5 px-1.5 mx-1.5 font-medium">{{ $this->pendingCount($user) }}</span>
                                                /
                                                <span
                                                    class="{{ $this->approvedCount($user) + $this->pendingCount($user) === 0 ? 'bg-gray-200 text-gray-700 selection:bg-gray-700 selection:text-gray-50' : 'bg-indigo-300 text-indigo-950 selection:bg-indigo-700 selection:text-indigo-50' }} select-all cursor-text rounded py-0.5 px-1.5 mx-1.5 font-medium">{{ $this->approvedCount($user) + $this->pendingCount($user) }}</span>
                                            </td>
                                            <td class="text-center p-2">
                                                @if ($this->checkIfOnline($user) !== true)
                                                    <span
                                                        class="select-all cursor-text {{ $user->last_login ? 'selection:bg-indigo-700 selection:text-indigo-50' : 'selection:bg-gray-700 selection:text-gray-50' }}">
                                                        {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('M d, Y @ h:i A') : 'Never' }}
                                                    </span>
                                                @else
                                                    <span class="flex items-center justify-center gap-1">
                                                        <span class="bg-green-900 rounded-full p-1"></span>
                                                        <span
                                                            class="select-all cursor-text selection:bg-green-700 selection:text-green-50">
                                                            Online
                                                        </span>
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- User Dropdown --}}
                                            <td class="p-1">
                                                {{-- View Button --}}
                                                <button type="button"
                                                    @click.stop="$wire.viewCoordinator('{{ encrypt($user->id) }}');"
                                                    id="beneficiaryRowButton-{{ $key }}"
                                                    class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out "
                                                    :class="{
                                                        'hover:bg-red-700 focus:bg-red-700 text-red-900 hover:text-red-50 focus:text-red-50': {{ json_encode(is_null($user->email_verified_at)) }},
                                                        'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50': {{ json_encode(!is_null($user->email_verified_at)) }}
                                                    }">

                                                    {{-- View Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[84vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if (isset($searchUsers) && !empty($searchUsers))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No coordinators found.</p>
                                    <p>Try a different <span class="text-indigo-900">search term</span>.
                                    </p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-zinc-300"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No coordinators found.</p>
                                    <p>Try creating a <span class="text-indigo-900">new coordinator</span>.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Add Coordinators Modal --}}
                    <livewire:focal.user-management.add-coordinators-modal />

                    {{-- View Coordinator Modal --}}
                    <livewire:focal.user-management.view-coordinator :$coordinatorId />
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Bar --}}
    <div x-data="{
        successShow: $wire.entangle('showAlert'),
        successMessage: $wire.entangle('alertMessage'),
        init() {
            window.addEventListener('show-alert', () => {
                setTimeout(() => { $wire.showAlert = false; }, 3000);
            });
        },
    }" x-cloak x-show="successShow" {{-- x-effect="console.log(successShow);" --}}
        x-transition:enter="transition ease-in-out duration-300 origin-left"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="origin-left transition ease-in-out duration-500"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="fixed left-6 bottom-6 z-50 flex items-center bg-indigo-200 text-indigo-1000 border border-indigo-500 rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
        role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                clip-rule="evenodd" />
        </svg>
        <p x-text="successMessage"></p>
    </div>
</div>


@script
    <script>
        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });
    </script>
@endscript
