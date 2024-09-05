<div x-cloak x-data="{ open: true, show: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    <x-slot:favicons>
        <x-f-favicons />
    </x-slot>

    {{-- Batch Dropdown Content --}}
    <div id="batchDropdown" class="absolute z-50 p-2 hidden bg-white border rounded shadow">
        <input type="text" id="batch-search" maxlength="100" autocomplete="off"
            @input.debounce.300ms="$wire.searchBatches = $el.value; $wire.setBatchAssignments();"
            class="relative duration-200 outline-none ease-in-out mb-2 px-2 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search for batch numbers">
        <ul class="px-2 text-sm text-blue-1100 overflow-y-auto h-48 scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700"
            aria-labelledby="batchButton">
            @foreach ($batches as $key => $batch)
                @php
                    $encryptedId = encrypt($batch['id']);
                @endphp
                <li wire:key="batch-{{ $key }}">
                    <button type="button" wire:click="selectBatchRow({{ $key }}, '{{ $encryptedId }}')"
                        class="flex items-center w-full px-1 py-2 text-xs hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out cursor-pointer">
                        {{ $batch['batch_num'] }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

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
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-blue-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="start-date" name="start" type="text" value="{{ $defaultStart }}"
                            class="bg-white border border-blue-300 text-blue-1100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10"
                            placeholder="Select date start">
                    </div>
                    <span class="mx-4 text-blue-1100">to</span>

                    {{-- End --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-blue-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
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
                <div class="relative lg:col-span-3 h-full w-full rounded bg-white shadow">
                    {{-- Table Header --}}
                    <div class="relative max-h-12 my-2 flex items-center justify-between">
                        <div class="inline-flex items-center text-blue-900">

                            {{-- Batches Dropdown Button --}}
                            <button id="batchesButton" data-dropdown-placement="bottom-start"
                                data-dropdown-offset-distance="3" data-dropdown-toggle="batchDropdown"
                                class="flex items-center ms-4 py-1 px-2 text-xs outline-none font-semibold rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 duration-200 ease-in-out">
                                {{ $currentBatch }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

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
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M55.859 51.091 C 37.210 57.030,26.929 76.899,32.690 95.866 C 35.051 103.642,34.376 102.847,97.852 172.610 L 156.250 236.794 156.253 298.670 C 156.256 359.035,156.294 360.609,157.808 363.093 C 161.323 368.857,170.292 370.737,175.953 366.895 C 184.355 361.193,241.520 314.546,242.553 312.549 C 243.578 310.566,243.750 304.971,243.750 273.514 L 243.750 236.794 302.148 172.610 C 365.624 102.847,364.949 103.642,367.310 95.866 C 372.533 78.673,364.634 60.468,348.673 52.908 L 343.359 50.391 201.172 50.243 C 87.833 50.126,58.350 50.298,55.859 51.091 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-ref="panel" x-show="open" x-transition.origin.top :id="$id('button')"
                                    style="display: none;"
                                    class="absolute text-xs left-0 mt-2 h-40 w-40 z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                    {{-- <button type="button" x-on:click="selectOption('e-Card / UMID')"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            e-Card / UMID
                                        </button> --}}
                                    Insert filters here
                                </div>
                            </div>
                        </div>
                        {{-- Search Beneficiaries --}}
                        <div class="me-4 flex items-center justify-end">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg class="size-3 text-blue-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="beneficiary-search" maxlength="100" autocomplete="off"
                                    @input.debounce.300ms="$wire.searchBeneficiaries = $el.value; $wire.setBeneficiaryList();"
                                    class="duration-200 outline-none ease-in-out ps-7 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search for beneficiaries">
                            </div>
                        </div>
                    </div>

                    {{-- Beneficiaries Table --}}
                    @if ($beneficiaries)
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
                                    @foreach ($beneficiaries as $key => $beneficiary)
                                        @php
                                            $encryptedId = Crypt::encrypt($beneficiary['id']);
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
                                                {{ $beneficiary['sex'] }}
                                            </td>
                                            <td class="pr-2 py-2 text-center">
                                                {{ $beneficiary['birthdate'] }}
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
