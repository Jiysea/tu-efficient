<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, show: false, trapCreate: false, trapAdd: false, profileShow: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <livewire:sidebar.focal-bar />

    <div x-data="{ scrollToTop() { document.getElementById('beneficiaries-table').scrollTo({ top: 0, behavior: 'smooth' }); } }" :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Date Dropdown --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Implementations</h1>

                {{-- Date Range picker --}}
                <div id="implementations-date-range" date-rangepicker datepicker-autohide class="flex items-center">

                    {{-- Start --}}
                    <div class="relative">
                        <div
                            class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <input id="start-date" name="start" type="text" value="{{ $defaultStart }}"
                            datepicker-max-date="{{ $defaultStart }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date start">
                    </div>

                    <span class="mx-4 text-indigo-1100">to</span>

                    {{-- End --}}
                    <div class="relative">
                        <div
                            class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                        <input id="end-date" name="end" type="text" value="{{ $defaultEnd }}"
                            datepicker-min-date="{{ $defaultEnd }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date end">
                    </div>
                </div>

                {{-- Loading State --}}
                <div class="absolute items-center justify-end z-50 min-h-full min-w-full text-indigo-900"
                    wire:loading.flex
                    wire:target="setStartDate, setEndDate, selectImplementationRow, selectBatchRow, selectBeneficiaryRow, loadMoreImplementations, loadMoreBeneficiaries, updateImplementations, updateBatches, viewProject, viewBatch, assignBatch">
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
                <div x-data="{ createProjectModal: $wire.entangle('createProjectModal'), viewProjectModal: $wire.entangle('viewProjectModal') }" class="relative lg:col-span-3 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 flex items-center justify-between">
                        <div class="inline-flex my-2 items-center text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M174.219 1.229 C 54.472 18.124,-24.443 135.741,6.311 251.484 C 9.642 264.022,18.559 287.500,19.989 287.500 C 20.159 287.500,25.487 284.951,31.829 281.836 C 38.171 278.721,43.450 276.139,43.562 276.100 C 43.673 276.060,42.661 273.599,41.313 270.631 C 20.301 224.370,21.504 168.540,44.499 122.720 C 91.474 29.119,207.341 -2.229,294.805 55.000 L 303.283 60.547 296.563 60.773 L 289.844 60.998 289.844 75.030 L 289.844 89.063 316.041 89.063 C 356.109 89.062,354.775 90.537,350.877 50.558 C 349.488 36.310,348.202 24.504,348.019 24.321 C 347.676 23.978,328.468 25.531,323.192 26.328 L 320.212 26.778 320.757 33.742 L 321.302 40.706 315.480 36.529 C 276.374 8.472,220.985 -5.369,174.219 1.229 M146.501 97.750 C 118.151 111.473,94.683 122.973,94.351 123.305 C 94.019 123.637,117.528 137.000,146.593 153.000 L 199.439 182.092 252.454 153.019 C 281.612 137.028,305.456 123.743,305.440 123.496 C 305.396 122.820,200.285 72.645,199.085 72.727 C 198.514 72.766,174.851 84.026,146.501 97.750 M367.815 118.385 L 356.334 124.187 358.736 129.476 C 379.696 175.622,378.473 231.507,355.501 277.280 C 308.659 370.616,191.853 402.240,105.195 345.048 L 96.718 339.453 103.828 339.228 L 110.938 339.004 110.938 324.971 L 110.938 310.938 83.858 310.938 L 56.778 310.937 53.464 312.880 C 49.750 315.056,46.875 319.954,46.875 324.105 C 46.875 327.673,51.612 375.310,52.006 375.704 C 52.327 376.025,69.823 374.588,76.418 373.699 L 79.790 373.245 79.242 366.245 L 78.695 359.245 84.074 363.146 C 180.358 432.973,317.505 400.914,375.933 294.922 C 405.531 241.229,408.161 173.609,382.825 117.732 C 379.977 111.450,381.685 111.375,367.815 118.385 M75.190 209.482 L 75.391 269.080 129.223 295.087 C 158.831 309.391,183.177 321.094,183.325 321.094 C 183.473 321.094,183.585 295.869,183.574 265.039 L 183.554 208.984 130.305 179.688 C 101.018 163.574,76.591 150.277,76.023 150.137 C 75.172 149.928,75.026 160.392,75.190 209.482 M269.139 179.604 L 215.234 209.207 215.034 265.236 C 214.844 318.400,214.904 321.239,216.206 320.749 C 216.961 320.466,241.562 308.738,270.876 294.687 L 324.174 269.141 324.197 209.570 C 324.209 176.807,323.954 150.000,323.631 150.000 C 323.307 150.000,298.786 163.322,269.139 179.604 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="hidden sm:inline-block ms-2 font-bold">List of Projects</h1>
                            <h1 class="max-[460px]:hidden ms-2 font-bold text-sm sm:hidden">Projects</h1>
                            <span
                                class="{{ $totalImplementations ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700 ' }} rounded ms-2 px-2 py-1 font-medium text-xs">{{ $totalImplementations ?? 0 }}</span>
                        </div>

                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="mx-2 flex items-center justify-end">

                            {{-- Loading State --}}
                            <div class="items-center justify-end z-50 text-indigo-900" wire:loading
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
                            </div>

                            <div class="relative me-2">

                                {{-- Search Icon --}}
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 {{ $this->implementations->isNotEmpty() || $searchProjects ? 'text-indigo-800' : 'text-zinc-400' }}"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>

                                {{-- Search Input Bar --}}
                                <input type="text" id="project-search" maxlength="100" autocomplete="off"
                                    @if ($this->implementations->isEmpty() && !$searchProjects) disabled @endif
                                    @input.debounce.300ms="$wire.searchProjects = $el.value; $wire.$refresh();"
                                    class="{{ $this->implementations->isNotEmpty() || $searchProjects
                                        ? 'text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full"
                                    placeholder="Search for project numbers">
                            </div>
                            <button @click="createProjectModal = !createProjectModal;"
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

                    @if (!$this->implementations->isEmpty())
                        {{-- List of Projects Table --}}
                        <div id="implementations-table"
                            class="relative h-[36vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
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
                                    @foreach ($this->implementations as $key => $implementation)
                                        <tr wire:key="implementation-{{ $key }}"
                                            wire:click.prevent='selectImplementationRow({{ $key }}, "{{ encrypt($implementation->id) }}")'
                                            class="relative border-b duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'bg-gray-200 text-indigo-900 hover:bg-gray-300' : ' hover:bg-gray-50' }} whitespace-nowrap cursor-pointer">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $implementation->project_num }}
                                            </th>
                                            <td class="pr-6 py-2">
                                                {{ $implementation->project_title }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $implementation->total_slots }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $implementation->days_of_work }}
                                            </td>
                                            {{-- Implementation Triple Dots --}}
                                            <td class="py-1">
                                                <button type="button"
                                                    @click.stop="$wire.viewProject('{{ encrypt($implementation->id) }}');"
                                                    id="implementationRowButton-{{ $key }}"
                                                    aria-label="{{ __('View Project') }}"
                                                    class="flex items-center justify-center z-0 p-1 outline-none rounded duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M167.400 18.185 C 150.115 28.164,135.964 36.599,135.955 36.930 C 135.915 38.323,147.648 57.404,148.429 57.216 C 148.900 57.102,160.542 50.510,174.301 42.567 C 188.059 34.624,199.643 28.125,200.041 28.125 C 200.440 28.125,212.028 34.632,225.791 42.584 C 239.555 50.536,251.180 57.128,251.623 57.232 C 252.414 57.418,263.978 38.646,264.019 37.109 C 264.044 36.180,201.493 -0.010,199.916 0.021 C 199.317 0.033,184.686 8.207,167.400 18.185 M142.401 86.929 C 110.936 105.075,85.184 120.098,85.174 120.313 C 85.143 120.997,198.778 186.328,200.000 186.328 C 201.235 186.328,314.854 121.022,314.829 120.326 C 314.814 119.917,200.604 53.894,199.959 53.921 C 199.767 53.929,173.866 68.783,142.401 86.929 M58.398 81.093 L 26.563 99.553 26.563 136.886 L 26.563 174.219 38.672 174.219 L 50.781 174.219 50.810 143.945 L 50.838 113.672 74.715 100.000 C 87.847 92.480,99.600 85.725,100.833 84.988 L 103.075 83.647 97.045 73.096 C 93.729 67.293,90.840 62.565,90.625 62.589 C 90.410 62.613,75.908 70.940,58.398 81.093 M302.976 73.087 L 296.967 83.612 304.633 88.095 C 308.849 90.561,320.594 97.324,330.731 103.125 L 349.164 113.672 349.191 143.945 L 349.219 174.219 361.328 174.219 L 373.438 174.219 373.438 136.877 L 373.438 99.536 341.715 81.213 C 324.268 71.136,309.766 62.817,309.489 62.727 C 309.211 62.636,306.280 67.299,302.976 73.087 M73.438 206.912 L 73.437 273.198 130.484 306.130 C 161.859 324.243,187.701 339.063,187.910 339.063 C 188.119 339.063,188.200 309.121,188.090 272.525 L 187.891 205.987 131.283 173.306 C 100.149 155.331,74.397 140.625,74.057 140.625 C 73.716 140.625,73.438 170.454,73.438 206.912 M268.735 173.317 L 212.109 206.009 211.910 272.536 C 211.800 309.125,211.893 339.063,212.117 339.063 C 212.341 339.063,238.183 324.248,269.543 306.142 L 326.563 273.221 326.563 206.923 C 326.563 170.459,326.292 140.625,325.961 140.625 C 325.631 140.625,299.879 155.336,268.735 173.317 M26.563 263.100 L 26.562 300.418 58.699 318.984 C 76.375 329.195,90.906 337.450,90.991 337.329 C 91.077 337.208,93.834 332.442,97.120 326.737 L 103.093 316.364 100.842 315.018 C 99.603 314.278,87.848 307.520,74.719 300.000 L 50.847 286.328 50.814 256.055 L 50.781 225.781 38.672 225.781 L 26.563 225.781 26.563 263.100 M349.191 256.055 L 349.164 286.328 330.731 296.875 C 320.594 302.676,308.845 309.442,304.623 311.911 L 296.947 316.400 302.912 326.755 C 306.193 332.450,308.944 337.210,309.025 337.334 C 309.106 337.457,323.632 329.207,341.305 319.000 L 373.438 300.442 373.438 263.111 L 373.438 225.781 361.328 225.781 L 349.219 225.781 349.191 256.055 M144.483 347.811 C 142.675 350.949,140.012 355.495,138.566 357.915 C 137.120 360.334,135.945 362.619,135.955 362.993 C 135.975 363.819,198.595 400.000,200.004 400.000 C 201.551 400.000,264.043 363.774,264.019 362.891 C 263.978 361.354,252.414 342.582,251.623 342.768 C 251.180 342.872,239.555 349.464,225.791 357.416 C 212.028 365.368,200.441 371.875,200.043 371.875 C 199.471 371.875,174.329 357.579,151.035 344.008 L 147.772 342.107 144.483 347.811 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($this->implementations->count() > 5 && $loop->last)
                                            <tr x-data x-intersect.full.once="$wire.loadMoreImplementations();">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[36vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p>No projects found.</p>
                                <p>Try creating a <span class=" text-indigo-900">new project</span>.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Create Project Modal --}}
                    <div x-cloak>
                        <!-- Modal Backdrop -->
                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                            x-show="createProjectModal">
                        </div>

                        <!-- Modal -->
                        <div x-show="createProjectModal" x-trap.noscroll="createProjectModal"
                            class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                            {{-- The Modal --}}
                            <div class="relative w-full max-w-5xl max-h-full">
                                <div class="relative bg-white rounded-md shadow">

                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                        <h1 class="text-lg font-semibold text-indigo-1100 ">
                                            Create New Project Implementation
                                        </h1>

                                        <div class="flex items-center justify-center">

                                            {{-- Loading State for Changes --}}
                                            <div class="z-50 text-indigo-900" wire:loading wire:target="autoCompute">
                                                <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>

                                            {{-- Close Button --}}
                                            <button type="button"
                                                @click="createProjectModal = false; $wire.resetProject();"
                                                class="text-indigo-400 bg-transparent hover:bg-indigo-200 hover:text-indigo-900 rounded  w-8 h-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-300 ease-in-out">
                                                <svg class="size-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                    </div>

                                    <hr class="">

                                    <!-- Modal body -->
                                    <form wire:submit.prevent="saveProject" class="p-4 md:p-5">
                                        <div class="grid gap-4 mb-4 grid-cols-5 text-xs">

                                            {{-- Project Number --}}
                                            <div class="relative col-span-5 sm:col-span-2 mb-4">
                                                <label for="project_num"
                                                    class="block mb-1  font-medium text-indigo-1100 ">Project
                                                    Number <span class="text-red-700 font-normal text-xs">*</span>
                                                    <span class="text-gray-500 ms-2">prefix:
                                                        <strong>{{ substr($projectNumPrefix ?? config('settings.project_number_prefix'), 0, strlen($projectNumPrefix ?? config('settings.project_number_prefix')) - 1) }}</strong></span></label>
                                                <input type="number" id="project_num" autocomplete="off"
                                                    wire:model.blur="project_num"
                                                    class="text-xs duration-200 {{ $errors->has('project_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5 "
                                                    placeholder="Type project number">
                                                @error('project_num')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Project Title --}}
                                            <div class="relative col-span-5 sm:col-span-3 mb-4">
                                                <label for="project_title"
                                                    class="block mb-1  font-medium text-indigo-1100 ">Project
                                                    Title</label>
                                                <input type="text" id="project_title" autocomplete="off"
                                                    wire:model.blur="project_title"
                                                    class="text-xs duration-200 {{ $errors->has('project_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5       "
                                                    placeholder="Type project title">
                                                @error('project_title')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Budget --}}
                                            <div class="relative col-span-5 sm:col-span-2 mb-4">
                                                <label for="budget_amount"
                                                    class="block mb-1 font-medium text-indigo-1100 ">Budget <span
                                                        class="text-red-700 font-normal text-xs">*</span></label>
                                                <div class="relative">
                                                    <div
                                                        class="text-sm {{ $errors->has('budget_amount') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-600 text-indigo-50' }} absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none">
                                                        <p
                                                            class="flex text-center w-full relative items-center justify-center font-semibold">
                                                            ₱
                                                        </p>
                                                    </div>
                                                    <input x-mask:dynamic="$money($input)" type="text"
                                                        min="0" autocomplete="off" id="budget_amount"
                                                        @blur="$wire.autoCompute(); $wire.set('budget_amount', $el.value)"
                                                        class="text-xs duration-200 {{ $errors->has('budget_amount') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} ps-11 border rounded block w-full pe-2.5 py-2.5"
                                                        placeholder="Type total budget">
                                                </div>
                                                @error('budget_amount')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            {{-- Total Slots --}}
                                            <div class="relative col-span-3 sm:col-span-2 mb-4">

                                                <div class="flex items-center">
                                                    <label for="total_slots"
                                                        class="block mb-1 whitespace-nowrap font-medium text-indigo-1100 ">Total
                                                        Slots <span
                                                            class="text-red-700 font-normal text-xs">*</span></label>
                                                    <div tabindex="-1"
                                                        class="w-full mb-1 flex items-center justify-end">
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <span
                                                                class="me-2 text-xs {{ $isAutoComputeEnabled ? 'text-indigo-900' : 'text-gray-500' }} duration-150 ease-in-out">Auto
                                                                compute by minimum wage</span>
                                                            <input type="checkbox" id="auto-compute"
                                                                wire:click="autoCompute" autocomplete="off"
                                                                wire:model.blur="isAutoComputeEnabled"
                                                                class="sr-only peer">
                                                            <div
                                                                class="relative w-9 h-4 bg-gray-500 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-[calc(100%+8px)] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:size-3 after:transition-all duration-300 ease-in-out peer-checked:bg-indigo-900">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <input type="number" min="0" id="total_slots"
                                                    autocomplete="off" wire:model.blur="total_slots"
                                                    @if ($isAutoComputeEnabled) disabled @endif
                                                    class="text-xs duration-300 ease-in-out {{ $isAutoComputeEnabled ? 'bg-gray-200 border-gray-300 text-indigo-1100 focus:ring-gray-800 focus:border-gray-800' : 'bg-indigo-50 autofill:bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} {{ $errors->has('total_slots') ? 'border-red-500 border bg-red-200 autofill:bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} rounded border block w-full p-2.5"
                                                    placeholder="Type total slots">
                                                @error('total_slots')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            {{-- Days of Work --}}
                                            <div class="relative col-span-2 sm:col-span-1 mb-4">
                                                <label for="days_of_work"
                                                    class="block mb-1  font-medium text-indigo-1100 ">Days of
                                                    Work <span
                                                        class="text-red-700 font-normal text-xs">*</span></label>
                                                <input type="number" min="0" max="15"
                                                    id="days_of_work" wire:model.blur="days_of_work"
                                                    @blur="$wire.autoCompute()"
                                                    class="text-xs duration-200 {{ $errors->has('days_of_work') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5"
                                                    placeholder="Type days of work">
                                                @error('days_of_work')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            {{-- Province --}}
                                            <div class="relative col-span-2 mb-4">
                                                <label for="province"
                                                    class="block mb-1  font-medium text-indigo-1100 ">Province</label>
                                                <select id="province" autocomplete="off" wire:model.live="province"
                                                    class="text-xs duration-200 {{ $errors->has('province') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                                    @foreach ($this->provinces as $province)
                                                        <option>{{ $province }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('province')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- City/Municipality --}}
                                            <div class="relative col-span-3 mb-4">
                                                <label for="city_municipality"
                                                    class="block mb-1  font-medium text-indigo-1100 ">
                                                    City/Municipality</label>
                                                <select id="city_municipality" autocomplete="off"
                                                    wire:model.live="city_municipality"
                                                    wire:key="{{ $province }}"
                                                    class="text-xs duration-200 {{ $errors->has('city_municipality') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                                    @foreach ($this->cities_municipalities as $city_municipality)
                                                        <option>{{ $city_municipality }}</option>
                                                    @endforeach
                                                </select>
                                                @error('city_municipality')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- District --}}
                                            <div class="relative col-span-2 mb-4">
                                                <label for="district"
                                                    class="block mb-1  font-medium text-indigo-1100 ">District</label>
                                                <select id="district" autocomplete="off" wire:model.live="district"
                                                    wire:key="{{ $district }}"
                                                    class="text-xs duration-200 {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                                    @foreach ($this->districts as $district)
                                                        <option>{{ $district }}</option>
                                                    @endforeach
                                                </select>
                                                @error('district')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Purpose --}}
                                            <div class="relative col-span-3 mb-4">
                                                <label for="purpose"
                                                    class="block mb-1  font-medium text-indigo-1100 ">Purpose of
                                                    Project <span
                                                        class="text-red-700 font-normal text-xs">*</span></label>
                                                <select id="purpose" autocomplete="off" wire:model.blur="purpose"
                                                    class="text-xs duration-200 {{ $errors->has('purpose') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                                    <option value="">Select a purpose...</option>
                                                    <option>DUE TO DISPLACEMENT/DISADVANTAGE</option>
                                                </select>
                                                @error('purpose')
                                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="w-full flex relative items-center justify-end">
                                            {{-- Loading State for Changes --}}
                                            <div class="z-50 text-indigo-900" wire:loading wire:target="saveProject">
                                                <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <button type="submit" wire:loading.attr="disabled"
                                                wire:target="saveProject"
                                                class="gap-2 py-2.5 px-3 text-sm rounded outline-none font-bold flex items-center justify-center disabled:opacity-75 text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 focus:ring-4 focus:ring-indigo-300">
                                                <p>CREATE NEW PROJECT</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M87.232 51.235 C 70.529 55.279,55.160 70.785,51.199 87.589 C 49.429 95.097,49.415 238.777,51.184 245.734 C 55.266 261.794,68.035 275.503,84.375 281.371 L 89.453 283.195 164.063 283.423 C 247.935 283.680,244.564 283.880,256.471 277.921 C 265.327 273.488,273.488 265.327,277.921 256.471 C 283.880 244.564,283.680 247.935,283.423 164.063 L 283.195 89.453 281.371 84.375 C 275.503 68.035,261.794 55.266,245.734 51.184 C 239.024 49.478,94.296 49.525,87.232 51.235 M326.172 101.100 C 323.101 102.461,320.032 105.395,318.240 108.682 C 316.870 111.194,316.777 115.490,316.406 193.359 L 316.016 275.391 313.810 281.633 C 308.217 297.460,296.571 308.968,280.859 314.193 L 275.391 316.012 193.359 316.404 L 111.328 316.797 108.019 318.693 C 97.677 324.616,97.060 340.415,106.903 347.255 L 110.291 349.609 195.575 349.609 L 280.859 349.609 287.500 347.798 C 317.300 339.669,339.049 318.056,347.783 287.891 L 349.592 281.641 349.816 196.680 C 350.060 104.007,350.312 109.764,345.807 104.807 C 341.717 100.306,332.072 98.485,326.172 101.100 M172.486 118.401 C 180.422 121.716,182.772 126.649,182.795 140.039 L 182.813 150.000 190.518 150.000 C 209.679 150.000,219.220 157.863,215.628 170.693 C 213.075 179.810,207.578 182.771,193.164 182.795 L 182.813 182.813 182.795 193.164 C 182.771 207.578,179.810 213.075,170.693 215.628 C 157.863 219.220,150.000 209.679,150.000 190.518 L 150.000 182.813 140.039 182.795 C 123.635 182.767,116.211 176.839,117.378 164.698 C 118.318 154.920,125.026 150.593,139.970 150.128 L 150.000 149.815 150.000 142.592 C 150.000 122.755,159.204 112.853,172.486 118.401 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($passedProjectId)
                        <livewire:focal.implementations.view-project :$passedProjectId :key="$passedProjectId" />
                    @endif
                </div>

                {{-- List of Batches --}}
                <div x-data="{ assignBatchesModal: $wire.entangle('assignBatchesModal'), viewBatchModal: $wire.entangle('viewBatchModal') }" class="relative lg:col-span-2 h-full w-full rounded bg-white shadow">
                    <div class="relative flex justify-between max-h-12 items-center">
                        <div class="inline-flex items-center my-2 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="max-[460px]:hidden ms-2 font-bold text-sm sm:text-base">Batches</h1>
                        </div>
                        {{-- Assign Button --}}
                        <div class="mx-2 flex items-center">
                            @if ($remainingBatchSlots || $remainingBatchSlots === 0)
                                <p class="text-xs text-indigo-1100 capitalize font-light me-1">remaining slots:</p>
                                <div
                                    class="{{ $remainingBatchSlots > 0 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }} rounded-md py-1 px-2 text-xs me-2">
                                    {{ $remainingBatchSlots }}</div>
                            @endif
                            <button @if (!$remainingBatchSlots || $remainingBatchSlots === null || $remainingBatchSlots === 0) disabled @endif
                                @if ($implementationId) @click="assignBatchesModal = !assignBatchesModal;" @endif
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

                    @if ($this->implementationId && $this->batches->isNotEmpty())

                        {{-- Batches Table --}}
                        <div id="batches-table"
                            class="relative h-[36vh] overflow-y-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">

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
                                    @foreach ($this->batches as $key => $batch)
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:click='selectBatchRow({{ $key }}, "{{ encrypt($batch->id) }}")'
                                            class="relative border-b whitespace-nowrap duration-200 ease-in-out cursor-pointer {{ $selectedBatchRow === $key ? 'bg-gray-100 text-indigo-900 hover:bg-gray-200' : ' hover:bg-gray-50' }}">
                                            <th scope="row" class="z-0 ps-4 py-2 font-medium">
                                                {{ $batch->barangay_name }}
                                            </th>
                                            <td class="px-2 py-2 text-center">
                                                {{ $batch->current_slots . ' / ' . $batch->slots_allocated }}
                                            </td>
                                            <td class="py-2">
                                                <p
                                                    class="px-1 py-1 text-xs rounded font-semibold uppercase {{ $batch->approval_status === 'approved' ? 'bg-green-300 text-green-950' : 'bg-amber-300 text-amber-950' }}  text-center">
                                                    {{ $batch->approval_status }}
                                                </p>
                                            </td>
                                            <td class="py-1 ps-2">
                                                <button @click.stop="$wire.viewBatch('{{ encrypt($batch->id) }}');"
                                                    id="batchRowButton-{{ $key }}"
                                                    class="flex justify-center items-center z-0 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBatchRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M196.094 28.629 C 195.449 28.884,154.668 44.553,105.469 63.450 C -5.207 105.958,5.050 101.718,2.258 106.121 C -2.113 113.013,-0.475 121.858,5.978 126.207 C 11.439 129.887,195.785 200.000,200.000 200.000 C 204.215 200.000,388.561 129.887,394.022 126.207 C 402.510 120.487,401.990 106.158,393.130 101.648 C 391.538 100.837,348.398 84.081,297.266 64.412 C 207.418 29.852,199.805 27.159,196.094 28.629 M270.092 85.625 C 308.670 100.491,341.237 113.019,342.463 113.463 C 345.218 114.462,202.811 169.873,199.219 169.200 C 198.145 168.999,165.351 156.563,126.345 141.564 L 55.424 114.293 127.517 86.499 C 167.168 71.211,199.686 58.679,199.779 58.649 C 199.873 58.618,231.513 70.758,270.092 85.625 M27.734 178.937 C 8.335 186.462,5.574 187.749,3.334 190.309 C -2.881 197.416,-0.344 209.612,8.118 213.305 C 34.431 224.791,197.646 286.063,201.099 285.752 C 204.384 285.456,376.320 220.179,391.882 213.319 C 400.350 209.586,402.878 197.424,396.666 190.302 C 394.417 187.724,391.728 186.476,372.085 178.892 L 350.029 170.377 330.733 177.806 C 320.120 181.893,310.950 185.509,310.354 185.843 C 309.658 186.232,315.440 188.805,326.508 193.029 C 335.988 196.648,343.743 199.785,343.740 200.000 C 343.737 200.215,311.394 212.816,271.867 228.003 L 200.000 255.614 128.133 228.003 C 88.606 212.816,56.263 200.215,56.260 200.000 C 56.257 199.785,64.002 196.652,73.470 193.038 C 82.938 189.424,90.408 186.230,90.069 185.941 C 89.518 185.472,53.654 171.648,50.781 170.798 C 50.137 170.607,39.766 174.269,27.734 178.937 M28.200 264.467 C 1.675 274.836,-0.000 276.085,0.000 285.509 C 0.000 292.897,2.730 296.701,10.265 299.816 C 49.494 316.032,196.246 371.435,200.000 371.445 C 203.950 371.456,381.599 304.222,393.130 298.352 C 399.546 295.086,402.301 284.114,398.224 278.064 C 395.451 273.950,393.030 272.722,370.793 264.156 L 349.950 256.126 330.249 263.690 C 319.413 267.850,310.240 271.441,309.865 271.670 C 309.490 271.898,317.177 275.154,326.947 278.904 C 343.410 285.223,344.546 285.782,342.472 286.533 C 341.241 286.980,308.701 299.497,270.159 314.349 L 200.084 341.354 127.712 313.514 L 55.339 285.673 72.942 278.969 C 82.624 275.282,90.300 272.037,89.999 271.757 C 89.162 270.976,50.655 256.239,49.682 256.327 C 49.213 256.369,39.546 260.032,28.200 264.467 "
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
                            class="relative bg-white px-4 pb-4 pt-2 h-[36vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($this->implementations->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try creating a <span class=" text-indigo-900">new project</span>.
                                    </p>
                                @elseif (!$implementationId)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class=" text-indigo-900">projects</span> row.
                                    </p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class=" size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No assignments found.</p>
                                    <p>Try assigning a <span class=" text-indigo-900">new batch</span>.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif

                    @if ($implementationId)

                        {{-- Assign Button | Main Modal --}}
                        <div x-cloak>
                            <!-- Modal Backdrop -->
                            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                x-show="assignBatchesModal">
                            </div>

                            <!-- Modal -->
                            <div x-show="assignBatchesModal" x-trap.noscroll="assignBatchesModal"
                                class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                                {{-- The Modal --}}
                                <div class="relative w-full max-w-5xl max-h-full">
                                    <div class="relative bg-white rounded-md shadow">
                                        <form wire:submit.prevent="saveBatches">
                                            <!-- Modal header -->
                                            <div class="flex items-center justify-between py-2 px-4 rounded-t ">
                                                <h1 class="text-lg font-semibold text-indigo-1100">
                                                    Assign and Create New Batches
                                                </h1>
                                                <div class="flex items-center justify-center">
                                                    {{-- Loading State for Changes --}}
                                                    <div class="z-50 text-indigo-900" wire:loading
                                                        wire:target="addBatchRow, editBatchRow, removeBatchRow, addToastCoordinator, removeToastCoordinatorFromBatchList, removeToastCoordinator, getAllCoordinatorsForBatchList, updateCurrentCoordinator, slots_allocated, barangay_name">
                                                        <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                    </div>

                                                    {{-- Close Modal --}}
                                                    <button type="button"
                                                        @click="$wire.resetBatches(); assignBatchesModal = false;"
                                                        class="text-indigo-400 bg-transparent hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 inline-flex justify-center items-center outline-none duration-200 ease-in-out">
                                                        <svg class="size-3" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <hr class="">

                                            {{-- Modal Body --}}
                                            <div class="py-3 px-6 text-indigo-1100 text-xs">
                                                <div class="grid gap-4 grid-cols-5 text-xs">
                                                    <!-- Headers -->
                                                    <div
                                                        class="col-span-full bg-indigo-50 px-4 py-3 shadow-sm rounded-md flex items-center justify-between text-indigo-1100 text-sm font-medium">
                                                        <div
                                                            class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                                                            <p class="">Project Number:
                                                            <p
                                                                class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                                                {{ $this->implementation->project_num }}
                                                            </p>
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                                                            <p class="">City/Municipality:
                                                            <p
                                                                class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                                                {{ $this->implementation->city_municipality }}</p>
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center">
                                                            <p class="">District:
                                                            <p
                                                                class="text-indigo-1000 bg-indigo-200 rounded-md py-1 px-2">
                                                                {{ $this->implementation->district }}
                                                            </p>
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row justify-center items-center duration-200 ease-in-out">
                                                            <p class="">Remaining Slots:
                                                            <p
                                                                class="{{ $remainingSlots === 0 ? 'text-red-1000 bg-red-200' : 'text-indigo-1000 bg-indigo-200' }} rounded-md py-1 px-2">
                                                                {{ $remainingSlots }}</p>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- Batch Number --}}
                                                    <div class="relative col-span-5 sm:col-span-2 mb-4">
                                                        <label for="batch_num"
                                                            class="block mb-1  font-medium text-indigo-1100 ">Batch
                                                            Number <span
                                                                class="text-red-700 font-normal text-xs">*</span><span
                                                                class="text-gray-500 ms-2">prefix:
                                                                {{ substr($batchNumPrefix ?? config('settings.batch_number_prefix'), 0, strlen($batchNumPrefix ?? config('settings.batch_number_prefix')) - 1) }}</span></label>
                                                        <input type="number" id="batch_num"
                                                            wire:model.blur="batch_num" autocomplete="off"
                                                            class="text-xs {{ $errors->has('batch_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full p-2.5 duration-200 ease-in-out"
                                                            placeholder="Type batch number">
                                                        @error('batch_num')
                                                            <p
                                                                class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                {{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>

                                                    {{-- Barangay Name dropdown --}}
                                                    <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }"
                                                        class="relative flex flex-col col-span-3 sm:col-span-2 mb-4">
                                                        <p class="block mb-1 font-medium text-indigo-1100 ">Barangay
                                                            <span class="text-red-700 font-normal text-xs">*</span>
                                                        </p>
                                                        <button type="button" id="barangay_name"
                                                            @click="show = !show;"
                                                            class="text-xs flex items-center justify-between px-4 {{ $errors->has('barangay_name') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                                            @if ($barangay_name)
                                                                <span x-text="barangay_name"></span>
                                                            @else
                                                                <span>Select a barangay...</span>
                                                            @endif

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-3 duration-200 ease-in-out">
                                                                <path fill-rule="evenodd"
                                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>

                                                        {{-- Barangay Name content --}}
                                                        <div x-show="show"
                                                            @click.away=" if(show == true) { show = !show; }"
                                                            class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-100 rounded p-3 mt-2">
                                                            <div
                                                                class="relative flex items-center justify-center py-1 group">
                                                                <svg wire:loading.remove wire:target="searchBarangay"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" fill="currentColor"
                                                                    class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <svg wire:loading wire:target="searchBarangay"
                                                                    class="absolute start-0 ms-2 size-4 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4">
                                                                    </circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                                <input id="searchBarangay"
                                                                    wire:model.live.debounce.300ms="searchBarangay"
                                                                    type="text" autocomplete="off"
                                                                    class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                                    placeholder="Search barangay">
                                                            </div>
                                                            <ul class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44">
                                                                @forelse ($this->barangays as $key => $barangay)
                                                                    <li wire:key={{ $key }}>
                                                                        <button type="button"
                                                                            @click="show = !show; barangay_name = '{{ $barangay }}'; $wire.$refresh();"
                                                                            wire:loading.attr="disabled"
                                                                            aria-label="{{ __('Barangays') }}"
                                                                            class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                                                    </li>
                                                                @empty
                                                                    <div
                                                                        class="h-full w-full text-xs text-gray-500 p-2">
                                                                        Empty Set
                                                                    </div>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                        @error('barangay_name')
                                                            <p
                                                                class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                {{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>

                                                    {{-- Slots --}}
                                                    <div class="relative col-span-2 sm:col-span-1 mb-4">
                                                        <label for="slots_allocated"
                                                            class="block mb-1 font-medium text-indigo-1100 ">Slots
                                                            <span
                                                                class="text-red-700 font-normal text-xs">*</span></label>
                                                        <div class="relative">
                                                            <input type="number" min="0" id="slots_allocated"
                                                                autocomplete="off"
                                                                wire:model.live.debounce.300ms="slots_allocated"
                                                                class="text-xs {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                                                placeholder="Type slots allocation">
                                                        </div>
                                                        @error('slots_allocated')
                                                            <p
                                                                class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                {{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>

                                                    {{-- Add Coordinators dropdown --}}
                                                    <div x-data="{ show: false, currentCoordinator: $wire.entangle('currentCoordinator'), selectedCoordinatorKey: $wire.entangle('selectedCoordinatorKey') }"
                                                        class="relative flex flex-col col-span-5 sm:col-span-2 mb-4">
                                                        <p class="block mb-1 font-medium text-indigo-1100 ">Add
                                                            Coordinator <span
                                                                class="text-red-700 font-normal text-xs">*</span></p>
                                                        <div class="relative z-50 h-full">

                                                            {{-- Current Coordinator --}}
                                                            <button type="button" id="coordinator_name"
                                                                @click="show = !show; $wire.set('searchCoordinator', null);"
                                                                class="w-full h-full border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none text-sm px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                                                <span x-text="currentCoordinator"></span>
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" fill="currentColor"
                                                                    class="size-4 ms-3 duration-200 ease-in-out">
                                                                    <path fill-rule="evenodd"
                                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>

                                                            {{-- Coordinator List Dropdown Content --}}
                                                            <div x-show="show"
                                                                @click.away="
                                                                    if(show == true) 
                                                                    {
                                                                        show = !show;
                                                                        $wire.set('searchCoordinator', null);
                                                                    }
                                                                    "
                                                                class="w-full end-0 absolute text-indigo-1100 bg-indigo-50 shadow-lg border border-indigo-300 rounded p-3 mt-2">
                                                                <div
                                                                    class="relative flex items-center justify-center py-1 group">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="currentColor"
                                                                        class="absolute start-0 ps-2 w-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                    <input id="searchCoordinator"
                                                                        wire:model.live.debounce.300ms="searchCoordinator"
                                                                        type="text"
                                                                        class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                                        placeholder="Search coordinator">
                                                                </div>
                                                                <ul
                                                                    class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-900">
                                                                    @if ($this->coordinators->isNotEmpty())
                                                                        @foreach ($this->coordinators as $key => $coordinator)
                                                                            <li wire:key={{ $key }}>
                                                                                <button type="button"
                                                                                    @click="show= !show; currentCoordinator = '{{ $this->getFullName($coordinator) }}'; selectedCoordinatorKey = {{ $key }};"
                                                                                    wire:loading.attr="disabled"
                                                                                    aria-label="{{ __('Coordinator') }}"
                                                                                    class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                                                    {{ $this->getFullName($coordinator) }}
                                                                                </button>
                                                                            </li>
                                                                        @endforeach
                                                                    @elseif ($this->coordinators->isEmpty() && !is_null($this->searchCoordinator))
                                                                        <li>
                                                                            <p
                                                                                class="text-gray-500 font-medium px-4 py-2 w-full flex items-center justify-start">
                                                                                No coordinators found.</p>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <p
                                                                                class="text-gray-500 font-medium px-4 py-2 w-full flex items-center justify-start">
                                                                                All coordinators were assigned.</p>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    {{-- Assigned Coordinators toast box --}}
                                                    <div
                                                        class="relative grid grid-cols-5 flex-grow col-span-5 sm:col-span-3 mb-4">
                                                        <div class="relative col-span-5">
                                                            <p class="block mb-1 ms-16 font-medium text-indigo-1100 ">
                                                                Assigned Coordinators</p>
                                                            <input type="hidden" id="assigned_coordinators"
                                                                wire:model.blur="assigned_coordinators">
                                                            <div class="relative flex">

                                                                {{-- Arrow/Add button --}}
                                                                <button type="button"
                                                                    @if ($this->coordinators->isNotEmpty()) wire:click="addToastCoordinator" @else disabled @endif
                                                                    class="me-4 px-2 flex items-center justify-center rounded border-2 outline-none duration-200 ease-in-out
                                                                                {{ $this->coordinators->isNotEmpty()
                                                                                    ? 'text-indigo-700 hover:text-indigo-50 active:text-indigo-300 border-indigo-700 focus:ring-indigo-700 focus:ring-2 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900'
                                                                                    : 'text-gray-300 border-gray-300' }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="size-6"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        width="400" height="400"
                                                                        viewBox="0, 0, 400,400">
                                                                        <g>
                                                                            <path
                                                                                d="M277.913 100.212 C 268.376 103.320,263.354 115.296,267.916 124.055 C 268.746 125.649,281.931 139.434,297.217 154.688 L 325.008 182.422 176.371 182.813 L 27.734 183.203 24.044 185.372 C 11.976 192.467,13.880 212.729,26.953 216.320 C 29.173 216.930,72.861 217.180,177.711 217.183 L 325.343 217.188 296.350 246.289 C 268.003 274.743,267.339 275.480,266.516 279.416 C 263.782 292.490,275.629 303.458,288.672 299.926 C 292.603 298.862,379.406 212.826,382.053 207.371 C 383.922 203.517,384.072 197.196,382.390 193.139 C 380.867 189.467,295.574 103.760,291.158 101.464 C 287.389 99.505,281.724 98.970,277.913 100.212 "
                                                                                stroke="none" fill="currentColor"
                                                                                fill-rule="evenodd">
                                                                            </path>
                                                                        </g>
                                                                    </svg>
                                                                </button>
                                                                <div
                                                                    class="text-xs border rounded w-full ps-2 py-2.5 duration-200 ease-in-out overflow-x-scroll whitespace-nowrap scrollbar-thin 
                                                                            
                                                                            @if ($errors->has('assigned_coordinators')) border-red-500 bg-red-200 placeholder-red-600 scrollbar-thumb-red-600 scrollbar-track-red-200
                                                                            @else
                                                                            {{ $assigned_coordinators ? 'bg-indigo-50 border-indigo-300 scrollbar-thumb-indigo-700 scrollbar-track-indigo-50' : 'bg-gray-100 border-gray-400 scrollbar-thumb-gray-700 scrollbar-track-gray-100' }} @endif">
                                                                    @forelse ($assigned_coordinators as $key => $coordinator)
                                                                        <span
                                                                            class="p-1 me-2 rounded duration-200 ease-in-out bg-indigo-200 text-indigo-800 font-medium">
                                                                            {{ $this->getFullName($coordinator) }}
                                                                            {{-- X button --}}
                                                                            <button type="button"
                                                                                wire:click="removeToastCoordinator({{ $key }})"
                                                                                class="ms-1 text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 duration-200 ease-in-out">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="size-2"
                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                    width="400" height="400"
                                                                                    viewBox="0, 0, 400,400">
                                                                                    <g>
                                                                                        <path
                                                                                            d="M361.328 24.634 C 360.898 24.760,359.492 25.090,358.203 25.367 C 356.430 25.748,336.886 44.832,277.930 103.751 L 200.000 181.630 122.461 104.141 C 63.729 45.447,44.353 26.531,42.578 26.152 C 41.289 25.876,39.757 25.501,39.174 25.318 C 34.894 23.974,27.311 29.477,25.821 35.008 C 23.781 42.584,18.944 37.183,104.155 122.463 L 181.634 200.004 104.179 277.541 C 20.999 360.810,24.999 356.511,25.003 362.644 C 25.008 370.270,29.730 374.992,37.356 374.997 C 43.489 375.001,39.190 379.002,122.461 295.819 L 200.000 218.362 277.539 295.819 C 360.929 379.120,356.496 375.000,362.724 375.000 C 371.964 375.000,378.326 365.021,374.228 356.953 C 373.704 355.922,338.420 320.186,295.819 277.539 L 218.362 200.000 295.819 122.461 C 338.420 79.814,373.664 44.154,374.138 43.215 C 378.302 34.974,369.518 22.233,361.328 24.634 "
                                                                                            stroke="none"
                                                                                            fill="currentColor"
                                                                                            fill-rule="evenodd">
                                                                                        </path>
                                                                                    </g>
                                                                                </svg>
                                                                            </button>
                                                                        </span>
                                                                    @empty
                                                                        <p
                                                                            class="ms-1 {{ $errors->has('assigned_coordinators') ? 'text-red-600' : 'text-gray-500' }} ">
                                                                            Added coordinators will be shown here!</p>
                                                                    @endforelse
                                                                </div>
                                                                @error('assigned_coordinators')
                                                                    <p
                                                                        class="ms-14 mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                        {{ $message }}
                                                                    </p>
                                                                @enderror
                                                                {{-- Adding Batch button --}}
                                                                <button type="button" wire:click="addBatchRow"
                                                                    class="flex items-center justify-center space-x-2 text-sm py-2 px-3 whitespace-nowrap rounded ms-4 font-bold bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 focus:ring-indigo-300 focus:ring-4 text-indigo-50 focus:outline-none duration-200 ease-in-out">
                                                                    <p>CREATE BATCH</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="size-3.5"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        width="400" height="400"
                                                                        viewBox="0, 0, 400,400">
                                                                        <g>
                                                                            <path
                                                                                d="M190.042 1.099 C 179.604 4.492,171.157 13.956,168.847 24.843 C 168.234 27.731,167.969 49.998,167.969 98.476 L 167.969 167.969 98.476 167.969 C 23.788 167.969,24.011 167.958,16.162 172.095 C -5.399 183.460,-5.399 216.540,16.162 227.905 C 24.011 232.042,23.788 232.031,98.476 232.031 L 167.969 232.031 167.969 301.524 C 167.969 376.212,167.958 375.989,172.095 383.838 C 183.460 405.399,216.540 405.399,227.905 383.838 C 232.042 375.989,232.031 376.212,232.031 301.524 L 232.031 232.031 301.524 232.031 C 376.212 232.031,375.989 232.042,383.838 227.905 C 405.399 216.540,405.399 183.460,383.838 172.095 C 375.989 167.958,376.212 167.969,301.524 167.969 L 232.031 167.969 232.031 98.476 C 232.031 23.788,232.042 24.011,227.905 16.162 C 221.235 3.509,203.873 -3.399,190.042 1.099 "
                                                                                stroke="none" fill="currentColor"
                                                                                fill-rule="evenodd">
                                                                            </path>
                                                                        </g>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Temporary Batches List --}}
                                                    @if ($temporaryBatchesList)

                                                        {{-- Label --}}
                                                        <div
                                                            class="relative col-span-5 font-semibold text-base text-indigo-1100 ms-2">
                                                            Batch List
                                                        </div>

                                                        {{-- Batch List Table --}}
                                                        <div
                                                            class="relative col-span-5 min-h-[12.375rem] max-h-[12.375rem] overflow-y-auto bg-indigo-50 border border-indigo-300 rounded-md whitespace-nowrap scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">

                                                            <table
                                                                class="relative w-full text-sm text-left text-indigo-1100 rounded-md">
                                                                <thead
                                                                    class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0 ">
                                                                    <tr>
                                                                        <th scope="col" class="ps-4 py-2">
                                                                            batch number
                                                                        </th>
                                                                        <th scope="col" class="px-2 py-2">
                                                                            barangay
                                                                        </th>
                                                                        <th scope="col" class="px-2 py-2">
                                                                            coordinator/s
                                                                        </th>
                                                                        <th scope="col"
                                                                            class="px-2 py-2 text-center">
                                                                            slots
                                                                        </th>
                                                                        <th scope="col" class="px-2 py-2">

                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-xs relative">
                                                                    @foreach ($temporaryBatchesList as $keyBatch => $batch)
                                                                        <tr wire:key='batch-{{ $keyBatch }}'
                                                                            class="relative border-b {{ $selectedBatchListRow === $keyBatch ? 'bg-indigo-100' : 'bg-indigo-50' }} whitespace-nowrap duration-200 ease-in-out">
                                                                            <th scope="row"
                                                                                class="z-0 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap">
                                                                                {{ $batch['batch_num'] }}
                                                                            </th>
                                                                            <td class="px-2 py-2">
                                                                                {{ $batch['barangay_name'] }}
                                                                            </td>
                                                                            <td class="grid-flow-row">
                                                                                @foreach ($batch['assigned_coordinators'] as $keyCoordinator => $coordinator)
                                                                                    <span
                                                                                        class="p-1 mx-1 rounded duration-200 ease-in-out {{ $selectedBatchListRow === $keyBatch ? 'bg-green-300 text-green-1000' : 'bg-indigo-300 text-indigo-1000' }}">
                                                                                        {{ $this->getFullName($coordinator) }}
                                                                                    </span>
                                                                                @endforeach
                                                                            </td>
                                                                            <td class="px-2 py-2 text-center">
                                                                                {{ $batch['slots_allocated'] }}
                                                                            </td>
                                                                            <td
                                                                                class="py-2 flex justify-end items-center">

                                                                                {{-- X button (table list) --}}
                                                                                <button type="button"
                                                                                    wire:click="removeBatchRow({{ $keyBatch }})"
                                                                                    class="p-1 me-3 rounded-md text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 bg-transparent hover:bg-indigo-300 duration-200 ease-in-out">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        class="size-4"
                                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                        width="400" height="400"
                                                                                        viewBox="0, 0, 400,400">
                                                                                        <g>
                                                                                            <path
                                                                                                d="M361.328 24.634 C 360.898 24.760,359.492 25.090,358.203 25.367 C 356.430 25.748,336.886 44.832,277.930 103.751 L 200.000 181.630 122.461 104.141 C 63.729 45.447,44.353 26.531,42.578 26.152 C 41.289 25.876,39.757 25.501,39.174 25.318 C 34.894 23.974,27.311 29.477,25.821 35.008 C 23.781 42.584,18.944 37.183,104.155 122.463 L 181.634 200.004 104.179 277.541 C 20.999 360.810,24.999 356.511,25.003 362.644 C 25.008 370.270,29.730 374.992,37.356 374.997 C 43.489 375.001,39.190 379.002,122.461 295.819 L 200.000 218.362 277.539 295.819 C 360.929 379.120,356.496 375.000,362.724 375.000 C 371.964 375.000,378.326 365.021,374.228 356.953 C 373.704 355.922,338.420 320.186,295.819 277.539 L 218.362 200.000 295.819 122.461 C 338.420 79.814,373.664 44.154,374.138 43.215 C 378.302 34.974,369.518 22.233,361.328 24.634 "
                                                                                                stroke="none"
                                                                                                fill="currentColor"
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
                                                        {{-- Shows up on initial modal open or when there's no batches created yet --}}
                                                        <div
                                                            class="relative col-span-5 bg-white pb-4 pt-2 h-60 min-w-full flex flex-col items-center justify-center">
                                                            <div
                                                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-gray-500 bg-red-50 border-red-300' : 'text-gray-500 bg-gray-50 border-gray-300' }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="size-12 sm:size-20 mb-4 duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-500' : 'text-gray-500' }}"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="400" height="400"
                                                                    viewBox="0, 0, 400,400">
                                                                    <g>
                                                                        <path
                                                                            d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                                            stroke="none" fill="currentColor"
                                                                            fill-rule="evenodd"></path>
                                                                    </g>
                                                                </svg>
                                                                <p>No batches found.</p>
                                                                <p>Try creating a <span
                                                                        class="duration-500 ease-in-out {{ $errors->has('temporaryBatchesList') ? 'text-red-700' : 'text-indigo-900' }} ">new
                                                                        batch</span>.</p>
                                                            </div>
                                                            @error('temporaryBatchesList')
                                                                <div
                                                                    class="absolute bottom-0 flex items-center justify-center w-full">
                                                                    <p class="text-red-500 z-10 text-xs">
                                                                        {{ $message }}
                                                                    </p>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    {{-- Modal footer --}}
                                                    <div class="col-span-full w-full flex items-center justify-end">

                                                        {{-- Loading State for Changes --}}
                                                        <div class="z-50 text-indigo-900" wire:loading
                                                            wire:target="saveBatches">
                                                            <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4">
                                                                </circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                </path>
                                                            </svg>
                                                        </div>

                                                        {{-- Finish Button --}}
                                                        <button type="submit" wire:loading.attr="disabled"
                                                            wire:target="saveBatches"
                                                            class="space-x-2 text-sm rounded-md py-2 px-4 text-center text-white font-bold flex items-center duration-200 ease-in-out bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                                            <p>FINISH</p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="400" height="400"
                                                                viewBox="0, 0, 400,400">
                                                                <g>
                                                                    <path
                                                                        d="M176.222 16.066 C 28.153 35.847,-39.558 211.481,57.248 324.669 C 157.007 441.310,349.713 393.836,383.125 244.388 C 411.601 117.016,304.582 -1.082,176.222 16.066 M301.850 131.509 C 305.728 134.467,307.570 139.619,306.306 143.971 C 305.319 147.369,169.764 284.375,167.389 284.375 C 166.285 284.375,96.190 214.001,94.754 211.451 C 90.790 204.410,96.950 194.541,105.312 194.534 C 110.533 194.530,111.285 195.163,139.058 222.996 C 159.505 243.486,165.653 249.219,167.181 249.219 C 168.729 249.219,181.425 236.938,228.123 190.269 C 260.566 157.846,288.164 130.758,289.453 130.072 C 292.834 128.275,298.465 128.927,301.850 131.509 "
                                                                        stroke="none" fill="currentColor"
                                                                        fill-rule="evenodd"></path>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($passedBatchId)

                            {{-- View Batch Modal --}}
                            <div x-cloak>
                                <!-- Modal Backdrop -->
                                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                    x-show="viewBatchModal">
                                </div>

                                <!-- Modal -->
                                <div x-show="viewBatchModal" x-trap.noscroll="viewBatchModal"
                                    class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                    {{-- The Modal --}}
                                    <div class="relative w-full max-w-5xl max-h-full">
                                        <div class="relative bg-white rounded-md shadow">

                                            <form wire:submit.prevent="editBatch">
                                                <!-- Modal Header -->
                                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                    <span class="flex items-center justify-center">
                                                        <h1
                                                            class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                            View Batch

                                                        </h1>

                                                    </span>
                                                    <div class="flex items-center justify-center">
                                                        {{-- Loading State for Changes --}}
                                                        <div class="z-50 text-indigo-900" wire:loading
                                                            wire:target="editBatch, liveUpdateRemainingSlots, toggleEditBatch, view_barangay_name, addToastCoordinator, removeToastCoordinator, deleteBatch">
                                                            <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4">
                                                                </circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <button type="button"
                                                            @click="$wire.resetViewBatch(); viewBatchModal = false;"
                                                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                                            <svg class="size-3" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 14 14">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                            </svg>
                                                            <span class="sr-only">Close Modal</span>
                                                        </button>
                                                    </div>
                                                </div>

                                                <hr class="">

                                                {{-- Modal body --}}
                                                @if ($this->batch)
                                                    <div class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                                                            {{-- Edit Mode is ON --}}
                                                            @if ($editMode)

                                                                {{-- Batch Number --}}
                                                                <div class="flex flex-1 flex-col relative mb-4">

                                                                    <label for="view_batch_num"
                                                                        class="block mb-1  font-medium text-indigo-1100 ">Batch
                                                                        Number <span
                                                                            class="text-red-700 font-normal text-xs">*</span>
                                                                        <span class="text-gray-500 ms-2">prefix:
                                                                            <strong>{{ substr($batchNumPrefix ?? config('settings.batch_number_prefix'), 0, strlen($batchNumPrefix ?? config('settings.batch_number_prefix')) - 1) }}</strong></span></label>
                                                                    <input type="text" id="view_batch_num"
                                                                        autocomplete="off"
                                                                        wire:model.blur="view_batch_num"
                                                                        class="text-xs duration-200 {{ $errors->has('view_batch_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5 "
                                                                        placeholder="Type project number">
                                                                    @error('view_batch_num')
                                                                        <p
                                                                            class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                            {{ $message }}
                                                                        </p>
                                                                    @enderror

                                                                </div>

                                                                {{-- Barangay --}}
                                                                <div x-data="{ show: false, view_barangay_name: $wire.entangle('view_barangay_name') }"
                                                                    class="relative flex flex-col mb-4">
                                                                    @if ($isEmpty)
                                                                        <p
                                                                            class="block mb-1 font-medium text-indigo-1100 ">
                                                                            Barangay <span
                                                                                class="text-red-700 font-normal text-xs">*</span>
                                                                        </p>

                                                                        {{-- Barangay Button --}}
                                                                        <button type="button" id="view_barangay_name"
                                                                            @click="show = !show;"
                                                                            class="text-xs flex items-center justify-between px-4 {{ $errors->has('view_barangay_name') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out">
                                                                            <span x-text="view_barangay_name"></span>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 24 24"
                                                                                fill="currentColor"
                                                                                class="size-3 duration-200 ease-in-out">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </button>

                                                                        {{-- Barangay Dropdown --}}
                                                                        <div x-show="show"
                                                                            @click.away=" 
                                                                        if(show == true) 
                                                                        { 
                                                                            show = !show; 
                                                                            $wire.set('searchBarangay', null);
                                                                        }"
                                                                            class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-300 rounded p-3 mt-2">
                                                                            <div
                                                                                class="relative flex items-center justify-center py-1 group">
                                                                                {{-- Search Icon --}}
                                                                                <svg wire:loading.remove
                                                                                    wire:target="searchBarangay"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    viewBox="0 0 24 24"
                                                                                    fill="currentColor"
                                                                                    class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                                        clip-rule="evenodd" />
                                                                                </svg>
                                                                                {{-- Loading Icon --}}
                                                                                <svg wire:loading
                                                                                    wire:target="searchBarangay"
                                                                                    class="absolute start-0 ms-2 size-4 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25"
                                                                                        cx="12" cy="12"
                                                                                        r="10" stroke="currentColor"
                                                                                        stroke-width="4">
                                                                                    </circle>
                                                                                    <path class="opacity-75"
                                                                                        fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                                {{-- Search Input Box --}}
                                                                                <input id="searchBarangay"
                                                                                    wire:model.live.debounce.500ms="searchBarangay"
                                                                                    type="text" autocomplete="off"
                                                                                    class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                                                    placeholder="Search barangay">
                                                                            </div>
                                                                            {{-- List of Barangays --}}
                                                                            <ul
                                                                                class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">
                                                                                @forelse ($this->barangays as $key => $barangay)
                                                                                    <li wire:key={{ $key }}>
                                                                                        <button type="button"
                                                                                            @click="show = !show; view_barangay_name = '{{ $barangay }}'; $wire.$refresh();"
                                                                                            wire:loading.attr="disabled"
                                                                                            aria-label="{{ __('Barangays') }}"
                                                                                            class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                                                                    </li>
                                                                                @empty
                                                                                    <div
                                                                                        class="h-full w-full text-xs text-gray-500 p-2">
                                                                                        No barangays found
                                                                                    </div>
                                                                                @endforelse
                                                                            </ul>
                                                                        </div>
                                                                        @error('view_barangay_name')
                                                                            <p
                                                                                class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                                {{ $message }}
                                                                            </p>
                                                                        @enderror
                                                                    @else
                                                                        <p
                                                                            class="block mb-1 font-medium text-indigo-1100">
                                                                            Barangay
                                                                        </p>
                                                                        <span
                                                                            class="flex flex-1 text-xs rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->barangay_name }}</span>
                                                                    @endif
                                                                </div>

                                                                {{-- Slots --}}
                                                                <div class="flex flex-1 flex-col relative mb-4">
                                                                    @if ($isEmpty)
                                                                        <label for="view_slots_allocated"
                                                                            class="flex items-center justify-between mb-1 font-medium text-indigo-1100 ">
                                                                            <p>
                                                                                Allocated Slots
                                                                                <span
                                                                                    class="text-red-700 font-normal text-xs">*</span>
                                                                            </p>

                                                                            <span
                                                                                class="{{ $remainingSlots === 0 ? 'text-red-1000 bg-red-200' : 'text-indigo-1000 bg-indigo-200' }} absolute top-0 right-0 rounded-md py-1 px-2">
                                                                                <span>Remaining:</span>
                                                                                <span
                                                                                    class="ps-1">{{ $remainingSlots }}
                                                                                </span>
                                                                            </span>

                                                                        </label>
                                                                        <div class="relative">
                                                                            <input type="number" min="0"
                                                                                id="view_slots_allocated"
                                                                                autocomplete="off"
                                                                                wire:model.live.debounce.300ms="view_slots_allocated"
                                                                                class="text-xs {{ $errors->has('view_slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} outline-none border rounded block w-full py-2.5 duration-200 ease-in-out"
                                                                                placeholder="Type slots allocation">
                                                                        </div>
                                                                        @error('view_slots_allocated')
                                                                            <p
                                                                                class="mt-2 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                                                {{ $message }}
                                                                            </p>
                                                                        @enderror
                                                                    @else
                                                                        <p
                                                                            class="block mb-1 font-medium text-indigo-1100">
                                                                            Allocated Slots
                                                                        </p>
                                                                        <span
                                                                            class="flex flex-1 text-xs rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->slots_allocated }}</span>
                                                                    @endif
                                                                </div>

                                                                {{-- Coordinators --}}
                                                                <div x-data="{ show: false, currentCoordinator: $wire.entangle('currentCoordinator'), selectedCoordinatorKey: $wire.entangle('selectedCoordinatorKey') }"
                                                                    class="flex flex-col relative mb-4">
                                                                    <p class="block mb-1 font-medium text-indigo-1100">
                                                                        Add Coordinator <span
                                                                            class="text-red-700 font-normal text-xs">*</span>
                                                                    </p>

                                                                    <div class="relative z-50 h-full">
                                                                        <button type="button" id="coordinator_name"
                                                                            @click="show = !show;"
                                                                            class="w-full h-full border bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none text-xs px-4 py-2.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                                                            <span x-text="currentCoordinator"></span>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 24 24"
                                                                                fill="currentColor"
                                                                                class="size-4 ms-3 duration-200 ease-in-out">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </button>

                                                                        {{-- Dropdown Content --}}
                                                                        <div x-show="show"
                                                                            @click.away="
                                                                            if(show == true) {
                                                                            show = !show;
                                                                            $wire.set('searchCoordinator', null);
                                                                            }
                                                                            "
                                                                            class="w-full end-0 absolute text-indigo-1100 bg-white shadow-lg border border-indigo-300 rounded p-3 mt-2">
                                                                            <div
                                                                                class="relative flex items-center justify-center py-1 group">
                                                                                {{-- Search Icon --}}
                                                                                <svg wire:loading.remove
                                                                                    wire:target="searchCoordinator"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    viewBox="0 0 24 24"
                                                                                    fill="currentColor"
                                                                                    class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                                                        clip-rule="evenodd" />
                                                                                </svg>
                                                                                {{-- Loading Icon --}}
                                                                                <svg wire:loading
                                                                                    wire:target="searchCoordinator"
                                                                                    class="absolute start-0 ms-2 size-4 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25"
                                                                                        cx="12" cy="12"
                                                                                        r="10" stroke="currentColor"
                                                                                        stroke-width="4">
                                                                                    </circle>
                                                                                    <path class="opacity-75"
                                                                                        fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                                {{-- Search Bar --}}
                                                                                <input id="searchCoordinator"
                                                                                    wire:model.live.debounce.300ms="searchCoordinator"
                                                                                    type="text" autocomplete="off"
                                                                                    class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                                                    placeholder="Search coordinator">
                                                                            </div>

                                                                            {{-- Available Coordinators List --}}
                                                                            <ul
                                                                                class="mt-2 text-xs overflow-y-auto h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-700">

                                                                                @if ($this->coordinators->isNotEmpty())
                                                                                    @foreach ($this->coordinators as $key => $coordinator)
                                                                                        <li
                                                                                            wire:key={{ $key }}>
                                                                                            <button type="button"
                                                                                                @click="show= !show; currentCoordinator = '{{ $this->getFullName($coordinator) }}'; selectedCoordinatorKey = {{ $key }};"
                                                                                                wire:loading.attr="disabled"
                                                                                                aria-label="{{ __('Coordinator') }}"
                                                                                                class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                                                                {{ $this->getFullName($coordinator) }}
                                                                                            </button>
                                                                                        </li>
                                                                                    @endforeach
                                                                                @elseif($this->coordinators->isEmpty() && is_null($searchCoordinator))
                                                                                    <li>
                                                                                        <p
                                                                                            class="w-full flex items-center justify-start text-gray-500 px-4 py-2">
                                                                                            All coordinators were
                                                                                            assigned.</p>
                                                                                    </li>
                                                                                @else
                                                                                    <li>
                                                                                        <p
                                                                                            class="w-full flex items-center justify-start text-gray-500 px-4 py-2">
                                                                                            No coordinators found.</p>
                                                                                    </li>
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Assigned Coordinators --}}
                                                                <div class="relative flex mb-4 gap-4 sm:col-span-2">

                                                                    {{-- Arrow button --}}
                                                                    <button type="button"
                                                                        @if ($this->coordinators->isNotEmpty()) wire:click="addToastCoordinator" @else disabled @endif
                                                                        class="z-40 p-2.5 grid place-items-center place-self-end rounded border-2 outline-none duration-200 ease-in-out
                                                                            {{ $this->coordinators->isNotEmpty()
                                                                                ? 'text-indigo-700 hover:text-indigo-50 active:text-indigo-300 border-indigo-700 focus:ring-indigo-700 focus:ring-2 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900'
                                                                                : 'text-gray-300 border-gray-300' }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="size-5"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                            width="400" height="400"
                                                                            viewBox="0, 0, 400,400">
                                                                            <g>
                                                                                <path
                                                                                    d="M277.913 100.212 C 268.376 103.320,263.354 115.296,267.916 124.055 C 268.746 125.649,281.931 139.434,297.217 154.688 L 325.008 182.422 176.371 182.813 L 27.734 183.203 24.044 185.372 C 11.976 192.467,13.880 212.729,26.953 216.320 C 29.173 216.930,72.861 217.180,177.711 217.183 L 325.343 217.188 296.350 246.289 C 268.003 274.743,267.339 275.480,266.516 279.416 C 263.782 292.490,275.629 303.458,288.672 299.926 C 292.603 298.862,379.406 212.826,382.053 207.371 C 383.922 203.517,384.072 197.196,382.390 193.139 C 380.867 189.467,295.574 103.760,291.158 101.464 C 287.389 99.505,281.724 98.970,277.913 100.212 "
                                                                                    stroke="none" fill="currentColor"
                                                                                    fill-rule="evenodd">
                                                                                </path>
                                                                            </g>
                                                                        </svg>
                                                                    </button>

                                                                    {{-- Toast Area --}}
                                                                    <div class="flex flex-1 flex-col overflow-auto">
                                                                        <p
                                                                            class="block mb-1 text-indigo-1100 font-medium">
                                                                            Assigned Coordinators
                                                                        </p>
                                                                        <span
                                                                            class="flex flex-1 py-1 overflow-x-scroll rounded border border-indigo-300 scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">

                                                                            {{-- A Toast of Coordinators --}}
                                                                            @foreach ($view_assigned_coordinators as $key => $assignedCoordinator)
                                                                                <span
                                                                                    class=" py-1 px-2 mx-1 rounded whitespace-nowrap duration-200 ease-in-out bg-indigo-100 text-indigo-700">
                                                                                    {{ $this->getFullName($assignedCoordinator) }}

                                                                                    {{-- X button --}}
                                                                                    <button type="button"
                                                                                        wire:click="removeToastCoordinator({{ $key }})"
                                                                                        class="ms-1 text-indigo-1100 hover:text-indigo-900 active:text-indigo-1000 duration-200 ease-in-out">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                            class="size-2"
                                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                            width="400"
                                                                                            height="400"
                                                                                            viewBox="0, 0, 400,400">
                                                                                            <g>
                                                                                                <path
                                                                                                    d="M361.328 24.634 C 360.898 24.760,359.492 25.090,358.203 25.367 C 356.430 25.748,336.886 44.832,277.930 103.751 L 200.000 181.630 122.461 104.141 C 63.729 45.447,44.353 26.531,42.578 26.152 C 41.289 25.876,39.757 25.501,39.174 25.318 C 34.894 23.974,27.311 29.477,25.821 35.008 C 23.781 42.584,18.944 37.183,104.155 122.463 L 181.634 200.004 104.179 277.541 C 20.999 360.810,24.999 356.511,25.003 362.644 C 25.008 370.270,29.730 374.992,37.356 374.997 C 43.489 375.001,39.190 379.002,122.461 295.819 L 200.000 218.362 277.539 295.819 C 360.929 379.120,356.496 375.000,362.724 375.000 C 371.964 375.000,378.326 365.021,374.228 356.953 C 373.704 355.922,338.420 320.186,295.819 277.539 L 218.362 200.000 295.819 122.461 C 338.420 79.814,373.664 44.154,374.138 43.215 C 378.302 34.974,369.518 22.233,361.328 24.634 "
                                                                                                    stroke="none"
                                                                                                    fill="currentColor"
                                                                                                    fill-rule="evenodd">
                                                                                                </path>
                                                                                            </g>
                                                                                        </svg>
                                                                                    </button>
                                                                                </span>
                                                                            @endforeach
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                {{-- Save & Cancel Buttons --}}
                                                                <div
                                                                    class="flex items-center {{ $isEmpty ? 'justify-end' : 'justify-between' }} col-span-full gap-2 sm:gap-4">
                                                                    @if (!$isEmpty)
                                                                        <span
                                                                            class="flex flex-1 items-center justify-start font-medium border bg-red-100 border-red-300 text-red-950 rounded text-xs p-3 outline-none">
                                                                            <svg class="size-3.5 me-2"
                                                                                aria-hidden="true"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                                            </svg>
                                                                            Some fields can only be editable if this
                                                                            batch has no beneficiaries yet.
                                                                        </span>
                                                                    @endif

                                                                    {{-- SAVE BUTTON --}}
                                                                    <div class="flex items-center justify-center">
                                                                        <button type="submit" wire:target="editBatch"
                                                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                                                            SAVE
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="size-4 ms-2"
                                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                width="400" height="400"
                                                                                viewBox="0, 0, 400,400">
                                                                                <g>
                                                                                    <path
                                                                                        d="M179.372 38.390 C 69.941 52.432,5.211 171.037,53.012 269.922 C 112.305 392.582,285.642 393.654,346.071 271.735 C 403.236 156.402,307.211 21.986,179.372 38.390 M273.095 139.873 C 278.022 142.919,280.062 149.756,277.522 154.718 C 275.668 158.341,198.706 250.583,194.963 253.668 C 189.575 258.110,180.701 259.035,173.828 255.871 C 168.508 253.422,123.049 207.486,121.823 203.320 C 119.042 193.868,129.809 184.732,138.528 189.145 C 139.466 189.620,149.760 199.494,161.402 211.088 L 182.569 232.168 220.917 186.150 C 242.008 160.840,260.081 139.739,261.078 139.259 C 264.132 137.789,270.227 138.101,273.095 139.873 "
                                                                                        stroke="none"
                                                                                        fill="currentColor"
                                                                                        fill-rule="evenodd">
                                                                                    </path>
                                                                                </g>
                                                                            </svg>
                                                                        </button>

                                                                        {{-- CANCEL | X Button --}}
                                                                        <button type="button"
                                                                            wire:click.prevent="toggleEditBatch"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="toggleEditBatch"
                                                                            class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-3 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50">

                                                                            <svg class="size-3" aria-hidden="true"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 14 14">
                                                                                <path stroke="currentColor"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            {{-- Edit Mode is OFF --}}
                                                            @if (!$editMode)

                                                                {{-- City/Municipality --}}
                                                                <div class="relative flex flex-col mb-4">
                                                                    <p class="block mb-1 font-medium text-indigo-1100">
                                                                        City/Municipality
                                                                    </p>
                                                                    <span
                                                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->city_municipality }}</span>
                                                                </div>

                                                                {{-- District --}}
                                                                <div class="relative flex flex-col mb-4">
                                                                    <p class="block mb-1 font-medium text-indigo-1100">
                                                                        District
                                                                    </p>
                                                                    <span
                                                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->district }}</span>
                                                                </div>

                                                                {{-- Edit/Delete Buttons OFF --}}
                                                                <div x-data="{ deleteBatchModal: $wire.entangle('deleteBatchModal') }"
                                                                    class="flex justify-center items-center">
                                                                    <button type="button"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="toggleEditBatch"
                                                                        @if ($this->batch->approval_status !== 'approved') wire:click.prevent="toggleEditBatch" @else disabled @endif
                                                                        class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm disabled:border disabled:cursor-not-allowed disabled:border-gray-500 disabled:bg-gray-100 disabled:text-gray-500 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                                                        EDIT
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="size-4 ms-2"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                            width="400" height="400"
                                                                            viewBox="0, 0, 400,400">
                                                                            <g>
                                                                                <path
                                                                                    d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                                                                    stroke="none" fill="currentColor"
                                                                                    fill-rule="evenodd">
                                                                                </path>
                                                                            </g>
                                                                        </svg>
                                                                    </button>

                                                                    {{-- Delete/Trash Button --}}
                                                                    <button type="button"
                                                                        @if ($isEmpty) @click="deleteBatchModal = !deleteBatchModal;" @else disabled @endif
                                                                        class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border disabled:cursor-not-allowed disabled:border-gray-500 disabled:bg-gray-100 disabled:text-gray-500 border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="size-6"
                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                            width="400" height="400"
                                                                            viewBox="0, 0, 400,400">
                                                                            <g>
                                                                                <path
                                                                                    d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                                                    stroke="none"
                                                                                    fill="currentColor"
                                                                                    fill-rule="evenodd">
                                                                                </path>
                                                                            </g>
                                                                        </svg>
                                                                    </button>

                                                                    {{-- Delete Batch Modal --}}
                                                                    <div x-cloak>
                                                                        <!-- Modal Backdrop -->
                                                                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                                                            x-show="deleteBatchModal">
                                                                        </div>

                                                                        <!-- Modal -->
                                                                        <div x-show="deleteBatchModal"
                                                                            x-trap.noscroll="deleteBatchModal"
                                                                            class="fixed inset-0 pt-4 px-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                                                            {{-- The Modal --}}
                                                                            <div
                                                                                class="relative w-full max-w-xl max-h-full">
                                                                                <div
                                                                                    class="relative bg-white rounded-md shadow">
                                                                                    <!-- Modal Header -->
                                                                                    <div
                                                                                        class="flex items-center py-2 px-4 rounded-t-md">
                                                                                        <h1
                                                                                            class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                                                            Delete the Batch
                                                                                        </h1>
                                                                                    </div>

                                                                                    <hr class="">

                                                                                    {{-- Modal body --}}
                                                                                    <div
                                                                                        class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                                                                        <p
                                                                                            class="font-medium text-sm mb-1">
                                                                                            Are you sure about deleting
                                                                                            this batch?
                                                                                        </p>
                                                                                        <p
                                                                                            class="text-gray-500 text-sm mb-4">
                                                                                            (This is action is
                                                                                            irreversible)
                                                                                        </p>
                                                                                        <div
                                                                                            class="flex items-center justify-center w-full gap-4">
                                                                                            <button type="button"
                                                                                                class="duration-200 ease-in-out flex flex-1 items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50"
                                                                                                @click="deleteBatchModal = false;">CANCEL</button>

                                                                                            <button type="button"
                                                                                                @click="$wire.deleteBatch();"
                                                                                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">CONFIRM</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Batch Number OFF --}}
                                                                <div class="relative flex flex-col mb-4">
                                                                    <p
                                                                        class="block mb-1 font-medium text-indigo-1100">
                                                                        Batch Number
                                                                    </p>
                                                                    <span
                                                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->batch_num }}</span>
                                                                </div>

                                                                {{-- Barangay OFF --}}
                                                                <div class="relative flex flex-col mb-4">
                                                                    <p
                                                                        class="block mb-1 font-medium text-indigo-1100">
                                                                        Barangay
                                                                    </p>
                                                                    <span
                                                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->barangay_name }}</span>
                                                                </div>

                                                                {{-- Slots OFF --}}
                                                                <div class="relative flex flex-col mb-4">
                                                                    <p
                                                                        class="block mb-1 font-medium text-indigo-1100">
                                                                        Alloted Slots
                                                                    </p>
                                                                    <span
                                                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->batch->slots_allocated }}</span>
                                                                </div>

                                                                {{-- Assigned Coordinators OFF --}}
                                                                <div
                                                                    class="relative flex flex-col mb-4 col-span-full">
                                                                    <p
                                                                        class="block mb-3 text-indigo-1100 mx-auto font-semibold">
                                                                        Assigned Coordinators <span
                                                                            class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 ms-1 font-medium">{{ sizeof($this->assignedCoordinators) }}</span>
                                                                    </p>
                                                                    <span
                                                                        class="flex flex-1 text-sm rounded p-2.5 border border-indigo-100 text-indigo-700 font-medium overflow-x-scroll scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                                                        {{-- Toast Box of Coordinators --}}
                                                                        @foreach ($this->assignedCoordinators as $key => $assignedCoordinator)
                                                                            <span
                                                                                class="py-1 px-2 me-2 rounded whitespace-nowrap duration-200 ease-in-out bg-indigo-100 text-indigo-700 font-medium">
                                                                                {{ $this->getFullName($assignedCoordinator) }}
                                                                            </span>
                                                                        @endforeach
                                                                    </span>
                                                                </div>

                                                                {{-- Date created && Last updated --}}
                                                                <div
                                                                    class="flex flex-col sm:flex-row items-center justify-between col-span-full gap-2 sm:gap-4">
                                                                    <div
                                                                        class="flex flex-1 items-center justify-center">
                                                                        <p class="font-bold text-indigo-1100">
                                                                            Date of Creation:
                                                                        </p>
                                                                        <span
                                                                            class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                                                            {{ date('M d, Y @ h:i:s a', strtotime($this->batch->created_at)) }}</span>
                                                                    </div>

                                                                    <div
                                                                        class="flex flex-1 items-center justify-center">
                                                                        <p class="font-bold text-indigo-1100">
                                                                            Last Updated:
                                                                        </p>
                                                                        <span
                                                                            class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                                                            {{ date('M d, Y @ h:i:s a', strtotime($this->batch->updated_at)) }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                    @endif
                </div>

                {{-- List of Beneficiaries by Batch --}}
                <div class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
                        <div class="inline-flex items-center my-2 col-span-1 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                height="384.37499999999994" viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="hidden sm:inline-block ms-2 font-bold">List of Beneficiaries</h1>
                            <h1 class="max-[500px]:hidden ms-2 font-bold text-sm sm:hidden">Beneficiaries</h1>

                            <span
                                class="{{ $batchId ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700' }} rounded px-2 py-1 ms-2 text-xs font-medium">
                                {{ $batchId ? ($beneficiarySlots['num_of_beneficiaries'] ?? 0) . ' / ' . $beneficiarySlots['batch_slots_allocated'] : 'N / A' }}</span>
                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="col-span-1 mx-2 flex items-center justify-end">
                            {{-- Loading State --}}
                            <div class="items-center justify-end z-50 text-indigo-900" wire:loading
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
                            </div>
                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 {{ $beneficiarySlots['num_of_beneficiaries'] ? 'text-indigo-800' : 'text-zinc-400' }}"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="beneficiary-search" maxlength="100" autocomplete="off"
                                    @if (!$beneficiarySlots['num_of_beneficiaries']) disabled @endif
                                    @input.debounce.300ms="$wire.searchBeneficiaries = $el.value; $wire.$refresh();"
                                    class="{{ $beneficiarySlots['num_of_beneficiaries']
                                        ? 'text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full "
                                    placeholder="Search for beneficiaries">
                            </div>

                            <button
                                @if ($batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries']) data-modal-target="add-beneficiaries-modal" data-modal-toggle="add-beneficiaries-modal" @click="trapAdd = true" @else disabled @endif
                                class="flex items-center {{ $batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries'] ? 'bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500' : 'bg-indigo-300 text-indigo-50' }} rounded-md px-4 py-1 text-sm font-bold duration-200 ease-in-out">
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

                    @if ($batchId && !$this->beneficiaries->isEmpty())
                        {{-- Beneficiaries Table --}}
                        <div id="beneficiaries-table"
                            class="relative h-[38.5vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
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
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        <tr wire:key="beneficiary-{{ $key }}"
                                            wire:click.prevent="selectBeneficiaryRow({{ $key }}, '{{ encrypt($beneficiary->id) }}')"
                                            class="relative {{ $selectedBeneficiaryRow === $key ? 'bg-gray-200 text-indigo-900 hover:bg-gray-300' : ' hover:bg-gray-50' }} border-b whitespace-nowrap">
                                            <th scope="row"
                                                class="pe-2 border-r border-gray-200 ps-4 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->first_name }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->middle_name ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->last_name }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->extension_name ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ \Carbon\Carbon::parse($beneficiary->birthdate)->format('M d, Y') }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->contact_num }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->sex }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->civil_status }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->age }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->is_senior_citizen }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->is_pwd }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->occupation ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                @if ($beneficiary->avg_monthly_income === null || $beneficiary->avg_monthly_income === 0)
                                                    -
                                                @else
                                                    ₱{{ number_format($beneficiary->avg_monthly_income / 100, 2) }}
                                                @endif
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->e_payment_acc_num ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->beneficiary_type }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->dependent ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->self_employment }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200 capitalize">
                                                {{ $beneficiary->skills_training }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->spouse_first_name ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->spouse_middle_name ?? '-' }}
                                            </td>
                                            <td class="px-2 border-r border-gray-200">
                                                {{ $beneficiary->spouse_last_name ?? '-' }}
                                            </td>
                                            <td class="px-2">
                                                {{ $beneficiary->spouse_extension_name ?? '-' }}
                                            </td>
                                            <td class="py-1">
                                                <button @click.stop=""
                                                    id="beneficiaryRowButton-{{ $key }}"
                                                    class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBeneficiaryRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                        height="400" viewBox="0, 0, 400,400">
                                                        <g>
                                                            <path
                                                                d="M182.813 38.986 C 123.313 52.113,100.226 125.496,141.415 170.564 C 183.488 216.599,261.606 197.040,276.896 136.644 C 291.453 79.146,240.501 26.259,182.813 38.986 M278.141 204.778 C 272.904 206.868,270.880 210.858,270.342 220.156 L 269.922 227.420 264.768 229.218 C 261.934 230.206,258.146 231.841,256.351 232.849 L 253.088 234.684 248.224 229.884 C 241.216 222.970,235.198 221.459,229.626 225.214 C 221.063 230.985,221.157 239.379,229.884 248.224 L 234.684 253.088 232.849 256.351 C 231.841 258.146,230.206 261.934,229.218 264.768 L 227.420 269.922 220.156 270.313 C 208.989 270.915,204.670 274.219,204.083 282.607 C 203.466 291.419,208.211 295.523,219.675 296.094 L 227.526 296.484 228.868 300.781 C 229.606 303.145,231.177 306.971,232.359 309.285 L 234.508 313.492 230.227 317.879 C 223.225 325.054,221.747 330.343,224.976 336.671 C 229.458 345.458,239.052 345.437,248.076 336.622 L 252.794 332.014 258.233 334.683 C 261.224 336.151,265.133 337.742,266.919 338.218 L 270.167 339.083 270.435 346.830 C 270.818 357.905,274.660 362.505,283.514 362.495 C 292.220 362.485,296.084 357.523,296.090 346.344 L 296.094 339.173 300.586 337.882 C 303.057 337.171,306.997 335.559,309.341 334.298 L 313.605 332.006 318.326 336.618 C 324.171 342.328,325.413 342.969,330.613 342.966 C 344.185 342.956,347.496 329.464,336.652 318.359 L 332.075 313.672 334.421 309.022 C 335.711 306.464,337.308 302.509,337.970 300.233 L 339.173 296.094 346.276 296.094 C 357.566 296.094,362.500 292.114,362.500 283.005 C 362.500 274.700,357.650 270.809,346.830 270.435 L 339.083 270.167 338.218 266.919 C 337.742 265.133,336.151 261.224,334.683 258.233 L 332.014 252.794 336.622 248.076 C 345.259 239.234,345.423 230.021,337.028 225.208 C 330.778 221.625,325.473 222.915,318.356 229.749 L 313.432 234.478 309.255 232.344 C 306.958 231.170,303.145 229.606,300.781 228.868 L 296.484 227.526 296.094 219.675 C 295.460 206.941,288.076 200.814,278.141 204.778 M140.625 220.855 C 91.525 226.114,53.906 267.246,53.906 315.674 C 53.906 333.608,63.031 349.447,77.831 357.207 C 88.240 362.664,85.847 362.500,155.113 362.500 L 217.422 362.500 214.329 360.259 C 202.518 351.704,196.602 335.289,200.309 321.365 L 201.381 317.339 196.198 313.914 C 172.048 297.955,174.729 264.426,201.338 249.629 C 201.430 249.578,200.995 247.619,200.371 245.276 C 198.499 238.241,199.126 229.043,201.981 221.680 C 202.483 220.383,151.436 219.698,140.625 220.855 M290.207 252.760 C 316.765 259.678,323.392 292.263,301.575 308.656 C 283.142 322.507,256.557 311.347,252.282 287.964 C 248.462 267.069,269.646 247.405,290.207 252.760 "
                                                                stroke="none" fill="currentColor"
                                                                fill-rule="evenodd">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <tr x-data x-intersect.full.once="$wire.loadMoreBeneficiaries()">
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[38.5vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($this->implementations->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M176.172 0.910 C 75.696 12.252,0.391 97.375,0.391 199.609 C 0.391 257.493,19.900 304.172,60.647 343.781 C 165.736 445.935,343.383 403.113,389.736 264.453 C 436.507 124.544,322.897 -15.653,176.172 0.910 M212.891 24.550 C 335.332 30.161,413.336 167.986,357.068 279.297 C 350.503 292.285,335.210 314.844,332.970 314.844 C 332.663 314.844,321.236 303.663,307.575 289.997 L 282.737 265.149 290.592 261.533 L 298.448 257.917 298.247 199.928 L 298.047 141.938 249.053 119.044 L 200.059 96.150 170.626 109.879 L 141.194 123.608 113.175 95.597 C 97.765 80.191,85.156 67.336,85.156 67.030 C 85.156 65.088,106.255 50.454,118.011 44.241 C 143.055 31.005,179.998 22.077,201.953 23.956 C 203.242 24.066,208.164 24.334,212.891 24.550 M92.437 110.015 L 117.287 134.874 109.420 138.499 L 101.552 142.124 101.753 200.081 L 101.953 258.037 151.001 280.950 L 200.048 303.863 229.427 290.127 L 258.805 276.392 286.825 304.403 C 302.235 319.809,314.844 332.664,314.844 332.970 C 314.844 333.277,312.471 335.418,309.570 337.729 C 221.058 408.247,89.625 377.653,40.837 275.175 C 14.785 220.453,19.507 153.172,52.898 103.328 C 58.263 95.320,66.167 85.156,67.030 85.156 C 67.337 85.156,78.770 96.343,92.437 110.015 M228.883 136.523 C 244.347 143.721,257.004 149.785,257.011 150.000 C 257.063 151.616,200.203 176.682,198.198 175.928 C 194.034 174.360,143.000 150.389,142.998 150.000 C 142.995 149.483,198.546 123.555,199.797 123.489 C 200.330 123.460,213.419 129.326,228.883 136.523 M157.170 183.881 L 187.891 198.231 188.094 234.662 C 188.205 254.700,188.030 271.073,187.703 271.047 C 187.377 271.021,173.398 264.571,156.641 256.713 L 126.172 242.425 125.969 205.978 C 125.857 185.932,125.920 169.531,126.108 169.531 C 126.296 169.531,140.274 175.989,157.170 183.881 M274.031 205.994 L 273.828 242.458 243.359 256.726 C 226.602 264.574,212.623 271.017,212.297 271.044 C 211.970 271.071,211.795 254.704,211.906 234.673 L 212.109 198.252 242.578 183.949 C 259.336 176.083,273.314 169.621,273.641 169.589 C 273.967 169.557,274.143 185.940,274.031 205.994 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try creating a <span class=" text-indigo-900">new project</span>.
                                    </p>
                                @elseif (!$implementationId)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class=" text-indigo-900">projects</span> row.
                                    </p>
                                @elseif ($this->batches->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class=" size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M28.642 13.710 C 17.961 17.627,11.930 27.414,12.661 39.645 C 13.208 48.819,14.371 50.486,34.057 70.324 L 51.512 87.913 45.092 91.335 C 16.276 106.692,12.891 110.231,12.891 125.000 C 12.891 142.347,8.258 138.993,99.219 187.486 C 138.105 208.218,174.754 227.816,180.660 231.039 C 190.053 236.164,192.025 236.948,196.397 237.299 L 201.395 237.701 211.049 247.388 C 221.747 258.122,221.627 257.627,214.063 259.898 C 199.750 264.194,187.275 262.111,169.753 252.500 C 148.071 240.607,28.689 177.141,27.332 176.786 C 24.779 176.118,15.433 186.072,13.702 191.302 C 11.655 197.487,12.276 207.141,15.021 211.791 C 20.209 220.580,17.082 218.698,99.219 262.486 C 138.105 283.218,174.840 302.864,180.851 306.144 L 191.781 312.109 199.601 312.109 C 208.733 312.109,207.312 312.689,234.766 297.765 L 251.953 288.422 260.903 297.306 C 265.825 302.192,269.692 306.315,269.497 306.470 C 267.636 307.938,219.572 333.017,216.016 334.375 C 209.566 336.839,195.517 337.462,188.275 335.607 C 181.558 333.886,183.489 334.878,100.148 290.322 C 17.221 245.988,26.705 249.778,19.140 257.949 C 9.782 268.056,9.995 283.074,19.635 292.854 C 24.062 297.344,26.747 298.850,99.219 337.486 C 138.105 358.218,174.840 377.864,180.851 381.144 L 191.781 387.109 199.647 387.109 C 209.010 387.109,202.356 390.171,259.666 359.492 L 300.974 337.380 324.510 360.767 C 346.368 382.486,348.381 384.279,352.734 385.895 C 365.447 390.614,379.540 385.290,385.303 373.590 C 387.943 368.230,387.927 355.899,385.273 350.781 C 381.586 343.670,52.871 16.129,47.432 14.148 C 42.118 12.211,33.289 12.006,28.642 13.710 M191.323 13.531 C 189.773 14.110,184.675 16.704,179.994 19.297 C 175.314 21.890,160.410 29.898,146.875 37.093 C 133.340 44.288,122.010 50.409,121.698 50.694 C 121.387 50.979,155.190 85.270,196.817 126.895 L 272.503 202.578 322.775 175.800 C 374.066 148.480,375.808 147.484,380.340 142.881 C 391.283 131.769,389.788 113.855,377.098 104.023 C 375.240 102.583,342.103 84.546,303.461 63.941 C 264.819 43.337,227.591 23.434,220.733 19.713 L 208.262 12.948 201.201 12.714 C 196.651 12.563,193.139 12.853,191.323 13.531 M332.061 198.065 C 309.949 209.881,291.587 219.820,291.257 220.150 C 290.927 220.480,297.593 227.668,306.071 236.125 L 321.484 251.500 347.612 237.539 C 383.915 218.142,387.375 214.912,387.466 200.334 C 387.523 191.135,378.828 176.525,373.323 176.571 C 372.741 176.576,354.174 186.248,332.061 198.065 M356.265 260.128 C 347.464 264.822,340.168 268.949,340.052 269.298 C 339.935 269.647,346.680 276.766,355.040 285.118 L 370.240 300.303 372.369 299.175 C 389.241 290.238,392.729 269.941,379.645 256.836 C 373.129 250.309,375.229 250.013,356.265 260.128 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try assigning a <span class=" text-indigo-900">new batch</span>.
                                    </p>
                                @elseif (!$batchId)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                            class=" text-indigo-900">batches</span> row.
                                    </p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No beneficiaries found.</p>
                                    <p>Try adding a <span class=" text-indigo-900">new beneficiary</span>.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif
                    @if ($batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries'])
                        {{-- Add Button | Add Beneficiaries Modal --}}
                        <livewire:focal.implementations.add-beneficiaries-modal :$batchId :key="$batchId" />
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
    }" x-cloak x-show="successShow"
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
            const implementationsTable = document.getElementById('implementations-table');
            if (implementationsTable) {
                implementationsTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
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
            const implementationsTable = document.getElementById('implementations-table');
            if (implementationsTable) {
                implementationsTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
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
    </script>
@endscript
