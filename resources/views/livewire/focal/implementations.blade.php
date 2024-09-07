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
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date end">
                    </div>
                </div>

                {{-- Loading State --}}
                <div class="absolute items-center justify-end z-50 min-h-full min-w-full text-indigo-900"
                    wire:loading.flex
                    wire:target="setStartDate, setEndDate, selectImplementationRow, selectBatchRow, selectBeneficiaryRow, loadMoreImplementations, loadMoreBeneficiaries, updateImplementations, updateBatches">
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
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 {{ $this->implementations->isNotEmpty() ? 'text-indigo-800' : 'text-zinc-400' }}"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="project-search" maxlength="100" autocomplete="off"
                                    @if ($this->implementations->isEmpty()) disabled @endif
                                    @input.debounce.300ms="$wire.searchProjects = $el.value; $wire.$refresh();"
                                    class="{{ $this->implementations->isNotEmpty()
                                        ? 'text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                        : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full"
                                    placeholder="Search for project numbers">
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
                                        @php
                                            $encryptedId = Crypt::encrypt($implementation->id);
                                        @endphp
                                        <tr @if (!$this->beneficiaries->isEmpty()) @click="scrollToTop()" @endif
                                            wire:key="implementation-{{ $key }}"
                                            wire:click.prevent='selectImplementationRow({{ $key }}, "{{ $encryptedId }}")'
                                            class="relative border-b duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'bg-gray-200 text-indigo-900 hover:bg-gray-300' : ' hover:bg-gray-50' }}  whitespace-nowrap cursor-pointer">
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
                                            {{-- Implementation Dropdown --}}
                                            <td x-data="iDropdownRotation({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="implementationRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="implementationRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 outline-none rounded duration-200 ease-in-out {{ $selectedImplementationRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="size-4 transition-transform duration-200 ease-in-out">
                                                        <path fill-rule="evenodd"
                                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @if ($this->implementations->count() > 5 && $loop->last)
                                            <tr x-data x-intersect.full="$wire.loadMoreImplementations();">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Implementation Dropdown Content --}}
                        @foreach ($this->implementations as $key => $implementation)
                            <div wire:key="implementationRowDropdown-{{ $key }}"
                                id="implementationRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="text-sm text-indigo-1100"
                                    aria-labelledby="implementationRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Project') }}"
                                            class="rounded-t-md flex items-center outline-none ring-0 justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M196.484 30.192 C 193.112 30.921,90.341 91.036,88.733 93.221 C 87.153 95.366,87.099 96.484,86.719 134.490 L 86.328 173.554 75.781 177.394 C -33.739 217.272,-22.890 302.011,95.768 333.518 L 98.958 334.365 96.759 344.331 C 92.647 362.961,95.217 369.852,106.250 369.785 C 109.346 369.766,162.422 349.406,171.174 344.880 C 176.624 342.062,178.462 332.569,174.580 327.300 C 171.295 322.843,127.874 279.583,125.926 278.827 C 115.728 274.870,110.733 279.883,106.915 297.906 L 104.297 310.265 101.953 309.811 C 89.267 307.353,63.261 296.301,50.195 287.815 C 7.311 259.963,19.068 225.316,79.297 202.054 L 86.328 199.339 86.719 210.412 C 86.956 217.124,87.493 222.155,88.084 223.188 C 90.857 228.032,194.289 287.103,200.000 287.103 C 203.857 287.104,294.205 236.696,308.801 226.401 C 312.664 223.676,313.281 221.391,313.281 209.815 L 313.281 199.241 317.773 200.904 C 419.850 238.702,380.629 302.630,244.766 319.905 C 229.129 321.893,227.054 322.693,224.706 327.642 C 221.919 333.513,223.930 340.377,229.378 343.594 C 237.632 348.466,295.155 337.825,328.450 325.266 C 426.552 288.262,424.383 213.945,324.134 177.371 L 313.672 173.554 313.281 134.490 C 312.840 90.412,313.185 92.724,306.590 89.523 C 304.899 88.702,282.070 75.661,255.859 60.541 C 197.763 27.030,201.731 29.058,196.484 30.192 M237.634 78.266 C 258.615 90.375,275.773 100.482,275.763 100.727 C 275.732 101.488,201.314 144.085,200.016 144.085 C 196.374 144.085,123.224 100.464,124.947 99.320 C 127.149 97.858,198.718 56.371,199.158 56.302 C 199.339 56.273,216.653 66.157,237.634 78.266 M149.688 143.951 L 187.891 166.027 188.092 209.967 C 188.203 234.134,188.063 253.906,187.780 253.906 C 187.497 253.906,170.092 243.986,149.102 231.860 L 110.938 209.814 110.938 165.845 C 110.938 141.661,111.061 121.875,111.211 121.875 C 111.362 121.875,128.676 131.809,149.688 143.951 M289.063 165.858 L 289.063 209.842 250.888 231.874 C 229.893 243.992,212.487 253.906,212.210 253.906 C 211.933 253.906,211.797 234.134,211.908 209.967 L 212.109 166.028 250.353 143.952 C 271.387 131.810,288.701 121.875,288.829 121.875 C 288.958 121.875,289.063 141.668,289.063 165.858 M142.969 329.731 C 142.969 330.434,124.202 337.744,123.696 337.238 C 123.329 336.871,126.469 320.979,127.870 316.114 C 128.254 314.783,142.969 328.053,142.969 329.731 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            View Project
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Modify Project') }}"
                                            class="rounded-b-md flex items-center outline-none ring-0 justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M73.654 21.493 C 57.244 25.059,43.944 37.290,38.832 53.516 L 37.109 58.984 37.109 200.000 L 37.109 341.016 38.832 346.484 C 44.001 362.890,57.287 374.996,74.011 378.540 C 78.301 379.449,89.313 379.663,132.452 379.674 L 185.606 379.687 189.089 377.930 C 197.816 373.525,198.464 361.986,190.325 355.908 C 188.207 354.326,187.188 354.290,133.342 353.906 C 71.185 353.463,74.722 353.845,68.479 346.914 C 62.361 340.123,62.891 354.043,62.891 200.000 L 62.891 62.109 64.687 58.462 C 65.675 56.456,68.015 53.403,69.887 51.678 C 76.184 45.873,72.988 46.094,150.881 46.094 L 220.229 46.094 220.466 76.777 L 220.703 107.459 223.261 112.653 C 230.648 127.651,235.336 129.222,273.633 129.529 L 303.906 129.772 303.906 158.334 C 303.906 185.979,303.959 186.991,305.561 189.841 C 310.258 198.199,323.311 198.051,327.941 189.587 C 329.656 186.454,330.450 126.522,328.885 118.359 C 327.048 108.771,326.294 107.889,283.368 65.114 C 241.714 23.606,241.100 23.073,232.650 21.124 C 227.078 19.839,79.693 20.181,73.654 21.493 M267.077 103.726 C 243.712 103.997,246.094 106.362,246.094 82.893 L 246.094 64.465 265.632 83.990 L 285.170 103.516 267.077 103.726 M96.971 103.891 C 86.128 106.520,83.634 120.880,92.931 127.146 L 95.544 128.906 137.434 128.906 L 179.325 128.906 182.129 127.001 C 189.779 121.802,189.297 109.849,181.247 105.120 C 178.324 103.403,103.484 102.312,96.971 103.891 M92.931 164.260 C 85.267 169.425,85.267 180.575,92.931 185.740 L 95.544 187.500 174.920 187.497 C 252.748 187.494,254.347 187.464,256.843 185.942 C 264.551 181.242,264.551 168.758,256.843 164.058 C 254.347 162.536,252.748 162.506,174.920 162.503 L 95.544 162.500 92.931 164.260 M264.844 204.939 C 256.120 206.628,254.875 207.872,249.987 219.775 C 245.390 230.969,243.780 231.875,231.016 230.453 C 216.581 228.844,213.893 230.325,204.750 244.928 C 193.227 263.331,192.908 269.027,202.697 281.565 C 209.871 290.751,209.841 292.493,202.353 302.168 C 192.905 314.377,193.082 318.102,204.018 337.215 C 212.564 352.152,217.046 354.770,230.895 352.910 L 239.989 351.689 243.389 353.742 C 246.413 355.569,247.106 356.574,249.673 362.859 C 255.097 376.137,256.621 377.371,269.563 378.962 C 292.950 381.838,302.199 378.265,308.140 364.063 C 311.296 356.520,311.854 355.692,315.086 353.778 L 318.601 351.696 326.683 352.852 C 342.157 355.064,348.387 350.573,358.197 330.132 C 364.864 316.242,364.674 313.537,356.268 302.585 C 348.565 292.550,348.587 290.936,356.569 280.362 C 365.509 268.520,364.633 261.091,352.126 242.653 C 343.300 229.642,340.951 228.575,325.618 230.617 C 315.060 232.023,312.874 230.627,308.393 219.621 C 304.614 210.342,301.807 206.940,296.772 205.535 C 291.765 204.138,270.986 203.750,264.844 204.939 M94.141 221.871 C 85.580 226.138,84.599 238.340,92.371 243.884 L 94.922 245.703 129.044 245.703 C 162.289 245.703,163.222 245.662,165.324 244.092 C 173.464 238.015,172.816 226.475,164.089 222.070 C 158.952 219.477,99.285 219.307,94.141 221.871 M287.291 236.719 C 290.896 245.459,292.223 247.189,297.090 249.497 C 299.335 250.562,302.500 252.337,304.123 253.442 C 311.171 258.239,313.421 258.547,326.953 256.566 C 330.062 256.110,330.091 256.130,332.733 260.509 L 335.387 264.910 332.946 268.197 C 331.604 270.005,329.236 273.290,327.684 275.497 L 324.862 279.511 324.805 290.732 C 324.738 304.126,324.764 304.223,330.624 311.922 L 335.239 317.985 332.716 322.720 C 330.609 326.672,329.925 327.379,328.573 327.004 C 327.682 326.756,323.262 326.337,318.750 326.073 L 310.547 325.593 306.589 328.335 C 304.413 329.843,300.722 332.034,298.386 333.203 C 292.195 336.302,291.065 337.646,287.461 346.186 L 284.203 353.906 279.168 353.906 L 274.132 353.906 272.441 349.805 C 267.996 339.021,266.240 336.445,261.719 334.082 C 259.355 332.846,255.208 330.421,252.502 328.693 L 247.583 325.550 239.612 326.039 C 235.228 326.307,230.892 326.741,229.978 327.002 C 228.590 327.398,227.913 326.772,225.876 323.213 C 222.786 317.811,222.686 318.423,227.718 311.946 C 233.417 304.611,233.594 303.984,233.591 291.160 C 233.588 278.746,233.645 278.923,226.665 270.030 L 223.070 265.451 225.476 261.046 C 227.717 256.944,228.066 256.651,230.543 256.788 C 246.958 257.696,247.881 257.603,252.459 254.573 C 254.826 253.007,258.535 250.827,260.701 249.729 C 265.917 247.086,267.839 244.636,271.101 236.470 L 273.828 229.645 279.190 229.862 L 284.551 230.078 287.291 236.719 M271.892 271.841 C 258.218 276.561,253.528 294.319,263.025 305.414 C 270.970 314.695,287.426 314.994,295.024 305.994 C 309.248 289.146,292.669 264.669,271.892 271.841 M93.913 280.196 C 84.324 284.971,85.045 299.439,95.080 303.632 C 100.082 305.722,159.760 305.115,164.089 302.930 C 172.816 298.525,173.464 286.986,165.325 280.908 C 162.300 278.648,98.303 278.010,93.913 280.196 "
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
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[36vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-gray-400"
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

                    {{-- Create Button | Main Modal --}}
                    <livewire:focal.implementations.create-project-modal />
                </div>

                {{-- Batch Assignments --}}
                <div class="relative lg:col-span-2 h-full w-full rounded bg-white shadow">
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
                                @if ($implementationId) data-modal-target="assign-batches-modal" data-modal-toggle="assign-batches-modal" @endif
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

                    @if ($this->batches->isNotEmpty())
                        {{-- Table --}}
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
                                        @php
                                            $encryptedId = encrypt($batch->id);
                                        @endphp
                                        <tr @if (!$this->beneficiaries->isEmpty()) @click="scrollToTop()" @endif
                                            wire:click='selectBatchRow({{ $key }}, "{{ $encryptedId }}")'
                                            wire:key='batch-{{ $key }}'
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
                                            <td x-data="bDropdownRotation({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="batchRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="batchRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBatchRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="size-4 transition-transform duration-200 ease-in-out">
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
                        @foreach ($this->batches as $key => $batch)
                            <div wire:key="batchRowDropdown-{{ $key }}"
                                id="batchRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="text-sm text-indigo-1100"
                                    aria-labelledby="batchRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Batch') }}"
                                            class="rounded-t-md flex items-center justify-start px-4 py-2 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M71.617 21.558 C 51.223 25.243,33.333 39.869,25.137 59.558 C 20.097 71.665,20.245 67.317,20.491 195.786 L 20.714 312.109 22.857 319.922 C 30.573 348.061,51.916 369.406,80.078 377.149 L 87.891 379.297 200.000 379.297 L 312.109 379.297 319.922 377.149 C 347.610 369.536,370.049 347.384,376.651 321.146 C 380.109 307.402,375.034 296.156,362.625 290.062 L 358.203 287.891 335.352 287.652 L 312.500 287.413 312.495 181.011 C 312.489 78.631,312.432 74.373,310.983 68.359 C 304.999 43.526,284.308 24.729,258.984 21.122 C 249.697 19.800,79.154 20.196,71.617 21.558 M262.109 48.353 C 276.330 54.023,285.054 65.152,286.734 79.771 C 287.180 83.651,287.491 127.384,287.494 186.894 L 287.500 287.460 231.055 287.675 L 174.609 287.891 170.112 290.170 C 161.307 294.631,155.585 302.296,151.400 315.234 C 134.637 367.059,63.663 365.987,47.940 313.672 C 46.209 307.911,45.235 87.071,46.898 77.344 C 49.379 62.839,60.755 50.750,75.272 47.193 C 78.410 46.424,98.911 46.238,168.359 46.346 L 257.422 46.484 262.109 48.353 M162.109 105.373 C 127.997 113.804,110.480 151.846,126.319 183.103 L 128.267 186.948 116.952 198.357 C 102.863 212.562,101.566 215.259,105.275 222.641 C 110.919 233.874,119.603 232.151,135.206 216.701 L 146.584 205.435 151.823 207.810 C 177.370 219.394,209.084 209.427,222.248 185.677 C 245.561 143.617,208.360 93.942,162.109 105.373 M186.888 132.332 C 199.280 138.248,206.059 152.104,202.795 164.846 C 195.322 194.018,154.678 194.018,147.205 164.846 C 141.415 142.246,165.802 122.265,186.888 132.332 M350.728 313.232 C 352.139 314.125,351.052 318.154,347.660 324.602 C 340.723 337.790,328.575 347.600,313.672 352.051 C 309.110 353.413,159.375 354.864,159.375 353.546 C 159.375 353.319,161.045 351.060,163.086 348.527 C 168.055 342.360,172.861 333.154,175.786 324.203 C 178.237 316.704,179.647 314.374,182.422 313.239 C 184.593 312.350,349.324 312.343,350.728 313.232 "
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
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 pe-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M176.172 0.866 C 39.197 17.293,-40.484 164.748,20.497 288.953 C 29.087 306.450,36.003 310.571,44.614 303.326 C 50.749 298.164,50.555 294.541,43.372 280.107 C 9.509 212.062,20.775 133.717,72.283 79.059 C 129.590 18.247,226.155 6.192,294.257 51.347 L 298.705 54.297 286.266 54.688 C 274.832 55.047,273.622 55.225,271.277 56.898 C 264.072 62.037,264.854 73.610,272.672 77.538 C 275.121 78.768,278.406 78.904,305.753 78.905 C 348.827 78.907,345.313 82.418,345.313 39.372 C 345.313 2.944,344.407 -0.003,333.215 0.001 C 323.356 0.005,321.573 3.447,321.094 23.404 L 320.703 39.668 311.097 33.250 C 272.687 7.585,221.862 -4.614,176.172 0.866 M358.203 94.853 C 353.887 96.771,350.781 101.466,350.781 106.070 C 350.781 107.205,353.576 113.774,356.992 120.668 C 394.427 196.219,374.965 285.427,309.680 337.540 C 251.787 383.752,167.820 388.701,107.031 349.483 L 101.172 345.703 113.672 345.313 C 125.171 344.953,126.376 344.776,128.723 343.102 C 135.928 337.963,135.146 326.390,127.328 322.462 C 124.879 321.232,121.594 321.096,94.247 321.095 C 51.173 321.093,54.688 317.582,54.688 360.628 C 54.688 397.056,55.593 400.003,66.785 399.999 C 76.648 399.995,78.427 396.556,78.906 376.572 L 79.297 360.284 89.755 367.271 C 152.885 409.449,238.727 411.239,303.483 371.727 C 391.984 317.728,425.313 205.636,380.445 112.891 C 371.637 94.684,367.016 90.936,358.203 94.853 M170.032 96.014 C 165.534 98.980,164.855 101.011,164.849 111.512 L 164.844 121.071 160.352 123.352 C 157.881 124.607,154.503 126.561,152.845 127.695 L 149.830 129.756 142.025 125.337 C 126.701 116.660,125.282 117.574,108.173 147.141 L 95.135 169.672 95.469 174.290 C 95.889 180.096,97.719 182.169,107.227 187.611 L 114.063 191.524 114.063 199.986 L 114.063 208.449 106.445 212.873 C 97.369 218.145,95.890 219.889,95.461 225.826 L 95.141 230.239 107.706 252.063 C 125.206 282.458,126.617 283.387,142.025 274.663 L 149.830 270.244 152.845 272.305 C 154.503 273.439,157.881 275.393,160.352 276.648 L 164.844 278.929 164.849 288.488 C 164.858 305.753,164.248 305.458,200.000 305.458 C 235.752 305.458,235.142 305.753,235.151 288.488 L 235.156 278.929 239.648 276.648 C 242.119 275.393,245.497 273.439,247.155 272.305 L 250.170 270.244 257.975 274.663 C 273.373 283.381,274.750 282.476,292.274 252.122 L 304.864 230.314 304.531 225.703 C 304.111 219.905,302.278 217.829,292.773 212.389 L 285.938 208.476 285.938 200.000 L 285.938 191.524 292.773 187.611 C 302.281 182.169,304.111 180.096,304.531 174.290 L 304.865 169.672 291.827 147.141 C 274.718 117.574,273.299 116.660,257.975 125.337 L 250.170 129.756 247.155 127.695 C 245.497 126.561,242.119 124.607,239.648 123.352 L 235.156 121.071 235.151 111.512 C 235.145 101.011,234.466 98.980,229.968 96.014 C 226.530 93.748,173.470 93.748,170.032 96.014 M211.719 126.170 L 211.719 134.370 214.544 137.498 C 216.098 139.218,217.727 140.625,218.165 140.625 C 220.291 140.625,233.858 147.960,238.934 151.854 C 246.536 157.686,250.435 157.936,258.780 153.125 C 261.761 151.406,264.439 150.000,264.732 150.000 C 265.290 150.000,275.781 167.696,275.781 168.638 C 275.781 168.937,273.128 170.755,269.885 172.677 C 261.029 177.926,261.396 176.775,261.290 199.609 C 261.182 222.745,260.970 222.060,269.871 227.323 C 273.122 229.245,275.781 231.063,275.781 231.362 C 275.781 232.304,265.290 250.000,264.732 250.000 C 264.439 250.000,261.761 248.594,258.780 246.875 C 250.435 242.064,246.536 242.314,238.934 248.146 C 234.038 251.902,220.418 259.320,218.269 259.401 C 217.271 259.439,214.620 261.801,213.062 264.041 C 212.004 265.562,211.719 267.680,211.719 274.002 L 211.719 282.031 200.000 282.031 L 188.281 282.031 188.281 273.830 L 188.281 265.630 185.456 262.502 C 183.902 260.782,182.273 259.375,181.835 259.375 C 179.709 259.375,166.142 252.040,161.066 248.146 C 153.464 242.314,149.565 242.064,141.220 246.875 C 138.239 248.594,135.551 250.000,135.247 250.000 C 134.722 250.000,124.845 233.288,124.397 231.641 C 124.280 231.211,126.844 229.294,130.096 227.380 C 139.022 222.125,138.818 222.784,138.710 199.609 C 138.604 176.748,138.951 177.833,130.063 172.601 C 126.830 170.698,124.280 168.789,124.397 168.359 C 124.845 166.712,134.722 150.000,135.247 150.000 C 135.551 150.000,138.239 151.406,141.220 153.125 C 149.565 157.936,153.464 157.686,161.066 151.854 C 166.142 147.960,179.709 140.625,181.835 140.625 C 182.273 140.625,183.902 139.218,185.456 137.498 L 188.281 134.370 188.281 126.170 L 188.281 117.969 200.000 117.969 L 211.719 117.969 211.719 126.170 M186.674 158.818 C 140.355 174.609,150.935 242.824,199.722 242.949 C 249.787 243.076,259.825 172.556,211.772 158.291 C 205.287 156.366,193.118 156.621,186.674 158.818 M208.039 182.901 C 225.967 191.898,220.095 218.750,200.199 218.750 C 179.935 218.750,173.728 192.293,191.797 182.937 C 196.134 180.691,203.603 180.675,208.039 182.901 "
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
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[36vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($this->implementations->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-gray-400"
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
                                        class="size-12 sm:size-20 mb-4 text-gray-400"
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
                                        class=" size-12 sm:size-20 mb-4 text-gray-400"
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
                        <livewire:focal.implementations.assign-batches-modal :$implementationId />
                    @endif
                </div>

                {{-- List of Beneficiaries by Batch --}}
                <div class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">
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
                                @if ($batchId && $beneficiarySlots['batch_slots_allocated'] > $beneficiarySlots['num_of_beneficiaries']) data-modal-target="add-beneficiaries-modal" data-modal-toggle="add-beneficiaries-modal" @else disabled @endif
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

                    @if (!$this->beneficiaries->isEmpty())
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
                                        @php
                                            $encryptedId = Crypt::encrypt($beneficiary->id);
                                        @endphp
                                        <tr wire:click.prevent="selectBeneficiaryRow({{ $key }}, '{{ $encryptedId }}')"
                                            wire:key="beneficiary-{{ $key }}"
                                            class="relative {{ $selectedBeneficiaryRow === $key ? 'bg-gray-200 text-indigo-900 hover:bg-gray-300' : ' hover:bg-gray-50' }} border-b whitespace-nowrap">
                                            <th scope="row"
                                                class="pe-2 border-r border-gray-200 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap ">
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
                                                {{ $beneficiary->birthdate }}
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
                                                    {{ number_format($beneficiary->avg_monthly_income / 100, 2) }}
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
                                            <td x-data="bfDropdownRotation({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="beneficiaryRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="beneficiaryRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBeneficiaryRow === $key ? 'hover:bg-indigo-700 focus:bg-indigo-700 text-indigo-900 hover:text-indigo-50 focus:text-indigo-50' : 'text-gray-900 hover:text-indigo-900 focus:text-indigo-900 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="size-4 transition-transform duration-200 ease-in-">
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
                        @foreach ($this->beneficiaries as $key => $beneficiary)
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
                                                        d="M183.594 67.232 C 118.911 75.230,67.777 118.661,56.355 175.303 L 54.919 182.422 46.014 182.863 C 31.007 183.605,25.000 188.504,25.000 200.000 C 25.000 213.667,31.401 217.188,56.250 217.188 C 83.154 217.188,86.648 214.744,88.259 194.809 C 95.262 108.120,212.967 69.071,283.150 130.154 C 340.998 180.501,319.330 267.240,242.487 292.932 C 191.481 309.986,136.258 293.537,104.910 251.953 C 94.653 238.347,80.945 237.395,73.685 249.784 C 68.091 259.329,73.250 270.777,91.359 288.999 C 147.671 345.664,245.068 348.740,305.660 295.766 C 361.204 247.206,365.234 166.304,314.765 112.976 C 283.195 79.619,230.903 61.382,183.594 67.232 M190.234 133.625 C 145.523 140.584,120.191 188.111,139.487 228.837 C 163.192 278.870,235.364 279.615,259.825 230.078 C 283.800 181.524,243.285 125.369,190.234 133.625 M211.719 169.417 C 236.030 178.842,240.075 211.874,218.743 226.777 C 200.407 239.587,175.174 230.700,168.620 209.123 C 160.882 183.649,186.812 159.760,211.719 169.417 "
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
                                                        d="M182.813 38.986 C 123.304 52.116,100.219 125.489,141.415 170.564 C 183.488 216.599,261.606 197.040,276.896 136.644 C 291.450 79.156,240.491 26.261,182.813 38.986 M212.500 64.552 C 245.234 71.790,263.087 109.776,248.069 140.234 C 230.774 175.309,182.319 180.675,158.180 150.189 C 126.590 110.293,162.602 53.520,212.500 64.552 M278.141 204.778 C 272.904 206.868,270.880 210.858,270.342 220.156 L 269.922 227.420 264.768 229.218 C 261.934 230.206,258.146 231.841,256.351 232.849 L 253.088 234.684 248.224 229.884 C 241.216 222.970,235.198 221.459,229.626 225.214 C 221.063 230.985,221.157 239.379,229.884 248.224 L 234.684 253.088 232.849 256.351 C 231.841 258.146,230.206 261.934,229.218 264.768 L 227.420 269.922 220.156 270.313 C 208.989 270.915,204.670 274.219,204.083 282.607 C 203.466 291.419,208.211 295.523,219.675 296.094 L 227.526 296.484 228.868 300.781 C 229.606 303.145,231.177 306.971,232.359 309.285 L 234.508 313.492 230.227 317.879 C 223.225 325.054,221.747 330.343,224.976 336.671 C 229.458 345.458,239.052 345.437,248.076 336.622 L 252.794 332.014 258.233 334.683 C 261.224 336.151,265.133 337.742,266.919 338.218 L 270.167 339.083 270.435 346.830 C 270.818 357.905,274.660 362.505,283.514 362.495 C 292.220 362.485,296.084 357.523,296.090 346.344 L 296.094 339.173 300.586 337.882 C 303.057 337.171,306.997 335.559,309.341 334.298 L 313.605 332.006 318.326 336.618 C 324.171 342.328,325.413 342.969,330.613 342.966 C 344.185 342.956,347.496 329.464,336.652 318.359 L 332.075 313.672 334.421 309.022 C 335.711 306.464,337.308 302.509,337.970 300.233 L 339.173 296.094 346.276 296.094 C 357.566 296.094,362.500 292.114,362.500 283.005 C 362.500 274.700,357.650 270.809,346.830 270.435 L 339.083 270.167 338.218 266.919 C 337.742 265.133,336.151 261.224,334.683 258.233 L 332.014 252.794 336.622 248.076 C 345.259 239.234,345.423 230.021,337.028 225.208 C 330.778 221.625,325.473 222.915,318.356 229.749 L 313.432 234.478 309.255 232.344 C 306.958 231.170,303.145 229.606,300.781 228.868 L 296.484 227.526 296.094 219.675 C 295.460 206.941,288.076 200.814,278.141 204.778 M140.625 220.855 C 91.471 226.119,53.930 267.194,53.909 315.732 C 53.900 337.690,67.576 355.837,88.281 361.339 C 91.824 362.281,100.892 362.483,139.924 362.491 L 187.269 362.500 189.881 360.740 C 197.235 355.784,197.489 344.842,190.374 339.535 C 188.205 337.918,187.409 337.884,141.936 337.500 C 90.142 337.062,92.090 337.282,86.005 331.198 C 69.936 315.128,85.943 272.866,114.453 256.087 C 128.176 248.012,138.284 246.094,167.128 246.094 C 186.784 246.094,187.332 246.051,189.905 244.317 C 198.662 238.416,198.212 226.754,189.047 222.070 C 185.750 220.386,152.743 219.558,140.625 220.855 M288.370 252.348 C 311.468 256.659,322.203 282.761,308.657 301.677 C 292.473 324.278,257.359 315.737,252.282 287.964 C 248.611 267.884,268.172 248.578,288.370 252.348 "
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
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[38.5vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                @if ($this->implementations->isEmpty())
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-gray-400"
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
                                        class="size-12 sm:size-20 mb-4 text-gray-400"
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
                                        class=" size-12 sm:size-20 mb-4 text-gray-400"
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
                                        class="size-12 sm:size-20 mb-4 text-gray-400"
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
                                        class="size-12 sm:size-20 mb-4 text-gray-400"
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
