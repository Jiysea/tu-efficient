<div x-cloak class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50" x-show="viewLogModal"
    @keydown.escape.window="viewLogModal = false">

    <!-- Modal -->
    <div x-trap.noautofocus.noreturn.noscroll="viewLogModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-text">

        {{-- The Modal --}}
        <div class="relative">
            <div class="relative bg-white rounded-md shadow">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                        View Log Info
                    </h1>

                    <div class="flex items-center">
                        {{-- Close Button --}}
                        <button type="button" @click="viewLogModal = false;"
                            class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close Modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                {{-- Modal body --}}
                <div
                    class="flex flex-col items-center justify-center gap-6 pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">

                    {{-- Log Info --}}
                    <div class="flex flex-col items-center justify-center w-full gap-2">

                        {{-- Log Description --}}
                        <span class="flex items-center justify-start w-full gap-2 font-medium text-sm">
                            Log Description:
                            <span class="font-medium text-xs max-w-xl rounded px-2 py-1"
                                :class="{
                                    'bg-lime-50 text-lime-700': {{ json_encode($this->log?->log_type === 'create') }},
                                    'bg-amber-50 text-amber-700': {{ json_encode($this->log?->log_type === 'update') }},
                                    'bg-red-50 text-red-700': {{ json_encode($this->log?->log_type === 'delete') }},
                                    'bg-violet-50 text-violet-700': {{ json_encode($this->log?->log_type === 'archive') }},
                                    'bg-emerald-50 text-emerald-700': {{ json_encode($this->log?->log_type === 'restore') }},
                                    'bg-zinc-50 text-zinc-700': {{ json_encode($this->log?->log_type === 'initialize') }},
                                    'bg-black text-slate-50': {{ json_encode($this->log?->log_type === 'error') }},
                                }">{{ $this->log?->description }}</span>
                        </span>

                        <div class="grid grid-cols-2 gap-6 w-full">
                            <span class="flex items-center justify-start gap-2 font-medium text-sm">
                                Sender:
                                <span class="font-normal text-xs">{{ $this->sender }}</span>
                            </span>
                            <span class="flex items-center justify-start gap-2 font-medium text-sm">
                                Type of Log:
                                <span class="font-semibold text-xs rounded px-2 py-1"
                                    :class="{
                                        'bg-lime-200 text-lime-800': {{ json_encode($this->log?->log_type === 'create') }},
                                        'bg-amber-200 text-amber-800': {{ json_encode($this->log?->log_type === 'update') }},
                                        'bg-red-200 text-red-800': {{ json_encode($this->log?->log_type === 'delete') }},
                                        'bg-violet-200 text-violet-800': {{ json_encode($this->log?->log_type === 'archive') }},
                                        'bg-emerald-200 text-emerald-800': {{ json_encode($this->log?->log_type === 'restore') }},
                                        'bg-zinc-200 text-zinc-800': {{ json_encode($this->log?->log_type === 'initialize') }},
                                        'bg-black text-slate-50': {{ json_encode($this->log?->log_type === 'error') }},
                                    }">{{ strtoupper($this->log?->log_type) }}</span>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-6 w-full">
                            <span class="flex items-center justify-start gap-2 font-medium text-sm">
                                Office:
                                <span class="font-normal text-xs">{{ $this->office }}</span>
                            </span>
                            <span class="flex items-center justify-start gap-2 font-medium text-sm">
                                Log Date:
                                <span
                                    class="font-normal text-xs">{{ \Carbon\Carbon::parse($this->log?->log_timestamp)->format('F d, Y @ h:i:sa') }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Log in Particular Information --}}
                    <div class="flex flex-col items-center justify-center gap-2 w-full">
                        {{-- Label --}}
                        <div class="flex items-center justify-center w-full">
                            <span class="font-medium text-sm rounded px-2 py-1"
                                :class="{
                                    'bg-lime-50 text-lime-700': {{ json_encode($this->log?->log_type === 'create') }},
                                    'bg-amber-50 text-amber-700': {{ json_encode($this->log?->log_type === 'update') }},
                                    'bg-red-50 text-red-700': {{ json_encode($this->log?->log_type === 'delete') }},
                                    'bg-violet-50 text-violet-700': {{ json_encode($this->log?->log_type === 'archive') }},
                                    'bg-emerald-50 text-emerald-700': {{ json_encode($this->log?->log_type === 'restore') }},
                                    'bg-zinc-50 text-zinc-700': {{ json_encode($this->log?->log_type === 'initialize') }},
                                    'bg-black text-slate-50': {{ json_encode($this->log?->log_type === 'error') }},
                                }">
                                @if ($this->log?->log_type === 'delete')
                                    Information of the Data Deleted
                                @endif
                            </span>
                        </div>

                        {{-- Beneficiary Info Body --}}
                        @if ($this->log?->main_table === 'beneficiaries')
                            <div
                                class="font-mono text-xs flex flex-col items-center gap-1 justify-center w-full p-6 rounded-md border border-zinc-300 bg-zinc-50 text-zinc-950">

                                {{-- Header --}}
                                <span class=""></span>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- First Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        First Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->first_name }}
                                        </span>
                                    </span>

                                    {{-- Middle Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Middle Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->middle_name ?? '-' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Last Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Last Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->last_name }}
                                        </span>
                                    </span>

                                    {{-- Extension Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Extension Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->extension_name ?? '-' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Birthdate --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Birthdate:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->birthdate ? \Carbon\Carbon::parse($this->beneficiary?->birthdate)->format('M d, Y') : '-' }}
                                        </span>
                                    </span>

                                    {{-- Contact Number --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Contact Number:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->contact_num }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Age --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Age:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->age }}
                                        </span>
                                    </span>

                                    {{-- Sex --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Sex:
                                        <span class="font-normal">
                                            {{ mb_strtoupper($this->beneficiary?->sex, 'UTF-8') }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Civil Status --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Civil Status:
                                        <span class="font-normal">
                                            {{ mb_strtoupper($this->beneficiary?->civil_status, 'UTF-8') }}
                                        </span>
                                    </span>

                                    {{-- Dependent --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Dependent:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->dependent }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Province --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Province:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->province }}
                                        </span>
                                    </span>

                                    {{-- City/Municipality --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        City/Municipality:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->city_municipality }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- District --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        District:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->district }}
                                        </span>
                                    </span>

                                    {{-- Barangay --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Barangay:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->barangay_name }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Occupation --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Occupation:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->occupation ?? 'None' }}
                                        </span>
                                    </span>

                                    {{-- Avg. Monthly Income --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Avg. Monthly Income:
                                        <span class="font-normal">
                                            {{ '₱' . \App\Services\MoneyFormat::mask($this->beneficiary?->avg_monthly_income) }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Type of ID --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Type of ID:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->type_of_id }}
                                        </span>
                                    </span>

                                    {{-- ID Number --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        ID Number:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->id_number }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- E-Payment Account Number --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        E-Payment Account Number:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->e_payment_acc_num ?? '-' }}
                                        </span>
                                    </span>

                                    {{-- Type of Beneficiary --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Type of Beneficiary:
                                        <span class="font-normal">
                                            {{ mb_strtoupper($this->beneficiary?->beneficiary_type, 'UTF-8') }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Interested in Self/Wage Employment --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Interested in Self/Wage Employment:
                                        <span class="font-normal">
                                            {{ mb_strtoupper($this->beneficiary?->self_employment, 'UTF-8') }}
                                        </span>
                                    </span>

                                    {{-- Skills Training --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Skills Training:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->skills_training ?? '-' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Person With Disability --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Person With Disability:
                                        <span class="font-normal">
                                            {{ mb_strtoupper($this->beneficiary?->is_pwd, 'UTF-8') }}
                                        </span>
                                    </span>

                                    {{-- Senior Citizen --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Senior Citizen:
                                        <span class="font-normal">
                                            {{ mb_strtoupper($this->beneficiary?->is_senior_citizen, 'UTF-8') }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Spouse First Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Spouse First Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->spouse_first_name ?? '-' }}
                                        </span>
                                    </span>

                                    {{-- Spouse Middle Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Spouse Middle Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->spouse_middle_name ?? '-' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Spouse Last Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Spouse Last Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->spouse_last_name ?? '-' }}
                                        </span>
                                    </span>

                                    {{-- Spouse Extension Name --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Spouse Extension Name:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->spouse_extension_name ?? '-' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Is Contract of Service Signed --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Is Contract of Service Signed:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->is_signed ? 'YES' : 'NO' }}
                                        </span>
                                    </span>

                                    {{-- Is Payroll Recieved --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Is Payroll Recieved:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->is_paid ? 'YES' : 'NO' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full">
                                    {{-- Date Created --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Date Created:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->created_at }}
                                        </span>
                                    </span>

                                    {{-- Last Updated --}}
                                    <span class="flex items-center justify-start gap-2 font-medium">
                                        Last Updated:
                                        <span class="font-normal">
                                            {{ $this->beneficiary?->updated_at }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
