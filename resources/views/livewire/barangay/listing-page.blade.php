<x-slot:favicons>
    <x-b-favicons />
</x-slot>

<div>
    <div x-data="{
        addBeneficiariesModal: $wire.entangle('addBeneficiariesModal'),
        editBeneficiaryModal: $wire.entangle('editBeneficiaryModal'),
        deleteBeneficiaryModal: $wire.entangle('deleteBeneficiaryModal'),
        viewCredentialsModal: $wire.entangle('viewCredentialsModal'),
        submitBatchModal: $wire.entangle('submitBatchModal'),
    
    }" class="p-2 min-h-screen select-none text-green-1100">
        {{-- App Name | Submission Type | Submit Button --}}
        <div class="relative flex items-center justify-between mb-2">
            <div class="flex items-center justify-between">

                <img class="rounded-lg object-contain drop-shadow size-11 ms-3 duration-500 ease-in-out select-none"
                    src="{{ asset('assets/b_logo.png') }}" alt="TU-Efficient Barangay logo">
                <h1 class="text-3xl font-bold ms-3 text-green-900 drop-shadow">TU-EFFICIENT</h1>

                @if ($this->batch->submission_status === 'encoding')
                    <span class="text-sm bg-green-300 text-green-1000 rounded-md px-2 py-1 ms-3 font-semibold">For
                        Submission
                    </span>
                @elseif ($this->batch->submission_status === 'revalidate')
                    <span class="text-sm bg-red-200 text-red-900 rounded-md px-2 py-1 ms-3 font-semibold">For
                        Revalidation
                    </span>
                @endif

            </div>
            <div class="flex items-center justify-end">
                {{-- Loading State --}}
                <div class="items-center justify-center z-50 text-green-900 me-3" wire:loading
                    wire:target="viewCredential, selectBeneficiaryRow, openEdit, loadMoreBeneficiaries, deleteBeneficiary,">
                    <svg class="size-8 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>

                {{-- Submit Button --}}
                <button type="button" id="submitBatchButton"
                    @if ($this->beneficiaryCount > 0) @click="submitBatchModal = true;"
                    @else disabled @endif
                    class="flex items-center text-xs sm:text-sm disabled:bg-gray-300 disabled:text-gray-500 bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50 rounded-md px-4 py-2 me-3 font-bold">
                    SUBMIT
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 ms-2">
                        <path
                            d="M11.47 1.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1-1.06 1.06l-1.72-1.72V7.5h-1.5V4.06L9.53 5.78a.75.75 0 0 1-1.06-1.06l3-3ZM11.25 7.5V15a.75.75 0 0 0 1.5 0V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-3 pb-3 lg:px-0 lg:pb-0 relative flex flex-col lg:grid lg:grid-cols-8 gap-4">

            {{-- List of Beneficiaries --}}
            <div class="col-span-4 w-full h-[89vh] bg-white shadow rounded">

                {{-- Upper/Header --}}
                <div class="flex flex-col gap-2 mb-2">

                    {{-- Batch Information --}}
                    <div class="flex flex-1 flex-col justify-center">

                        {{-- Header Title --}}
                        <span class="flex items-center justify-start gap-2 font-bold text-lg my-2 ms-2 text-green-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            Beneficiaries <span class="rounded text-xs px-2 py-1 font-medium"
                                :class="{
                                    'bg-red-100 text-red-700': {{ json_encode($this->beneficiaryCount === 0) }},
                                    'bg-amber-100 text-amber-700': {{ json_encode($this->beneficiaryCount !== $this->batch->slots_allocated) }},
                                    'bg-green-100 text-green-700': {{ json_encode($this->beneficiaryCount === $this->batch->slots_allocated) }},
                                }">{{ $this->beneficiaryCount . ' / ' . $this->batch->slots_allocated }}</span>
                        </span>

                        {{-- Barangay Name | Location --}}
                        <div class="flex flex-col text-xs ms-4 gap-y-0.5 text-green-900 font-medium">
                            <span class="flex items-center">

                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 me-1 stroke-green-900"
                                    width="32" height="32" fill="currentColor" viewBox="0 0 256 256">
                                    <path
                                        d="M240,208H224V136l2.34,2.34A8,8,0,0,0,237.66,127L139.31,28.68a16,16,0,0,0-22.62,0L18.34,127a8,8,0,0,0,11.32,11.31L32,136v72H16a8,8,0,0,0,0,16H240a8,8,0,0,0,0-16ZM48,120l80-80,80,80v88H160V152a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8v56H48Zm96,88H112V160h32Z">
                                    </path>
                                </svg>
                                <p class="me-1">Barangay:</p> <span
                                    class="text-green-1100 font-normal">{{ $this->batchInformation['barangay'] }}</span>
                            </span>
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 me-1 stroke-green-900">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <p class="me-1">Location:</p> <span
                                    class="text-green-1100 font-normal">{{ $this->batchInformation['location'] }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Search and Add Button | and Slots (for lower lg) --}}
                    <div class="flex items-center justify-end mx-2 gap-2">

                        {{-- Search Bar --}}
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 start-0 flex items-center ps-2 text-green-900 pointer-events-none">

                                {{-- Loading Circle --}}
                                <svg class="size-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    wire:loading wire:target="searchBeneficiaries" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Search Icon --}}
                                <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    wire:loading.remove wire:target="searchBeneficiaries" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="beneficiary-search" maxlength="100" autocomplete="off"
                                wire:model.live.debounce.300ms="searchBeneficiaries"
                                class="text-green-1100 placeholder-green-500 border-green-300 bg-green-50 focus:ring-green-500 focus:border-green-500
                                     outline-none duration-200 ease-in-out ps-7 py-1 text-xs border rounded w-full"
                                placeholder="Search beneficiary">
                        </div>
                        <button id="addModalButton" @click="addBeneficiariesModal = !addBeneficiariesModal;"
                            class="flex items-center bg-green-900 hover:bg-green-800 text-green-50 hover:text-green-100 rounded-md px-4 py-1 text-sm font-bold focus:ring-green-500 focus:border-green-500 focus:outline-green-500 duration-200 ease-in-out">
                            ADD
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

                {{-- Beneficiaries Table --}}
                @if ($this->beneficiaries->isNotEmpty())
                    <div id="beneficiaries-table"
                        class="relative h-[70vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-green-50 scrollbar-thumb-green-700">
                        <table class="relative w-full text-sm text-left text-green-1100">
                            <thead
                                class="text-xs z-20 text-green-50 uppercase bg-green-600 sticky top-0 whitespace-nowrap">
                                <tr>
                                    <th scope="col" class="absolute h-full w-1 left-0 z-50">
                                        {{-- Selected Row Indicator --}}
                                    </th>
                                    <th scope="col" class="pe-2 ps-4 py-2">
                                        #
                                    </th>
                                    <th scope="col" class="p-2">
                                        first name
                                    </th>
                                    <th scope="col" class="p-2">
                                        middle name
                                    </th>
                                    <th scope="col" class="p-2">
                                        last name
                                    </th>
                                    <th scope="col" class="p-2">
                                        ext.
                                    </th>
                                    <th scope="col" class="p-2">
                                        birthdate
                                    </th>
                                    <th scope="col" class="p-2">
                                        contact #
                                    </th>
                                    <th scope="col" class="p-2">
                                        sex
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($this->beneficiaries as $key => $beneficiary)
                                    <tr wire:key="beneficiary-{{ $key }}"
                                        wire:loading.class="pointer-events-none"
                                        wire:click.prevent="selectBeneficiaryRow({{ $key }}, '{{ encrypt($beneficiary->id) }}')"
                                        class="relative {{ $selectedBeneficiaryRow === $key ? 'bg-gray-100 text-green-1000 hover:bg-gray-200' : ' hover:bg-gray-50' }} border-b divide-x whitespace-nowrap cursor-pointer">
                                        <td class="absolute h-full w-1 left-0 z-50"
                                            :class="{
                                                'bg-green-700': {{ json_encode($beneficiary->beneficiary_type !== 'special case' && $selectedBeneficiaryRow === $key) }},
                                                '': {{ json_encode($beneficiary->beneficiary_type !== 'special case' && $selectedBeneficiaryRow !== $key) }},
                                                'bg-amber-700': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow === $key) }},
                                                '': {{ json_encode($beneficiary->beneficiary_type === 'special case' && $selectedBeneficiaryRow !== $key) }},
                                            }">
                                            {{-- Selected Row Indicator --}}
                                        </td>
                                        <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="p-2">
                                            {{ $beneficiary->first_name }}
                                        </td>
                                        <td class="p-2">
                                            {{ $beneficiary->middle_name ?? '-' }}
                                        </td>
                                        <td class="p-2">
                                            {{ $beneficiary->last_name }}
                                        </td>
                                        <td class="p-2">
                                            {{ $beneficiary->extension_name ?? '-' }}
                                        </td>
                                        <td class="p-2">
                                            {{ \Carbon\Carbon::parse($beneficiary->birthdate)->format('M d, Y') }}
                                        </td>
                                        <td class="p-2">
                                            {{ $beneficiary->contact_num }}
                                        </td>
                                        <td class="p-2">
                                            {{ ucwords($beneficiary->sex) }}
                                        </td>
                                    </tr>
                                    @if (count($this->beneficiaries) >= 15 && $loop->last)
                                        <tr x-data x-intersect.full.once="$wire.loadMoreBeneficiaries()">
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex h-[70vh] items-center justify-center bg-white px-4 pb-4 pt-2">
                        <div
                            class="relative flex flex-col items-center justify-center border rounded size-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                            @if (isset($searchBeneficiaries) && !empty($searchBeneficiaries))
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 text-green-900 opacity-65"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p>No beneficiaries found.</p>
                                <p>Try a different <span class=" text-green-900">search term</span>.</p>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 text-green-900 opacity-65"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M361.328 21.811 C 359.379 22.724,352.051 29.460,341.860 39.707 L 325.516 56.139 321.272 52.356 C 301.715 34.925,269.109 39.019,254.742 60.709 C 251.063 66.265,251.390 67.408,258.836 75.011 C 266.104 82.432,270.444 88.466,274.963 97.437 L 278.026 103.516 268.162 113.440 L 258.298 123.365 256.955 118.128 C 243.467 65.556,170.755 58.467,147.133 107.420 C 131.423 139.978,149.016 179.981,183.203 189.436 C 185.781 190.149,188.399 190.899,189.021 191.104 C 189.763 191.348,184.710 196.921,174.310 207.331 L 158.468 223.186 152.185 224.148 C 118.892 229.245,91.977 256.511,88.620 288.544 L 88.116 293.359 55.031 326.563 C 36.835 344.824,21.579 360.755,21.130 361.965 C 17.143 372.692,27.305 382.854,38.035 378.871 C 41.347 377.642,376.344 42.597,378.187 38.672 C 383.292 27.794,372.211 16.712,361.328 21.811 M97.405 42.638 C 47.755 54.661,54.862 127.932,105.980 131.036 C 115.178 131.595,116.649 130.496,117.474 122.444 C 119.154 106.042,127.994 88.362,141.155 75.080 C 148.610 67.556,148.903 66.533,145.237 60.820 C 135.825 46.153,115.226 38.322,97.405 42.638 M70.703 149.594 C 43.318 155.622,25.834 177.504,24.497 207.422 C 23.213 236.172,37.373 251.487,65.294 251.543 C 76.009 251.565,75.484 251.833,80.526 243.758 C 92.892 223.950,111.306 210.306,134.809 203.537 C 145.766 200.382,146.518 197.670,138.775 189.234 C 129.672 179.314,123.881 169.218,120.304 157.031 C 117.658 148.016,118.857 148.427,95.421 148.500 C 81.928 148.541,73.861 148.898,70.703 149.594 M317.578 149.212 C 313.524 150.902,267.969 198.052,267.969 200.558 C 267.969 202.998,270.851 206.250,273.014 206.250 C 274.644 206.250,288.145 213.131,293.050 216.462 C 303.829 223.781,314.373 234.794,320.299 244.922 C 324.195 251.580,324.162 251.565,334.706 251.543 C 345.372 251.522,349.106 250.852,355.379 247.835 C 387.793 232.245,380.574 173.557,343.994 155.278 C 335.107 150.837,321.292 147.665,317.578 149.212 M179.490 286.525 C 115.477 350.543,115.913 350.065,117.963 353.895 C 120.270 358.206,126.481 358.549,203.058 358.601 C 280.844 358.653,277.095 358.886,287.819 353.340 C 327.739 332.694,320.301 261.346,275.391 234.126 C 266.620 228.810,252.712 224.219,245.381 224.219 L 241.793 224.219 179.490 286.525 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p>No beneficiaries found.</p>
                                <p>Try adding a <span class=" text-green-900">new beneficiary</span>.
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Beneficiary Preview --}}
            <div class="relative flex flex-col col-span-4 size-full">
                <div class="flex flex-col size-full rounded bg-white shadow text-xs select-text">

                    @if ($beneficiaryId)
                        {{-- Whole Thing --}}
                        <div class="grid grid-cols-11 gap-2 p-4">

                            {{-- Left Side --}}
                            <div class="flex flex-col col-span-full sm:col-span-3 items-center text-green-1100 gap-2">

                                {{-- Identity Information --}}
                                <div class="flex flex-col items-center text-green-1100">
                                    {{-- ID Image --}}
                                    <div
                                        class="flex flex-col items-center justify-center bg-gray-50 text-gray-400 border-gray-300 border rounded mb-2 size-32 aspect-square">

                                        @if (isset($identity) && !empty($identity))
                                            <button class="flex items-center justify-center rounded"
                                                @click="$wire.viewCredential('identity');">
                                                <img class="w-[90%]"
                                                    src="{{ route('credentials.show', ['filename' => $identity]) }}">


                                            </button>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-[50%]"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M32.422 11.304 C 31.992 11.457,30.680 11.794,29.507 12.052 C 24.028 13.260,19.531 19.766,19.531 26.487 C 19.531 32.602,20.505 34.096,32.052 45.703 L 42.932 56.641 34.864 64.939 C 15.117 85.248,8.104 102.091,3.189 141.016 C -3.142 191.153,0.379 261.277,10.675 290.108 C 22.673 323.703,54.885 351.747,88.994 358.293 C 140.763 368.227,235.891 369.061,300.224 360.143 C 314.334 358.187,325.014 355.166,333.980 350.595 L 337.882 348.606 356.803 367.237 C 377.405 387.523,378.751 388.534,385.156 388.534 C 396.064 388.534,402.926 378.158,399.161 367.358 C 398.216 364.648,45.323 14.908,41.621 13.013 C 39.365 11.859,33.779 10.821,32.422 11.304 M173.685 26.603 C 149.478 27.530,105.181 31.289,103.940 32.521 C 103.744 32.716,109.721 38.980,117.221 46.441 L 130.859 60.008 143.750 58.937 C 190.711 55.035,239.415 56.114,289.049 62.156 C 323.242 66.318,344.750 80.309,357.596 106.748 C 367.951 128.058,373.239 201.260,367.335 241.563 L 366.797 245.235 356.492 231.797 C 310.216 171.453,298.664 162.344,271.006 164.387 C 260.988 165.127,245.312 170.115,245.313 172.562 C 245.313 173.401,380.320 307.031,381.167 307.031 C 382.090 307.031,388.660 292.643,390.518 286.555 C 403.517 243.958,402.683 139.537,389.046 102.170 C 377.740 71.192,349.876 45.280,318.284 36.368 C 294.697 29.713,221.504 24.771,173.685 26.603 M88.547 101.394 L 98.578 111.490 94.406 113.848 C 74.760 124.952,71.359 153.827,87.859 169.432 C 104.033 184.729,130.241 181.325,141.915 162.410 L 144.731 157.848 146.780 159.342 C 147.906 160.164,161.448 173.480,176.871 188.934 L 204.915 217.032 200.234 222.774 C 194.483 229.829,171.825 260.177,171.304 261.523 C 170.623 263.286,169.872 262.595,162.828 253.726 C 153.432 241.895,140.224 226.635,137.217 224.134 C 126.063 214.861,107.616 213.280,93.162 220.358 C 85.033 224.339,70.072 241.107,47.047 272.044 L 40.234 281.197 39.314 279.023 C 32.914 263.906,28.466 201.412,31.263 165.934 C 34.978 118.821,40.622 102.197,58.912 84.488 L 64.848 78.741 71.682 85.019 C 75.440 88.472,83.030 95.841,88.547 101.394 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            <p class="font-medium text-xs mt-2">
                                                No image uploaded.
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Type of ID --}}
                                    <p class="font-semibold select-all text-center">
                                        {{ $this->getIdType }}
                                    </p>

                                    {{-- ID Number --}}
                                    <p class="text-center select-all">
                                        {{ $this->beneficiary->id_number }}
                                    </p>
                                </div>

                                {{-- Address Information --}}
                                <div class="flex flex-col w-full text-green-1100 gap-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                        address</p>

                                    {{-- Body --}}
                                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                                        {{-- Province --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                province </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->province }}</span>
                                        </div>

                                        {{-- City/Municipality --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                city / municipality </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->city_municipality }}</span>
                                        </div>

                                        {{-- District --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                district </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->district }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Spouse Information --}}
                                <div class="flex flex-col w-full text-green-1100 gap-1">

                                    {{-- Header --}}
                                    <p
                                        class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                        spouse info</p>

                                    {{-- Body --}}
                                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">

                                        {{-- Spouse First Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                first name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->spouse_first_name ?? '-' }}</span>
                                        </div>

                                        {{-- Spouse Middle Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                middle name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->spouse_middle_name ?? '-' }}</span>
                                        </div>

                                        {{-- Spouse Last Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                last name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->spouse_last_name ?? '-' }}</span>
                                        </div>

                                        {{-- Spouse Extension Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                ext. name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->spouse_extension_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Side --}}
                            <div class="flex col-span-full sm:col-span-8 flex-col text-green-1100 gap-1">

                                {{-- Header --}}
                                <p
                                    class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                                    basic information
                                </p>

                                {{-- Body --}}
                                <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- First Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                first name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->first_name }}</span>
                                        </div>

                                        {{-- Middle Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                middle name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->middle_name ?? '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- Last Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                last name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->last_name }}</span>
                                        </div>

                                        {{-- Extension Name --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                ext. name </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->extension_name ?? '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- Birthdate --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                birthdate </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ Carbon\Carbon::parse($this->beneficiary->birthdate)->format('M. d, Y') }}</span>
                                        </div>

                                        {{-- Age --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                age </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->age }}
                                            </span>
                                        </div>

                                        {{-- Sex --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                sex </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ $this->beneficiary->sex }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- Civil Status --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                civil status </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ $this->beneficiary->civil_status }}</span>
                                        </div>

                                        {{-- Contact Number --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                contact number </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->contact_num }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- Occupation --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                occupation </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->occupation ?? 'None' }}</span>
                                        </div>

                                        {{-- Avg Monthly Income --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium capitalize">
                                                avg. monthly income </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                @if ($this->beneficiary->avg_monthly_income === null || $this->beneficiary->avg_monthly_income === 0)
                                                    {{ '-' }}
                                                @else
                                                    {{ '₱' . number_format($this->beneficiary->avg_monthly_income / 100, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- Type of Beneficiary --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium">
                                                Type of Beneficiary </p>

                                            @if ($this->beneficiary->beneficiary_type === 'special case')
                                                <button type="button" @click="$wire.viewCredential('special');"
                                                    class="relative flex items-center justify-between whitespace-normal rounded capitalize px-2 py-0.5 outline-none bg-amber-100 active:bg-amber-200 text-amber-950 hover:text-amber-700 duration-200 ease-in-out">
                                                    {{ $this->beneficiary->beneficiary_type }}

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="absolute right-2 size-4"
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
                                            @else
                                                <span
                                                    class="whitespace-normal rounded px-2 py-0.5 bg-green-50 text-green-1000 capitalize select-all">
                                                    {{ $this->beneficiary->beneficiary_type }}
                                                </span>
                                            @endif

                                        </div>

                                        {{-- Dependent --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                dependent </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->dependent ?? '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between">
                                        {{-- Interested in Self Employment or Wage Employment --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                interested in self employment or wage employment </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ $this->beneficiary->self_employment }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between">
                                        {{-- Skills Training --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                skills training </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->skills_training ?? '-' }}
                                            </span>
                                        </div>

                                        {{-- e-Payment Account Number --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium">
                                                e-Payment Account Number </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 select-all">
                                                {{ $this->beneficiary->e_payment_acc_num ?? '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- is PWD --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                Person w/ Disability </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ $this->beneficiary->is_pwd }}</span>
                                        </div>

                                        {{-- is Senior Citizen --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                Senior Citizen </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ $this->beneficiary->is_senior_citizen }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                                        {{-- is PWD --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                Date Added </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ \Carbon\Carbon::parse($this->beneficiary->created_at)->format('M d, Y @ h:i:sa') }}</span>
                                        </div>

                                        {{-- is Senior Citizen --}}
                                        <div class="flex flex-1 flex-col justify-center">
                                            <p class="select-all font-medium  capitalize">
                                                Last Updated </p>
                                            <span
                                                class="whitespace-normal bg-green-50 text-green-1000 rounded px-2 py-0.5 capitalize select-all">
                                                {{ \Carbon\Carbon::parse($this->beneficiary->updated_at)->format('M d, Y @ h:i:sa') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex flex-1 px-2 py-1 gap-2">
                                    <div
                                        class="relative max-[430px]:flex-col flex flex-1 items-center justify-end gap-2">

                                        {{-- Edit Button --}}
                                        <button
                                            @if ($this->batch->approval_status !== 'approved') @click="$wire.openEdit(); $dispatch('openEdit');"
                                        @else
                                        disabled @endif
                                            class="rounded text-sm font-bold flex flex-1 gap-2 items-center justify-center px-3 py-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50 focus:bg-green-800 focus:ring-2 focus:ring-green-300 duration-200 ease-in-out">
                                            EDIT
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M182.813 38.986 C 123.313 52.113,100.226 125.496,141.415 170.564 C 183.488 216.599,261.606 197.040,276.896 136.644 C 291.453 79.146,240.501 26.259,182.813 38.986 M278.141 204.778 C 272.904 206.868,270.880 210.858,270.342 220.156 L 269.922 227.420 264.768 229.218 C 261.934 230.206,258.146 231.841,256.351 232.849 L 253.088 234.684 248.224 229.884 C 241.216 222.970,235.198 221.459,229.626 225.214 C 221.063 230.985,221.157 239.379,229.884 248.224 L 234.684 253.088 232.849 256.351 C 231.841 258.146,230.206 261.934,229.218 264.768 L 227.420 269.922 220.156 270.313 C 208.989 270.915,204.670 274.219,204.083 282.607 C 203.466 291.419,208.211 295.523,219.675 296.094 L 227.526 296.484 228.868 300.781 C 229.606 303.145,231.177 306.971,232.359 309.285 L 234.508 313.492 230.227 317.879 C 223.225 325.054,221.747 330.343,224.976 336.671 C 229.458 345.458,239.052 345.437,248.076 336.622 L 252.794 332.014 258.233 334.683 C 261.224 336.151,265.133 337.742,266.919 338.218 L 270.167 339.083 270.435 346.830 C 270.818 357.905,274.660 362.505,283.514 362.495 C 292.220 362.485,296.084 357.523,296.090 346.344 L 296.094 339.173 300.586 337.882 C 303.057 337.171,306.997 335.559,309.341 334.298 L 313.605 332.006 318.326 336.618 C 324.171 342.328,325.413 342.969,330.613 342.966 C 344.185 342.956,347.496 329.464,336.652 318.359 L 332.075 313.672 334.421 309.022 C 335.711 306.464,337.308 302.509,337.970 300.233 L 339.173 296.094 346.276 296.094 C 357.566 296.094,362.500 292.114,362.500 283.005 C 362.500 274.700,357.650 270.809,346.830 270.435 L 339.083 270.167 338.218 266.919 C 337.742 265.133,336.151 261.224,334.683 258.233 L 332.014 252.794 336.622 248.076 C 345.259 239.234,345.423 230.021,337.028 225.208 C 330.778 221.625,325.473 222.915,318.356 229.749 L 313.432 234.478 309.255 232.344 C 306.958 231.170,303.145 229.606,300.781 228.868 L 296.484 227.526 296.094 219.675 C 295.460 206.941,288.076 200.814,278.141 204.778 M140.625 220.855 C 91.525 226.114,53.906 267.246,53.906 315.674 C 53.906 333.608,63.031 349.447,77.831 357.207 C 88.240 362.664,85.847 362.500,155.113 362.500 L 217.422 362.500 214.329 360.259 C 202.518 351.704,196.602 335.289,200.309 321.365 L 201.381 317.339 196.198 313.914 C 172.048 297.955,174.729 264.426,201.338 249.629 C 201.430 249.578,200.995 247.619,200.371 245.276 C 198.499 238.241,199.126 229.043,201.981 221.680 C 202.483 220.383,151.436 219.698,140.625 220.855 M290.207 252.760 C 316.765 259.678,323.392 292.263,301.575 308.656 C 283.142 322.507,256.557 311.347,252.282 287.964 C 248.462 267.069,269.646 247.405,290.207 252.760 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        {{-- Delete Button --}}
                                        <button
                                            @if ($this->batch->approval_status !== 'approved') @click="deleteBeneficiaryModal = !deleteBeneficiaryModal;"
                                        @else
                                        disabled @endif
                                            class="rounded text-sm font-bold flex items-center justify-center p-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 focus:bg-red-800 focus:ring-2 focus:ring-red-300 duration-200 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded relative bg-white p-4 h-[89vh] flex items-center justify-center">
                            <div
                                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-12 sm:size-20 mb-4 text-green-900 opacity-65"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M157.812 1.758 C 152.898 5.112,152.344 7.271,152.344 23.047 C 152.344 35.256,152.537 37.497,153.790 39.856 C 158.280 48.306,170.943 48.289,175.194 39.828 C 177.357 35.523,177.211 9.277,175.004 5.657 C 171.565 0.017,163.157 -1.890,157.812 1.758 M92.282 29.461 C 81.984 34.534,84.058 43.360,98.976 57.947 C 111.125 69.826,115.033 71.230,122.082 66.248 C 130.544 60.266,128.547 52.987,114.703 39.342 C 102.476 27.292,99.419 25.945,92.282 29.461 M224.609 29.608 C 220.914 31.937,204.074 49.371,203.164 51.809 C 199.528 61.556,208.074 71.025,217.862 68.093 C 222.301 66.763,241.856 46.745,242.596 42.773 C 244.587 32.094,233.519 23.992,224.609 29.608 M155.754 71.945 C 151.609 73.146,145.829 77.545,143.171 81.523 C 138.040 89.200,138.281 84.305,138.281 180.886 L 138.281 268.519 136.523 271.102 C 131.545 278.417,122.904 278.656,117.660 271.624 C 116.063 269.483,116.004 268.442,115.625 235.830 L 115.234 202.240 109.681 206.141 C 92.677 218.084,88.279 229.416,88.286 261.258 C 88.297 310.416,101.114 335.739,136.914 357.334 C 138.733 358.431,139.063 359.154,139.063 362.045 C 139.063 377.272,152.803 393.856,169.478 398.754 C 175.500 400.522,274.549 400.621,281.147 398.865 C 300.011 393.844,312.500 376.696,312.500 355.816 L 312.500 350.200 317.647 344.827 C 338.941 322.596,341.616 310.926,341.256 241.797 L 341.016 195.703 338.828 191.248 C 329.203 171.647,301.256 172.127,292.338 192.045 L 290.848 195.375 290.433 190.802 C 288.082 164.875,250.064 160.325,241.054 184.892 L 239.954 187.891 239.903 183.594 C 239.599 158.139,203.249 149.968,191.873 172.797 L 189.906 176.743 189.680 133.489 L 189.453 90.234 187.359 85.765 C 181.948 74.222,168.375 68.287,155.754 71.945 M64.062 96.289 C 56.929 101.158,56.929 111.342,64.062 116.211 C 68.049 118.932,96.783 118.920,100.861 116.195 C 108.088 111.368,107.944 100.571,100.593 96.090 C 96.473 93.578,67.805 93.734,64.062 96.289 M228.125 96.289 C 224.932 98.468,222.656 102.614,222.656 106.250 C 222.656 109.886,224.932 114.032,228.125 116.211 C 232.111 118.932,260.845 118.920,264.924 116.195 C 272.150 111.368,272.006 100.571,264.656 96.090 C 260.536 93.578,231.867 93.734,228.125 96.289 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                                <p>No preview.</p>
                                <p>Try <span class="underline underline-offset-2">clicking</span> one of the <span
                                        class="text-green-900">beneficiaries</span> row.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Add Beneficiaries Modal --}}
        <livewire:barangay.listing-page.add-beneficiaries-modal :$batchId />

        {{-- Edit Beneficiary Modal --}}
        <livewire:barangay.listing-page.edit-beneficiary-modal :$beneficiaryId />

        {{-- View Credentials Modal --}}
        <livewire:barangay.listing-page.view-credentials-modal :$credentialId />

        {{-- Delete Beneficiary Modal --}}
        <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
            x-show="deleteBeneficiaryModal">

            <!-- Modal -->
            <div x-show="deleteBeneficiaryModal" x-trap.noautofocus.noscroll="deleteBeneficiaryModal"
                class="min-h-screen p-4 flex items-center justify-center overflow-y-auto z-50 select-none">

                {{-- The Modal --}}
                <div class="relative">
                    <div class="relative bg-white rounded-md shadow overflow-hidden">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                            <h1 class="text-sm sm:text-base font-semibold text-green-1100">
                                Delete Beneficiary
                            </h1>

                            {{-- Close Button --}}
                            <button type="button" @click="deleteBeneficiaryModal = false;"
                                class="outline-none text-green-400 hover:bg-green-200 hover:text-green-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close Modal</span>
                            </button>
                        </div>

                        <hr class="">

                        {{-- Modal body --}}
                        <div class="grid w-full place-items-center pt-5 pb-10 px-8 md:px-16 text-green-1100 text-xs">

                            <p class="font-medium text-sm mb-2">
                                Are you sure about deleting this beneficiary?
                            </p>
                            <p class="text-gray-500 text-xs font-semibold mb-4">
                                This is action is irreversible
                            </p>

                            <div class="flex items-center justify-center w-full gap-2">
                                <button type="button"
                                    class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-green-700 hover:border-transparent active:border-transparent hover:bg-green-800 active:bg-green-900 text-green-700 hover:text-green-50 active:text-green-50"
                                    @click="deleteBeneficiaryModal = false;">
                                    CANCEL
                                </button>
                                <button type="button"
                                    class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50"
                                    @click="$wire.deleteBeneficiary(); deleteBeneficiaryModal = false;">
                                    CONFIRM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Batch Modal --}}
        <div x-cloak>
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="submitBatchModal">
            </div>

            <!-- Modal -->
            <div x-show="submitBatchModal" x-trap.noautofocus.noscroll="submitBatchModal"
                class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                {{-- The Modal --}}
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-md shadow">

                        <!-- Modal Header -->
                        <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                            <h1 class="text-sm sm:text-base font-semibold text-green-1100">Submit List
                            </h1>

                            <div class="flex items-center justify-center">
                                {{-- Loading State for Changes --}}
                                <div class="z-50 text-green-900" wire:loading
                                    wire:target="confirm_submit, submitBatch">
                                    <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>

                                {{-- Close Modal --}}
                                <button type="button" @click="$wire.resetConfirm(); submitBatchModal = false;"
                                    class="outline-none text-green-400 hover:bg-green-200 hover:text-green-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                    <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close Modal</span>
                                </button>
                            </div>
                        </div>

                        <hr class="">

                        {{-- Modal body --}}
                        <div class="grid w-full place-items-center pt-5 pb-10 px-3 md:px-16 text-xs">

                            <p class="mb-2 text-sm font-medium text-green-1100">Are you sure about submitting this
                                list?
                            </p>
                            <p class="mb-4 text-xs font-medium text-gray-500">You won't be able to access this until
                                a coordinator opens it again.
                            </p>

                            <div class="relative flex items-center justify-center w-full gap-2">
                                <div class="relative">
                                    <input type="text" id="confirm_submit" wire:model.blur="confirm_submit"
                                        autocomplete="off"
                                        class="flex flex-1 {{ $errors->has('confirm_submit') ? 'caret-red-900 border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'caret-green-900 border-green-300 focus:border-green-500 bg-green-50 focus:ring-0' }} rounded outline-none border py-2.5 text-sm select-all duration-200 ease-in-out"
                                        placeholder="Type CONFIRM to continue">
                                    @error('confirm_submit')
                                        <p class="absolute top-full left-0 mt-1 text-xs text-red-700">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <button wire:loading.attr="disabled" wire:target="submitBatch"
                                    class="flex items-center justify-center disabled:bg-green-300 bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50 py-2.5 px-2 rounded text-sm font-bold duration-200 ease-in-out"
                                    wire:click="submitBatch">
                                    CONFIRM
                                </button>
                            </div>
                        </div>
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
            class="fixed left-6 bottom-6 z-50 flex items-center border bg-green-200 text-green-1000 border-green-300 rounded-lg text-sm sm:text-md font-bold px-4 py-3 select-none"
            role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                    clip-rule="evenodd" />
            </svg>
            <p x-text="successMessage"></p>
        </div>
    </div>
</div>
