<div class="relative grid grid-cols-3 h-full place-content-start gap-x-4 gap-y-2 rounded">
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

    {{-- ID Picture --}}
    <div
        class="relative flex items-center justify-center col-span-1 my-2 mx-2 bg-green-500 w-[90%] aspect-square rounded-md">
        <h2
            class="text-center tracking-tighter font-semibold text-2xl xl:text-3xl 2xl:text-4xl m-0 p-0 text-green-1100">
            ID<br>
            Picture
        </h2>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="absolute -bottom-1 -right-1 w-8 p-1 rounded-full bg-white stroke-green-1000">
            <path fill-rule="evenodd"
                d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Zm8.25-3.75a.75.75 0 0 1 .75.75v2.25h2.25a.75.75 0 0 1 0 1.5h-2.25v2.25a.75.75 0 0 1-1.5 0v-2.25H7.5a.75.75 0 0 1 0-1.5h2.25V7.5a.75.75 0 0 1 .75-.75Z"
                clip-rule="evenodd" />
        </svg>
    </div>
    {{-- Name and Basic Info --}}
    <div class="col-span-2 col-start-2">
        <h1 class="text-lg truncate font-bold mt-2 mb-2 text-green-1100">{{ $full_name }}</h1>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Birthdate</h4>
                <p class="text-xs text-green-1100">{{ date('F j, Y', strtotime($preview['birthdate'])) }}</p>
            </div>
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Occupation</h4>
                <p class="text-xs text-green-1100">{{ $preview['occupation'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Age</h4>
                <p class="text-xs text-green-1100">{{ $preview['age'] }}</p>
            </div>
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Sex</h4>
                <p class="text-xs text-green-1100">{{ $preview['sex'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Civil Status</h4>
                <p class="text-xs text-green-1100">{{ $preview['civil_status'] }}</p>
            </div>
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Contact Number</h4>
                <p class="text-xs text-green-1100">{{ $preview['contact_num'] }}</p>
            </div>
        </div>
    </div>
    {{-- Identification --}}
    <div class="col-span-1 ml-2 mr-2">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <hr class="w-full border-gray-600">
            </div>
            <div class="relative flex justify-start text-sm">
                <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                    IDENTIFICATION
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">ID Type</h4>
                <p class="text-xs text-green-1100">{{ $preview['type_of_id'] }}</p>
            </div>
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">ID Number</h4>
                <p class="text-xs text-green-1100">{{ $preview['id_number'] }}</p>
            </div>
        </div>
    </div>
    {{-- Additional Information --}}
    <div class="col-span-2 col-start-2 mr-2">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <hr class="w-full border-gray-600">
            </div>
            <div class="relative flex justify-start text-sm">
                <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                    ADDITIONAL INFORMATION
                </p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight ">Type of Beneficiary</h4>
                <p class="text-xs text-green-1100">{{ $preview['beneficiary_type'] }}</p>
            </div>
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Dependent</h4>
                <p class="text-xs text-green-1100">{{ $preview['dependent'] === '-' ? 'None' : $preview['dependent'] }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight ">Avg. Monthly Income</h4>
                <p class="text-xs text-green-1100">
                    {{ $preview['avg_monthly_income'] === '-' ? '-' : '₱' . $preview['avg_monthly_income'] }}</p>
            </div>

            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Skills Training</h4>
                <p class="text-xs text-green-1100">{{ $preview['skills_training'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight ">Interested in
                    <br>Self-Employment
                </h4>
                <p class="text-xs text-green-1100">{{ $preview['self_employment'] }}</p>
            </div>
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight ">e-Payment <br>Account
                    Number</h4>
                <p class="text-xs text-green-1100">
                    {{ $preview['e_payment_acc_num'] === '-' ? 'None' : $preview['e_payment_acc_num'] }}</p>
            </div>
        </div>
    </div>
    {{-- Address --}}
    <div class="col-span-1 ml-2 mr-2">
        <div class="relative ">
            <div class="absolute inset-0 flex items-center">
                <hr class="w-full border-gray-600">
            </div>
            <div class="relative flex justify-start text-sm">
                <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                    ADDRESS
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">City/Municipality</h4>
                <p class="text-xs text-green-1100">{{ $preview['city_municipality'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Province</h4>
                <p class="text-xs text-green-1100">{{ $preview['province'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">District</h4>
                <p class="text-xs text-green-1100">{{ $preview['district'] }}</p>
            </div>
        </div>
    </div>
    {{-- Spouse Information --}}
    <div class="col-span-2 col-start-2 mr-2">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <hr class="w-full border-gray-600">
            </div>
            <div class="relative flex justify-start text-sm">
                <p class="text-xs font-bold uppercase pr-2 text-gray-500 bg-white">
                    SPOUSE INFORMATION
                </p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">First Name</h4>
                <p class="text-xs text-green-1100">{{ $preview['spouse_first_name'] }}</p>
            </div>


            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Extension Name</h4>
                <p class="text-xs text-green-1100">{{ $preview['spouse_extension_name'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight">Middle Name</h4>
                <p class="text-xs text-green-1100">{{ $preview['spouse_middle_name'] }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div class="mt-2">
                <h4 class="text-sm font-bold text-green-1000 leading-tight ">Last Name</h4>
                <p class="text-xs text-green-1100">{{ $preview['spouse_last_name'] }}</p>
            </div>
        </div>
    </div>
</div>
