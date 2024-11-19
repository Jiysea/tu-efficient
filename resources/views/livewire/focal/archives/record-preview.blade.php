<div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-700 scrollbar-track-indigo-50">

    @if ($archiveId)
        {{-- Whole Thing --}}
        <div class="grid grid-cols-11 gap-2 p-4 text-xs">
            {{-- Left Side --}}
            <div class="flex flex-col col-span-full sm:col-span-3 items-center text-indigo-1100 gap-2">

                {{-- Identity Information --}}
                <div class="flex flex-col items-center text-indigo-1100">

                    {{-- ID Image --}}
                    <div
                        class="flex flex-col items-center justify-center bg-gray-50 text-gray-400 border-gray-300 border rounded mb-2 size-32 aspect-square">

                        @if ($this->identity)
                            <button class="flex items-center justify-center rounded" @click="">
                                <img class="w-[90%]"
                                    src="{{ route('credentials.show', ['filename' => $this->identity['image_file_path']]) }}">
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
                        {{ $this->archive->data['id_number'] }}
                    </p>
                </div>

                {{-- Address Information --}}
                <div class="flex flex-col w-full text-indigo-1100 gap-1">

                    {{-- Header --}}
                    <p class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                        address</p>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">
                        {{-- Province --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                province </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['province'] }}</span>
                        </div>

                        {{-- City/Municipality --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                city / municipality </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['city_municipality'] }}</span>
                        </div>

                        {{-- District --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                district </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['district'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Spouse Information --}}
                <div class="flex flex-col w-full text-indigo-1100 gap-1">

                    {{-- Header --}}
                    <p class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
                        spouse info</p>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col px-2 py-1 gap-2">

                        {{-- Spouse First Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                first name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['spouse_first_name'] ?? '-' }}</span>
                        </div>

                        {{-- Spouse Middle Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                middle name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['spouse_middle_name'] ?? '-' }}</span>
                        </div>

                        {{-- Spouse Last Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                last name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['spouse_last_name'] ?? '-' }}</span>
                        </div>

                        {{-- Spouse Extension Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                ext. name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['spouse_extension_name'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex col-span-full sm:col-span-8 flex-col text-indigo-1100 gap-1">

                {{-- Header --}}
                <p class="font-bold text-sm lg:text-xs bg-gray-200 text-gray-700 rounded uppercase px-2 py-1">
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
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['first_name'] }}</span>
                        </div>

                        {{-- Middle Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                middle name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['middle_name'] ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Last Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                last name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['last_name'] }}</span>
                        </div>

                        {{-- Extension Name --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                ext. name </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['extension_name'] ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Birthdate --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                birthdate </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ Carbon\Carbon::parse($this->archive->data['birthdate'])->format('M. d, Y') }}</span>
                        </div>

                        {{-- Age --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                age </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['age'] }}
                            </span>
                        </div>

                        {{-- Sex --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                sex </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->archive->data['sex'] }}</span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Civil Status --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                civil status </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->archive->data['civil_status'] }}</span>
                        </div>

                        {{-- Contact Number --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                contact number </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['contact_num'] }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Occupation --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                occupation </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['occupation'] ?? 'None' }}</span>
                        </div>

                        {{-- Avg Monthly Income --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium capitalize">
                                avg. monthly income </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                @if ($this->archive->data['avg_monthly_income'] === null || $this->archive->data['avg_monthly_income'] === 0)
                                    {{ '-' }}
                                @else
                                    {{ '₱' . number_format($this->archive->data['avg_monthly_income'] / 100, 2) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- Type of Beneficiary --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium">
                                Type of Beneficiary </p>

                            @if ($this->archive->data['beneficiary_type'] === 'special case')
                                <button type="button" @click="$wire.viewCredential('special');"
                                    class="relative flex items-center justify-between whitespace-normal rounded capitalize px-2 py-0.5 outline-none bg-amber-100 active:bg-amber-200 text-amber-950 hover:text-amber-700 duration-200 ease-in-out">
                                    {{ $this->archive->data['beneficiary_type'] }}

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
                                    class="whitespace-normal rounded px-2 py-0.5 bg-indigo-50 text-indigo-1000 capitalize select-all">
                                    {{ $this->archive->data['beneficiary_type'] }}
                                </span>
                            @endif

                        </div>

                        {{-- Dependent --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                dependent </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['dependent'] ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between">
                        {{-- Interested in Self Employment or Wage Employment --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                interested in self employment or wage employment </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->archive->data['self_employment'] }}</span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between">
                        {{-- Skills Training --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                skills training </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['skills_training'] ?? '-' }}
                            </span>
                        </div>

                        {{-- e-Payment Account Number --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium">
                                e-Payment Account Number </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 select-all">
                                {{ $this->archive->data['e_payment_acc_num'] ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- is PWD --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Person w/ Disability </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->archive->data['is_pwd'] }}</span>
                        </div>

                        {{-- is Senior Citizen --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Senior Citizen </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ $this->archive->data['is_senior_citizen'] }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center whitespace-nowrap justify-between gap-2">
                        {{-- is PWD --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Date Added </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ \Carbon\Carbon::parse($this->archive->data['created_at'])->format('M d, Y @ h:i:sa') }}</span>
                        </div>

                        {{-- is Senior Citizen --}}
                        <div class="flex flex-1 flex-col justify-center">
                            <p class="select-all font-medium  capitalize">
                                Last Updated </p>
                            <span
                                class="whitespace-normal bg-indigo-50 text-indigo-1000 rounded px-2 py-0.5 capitalize select-all">
                                {{ \Carbon\Carbon::parse($this->archive->data['updated_at'])->format('M d, Y @ h:i:sa') }}
                            </span>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    @else
        <div class="rounded relative bg-white p-4 h-[89vh] flex items-center justify-center">
            <div
                class="relative flex flex-col items-center justify-center border rounded size-full font-medium text-sm text-gray-500 bg-gray-50 border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
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
                        class="text-indigo-900">records</span>.
                </p>
            </div>
        </div>
    @endif
</div>
