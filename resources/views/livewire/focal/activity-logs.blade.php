<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, show: false, profileShow: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    <livewire:sidebar.focal-bar />

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">
            {{-- Page Name && Filter Button --}}
            <div class="relative flex items-center justify-between my-2 mx-4">
                <h1 class="text-xl font-bold">Activity Logs</h1>

                <div class="flex items-center justify-end">
                    {{-- Loading State --}}
                    <div class="me-3 flex items-center justify-center z-50 text-indigo-900" wire:loading.flex>
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

                    {{-- Filter Button --}}
                    <div x-data="{ open: false }" x-id="['button']" class="relative" x-on:click.outside="open = false">
                        <!-- Button -->
                        <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                            :aria-controls="$id('button')" type="button"
                            class="flex items-center justify-center font-bold outline-none duration-200 ease-in-out rounded px-2 py-1 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                            FILTER
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 ms-2"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M55.859 51.091 C 37.210 57.030,26.929 76.899,32.690 95.866 C 35.051 103.642,34.376 102.847,97.852 172.610 L 156.250 236.794 156.253 298.670 C 156.256 359.035,156.294 360.609,157.808 363.093 C 161.323 368.857,170.292 370.737,175.953 366.895 C 184.355 361.193,241.520 314.546,242.553 312.549 C 243.578 310.566,243.750 304.971,243.750 273.514 L 243.750 236.794 302.148 172.610 C 365.624 102.847,364.949 103.642,367.310 95.866 C 372.533 78.673,364.634 60.468,348.673 52.908 L 343.359 50.391 201.172 50.243 C 87.833 50.126,58.350 50.298,55.859 51.091 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                    </path>
                                </g>
                            </svg>
                        </button>

                        <!-- Content -->
                        <div x-cloak x-ref="panel" x-show="open" x-transition.origin.top :id="$id('button')"
                            class="absolute text-xs right-0 mt-2 p-2 size-40 z-50 rounded bg-white shadow-lg border border-indigo-300 overflow-y-auto scrollbar-thin scrollbar-track-white scrollbar-thumb-indigo-700">
                            {{-- <button type="button" x-on:click="selectOption('e-Card / UMID')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                    e-Card / UMID
                                    </button> --}}
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                            Insert filters here
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-5">

                {{-- Activity Logs Table --}}
                <div class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">
                    <div x-data="{ row: $wire.entangle('selectedRow') }" id="logs-table"
                        class="relative h-[90vh] w-full overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                        <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                            <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                <tr>
                                    <th scope="col" class="pe-2 ps-4 py-2">
                                        #
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        datetime
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        description
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">
                                        sender
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="relative text-xs">
                                @foreach ($this->logs as $key => $log)
                                    <tr wire:key="log-{{ $key }}"
                                        @click="
                                    if(row == {{ $key }}) {
                                        row = -1;
                                    } else {
                                        row = {{ $key }};
                                    }
                                    "
                                        class="relative border-b duration-100 ease-in-out whitespace-nowrap cursor-pointer"
                                        :class="{
                                            'bg-gray-100 text-indigo-900': row === {{ $key }},
                                        }">
                                        <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="px-2 py-2">
                                            {{ \Carbon\Carbon::parse($log->log_timestamp)->format('M d, Y @ h:i:s a') }}
                                        </td>
                                        <td class="px-2 py-2">
                                            {{ $log->description }}
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            {{ $this->getFullName($log) }}
                                        </td>
                                    </tr>
                                    @if ($this->logs->count() > 20 && $loop->last)
                                        <tr x-data x-intersect.full.once="$wire.loadMoreLogs();">

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
