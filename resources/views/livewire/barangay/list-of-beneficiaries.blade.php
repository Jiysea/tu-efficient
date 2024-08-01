<div class="relative bg-white rounded">
    {{-- Loading State --}}
    <div class="absolute items-center justify-center z-50 min-h-full min-w-full text-green-900" wire:loading.flex>
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
    {{-- Upper/Header --}}
    <div class="relative grid grid-cols-2 items-center max-h-12">
        <div class="">
            <h1 class="font-bold text-2xl ml-4 my-2 text-green-1100">List of Beneficiaries</h1>
        </div>
        {{-- Search and Add Button | and Slots (for lower lg) --}}
        <div class="mx-2 flex items-center justify-end">
            <div class="relative me-2">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-3 h-3 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" maxlength="100"
                    class="ps-10 text-xs p-1 text-green-1100 border border-gray-300 rounded w-full bg-gray-50 focus:ring-green-500 focus:border-green-500"
                    placeholder="Search for beneficiaries">
            </div>
            <button
                class="flex items-center justify-center bg-green-900 text-blue-50 rounded-md px-3 py-1 text-xs font-bold focus:ring-green-500 focus:border-green-500 focus:outline-green-500">
                Add
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-4 text-green-50 ml-2">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="relative max-h-[64vh] overflow-y-auto overflow-x-auto">
        <table class="w-full text-sm text-left text-green-1100">
            <thead class="text-xs text-green-50 uppercase bg-green-900 sticky top-0 whitespace-nowrap">
                <tr>
                    <th scope="col" class="px-2 py-2 text-center">
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
                    <th scope="col" class="px-2 py-2 text-center">
                        ext.
                    </th>
                    <th scope="col" class="px-2 py-2 text-center">
                        birthdate
                    </th>
                    <th scope="col" class="py-2">

                    </th>
                </tr>
            </thead>
            <tbody class="text-xs whitespace-nowrap">
                @foreach ($beneficiaries as $key => $beneficiary)
                    @php
                        $encryptedId = Crypt::encrypt($beneficiary['id']);
                    @endphp
                    <tr wire:key="{{ $key }}"
                        wire:click="selectRow({{ $key }}, '{{ $encryptedId }}')"
                        class="{{ $selectedRow === $key ? 'bg-green-200' : '' }} border-b hover:bg-green-100">
                        <th scope="row" class="px-2 py-2 text-center font-semibold text-green-1100">
                            {{ $key + 1 }}
                        </th>
                        <td class="px-2 py-2">
                            {{ $beneficiary['first_name'] }}
                        </td>
                        <td class="px-2 py-2">
                            {{ $beneficiary['middle_name'] }}
                        </td>
                        <td class="px-2 py-2">
                            {{ $beneficiary['last_name'] }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            {{ $beneficiary['extension_name'] }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            {{ date('Y-m-d', strtotime($beneficiary['birthdate'])) }}
                        </td>
                        <td class="py-2 flex">
                            <a href="#" class="font-medium text-amber-50 bg-amber-600 rounded-md mx-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4">
                                    <path d=" M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712
                    3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15
                    12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25
                    0 0 0 2.214-1.32L19.513 8.2Z" />
                                </svg>
                            </a>
                            <a href="#" class="font-medium text-red-50 bg-red-600 rounded-md mx-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4">
                                    <path fill-rule="evenodd"
                                        d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
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
