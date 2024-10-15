<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
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
                    wire:target="setStartDate, setEndDate, selectImplementationRow, selectBatchRow, selectBeneficiaryRow, loadMoreImplementations, loadMoreBeneficiaries, saveProject, editProject, deleteProject, viewProject, saveBatches, editBatch, deleteBatch, viewBatch, saveBeneficiaries, editBeneficiary, deleteBeneficiary, archiveBeneficiary, viewBeneficiary">
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

                            <div class="relative me-2">

                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $this->implementations->isNotEmpty() || $searchProjects ? 'text-indigo-800' : 'text-zinc-400' }}">

                                    {{-- Loading Icon --}}
                                    <svg class="size-4 animate-spin" wire:loading wire:target="searchProjects"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Search Icon --}}
                                    <svg class="size-3" wire:loading.remove wire:target="searchProjects"
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
                                        <th scope="col" class="absolute h-full w-1 left-0 z-50">
                                            {{-- Selected Row Indicator --}}
                                        </th>
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
                                            wire:loading.class="pointer-events-none"
                                            wire:target="selectImplementationRow"
                                            wire:click.prevent='selectImplementationRow({{ $key }}, "{{ encrypt($implementation->id) }}")'
                                            class="relative border-b duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'bg-gray-200 text-indigo-900 hover:bg-gray-300' : ' hover:bg-gray-50' }} whitespace-nowrap cursor-pointer">
                                            <td class="absolute h-full w-1 left-0 z-50"
                                                :class="{
                                                    'bg-indigo-700': {{ json_encode($selectedImplementationRow === $key) }},
                                                    '': {{ json_encode($selectedImplementationRow !== $key) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
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
                    <livewire:focal.implementations.create-project-modal />

                    {{-- View Project Modal --}}
                    <livewire:focal.implementations.view-project :$passedProjectId />

                </div>

                {{-- List of Batches --}}
                <div x-data="{ assignBatchesModal: $wire.entangle('assignBatchesModal'), viewBatchModal: $wire.entangle('viewBatchModal') }" class="relative lg:col-span-2 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
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
                                <p class="text-xs text-indigo-1100 capitalize font-light me-1">unallocated slots:</p>
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
                                        <th scope="col" class="absolute h-full w-1 left-0 z-50">
                                            {{-- Selected Row Indicator --}}
                                        </th>
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
                                            wire:loading.class="pointer-events-none" wire:target="selectBatchRow"
                                            wire:click='selectBatchRow({{ $key }}, "{{ encrypt($batch->id) }}")'
                                            class="relative border-b whitespace-nowrap duration-200 ease-in-out cursor-pointer {{ $selectedBatchRow === $key ? 'bg-gray-100 text-indigo-900 hover:bg-gray-200' : ' hover:bg-gray-50' }}">
                                            <td class="absolute h-full w-1 left-0 z-50"
                                                :class="{
                                                    'bg-indigo-700': {{ json_encode($selectedBatchRow === $key) }},
                                                    '': {{ json_encode($selectedBatchRow !== $key) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            <th scope="row" class="z-0 ps-4 py-2 font-medium">
                                                {{ $batch->barangay_name }}
                                            </th>
                                            <td class="px-2 py-2 text-center">
                                                {{ $batch->current_slots . ' / ' . $batch->slots_allocated }}
                                            </td>
                                            <td class="py-2 text-center">
                                                <span
                                                    class="px-3 py-1 text-xs rounded-full font-semibold uppercase {{ $batch->approval_status === 'approved' ? 'bg-green-300 text-green-1000' : 'bg-amber-300 text-amber-900' }}">
                                                    {{ $batch->approval_status }}
                                                </span>
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

                    {{-- Assign Batches Modal --}}
                    <livewire:focal.implementations.assign-batches-modal :$implementationId />

                    {{-- View Batch Modal --}}
                    <livewire:focal.implementations.view-batch :$passedBatchId />
                </div>

                {{-- List of Beneficiaries --}}
                <div x-data="{ addBeneficiariesModal: $wire.entangle('addBeneficiariesModal'), viewBeneficiaryModal: $wire.entangle('viewBeneficiaryModal'), importFileModal: $wire.entangle('importFileModal') }" class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
                        <div class="inline-flex items-center my-2 col-span-1 text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
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
                            <div class="items-center justify-end z-50 text-indigo-900">

                            </div>

                            {{-- Search Box --}}
                            <div class="relative me-2">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $beneficiarySlots['num_of_beneficiaries'] ? 'text-indigo-800' : 'text-zinc-400' }}">

                                    {{-- Loading Icon --}}
                                    <svg class="size-4 animate-spin" wire:loading wire:target="searchBeneficiaries"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Search Icon --}}
                                    <svg class="size-3" wire:loading.remove wire:target="searchBeneficiaries"
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
                                @if ($batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries']) @click="importFileModal = !importFileModal;" @else disabled @endif
                                class="flex items-center {{ $batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries'] ? 'bg-indigo-900 hover:bg-indigo-800 text-indigo-50 hover:text-indigo-100 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500' : 'bg-indigo-300 text-indigo-50' }} rounded-md px-4 py-1 me-2 text-sm font-bold duration-200 ease-in-out">
                                IMPORT
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ml-2"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M88.662 38.905 C 77.836 42.649,67.355 52.603,65.200 61.185 L 64.674 63.281 200.306 63.281 C 299.168 63.281,335.938 63.046,335.937 62.414 C 335.937 55.417,322.420 42.307,311.832 39.034 C 304.555 36.786,95.142 36.664,88.662 38.905 M38.263 89.278 C 24.107 94.105,14.410 105.801,12.526 120.321 C 11.517 128.096,11.508 322.580,12.516 330.469 C 14.429 345.442,25.707 358.293,40.262 362.084 C 47.253 363.905,353.543 363.901,360.535 362.080 C 373.149 358.794,383.672 348.107,387.146 335.054 C 388.888 328.512,388.825 121.947,387.080 115.246 C 383.906 103.062,374.023 92.802,361.832 89.034 C 356.966 87.531,353.736 87.500,200.113 87.520 L 43.359 87.540 38.263 89.278 M206.688 139.873 C 212.751 143.620,212.500 140.621,212.500 209.231 C 212.500 242.826,212.767 270.313,213.093 270.313 C 213.420 270.313,220.714 263.272,229.304 254.667 C 248.566 235.371,251.875 233.906,259.339 241.370 C 267.556 249.587,267.098 250.354,234.514 283.031 C 204.767 312.862,204.216 313.301,197.927 312.154 C 194.787 311.582,142.095 260.408,139.398 255.312 C 136.012 248.916,140.354 240.015,147.563 238.573 C 153.629 237.360,154.856 238.189,171.509 254.750 C 180.116 263.309,187.411 270.313,187.720 270.313 C 188.029 270.313,188.281 242.680,188.281 208.907 C 188.281 140.478,188.004 144.025,193.652 140.187 C 197.275 137.725,202.990 137.588,206.688 139.873 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>

                            <button
                                @if ($batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries']) @click="addBeneficiariesModal = !addBeneficiariesModal;" @else disabled @endif
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
                                        <th scope="col" class="absolute h-full w-1 left-0 z-50">
                                            {{-- Selected Row Indicator --}}
                                        </th>
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
                                            type of id
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            id number
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
                                            senior citizen
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            pwd
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
                                        <tr wire:key="beneficiary-{{ $key }}" {{-- wire:loading.class="pointer-events-none"
                                            wire:target="selectBeneficiaryRow" --}}
                                            @click="$wire.selectBeneficiaryRow({{ $key }}, '{{ encrypt($beneficiary->id) }}');"
                                            @dblClick="$wire.viewBeneficiary('{{ encrypt($beneficiary->id) }}');"
                                            class="relative border-b divide-x whitespace-nowrap cursor-pointer"
                                            :class="{
                                                'bg-gray-200 text-indigo-900 hover:bg-gray-300': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && $selectedBeneficiaryRow === $key) }},
                                                'hover:bg-gray-50': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && $selectedBeneficiaryRow !== $key) }},
                                                'bg-amber-200 text-amber-900 hover:bg-amber-300': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow === $key) }},
                                                'bg-amber-100 text-amber-700 hover:bg-amber-200': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow !== $key) }},
                                            
                                            }">
                                            <td class="absolute h-full w-1 left-0 z-50"
                                                :class="{
                                                    'bg-indigo-700': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && $selectedBeneficiaryRow === $key) }},
                                                    '': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && $selectedBeneficiaryRow !== $key) }},
                                                    'bg-amber-700': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow === $key) }},
                                                    '': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow !== $key) }},
                                                }">
                                                {{-- Selected Row Indicator --}}
                                            </td>
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="px-2 ">
                                                {{ $beneficiary->first_name }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->middle_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->last_name }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->extension_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ \Carbon\Carbon::parse($beneficiary->birthdate)->format('M d, Y') }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->contact_num }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->type_of_id }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->id_number }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->sex }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->civil_status }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->age }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->is_senior_citizen }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->is_pwd }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->occupation ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ !is_null($beneficiary->avg_monthly_income) || intval($beneficiary->avg_monthly_income) !== 0 ? '₱' . \App\Services\MoneyFormat::mask(intval($beneficiary->avg_monthly_income)) : '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->e_payment_acc_num ?? '-' }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->beneficiary_type }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->dependent ?? '-' }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->self_employment }}
                                            </td>
                                            <td class="px-2  capitalize">
                                                {{ $beneficiary->skills_training ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->spouse_first_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->spouse_middle_name ?? '-' }}
                                            </td>
                                            <td class="px-2 ">
                                                {{ $beneficiary->spouse_last_name ?? '-' }}
                                            </td>
                                            <td class="px-2">
                                                {{ $beneficiary->spouse_extension_name ?? '-' }}
                                            </td>
                                            <td class="py-1">
                                                <button type="button"
                                                    @click.stop="$wire.viewBeneficiary('{{ encrypt($beneficiary->id) }}');"
                                                    id="beneficiaryRowButton-{{ $key }}"
                                                    class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out"
                                                    :class="{
                                                        'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && $selectedBeneficiaryRow === $key) }},
                                                        'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300': {{ json_encode($beneficiary->beneficiary_type === 'underemployed' && $selectedBeneficiaryRow !== $key) }},
                                                        'hover:bg-amber-700 focus:bg-amber-700 text-amber-900 hover:text-amber-50 focus:text-amber-50': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow === $key) }},
                                                        'text-amber-700 hover:text-amber-900 focus:text-amber-900 hover:bg-amber-300 focus:bg-amber-300': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow !== $key) }},
                                                    
                                                    }">
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

                    {{-- $batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries'] --}}

                    {{-- Add Beneficiaries Modal --}}
                    <livewire:focal.implementations.add-beneficiaries-modal :$batchId />

                    {{-- View Beneficiaries Modal --}}
                    <livewire:focal.implementations.view-beneficiary :$passedBeneficiaryId />

                    {{-- Import File Modal --}}
                    <livewire:focal.implementations.import-file-modal :$batchId />
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
