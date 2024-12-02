<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="viewCredentialsModal"
    x-trap.noautofocus.noscroll="viewCredentialsModal">

    <!-- Modal -->
    <div x-show="viewCredentialsModal"
        class="relative h-full overflow-y-auto p-4 flex items-start sm:items-center justify-center select-none">

        {{-- The Modal --}}
        <div class="w-full max-w-4xl">
            <div x-data="{ viewImageModal: $wire.entangle('viewImageModal') }" class="relative bg-white rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">

                            @if ($type === 'special')
                                View Special Case
                            @elseif($type === 'identity')
                                View Identification Card
                            @endif
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

                        {{-- Close Button --}}
                        <button type="button" @click="$wire.resetViewCredentials(); viewCredentialsModal = false;"
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

                {{-- Modal body --}}
                @if ($type === 'special')
                    <div class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                            {{-- Case Proof --}}
                            <div class="relative col-span-full sm:col-span-1">
                                <div class="flex flex-col items-start">
                                    <div class="flex items-center">
                                        <p class="inline mb-1 font-medium text-indigo-1100">
                                            Case Proof
                                        </p>
                                    </div>

                                    {{-- Certificate or Something --}}
                                    @if ($this->archiveCredential?->data['image_file_path'])
                                        <button type="button" @click="viewImageModal = !viewImageModal;"
                                            class="relative flex items-center justify-center col-span-1 bg-indigo-50 size-[90%] aspect-square rounded overflow-hidden group">

                                            <img src="{{ route('credentials.show', ['filename' => $this->archiveCredential?->data['image_file_path']]) }}"
                                                class="size-[95%] object-contain" alt="ID Picture">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="absolute bottom-0 right-0 size-6 p-1 rounded-tl ease-in-out duration-200 text-indigo-1100 group-hover:text-indigo-900"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M153.659 25.425 C 31.123 42.120,-19.410 193.876,68.399 281.468 C 121.389 334.328,207.977 340.004,266.194 294.434 C 268.870 292.339,271.327 290.625,271.653 290.625 C 271.979 290.625,291.059 309.410,314.053 332.369 C 359.446 377.694,357.193 375.813,364.857 374.785 C 372.845 373.713,377.360 364.746,373.906 356.811 C 373.283 355.380,355.110 336.630,331.718 313.284 C 309.117 290.727,290.625 271.999,290.625 271.666 C 290.625 271.333,292.339 268.870,294.434 266.194 C 329.224 221.749,335.020 157.497,308.873 106.138 C 279.810 49.053,217.615 16.711,153.659 25.425 M191.797 51.641 C 281.892 63.052,329.341 168.641,278.329 244.200 C 209.721 345.820,51.172 297.520,51.172 175.000 C 51.172 98.776,115.866 42.025,191.797 51.641 M168.157 114.058 C 162.689 117.392,162.506 118.283,162.503 141.602 L 162.500 162.500 141.602 162.503 C 115.437 162.507,112.506 163.766,112.506 175.000 C 112.506 186.234,115.437 187.493,141.602 187.497 L 162.500 187.500 162.503 208.398 C 162.507 234.563,163.766 237.494,175.000 237.494 C 186.234 237.494,187.493 234.563,187.497 208.398 L 187.500 187.500 208.398 187.497 C 234.563 187.493,237.494 186.234,237.494 175.000 C 237.494 163.766,234.563 162.507,208.398 162.503 L 187.500 162.500 187.497 141.602 C 187.494 118.283,187.311 117.392,181.843 114.058 C 178.384 111.949,171.616 111.949,168.157 114.058 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>
                                    @else
                                        <div
                                            class="relative flex flex-1 flex-col items-center justify-center col-span-1 bg-indigo-50 size-[90%] aspect-square rounded">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-28 rounded text-gray-400"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M32.422 11.304 C 31.992 11.457,30.680 11.794,29.507 12.052 C 24.028 13.260,19.531 19.766,19.531 26.487 C 19.531 32.602,20.505 34.096,32.052 45.703 L 42.932 56.641 34.864 64.939 C 15.117 85.248,8.104 102.091,3.189 141.016 C -3.142 191.153,0.379 261.277,10.675 290.108 C 22.673 323.703,54.885 351.747,88.994 358.293 C 140.763 368.227,235.891 369.061,300.224 360.143 C 314.334 358.187,325.014 355.166,333.980 350.595 L 337.882 348.606 356.803 367.237 C 377.405 387.523,378.751 388.534,385.156 388.534 C 396.064 388.534,402.926 378.158,399.161 367.358 C 398.216 364.648,45.323 14.908,41.621 13.013 C 39.365 11.859,33.779 10.821,32.422 11.304 M173.685 26.603 C 149.478 27.530,105.181 31.289,103.940 32.521 C 103.744 32.716,109.721 38.980,117.221 46.441 L 130.859 60.008 143.750 58.937 C 190.711 55.035,239.415 56.114,289.049 62.156 C 323.242 66.318,344.750 80.309,357.596 106.748 C 367.951 128.058,373.239 201.260,367.335 241.563 L 366.797 245.235 356.492 231.797 C 310.216 171.453,298.664 162.344,271.006 164.387 C 260.988 165.127,245.312 170.115,245.313 172.562 C 245.313 173.401,380.320 307.031,381.167 307.031 C 382.090 307.031,388.660 292.643,390.518 286.555 C 403.517 243.958,402.683 139.537,389.046 102.170 C 377.740 71.192,349.876 45.280,318.284 36.368 C 294.697 29.713,221.504 24.771,173.685 26.603 M88.547 101.394 L 98.578 111.490 94.406 113.848 C 74.760 124.952,71.359 153.827,87.859 169.432 C 104.033 184.729,130.241 181.325,141.915 162.410 L 144.731 157.848 146.780 159.342 C 147.906 160.164,161.448 173.480,176.871 188.934 L 204.915 217.032 200.234 222.774 C 194.483 229.829,171.825 260.177,171.304 261.523 C 170.623 263.286,169.872 262.595,162.828 253.726 C 153.432 241.895,140.224 226.635,137.217 224.134 C 126.063 214.861,107.616 213.280,93.162 220.358 C 85.033 224.339,70.072 241.107,47.047 272.044 L 40.234 281.197 39.314 279.023 C 32.914 263.906,28.466 201.412,31.263 165.934 C 34.978 118.821,40.622 102.197,58.912 84.488 L 64.848 78.741 71.682 85.019 C 75.440 88.472,83.030 95.841,88.547 101.394 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            <p class="text-gray-500 font-semibold text-xs mt-4">
                                                No image uploaded.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Image Description --}}
                            <div class="relative flex flex-col justify-between col-span-full sm:col-span-2 size-full">
                                <div class="flex flex-col h-full">
                                    <p class="mb-1 font-medium text-indigo-1100">
                                        Description
                                    </p>
                                    <div
                                        class="h-full flex flex-1 text-xs rounded w-full p-2 bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 select-all">
                                        {{ $this->archiveCredential?->data['image_description'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'identity')
                    <div class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                            {{-- Proof of Identity --}}
                            <div class="relative col-span-full sm:col-span-1">
                                <div class="flex flex-col items-start">
                                    <div class="flex items-center">
                                        <p class="inline mb-1 font-medium text-indigo-1100">
                                            Proof of Identity
                                        </p>
                                    </div>

                                    {{-- ID Picture --}}
                                    @if ($this->archiveCredential?->data['image_file_path'])
                                        <button type="button" @click="viewImageModal = !viewImageModal;"
                                            class="relative flex items-center justify-center col-span-1 bg-indigo-50 size-[90%] aspect-square rounded overflow-hidden group">

                                            <img src="{{ route('credentials.show', ['filename' => $this->archiveCredential?->data['image_file_path']]) }}"
                                                class="size-[95%] object-contain" alt="ID Picture">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="absolute bottom-0 right-0 size-6 p-1 rounded-tl ease-in-out duration-200 text-indigo-1100 group-hover:text-indigo-900"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                                viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M153.659 25.425 C 31.123 42.120,-19.410 193.876,68.399 281.468 C 121.389 334.328,207.977 340.004,266.194 294.434 C 268.870 292.339,271.327 290.625,271.653 290.625 C 271.979 290.625,291.059 309.410,314.053 332.369 C 359.446 377.694,357.193 375.813,364.857 374.785 C 372.845 373.713,377.360 364.746,373.906 356.811 C 373.283 355.380,355.110 336.630,331.718 313.284 C 309.117 290.727,290.625 271.999,290.625 271.666 C 290.625 271.333,292.339 268.870,294.434 266.194 C 329.224 221.749,335.020 157.497,308.873 106.138 C 279.810 49.053,217.615 16.711,153.659 25.425 M191.797 51.641 C 281.892 63.052,329.341 168.641,278.329 244.200 C 209.721 345.820,51.172 297.520,51.172 175.000 C 51.172 98.776,115.866 42.025,191.797 51.641 M168.157 114.058 C 162.689 117.392,162.506 118.283,162.503 141.602 L 162.500 162.500 141.602 162.503 C 115.437 162.507,112.506 163.766,112.506 175.000 C 112.506 186.234,115.437 187.493,141.602 187.497 L 162.500 187.500 162.503 208.398 C 162.507 234.563,163.766 237.494,175.000 237.494 C 186.234 237.494,187.493 234.563,187.497 208.398 L 187.500 187.500 208.398 187.497 C 234.563 187.493,237.494 186.234,237.494 175.000 C 237.494 163.766,234.563 162.507,208.398 162.503 L 187.500 162.500 187.497 141.602 C 187.494 118.283,187.311 117.392,181.843 114.058 C 178.384 111.949,171.616 111.949,168.157 114.058 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>
                                    @else
                                        <div
                                            class="relative flex flex-1 flex-col items-center justify-center col-span-1 bg-indigo-50 size-[90%] aspect-square rounded">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="size-28 rounded text-gray-400"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M32.422 11.304 C 31.992 11.457,30.680 11.794,29.507 12.052 C 24.028 13.260,19.531 19.766,19.531 26.487 C 19.531 32.602,20.505 34.096,32.052 45.703 L 42.932 56.641 34.864 64.939 C 15.117 85.248,8.104 102.091,3.189 141.016 C -3.142 191.153,0.379 261.277,10.675 290.108 C 22.673 323.703,54.885 351.747,88.994 358.293 C 140.763 368.227,235.891 369.061,300.224 360.143 C 314.334 358.187,325.014 355.166,333.980 350.595 L 337.882 348.606 356.803 367.237 C 377.405 387.523,378.751 388.534,385.156 388.534 C 396.064 388.534,402.926 378.158,399.161 367.358 C 398.216 364.648,45.323 14.908,41.621 13.013 C 39.365 11.859,33.779 10.821,32.422 11.304 M173.685 26.603 C 149.478 27.530,105.181 31.289,103.940 32.521 C 103.744 32.716,109.721 38.980,117.221 46.441 L 130.859 60.008 143.750 58.937 C 190.711 55.035,239.415 56.114,289.049 62.156 C 323.242 66.318,344.750 80.309,357.596 106.748 C 367.951 128.058,373.239 201.260,367.335 241.563 L 366.797 245.235 356.492 231.797 C 310.216 171.453,298.664 162.344,271.006 164.387 C 260.988 165.127,245.312 170.115,245.313 172.562 C 245.313 173.401,380.320 307.031,381.167 307.031 C 382.090 307.031,388.660 292.643,390.518 286.555 C 403.517 243.958,402.683 139.537,389.046 102.170 C 377.740 71.192,349.876 45.280,318.284 36.368 C 294.697 29.713,221.504 24.771,173.685 26.603 M88.547 101.394 L 98.578 111.490 94.406 113.848 C 74.760 124.952,71.359 153.827,87.859 169.432 C 104.033 184.729,130.241 181.325,141.915 162.410 L 144.731 157.848 146.780 159.342 C 147.906 160.164,161.448 173.480,176.871 188.934 L 204.915 217.032 200.234 222.774 C 194.483 229.829,171.825 260.177,171.304 261.523 C 170.623 263.286,169.872 262.595,162.828 253.726 C 153.432 241.895,140.224 226.635,137.217 224.134 C 126.063 214.861,107.616 213.280,93.162 220.358 C 85.033 224.339,70.072 241.107,47.047 272.044 L 40.234 281.197 39.314 279.023 C 32.914 263.906,28.466 201.412,31.263 165.934 C 34.978 118.821,40.622 102.197,58.912 84.488 L 64.848 78.741 71.682 85.019 C 75.440 88.472,83.030 95.841,88.547 101.394 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                            <p class="text-gray-500 font-semibold text-xs mt-4">
                                                No image uploaded.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- ID Information --}}
                            <div class="relative flex flex-col col-span-full sm:col-span-2 size-full gap-4">
                                <div class="flex flex-1 flex-col">
                                    <p class="mb-1 font-medium text-indigo-1100">
                                        Type of ID
                                    </p>
                                    <div
                                        class="flex flex-1 font-medium text-sm rounded w-full p-2.5 bg-indigo-50 text-indigo-700 select-all">
                                        {{ $this->idInformation ? $this->idInformation['type_of_id'] : null }}
                                    </div>
                                </div>
                                <div class="flex flex-1 flex-col">
                                    <p class="mb-1 font-medium text-indigo-1100">
                                        ID Number
                                    </p>
                                    <div
                                        class="flex flex-1 font-medium text-sm rounded w-full p-2.5 bg-indigo-50 text-indigo-700 select-all">
                                        {{ $this->idInformation ? $this->idInformation['id_number'] : null }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- View Image Modal --}}
                <div x-cloak x-show="viewImageModal" x-trap.noscroll="viewImageModal"
                    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

                    <!-- Modal -->
                    <div x-show="viewImageModal" x-trap.noscroll="viewImageModal"
                        class="relative h-full overflow-y-auto p-4 flex items-start sm:items-center justify-center select-none">

                        <div class="w-full">
                            {{-- Close Button --}}
                            <button type="button" @click="viewImageModal = false;"
                                class="absolute flex items-center justify-center top-0 right-0 m-4 outline-none text-indigo-50 hover:bg-indigo-50 hover:text-indigo-800 rounded size-8 duration-300 ease-in-out">
                                <svg class="size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close Modal</span>
                            </button>

                            {{-- The Modal --}}
                            <div class="flex items-center justify-center">

                                {{-- Modal Body --}}
                                <div
                                    class="relative flex items-center justify-center size-[75%] sm:size-[50%] md:size-[30%] lg:size-[25%] rounded">
                                    @if (isset($this->archiveCredential?->data['image_file_path']))
                                        <img src="{{ route('credentials.show', ['filename' => $this->archiveCredential?->data['image_file_path']]) }}"
                                            class="size-full object-contain" alt="ID Picture">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
