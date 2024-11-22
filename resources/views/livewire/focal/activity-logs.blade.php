<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">

    <div :class="{
        'md:ml-20': !open,
        'md:ml-20 xl:ml-64': open,
    }"
        class="md:ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">

            {{-- Page Name && Filter Button --}}
            <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between my-2 gap-2">

                <div class="flex items-center gap-2">
                    <livewire:sidebar.focal-bar />

                    <h1 class="text-xl md:text-base lg:text-xl font-semibold sm:font-bold xl:ms-2">
                        <span class="hidden sm:inline">Activity</span>
                        Logs
                    </h1>

                    {{-- Date Range picker --}}
                    <div id="logs-date-range" date-rangepicker datepicker-autohide
                        class="flex items-center gap-1 sm:gap-2 text-xs">

                        {{-- Start --}}
                        <div class="relative">
                            <div
                                class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </div>
                            <input type="text" id="start-date"
                                @change-date.camel="$dispatch('scroll-top-logs'); $wire.setStartDate($el.value);"
                                name="start" value="{{ $defaultStart }}" datepicker-max-date="{{ $defaultStart }}"
                                class="bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                placeholder="Select date start">
                        </div>

                        <span class="text-indigo-1100">to</span>

                        {{-- End --}}
                        <div class="relative">
                            <div
                                class="absolute text-indigo-900 inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 sm:size-5"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M126.172 51.100 C 118.773 54.379,116.446 59.627,116.423 73.084 L 116.406 83.277 108.377 84.175 C 76.942 87.687,54.343 110.299,50.788 141.797 C 49.249 155.427,50.152 292.689,51.825 299.512 C 57.852 324.094,76.839 342.796,101.297 348.245 C 110.697 350.339,289.303 350.339,298.703 348.245 C 323.161 342.796,342.148 324.094,348.175 299.512 C 349.833 292.748,350.753 155.358,349.228 142.055 C 345.573 110.146,323.241 87.708,291.623 84.175 L 283.594 83.277 283.594 73.042 C 283.594 56.745,279.386 50.721,267.587 50.126 C 254.712 49.475,250.000 55.397,250.000 72.227 L 250.000 82.813 200.000 82.813 L 150.000 82.813 150.000 72.227 C 150.000 58.930,148.409 55.162,141.242 51.486 C 137.800 49.721,129.749 49.515,126.172 51.100 M293.164 118.956 C 308.764 123.597,314.804 133.574,316.096 156.836 L 316.628 166.406 200.000 166.406 L 83.372 166.406 83.904 156.836 C 85.337 131.034,93.049 120.612,112.635 118.012 C 123.190 116.612,288.182 117.474,293.164 118.956 M316.400 237.305 C 316.390 292.595,315.764 296.879,306.321 306.321 C 296.160 316.483,296.978 316.405,200.000 316.405 C 103.022 316.405,103.840 316.483,93.679 306.321 C 84.236 296.879,83.610 292.595,83.600 237.305 L 83.594 200.000 200.000 200.000 L 316.406 200.000 316.400 237.305 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </div>
                            <input type="text" id="end-date"
                                @change-date.camel="$dispatch('scroll-top-logs'); $wire.setEndDate($el.value);"
                                name="end" value="{{ $defaultEnd }}" datepicker-min-date="{{ $defaultEnd }}"
                                class="bg-white border border-indigo-300 text-xs text-indigo-1100 rounded focus:ring-indigo-500 focus:border-indigo-500 block w-28 sm:w-32 py-1.5 ps-7 sm:ps-8"
                                placeholder="Select date end">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end w-full md:w-auto gap-2.5">

                    {{-- Loading State --}}
                    <svg class="size-5 text-indigo-900 animate-spin" wire:loading
                        wire:target="setStartDate, setEndDate, loadMoreLogs, sortTable, resultsFrequency, choose, clear"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    {{-- Search Bar --}}
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none {{ $this->logs->isNotEmpty() || $searchLogs ? 'text-indigo-800' : 'text-zinc-400' }}">

                            {{-- Loading Icon --}}
                            <svg class="size-3 animate-spin" wire:loading wire:target="searchLogs"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Search Icon --}}
                            <svg class="size-3" wire:loading.remove wire:target="searchLogs" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>

                        {{-- Search Input Bar --}}
                        <input type="text" id="project-search" maxlength="100" autocomplete="off"
                            @if ($this->logs->isEmpty() && !$searchLogs) disabled @endif
                            wire:model.live.debounce.300ms="searchLogs"
                            @input.debounce.300ms="$dispatch('scroll-top-logs');"
                            class="{{ $this->logs->isNotEmpty() || $searchLogs
                                ? 'text-indigo-1100 placeholder-indigo-500 border-indigo-300 bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500'
                                : 'text-zinc-400 placeholder-zinc-400 border-zinc-300 bg-zinc-50' }} outline-none duration-200 ease-in-out ps-7 py-1.5 text-xs border rounded w-full"
                            placeholder="Search for logs">
                    </div>

                    {{-- Filter Button --}}
                    <div x-data="{ open: false }" x-id="['button']" class="relative" x-on:click.outside="open = false">

                        <!-- Button -->
                        <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                            :aria-controls="$id('button')" type="button"
                            class="flex items-center justify-center gap-2 text-sm font-bold outline-none duration-200 ease-in-out rounded px-3 py-1 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                            FILTER
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
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
                            class="absolute right-0 flex flex-col justify-center gap-6 text-xs mt-2 p-3 w-72 z-50 rounded bg-white shadow-lg border border-indigo-300">

                            {{-- Results Frequency --}}
                            <div class="flex flex-col gap-2 text-xs">
                                <span class="text-sm text-indigo-1100 font-medium">Results Frequency</span>
                                <span class="flex items-center gap-2">
                                    <label for="by_100" @click="$dispatch('scroll-top-logs')"
                                        class="flex flex-1 items-center justify-center p-1.5 rounded cursor-pointer
                                        {{ $resultsFrequency === 100 ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500' }}">
                                        <input type="radio" id="by_100" value="100" class="hidden"
                                            wire:model.live="resultsFrequency">
                                        By 100
                                    </label>
                                    <label for="by_250" @click="$dispatch('scroll-top-logs')"
                                        class="flex flex-1 items-center justify-center p-1.5 rounded cursor-pointer
                                            {{ $resultsFrequency === 250 ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500' }}">
                                        <input type="radio" id="by_250" value="250" class="hidden"
                                            wire:model.live="resultsFrequency">
                                        By 250
                                    </label>
                                    <label for="by_500" @click="$dispatch('scroll-top-logs')"
                                        class="flex flex-1 items-center justify-center p-1.5 rounded cursor-pointer
                                            {{ $resultsFrequency === 500 ? 'bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50' : 'bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-500' }}">
                                        <input type="radio" id="by_500" value="500" class="hidden"
                                            wire:model.live="resultsFrequency">
                                        By 500
                                    </label>
                                </span>
                            </div>

                            {{-- Coordinator Filter --}}
                            <div class="flex flex-col gap-2 text-xs">
                                <span class="flex items-center justify-between gap-2">
                                    <span class="text-sm text-indigo-1100 font-medium">Sender Filter</span>
                                    <button type="button" wire:click="clear"
                                        class="flex items-center justify-center rounded p-1.5 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 font-bold">CLEAR</button>
                                </span>

                                <div x-data="{ show: false, currentUser: $wire.entangle('currentUser') }" class="relative">

                                    {{-- Current Coordinator --}}
                                    <button type="button" id="user_name"
                                        @click="show = !show; $wire.set('searchUser', null);"
                                        class="gap-3 text-left size-full border text-xs bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600 outline-none px-3 py-1.5 rounded flex items-center justify-between duration-200 ease-in-out">
                                        <span x-text="currentUser"></span>

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd"
                                                d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    {{-- Coordinator List Dropdown Content --}}
                                    <div x-show="show"
                                        @click.away="
                                            if(show == true) 
                                            {
                                                show = !show;
                                                $wire.set('searchUser', null);
                                            }
                                            "
                                        class="z-50 w-full end-0 top-full absolute bg-white text-indigo-1100 shadow-lg border border-indigo-300 rounded p-3 mt-2">
                                        <div class="relative flex items-center justify-center py-1 group">

                                            {{-- Loading Icon --}}
                                            <svg class="absolute left-2 size-3.5 animate-spin group-hover:text-indigo-700 group-focus:text-indigo-700 duration-200 ease-in-out pointer-events-none"
                                                wire:loading wire:target="searchUser"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Search Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="absolute left-2 size-3.5 group-hover:text-indigo-700 group-focus:text-indigo-700 duration-200 ease-in-out pointer-events-none"
                                                wire:loading.remove wire:target="searchUser" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <input id="searchUser" wire:model.live.debounce.300ms="searchUser"
                                                type="text"
                                                class="rounded w-full ps-7 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                                placeholder="Search users">
                                        </div>
                                        <ul
                                            class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-indigo-900">
                                            @if ($this->users->isNotEmpty())
                                                @foreach ($this->users as $key => $user)
                                                    <li wire:key={{ $key }}>
                                                        <button type="button"
                                                            wire:click="choose('{{ encrypt($user->id) }}')"
                                                            @click="show= !show; currentUser = '{{ $this->getFullName($user) }}';"
                                                            wire:loading.attr="disabled"
                                                            aria-label="{{ __('User') }}"
                                                            class="text-left w-full flex items-center justify-start px-3 py-1.5 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">
                                                            {{ $this->getFullName($user) }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li
                                                    class="flex flex-col flex-1 items-center justify-center size-full text-gray-500 font-medium px-4 py-2">
                                                    <p>
                                                        No users found.
                                                    </p>
                                                    <p class="">
                                                        Maybe try a different <span class="text-indigo-700">search
                                                            term</span>.
                                                    </p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-5">

                {{-- Activity Logs Table --}}
                <div class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">

                    @if ($this->logs->isNotEmpty())

                        <div id="logs-table" x-data="{ row: $wire.entangle('selectedRow') }"
                            class="relative h-[90vh] w-full overflow-y-auto overflow-x-auto scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                            <table
                                class="table-auto relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                                <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                    <tr>
                                        <th scope="col" class="pe-2 ps-4 py-2">
                                            #
                                        </th>
                                        <th scope="col" class="flex items-center px-2 py-2">
                                            <button type="button"
                                                class="flex items-center justify-center gap-1.5 p-1 uppercase"
                                                wire:click="$toggle('sortTable')">
                                                datetime
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-3 duration-300 ease-in"
                                                    :class="{
                                                        'rotate-0': {{ json_encode(!$sortTable) }},
                                                        'rotate-180': {{ json_encode($sortTable) }},
                                                    }"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                    height="400" viewBox="0, 0, 400,400">
                                                    <g>
                                                        <path
                                                            d="M37.242 100.487 C 26.798 103.696,21.407 116.801,26.295 127.101 C 27.981 130.654,181.151 293.459,185.434 296.251 C 194.013 301.842,205.987 301.842,214.566 296.251 C 218.849 293.459,372.019 130.654,373.705 127.101 C 378.681 116.616,373.192 103.638,362.430 100.442 C 356.342 98.634,43.132 98.677,37.242 100.487 "
                                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-2 py-2">
                                            description
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-center">
                                            sender
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-2xs sm:text-xs">
                                    @foreach ($this->logs as $key => $log)
                                        <tr wire:key="log-{{ $key }}"
                                            @click="
                                    if(row == {{ $key }}) {
                                        row = -1;
                                    } else {
                                        row = {{ $key }};
                                    }
                                    "
                                            class="relative border-b duration-100 h-12 ease-in-out whitespace-nowrap cursor-pointer"
                                            :class="{
                                                'bg-gray-100 text-indigo-900': row === {{ $key }},
                                            }">
                                            <th scope="row" class="pe-2 ps-4 py-2 font-medium">
                                                {{ $key + 1 }}
                                            </th>
                                            <td class="px-2 py-2">
                                                {{ \Carbon\Carbon::parse($log->log_timestamp)->format('M d, Y @ h:i:s a') }}
                                            </td>
                                            <td
                                                class="px-2 py-2 max-w-[400px] md:max-w-[300px] lg:max-w-[500px] overflow-x-auto whitespace-nowrap scrollbar-none select-text cursor-text">
                                                {{ $log->description }}
                                            </td>
                                            <td class="px-2 py-2 text-center">
                                                {{ $log->users_id ? $this->getFullName($log) : 'N/A' }}
                                            </td>
                                        </tr>
                                        @if ($this->logs->count() > 99 && $loop->last)
                                            <tr x-data x-intersect.full.once="$wire.loadMoreLogs();">

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="relative h-[90vh] w-full p-5">
                            <div
                                class="flex flex-col items-center justify-center size-full font-medium text-sm rounded border bg-gray-50 border-gray-300 text-gray-500">
                                @if (
                                    \Carbon\Carbon::createFromFormat('m/d/Y', $this->defaultStart)->format('Y-m-d') !==
                                        \Carbon\Carbon::parse($this->start)->format('Y-m-d') ||
                                        \Carbon\Carbon::createFromFormat('m/d/Y', $this->defaultEnd)->format('Y-m-d') !==
                                            \Carbon\Carbon::parse($this->end)->format('Y-m-d'))
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M258.564 14.258 L 255.078 16.016 223.171 86.271 C 189.248 160.964,189.695 159.840,191.763 165.255 C 194.699 172.943,201.219 174.981,211.704 171.489 C 219.157 169.007,218.365 168.478,222.665 178.814 C 229.152 194.405,232.459 195.912,248.183 190.444 L 258.475 186.864 262.496 196.362 C 269.593 213.126,271.560 213.773,293.359 206.515 C 311.301 200.541,306.681 207.645,339.259 135.938 C 373.537 60.488,371.647 65.626,367.302 59.688 C 363.777 54.871,364.463 55.218,315.234 33.334 C 264.370 10.724,265.091 10.967,258.564 14.258 M87.109 86.767 C 34.473 95.467,12.577 160.993,49.202 200.210 C 84.275 237.767,147.378 223.403,163.391 174.219 C 178.995 126.290,137.287 78.474,87.109 86.767 M111.523 114.086 C 114.639 115.102,117.188 116.129,117.188 116.369 C 117.188 116.609,104.544 129.445,89.091 144.894 L 60.994 172.982 59.209 169.108 C 56.806 163.891,56.583 146.546,58.835 140.088 C 66.298 118.695,89.912 107.041,111.523 114.086 M138.303 139.258 C 151.482 169.682,118.182 204.182,86.037 193.407 L 79.497 191.214 107.518 163.185 C 122.929 147.769,135.761 135.156,136.033 135.156 C 136.304 135.156,137.326 137.002,138.303 139.258 M200.878 210.998 C 198.677 211.879,180.346 228.914,177.883 232.367 C 176.185 234.747,176.169 235.091,175.781 277.344 L 175.391 319.922 172.070 326.563 C 164.766 341.167,146.913 360.164,138.596 362.178 C 130.745 364.080,126.972 373.500,130.842 381.538 C 133.762 387.603,133.527 387.563,164.844 387.323 L 192.578 387.109 197.266 384.563 C 202.402 381.773,205.755 378.915,210.592 373.203 L 213.762 369.460 227.780 376.551 C 248.724 387.146,245.937 386.722,294.461 386.702 C 339.606 386.682,339.541 386.690,342.950 381.100 C 348.036 372.761,347.012 370.866,325.777 349.335 C 299.351 322.539,301.591 329.933,301.575 269.451 L 301.563 223.669 299.786 221.033 C 294.120 212.624,285.864 212.657,276.362 221.126 C 271.016 225.891,272.082 226.041,265.010 219.525 C 251.883 207.429,247.491 207.286,235.249 218.555 C 226.868 226.270,228.461 226.160,221.260 219.525 C 212.432 211.390,206.304 208.827,200.878 210.998 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No activity logs found.</p>
                                    <p>
                                        Maybe try adjusting the <span class=" text-indigo-900">date range</span>.
                                    </p>
                                @elseif ($searchLogs)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M258.564 14.258 L 255.078 16.016 223.171 86.271 C 189.248 160.964,189.695 159.840,191.763 165.255 C 194.699 172.943,201.219 174.981,211.704 171.489 C 219.157 169.007,218.365 168.478,222.665 178.814 C 229.152 194.405,232.459 195.912,248.183 190.444 L 258.475 186.864 262.496 196.362 C 269.593 213.126,271.560 213.773,293.359 206.515 C 311.301 200.541,306.681 207.645,339.259 135.938 C 373.537 60.488,371.647 65.626,367.302 59.688 C 363.777 54.871,364.463 55.218,315.234 33.334 C 264.370 10.724,265.091 10.967,258.564 14.258 M87.109 86.767 C 34.473 95.467,12.577 160.993,49.202 200.210 C 84.275 237.767,147.378 223.403,163.391 174.219 C 178.995 126.290,137.287 78.474,87.109 86.767 M111.523 114.086 C 114.639 115.102,117.188 116.129,117.188 116.369 C 117.188 116.609,104.544 129.445,89.091 144.894 L 60.994 172.982 59.209 169.108 C 56.806 163.891,56.583 146.546,58.835 140.088 C 66.298 118.695,89.912 107.041,111.523 114.086 M138.303 139.258 C 151.482 169.682,118.182 204.182,86.037 193.407 L 79.497 191.214 107.518 163.185 C 122.929 147.769,135.761 135.156,136.033 135.156 C 136.304 135.156,137.326 137.002,138.303 139.258 M200.878 210.998 C 198.677 211.879,180.346 228.914,177.883 232.367 C 176.185 234.747,176.169 235.091,175.781 277.344 L 175.391 319.922 172.070 326.563 C 164.766 341.167,146.913 360.164,138.596 362.178 C 130.745 364.080,126.972 373.500,130.842 381.538 C 133.762 387.603,133.527 387.563,164.844 387.323 L 192.578 387.109 197.266 384.563 C 202.402 381.773,205.755 378.915,210.592 373.203 L 213.762 369.460 227.780 376.551 C 248.724 387.146,245.937 386.722,294.461 386.702 C 339.606 386.682,339.541 386.690,342.950 381.100 C 348.036 372.761,347.012 370.866,325.777 349.335 C 299.351 322.539,301.591 329.933,301.575 269.451 L 301.563 223.669 299.786 221.033 C 294.120 212.624,285.864 212.657,276.362 221.126 C 271.016 225.891,272.082 226.041,265.010 219.525 C 251.883 207.429,247.491 207.286,235.249 218.555 C 226.868 226.270,228.461 226.160,221.260 219.525 C 212.432 211.390,206.304 208.827,200.878 210.998 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No activity logs found.</p>
                                    <p>
                                        Maybe try a different <span class=" text-indigo-900">search term </span>
                                        or
                                        <span class=" text-indigo-900">keyword</span>.
                                    </p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-12 sm:size-20 mb-4 text-indigo-900 opacity-65"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                        viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M258.564 14.258 L 255.078 16.016 223.171 86.271 C 189.248 160.964,189.695 159.840,191.763 165.255 C 194.699 172.943,201.219 174.981,211.704 171.489 C 219.157 169.007,218.365 168.478,222.665 178.814 C 229.152 194.405,232.459 195.912,248.183 190.444 L 258.475 186.864 262.496 196.362 C 269.593 213.126,271.560 213.773,293.359 206.515 C 311.301 200.541,306.681 207.645,339.259 135.938 C 373.537 60.488,371.647 65.626,367.302 59.688 C 363.777 54.871,364.463 55.218,315.234 33.334 C 264.370 10.724,265.091 10.967,258.564 14.258 M87.109 86.767 C 34.473 95.467,12.577 160.993,49.202 200.210 C 84.275 237.767,147.378 223.403,163.391 174.219 C 178.995 126.290,137.287 78.474,87.109 86.767 M111.523 114.086 C 114.639 115.102,117.188 116.129,117.188 116.369 C 117.188 116.609,104.544 129.445,89.091 144.894 L 60.994 172.982 59.209 169.108 C 56.806 163.891,56.583 146.546,58.835 140.088 C 66.298 118.695,89.912 107.041,111.523 114.086 M138.303 139.258 C 151.482 169.682,118.182 204.182,86.037 193.407 L 79.497 191.214 107.518 163.185 C 122.929 147.769,135.761 135.156,136.033 135.156 C 136.304 135.156,137.326 137.002,138.303 139.258 M200.878 210.998 C 198.677 211.879,180.346 228.914,177.883 232.367 C 176.185 234.747,176.169 235.091,175.781 277.344 L 175.391 319.922 172.070 326.563 C 164.766 341.167,146.913 360.164,138.596 362.178 C 130.745 364.080,126.972 373.500,130.842 381.538 C 133.762 387.603,133.527 387.563,164.844 387.323 L 192.578 387.109 197.266 384.563 C 202.402 381.773,205.755 378.915,210.592 373.203 L 213.762 369.460 227.780 376.551 C 248.724 387.146,245.937 386.722,294.461 386.702 C 339.606 386.682,339.541 386.690,342.950 381.100 C 348.036 372.761,347.012 370.866,325.777 349.335 C 299.351 322.539,301.591 329.933,301.575 269.451 L 301.563 223.669 299.786 221.033 C 294.120 212.624,285.864 212.657,276.362 221.126 C 271.016 225.891,272.082 226.041,265.010 219.525 C 251.883 207.429,247.491 207.286,235.249 218.555 C 226.868 226.270,228.461 226.160,221.260 219.525 C 212.432 211.390,206.304 208.827,200.878 210.998 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                    <p>No activity logs found.</p>
                                    <p>
                                        Seems like this application is on a fresh start. Try <span
                                            class=" text-indigo-900">doing</span> something!
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
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

        $wire.on('scroll-top-logs', () => {
            const logsTable = document.getElementById('logs-table');
            if (logsTable) {
                logsTable.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

        });

        $wire.on('modifyStart', (event) => {
            const start = document.getElementById('start-date');
            start.value = event.newStart;

            const datepicker = FlowbiteInstances.getInstance('Datepicker', 'logs-date-range');
            console.log(datepicker);
            if (datepicker) {
                datepicker.setDate(event.newStart);
            }

            $dispatchSelf('init-reload');
        });
    </script>
@endscript
