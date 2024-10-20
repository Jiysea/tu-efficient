<!DOCTYPE html>
<html lang="en">

<head>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @page {
            size: A4;
            font-family: 'Inter', sans-serif;

            @top-left {
                content: "Page: " counter(page);
            }
        }
    </style>

    {{-- Layouts Guide --}}
    {{-- 

        # The session key-value
        'print-summary-information' => [
                'overall' => $overall,
                'seniors' => $seniors,
                'pwds' => $pwds,
                'implementation' => $implementation,
                'barangays' => $barangays
            ]
        
        # overall -> should be an array of `male` and `female` (int)
        # seniors -> should be an array of `male` and `female` (int)
        # pwds -> should be an array of `male` and `female` (int)
        # implementation -> should be an array that consist all
                            details about that implementation
        # barangays -> should be an array of the SUM per overall,
                        PWD, and Senior Citizens 
        
                        --}}
</head>

<body class="antialiased">

    {{-- Header --}}
    <div class="grid grid-cols-3">
        {{-- <span class="absolute top-0 left-0">
            1
        </span> --}}
        <span class="col-span-full flex items-center justify-center w-full font-bold text-2xl">
            <p>Summary of Beneficiaries</p>
        </span>
        {{-- <span class="absolute top-0 right-0">
            2
        </span> --}}
    </div>

    <hr class="text-gray-500 mx-20 my-5">

    {{-- Body --}}
    <div class="w-full text-sm">

        {{-- Implementation Information --}}
        <div class="grid grid-cols-2 w-full mb-4">
            <h1 class="col-span-full text-lg font-bold mb-2">
                • Implementation Information
            </h1>

            <p class="flex items-center gap-2"> <span class="font-medium">Project Number:</span>
                {{ $implementation['project_num'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Project Title:</span>
                {{ $implementation['project_title'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Province:</span>
                {{ $implementation['province'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">City/Municipality:</span>
                {{ $implementation['city_municipality'] }}
            </p>
            <p class="flex items-center gap-2"> <span class="font-medium">District:</span>
                {{ $implementation['district'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Budget:</span>
                ₱{{ \App\Services\MoneyFormat::mask($implementation['budget_amount']) }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Minimum Wage:</span>
                ₱{{ \App\Services\MoneyFormat::mask($implementation['minimum_wage']) }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Total Slots:</span>
                {{ $implementation['total_slots'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Days of Work:</span>
                {{ $implementation['days_of_work'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Purpose:</span>
                {{ $implementation['purpose'] }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Date Created:</span>
                {{ \Carbon\Carbon::parse($implementation['created_at'])->format('M d, Y @ h:i:sa') }} </p>
            <p class="flex items-center gap-2"> <span class="font-medium">Last Updated:</span>
                {{ \Carbon\Carbon::parse($implementation['updated_at'])->format('M d, Y @ h:i:sa') }} </p>
        </div>

        {{-- Total of Beneficiaries --}}
        <div class="grid grid-cols-3 w-full mb-4">
            <h1 class="col-span-full text-lg font-bold mb-2">
                • Total of Beneficiaries
            </h1>

            {{-- Overall --}}
            <div class="flex flex-col mb-2">
                <h2 class="flex items-center gap-2 font-medium">Overall
                    <span>({{ $overall['male'] + $overall['female'] }})</span>
                </h2>
                <div class="flex items-center gap-x-4">
                    @if ($overall !== 0)
                        <p>Male: {{ $overall['male'] }}</p>
                        <p>Female: {{ $overall['female'] }}</p>
                    @else
                        <p>Male: 0</p>
                        <p>Female: 0</p>
                    @endif
                </div>
            </div>

            {{-- PWDs --}}
            <div class="flex flex-col mb-2">
                <h2 class="flex items-center gap-2 font-medium">People with Disability
                    <span>({{ $pwds['male'] + $pwds['female'] }})</span>
                </h2>
                <div class="flex items-center gap-x-4">
                    @if ($pwds !== 0)
                        <p>Male: {{ $pwds['male'] }}</p>
                        <p>Female: {{ $pwds['female'] }}</p>
                    @else
                        <p>Male: 0</p>
                        <p>Female: 0</p>
                    @endif
                </div>
            </div>

            {{-- Seniors --}}
            <div class="flex flex-col mb-2">
                <h2 class="flex items-center gap-2 font-medium">Senior Citizens
                    <span>({{ $seniors['male'] + $seniors['female'] }})</span>
                </h2>
                <div class="flex items-center gap-x-4">
                    @if ($pwds !== 0)
                        <p>Male: {{ $seniors['male'] }}</p>
                        <p>Female: {{ $seniors['female'] }}</p>
                    @else
                        <p>Male: 0</p>
                        <p>Female: 0</p>
                    @endif
                </div>
            </div>
        </div>

        <hr class="text-gray-500 my-5">

        {{-- By Beneficiaries --}}
        <div class="text-lg font-bold mb-4">• Total By Barangay</div>
        @foreach ($barangays as $key => $barangay)
            <div class="grid grid-cols-2 w-full mb-4">
                <h1 class="col-span-full text-base font-medium mb-3 underline underline-offset-8 decoration-gray-300">
                    #{{ $key + 1 }} • Brgy. {{ $barangay['barangay_name'] }}
                    ({{ $barangay['total_male'] + $barangay['total_female'] }})
                </h1>

                <p class="flex items-center gap-2">
                    <span class="font-medium">Total Male:</span>
                    {{ $barangay['total_male'] }}
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-medium">Total Female:</span>
                    {{ $barangay['total_female'] }}
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-medium">PWD Male:</span>
                    {{ $barangay['total_pwd_male'] }}
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-medium">PWD Female:</span>
                    {{ $barangay['total_pwd_female'] }}
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-medium">Senior Male:</span>
                    {{ $barangay['total_senior_male'] }}
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-medium">Senior Female:</span>
                    {{ $barangay['total_senior_female'] }}
                </p>
            </div>
        @endforeach

    </div>
    @livewireScriptConfig
</body>

</html>
