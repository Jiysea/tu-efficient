<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="viewBeneficiaryModal">
    </div>

    <!-- Modal -->
    <div x-show="viewBeneficiaryModal" x-trap.noscroll="viewBeneficiaryModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

        {{-- The Modal --}}
        <div class="relative pt-4 px-4 w-full max-w-7xl max-h-full">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">View Beneficiary
                        </h1>

                    </span>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading>
                            <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <button type="button" @click="$wire.resetViewBeneficiary(); viewBeneficiaryModal = false;"
                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close Modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                @if ($this->beneficiary)
                    <form wire:submit.prevent="saveBeneficiary" class="p-4 md:p-5">
                        <div class="grid gap-4 grid-cols-1 sm:grid-cols-3 md:grid-cols-5 text-xs">
                            {{-- IF Edit Mode is ON --}}
                            @if ($editMode)
                            @endif

                            {{-- IF Edit Mode is OFF --}}
                            @if (!$editMode)

                                {{-- Project Number --}}
                                <div class="relative md:col-span-2 flex flex-col mb-2">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Project Number
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->projectInformation->project_num }}</span>
                                </div>

                                {{-- Batch Number --}}
                                <div class="relative md:col-span-2 flex flex-col mb-2">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Batch Number
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->projectInformation->batch_num }}</span>
                                </div>

                                {{-- Edit | Delete Buttons OFF --}}
                                <div x-data="{ deleteBeneficiaryModal: $wire.entangle('deleteBeneficiaryModal') }" class="flex justify-center items-center">
                                    <button type="button" wire:loading.attr="disabled" wire:target="toggleEdit"
                                        wire:click.prevent="toggleEdit"
                                        class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                        EDIT
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                    </button>

                                    {{-- Delete/Trash Button --}}
                                    <button type="button" @click="deleteBeneficiaryModal = !deleteBeneficiaryModal;"
                                        class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                    </button>

                                    {{-- Delete Project Modal --}}
                                    <div x-cloak>
                                        <!-- Modal Backdrop -->
                                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                            x-show="deleteBeneficiaryModal">
                                        </div>

                                        <!-- Modal -->
                                        <div x-trap.inert="deleteBeneficiaryModal" x-show="deleteBeneficiaryModal"
                                            x-trap.noscroll="deleteBeneficiaryModal"
                                            class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                            {{-- The Modal --}}
                                            <div class="relative w-full max-w-xl max-h-full">
                                                <div class="relative bg-white rounded-md shadow">
                                                    <!-- Modal Header -->
                                                    <div class="flex items-center py-2 px-4 rounded-t-md">
                                                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                            Delete Beneficiary
                                                        </h1>
                                                    </div>

                                                    <hr class="">

                                                    {{-- Modal body --}}
                                                    <div
                                                        class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                                        @if ($this->projectInformation->approval_status === 'approved')
                                                            <p class="font-medium text-sm mb-5">
                                                                Are you sure about archiving this beneficiary?
                                                            </p>
                                                        @else
                                                            <p class="font-medium text-sm mb-1">
                                                                Are you sure about deleting this beneficiary?
                                                            </p>
                                                            <p class="text-gray-500 text-sm mb-4">
                                                                (This is action is irreversible)
                                                            </p>
                                                        @endif

                                                        <div class="flex items-center justify-center w-full gap-4">
                                                            <button type="button"
                                                                class="duration-200 ease-in-out flex flex-1 items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50"
                                                                @click="deleteBeneficiaryModal = false;">CANCEL</button>

                                                            <button type="button"
                                                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50"
                                                                wire:click.prevent="restoreBeneficiary">RESTORE THE
                                                                ARCHIVE</button>

                                                            <button type="button"
                                                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50"
                                                                wire:click.prevent="deleteBeneficiary">CONFIRM</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Basic Information --}}
                                <span
                                    class="relative flex col-span-full mb-2 font-semibold text-sm rounded px-2 py-1 bg-indigo-500 text-indigo-50">Basic
                                    Information</span>
                                @foreach ($this->basicInformation as $key => $info)
                                    <div class="relative flex flex-col mb-2">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            {{ $key }}
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $info }}</span>
                                    </div>
                                @endforeach

                                {{-- Address Information --}}
                                <span
                                    class="relative flex col-span-full mt-4 mb-2 font-semibold text-sm rounded px-2 py-1 bg-indigo-500 text-indigo-50">Address
                                    / Location of the Implementation</span>
                                @foreach ($this->addressInformation as $key => $info)
                                    <div class="relative flex flex-col mb-2">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            {{ $key }}
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $info }}</span>
                                    </div>
                                @endforeach

                                {{-- Additional Information --}}
                                <span
                                    class="relative flex col-span-full mt-4 mb-2 font-semibold text-sm rounded px-2 py-1 bg-indigo-500 text-indigo-50">Additional
                                    Information</span>
                                @foreach ($this->additionalInformation as $key => $info)
                                    <div class="relative flex flex-col mb-2">
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            {{ $key }}
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $info }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
