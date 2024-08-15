<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, show: false, profileShow: false, rotation: 0, caretRotate: 0, dashboardHover: false, implementationsHover: false, umanagementHover: false, alogsHover: false, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    @if (session()->has('success'))
        @foreach (session('success') as $message)
            <div x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeSuccessMessage('success', '{{ $loop->index }}');
            }, 2000)" x-show="show" x-transition:enter="fade-enter"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="fade-leave-active" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed left-6 bottom-6 flex items-center bg-red-300 text-red-900 rounded-lg text-sm sm:text-md font-bold px-4 py-3"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>
                <p>{{ $message }}</p>
            </div>
        @endforeach
    @endif


    <livewire:sidebar.focal-bar wire:key="{{ str()->random(50) }}" />

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">

            {{-- Nav Title and Time Dropdown --}}
            <div class="relative flex items-center my-2">
                <h1 class="text-xl font-bold me-4 ms-3">Implementations</h1>

                <div id="implementations-date-range" date-rangepicker datepicker-autohide class="flex items-center">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-indigo-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="start-date" name="start" type="text" value="{{ $defaultStart }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date start">
                    </div>
                    <span class="mx-4 text-indigo-1100">to</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-indigo-900 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="end-date" name="end" type="text" value="{{ $defaultEnd }}"
                            class="bg-white border border-indigo-300 text-indigo-1100 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10"
                            placeholder="Select date end">
                    </div>
                </div>

                {{-- Loading State --}}
                <div class="absolute items-center justify-end z-50 min-h-full min-w-full text-indigo-900"
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

            <div class="relative grid grid-cols-1 w-full h-full gap-4 lg:grid-cols-5">

                {{-- List of Projects --}}
                <div class="relative lg:col-span-3 h-full w-full rounded bg-white shadow">

                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
                        <div class="col-span-1">
                            <h1 class="font-bold ml-4 my-2 text-indigo-1100">List of Projects</h1>

                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="col-span-1 mx-2 flex items-center justify-end">
                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-3 h-3 text-indigo-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="project-search" maxlength="100"
                                    class="ps-10 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded-lg w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Search for project titles">
                            </div>
                            <button data-modal-target="create-modal" data-modal-toggle="create-modal"
                                class="flex items-center bg-indigo-900 text-indigo-50 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500">
                                CREATE
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 ml-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- List of Projects Table --}}
                    <div class="relative min-h-60 max-h-60 overflow-y-auto overflow-x-auto">
                        <table class="relative w-full text-sm text-left text-indigo-1100 whitespace-nowrap">
                            <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0">
                                <tr>
                                    <th scope="col" class="pe-2 ps-4 py-2">
                                        project #
                                    </th>
                                    <th scope="col" class="pr-6 py-2">
                                        project title
                                    </th>
                                    <th scope="col" class="pr-2 py-2 text-center">
                                        total slots
                                    </th>
                                    <th scope="col" class="pr-2 py-2 text-center">
                                        days of work
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="relative text-xs">
                                @foreach ($implementations as $key => $implementation)
                                    @php
                                        $encryptedId = Crypt::encrypt($implementation['id']);
                                    @endphp
                                    <tr wire:click='selectImplementationRow({{ $key }}, "{{ $encryptedId }}")'
                                        wire:key='{{ $key }}'
                                        class="relative border-b {{ $selectedImplementationRow === $key ? 'bg-indigo-200' : '' }} hover:bg-indigo-100 whitespace-nowrap">
                                        <th scope="row" class="pe-2 ps-4 py-2 font-medium text-indigo-1100">
                                            {{ $implementation['project_num'] }}
                                        </th>
                                        <td class="pr-6 py-2">
                                            {{ $implementation['project_title'] }}
                                        </td>
                                        <td class="pr-2 py-2 text-center">
                                            {{ $implementation['total_slots'] }}
                                        </td>
                                        <td class="pr-2 py-2 text-center">
                                            {{ $implementation['days_of_work'] }}
                                        </td>
                                        <td class="py-2 flex">
                                            <button @click.stop id="projectRowButton-{{ $key }}"
                                                data-dropdown-toggle="projectRowDropdown-{{ $key }}"
                                                class="z-0 font-medium text-indigo-700 hover:text-indigo-500 active:text-indigo-900 bg-transparent hover:bg-indigo-100 active:bg-indigo-200 rounded mx-1 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-4">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <!-- Project Row Dropdown menu -->
                                            <div id="projectRowDropdown-{{ $key }}"
                                                class="absolute z-10 hidden bg-white divide-y border divide-gray-100 rounded-lg shadow w-40">
                                                <ul class="py-2 text-sm text-gray-700"
                                                    aria-labelledby="projectRowButton-{{ $key }}">
                                                    <li>
                                                        <a aria-label="{{ __('View Project') }}"
                                                            class="flex items-center justify-start text-indigo-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-6 pe-2">
                                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>

                                                            View Project
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a aria-label="{{ __('Edit Project') }}"
                                                            class="flex items-center justify-start text-indigo-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-6 pe-2">
                                                                <path
                                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                                <path
                                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                            </svg>

                                                            Edit Project
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Create Button | Main Modal --}}
                    <livewire:focal.implementations.create-project-modal />
                </div>

                {{-- Batch Assignments --}}
                <div class="relative lg:col-span-2 h-full w-full rounded bg-white shadow">
                    <div class="relative flex justify-between max-h-12 items-center">
                        <div class="">
                            <h1 class="font-bold ml-4 my-2 text-blue-1100">Batch Assignments</h1>

                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="mx-2 flex items-end justify-between">
                            <button {{-- data-modal-target="assign-batches-modal" data-modal-toggle="assign-batches-modal" --}}
                                class="flex items-center bg-blue-900 text-blue-50 rounded-md px-3 py-1 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500">
                                ASSIGN
                                <svg class="w-4 ml-2" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M175.781 26.530 C 119.992 38.462,92.306 102.931,122.082 151.572 C 157.176 208.903,244.031 202.905,269.988 141.358 C 296.391 78.756,241.936 12.381,175.781 26.530 M107.813 191.177 C 85.230 195.102,68.383 210.260,61.975 232.422 C 59.986 239.301,59.428 318.137,61.292 328.937 C 65.057 350.758,80.886 368.049,102.049 373.462 C 107.795 374.931,110.968 375.000,173.282 375.000 L 238.502 375.000 229.212 365.425 C 219.425 355.339,216.440 350.863,214.479 343.332 C 205.443 308.642,247.642 282.676,274.554 306.365 L 278.297 309.660 291.520 296.252 C 306.255 281.311,310.725 278.355,321.367 276.518 L 326.718 275.594 326.363 256.352 C 325.910 231.742,323.949 224.404,314.486 211.897 C 303.479 197.348,289.113 191.080,266.681 191.040 L 253.285 191.016 250.200 193.359 C 248.504 194.648,244.688 197.549,241.722 199.806 C 212.635 221.931,168.906 220.569,140.934 196.668 C 134.265 190.970,133.021 190.608,120.533 190.731 C 114.611 190.790,108.887 190.991,107.813 191.177 M322.312 301.147 C 320.008 301.982,314.069 307.424,298.707 322.778 L 278.180 343.293 267.765 333.054 C 253.176 318.711,244.359 317.212,238.868 328.141 C 234.924 335.991,236.251 338.322,255.671 357.670 C 279.723 381.632,275.648 382.438,311.465 346.621 C 334.644 323.443,338.278 319.491,339.020 316.655 C 341.715 306.359,332.231 297.556,322.312 301.147 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                    {{-- Table --}}
                    <div class="relative min-h-60 max-h-60 overflow-y-auto">

                        <table class="relative w-full text-sm text-left text-blue-1100">
                            <thead class="text-xs z-20 text-blue-50 uppercase bg-blue-600 sticky top-0">
                                <tr>
                                    <th scope="col" class="ps-4 py-2">
                                        barangay
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">
                                        slots
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">
                                        status
                                    </th>
                                    <th scope="col" class="px-2 py-2">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-xs relative">
                                @foreach ($batches as $key => $batch)
                                    @php
                                        $batchEncryptedId = encrypt($batch['batches_id']);
                                    @endphp
                                    <tr wire:click='selectBatchRow({{ $key }}, "{{ $batchEncryptedId }}")'
                                        wire:key='{{ $key }}'
                                        class="relative border-b {{ $selectedBatchRow === $key ? 'bg-blue-200' : '' }} hover:bg-blue-100 whitespace-nowrap">
                                        <th scope="row"
                                            class="z-0 ps-4 py-2 font-medium text-blue-1100 whitespace-nowrap">
                                            {{ $batch['barangay_name'] }}
                                        </th>
                                        <td class="px-2 py-2 text-center">
                                            {{ $batch['current_slots'] . ' / ' . $batch['slots_allocated'] }}
                                        </td>
                                        <td class="px-2 py-2">
                                            <p
                                                class="px-1 py-1 text-xs font-bold rounded-xl {{ $batch['approval_status'] === 'APPROVED' ? 'bg-lime-300 text-lime-950' : 'bg-gray-300 text-gray-950' }}  text-center">
                                                {{ $batch['approval_status'] }}
                                            </p>
                                        </td>
                                        <td class="px-2 py-2 flex">
                                            <button @click.stop id="batchRowButton-{{ $key }}"
                                                data-dropdown-toggle="batchRowDropdown-{{ $key }}"
                                                class="z-0 font-medium text-blue-700 hover:text-blue-500 active:text-blue-900 bg-transparent hover:bg-blue-100 active:bg-blue-200 rounded mx-1 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-4">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <!-- Project Row Dropdown menu -->
                                            <div id="batchRowDropdown-{{ $key }}"
                                                class="absolute z-10 hidden bg-white divide-y border divide-gray-100 rounded-lg shadow w-40">
                                                <ul class="py-2 text-sm text-gray-700"
                                                    aria-labelledby="batchRowButton-{{ $key }}">
                                                    <li>
                                                        <a aria-label="{{ __('Batch Dropdown') }}"
                                                            class="flex items-center justify-start text-blue-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-6 pe-2">
                                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>

                                                            View Batch
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a aria-label="{{ __('Settings') }}"
                                                            class="flex items-center justify-start text-blue-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-6 pe-2">
                                                                <path
                                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                                <path
                                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                            </svg>
                                                            Edit Batch
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Assign Button | Main Modal --}}
                    {{-- <livewire:focal.implementations.assign-batches-modal /> --}}

                </div>

                {{-- List of Beneficiaries by Batch --}}
                <div class="relative lg:col-span-5 h-full w-full rounded bg-white shadow">
                    {{-- Upper/Header --}}
                    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
                        <div class="col-span-1">
                            <h1 class="font-bold ml-4 my-2 text-green-1100">List of Beneficiaries</h1>
                        </div>
                        {{-- Search and Add Button | and Slots (for lower lg) --}}
                        <div class="col-span-1 mx-2 flex items-center justify-end">
                            <div class="relative me-2">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-3 h-3 text-green-500 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="beneficiary-search" maxlength="100"
                                    class="ps-10 py-1 text-xs text-green-1100 placeholder-green-500 border border-green-300 rounded-lg w-full bg-green-50 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Search for beneficiaries">
                            </div>
                            <button
                                class="flex items-center bg-green-900 text-green-50 rounded-md px-4 py-1 text-sm font-bold focus:ring-green-200 focus:border-green-300 focus:outline-green-200">
                                ADD
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 ml-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="relative max-h-60 overflow-y-auto overflow-x-auto">

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
                                        sex
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        civil status
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        age
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        occupation
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        Senior Citizen
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        PWD
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        avg monthly income
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        e-payment acc num
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        beneficiary type
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        dependent
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        interested in s.e
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        skills training
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        spouse first name
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        spouse middle name
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        spouse last name
                                    </th>
                                    <th scope="col" class="px-2 py-2">
                                        spouse ext. name
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($beneficiaries as $key => $beneficiary)
                                    @php
                                        $encryptedId = Crypt::encrypt($beneficiary['id']);
                                    @endphp
                                    <tr wire:click="selectBeneficiaryRow({{ $key }}, '{{ $encryptedId }}')"
                                        wire:key="{{ $key }}"
                                        class="relative {{ $selectedBeneficiaryRow === $key ? 'bg-green-200' : '' }} border-b hover:bg-green-100 active:bg-green-200 whitespace-nowrap">
                                        <th scope="row"
                                            class="pe-2 border-r border-gray-200 ps-4 py-2 font-medium text-green-1100 whitespace-nowrap ">
                                            {{ $key + 1 }}
                                        </th>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['first_name'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['middle_name'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['last_name'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['extension_name'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['birthdate'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['contact_num'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['sex'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['civil_status'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['age'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['occupation'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['is_senior_citizen'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['is_pwd'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['avg_monthly_income'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['e_payment_acc_num'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['beneficiary_type'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['dependent'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['self_employment'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['skills_training'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['spouse_first_name'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['spouse_middle_name'] }}
                                        </td>
                                        <td class="px-2 border-r border-gray-200">
                                            {{ $beneficiary['spouse_last_name'] }}
                                        </td>
                                        <td class="px-2 ">
                                            {{ $beneficiary['spouse_extension_name'] }}
                                        </td>
                                        <td class="py-2 flex">
                                            <button @click.stop id="beneficiaryRowButton-{{ $key }}"
                                                data-dropdown-toggle="beneficiaryRowDropdown-{{ $key }}"
                                                class="z-0 font-medium text-green-700 hover:text-green-500 active:text-green-900 bg-transparent hover:bg-green-100 active:bg-green-200 rounded mx-1 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-4">
                                                    <path fill-rule="evenodd"
                                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <!-- Project Row Dropdown menu -->
                                            <div id="beneficiaryRowDropdown-{{ $key }}"
                                                class="absolute z-10 hidden bg-white divide-y border divide-gray-100 rounded-lg shadow w-40">
                                                <ul class="py-2 text-sm text-gray-700"
                                                    aria-labelledby="beneficiaryRowButton-{{ $key }}">
                                                    <li>
                                                        <a aria-label="{{ __('Projects Dropdown') }}"
                                                            class="flex items-center justify-start text-green-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-6 pe-2">
                                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>

                                                            View Beneficiary
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a aria-label="{{ __('Settings') }}"
                                                            class="flex items-center justify-start text-green-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor"
                                                                class="size-6 pe-2">
                                                                <path
                                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                                <path
                                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                            </svg>

                                                            Edit Beneficiary
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ID Picture Preview --}}
                {{-- <div class="lg:col-span-2 h-full w-full rounded bg-white shadow">
                    <livewire:focal.implementations.beneficiary-preview />
                </div> --}}

            </div>
        </div>
    </div>
</div>

@script
    <script>
        const datepickerStart = document.getElementById('start-date');
        const datepickerEnd = document.getElementById('end-date');

        datepickerStart.addEventListener('changeDate', function(event) {
            $wire.dispatchSelf('start-change', {
                value: datepickerStart.value
            });
        });

        datepickerEnd.addEventListener('changeDate', function(event) {
            $wire.dispatchSelf('end-change', {
                value: datepickerEnd.value
            });
        });
    </script>
@endscript
