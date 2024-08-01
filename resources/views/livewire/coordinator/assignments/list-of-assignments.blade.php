<div class="relative w-full">

    <div class="relative flex justify-end max-h-12 items-center pb-2">
        {{-- Search and Filter Button | and Slots (for lower lg) --}}
        <div class="flex items-end justify-end">
            <div class="relative me-2">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-3 h-3 text-blue-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="beneficiary-search" maxlength="100"
                    class="ps-10 py-1 text-xs text-blue-1100 placeholder-blue-500 border border-blue-300 rounded-lg w-full bg-blue-50 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search for beneficiaries">
            </div>
            <button
                class="flex items-center bg-blue-900 text-blue-50 rounded-md px-3 py-1 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                        clip-rule="evenodd" />
                </svg>
                <p class="p-0 m-0">
                    FILTER
                </p>
            </button>
        </div>
    </div>
    {{-- Table --}}
    <div class="relative min-h-[83vh] max-h-[83vh] overflow-y-auto rounded bg-white">
        {{-- Loading State --}}
        <div class="absolute items-center justify-center z-50 min-h-full min-w-full text-blue-900" wire:loading.flex>
            <div class="absolute min-h-full min-w-full bg-black opacity-5">
            </div>
            <svg class="w-8 h-8 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
        <table class="w-full text-sm text-left text-blue-1100">
            <thead class="text-xs text-blue-50 uppercase bg-blue-600 sticky top-0">
                <tr>
                    <th scope="col" class="ps-4 pe-6 py-2">
                        batch #
                    </th>
                    <th scope="col" class="pr-2 py-2">
                        barangay
                    </th>
                    <th scope="col" class="pr-2 py-2 text-center">
                        a / t slots
                    </th>
                    <th scope="col" class="pr-2 py-2 text-center">
                        assignment status
                    </th>
                    <th scope="col" class="pr-2 py-2 text-center">
                        submission status
                    </th>
                    <th scope="col" class="pr-2 py-2">

                    </th>
                </tr>
            </thead>
            <tbody class="text-xs">
                @foreach ($batches as $key => $batch)
                    @php
                        $encryptedId = encrypt($batch['batches_id']);
                    @endphp
                    <tr wire:click='selectRow({{ $key }}, "{{ $encryptedId }}")'
                        wire:key='{{ $key }}'
                        class=" border-b {{ $selectedRow === $key ? 'bg-blue-200' : '' }} hover:bg-blue-100 whitespace-nowrap">
                        <th scope="row" class="ps-4 pe-6 py-2 font-semibold text-blue-1100 whitespace-nowrap">
                            {{ $batch['batch_num'] }}
                        </th>
                        <td class="pr-2 py-2">
                            {{ $batch['barangay_name'] }}
                        </td>
                        <td class="pr-2 py-2 text-center">
                            {{ $batch['current_slots'] . ' / ' . $batch['slots_allocated'] }}
                        </td>
                        <td class="py-2 px-2 text-center">
                            <p
                                class="px-1 py-1 text-xs font-bold rounded-xl {{ $batch['approval_status'] === 'APPROVED' ? 'bg-lime-300 text-lime-950' : 'bg-gray-300 text-gray-950' }}  text-center">
                                {{ $batch['approval_status'] }}
                            </p>
                        </td>
                        <td class="py-2 px-2 text-center">
                            <p
                                class="px-1 py-1 text-xs font-bold rounded-xl {{ $batch['approval_status'] === 'APPROVED' ? 'bg-lime-300 text-lime-950' : 'bg-gray-300 text-gray-950' }}  text-center">
                                {{ $batch['submission_status'] }}
                            </p>
                        </td>
                        <td class="py-2 flex">
                            <a href="#"
                                class="font-medium text-gray-700 hover:text-gray-500 active:text-gray-900 bg-transparent hover:bg-gray-100 active:bg-gray-200 rounded mx-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4">
                                    <path fill-rule="evenodd"
                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                        clip-rule="evenodd" />
                                </svg>

                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
