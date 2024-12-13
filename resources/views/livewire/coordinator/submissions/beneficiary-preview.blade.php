<div class="flex flex-col size-full rounded bg-white shadow text-xs select-text">

    @if ($beneficiaryId)
        {{-- Whole Thing --}}
        <div class="grid grid-cols-11 gap-2 p-4">

            {{-- Left Side --}}
            <div class="flex flex-col col-span-full sm:col-span-3 items-center text-blue-1100 gap-2">

                {{-- Identity Information --}}
                <div class="flex flex-col items-center text-blue-1100">
                    {{-- ID Image --}}
                    <div
                        class="flex flex-col items-center justify-center bg-gray-50 text-gray-400 border-gray-300 border rounded mb-2 size-32 aspect-square">

                        @if (isset($identity) && !empty($identity))
                            <button tabindex="-1"
                                class="flex items-center justify-center rounded size-32 aspect-square outline-none"
                                @click="$wire.viewCredential('identity');">
                                <img class="size-[90%] object-contain"
                                    src="{{ route('credentials.show', ['filename' => $identity]) }}">
                            </button>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-[50%]"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
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
                        {{ $this->beneficiary?->id_number }}
                    </p>
                </div>

                {{-- Address Information --}}
                <div class="flex flex-col w-full text-blue-1100 gap-1">

                    {{-- Header --}}
                    <p class="font-bold text-sm bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                        address</p>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                        {{-- Province --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                province </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->province }}</span>
                        </div>

                        {{-- City/Municipality --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                city / municipality </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->city_municipality }}</span>
                        </div>

                        {{-- District --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                district </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->district }}</span>
                        </div>
                    </div>
                </div>

                {{-- Spouse Information --}}
                <div class="flex flex-col w-full text-blue-1100 gap-1">

                    {{-- Header --}}
                    <p class="font-bold text-sm bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                        spouse info</p>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">

                        {{-- Spouse First Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                first name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->spouse_first_name ?? '-' }}</span>
                        </div>

                        {{-- Spouse Middle Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                middle name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->spouse_middle_name ?? '-' }}</span>
                        </div>

                        {{-- Spouse Last Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                last name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->spouse_last_name ?? '-' }}</span>
                        </div>

                        {{-- Spouse Extension Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                ext. name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->spouse_extension_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex col-span-full sm:col-span-8 flex-col text-blue-1100 gap-1">

                {{-- Header --}}
                <p class="font-bold text-sm bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                    basic
                    information</p>

                {{-- Body --}}
                <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- First Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                first name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->first_name }}</span>
                        </div>

                        {{-- Middle Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                middle name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->middle_name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Last Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                last name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->last_name }}</span>
                        </div>

                        {{-- Extension Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                ext. name </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->extension_name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Birthdate --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                birthdate </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ Carbon\Carbon::parse($this->beneficiary?->birthdate)->format('M. d, Y') }}</span>
                        </div>

                        {{-- Age --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                age </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->age }}
                            </span>
                        </div>

                        {{-- Sex --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                sex </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->beneficiary?->sex }}</span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Civil Status --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                civil status </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->beneficiary?->civil_status }}</span>
                        </div>

                        {{-- Contact Number --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                contact number </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->contact_num }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Occupation --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                occupation </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->occupation ?? 'None' }}</span>
                        </div>

                        {{-- Avg Monthly Income --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                avg. monthly income </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                @if ($this->beneficiary?->avg_monthly_income === null || $this->beneficiary?->avg_monthly_income === 0)
                                    {{ '-' }}
                                @else
                                    {{ '₱' . number_format($this->beneficiary?->avg_monthly_income / 100, 2) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Type of Beneficiary --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium">
                                Type of Beneficiary </p>

                            @if ($this->beneficiary?->beneficiary_type === 'special case')
                                <button type="button" @click="$wire.viewCredential('special');"
                                    class="relative flex items-center justify-between whitespace-normal rounded capitalize px-2 py-0.5 outline-none bg-red-100 active:bg-red-200 text-red-950 hover:text-red-700 duration-200 ease-in-out">
                                    {{ $this->beneficiary?->beneficiary_type }}

                                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-2 size-4"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                            @else
                                <span
                                    class="whitespace-normal rounded px-2 py-0.5 bg-blue-50 text-blue-1000 capitalize select-all">
                                    {{ $this->beneficiary?->beneficiary_type }}
                                </span>
                            @endif

                        </div>

                        {{-- Dependent --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                dependent </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->dependent ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between">
                        {{-- Interested in Self Employment or Wage Employment --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                interested in self employment or wage employment </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->beneficiary?->self_employment }}</span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between">
                        {{-- Skills Training --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                skills training </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->skills_training ?? '-' }}
                            </span>
                        </div>

                        {{-- e-Payment Account Number --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium">
                                e-Payment Account Number </p>
                            <span class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->beneficiary?->e_payment_acc_num ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- is PWD --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Person w/ Disability </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->beneficiary?->is_pwd }}</span>
                        </div>

                        {{-- is Senior Citizen --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Senior Citizen </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->beneficiary?->is_senior_citizen }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- is PWD --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Date Added </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ \Carbon\Carbon::parse($this->beneficiary?->created_at)->format('M d, Y @ h:i:sa') }}</span>
                        </div>

                        {{-- is Senior Citizen --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Last Updated </p>
                            <span
                                class="whitespace-normal bg-blue-50 text-blue-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ \Carbon\Carbon::parse($this->beneficiary?->updated_at)->format('M d, Y @ h:i:sa') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-1 px-2 py-1 gap-2">
                    <div class="relative max-[430px]:flex-col flex flex-1 items-center justify-end gap-2">

                        {{-- Edit Button --}}
                        <button
                            @if ($this->batch?->approval_status !== 'approved') @click="$wire.openEdit(); $dispatch('openEdit');"
                            @else
                            disabled @endif
                            class="rounded text-sm font-bold flex flex-1 gap-2 items-center justify-center px-3 py-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:bg-blue-800 focus:ring-2 focus:ring-blue-300 duration-200 ease-in-out">
                            EDIT

                            {{-- Loading Icon --}}
                            <svg class="size-5 animate-spin" wire:loading wire:target="openEdit"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Edit Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" wire:loading.remove
                                wire:target="openEdit" xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
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
                            @if ($this->batch?->approval_status !== 'approved') @click="deleteBeneficiaryModal = !deleteBeneficiaryModal;"
                            @else
                            disabled @endif
                            class="rounded text-sm font-bold flex items-center justify-center p-2 outline-none disabled:bg-gray-300 disabled:text-gray-500 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 focus:bg-red-800 focus:ring-2 focus:ring-red-300 duration-200 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5"
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
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="rounded relative bg-white p-4 h-full w-full flex items-center justify-center">
            <div
                class="relative flex flex-col items-center justify-center border rounded h-full w-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4 text-blue-900 opacity-65"
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
                        class="text-blue-900">beneficiaries</span> row.
                </p>
            </div>
        </div>
    @endif

    {{-- View Credentials Modal --}}
    <livewire:coordinator.submissions.view-credentials-modal :$passedCredentialId />

    {{-- Edit Beneficiary Modal --}}
    <livewire:coordinator.submissions.edit-beneficiary-modal :$beneficiaryId />

    {{-- Delete Beneficiary Modal --}}
    <div x-cloak @keydown.window.escape="deleteBeneficiaryModal"
        class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50"
        x-show="deleteBeneficiaryModal">

        <!-- Modal -->
        <div x-show="deleteBeneficiaryModal" x-trap.noscroll.noautofocus="deleteBeneficiaryModal"
            class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

            {{-- The Modal --}}
            <div class="relative size-full max-w-xl">
                <div class="relative bg-white rounded-md shadow">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                        <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                            {{ $this->defaultArchive ? 'Archive' : 'Delete' }} this Beneficiary
                        </h1>

                        {{-- Close Button --}}
                        <button type="button" @click="deleteBeneficiaryModal = false;"
                            class="outline-none text-blue-400 focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close Modal</span>
                        </button>
                    </div>

                    <hr class="">

                    {{-- Modal body --}}
                    <div class="grid w-full place-items-center py-5 px-3 md:px-16 text-blue-1100 text-xs">

                        @if ($this->defaultArchive)
                            <p class="font-medium text-sm mb-2">
                                Are you sure about archiving this beneficiary?
                            </p>
                            <p class="text-gray-500 text-xs font-normal mb-4">
                                You could restore this beneficiary back from the Archives page
                            </p>
                        @else
                            <p class="font-medium text-sm mb-2">
                                Are you sure about deleting this beneficiary?
                            </p>
                            <p class="text-gray-500 text-xs font-normal mb-4">
                                This is action is irreversible
                            </p>
                        @endif

                        <div class="flex items-center justify-center w-full gap-2">

                            <button @click="deleteBeneficiaryModal = false;"
                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-blue-700 hover:border-transparent active:border-transparent hover:bg-blue-800 active:bg-blue-900 text-blue-700 hover:text-blue-50 active:text-blue-50">
                                CANCEL
                            </button>
                            <button type="button" @click="$wire.deleteBeneficiary(); deleteBeneficiaryModal = false;"
                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                CONFIRM
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
