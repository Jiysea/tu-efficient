<div class="relative flex flex-col">

    {{-- Title --}}
    <div class="text-xl ms-2 font-bold text-blue-1100 pb-2">
        List Overview
    </div>
    <div class="relative bg-white p-2 rounded text-blue-1100 text-xs font-semibold">
        {{-- Loading State --}}
        <div class="absolute items-center top-0 left-0 justify-center z-50 min-h-full min-w-full text-blue-900"
            wire:loading.flex>
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
        <div class="flex items-center justify-start my-1">
            Location: <p class="font-normal ps-2 truncate">Brgy.
                {{ $location['barangay_name'] . ', ' . $location['district'] . ', ' . $location['city_municipality'] }}
            </p>
        </div>
        <div class="flex items-center justify-start my-1">
            Access Code: <p class="ps-2 font-normal"> {{ $accessCode['access_code'] }}</p>
        </div>
        <div class="flex items-center justify-start my-1">
            Resubmissions: <p class="font-normal ps-2">0</p>
        </div>
    </div>
    <div class="flex items-center justify-end my-2 w-full text-sm">
        {{-- Found 3 special cases! --}}
        <button
            class="flex items-center bg-blue-900 text-blue-50 rounded-md px-3 py-1 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 focus:outline-blue-500">

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 mr-2">
                <path fill-rule="evenodd"
                    d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                    clip-rule="evenodd" />
            </svg>

            <p class="p-0 m-0">
                VIEW LIST
            </p>
        </button>
    </div>


    {{-- Table --}}
    <div class="relative min-h-[64vh] max-h-[64vh] overflow-y-auto rounded whitespace-nowrap bg-white">
        {{-- Loading State --}}
        <div class="absolute items-center justify-center z-50 min-h-full min-w-full w-full text-blue-900"
            wire:loading.flex>
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
                    <th scope="col" class="px-2 py-2">
                        #
                    </th>
                    <th scope="col" class="px-2 py-2">
                        full name
                    </th>
                    <th scope="col" class="px-2 py-2 text-center">
                        birthdate
                    </th>
                    <th scope="col" class="px-2 py-2 text-center">
                        contact #
                    </th>
                </tr>
            </thead>
            <tbody class="text-xs">
                @foreach ($beneficiaries as $key => $beneficiary)
                    @php
                        $encryptedId = encrypt($beneficiary['id']);
                    @endphp
                    <tr class=" border-b hover:bg-blue-100 whitespace-nowrap">
                        <th scope="row" class="px-2 py-2 font-semibold text-blue-1100 whitespace-nowrap">
                            {{ $key + 1 }}
                        </th>
                        <td class="px-2 py-2">
                            @php
                                $first = $beneficiary['first_name'];
                                $middle = $beneficiary['middle_name'];
                                $last = $beneficiary['last_name'];
                                $ext = $beneficiary['extension_name'];

                                if ($ext === '-' && $middle === '-') {
                                    $full_name = $first . ' ' . $last;
                                } elseif ($middle === '-' && $ext !== '-') {
                                    $full_name = $first . ' ' . $last . ' ' . $ext;
                                } elseif ($middle !== '-' && $ext === '-') {
                                    $full_name = $first . ' ' . $middle . ' ' . $last;
                                } else {
                                    $full_name = $first . ' ' . $middle . ' ' . $last . ' ' . $ext;
                                }
                            @endphp
                            {{ $full_name }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            {{ $beneficiary['birthdate'] }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            {{ $beneficiary['contact_num'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
