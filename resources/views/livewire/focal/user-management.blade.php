<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    <livewire:sidebar.focal-bar />

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div x-data="{ addCoordinatorsModal: false }" class="p-2 min-h-screen select-none">

            {{-- Nav Title and Date Dropdown --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">User Management</h1>

                {{-- Loading State --}}
                <div class="absolute items-center justify-end z-50 min-h-full min-w-full text-indigo-900"
                    wire:loading.flex wire:target="selectedAllRows, updateCoordinators">
                    <svg class="size-8 me-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="relative grid grid-cols-1 w-full h-full lg:grid-cols-5">
                {{-- List of Coordinators --}}
                <div class="relative lg:col-span-full h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 flex items-center justify-between">
                        <div class="inline-flex items-center text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="font-bold m-2">List of Coordinators</h1>
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
                                    <svg class="size-4 animate-spin" wire:loading wire:target="searchUsers"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                                    wire:model.live.debounce.300ms="searchUsers"
                                    class="ps-7 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search for coordinators">
                            </div>
                            <button @click="addCoordinatorsModal = !addCoordinatorsModal;"
                                class="flex items-center bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500 duration-200 ease-in-out">
                                ADD COORDINATORS
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
                        {{-- List of Projects Table --}}
                        <div id="users-table"
                            class="relative min-h-[84vh] max-h-[84vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            <input id="select-all-checkbox" type="checkbox"
                                                wire:model.live="selectedAllRows"
                                                class="size-4 text-indigo-600 bg-indigo-100 border-indigo-300 rounded ring-indigo-100 ring-2 focus:ring-indigo-100 focus:ring-2">
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
                                            class="relative border-b {{ in_array($key, $selectedRows) ? 'bg-gray-100 hover:bg-gray-200 text-indigo-900' : 'hover:bg-indigo-50' }} whitespace-nowrap duration-200 ease-in-out">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium text-indigo-1100">
                                                <input id="user-checkbox-{{ $key }}" type="checkbox"
                                                    value="{{ $key }}" wire:model.live="selectedRows"
                                                    class="size-4 text-indigo-600 bg-indigo-100 border-indigo-300 rounded focus:ring-indigo-500 focus:ring-2">
                                            </th>
                                            <td class="pr-6 py-2">
                                                {{ $this->full_name($user) }}
                                            </td>
                                            <td class="p-2">
                                                {{ $user->email }}
                                            </td>
                                            <td class="p-2">
                                                {{ $user->contact_num }}
                                            </td>
                                            <td class="p-2 text-center">
                                                <span
                                                    class="bg-green-200 text-green-1000 rounded p-1.5 mx-1.5 font-semibold">{{ $user->approved_assignments }}</span>
                                                /
                                                <span
                                                    class="bg-amber-200 text-amber-900 rounded p-1.5 mx-1.5 font-semibold">{{ $user->pending_assignments }}</span>
                                                /
                                                <span
                                                    class="bg-indigo-200 text-indigo-1000 rounded p-1.5 mx-1.5 font-semibold">{{ $user->approved_assignments + $user->pending_assignments }}</span>
                                            </td>
                                            <td class="p-2 text-center">
                                                {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('M d, Y') : 'Never' }}
                                            </td>
                                            {{-- User Dropdown --}}
                                            <td class="p-2">

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        {{-- User Dropdown Content --}}
                        {{-- @foreach ($this->users as $key => $user)
                            <div wire:key="userRowDropdown-{{ $key }}"
                                id="userRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="text-sm text-indigo-1100"
                                    aria-labelledby="userRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Coordinator') }}"
                                            class="rounded-t-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M101.397 46.276 C 101.173 92.311,101.162 92.594,99.468 95.313 C 95.650 101.440,98.231 101.144,46.280 101.396 L -0.017 101.621 0.187 238.115 L 0.391 374.609 3.063 380.078 C 6.559 387.234,12.766 393.441,19.922 396.937 L 25.391 399.609 150.629 399.814 C 290.080 400.042,279.856 400.425,288.803 394.633 C 296.183 389.855,300.883 383.544,303.595 374.770 L 304.995 370.244 289.411 354.609 C 275.047 340.197,273.645 339.001,271.484 339.321 C 270.195 339.511,265.449 340.426,260.938 341.353 C 235.047 346.675,205.887 340.299,183.868 324.501 L 178.094 320.358 116.976 320.140 C 57.447 319.928,55.792 319.881,53.266 318.359 C 45.856 313.894,45.666 303.603,52.911 299.099 L 55.859 297.266 106.435 297.043 L 157.010 296.821 153.561 290.403 C 151.664 286.873,149.176 281.625,148.032 278.741 L 145.951 273.497 100.905 273.272 C 56.162 273.048,55.841 273.035,53.125 271.343 C 45.806 266.782,45.693 256.712,52.911 252.224 L 55.859 250.391 98.572 250.164 L 141.284 249.937 141.317 240.948 C 141.334 236.004,141.574 230.759,141.849 229.292 L 142.350 226.625 99.105 226.399 C 41.076 226.094,47.266 231.205,47.266 183.594 C 47.266 145.513,47.137 146.438,52.911 142.849 L 55.859 141.016 153.906 141.016 C 244.733 141.016,252.486 141.118,259.186 142.408 C 273.372 145.139,285.766 150.121,297.266 157.716 C 300.273 159.703,303.181 161.613,303.728 161.962 C 304.529 162.474,304.680 149.339,304.509 93.993 L 304.297 25.391 301.625 19.922 C 298.129 12.766,291.921 6.559,284.766 3.063 L 279.297 0.391 190.459 0.182 L 101.621 -0.026 101.397 46.276 M41.797 42.188 L 5.866 78.125 41.995 78.125 L 78.125 78.125 78.125 42.188 C 78.125 22.422,78.036 6.250,77.927 6.250 C 77.817 6.250,61.559 22.422,41.797 42.188 M251.421 48.828 C 258.913 53.343,258.913 63.845,251.421 68.359 C 248.891 69.884,247.443 69.922,191.406 69.922 C 135.370 69.922,133.922 69.884,131.391 68.359 C 122.382 62.930,124.454 50.112,134.749 47.588 C 141.019 46.052,248.713 47.196,251.421 48.828 M251.421 95.703 C 258.913 100.218,258.913 110.720,251.421 115.234 C 248.891 116.759,247.443 116.797,191.406 116.797 C 135.370 116.797,133.922 116.759,131.391 115.234 C 122.316 109.766,124.386 97.023,134.766 94.463 C 140.996 92.926,248.713 94.071,251.421 95.703 M70.313 183.594 L 70.313 203.125 109.598 203.125 L 148.883 203.125 151.469 197.852 C 155.839 188.941,162.490 179.651,170.206 171.680 L 177.580 164.063 123.946 164.063 L 70.313 164.063 70.313 183.594 M226.563 165.184 C 176.220 175.719,149.210 230.954,171.889 276.987 C 200.399 334.854,283.976 334.854,312.486 276.987 C 334.140 233.035,309.949 179.063,262.891 166.340 C 254.916 164.184,234.453 163.532,226.563 165.184 M324.499 300.586 C 319.053 308.219,309.999 317.409,302.148 323.275 C 298.389 326.084,295.313 328.594,295.313 328.852 C 295.313 330.809,362.570 396.223,366.406 397.997 C 386.489 407.286,407.286 386.489,397.997 366.406 C 396.189 362.496,330.790 295.313,328.792 295.313 C 328.500 295.313,326.569 297.686,324.499 300.586 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            View Coordinator
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Modify Coordinator') }}"
                                            class="rounded-b-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M303.516 1.151 C 303.086 1.286,300.186 1.817,297.070 2.332 L 291.406 3.267 291.406 13.161 L 291.406 23.055 286.776 24.850 C 284.230 25.837,279.286 28.560,275.789 30.901 C 268.179 35.995,269.993 36.042,260.547 30.499 L 252.734 25.914 250.479 27.996 C 242.118 35.714,226.598 64.358,229.308 67.068 C 229.581 67.341,233.185 69.506,237.318 71.880 L 244.832 76.195 244.890 89.111 L 244.948 102.027 236.943 106.605 C 227.905 111.774,228.276 110.842,231.644 119.932 C 235.614 130.648,241.667 140.650,248.649 148.031 L 252.734 152.350 260.813 147.659 C 270.417 142.083,268.427 142.249,274.532 146.517 C 277.323 148.468,282.262 151.291,285.507 152.790 L 291.406 155.516 291.406 165.151 L 291.406 174.786 298.242 176.146 C 307.129 177.914,321.538 177.925,331.055 176.170 L 338.281 174.838 338.281 164.954 L 338.281 155.070 342.911 153.275 C 345.457 152.288,350.414 149.556,353.927 147.205 L 360.313 142.931 368.633 147.708 L 376.953 152.485 380.342 148.748 C 387.935 140.373,394.881 128.579,398.409 118.072 C 400.664 111.353,400.684 111.388,391.797 106.226 C 385.964 102.839,384.034 101.327,384.179 100.259 C 384.615 97.052,384.467 80.715,383.984 78.792 C 383.493 76.838,383.902 76.476,391.414 72.223 C 395.785 69.748,399.536 67.269,399.749 66.716 C 401.432 62.330,389.916 40.052,380.692 29.849 L 376.953 25.713 368.827 30.435 C 359.151 36.057,361.132 35.919,354.408 31.438 C 351.339 29.393,346.473 26.669,343.594 25.384 L 338.361 23.047 338.126 13.281 L 337.891 3.516 331.641 2.214 C 326.163 1.074,306.162 0.317,303.516 1.151 M327.682 60.017 C 359.999 75.140,347.796 123.308,312.265 120.873 C 286.986 119.140,273.851 88.930,289.784 69.170 C 298.991 57.753,314.723 53.953,327.682 60.017 M161.866 114.621 C 161.471 115.388,159.505 120.410,157.497 125.781 L 153.846 135.547 141.572 135.581 C 134.821 135.600,128.418 135.844,127.344 136.122 C 125.476 136.607,125.187 136.166,120.750 126.046 C 118.198 120.225,115.679 115.297,115.152 115.095 C 109.188 112.806,60.133 134.492,55.945 141.268 C 55.748 141.587,57.686 146.585,60.251 152.376 L 64.915 162.905 57.202 170.710 C 52.960 175.003,48.598 179.697,47.509 181.141 L 45.528 183.767 35.067 179.774 C 22.138 174.840,23.441 174.701,19.224 181.466 C 11.509 193.844,4.697 210.957,1.583 225.781 C -0.921 237.703,-1.558 236.557,9.961 240.858 C 15.439 242.904,20.322 244.831,20.811 245.140 C 21.404 245.516,21.797 250.423,21.990 259.864 L 22.281 274.025 11.870 278.577 C 6.144 281.081,1.305 283.379,1.116 283.684 C -1.798 288.398,20.533 339.150,27.384 343.385 C 27.717 343.590,32.748 341.603,38.564 338.969 L 49.139 334.180 55.624 340.769 C 59.191 344.393,63.904 348.766,66.097 350.486 L 70.084 353.615 65.902 364.497 C 63.601 370.483,61.719 375.613,61.719 375.898 C 61.719 376.674,72.958 383.252,80.078 386.643 C 92.123 392.380,109.257 397.767,119.160 398.931 L 123.202 399.406 126.333 391.305 C 128.055 386.849,129.936 381.885,130.514 380.273 L 131.564 377.344 143.516 377.192 C 150.090 377.108,156.542 377.020,157.854 376.997 C 160.173 376.954,160.370 377.246,164.915 387.464 C 167.486 393.245,169.680 398.065,169.790 398.175 C 170.984 399.370,193.632 391.863,204.636 386.625 C 214.000 382.168,229.688 372.474,229.688 371.145 C 229.688 370.790,227.609 365.826,225.068 360.113 L 220.448 349.726 226.178 344.374 C 229.330 341.431,233.721 336.789,235.936 334.059 L 239.963 329.096 250.905 333.305 C 263.545 338.167,262.147 338.401,266.681 330.664 C 275.931 314.880,288.526 278.890,285.765 276.130 C 285.624 275.989,280.655 274.007,274.723 271.726 L 263.937 267.578 263.692 254.688 C 263.557 247.598,263.217 241.237,262.937 240.552 C 262.530 239.559,264.647 238.313,273.401 234.391 C 285.846 228.817,285.165 229.918,282.441 219.748 C 278.212 203.962,271.838 189.792,262.780 176.037 C 257.678 168.290,258.883 168.430,246.401 174.134 L 236.328 178.738 228.802 171.172 C 224.662 167.012,220.007 162.743,218.456 161.686 C 214.999 159.332,214.887 160.714,219.531 148.437 C 224.443 135.454,224.854 137.012,215.039 131.413 C 201.929 123.933,189.239 118.936,175.391 115.802 C 163.555 113.122,162.678 113.045,161.866 114.621 M158.594 193.865 C 207.759 206.194,223.725 268.978,186.493 303.574 C 153.132 334.572,99.094 322.263,82.653 279.922 C 63.723 231.170,107.867 181.145,158.594 193.865 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            Modify Coordinator
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endforeach --}}
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[84vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="animate-pulse size-12 sm:size-20 mb-4 text-gray-300"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p>No coordinators found.</p>
                                <p>Try creating a <span class="animate-pulse text-indigo-900">new coordinator</span>.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Create Button | Main Modal --}}
                    <livewire:focal.user-management.add-coordinators-modal />
                </div>
            </div>
        </div>
    </div>

    {{-- Sweet Alert --}}
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
