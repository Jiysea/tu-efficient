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
    {{-- Upper/Header --}}
    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
        <div class="col-span-1">
            <h1 class="font-bold ml-4 my-2 text-indigo-1100">List of Beneficiaries</h1>
        </div>
        {{-- Search and Add Button | and Slots (for lower lg) --}}
        <div class="col-span-1 mx-2 flex items-center justify-end">
            <div class="relative me-2">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-3 h-3 text-indigo-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="beneficiary-search" maxlength="100"
                    class="ps-10 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded-lg w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Search for beneficiaries">
            </div>
            <button
                class="flex items-center bg-indigo-900 text-indigo-50 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-200 focus:border-indigo-200 focus:outline-indigo-200">
                ADD
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        </div>
    </div>



    {{-- Table --}}
    <div class="relative max-h-60 overflow-y-auto overflow-x-auto">

        <table class=" w-full text-sm text-left text-indigo-1100">
            <thead class="text-xs text-indigo-50 uppercase bg-indigo-600 sticky top-0 whitespace-nowrap">
                <tr>
                    <th scope="col" class="pe-2 border-r border-indigo-200 ps-4 py-2">
                        #
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        first name
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        middle name
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        last name
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        ext.
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        birthdate
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        contact #
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        sex
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        civil status
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        age
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        occupation
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        Senior Citizen
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        PWD
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        avg monthly income
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        e-payment acc num
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        beneficiary type
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        dependent
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
                        interested in s.e
                    </th>
                    <th scope="col" class="px-2 border-r border-indigo-200 py-2">
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
                    <tr wire:click="selectRow({{ $key }}, '{{ $encryptedId }}')"
                        wire:key="{{ $key }}"
                        class="{{ $selectedRow === $key ? 'bg-indigo-200' : '' }} border-b hover:bg-indigo-100 active:bg-indigo-200 whitespace-nowrap">
                        <th scope="row"
                            class="pe-2 border-r border-indigo-200 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap ">
                            {{ $key + 1 }}
                        </th>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['first_name'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['middle_name'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['last_name'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['extension_name'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['birthdate'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['contact_num'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['sex'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['civil_status'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['age'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['occupation'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['is_senior_citizen'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['is_pwd'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['avg_monthly_income'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['e_payment_acc_num'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['beneficiary_type'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['dependent'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['self_employment'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['skills_training'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['spouse_first_name'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['spouse_middle_name'] }}
                        </td>
                        <td class="px-2 border-r border-indigo-200">
                            {{ $beneficiary['spouse_last_name'] }}
                        </td>
                        <td class="px-2 ">
                            {{ $beneficiary['spouse_extension_name'] }}
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
