<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-cloak x-data="{ open: true, show: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <livewire:sidebar.coordinator-bar wire:key="{{ str()->random(50) }}" />

    <div x-data="{ scrollToTop() { document.getElementById('batches-table').scrollTo({ top: 0, behavior: 'smooth' }); } }" :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">

            {{-- Title --}}
            <div class="relative flex items-center justify-between my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Assignments</h1>
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

            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-5">

                {{-- List of Batches --}}
                <div class="relative lg:col-span-3 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 flex items-center justify-between">
                        <div class="inline-flex items-center text-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M194.141 24.141 C 160.582 38.874,10.347 106.178,8.003 107.530 C -1.767 113.162,-2.813 128.836,6.116 135.795 C 7.694 137.024,50.784 160.307,101.873 187.535 L 194.761 237.040 200.000 237.040 L 205.239 237.040 298.127 187.535 C 349.216 160.307,392.306 137.024,393.884 135.795 C 402.408 129.152,401.802 113.508,392.805 107.955 C 391.391 107.082,348.750 87.835,298.047 65.183 C 199.201 21.023,200.275 21.448,194.141 24.141 M11.124 178.387 C -0.899 182.747,-4.139 200.673,5.744 208.154 C 7.820 209.726,167.977 295.513,188.465 306.029 C 198.003 310.924,201.997 310.924,211.535 306.029 C 232.023 295.513,392.180 209.726,394.256 208.154 C 404.333 200.526,400.656 181.925,388.342 178.235 C 380.168 175.787,387.662 172.265,289.164 224.847 C 242.057 249.995,202.608 270.919,201.499 271.344 C 199.688 272.039,190.667 267.411,113.316 226.098 C 11.912 171.940,19.339 175.407,11.124 178.387 M9.766 245.797 C -1.277 251.753,-3.565 266.074,5.202 274.365 C 7.173 276.229,186.770 372.587,193.564 375.426 C 197.047 376.881,202.953 376.881,206.436 375.426 C 213.230 372.587,392.827 276.229,394.798 274.365 C 406.493 263.306,398.206 243.873,382.133 244.666 L 376.941 244.922 288.448 292.077 L 199.954 339.231 111.520 292.077 L 23.085 244.922 17.597 244.727 C 13.721 244.590,11.421 244.904,9.766 245.797 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            <h1 class="font-bold m-2">List of Batches</h1>

                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="mx-2 flex items-center justify-end">
                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 text-blue-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="project-search" maxlength="100"
                                    class="duration-200 ease-in-out ps-7 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search for project titles">
                            </div>
                            <button
                                class="flex items-center bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 hover:text-blue-100 active:text-blue-200 focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500 rounded px-4 py-1 text-sm font-bold duration-200 ease-in-out">
                                FILTER
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

                    @if ($batches)
                        {{-- List of Projects Table --}}
                        <div id="batches-table"
                            class="relative min-h-[84vh] max-h-[84vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700">
                            <table class="relative w-full text-sm text-left text-blue-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-blue-50 uppercase bg-blue-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            batch #
                                        </th>
                                        <th scope="col" class="pr-6 py-2">
                                            barangay
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            slots
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            approval status
                                        </th>
                                        <th scope="col" class="pr-2 py-2 text-center">
                                            submission status
                                        </th>

                                        <th scope="col" class="px-2 py-2 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-xs">
                                    @foreach ($batches as $key => $batch)
                                        @php
                                            $encryptedId = Crypt::encrypt($batch['id']);
                                        @endphp
                                        <tr wire:key="batch-{{ $key }}"
                                            wire:click.prevent='selectBatchRow({{ $key }}, "{{ $encryptedId }}")'
                                            class="relative border-b {{ $selectedBatchRow === $key ? 'bg-blue-100 hover:bg-blue-200 text-blue-900' : 'hover:bg-blue-50' }} whitespace-nowrap duration-200 ease-in-out">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $batch['batch_num'] }}
                                            </th>
                                            <td class="pr-6 py-2">
                                                {{ $batch['barangay_name'] }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $batch['current_slots'] }}
                                                /
                                                {{ $batch['slots_allocated'] }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                @if ($batch['approval_status'] === 'approved')
                                                    <span
                                                        class="bg-green-300 text-green-1100 rounded py-1 px-2 uppercase font-medium">{{ $batch['approval_status'] }}</span>
                                                @else
                                                    <span
                                                        class="bg-amber-300 text-amber-950 rounded py-1 px-2 uppercase font-medium">{{ $batch['approval_status'] }}</span>
                                                @endif
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                @if ($batch['submission_status'] === 'unopened')
                                                    <span
                                                        class="bg-amber-300 text-amber-950 rounded py-1 px-2 uppercase font-medium">{{ $batch['submission_status'] }}</span>
                                                @elseif($batch['submission_status'] === 'encoding')
                                                    <span
                                                        class="bg-sky-300 text-sky-950 rounded py-1 px-2 uppercase font-medium">{{ $batch['submission_status'] }}</span>
                                                @elseif($batch['submission_status'] === 'submitted')
                                                    <span
                                                        class="bg-green-300 text-green-1100 rounded py-1 px-2 uppercase font-medium">{{ $batch['submission_status'] }}</span>
                                                @elseif($batch['submission_status'] === 'revalidate')
                                                    <span
                                                        class="bg-red-300 text-red-950 rounded py-1 px-2 uppercase font-medium">{{ $batch['submission_status'] }}</span>
                                                @endif
                                            </td>

                                            {{-- Batch Dropdown --}}
                                            <td x-data="batchDropdown({{ $key }})" class="py-2 flex">
                                                <button @click.stop="handleClick()"
                                                    id="batchRowButton-{{ $key }}"
                                                    data-dropdown-placement="left"
                                                    data-dropdown-toggle="batchRowDropdown-{{ $key }}"
                                                    class="z-0 mx-1 p-1 outline-none font-medium rounded 
                                                    {{ $selectedBatchRow === $key
                                                        ? 'bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-blue-50'
                                                        : 'text-blue-1100 hover:text-blue-1000 active:text-blue-900 hover:bg-blue-200' }}  duration-200 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                        :class="{
                                                            'rotate-0': !isVisible(),
                                                            'rotate-90': isVisible(),
                                                        }"
                                                        class="w-4 duration-200 ease-in-out">
                                                        <path fill-rule="evenodd"
                                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
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

                        {{-- Batch Dropdown Content --}}
                        @foreach ($batches as $key => $batch)
                            <div wire:key="batchRowDropdown-{{ $key }}"
                                id="batchRowDropdown-{{ $key }}"
                                class="absolute z-50 hidden bg-white border rounded-md shadow">
                                <ul class="text-sm text-blue-1100"
                                    aria-labelledby="batchRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('View Batch') }}"
                                            class="rounded-t-md flex items-center justify-start px-4 py-2 hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out cursor-pointer">
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
                                            View Batch
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Access Code') }}"
                                            class="rounded-b-md flex items-center justify-start px-4 py-2 hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out cursor-pointer">

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
                                            Access Code
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
                                <p>No batches found.</p>
                                <p>Try creating a <span class="animate-pulse text-blue-900">new batch</span>.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Create Button | Main Modal --}}
                    {{-- <livewire:focal.batchs.create-project-modal /> --}}
                </div>

                {{-- List Overview --}}
                <div class="relative w-full col-span-2">
                    {{-- Title --}}

                    <div class="relative flex-col flex bg-white p-2 rounded text-blue-1100 text-xs font-semibold">
                        <p class="inline-flex text-lg ms-1 font-bold text-blue-900 pb-2">
                            List Overview
                        </p>
                        <div class="flex items-center justify-start ms-2 my-1">
                            Location: <p class="font-normal ps-2 truncate">Brgy.
                                {{ $location['barangay_name'] . ', ' . $location['district'] . ', ' . $location['city_municipality'] }}
                            </p>
                        </div>
                        <div class="flex items-center justify-start ms-2 my-1">
                            Access Code: <p class="ps-2 font-normal"> {{ $accessCode['access_code'] }}</p>
                        </div>
                        <div class="flex items-center justify-start ms-2 my-1">
                            Resubmissions: <p class="font-normal ps-2">0</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end my-2 w-full text-sm">
                        {{-- Found 3 special cases! --}}
                        <button
                            class="flex items-center bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 hover:text-blue-100 active:text-blue-200 focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500 rounded px-3 py-1 text-sm font-bold">
                            <p class="p-0 m-0">
                                VIEW LIST
                            </p>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-5 ms-2">
                                <path fill-rule="evenodd"
                                    d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>


                    {{-- Table --}}
                    <div class="relative min-h-[64vh] max-h-[64vh] overflow-y-auto rounded whitespace-nowrap bg-white">

                        <table class="w-full text-sm text-left text-blue-1100">
                            <thead class="text-xs text-blue-50 uppercase bg-blue-600 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-2 py-2">
                                        #
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        full name
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">
                                        birthdate
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">
                                        contact #
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($beneficiaries as $key => $beneficiary)
                                    @php
                                        $encryptedId = encrypt($beneficiary['id']);
                                    @endphp
                                    <tr class=" border-b hover:bg-blue-100 whitespace-nowrap">
                                        <th scope="row"
                                            class="px-2 py-2 font-semibold text-blue-1100 whitespace-nowrap">
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="px-2 py-2">
                                            @php
                                                $first = $beneficiary['first_name'];
                                                $middle = $beneficiary['middle_name'];
                                                $last = $beneficiary['last_name'];
                                                $ext = $beneficiary['extension_name'];

                                                if ($ext === '-' && $middle === '-') {
                                                    $full_name = $first . ' ' . $last;
                                                } elseif ($middle === '-' && $ext !== '-') {
                                                    $full_name = $first . ' ' . $last . ' ' . $ext;
                                                } elseif ($middle !== '-' && $ext === '-') {
                                                    $full_name = $first . ' ' . $middle . ' ' . $last;
                                                } else {
                                                    $full_name = $first . ' ' . $middle . ' ' . $last . ' ' . $ext;
                                                }
                                            @endphp
                                            {{ $full_name }}
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            {{ $beneficiary['birthdate'] }}
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            {{ $beneficiary['contact_num'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });

        // Implementation Dropdown Shenanigans
        Alpine.data('batchDropdown', (key) => ({
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
    </script>
@endscript
