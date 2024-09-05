<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, show: false, profileShow: false, rotation: 0, caretRotate: 0, dashboardHover: false, implementationsHover: false, umanagementHover: false, alogsHover: false, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <livewire:sidebar.focal-bar wire:key="{{ str()->random(50) }}" />

    <div x-data="{ scrollToTop() { document.getElementById('beneficiaries-table').scrollTo({ top: 0, behavior: 'smooth' }); } }" :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Date Dropdown --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Implementations</h1>

                <div id="implementations-date-range" date-rangepicker datepicker-autohide class="flex items-center">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-indigo-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="start-date" name="start" type="text" value="{{ $defaultStart }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date start">
                    </div>
                    <span class="mx-4 text-indigo-1100">to</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-indigo-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="end-date" name="end" type="text" value="{{ $defaultEnd }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date end">
                    </div>
                </div>

                {{-- Loading State --}}
                <div class="absolute items-center justify-end z-50 min-h-full min-w-full text-indigo-900"
                    wire:loading.flex>
                    <svg class="w-8 h-8 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
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

            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-5">
                {{-- List of Projects --}}
                <div class="relative lg:col-span-3 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 flex items-center justify-between">
                        <div class="inline-flex items-center text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M174.219 1.229 C 54.472 18.124,-24.443 135.741,6.311 251.484 C 9.642 264.022,18.559 287.500,19.989 287.500 C 20.159 287.500,25.487 284.951,31.829 281.836 C 38.171 278.721,43.450 276.139,43.562 276.100 C 43.673 276.060,42.661 273.599,41.313 270.631 C 20.301 224.370,21.504 168.540,44.499 122.720 C 91.474 29.119,207.341 -2.229,294.805 55.000 L 303.283 60.547 296.563 60.773 L 289.844 60.998 289.844 75.030 L 289.844 89.063 316.041 89.063 C 356.109 89.062,354.775 90.537,350.877 50.558 C 349.488 36.310,348.202 24.504,348.019 24.321 C 347.676 23.978,328.468 25.531,323.192 26.328 L 320.212 26.778 320.757 33.742 L 321.302 40.706 315.480 36.529 C 276.374 8.472,220.985 -5.369,174.219 1.229 M146.501 97.750 C 118.151 111.473,94.683 122.973,94.351 123.305 C 94.019 123.637,117.528 137.000,146.593 153.000 L 199.439 182.092 252.454 153.019 C 281.612 137.028,305.456 123.743,305.440 123.496 C 305.396 122.820,200.285 72.645,199.085 72.727 C 198.514 72.766,174.851 84.026,146.501 97.750 M367.815 118.385 L 356.334 124.187 358.736 129.476 C 379.696 175.622,378.473 231.507,355.501 277.280 C 308.659 370.616,191.853 402.240,105.195 345.048 L 96.718 339.453 103.828 339.228 L 110.938 339.004 110.938 324.971 L 110.938 310.938 83.858 310.938 L 56.778 310.937 53.464 312.880 C 49.750 315.056,46.875 319.954,46.875 324.105 C 46.875 327.673,51.612 375.310,52.006 375.704 C 52.327 376.025,69.823 374.588,76.418 373.699 L 79.790 373.245 79.242 366.245 L 78.695 359.245 84.074 363.146 C 180.358 432.973,317.505 400.914,375.933 294.922 C 405.531 241.229,408.161 173.609,382.825 117.732 C 379.977 111.450,381.685 111.375,367.815 118.385 M75.190 209.482 L 75.391 269.080 129.223 295.087 C 158.831 309.391,183.177 321.094,183.325 321.094 C 183.473 321.094,183.585 295.869,183.574 265.039 L 183.554 208.984 130.305 179.688 C 101.018 163.574,76.591 150.277,76.023 150.137 C 75.172 149.928,75.026 160.392,75.190 209.482 M269.139 179.604 L 215.234 209.207 215.034 265.236 C 214.844 318.400,214.904 321.239,216.206 320.749 C 216.961 320.466,241.562 308.738,270.876 294.687 L 324.174 269.141 324.197 209.570 C 324.209 176.807,323.954 150.000,323.631 150.000 C 323.307 150.000,298.786 163.322,269.139 179.604 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="font-bold m-2">List of Projects</h1>

                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="mx-2 flex items-center justify-end">
                            {{-- Loading State --}}
                            {{-- <div class="items-center justify-end z-50 text-indigo-900" wire:loading
                                wire:target="searchProjects">
                                <svg class="size-4 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div> --}}
                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 text-indigo-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="project-search" maxlength="100" {{-- wire:model.live="searchProjects" --}}
                                    class="duration-200 ease-in-out ps-7 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search for project titles">
                            </div>
                            <button data-modal-target="create-modal" data-modal-toggle="create-modal"
                                class="flex items-center bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500 duration-200 ease-in-out">
                                CREATE
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ml-2"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M87.232 51.235 C 70.529 55.279,55.160 70.785,51.199 87.589 C 49.429 95.097,49.415 238.777,51.184 245.734 C 55.266 261.794,68.035 275.503,84.375 281.371 L 89.453 283.195 164.063 283.423 C 247.935 283.680,244.564 283.880,256.471 277.921 C 265.327 273.488,273.488 265.327,277.921 256.471 C 283.880 244.564,283.680 247.935,283.423 164.063 L 283.195 89.453 281.371 84.375 C 275.503 68.035,261.794 55.266,245.734 51.184 C 239.024 49.478,94.296 49.525,87.232 51.235 M326.172 101.100 C 323.101 102.461,320.032 105.395,318.240 108.682 C 316.870 111.194,316.777 115.490,316.406 193.359 L 316.016 275.391 313.810 281.633 C 308.217 297.460,296.571 308.968,280.859 314.193 L 275.391 316.012 193.359 316.404 L 111.328 316.797 108.019 318.693 C 97.677 324.616,97.060 340.415,106.903 347.255 L 110.291 349.609 195.575 349.609 L 280.859 349.609 287.500 347.798 C 317.300 339.669,339.049 318.056,347.783 287.891 L 349.592 281.641 349.816 196.680 C 350.060 104.007,350.312 109.764,345.807 104.807 C 341.717 100.306,332.072 98.485,326.172 101.100 M172.486 118.401 C 180.422 121.716,182.772 126.649,182.795 140.039 L 182.813 150.000 190.518 150.000 C 209.679 150.000,219.220 157.863,215.628 170.693 C 213.075 179.810,207.578 182.771,193.164 182.795 L 182.813 182.813 182.795 193.164 C 182.771 207.578,179.810 213.075,170.693 215.628 C 157.863 219.220,150.000 209.679,150.000 190.518 L 150.000 182.813 140.039 182.795 C 123.635 182.767,116.211 176.839,117.378 164.698 C 118.318 154.920,125.026 150.593,139.970 150.128 L 150.000 149.815 150.000 142.592 C 150.000 122.755,159.204 112.853,172.486 118.401 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($implementations)
                        {{-- List of Projects Table --}}
                        <div id="implementations-table"
                            class="relative min-h-60 max-h-60 overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            project #
                                        </th>
                                        <th scope="col" class="pr-6 py-2">
                                            project title
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            total slots
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            days of work
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($implementations as $key => $implementation)
                                        @php
                                            $encryptedId = Crypt::encrypt($implementation['id']);
                                        @endphp
                                        <tr @if ($beneficiaries) @click="scrollToTop()" @endif
                                            wire:key="implementation-{{ $key }}"
                                            wire:click.prevent='selectImplementationRow({{ $key }}, "{{ $encryptedId }}")'
                                            class="relative border-b {{ $selectedImplementationRow === $key ? 'bg-indigo-100 hover:bg-indigo-200' : 'bg-white hover:bg-indigo-50' }}  whitespace-nowrap duration-200 ease-in-out">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium text-indigo-1100">
                                                {{ $implementation['project_num'] }}
                                            </th>
                                            <td class="pr-6 py-2">
                                                {{ $implementation['project_title'] }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $implementation['total_slots'] }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $implementation['days_of_work'] }}
                                            </td>
                                            {{-- Implementation Dropdown --}}
                                            <td x-data="iDropdownRotation({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="implementationRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="implementationRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 font-medium rounded text-indigo-1100 hover:text-indigo-1000 active:text-indigo-900 bg-transparent hover:bg-indigo-200 duration-200 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="w-4 duration-300 ease-in-out">
                                                        <path fill-rule="evenodd"
                                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <tr x-data x-intersect.full="$wire.loadMoreImplementations();">
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Implementation Dropdown Content --}}
                        @foreach ($implementations as $key => $implementation)
                            <div wire:key="implementationRowDropdown-{{ $key }}"
                                id="implementationRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="text-sm text-indigo-1100"
                                    aria-labelledby="implementationRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Project') }}"
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
                                            View Project
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Modify Project') }}"
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
                                            Modify Project
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <div class="relative bg-white px-4 pb-4 pt-2 h-60 min-w-full flex items-center justify-center">
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
                                <p>No projects found.</p>
                                <p>Try creating a <span class="animate-pulse text-indigo-900">new project</span>.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Create Button | Main Modal --}}
                    <livewire:focal.implementations.create-project-modal />
                </div>

                {{-- Batch Assignments --}}
                <div class="relative lg:col-span-2 h-full w-full rounded bg-white shadow">
                    <div class="relative flex justify-between max-h-12 items-center">
                        <div class="inline-flex items-center text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="font-bold m-2">Batches</h1>
                        </div>
                        {{-- Assign Button --}}
                        <div class="mx-2 flex items-center">
                            @if ($remainingBatchSlots || $remainingBatchSlots === 0)
                                <p class="text-xs text-indigo-1100 capitalize font-light me-1">remaining slots:</p>
                                <div
                                    class="{{ $remainingBatchSlots > 0 ? 'bg-indigo-300 text-indigo-1000' : 'bg-red-300 text-red-900' }} rounded-md py-1 px-2 text-xs me-2">
                                    {{ $remainingBatchSlots }}</div>
                            @endif
                            <button @if (!$remainingBatchSlots || $remainingBatchSlots === null || $remainingBatchSlots === 0) disabled @endif
                                @if ($implementations) data-modal-target="assign-batches-modal" data-modal-toggle="assign-batches-modal" @endif
                                class="flex items-center rounded-md px-3 py-1 text-sm font-bold duration-200 ease-in-out {{ $remainingBatchSlots > 0 ? 'bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500' : 'bg-indigo-300 text-indigo-50' }}">
                                ASSIGN
                                <svg class="size-4 ml-2" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M175.781 26.530 C 119.992 38.462,92.306 102.931,122.082 151.572 C 157.176 208.903,244.031 202.905,269.988 141.358 C 296.391 78.756,241.936 12.381,175.781 26.530 M107.813 191.177 C 85.230 195.102,68.383 210.260,61.975 232.422 C 59.986 239.301,59.428 318.137,61.292 328.937 C 65.057 350.758,80.886 368.049,102.049 373.462 C 107.795 374.931,110.968 375.000,173.282 375.000 L 238.502 375.000 229.212 365.425 C 219.425 355.339,216.440 350.863,214.479 343.332 C 205.443 308.642,247.642 282.676,274.554 306.365 L 278.297 309.660 291.520 296.252 C 306.255 281.311,310.725 278.355,321.367 276.518 L 326.718 275.594 326.363 256.352 C 325.910 231.742,323.949 224.404,314.486 211.897 C 303.479 197.348,289.113 191.080,266.681 191.040 L 253.285 191.016 250.200 193.359 C 248.504 194.648,244.688 197.549,241.722 199.806 C 212.635 221.931,168.906 220.569,140.934 196.668 C 134.265 190.970,133.021 190.608,120.533 190.731 C 114.611 190.790,108.887 190.991,107.813 191.177 M322.312 301.147 C 320.008 301.982,314.069 307.424,298.707 322.778 L 278.180 343.293 267.765 333.054 C 253.176 318.711,244.359 317.212,238.868 328.141 C 234.924 335.991,236.251 338.322,255.671 357.670 C 279.723 381.632,275.648 382.438,311.465 346.621 C 334.644 323.443,338.278 319.491,339.020 316.655 C 341.715 306.359,332.231 297.556,322.312 301.147 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($batches)
                        {{-- Table --}}
                        <div id="batches-table"
                            class="relative min-h-60 max-h-60 overflow-y-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">

                            <table class="relative w-full text-sm text-left text-indigo-1100">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="ps-4 py-2">
                                            barangay
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">
                                            slots
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">
                                            status
                                        </th>
                                        <th scope="col" class="px-2 py-2">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs relative">
                                    @foreach ($batches as $key => $batch)
                                        @php
                                            $batchEncryptedId = encrypt($batch['batches_id']);
                                        @endphp
                                        <tr @if ($beneficiaries) @click="scrollToTop()" @endif
                                            wire:click='selectBatchRow({{ $key }}, "{{ $batchEncryptedId }}")'
                                            wire:key='batch-{{ $key }}'
                                            class="relative border-b {{ $selectedBatchRow === $key ? 'bg-indigo-100 hover:bg-indigo-200' : 'bg-white hover:bg-indigo-50' }} whitespace-nowrap duration-200 ease-in-out">
                                            <th scope="row"
                                                class="z-0 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap">
                                                {{ $batch['barangay_name'] }}
                                            </th>
                                            <td class="px-2 py-2 text-center">
                                                {{ $batch['current_slots'] . ' / ' . $batch['slots_allocated'] }}
                                            </td>
                                            <td class="py-2">
                                                <p
                                                    class="px-1 py-1 text-xs rounded font-semibold uppercase {{ $batch['approval_status'] === 'approved' ? 'bg-green-300 text-green-1000' : 'bg-gray-500 text-gray-50' }}  text-center">
                                                    {{ $batch['approval_status'] }}
                                                </p>
                                            </td>
                                            <td x-data="bDropdownRotation({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="batchRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="batchRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 font-medium rounded text-indigo-1100 hover:text-indigo-1000 active:text-indigo-900 bg-transparent hover:bg-indigo-200 duration-200 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="w-4 duration-300 ease-in-out">
                                                        <path fill-rule="evenodd"
                                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Batch Assignment Dropdown Content --}}
                        @foreach ($batches as $key => $batch)
                            <div wire:key="batchRowDropdown-{{ $key }}"
                                id="batchRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="text-sm text-indigo-1100"
                                    aria-labelledby="batchRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Batch') }}"
                                            class="rounded-t-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M74.625 33.647 C 56.792 36.627,41.748 49.646,35.409 67.586 L 33.203 73.828 33.203 184.766 C 33.203 308.132,32.802 299.063,38.814 311.624 C 62.789 361.720,136.742 346.764,142.089 290.738 C 142.998 281.216,145.733 277.386,152.795 275.748 C 154.514 275.349,187.491 275.029,227.930 275.019 L 300.000 275.000 300.000 175.164 C 300.000 88.745,299.842 74.703,298.821 70.672 C 294.245 52.593,278.449 37.678,259.994 34.009 C 253.260 32.671,82.462 32.337,74.625 33.647 M186.328 105.856 C 215.777 112.001,235.400 144.491,226.672 172.656 C 216.615 205.110,181.515 221.086,151.953 206.664 L 146.484 203.996 134.766 215.569 C 120.189 229.965,116.053 231.672,109.370 226.048 C 100.922 218.939,102.562 213.704,118.196 197.882 L 129.166 186.780 126.747 181.476 C 108.102 140.585,142.646 96.741,186.328 105.856 M166.797 129.956 C 134.752 140.584,140.324 186.077,173.828 187.362 C 205.968 188.594,216.609 145.430,187.535 131.761 C 181.633 128.986,172.198 128.165,166.797 129.956 M174.928 301.815 C 170.075 304.372,166.358 309.670,163.735 317.766 C 154.784 345.393,134.830 361.993,105.469 366.236 C 102.127 366.719,135.285 367.007,202.437 367.079 C 312.875 367.196,311.266 367.251,322.958 363.007 C 342.029 356.085,357.742 339.730,363.780 320.518 C 366.711 311.192,365.064 305.682,358.180 301.783 L 355.078 300.026 266.724 300.013 L 178.371 300.000 174.928 301.815 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            View Batch
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Modify Batch') }}"
                                            class="rounded-b-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M188.246 15.408 C 180.440 36.740,180.294 36.947,171.098 39.805 C 166.872 41.117,159.905 43.948,155.616 46.096 C 145.999 50.910,146.595 50.921,134.375 45.703 C 121.889 40.371,123.039 40.277,115.773 47.220 C 106.717 55.872,106.899 54.747,112.459 67.746 C 118.094 80.920,117.971 79.031,113.717 86.921 C 111.808 90.462,108.836 97.543,107.114 102.657 C 103.312 113.945,104.791 112.513,91.286 117.991 L 80.078 122.537 80.078 133.339 L 80.078 144.141 90.870 148.509 C 103.680 153.694,103.958 153.944,106.564 162.615 C 107.681 166.330,110.547 173.287,112.934 178.074 C 116.090 184.405,117.126 187.250,116.730 188.507 C 116.431 189.457,114.303 194.609,112.000 199.956 C 106.820 211.983,106.491 210.043,115.176 218.691 L 122.539 226.024 133.730 221.210 C 147.152 215.436,145.622 215.498,155.137 220.343 C 159.390 222.508,166.285 225.362,170.460 226.685 C 179.748 229.630,179.554 229.418,184.713 242.276 L 188.910 252.734 191.916 253.275 C 195.778 253.970,204.222 253.970,208.084 253.275 L 211.090 252.734 215.315 242.205 C 220.421 229.480,220.166 229.742,230.859 226.203 C 235.586 224.638,242.350 221.794,245.891 219.882 C 253.930 215.542,253.091 215.491,266.016 221.094 C 271.963 223.672,276.992 225.781,277.192 225.781 C 278.041 225.781,292.188 211.344,292.188 210.477 C 292.188 209.952,290.078 204.592,287.500 198.565 C 281.899 185.470,281.953 188.330,287.109 177.988 C 289.472 173.248,292.319 166.330,293.436 162.615 C 296.042 153.944,296.320 153.694,309.130 148.509 L 319.922 144.141 319.922 133.253 L 319.922 122.365 308.634 117.825 C 295.069 112.368,296.677 113.908,292.918 102.778 C 291.214 97.731,288.189 90.463,286.196 86.628 L 282.573 79.655 287.469 67.981 L 292.365 56.308 289.936 53.242 C 286.567 48.987,278.260 41.406,276.968 41.406 C 276.381 41.406,271.308 43.340,265.694 45.703 C 253.306 50.919,253.993 50.906,244.384 46.096 C 240.095 43.948,233.097 41.108,228.834 39.783 C 219.954 37.024,220.936 38.172,215.171 23.828 L 211.090 13.672 200.065 13.455 L 189.040 13.238 188.246 15.408 M211.722 48.004 C 270.040 56.795,302.909 118.304,277.349 170.814 C 243.761 239.817,144.582 233.803,118.492 161.181 C 97.259 102.077,149.526 38.627,211.722 48.004 M191.308 60.137 C 138.528 66.796,109.522 125.507,136.572 170.925 C 166.540 221.241,240.891 218.041,266.402 165.336 C 291.473 113.539,248.227 52.955,191.308 60.137 M204.492 89.526 C 208.879 94.435,205.486 98.897,196.520 100.011 C 181.959 101.820,171.036 111.192,167.545 124.872 C 166.078 130.620,166.333 131.254,169.205 128.995 C 173.855 125.337,180.641 129.132,179.264 134.620 C 178.700 136.867,163.567 151.986,161.172 152.696 C 157.250 153.858,140.625 138.401,140.625 133.593 C 140.625 128.020,146.972 125.554,151.503 129.366 L 153.906 131.389 153.906 129.612 C 153.906 122.215,159.740 109.258,166.221 102.257 C 177.503 90.071,198.684 83.025,204.492 89.526 M251.022 122.539 C 259.704 131.351,260.847 133.565,258.374 136.786 C 255.770 140.178,252.013 140.367,248.636 137.277 C 246.475 135.299,246.642 135.057,245.091 142.424 C 241.242 160.705,227.249 174.631,208.802 178.540 C 200.790 180.238,198.680 180.050,196.034 177.404 C 190.933 172.304,193.721 168.222,203.266 166.812 C 218.550 164.555,228.843 155.617,232.472 141.452 C 233.887 135.928,233.568 134.985,231.052 137.250 C 227.436 140.505,222.652 139.646,221.059 135.455 C 219.775 132.078,220.973 130.103,229.247 121.961 C 240.124 111.258,239.898 111.252,251.022 122.539 M56.250 190.681 C 13.876 207.932,13.281 208.245,13.281 213.281 C 13.281 218.328,14.187 218.836,46.875 232.120 C 63.848 239.017,104.910 255.706,138.124 269.205 C 171.338 282.705,199.199 293.750,200.037 293.750 C 201.176 293.750,327.705 242.741,378.720 221.716 C 386.520 218.501,389.175 213.284,385.352 208.685 C 384.150 207.240,303.352 173.682,302.736 174.373 C 302.620 174.503,301.088 177.482,299.331 180.994 L 296.138 187.378 300.413 197.500 C 306.736 212.472,306.283 214.483,293.758 227.044 C 280.987 239.851,278.874 240.317,263.758 233.651 L 254.470 229.554 246.961 233.046 C 242.832 234.966,237.520 237.152,235.156 237.903 C 229.414 239.729,229.471 239.666,226.564 247.475 C 220.087 264.871,218.447 266.016,200.000 266.015 C 181.658 266.015,180.600 265.325,174.228 249.219 C 170.362 239.447,170.747 239.857,162.891 237.137 C 159.453 235.946,154.219 233.754,151.260 232.266 L 145.879 229.560 136.025 233.721 C 120.690 240.196,118.962 239.812,106.327 227.113 C 93.508 214.229,93.162 212.715,99.567 197.548 L 103.822 187.474 100.642 180.847 C 98.893 177.201,97.242 174.237,96.973 174.260 C 96.704 174.282,78.379 181.672,56.250 190.681 M28.146 248.769 C 13.380 254.885,9.824 259.082,14.648 264.700 C 16.634 267.012,198.824 340.893,201.119 340.317 C 206.724 338.911,384.182 266.087,385.352 264.714 C 387.392 262.317,387.177 256.932,384.961 254.951 C 382.486 252.739,359.492 243.538,357.499 243.962 C 356.597 244.154,321.934 258.158,280.469 275.081 C 207.827 304.728,204.893 305.850,200.000 305.844 C 195.116 305.839,192.033 304.659,119.531 275.054 C 78.066 258.122,43.262 244.155,42.188 244.015 C 41.072 243.871,35.049 245.910,28.146 248.769 M29.279 295.052 C 14.811 300.941,13.281 302.049,13.281 306.641 C 13.281 311.688,13.976 312.051,57.433 329.720 C 79.771 338.802,120.455 355.343,147.842 366.476 C 175.735 377.815,198.698 386.719,200.048 386.719 C 203.183 386.719,382.529 313.820,384.920 311.574 C 387.440 309.206,387.425 304.061,384.891 301.680 C 383.885 300.736,377.506 297.735,370.713 295.011 L 358.363 290.059 352.033 292.618 C 348.552 294.025,314.063 308.077,275.391 323.845 C 211.402 349.936,204.657 352.523,200.391 352.610 C 196.194 352.695,112.381 319.637,44.513 291.127 C 41.810 289.992,41.538 290.062,29.279 295.052 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            Modify Batch
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <div class="relative bg-white px-4 pb-4 pt-2 h-60 min-w-full flex items-center justify-center">
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
                                <p>No assignments found.</p>
                                @if ($implementations)
                                    <p>Try assigning a <span class="animate-pulse text-indigo-900">new batch</span>.
                                    </p>
                                @else
                                    <p>Try creating a <span class="animate-pulse text-indigo-900">new project</span>.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif
                    @if ($implementations)
                        {{-- Assign Button | Main Modal --}}
                        <livewire:focal.implementations.assign-batches-modal :$implementationId />
                    @endif
                </div>

                {{-- List of Beneficiaries by Batch --}}
                <div class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">
                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
                        <div class="inline-flex items-center col-span-1 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="font-bold m-2">List of Beneficiaries</h1>
                            @if ($batches)
                                <span
                                    class="bg-indigo-100 text-indigo-700 rounded px-2 py-1 text-xs font-semibold">{{ $beneficiarySlots['num_of_beneficiaries'] }}
                                    / {{ $beneficiarySlots['batch_slots_allocated'] }}</span>
                            @endif

                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="col-span-1 mx-2 flex items-center justify-end">
                            {{-- Loading State --}}
                            {{-- <div class="items-center justify-end z-50 text-indigo-900" wire:loading
                                wire:target="searchBeneficiaries">
                                <svg class="size-4 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div> --}}
                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 text-indigo-800" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="beneficiary-search" maxlength="100" {{-- wire:model.live="searchBeneficiaries" --}}
                                    class="duration-200 ease-in-out ps-7 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search for beneficiaries">
                            </div>

                            <button
                                @if ($batches && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries']) data-modal-target="add-beneficiaries-modal" data-modal-toggle="add-beneficiaries-modal" @else disabled @endif
                                class="flex items-center {{ $batches && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries'] ? 'bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500' : 'bg-indigo-300 text-indigo-50' }} rounded-md px-4 py-1 text-sm font-bold duration-200 ease-in-out">
                                ADD
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

                    @if ($beneficiaries)

                        {{-- Beneficiaries Table --}}
                        <div id="beneficiaries-table"
                            class="relative max-h-60 overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table class="relative w-full text-sm text-left text-indigo-1100">
                                <thead
                                    class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0 whitespace-nowrap">
                                    <tr>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            first name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            middle name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            last name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            ext.
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            birthdate
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            contact #
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            sex
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            civil status
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            age
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            Senior Citizen
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            PWD
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            occupation
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            avg monthly income
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            e-payment acc num
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            beneficiary type
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            dependent
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            interested in s.e
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            skills training
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse first name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse middle name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse last name
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            spouse ext. name
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($beneficiaries as $key => $beneficiary)
                                        @php
                                            $encryptedId = Crypt::encrypt($beneficiary['id']);
                                        @endphp
                                        <tr wire:click.prevent="selectBeneficiaryRow({{ $key }}, '{{ $encryptedId }}')"
                                            wire:key="beneficiary-{{ $key }}"
                                            class="relative {{ $selectedBeneficiaryRow === $key ? 'bg-indigo-100 hover:bg-indigo-200' : 'bg-white hover:bg-indigo-50' }} border-b whitespace-nowrap">
                                            <th scope="row"
                                                class="pe-2 border-r border-gray-200 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap ">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['first_name'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['middle_name'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['last_name'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['extension_name'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['birthdate'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['contact_num'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['sex'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['civil_status'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['age'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['is_senior_citizen'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['is_pwd'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['occupation'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                @if ($beneficiary['avg_monthly_income'] === null || $beneficiary['avg_monthly_income'] === 0)
                                                    -
                                                @else
                                                    {{ number_format($beneficiary['avg_monthly_income'] / 100, 2) }}
                                                @endif
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['e_payment_acc_num'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['beneficiary_type'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['dependent'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['self_employment'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary['skills_training'] }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['spouse_first_name'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['spouse_middle_name'] ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary['spouse_last_name'] ?? '-' }}
                                            </td>
                                            <td class="px-2">
                                                {{ $beneficiary['spouse_extension_name'] ?? '-' }}
                                            </td>
                                            <td x-data="bfDropdownRotation({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="beneficiaryRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="beneficiaryRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 font-medium rounded text-indigo-1100 hover:text-indigo-1000 focus:outline-none active:text-indigo-900 bg-transparent hover:bg-indigo-200 duration-200 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="w-4 duration-300 ease-in-out">
                                                        <path fill-rule="evenodd"
                                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <tr x-data x-intersect.full="$wire.loadMoreBeneficiaries()">
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- Beneficiary Dropdown Content --}}
                        @foreach ($beneficiaries as $key => $beneficiary)
                            <div wire:key="beneficiaryRowDropdown-{{ $key }}"
                                id="beneficiaryRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="whitespace-nowrap text-sm text-indigo-1100"
                                    aria-labelledby="beneficiaryRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Beneficiary') }}"
                                            class="rounded-t-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M159.766 26.143 C 82.778 40.246,53.502 136.520,109.774 190.543 C 171.402 249.708,273.405 206.387,273.434 121.036 C 273.455 61.032,218.725 15.342,159.766 26.143 M108.984 225.222 C 65.748 242.792,41.518 281.658,42.324 332.151 C 42.784 360.914,45.442 362.881,92.880 369.550 C 127.305 374.389,176.207 376.326,211.328 374.241 C 230.586 373.098,229.521 373.534,224.178 368.982 C 194.528 343.724,194.626 329.021,224.609 304.019 C 247.482 284.947,269.785 275.947,292.354 276.681 L 300.712 276.953 297.944 271.920 C 286.474 251.060,264.398 232.068,242.534 224.249 L 237.803 222.557 231.988 225.690 C 198.778 243.580,159.098 244.035,124.604 226.922 C 114.469 221.894,116.603 222.126,108.984 225.222 M282.963 298.494 C 265.515 300.759,245.158 312.416,228.629 329.605 C 221.808 336.698,221.881 337.153,231.288 346.288 C 270.890 384.742,311.622 384.568,351.031 345.776 C 360.204 336.746,360.201 336.279,350.900 327.303 C 333.660 310.665,315.824 300.917,298.186 298.495 C 291.421 297.567,290.109 297.566,282.963 298.494 M296.712 327.256 C 297.601 327.732,299.013 329.089,299.849 330.272 C 306.643 339.885,295.356 352.202,285.344 346.098 C 272.693 338.384,283.630 320.255,296.712 327.256 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            View Beneficiary
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Modify Beneficiary') }}"
                                            class="rounded-b-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M163.672 26.574 C 94.656 40.726,63.265 121.980,104.983 178.487 C 150.726 240.448,247.697 226.328,273.791 153.906 C 299.100 83.664,236.766 11.585,163.672 26.574 M113.672 225.690 C 71.479 243.619,48.083 280.840,48.056 330.078 C 48.039 360.498,51.244 363.005,98.438 369.501 C 126.542 373.369,150.288 375.000,178.491 375.000 L 199.827 375.000 199.445 368.555 C 198.887 359.140,203.831 330.583,207.182 323.863 C 209.181 319.854,215.072 313.590,246.190 282.388 L 282.844 245.635 280.680 243.598 C 274.109 237.413,258.394 228.023,248.284 224.240 L 243.862 222.586 235.876 226.594 C 201.893 243.647,163.009 243.640,129.787 226.575 C 120.732 221.924,122.327 222.012,113.672 225.690 M319.922 244.437 C 313.971 246.009,298.438 258.182,298.438 261.275 C 298.438 262.408,333.750 297.656,334.886 297.656 C 336.527 297.656,347.527 285.186,349.538 281.047 C 359.039 261.487,340.789 238.927,319.922 244.437 M254.087 304.908 C 238.519 320.497,225.781 333.596,225.781 334.015 C 225.781 334.435,224.547 341.904,223.038 350.614 C 218.582 376.335,219.803 377.431,247.532 372.593 C 264.796 369.580,261.002 372.280,292.964 340.271 L 319.911 313.283 301.563 294.923 C 291.472 284.825,283.031 276.563,282.804 276.563 C 282.578 276.563,269.655 289.318,254.087 304.908 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            Modify Beneficiary
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <div class="relative bg-white px-4 pb-4 pt-2 h-60 min-w-full flex items-center justify-center">
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
                                <p>No beneficiaries found.</p>
                                @if (!$implementations)
                                    <p>Try creating a <span class="animate-pulse text-indigo-900">new project</span>.
                                    </p>
                                @elseif (!$batches)
                                    <p>Try assigning a <span class="animate-pulse text-indigo-900">new batch</span>.
                                    </p>
                                @else
                                    <p>Try adding a <span class="animate-pulse text-indigo-900">new beneficiary</span>.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif
                    @if ($batches && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries'])
                        {{-- Add Button | Add Beneficiaries Modal --}}
                        <livewire:focal.implementations.add-beneficiaries-modal :$batchId />
                    @endif

                </div>
            </div>
        </div>
    </div>

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
        const datepickerStart = document.getElementById('start-date');
        const datepickerEnd = document.getElementById('end-date');

        datepickerStart.addEventListener('changeDate', function(event) {
            document.getElementById('implementations-table').scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            const batchesTable = document.getElementById('batches-table');
            if (batchesTable) {
                batchesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            $wire.dispatchSelf('start-change', {
                value: datepickerStart.value
            });
        });

        datepickerEnd.addEventListener('changeDate', function(event) {
            document.getElementById('implementations-table').scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            const batchesTable = document.getElementById('batches-table');
            if (batchesTable) {
                batchesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            $wire.dispatchSelf('end-change', {
                value: datepickerEnd.value
            });
        });

        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });

        // Implementation Dropdown Shenanigans
        Alpine.data('iDropdownRotation', (key) => ({
            dropdown: null,

            init() {
                // Initialize the dropdown when the Alpine component is initialized
                this.dropdown = new Dropdown(
                    document.getElementById(`implementationRowDropdown-${key}`), document.getElementById(
                        `implementationRowButton-${key}`)
                );
            },
            handleClick() {

                if (!this.dropdown) {
                    this.init();
                }
                this.dropdown.toggle();
            },

            isVisible() {
                return this.dropdown.isVisible();
            },
        }));

        // Batch Assignment Dropdown Shenanigans
        Alpine.data('bDropdownRotation', (key) => ({
            dropdown: null,

            init() {
                // Initialize the dropdown when the Alpine component is initialized
                this.dropdown = new Dropdown(
                    document.getElementById(`batchRowDropdown-${key}`), document.getElementById(
                        `batchRowButton-${key}`)
                );
            },
            handleClick() {

                if (!this.dropdown) {
                    this.init();
                }
                this.dropdown.toggle();
            },

            isVisible() {
                return this.dropdown.isVisible();
            },
        }));

        // Beneficiary Dropdown Shenanigans
        Alpine.data('bfDropdownRotation', (key) => ({
            dropdown: null,

            init() {
                // Initialize the dropdown when the Alpine component is initialized
                this.dropdown = new Dropdown(
                    document.getElementById(`beneficiaryRowDropdown-${key}`), document.getElementById(
                        `beneficiaryRowButton-${key}`)
                );
            },
            handleClick() {

                if (!this.dropdown) {
                    this.init();
                }
                this.dropdown.toggle();
            },

            isVisible() {
                return this.dropdown.isVisible();
            },
        }));
    </script>
@endscript
