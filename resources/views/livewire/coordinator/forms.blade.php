<x-slot:favicons>
    <x-c-favicons />
</x-slot>

<div x-cloak x-data="{ open: true, show: false, trapImport: false, trapDownload: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <livewire:sidebar.coordinator-bar />

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none text-blue-1100">

            {{-- Submissions Header --}}
            <div class="relative flex items-center justify-between my-2 mx-4">

                {{-- Header && Date Range Picker --}}
                <div class="flex items-center justify-center">
                    <h1 class="text-xl font-bold">Forms</h1>

                    {{-- Date Range picker --}}
                    <div id="implementations-date-range" date-rangepicker datepicker-autohide
                        class="flex items-center ms-4">

                        {{-- Start --}}
                        <div class="relative w-36 z-10">
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
                                class="bg-white w-full border border-blue-300 text-blue-1100 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block ps-10"
                                placeholder="Select date start">
                        </div>

                        <span class="mx-2 text-blue-1100 text-sm">to</span>

                        {{-- End --}}
                        <div class="relative w-36 z-10">
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
                                class="bg-white w-full border border-blue-300 text-blue-1100 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block ps-10"
                                placeholder="Select date end">
                        </div>
                    </div>
                </div>

                {{-- Right Side buttons --}}
                <div class="flex items-center justify-center gap-x-3">
                    {{-- Loading State --}}
                    <div class="flex items-center justify-start z-50 text-blue-900" wire:loading>
                        <svg class="size-8 me-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>

                    <button
                        class="flex items-center justify-center rounded text-sm px-2 py-1 font-bold outline-none duration-200 ease-in-out bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                        EXPORT
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ms-2"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M56.641 17.205 C 39.717 20.602,25.625 32.896,19.050 50.000 L 16.797 55.859 16.797 174.609 C 16.797 306.165,16.390 296.103,22.165 307.422 C 27.877 318.619,38.263 327.210,51.563 331.740 C 55.645 333.130,59.606 333.203,130.987 333.203 L 206.115 333.203 209.503 330.825 C 214.995 326.969,216.518 323.311,217.253 312.212 C 220.359 265.325,250.356 230.202,296.875 218.986 C 300.906 218.014,309.120 217.662,336.719 217.279 L 371.484 216.797 374.965 214.844 C 376.880 213.770,379.517 211.366,380.825 209.503 L 383.203 206.115 383.203 156.378 L 383.203 106.641 381.053 100.564 C 375.549 85.011,363.754 73.600,347.656 68.255 C 343.677 66.934,338.565 66.798,278.516 66.422 L 213.672 66.016 210.156 64.063 C 207.200 62.420,205.223 59.998,197.721 48.828 C 184.118 28.572,176.036 21.730,161.243 17.947 C 154.833 16.308,64.310 15.665,56.641 17.205 M328.390 250.310 C 319.246 253.254,316.406 258.585,316.406 272.810 L 316.406 282.813 290.333 282.813 C 255.179 282.813,250.000 285.019,250.000 300.000 C 250.000 314.910,255.369 317.188,290.520 317.188 L 316.406 317.188 316.423 327.148 C 316.452 343.477,321.319 350.034,333.373 349.980 C 340.465 349.947,342.435 348.536,362.222 329.318 C 391.191 301.182,391.190 301.647,362.347 271.806 C 340.778 249.491,337.459 247.390,328.390 250.310 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>
                    <button
                        class="flex items-center justify-center rounded text-sm px-2 py-1 font-bold outline-none duration-200 ease-in-out bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                        PRINT
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ms-2"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M124.097 38.581 C 108.307 44.266,104.391 51.875,104.050 77.539 L 103.804 96.094 200.000 96.094 L 296.196 96.094 295.950 77.539 C 295.628 53.341,293.166 47.530,280.275 40.539 L 275.391 37.891 201.172 37.722 C 141.440 37.586,126.396 37.753,124.097 38.581 M73.438 121.531 C 57.106 125.052,42.955 138.554,38.653 154.723 C 36.900 161.309,36.966 255.134,38.728 261.934 C 43.270 279.462,57.931 292.606,76.367 295.678 L 79.688 296.231 79.688 270.936 C 79.688 238.426,80.677 234.235,90.771 224.007 C 102.288 212.337,100.272 212.548,200.000 212.548 C 299.759 212.548,297.603 212.321,309.330 224.049 C 319.253 233.972,320.312 238.495,320.312 270.936 L 320.313 296.231 323.633 295.678 C 342.069 292.606,356.730 279.462,361.272 261.934 C 363.034 255.134,363.100 161.309,361.347 154.723 C 356.995 138.368,342.831 124.986,326.172 121.488 C 318.193 119.813,81.221 119.853,73.438 121.531 M289.059 156.342 C 297.012 160.812,297.255 171.147,289.543 176.905 C 285.733 179.749,263.827 179.322,259.899 176.327 C 252.667 170.811,253.131 160.836,260.827 156.373 C 265.105 153.892,284.662 153.871,289.059 156.342 M109.277 239.405 C 103.650 243.229,103.834 241.304,104.078 293.917 L 104.297 341.016 106.375 345.449 C 109.153 351.374,113.792 356.243,119.725 359.461 L 124.609 362.109 200.000 362.109 L 275.391 362.109 280.275 359.461 C 286.208 356.243,290.847 351.374,293.625 345.449 L 295.703 341.016 295.922 293.960 C 296.167 241.112,296.342 242.837,290.362 239.141 L 287.706 237.500 199.894 237.500 L 112.081 237.500 109.277 239.405 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Content --}}
            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-7">
                <div class="flex flex-col lg:col-span-2 w-full h-full rounded bg-white p-4">
                    <h1 class="font-bold text-lg mb-2">Configurations</h1>

                    {{-- Assignments Dropdown --}}
                    <div x-data="{ open: false }" x-id="['button']" class="relative mb-4"
                        x-on:click.outside="open = false">
                        <p class="text-xs font-medium mb-1">Assignments</p>
                        <!-- Button -->
                        <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                            :aria-controls="$id('button')" type="button"
                            class="flex items-center justify-between w-full outline-none rounded px-2 py-1 text-xs duration-200 ease-in-out bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                            {{ $currentAssignment }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="32" height="32"
                                fill="currentColor" viewBox="0 0 256 256">
                                <path
                                    d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z">
                                </path>
                            </svg>
                        </button>

                        <!-- Panel -->
                        <div x-ref="panel" x-show="open" x-transition.origin.top :id="$id('button')"
                            style="display: none;"
                            class="absolute text-xs left-0 mt-2 p-2 max-h-96 w-full z-50 rounded shadow border border-blue-300 bg-white overflow-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            <input type="text" wire:model.live.debounce.250ms="searchBatches"
                                class="px-2 py-1 mb-2 border border-blue-300 w-full text-xs"
                                placeholder="Type batch number or barangay">
                            @foreach ($this->assignments as $key => $assignment)
                                <button type="button"
                                    @click="open = !open; $wire.selectAssignment({{ $key }}, '{{ encrypt($assignment->id) }}')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                    {{ $assignment->batch_num . ' (' . $assignment->barangay_name . ')' }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Form Type Dropdown --}}
                    <div x-data="{ open: false }" x-id="['button']" class="relative"
                        x-on:click.outside="open = false">
                        <p class="text-xs font-medium mb-1">Form Type</p>
                        <!-- Button -->
                        <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                            :aria-controls="$id('button')" type="button"
                            class="flex items-center justify-between w-full outline-none rounded px-2 py-1 text-xs duration-200 ease-in-out bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                            {{ $currentFormType }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="32" height="32"
                                fill="currentColor" viewBox="0 0 256 256">
                                <path
                                    d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z">
                                </path>
                            </svg>
                        </button>

                        <!-- Panel -->
                        <div x-ref="panel" x-show="open" x-transition.origin.top :id="$id('button')"
                            style="display: none;"
                            class="absolute text-xs left-0 mt-2 p-2 h-40 w-full z-50 rounded bg-blue-50 text-blue-1100 shadow-lg border border-blue-500 overflow-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-blue-700">
                            @foreach ($this->formTypes as $key => $type)
                                <button type="button"
                                    @click="open = !open; $wire.selectFormType({{ $key }})"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                    {{ $type }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Forms Preview --}}
                <div class="flex flex-col lg:col-span-5 w-full h-[90vh] border bg-white rounded p-4">
                    <h1 class="font-bold text-lg mb-2">Preview</h1>
                </div>
            </div>
        </div>
    </div>
</div>
