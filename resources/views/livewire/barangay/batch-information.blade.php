<div class="relative bg-white rounded grid grid-cols-2 items-center">
    <ul class="my-2 ml-4 text-xs font-bold">
        <li class="flex items-center my-1"><span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 mr-1 stroke-green-900">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </span>
            <p class="text-green-900 mr-2">Access Code:</p>
            <p class="text-green-1100 font-medium">{{ $accessCode }}</p>
        </li>
        <li class="flex items-center my-1"><span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 mr-1 stroke-green-900">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>

            </span>
            <p class="text-green-900 mr-2">Coordinator/s:</p>
            <p class="text-green-1100 font-medium">
                @php
                    $counter = 1;
                @endphp
                @foreach ($users as $key => $user)
                    {{ $user['last_name'] }}
                    @if ($counter < count($users))
                        ,
                    @endif
                    @php
                        $counter++;
                    @endphp
                @endforeach
            </p>
        </li>
        <li class="flex items-center my-1"><span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 mr-1 stroke-green-900">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
            </span>
            <p class="text-green-900 mr-2">Location:</p>
            <p class="text-green-1100 font-medium">Brgy. {{ $location['barangay_name'] }}, {{ $location['district'] }}
            </p>
        </li>
    </ul>

    <div class="grid justify-end me-4">
        <div class="flex text-nowrap items-center row-start-2">
            <p class="mr-2 text-green-1100 font-bold text-xs xl:text-sm">SLOTS:</p>
            <span class="bg-green-500 rounded-md px-3 py-1">
                <p class="text-green-1100 font-bold text-xs xl:text-sm">{{ $slots['current_slots'] }} /
                    {{ $slots['slots_allocated'] }}</p>
            </span>
        </div>
    </div>
</div>
