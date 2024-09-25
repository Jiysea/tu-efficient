<x-slot:favicons>
    <x-b-favicons />
</x-slot>

<div>
    <div x-data="{
        addBeneficiariesModal: $wire.entangle('addBeneficiariesModal'),
        beneficiaryDeleteModal: $wire.entangle('beneficiaryDeleteModal'),
        submitBatchModal: $wire.entangle('submitBatchModal'),
    
    }" class="p-2 min-h-screen select-none text-green-1100">
        {{-- App Name | Submission Type | Submit Button --}}
        <div class="relative flex items-center justify-between mb-2">
            <div class="flex items-center justify-between">

                <img class="rounded-lg object-contain drop-shadow size-11 ms-3 duration-500 ease-in-out select-none"
                    src="{{ asset('assets/b_logo.png') }}" alt="TU-Efficient Barangay logo">
                <h1 class="text-3xl font-bold ms-3 text-green-900 drop-shadow">TU-EFFICIENT</h1>

                @if ($this->batch->submission_status === 'encoding')
                    <span class="text-sm bg-green-300 text-green-900 rounded-md px-2 py-1 ms-3 font-bold">For
                        Submission
                    </span>
                @elseif ($this->batch->submission_status === 'revalidate')
                    <span class="text-sm bg-red-300 text-red-900 rounded-md px-2 py-1 ms-3 font-bold">For
                        Revalidation
                    </span>
                @endif

            </div>
            <div class="flex items-center justify-end">
                {{-- Loading State --}}
                <div class="items-center justify-center z-50 text-green-900 me-3" wire:loading>
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
                <button type="button" id="submitBatchButton" @click="submitBatchModal = true;"
                    class="flex items-center text-xs sm:text-sm bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 rounded-md px-4 py-2 me-3 font-bold">
                    SUBMIT
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 ms-2">
                        <path
                            d="M11.47 1.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1-1.06 1.06l-1.72-1.72V7.5h-1.5V4.06L9.53 5.78a.75.75 0 0 1-1.06-1.06l3-3ZM11.25 7.5V15a.75.75 0 0 0 1.5 0V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-3 pb-3 lg:px-0 lg:pb-0 relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-7">

            {{-- List of Beneficiaries --}}
            <div class="lg:col-span-4 w-full h-[89vh] bg-white rounded">

                {{-- Upper/Header --}}
                <div class="flex flex-col gap-y-4 sm:gap-0 sm:flex-row sm:items-end sm:justify-between mb-2">

                    {{-- Batch Information --}}
                    <div class="flex flex-1 flex-col justify-center">

                        {{-- Header Title --}}
                        <span class="flex items-center justify-start font-bold text-lg my-2 ms-2 text-green-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 me-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="384.37499999999994"
                                viewBox="0, 0, 400,384.37499999999994">
                                <g>
                                    <path
                                        d="M188.621 32.904 C 122.999 37.683,93.854 121.545,141.940 167.222 C 185.162 208.279,257.008 188.004,271.559 130.643 C 285.028 77.544,243.742 28.889,188.621 32.904 M79.688 51.207 C 16.861 64.602,13.468 152.666,75.034 171.999 C 84.572 174.994,110.462 174.174,113.867 170.769 C 114.020 170.615,112.507 167.957,110.504 164.860 C 89.737 132.758,89.513 87.775,109.967 56.868 C 112.481 53.068,112.054 52.632,104.375 51.162 C 96.938 49.739,86.481 49.758,79.688 51.207 M286.722 51.224 C 279.140 52.867,279.287 52.749,281.208 55.668 C 302.425 87.895,302.275 133.700,280.847 165.983 C 279.243 168.400,278.062 170.503,278.223 170.656 C 279.694 172.051,288.669 173.657,296.875 173.992 C 349.201 176.132,380.193 118.210,349.635 75.386 C 335.884 56.115,310.008 46.177,286.722 51.224 M78.125 197.363 C 30.517 203.239,-3.719 231.505,0.552 261.411 C 3.121 279.401,17.880 290.813,45.505 296.168 C 55.988 298.201,55.172 298.551,55.787 291.760 C 58.875 257.683,91.117 224.054,134.153 210.024 C 143.661 206.924,143.639 206.969,136.762 204.420 C 121.291 198.685,94.013 195.403,78.125 197.363 M281.250 198.000 C 270.588 199.536,256.843 203.217,251.293 206.024 C 249.071 207.148,249.074 207.149,257.152 209.886 C 303.683 225.646,336.719 262.029,336.719 297.514 C 336.719 299.005,360.300 293.209,367.458 289.958 C 409.932 270.672,394.814 221.464,340.868 203.412 C 323.491 197.598,299.294 195.401,281.250 198.000 M183.203 223.435 C 124.333 227.701,78.906 260.575,78.906 298.910 C 78.906 335.079,115.408 351.618,195.192 351.600 C 271.127 351.583,306.832 338.145,312.435 307.474 C 321.082 260.128,256.489 218.123,183.203 223.435 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                            List of Beneficiaries
                        </span>

                        {{-- Access Code | Coordinator | Location --}}
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
                    <div class="flex items-center justify-end mx-2">

                        {{-- Search Bar --}}
                        <div class="relative me-2">
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
                                    wire:loading.class="hidden" wire:target="searchBeneficiaries" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="beneficiary-search" maxlength="100" autocomplete="off"
                                @input.debounce.300ms="$wire.set('searchBeneficiaries', $el.value); $wire.$refresh();"
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
                        class="relative h-[72.5vh] overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-green-50 scrollbar-thumb-green-700">
                        <table class="relative w-full text-sm text-left text-green-1100">
                            <thead
                                class="text-xs z-20 text-green-50 uppercase bg-green-600 sticky top-0 whitespace-nowrap">
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

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y">
                                @foreach ($this->beneficiaries as $key => $beneficiary)
                                    <tr wire:key="beneficiary-{{ $key }}"
                                        wire:loading.class="pointer-events-none"
                                        wire:click.prevent="selectBeneficiaryRow({{ $key }})"
                                        class="relative {{ $selectedBeneficiaryRow === $key ? 'bg-gray-100 text-green-1000 hover:bg-gray-200' : ' hover:bg-gray-50' }} divide-x whitespace-nowrap cursor-pointer">
                                        <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="px-2">
                                            {{ $beneficiary->first_name }}
                                        </td>
                                        <td class="px-2">
                                            {{ $beneficiary->middle_name ?? '-' }}
                                        </td>
                                        <td class="px-2">
                                            {{ $beneficiary->last_name }}
                                        </td>
                                        <td class="px-2">
                                            {{ $beneficiary->extension_name ?? '-' }}
                                        </td>
                                        <td class="px-2">
                                            {{ \Carbon\Carbon::parse($beneficiary->birthdate)->format('M d, Y') }}
                                        </td>
                                        <td class="px-2">
                                            {{ $beneficiary->contact_num }}
                                        </td>
                                        <td class="flex items-center justify-center py-1">

                                            {{-- Edit Beneficiary Button --}}
                                            <button id="editBeneficiaryButton-{{ $key }}" @click.stop=""
                                                class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBeneficiaryRow === $key ? 'hover:bg-amber-700 focus:bg-amber-700 text-amber-700 hover:text-amber-50 focus:text-amber-50' : 'text-gray-900 hover:text-amber-700 focus:text-amber-700 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </button>

                                            {{-- Delete Beneficiary Button --}}
                                            <button id="deleteBeneficiaryButton-{{ $key }}"
                                                @click.stop="$wire.openDeleteModal('{{ encrypt($beneficiary->id) }}')"
                                                class="flex items-center justify-center z-0 mx-1 p-1 font-medium rounded outline-none duration-200 ease-in-out {{ $selectedBeneficiaryRow === $key ? 'hover:bg-red-700 focus:bg-red-700 text-red-700 hover:text-red-50 focus:text-red-50' : 'text-gray-900 hover:text-red-700 focus:text-red-700 hover:bg-gray-300 focus:bg-gray-300' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
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
                    <div class="flex items-center justify-center h-[72.5vh] bg-white px-4 pb-4 pt-2 min-w-full">
                        <div
                            class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
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
                        </div>
                    </div>
                @endif
            </div>

            {{-- Beneficiary Profile Overview --}}
            <div class=" lg:col-span-3 w-full h-[89vh] bg-white rounded">
                @if ($selectedBeneficiaryRow !== -1)
                    <div class="relative grid grid-cols-3 h-full place-content-start gap-x-4 gap-y-2 rounded">

                        {{-- ID Picture --}}
                        <div
                            class="relative flex items-center justify-center col-span-1 my-2 mx-2 bg-green-50 border-green-300 border-dashed border-2 w-[90%] aspect-square rounded-md">
                            @if ($this->proofImages['identity'])
                                <img src="{{ asset('storage/' . $this->proofImages['identity']) }}"
                                    class="size-[95%] rounded" alt="ID Picture">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-[95%] rounded"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M107.422 50.878 C 79.094 54.549,57.713 74.036,51.814 101.563 C 49.620 111.800,49.620 288.200,51.814 298.438 C 57.220 323.662,76.338 342.780,101.563 348.186 C 107.773 349.517,114.149 349.609,200.000 349.609 C 285.851 349.609,292.227 349.517,298.438 348.186 C 323.662 342.780,342.780 323.662,348.186 298.438 C 350.380 288.200,350.380 111.800,348.186 101.563 C 342.861 76.716,324.200 57.775,299.219 51.860 C 292.608 50.294,118.792 49.405,107.422 50.878 M283.372 84.383 C 295.540 85.460,299.847 87.205,306.321 93.679 C 315.819 103.176,316.386 107.330,316.398 167.420 L 316.406 208.669 313.086 206.393 C 290.258 190.744,266.010 193.819,243.963 215.159 C 238.678 220.274,234.240 224.317,234.100 224.144 C 220.448 207.251,185.837 166.529,182.862 163.858 C 168.386 150.865,145.748 148.079,127.547 157.051 C 119.004 161.262,114.813 165.299,98.040 185.480 L 83.984 202.389 83.754 165.062 C 83.406 108.493,84.139 103.218,93.679 93.679 C 99.894 87.463,104.758 85.373,115.176 84.442 C 125.621 83.509,272.912 83.457,283.372 84.383 M227.937 133.924 C 211.002 139.968,213.315 164.176,231.085 166.867 C 241.190 168.397,250.000 160.541,250.000 150.000 C 250.000 137.987,239.004 129.974,227.937 133.924 M156.764 187.447 C 159.428 188.657,164.587 194.405,185.420 219.379 C 212.037 251.287,213.239 252.533,220.736 255.991 C 235.489 262.795,247.798 259.174,264.151 243.218 C 281.729 226.068,285.035 226.261,304.492 245.576 L 316.406 257.403 316.398 265.616 C 316.363 301.864,308.764 313.369,283.372 315.617 C 271.802 316.641,128.198 316.641,116.628 315.617 C 91.083 313.356,83.659 302.019,83.606 265.193 L 83.594 256.558 111.781 222.744 C 144.714 183.237,145.732 182.438,156.764 187.447 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="absolute -bottom-1 -right-1 w-8 p-1 rounded-full bg-white stroke-green-1000">
                                <path fill-rule="evenodd"
                                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Zm8.25-3.75a.75.75 0 0 1 .75.75v2.25h2.25a.75.75 0 0 1 0 1.5h-2.25v2.25a.75.75 0 0 1-1.5 0v-2.25H7.5a.75.75 0 0 1 0-1.5h2.25V7.5a.75.75 0 0 1 .75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        {{-- Name and Basic Info --}}
                        <div class="col-span-2 col-start-2">
                            <h1 class="text-lg truncate font-medium mt-2 mb-2 text-green-1100">
                                {{ $this->full_name($selectedBeneficiaryRow) }}</h1>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Birthdate</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ date('F j, Y', strtotime($this->beneficiaries[$selectedBeneficiaryRow]->birthdate)) }}
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Occupation</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->occupation ?? 'None' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Age</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->age }}</p>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Sex</h4>
                                    <p class="text-xs text-green-1100 capitalize">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->sex }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Civil Status</h4>
                                    <p class="text-xs text-green-1100 capitalize">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->civil_status }}</p>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Contact Number</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->contact_num }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- Identification --}}
                        <div class="col-span-1 ml-2 mr-2">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <hr class="w-full border-gray-600">
                                </div>
                                <div class="relative flex justify-start text-sm">
                                    <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                                        IDENTIFICATION
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">ID Type</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->type_of_id }}</p>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">ID Number</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->id_number }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- Additional Information --}}
                        <div class="col-span-2 col-start-2 mr-2">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <hr class="w-full border-gray-600">
                                </div>
                                <div class="relative flex justify-start text-sm">
                                    <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                                        ADDITIONAL INFORMATION
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight ">Type of Beneficiary
                                    </h4>
                                    <p class="text-xs text-green-1100 capitalize">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->beneficiary_type }}</p>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Dependent</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->dependent ?? 'None' }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight ">Avg. Monthly Income
                                    </h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->avg_monthly_income ? '-' : '₱' . $this->beneficiaries[$selectedBeneficiaryRow]->avg_monthly_income }}
                                    </p>
                                </div>

                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Skills Training</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->skills_training ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight ">Interested in
                                        <br>Self-Employment
                                    </h4>
                                    <p class="text-xs text-green-1100 capitalize">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->self_employment }}</p>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight ">e-Payment
                                        <br>Account
                                        Number
                                    </h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->e_payment_acc_num ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- Address --}}
                        <div class="col-span-1 ml-2 mr-2">
                            <div class="relative ">
                                <div class="absolute inset-0 flex items-center">
                                    <hr class="w-full border-gray-600">
                                </div>
                                <div class="relative flex justify-start text-sm">
                                    <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                                        ADDRESS
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">City/Municipality
                                    </h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->city_municipality }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Province</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->province }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">District</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->district }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- Spouse Information --}}
                        <div class="col-span-2 col-start-2 mr-2">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <hr class="w-full border-gray-600">
                                </div>
                                <div class="relative flex justify-start text-sm">
                                    <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                                        SPOUSE INFORMATION
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">First Name</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_first_name ?? '-' }}
                                    </p>
                                </div>


                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Extension Name</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_extension_name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight">Middle Name</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_middle_name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="mt-2">
                                    <h4 class="text-sm font-medium text-green-1000 leading-tight ">Last Name</h4>
                                    <p class="text-xs text-green-1100">
                                        {{ $this->beneficiaries[$selectedBeneficiaryRow]->spouse_last_name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center h-full p-4 min-w-full">
                        <div
                            class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
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
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{--  
            
        ###--- Modals Section ---###
            
        --}}

        {{-- Add Beneficiaries Modal --}}
        <div x-cloak>
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="addBeneficiariesModal">
            </div>

            <!-- Modal -->
            <div x-show="addBeneficiariesModal" x-trap.noscroll="addBeneficiariesModal"
                class="fixed inset-0 pt-4 px-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                {{-- The Modal --}}
                <div class="relative w-full max-w-7xl max-h-full">
                    <div class="relative bg-white rounded-md shadow">

                        <!-- Modal header -->
                        <div class="flex items-center justify-between py-2 px-4 rounded-t">
                            <h1 class="text-lg font-semibold text-green-1100">
                                Add New Beneficiary
                            </h1>
                            <div class="flex items-center justify-center">
                                {{-- Loading State for Changes --}}
                                <div class="z-50 text-green-900" wire:loading wire:target="nameCheck">
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

                                {{-- Close X Button --}}
                                <button id="closeAddButton" type="button"
                                    @click="addBeneficiariesModal = !addBeneficiariesModal;"
                                    class="text-green-400 bg-transparent hover:bg-green-200 hover:text-green-900 rounded size-8 inline-flex justify-center items-center outline-none duration-200 ease-in-out">
                                    <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                        </div>

                        <hr class="">

                        <form wire:submit.prevent="saveBeneficiary" class="p-5">
                            <div class="grid gap-4 sm:gap-2 grid-cols-10 text-xs">

                                {{-- Similarity Results --}}
                                <div x-data="{ isResolved: $wire.entangle('isResolved'), isPerfectDuplicate: $wire.entangle('isPerfectDuplicate') }" class="relative col-span-full mb-2">
                                    @if ($isPerfectDuplicate)
                                        <div class="flex items-center justify-between border rounded text-xs p-2 duration-200 ease-in-out"
                                            :class="{
                                                'border-red-300 bg-red-50 text-red-900': isPerfectDuplicate && !
                                                    isResolved,
                                                'border-green-300 bg-green-50 text-green-900': isResolved,
                                            }">

                                            <p x-show="!isResolved" class="inline mx-2">This
                                                beneficiary has
                                                already
                                                been listed in the
                                                database this
                                                year.
                                            </p>

                                            <p x-show="isResolved" class="inline mx-2">Possible duplication is
                                                resolved.
                                            </p>

                                            @if ($beneficiary_type === 'Special Case')
                                                <div x-data="{ addReasonModal: $wire.entangle('addReasonModal') }">

                                                    <button x-show="!isResolved" type="button"
                                                        @click="addReasonModal = !addReasonModal"
                                                        class="px-2 py-1 rounded font-bold text-xs"
                                                        :class="{
                                                            ' bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50': isPerfectDuplicate,
                                                            ' bg-amber-700 hover:bg-amber-800 active:bg-amber-900 text-amber-50':
                                                                !
                                                                isPerfectDuplicate,
                                                        
                                                        }">ADD
                                                        REASON
                                                    </button>

                                                    <button x-show="isResolved" type="button"
                                                        @click="addReasonModal = !addReasonModal"
                                                        class="px-2 py-1 rounded font-bold text-xs bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">VIEW
                                                        REASON
                                                    </button>

                                                    {{-- Hard-coded Add Reason because the nesting doesn't work! --}}
                                                    <div x-cloak>
                                                        <!-- Modal Backdrop -->
                                                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                                            x-show="addReasonModal">
                                                        </div>

                                                        <!-- Modal -->
                                                        <div x-show="addReasonModal" x-trap.noscroll="addReasonModal"
                                                            class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                                                            {{-- The Modal --}}
                                                            <div class="relative w-full max-w-3xl max-h-full">
                                                                <div class="relative bg-white rounded-md shadow">
                                                                    <form wire:submit.prevent="saveReason">
                                                                        <!-- Modal Header -->
                                                                        <div
                                                                            class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                                            <span
                                                                                class="flex items-center justify-center">
                                                                                <h1
                                                                                    class="text-sm sm:text-base font-semibold text-green-1100">
                                                                                    Add Reason
                                                                                </h1>
                                                                            </span>

                                                                            <div
                                                                                class="flex items-center justify-center">
                                                                                {{-- Loading State for Changes --}}
                                                                                <div class="z-50 text-green-900"
                                                                                    wire:loading>
                                                                                    <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                        fill="none"
                                                                                        viewBox="0 0 24 24">
                                                                                        <circle class="opacity-25"
                                                                                            cx="12"
                                                                                            cy="12" r="10"
                                                                                            stroke="currentColor"
                                                                                            stroke-width="4">
                                                                                        </circle>
                                                                                        <path class="opacity-75"
                                                                                            fill="currentColor"
                                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </div>
                                                                                <button type="button"
                                                                                    @click="addReasonModal = false;"
                                                                                    class="outline-none text-green-400 hover:bg-green-200 hover:text-green-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                                                                    <svg class="size-3"
                                                                                        aria-hidden="true"
                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                        fill="none"
                                                                                        viewBox="0 0 14 14">
                                                                                        <path stroke="currentColor"
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                                    </svg>
                                                                                    <span class="sr-only">Close
                                                                                        Modal</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                        <hr class="">

                                                                        {{-- Modal Body --}}
                                                                        <div
                                                                            class="pt-5 pb-6 px-3 md:px-12 text-green-1100 text-xs">
                                                                            <div
                                                                                class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                                                                                {{-- Case Proof --}}
                                                                                <div
                                                                                    class="relative col-span-full sm:col-span-1 pb-4">
                                                                                    <div
                                                                                        class="flex flex-col items-start">
                                                                                        <div class="flex items-center">
                                                                                            <p
                                                                                                class="inline mb-1 font-medium text-green-1100">
                                                                                                Case Proof <span
                                                                                                    class="text-red-700 font-normal text-xs">*</span>
                                                                                            </p>
                                                                                        </div>

                                                                                        {{-- Image Area --}}
                                                                                        <label
                                                                                            for="reason_image_file_path"
                                                                                            class="{{ $errors->has('reason_image_file_path') ? 'border-red-300 bg-red-50 text-red-500' : 'border-green-300 bg-green-50 text-gray-500' }} flex flex-col items-center justify-center w-full h-full border-2 border-dashed rounded cursor-pointer">

                                                                                            {{-- Image Preview --}}
                                                                                            <div
                                                                                                class="relative flex flex-col items-center justify-center w-full h-full aspect-square">

                                                                                                {{-- Loading State for Changes --}}
                                                                                                <div class="absolute flex items-center justify-center w-full h-full z-50 text-green-900"
                                                                                                    wire:loading.flex
                                                                                                    wire:target="reason_image_file_path">
                                                                                                    <div
                                                                                                        class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
                                                                                                        {{-- Darkness... --}}
                                                                                                    </div>

                                                                                                    {{-- Loading Circle --}}
                                                                                                    <svg class="size-6 animate-spin"
                                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                                        fill="none"
                                                                                                        viewBox="0 0 24 24">
                                                                                                        <circle
                                                                                                            class="opacity-25"
                                                                                                            cx="12"
                                                                                                            cy="12"
                                                                                                            r="10"
                                                                                                            stroke="currentColor"
                                                                                                            stroke-width="4">
                                                                                                        </circle>
                                                                                                        <path
                                                                                                            class="opacity-75"
                                                                                                            fill="currentColor"
                                                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                                        </path>
                                                                                                    </svg>
                                                                                                </div>

                                                                                                {{-- Preview --}}
                                                                                                @if ($reason_image_file_path && !$errors->has('reason_image_file_path'))
                                                                                                    <img class="size-28"
                                                                                                        src="{{ $reason_image_file_path->temporaryUrl() }}">

                                                                                                    {{-- Default --}}
                                                                                                @else
                                                                                                    <svg class="size-8 mb-4"
                                                                                                        aria-hidden="true"
                                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                                        fill="none"
                                                                                                        viewBox="0 0 20 16">
                                                                                                        <path
                                                                                                            stroke="currentColor"
                                                                                                            stroke-linecap="round"
                                                                                                            stroke-linejoin="round"
                                                                                                            stroke-width="2"
                                                                                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                                                                    </svg>
                                                                                                    <p
                                                                                                        class="mb-2 text-xs">
                                                                                                        <span
                                                                                                            class="font-semibold">Click
                                                                                                            to
                                                                                                            upload</span>
                                                                                                        or
                                                                                                        drag and
                                                                                                        drop
                                                                                                    </p>
                                                                                                    <p class="text-xs">
                                                                                                        PNG or JPG (MAX.
                                                                                                        5MB)</p>
                                                                                                @endif
                                                                                            </div>

                                                                                            {{-- The Image itself --}}
                                                                                            <input
                                                                                                id="reason_image_file_path"
                                                                                                wire:model="reason_image_file_path"
                                                                                                type="file"
                                                                                                accept=".png,.jpg,.jpeg"
                                                                                                class="hidden" />
                                                                                        </label>
                                                                                    </div>
                                                                                    @error('reason_image_file_path')
                                                                                        <p
                                                                                            class="text-center whitespace-nowrap w-full text-red-500 mt-1 z-10 text-xs">
                                                                                            {{ $message }}</p>
                                                                                    @enderror
                                                                                </div>

                                                                                {{-- Image Description --}}
                                                                                <div
                                                                                    class="relative flex flex-col justify-between col-span-full sm:col-span-2 pb-4">
                                                                                    <div class="flex flex-col">
                                                                                        <label for="image_description"
                                                                                            class="block mb-1 font-medium text-green-1100 ">Description
                                                                                            <span
                                                                                                class="text-red-700 font-normal text-xs">*</span></label>
                                                                                        <textarea type="text" id="image_description" autocomplete="off" wire:model.blur="image_description"
                                                                                            maxlength="255" rows="4"
                                                                                            class="resize-none h-full text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('image_description') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                                                                            placeholder="What is the reason for this special case?"></textarea>

                                                                                        @error('image_description')
                                                                                            <p
                                                                                                class="text-red-500 whitespace-nowrap w-full mt-1 z-10 text-xs">
                                                                                                {{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div
                                                                                        class="flex justify-end w-full">
                                                                                        <button type="button"
                                                                                            wire:click.prevent="saveReason"
                                                                                            class="px-2 py-1 rounded bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50 font-bold text-lg">
                                                                                            CONFIRM
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="inline mx-2">Not a mistake?
                                                    Change the
                                                    <span class="font-medium">Type of Beneficiary</span> to
                                                    <strong class="underline underline-offset-2">Special Case</strong>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- First Name --}}
                                <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                                    <label for="first_name" class="block mb-1 font-medium text-green-1100 ">First
                                        Name <span class="text-red-700 font-normal text-xs">*</span></label>
                                    <input type="text" id="first_name" autocomplete="off"
                                        @blur="$wire.set('first_name', $el.value); $wire.nameCheck();"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                        placeholder="Type first name">
                                    @error('first_name')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Middle Name --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="middle_name" class="block mb-1  font-medium text-green-1100 ">Middle
                                        Name</label>
                                    <input type="text" id="middle_name" autocomplete="off"
                                        @blur="$wire.set('middle_name', $el.value); $wire.nameCheck();"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                        placeholder="(optional)">
                                    @error('middle_name')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Last Name --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="last_name" class="block mb-1  font-medium text-green-1100 ">Last Name
                                        <span class="text-red-700 font-normal text-xs">*</span></label>
                                    <input type="text" id="last_name" autocomplete="off"
                                        @blur="$wire.set('last_name', $el.value); $wire.nameCheck();"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                        placeholder="Type last name">
                                    @error('last_name')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Extension Name --}}
                                <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                                    <label for="extension_name" class="block mb-1 font-medium text-green-1100 ">Ext.
                                        Name</label>
                                    <input type="text" id="extension_name" autocomplete="off"
                                        @blur="$wire.set('extension_name', $el.value); $wire.nameCheck();"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                        placeholder="III, Sr., etc.">
                                    @error('extension_name')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Birthdate --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="birthdate" class="block mb-1  font-medium text-green-1100 ">Birthdate
                                        <span class="text-red-700 font-normal text-xs">*</span></label>
                                    <div
                                        class="absolute start-0 bottom-3.5 flex items-center ps-3 pointer-events-none">
                                        <svg class="size-4 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'text-red-700' : 'text-green-900' }}"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input datepicker datepicker-autohide datepicker-format="mm-dd-yyyy"
                                        datepicker-min-date='{{ $minDate }}'
                                        datepicker-max-date='{{ $maxDate }}' id="birthdate" autocomplete="off"
                                        class="text-xs border outline-none rounded block w-full py-2 ps-9 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                        placeholder="Select date">
                                    @error('birthdate')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Contact Number --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="contact_num" class="block mb-1 font-medium text-green-1100 ">Contact
                                        Number <span class="text-red-700 font-normal text-xs">*</span></label>
                                    <div {{-- x-effect="console.log(unmaskedBudget)" --}} class="relative">
                                        <div
                                            class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('contact_num') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-green-700 text-green-50' }}">
                                            <p
                                                class="flex text-center w-full relative items-center justify-center font-medium">
                                                +63
                                            </p>
                                        </div>
                                        <input x-mask="99999999999" type="text" inputmode="numeric"
                                            min="0" autocomplete="off" id="contact_num"
                                            @blur="$wire.set('contact_num', $el.value);"
                                            class="text-xs outline-none border ps-12 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('contact_num') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50  border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                            placeholder="ex. 09123456789">
                                    </div>
                                    @error('contact_num')
                                        <p class="whitespace-nowrap text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- E-payment Account Number --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="e_payment_acc_num"
                                        class="block mb-1 font-medium text-green-1100 ">E-payment
                                        Account No.</label>
                                    <input type="text" id="e_payment_acc_num" autocomplete="off"
                                        wire:model.blur="e_payment_acc_num"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600"
                                        placeholder="Type e-payment account number">
                                </div>
                                {{-- Type of Beneficiary --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <p class="mb-1 font-medium text-green-1100">Type of Beneficiary</p>
                                    <div x-data="{
                                        open: false,
                                        beneficiary_type: $wire.entangle('beneficiary_type'),
                                        toggle() {
                                            if (this.open) {
                                                return this.close()
                                            }
                                    
                                            this.$refs.beneficiaryTypeButton.focus()
                                    
                                            this.open = true
                                        },
                                        close(focusAfter) {
                                            if (!this.open) return
                                    
                                            this.open = false
                                    
                                            focusAfter && focusAfter.focus()
                                        },
                                        selectOption(option) {
                                            this.beneficiary_type = option;
                                            this.close(this.$refs.beneficiaryTypeButton); // Close the dropdown after selecting an option
                                        }
                                    }"
                                        x-on:keydown.escape.prevent.stop="close($refs.beneficiaryTypeButton)"
                                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                        x-id="['beneficiary-type-button']" class="relative">
                                        <!-- Button -->
                                        <button x-ref="beneficiaryTypeButton" x-on:click="toggle()"
                                            :aria-expanded="open" :aria-controls="$id('beneficiary-type-button')"
                                            type="button"
                                            class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-green-50 border-green-300 text-green-1100 outline-green-300 focus:outline-green-600 focus:border-green-600">
                                            <span x-text="beneficiary_type"></span>
                                            <!-- Display selected option -->

                                            <!-- Heroicon: chevron-down -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-4 text-green-1100 group-hover:text-green-900 group-active:text-green-1000 duration-200 ease-in-out"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Panel -->
                                        <div x-ref="panel" x-show="open"
                                            x-on:click.outside="close($refs.beneficiaryTypeButton)"
                                            :id="$id('beneficiary-type-button')" style="display: none;"
                                            class="absolute left-0 mt-2 w-full z-50 rounded bg-green-50 shadow-lg border border-green-500">
                                            <button type="button" x-on:click="selectOption('Underemployed');"
                                                wire:click.prevent="$refresh"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Underemployed
                                            </button>

                                            <button type="button" x-on:click="selectOption('Special Case');"
                                                wire:click.prevent="$refresh"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Special Case
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Occupation --}}
                                <div x-data="{ avg: $wire.entangle('avg_monthly_income') }" class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="occupation"
                                        class="block mb-1  font-medium text-green-1100 ">Occupation <span
                                            x-show="avg" class="text-red-700 font-normal text-xs">*</span></label>
                                    <input type="text" id="occupation" autocomplete="off"
                                        wire:model.blur="occupation"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('occupation') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                        placeholder="Type occupation">
                                    @error('occupation')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Sex --}}
                                <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                                    <p class="mb-1 font-medium text-green-1100 ">Sex</p>
                                    <div x-data="{
                                        open: false,
                                        sex: $wire.entangle('sex'),
                                        toggle() {
                                            if (this.open) {
                                                return this.close()
                                            }
                                    
                                            this.$refs.sexButton.focus()
                                    
                                            this.open = true
                                        },
                                        close(focusAfter) {
                                            if (!this.open) return
                                    
                                            this.open = false
                                    
                                            focusAfter && focusAfter.focus()
                                        },
                                        selectOption(option) {
                                            this.sex = option;
                                            this.close(this.$refs.sexButton); // Close the dropdown after selecting an option
                                        }
                                    }"
                                        x-on:keydown.escape.prevent.stop="close($refs.sexButton)"
                                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                        x-id="['sex-button']" class="relative">
                                        <!-- Button -->
                                        <button x-ref="sexButton" x-on:click="toggle()" :aria-expanded="open"
                                            :aria-controls="$id('sex-button')" type="button"
                                            class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-green-50 border-green-300 text-green-1100 outline-green-300 focus:outline-green-600 focus:border-green-600">
                                            <span x-text="sex"></span> <!-- Display selected option -->

                                            <!-- Heroicon: chevron-down -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-4 text-green-1100 group-hover:text-green-900 group-active:text-green-1000 duration-200 ease-in-out"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Panel -->
                                        <div x-ref="panel" x-show="open"
                                            x-on:click.outside="close($refs.sexButton)" :id="$id('sex-button')"
                                            style="display: none;"
                                            class="absolute left-0 mt-2 w-full z-50 rounded bg-green-50 shadow-lg border border-green-500">
                                            <button type="button" x-on:click="selectOption('Male')"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Male
                                            </button>

                                            <button type="button" x-on:click="selectOption('Female')"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Female
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Civil Status --}}
                                <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                                    <p class="mb-1 font-medium text-green-1100 ">Civil Status</p>
                                    <div x-data="{
                                        open: false,
                                        civil_status: $wire.entangle('civil_status'),
                                        spouse_first_name: $wire.entangle('spouse_first_name'),
                                        spouse_middle_name: $wire.entangle('spouse_middle_name'),
                                        spouse_last_name: $wire.entangle('spouse_last_name'),
                                        spouse_extension_name: $wire.entangle('spouse_extension_name'),
                                        toggle() {
                                            if (this.open) {
                                                return this.close()
                                            }
                                    
                                            this.$refs.civilStatusButton.focus()
                                    
                                            this.open = true
                                        },
                                        close(focusAfter) {
                                            if (!this.open) return
                                    
                                            this.open = false
                                    
                                            focusAfter && focusAfter.focus()
                                        },
                                        selectOption(option) {
                                            this.civil_status = option;
                                    
                                            if (this.civil_status === 'Single') {
                                                this.spouse_first_name = null;
                                                this.spouse_middle_name = null;
                                                this.spouse_last_name = null;
                                                this.spouse_extension_name = null;
                                            }
                                            this.close(this.$refs.civilStatusButton); // Close the dropdown after selecting an option
                                        }
                                    }"
                                        x-on:keydown.escape.prevent.stop="close($refs.civilStatusButton)"
                                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                        x-id="['civil-status-button']" class="relative">
                                        <!-- Button -->
                                        <button x-ref="civilStatusButton" x-on:click="toggle()" :aria-expanded="open"
                                            :aria-controls="$id('civil_status-button')" type="button"
                                            class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-green-50 border-green-300 text-green-1100 outline-green-300 focus:outline-green-600 focus:border-green-600">
                                            <span x-text="civil_status"></span> <!-- Display selected option -->

                                            <!-- Heroicon: chevron-down -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-4 text-green-1100 group-hover:text-green-900 group-active:text-green-1000 duration-200 ease-in-out"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Panel -->
                                        <div x-ref="panel" x-show="open"
                                            x-on:click.outside="close($refs.civilStatusButton)"
                                            :id="$id('civil-status-button')" style="display: none;"
                                            class="absolute left-0 mt-2 w-full z-50 rounded bg-green-50 shadow-lg border border-green-500">
                                            <button type="button"
                                                x-on:click="selectOption('Single'); $wire.$refresh();"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Single
                                            </button>

                                            <button type="button"
                                                x-on:click="selectOption('Married'); $wire.$refresh();"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Married
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Average Monthly Income --}}
                                <div x-data="{
                                    budgetToFloat: null,
                                    budgetToInt: null,
                                    unmaskedBudget: null,
                                    occ: $wire.entangle('occupation')
                                }" class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="avg_monthly_income"
                                        class="block mb-1  font-medium text-green-1100 ">Average
                                        Monthly
                                        Income <span x-show="occ"
                                            class="text-red-700 font-normal text-xs">*</span></label></label>
                                    <div class="relative">
                                        <div
                                            class="text-sm duration-200 ease-in-out absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none {{ $errors->has('avg_monthly_income') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-green-700 text-green-50' }}">
                                            <p
                                                class="flex text-center w-full relative items-center justify-center font-medium">
                                                ₱
                                            </p>
                                        </div>
                                        <input x-mask:dynamic="$money($input)" type="text" min="0"
                                            autocomplete="off" id="avg_monthly_income"
                                            wire:model.blur="avg_monthly_income"
                                            class="text-xs outline-none border ps-10 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('avg_monthly_income') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50  border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                            placeholder="0.00">
                                    </div>
                                    @error('avg_monthly_income')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                {{-- Dependent --}}
                                <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                                    <div class="flex items-center">
                                        <label for="dependent"
                                            class="block mb-1 font-medium text-green-1100 ">Dependent</label>
                                        <p class="block mb-1 ms-2 text-gray-500 ">(must be 18+ years old)</p>
                                    </div>
                                    <input type="text" id="dependent" autocomplete="off"
                                        wire:model.blur="dependent"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600"
                                        placeholder="Type dependent's name">
                                </div>
                                {{-- Interested in Wage Employment or Self-Employment --}}
                                <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                                    <p class="mb-1 font-medium text-green-1100">Interested in Wage or
                                        Self-Employment</p>
                                    <div x-data="{
                                        open: false,
                                        self_employment: $wire.entangle('self_employment'),
                                        toggle() {
                                            if (this.open) {
                                                return this.close()
                                            }
                                    
                                            this.$refs.selfEmploymentButton.focus()
                                    
                                            this.open = true
                                        },
                                        close(focusAfter) {
                                            if (!this.open) return
                                    
                                            this.open = false
                                    
                                            focusAfter && focusAfter.focus()
                                        },
                                        selectOption(option) {
                                            this.self_employment = option;
                                            this.close(this.$refs.selfEmploymentButton); // Close the dropdown after selecting an option
                                        }
                                    }"
                                        x-on:keydown.escape.prevent.stop="close($refs.selfEmploymentButton)"
                                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                        x-id="['self-employment-button']" class="relative">
                                        <!-- Button -->
                                        <button x-ref="selfEmploymentButton" x-on:click="toggle()"
                                            :aria-expanded="open" :aria-controls="$id('self-employment-button')"
                                            type="button"
                                            class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-green-50 border-green-300 text-green-1100 outline-green-300 focus:outline-green-600 focus:border-green-600">
                                            <span x-text="self_employment"></span> <!-- Display selected option -->

                                            <!-- Heroicon: chevron-down -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-4 text-green-1100 group-hover:text-green-900 group-active:text-green-1000 duration-200 ease-in-out"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Panel -->
                                        <div x-ref="panel" x-show="open"
                                            x-on:click.outside="close($refs.selfEmploymentButton)"
                                            :id="$id('self-employment-button')" style="display: none;"
                                            class="absolute left-0 mt-2 w-full z-50 rounded bg-green-50 shadow-lg border border-green-500">
                                            <button type="button" x-on:click="selectOption('Yes')"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                Yes
                                            </button>

                                            <button type="button" x-on:click="selectOption('No')"
                                                class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Skills Training Needed --}}
                                <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                    <label for="skills_training"
                                        class="block mb-1  font-medium text-green-1100 ">Skills Training
                                        Needed</label>
                                    <input type="text" id="skills_training" autocomplete="off"
                                        wire:model.blur="skills_training"
                                        class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600"
                                        placeholder="Type skills">
                                </div>
                                {{-- Bottom Section --}}
                                <div class="relative grid gap-x-4 gap-y-2 grid-cols-10 col-span-full grid-rows-2">
                                    {{-- Proof of Identity --}}
                                    <div class="relative h-full col-span-full row-span-full sm:col-span-2">
                                        <div class="flex flex-col h-full items-start">
                                            <div class="flex items-center">
                                                <p class="inline mb-1 font-medium text-green-1100">Proof of Identity
                                                </p>
                                                {{-- Popover Thingy --}}
                                                <div x-data="{ pop: false }" class="relative flex items-center"
                                                    id="identity-question-mark">
                                                    <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-green-700"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        @mouseleave="pop = false;"
                                                        @mouseenter="setTimeout(() => {pop = true;}, 350);">
                                                        <path
                                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                                    </svg>
                                                    {{-- Popover --}}
                                                    <div id="identity-popover"
                                                        class="absolute -left-20 sm:left-0 bottom-full mb-2 z-50 text-xs whitespace-nowrap text-green-50 bg-green-900 rounded p-2 shadow"
                                                        x-show="pop">
                                                        It's basically an image of a beneficiary's ID card <br>
                                                        to prove that their identity is legitimate.
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Image Area --}}
                                            <label for="image_file_path"
                                                class="mb-1 flex flex-col items-center justify-center w-full h-full border-2 {{ $errors->has('image_file_path') ? 'text-red-500 border-red-300 bg-red-50' : 'text-gray-500 border-green-300 bg-green-50' }} border-dashed rounded cursor-pointer">

                                                {{-- Image Preview --}}
                                                <div
                                                    class="relative flex flex-col items-center justify-center w-full h-full">
                                                    {{-- Loading State for Changes --}}
                                                    <div class="absolute items-center justify-center w-full h-full z-50 text-green-900"
                                                        wire:loading.flex wire:target="image_file_path">
                                                        <div
                                                            class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
                                                            {{-- Darkness... --}}
                                                        </div>

                                                        {{-- Loading Circle --}}
                                                        <svg class="size-6 animate-spin"
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

                                                    {{-- Preview --}}
                                                    @if ($image_file_path && !$errors->has('image_file_path'))
                                                        <img class="size-28"
                                                            src="{{ $image_file_path->temporaryUrl() }}">

                                                        {{-- Default --}}
                                                    @else
                                                        <svg class="size-8 mt-2 mb-4" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                        </svg>
                                                        <p class="mb-2 text-xs"><span class="font-semibold">Click to
                                                                upload</span> or drag and drop</p>
                                                        <p class="mb-2 text-xs">PNG or JPG (MAX. 5MB)
                                                        </p>
                                                    @endif
                                                </div>

                                                {{-- The Image itself --}}
                                                <input id="image_file_path" wire:model="image_file_path"
                                                    type="file" accept=".png,.jpg,.jpeg" class="hidden" />
                                            </label>
                                        </div>
                                        @error('image_file_path')
                                            <p
                                                class="text-center whitespace-nowrap w-full text-red-500 absolute -bottom-4 z-10 text-xs">
                                                {{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- ID Type --}}
                                    <div class="relative col-span-full sm:col-span-4 sm:row-span-1 mb-4 pb-1">
                                        <p class="mb-1 font-medium text-green-1100 ">ID Type</p>
                                        <div x-data="{
                                            open: false,
                                            type_of_id: $wire.entangle('type_of_id'),
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
                                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-green-50 border-green-300 text-green-1100 outline-green-300 focus:outline-green-600 focus:border-green-600">
                                                <span x-text="type_of_id"></span> <!-- Display selected option -->

                                                <!-- Heroicon: chevron-down -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-4 text-green-1100 group-hover:text-green-900 group-active:text-green-1000 duration-200 ease-in-out"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown Content -->
                                            <div x-ref="panel" x-show="open" :id="$id('button')"
                                                style="display: none;"
                                                class="absolute left-0 mt-2 max-h-[10rem] w-full z-50 rounded bg-green-50 shadow-lg border overflow-y-scroll border-green-500 scrollbar-thin scrollbar-track-green-50 scrollbar-thumb-green-700">
                                                @if ($is_pwd === 'Yes')
                                                    <button type="button"
                                                        x-on:click="selectOption('Person\'s With Disability (PWD) ID')"
                                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                        Person's With Disability (PWD) ID
                                                    </button>
                                                @endif

                                                @if ($birthdate && strtotime($birthdate) < strtotime(\Carbon\Carbon::now()->subYears(60)))
                                                    <button type="button"
                                                        x-on:click="selectOption('Senior Citizen ID')"
                                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                        Senior Citizen ID
                                                    </button>
                                                @endif

                                                <button type="button" x-on:click="selectOption('e-Card / UMID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    e-Card / UMID
                                                </button>

                                                <button type="button" x-on:click="selectOption('Barangay ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Barangay ID
                                                </button>

                                                <button type="button" x-on:click="selectOption('Driver\'s License')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Driver's License
                                                </button>

                                                <button type="button" x-on:click="selectOption('Passport')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Passport
                                                </button>

                                                <button type="button" x-on:click="selectOption('Phil-health ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Phil-health ID
                                                </button>

                                                <button type="button"
                                                    x-on:click="selectOption('Philippine Postal ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Philippine Postal ID
                                                </button>

                                                <button type="button" x-on:click="selectOption('SSS ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    SSS ID
                                                </button>

                                                <button type="button"
                                                    x-on:click="selectOption('COMELEC / Voter\'s ID / COMELEC Registration Form')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    COMELEC / Voter's ID / COMELEC Registration Form
                                                </button>

                                                <button type="button"
                                                    x-on:click="selectOption('Philippine Identification (PhilID / ePhilID)')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Philippine Identification (PhilID / ePhilID)
                                                </button>

                                                <button type="button" x-on:click="selectOption('NBI Clearance')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    NBI Clearance
                                                </button>

                                                <button type="button"
                                                    x-on:click="selectOption('Pantawid Pamilya Pilipino Program (4Ps) ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Pantawid Pamilya Pilipino Program (4Ps) ID
                                                </button>

                                                <button type="button"
                                                    x-on:click="selectOption('Integrated Bar of the Philippines (IBP) ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Integrated Bar of the Philippines (IBP) ID
                                                </button>

                                                <button type="button" x-on:click="selectOption('BIR (TIN)')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    BIR (TIN)
                                                </button>

                                                <button type="button" x-on:click="selectOption('Pag-ibig ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Pag-ibig ID
                                                </button>

                                                <button type="button" x-on:click="selectOption('Solo Parent ID')"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Solo Parent ID
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ID Number --}}
                                    <div class="relative col-span-full sm:col-span-3 sm:row-span-1 mb-4 pb-1">

                                        <label for="id_number" class="block mb-1 font-medium text-green-1100 ">ID
                                            Number <span class="text-red-700 font-normal text-xs">*</span></label>
                                        <input type="text" id="id_number" autocomplete="off"
                                            wire:model.blur="id_number"
                                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('id_number') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}"
                                            placeholder="Type ID number">
                                        @error('id_number')
                                            <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- Is PWD? --}}
                                    <div class="relative col-span-full sm:col-span-1 sm:row-span-1 mb-4 pb-1">
                                        <div class="flex items-center">
                                            <p class="inline mb-1 font-medium text-green-1100">Is PWD?</p>
                                            {{-- Popover Thingy --}}
                                            <div x-data="{ pop: false }" class="relative flex items-center"
                                                id="is-pwd-question-mark">
                                                <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-green-700"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20"
                                                    @mouseleave="pop = false;"
                                                    @mouseenter="setTimeout(() => {pop = true}, 350);">
                                                    <path
                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                                </svg>
                                                {{-- Popover --}}
                                                <div id="is-pwd-popover"
                                                    class="absolute z-50 bottom-full mb-2 left-0 md:left-auto md:right-0 text-xs whitespace-nowrap text-green-50 bg-green-900 rounded p-2 shadow"
                                                    x-show="pop">
                                                    PWD stands for <b>P</b>erson <b>w</b>ith
                                                    <b>D</b>isability
                                                </div>
                                            </div>
                                        </div>
                                        <div x-data="{
                                            open: false,
                                            is_pwd: $wire.entangle('is_pwd'),
                                            toggle() {
                                                if (this.open) {
                                                    return this.close()
                                                }
                                        
                                                this.$refs.isPWDButton.focus()
                                        
                                                this.open = true
                                            },
                                            close(focusAfter) {
                                                if (!this.open) return
                                        
                                                this.open = false
                                        
                                                focusAfter && focusAfter.focus()
                                            },
                                            selectOption(option) {
                                                this.is_pwd = option;
                                                this.close(this.$refs.isPWDButton); // Close the dropdown after selecting an option
                                            }
                                        }"
                                            x-on:keydown.escape.prevent.stop="close($refs.isPWDButton)"
                                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                            x-id="['is-pwd-button']" class="relative">
                                            <!-- Button -->
                                            <button x-ref="isPWDButton" x-on:click="toggle()" :aria-expanded="open"
                                                :aria-controls="$id('is-pwd-button')" type="button"
                                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-green-50 border-green-300 text-green-1100 outline-green-300 focus:outline-green-600 focus:border-green-600">
                                                <span x-text="is_pwd"></span> <!-- Display selected option -->

                                                <!-- Heroicon: chevron-down -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-4 text-green-1100 group-hover:text-green-900 group-active:text-green-1000 duration-200 ease-in-out"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <!-- Panel -->
                                            <div x-ref="panel" x-show="open"
                                                x-on:click.outside="close($refs.isPWDButton)"
                                                :id="$id('is-pwd-button')" style="display: none;"
                                                class="absolute left-0 mt-2 w-full z-50 rounded bg-green-50 shadow-lg border border-green-500">
                                                <button type="button"
                                                    x-on:click="selectOption('Yes'); $wire.$refresh();"
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    Yes
                                                </button>

                                                <button type="button"
                                                    x-on:click="
                                            selectOption('No'); 
                                            $wire.$refresh();
                                            if ($wire.type_of_id === 'Person\'s With Disability (PWD) ID') {
                                                $wire.type_of_id = 'e-Card / UMID';
                                            }
                                            "
                                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-green-1100 hover:text-green-900 focus:text-green-900 active:text-green-1000 hover:bg-green-100 focus:bg-green-100 active:bg-green-200">
                                                    No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Spouse First Name --}}
                                    <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                                        <label for="spouse_first_name"
                                            class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-green-1100' : 'text-gray-400' }}">Spouse
                                            First Name @if ($civil_status === 'Married')
                                                <span class="text-red-700 font-normal text-xs ms-0.5">*</span>
                                            @endif
                                        </label>
                                        <input type="text" id="spouse_first_name" autocomplete="off"
                                            wire:model.blur="spouse_first_name"
                                            @if ($civil_status === 'Single') disabled @endif
                                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}
                                                @else
                                                bg-gray-200 border-gray-300 text-gray-500 @endif"
                                            placeholder="Type spouse first name">
                                        @error('spouse_first_name')
                                            <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- Spouse Middle Name --}}
                                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                        <label for="spouse_middle_name"
                                            class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-green-1100' : 'text-gray-400' }}">Spouse
                                            Middle Name </label>
                                        <input type="text" id="spouse_middle_name" autocomplete="off"
                                            wire:model.blur="spouse_middle_name"
                                            @if ($civil_status === 'Single') disabled @endif
                                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                        {{ $civil_status === 'Married'
                                            ? 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600'
                                            : 'bg-gray-200 border-gray-300 text-gray-500' }}"
                                            placeholder="(optional)">
                                    </div>
                                    {{-- Spouse Last Name --}}
                                    <div class="relative flex flex-col col-span-full sm:col-span-2 mb-4 pb-1">
                                        <label for="spouse_last_name"
                                            class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-green-1100' : 'text-gray-400' }}">Spouse
                                            Last Name @if ($civil_status === 'Married')
                                                <span class="text-red-700 font-normal text-xs ms-0.5">*</span>
                                            @endif
                                        </label>
                                        <input type="text" id="spouse_last_name" autocomplete="off"
                                            wire:model.blur="spouse_last_name"
                                            @if ($civil_status === 'Single') disabled @endif
                                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                        
                                        @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600' }}
                                        @else
                                        bg-gray-200 border-gray-300 text-gray-500 @endif"
                                            placeholder="Type spouse last name">
                                        @error('spouse_last_name')
                                            <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- Spouse Extension Name --}}
                                    <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                                        <label for="spouse_extension_name"
                                            class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-green-1100' : 'text-gray-400' }}">Spouse
                                            Ext. Name</label>
                                        <input type="text" id="spouse_extension_name" autocomplete="off"
                                            wire:model.blur="spouse_extension_name"
                                            @if ($civil_status === 'Single') disabled @endif
                                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                        {{ $civil_status === 'Married'
                                            ? 'bg-green-50 border-green-300 text-green-1100 focus:ring-green-600 focus:border-green-600'
                                            : 'bg-gray-200 border-gray-300 text-gray-500' }}"
                                            placeholder="III, Sr., etc.">

                                    </div>
                                </div>
                                {{-- Modal footer --}}
                                <div class="relative col-span-full w-full flex items-center justify-end">
                                    <div class="flex items-center justify-end relative">
                                        {{-- Loading State for Changes --}}
                                        <div class="z-50 text-green-900" wire:loading wire:target="saveBeneficiary">
                                            <svg class="size-6 me-3 -ms-1 animate-spin"
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
                                        <button wire:click.prevent="saveBeneficiary" wire:loading.attr="disabled"
                                            wire:target="saveBeneficiary"
                                            class="space-x-2 py-2 px-4 text-center text-white font-bold flex items-center bg-green-700 disabled:opacity-75 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-md">
                                            <p>ADD</p>
                                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M181.716 13.755 C 102.990 27.972,72.357 125.909,128.773 183.020 C 181.183 236.074,272.696 214.609,295.333 143.952 C 318.606 71.310,256.583 0.235,181.716 13.755 M99.463 202.398 C 60.552 222.138,32.625 260.960,26.197 304.247 C 24.209 317.636,24.493 355.569,26.629 361.939 C 30.506 373.502,39.024 382.022,50.561 385.877 C 55.355 387.479,56.490 387.500,136.304 387.500 L 217.188 387.500 209.475 379.883 C 171.918 342.791,164.644 284.345,192.232 241.338 C 195.148 236.792,195.136 236.719,191.484 236.719 C 169.055 236.719,137.545 223.179,116.259 204.396 L 108.691 197.717 99.463 202.398 M269.531 213.993 C 176.853 234.489,177.153 366.574,269.922 386.007 C 337.328 400.126,393.434 333.977,369.538 268.559 C 355.185 229.265,310.563 204.918,269.531 213.993 M293.788 265.042 C 298.143 267.977,299.417 271.062,299.832 279.675 L 300.199 287.301 307.825 287.668 C 319.184 288.215,324.219 292.002,324.219 300.000 C 324.219 307.998,319.184 311.785,307.825 312.332 L 300.199 312.699 299.832 320.325 C 299.285 331.684,295.498 336.719,287.500 336.719 C 279.502 336.719,275.715 331.684,275.168 320.325 L 274.801 312.699 267.175 312.332 C 255.816 311.785,250.781 307.998,250.781 300.000 C 250.781 292.002,255.816 288.215,267.175 287.668 L 274.801 287.301 275.168 279.675 C 275.715 268.316,279.502 263.281,287.500 263.281 C 290.019 263.281,291.997 263.835,293.788 265.042 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
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

        {{-- Delete Beneficiary Modal --}}
        <div x-cloak>
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="beneficiaryDeleteModal">
            </div>

            <!-- Modal -->
            <div x-show="beneficiaryDeleteModal" x-trap.noscroll="beneficiaryDeleteModal"
                class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                {{-- The Modal --}}
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-md shadow">
                        <!-- Modal Header -->
                        <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                            <h1 class="text-sm sm:text-base font-semibold text-green-1100">Delete Beneficiary
                            </h1>

                            <div class="flex items-center justify-center">
                                {{-- Loading State for Changes --}}
                                <div class="z-50 text-green-900" wire:loading wire:target="beneficiaryDeleteModal">
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
                                <button type="button" wire:click="closeDeleteModal"
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

                            <p class="mb-10 text-sm font-medium">Are you sure about deleting <span
                                    class="font-semibold text-red-600">
                                    @if ($beneficiaryDeleteModal)
                                        {{ $this->full_name_by_id() }}
                                    @endif

                                </span>?
                            </p>

                            <div class="relative flex items-center justify-center w-full">
                                <div class="flex items-center justify-center">
                                    <div class="relative me-2">
                                        <label for="confirming" class="absolute bottom-full mb-1 font-medium">Type
                                            CONFIRM to
                                            execute</label>
                                        <input type="text" id="confirming" wire:model.blur="confirming"
                                            autocomplete="off"
                                            class="flex flex-1 {{ $errors->has('confirming') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'border-green-300 bg-green-50' }} rounded outline-none border py-2.5 text-sm select-all duration-200 ease-in-out"
                                            placeholder="CONFIRM">
                                        @error('confirming')
                                            <p class="absolute top-full left-0 text-xs text-red-700">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    <button id="confirmDeleteBeneficiaryButton" wire:loading.attr="disabled"
                                        wire:target="deleteBeneficiary"
                                        class="flex items-center justify-center disabled:bg-red-300 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                        @click="$wire.deleteBeneficiary();">
                                        CONFIRM
                                    </button>
                                </div>
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
            <div x-show="submitBatchModal" x-trap.noscroll="submitBatchModal"
                class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                {{-- The Modal --}}
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-md shadow">
                        <!-- Modal Header -->
                        <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                            <h1 class="text-sm sm:text-base font-semibold text-green-1100">Delete Beneficiary
                            </h1>

                            <div class="flex items-center justify-center">
                                {{-- Loading State for Changes --}}
                                <div class="z-50 text-green-900" wire:loading wire:target="submitBatchModal">
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
                                <button type="button" @click="submitBatchModal = false;"
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

                            <p class="text-lg font-medium">Are you sure about submitting this?
                            </p>
                            <p class="mb-6 text-sm font-medium text-gray-500">(This access code will become
                                unaccessible)
                            </p>

                            <div class="relative flex items-center justify-center w-full">
                                <div class="flex items-center justify-center gap-x-4">
                                    <button id="cancelSubmitButton" wire:loading.attr="disabled"
                                        wire:target="submitBatch"
                                        class="flex items-center justify-center border border-green-700 hover:border-transparent active:border-transparent hover:bg-green-800 active:bg-green-900 text-green-700 hover:text-green-50 active:text-green-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                        @click="submitBatchModal = false;">
                                        CANCEL
                                    </button>
                                    <button id="confirmSubmitBatchButton" wire:loading.attr="disabled"
                                        wire:target="submitBatch"
                                        class="flex items-center justify-center disabled:bg-red-300 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                        @click="$wire.submitBatch();">
                                        CONFIRM
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        const birthdatePicker = document.getElementById('birthdate');

        birthdatePicker.addEventListener('changeDate', function(event) {
            $wire.dispatchSelf('birthdate-change', {
                value: birthdatePicker.value
            });
        });
    </script>
@endscript
