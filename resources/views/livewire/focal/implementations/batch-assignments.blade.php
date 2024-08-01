<div class="relative">
    {{-- Loading State --}}
    <div class="absolute items-center justify-center z-50 min-h-full min-w-full text-indigo-900" wire:loading.flex>
        <div class="absolute min-h-full min-w-full bg-black opacity-5">
        </div>
        <svg class="w-8 h-8 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </div>
    <div class="relative flex justify-between max-h-12 items-center">
        <div class="">
            <h1 class="font-bold ml-4 my-2 text-indigo-1100">Batch Assignments</h1>

        </div>
        {{-- Search and Add Button | and Slots (for lower lg) --}}
        <div class="mx-2 flex items-end justify-between">
            <button
                class="flex items-center bg-indigo-900 text-indigo-50 rounded-md px-3 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500">
                ASSIGN
                <svg class="w-4 ml-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0, 0, 400,400">
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

        <table class="relative w-full text-sm text-left text-indigo-1100">
            <thead class="text-xs text-indigo-50 uppercase bg-indigo-600 sticky top-0">
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
                        $encryptedId = encrypt($batch['batches_id']);
                    @endphp
                    <tr wire:click='selectRow({{ $key }}, "{{ $encryptedId }}")'
                        wire:key='{{ $key }}'
                        class="relative border-b {{ $selectedRow === $key ? 'bg-indigo-200' : '' }} hover:bg-indigo-100 whitespace-nowrap">
                        <th scope="row" class="ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap">
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
                                class="font-medium text-indigo-700 hover:text-indigo-500 active:text-indigo-900 bg-transparent hover:bg-indigo-100 active:bg-indigo-200 rounded mx-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4">
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
                                            class="flex items-center justify-start text-indigo-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-6 pe-2">
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
                                            class="flex items-center justify-start text-indigo-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-6 pe-2">
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
</div>
