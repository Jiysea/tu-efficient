<x-slot:favicons>
    <x-c-favicons />
</x-slot>

<div x-cloak x-data="{ open: true, show: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <livewire:sidebar.coordinator-bar />

    <div x-data="{ scrollToTop() { document.getElementById('batches-table').scrollTo({ top: 0, behavior: 'smooth' }); } }" :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">
            {{-- Submissions Header --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Submissions</h1>

                {{-- Date Range picker --}}
                <div id="implementations-date-range" date-rangepicker datepicker-autohide class="flex items-center">

                    {{-- Start --}}
                    <div class="relative">
                        <div
                            class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
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
                            class="bg-white border border-blue-300 text-blue-1100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10"
                            placeholder="Select date start">
                    </div>

                    <span class="mx-4 text-blue-1100">to</span>

                    {{-- End --}}
                    <div class="relative">
                        <div
                            class="absolute text-blue-900 inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
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
                            class="bg-white border border-blue-300 text-blue-1100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10"
                            placeholder="Select date end">
                    </div>
                </div>

                {{-- Loading State --}}
                <div class="absolute items-center justify-end z-50 min-h-full min-w-full text-blue-900"
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

            {{-- Content --}}
            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-6">

                {{-- List of Beneficiaries --}}
                <div class="relative lg:col-span-3 h-[89vh] w-full rounded bg-white shadow">
                    {{-- Table Header --}}
                    <div class="relative max-h-12 my-2 flex items-center justify-between">
                        <div x-data="{ open: false }" class="relative text-blue-900">

                            {{-- Batches Dropdown Button --}}
                            <button id="batchDropdownButton" @click="open = !open;"
                                class="flex items-center ms-4 py-1 px-2 text-xs outline-none font-semibold rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 duration-200 ease-in-out">
                                {{ $this->currentBatch }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            {{-- Batch Dropdown Content --}}
                            <div id="batchDropdownContent" x-cloak x-show="open" @click.away="open = !open;"
                                :class="{
                                    'block': open === true,
                                    'hidden': open === false,
                                }"
                                class="absolute top-7 left-4 z-50 p-2 w-[20.5rem] bg-white border rounded shadow">
                                {{-- Header / Search Batches / Counter / Filter --}}
                                <div class="mx-4 mb-2 flex items-center justify-center">
                                    <span
                                        class="flex items-center rounded text-blue-700 bg-blue-100 py-1 px-2 text-xs me-2 select-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 me-1.5"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                        {{ $this->batchesCount }}</span>
                                    <div class="relative flex items-center">
                                        <div
                                            class="absolute z-50 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                            <svg class="size-3 text-blue-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="batch-search" maxlength="100" autocomplete="off"
                                            wire:model.live.debounce.300ms="searchBatches"
                                            class="duration-200 outline-none ease-in-out ps-6 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Search for batch numbers">
                                    </div>
                                    {{-- Filter Button --}}
                                    <div x-data="{
                                        open: false,
                                        toggle() {
                                            this.open = !this.open;
                                        },
                                    
                                        selectOption(option) {
                                            this.type_of_id = option;
                                            this.toggle(); // Close the dropdown after selecting an option
                                        }
                                    }" x-id="['button']" class="relative"
                                        x-on:click.outside="open = false">
                                        <!-- Button -->
                                        <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                                            :aria-controls="$id('button')" type="button"
                                            class="flex items-center outline-none rounded p-1 ms-2 text-sm font-bold duration-200 ease-in-out border-2 border-blue-700 hover:border-transparent active:border-transparent hover:bg-blue-700 active:bg-blue-900 text-blue-900 hover:text-blue-100 active:text-blue-200 focus:bg-blue-700 focus:text-blue-50 focus:border-transparent">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M55.859 51.091 C 37.210 57.030,26.929 76.899,32.690 95.866 C 35.051 103.642,34.376 102.847,97.852 172.610 L 156.250 236.794 156.253 298.670 C 156.256 359.035,156.294 360.609,157.808 363.093 C 161.323 368.857,170.292 370.737,175.953 366.895 C 184.355 361.193,241.520 314.546,242.553 312.549 C 243.578 310.566,243.750 304.971,243.750 273.514 L 243.750 236.794 302.148 172.610 C 365.624 102.847,364.949 103.642,367.310 95.866 C 372.533 78.673,364.634 60.468,348.673 52.908 L 343.359 50.391 201.172 50.243 C 87.833 50.126,58.350 50.298,55.859 51.091 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        <!-- Panel -->
                                        <div x-ref="panel" x-show="open" x-transition.origin.top
                                            :id="$id('button')" style="display: none;"
                                            class="absolute text-xs left-0 mt-2 h-40 w-40 z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                            {{-- <button type="button" x-on:click="selectOption('e-Card / UMID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                                    e-Card / UMID
                                                    </button> --}}
                                            Insert filters here
                                        </div>
                                    </div>
                                </div>
                                <ul class="px-2 text-sm text-blue-1100 overflow-y-auto h-48 scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700"
                                    aria-labelledby="batchButton">
                                    @forelse ($this->batches as $key => $batch)
                                        @php
                                            $encryptedId = encrypt($batch->id);
                                        @endphp
                                        <li wire:key="batch-{{ $key }}">
                                            <button type="button" @click="open = !open;"
                                                wire:click="selectBatchRow({{ $key }}, '{{ $encryptedId }}')"
                                                class="flex items-center w-full px-1 py-2 text-xs hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out cursor-pointer">
                                                {{ $batch->batch_num }} / {{ $batch->barangay_name }}
                                            </button>
                                        </li>
                                    @empty
                                        <li
                                            class="flex items-center justify-center h-full w-full border rounded bg-gray-50 border-gray-300 text-gray-400">
                                            <p>Nothing to see here...</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- Search Beneficiaries --}}
                        <div class="me-4 flex items-center justify-end">

                            {{-- Beneficiary Count --}}
                            <span
                                class="flex items-center font-medium rounded text-blue-700 bg-blue-100 py-1 px-2 text-xs me-2 select-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 me-1.5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M96.875 42.643 C 52.219 54.424,52.561 118.254,97.341 129.707 C 111.583 133.349,116.540 131.561,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.616 67.550,148.905 66.535,145.219 60.791 C 135.687 45.938,114.514 37.989,96.875 42.643 M280.938 42.600 C 270.752 45.179,260.204 52.464,254.763 60.678 C 251.061 66.267,251.383 67.401,258.836 75.011 C 272.214 88.670,280.835 105.931,282.526 122.444 C 283.253 129.539,284.941 131.255,291.175 131.236 C 330.920 131.117,351.409 84.551,324.504 55.491 C 313.789 43.917,296.242 38.725,280.938 42.600 M189.063 75.494 C 134.926 85.627,123.780 159.908,172.566 185.433 C 216.250 208.290,267.190 170.135,257.471 121.839 C 251.236 90.860,220.007 69.703,189.063 75.494 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M283.058 149.743 C 282.139 150.542,280.658 153.753,279.696 157.031 C 276.119 169.218,270.328 179.314,261.225 189.234 C 253.482 197.670,254.234 200.382,265.191 203.537 C 288.694 210.306,307.108 223.950,319.474 243.758 C 324.516 251.833,323.991 251.565,334.706 251.543 C 362.465 251.487,376.780 236.149,375.520 207.813 C 374.261 179.527,360.172 159.904,334.766 151.051 C 326.406 148.137,286.076 147.117,283.058 149.743 M150.663 223.858 C 119.731 229.560,95.455 253.370,88.566 284.766 C 80.747 320.396,94.564 350.121,122.338 357.418 C 129.294 359.246,270.706 359.246,277.662 357.418 C 300.848 351.327,312.868 333.574,312.837 305.469 C 312.790 264.161,291.822 235.385,254.043 224.786 C 246.270 222.606,161.583 221.845,150.663 223.858 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                {{ $this->beneficiarySlots }}</span>

                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 text-blue-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="beneficiary-search" maxlength="100" autocomplete="off"
                                    @input.debounce.300ms="$wire.searchBeneficiaries = $el.value; $wire.$refresh();"
                                    class="duration-200 outline-none ease-in-out ps-7 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search for beneficiaries">
                            </div>
                        </div>
                    </div>

                    {{-- Beneficiaries Table --}}
                    @if ($this->beneficiaries->isNotEmpty())
                        <div id="beneficiaries-table"
                            class="relative min-h-[82.5vh] max-h-[82.5vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            <table class="relative w-full text-sm text-left text-blue-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-blue-50 uppercase bg-blue-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pr-2 ps-4 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="pr-2 py-2">
                                            full name
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            sex
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            birthdate
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($this->beneficiaries as $key => $beneficiary)
                                        @php
                                            $encryptedId = Crypt::encrypt($beneficiary->id);
                                        @endphp
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:click.prevent='selectBeneficiaryRow({{ $key }}, "{{ $encryptedId }}")'
                                            class="relative border-b {{ $selectedBeneficiaryRow === $key ? 'bg-gray-100 hover:bg-gray-200 text-blue-1000 hover:text-blue-900' : 'hover:bg-gray-50' }} whitespace-nowrap duration-200 ease-in-out cursor-pointer">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="pr-2 py-2">
                                                {{ $this->getFullName($key) }}
                                            </td>
                                            <td class="pr-2 py-2 text-center uppercase">
                                                {{ $beneficiary->sex }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $beneficiary->birthdate }}
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <tr x-data x-intersect.full="$wire.loadMoreBatches()">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="relative bg-white px-4 pb-4 pt-2 h-[82.5vh] min-w-full flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="animate-pulse size-12 sm:size-20 mb-4 text-gray-400"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M178.125 0.827 C 46.919 16.924,-34.240 151.582,13.829 273.425 C 21.588 293.092,24.722 296.112,36.372 295.146 C 48.440 294.145,53.020 282.130,46.568 268.403 C 8.827 188.106,45.277 89.951,128.125 48.784 C 171.553 27.204,219.595 26.272,266.422 46.100 C 283.456 53.313,294.531 48.539,294.531 33.984 C 294.531 23.508,289.319 19.545,264.116 10.854 C 238.096 1.882,202.941 -2.217,178.125 0.827 M377.734 1.457 C 373.212 3.643,2.843 374.308,1.198 378.295 C -4.345 391.732,9.729 404.747,23.047 398.500 C 28.125 396.117,397.977 25.550,399.226 21.592 C 403.452 8.209,389.945 -4.444,377.734 1.457 M359.759 106.926 C 348.924 111.848,347.965 119.228,355.735 137.891 C 411.741 272.411,270.763 412.875,136.719 356.108 C 120.384 349.190,113.734 349.722,107.773 358.421 C 101.377 367.755,106.256 378.058,119.952 384.138 C 163.227 403.352,222.466 405.273,267.578 388.925 C 375.289 349.893,429.528 225.303,383.956 121.597 C 377.434 106.757,370.023 102.263,359.759 106.926 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p>No batches found.</p>
                                <p>Ask your focal to assign a <span class="animate-pulse text-blue-900">new
                                        batch</span>.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Create Button | Main Modal --}}
                    {{-- <livewire:focal.batchs.create-project-modal /> --}}
                </div>

                {{-- Beneficiary Preview | Special Cases --}}
                <div class="relative flex flex-col lg:col-span-3 size-full">

                    {{-- Beneficiary Preview --}}
                    <div class="grid grid-rows-3 h-1/2 w-full rounded bg-white shadow text-xs">

                        {{-- Upper --}}
                        <div class="row-span-2 flex items-start justify-stretch">

                            {{-- ID --}}
                            <div class="flex flex-col text-blue-1100">
                                <div
                                    class="bg-blue-100 text-blue-700 border-blue-300 border-dashed border-2 rounded mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-32"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M107.422 50.878 C 79.094 54.549,57.713 74.036,51.814 101.563 C 49.620 111.800,49.620 288.200,51.814 298.438 C 57.220 323.662,76.338 342.780,101.563 348.186 C 107.773 349.517,114.149 349.609,200.000 349.609 C 285.851 349.609,292.227 349.517,298.438 348.186 C 323.662 342.780,342.780 323.662,348.186 298.438 C 350.380 288.200,350.380 111.800,348.186 101.563 C 342.861 76.716,324.200 57.775,299.219 51.860 C 292.608 50.294,118.792 49.405,107.422 50.878 M283.372 84.383 C 295.540 85.460,299.847 87.205,306.321 93.679 C 315.819 103.176,316.386 107.330,316.398 167.420 L 316.406 208.669 313.086 206.393 C 290.258 190.744,266.010 193.819,243.963 215.159 C 238.678 220.274,234.240 224.317,234.100 224.144 C 220.448 207.251,185.837 166.529,182.862 163.858 C 168.386 150.865,145.748 148.079,127.547 157.051 C 119.004 161.262,114.813 165.299,98.040 185.480 L 83.984 202.389 83.754 165.062 C 83.406 108.493,84.139 103.218,93.679 93.679 C 99.894 87.463,104.758 85.373,115.176 84.442 C 125.621 83.509,272.912 83.457,283.372 84.383 M227.937 133.924 C 211.002 139.968,213.315 164.176,231.085 166.867 C 241.190 168.397,250.000 160.541,250.000 150.000 C 250.000 137.987,239.004 129.974,227.937 133.924 M156.764 187.447 C 159.428 188.657,164.587 194.405,185.420 219.379 C 212.037 251.287,213.239 252.533,220.736 255.991 C 235.489 262.795,247.798 259.174,264.151 243.218 C 281.729 226.068,285.035 226.261,304.492 245.576 L 316.406 257.403 316.398 265.616 C 316.363 301.864,308.764 313.369,283.372 315.617 C 271.802 316.641,128.198 316.641,116.628 315.617 C 91.083 313.356,83.659 302.019,83.606 265.193 L 83.594 256.558 111.781 222.744 C 144.714 183.237,145.732 182.438,156.764 187.447 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="font-semibold text-center">PhilID</p>
                                <p class="text-center text-2xs">1234-5678-9123-4567</p>

                            </div>

                            {{-- Basic Information --}}
                            <div>

                                b

                            </div>

                            {{-- Additional Information --}}
                            <div>

                                c

                            </div>
                        </div>

                        {{-- Lower --}}
                        <div class="flex items-start justify-stretch">
                            {{-- Address --}}
                            <div>d</div>

                            {{-- Spouse Information --}}
                            <div>e</div>
                        </div>
                    </div>

                    {{-- Beneficiary Preview --}}
                    <div class="h-1/2 w-full">
                        b
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@script
    <script>
        const datepickerStart = document.getElementById('start-date');
        const datepickerEnd = document.getElementById('end-date');

        datepickerStart.addEventListener('changeDate', function(event) {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            $wire.dispatchSelf('start-change', {
                value: datepickerStart.value
            });
        });

        datepickerEnd.addEventListener('changeDate', function(event) {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            $wire.dispatchSelf('end-change', {
                value: datepickerEnd.value
            });
        });

        $wire.on('scroll-to-top', () => {
            const beneficiariesTable = document.getElementById('beneficiaries-table');
            if (beneficiariesTable) {
                beneficiariesTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });
    </script>
@endscript
