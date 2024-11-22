<div x-cloak x-show="errorPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

    <!-- Modal -->
    <div x-show="errorPreviewModal" x-trap.noscroll="errorPreviewModal"
        class="relative h-full overflow-y-auto p-4 flex items-center justify-center select-none">

        <div class="size-full max-w-5xl">

            <!-- Modal content -->
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-lg font-semibold text-red-950">
                        Error Preview
                    </h1>

                    <div class="flex items-center justify-center">

                        {{-- Loading State --}}
                        <div class="flex items-center justify-center me-4 z-50 text-red-900" wire:loading
                            wire:target="">
                            <svg class="size-6 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        {{-- Close Button --}}
                        <button type="button" @click="errorPreviewModal = false;" wire:loading.attr="disabled"
                            wire:target="validateFile"
                            class="outline-none text-red-400 hover:bg-red-200 hover:text-red-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-200 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                <div class="px-5 pt-5 pb-10">
                    <div class="flex flex-col size-full text-red-950">
                        <div class="flex flex-col mb-6">
                            <span class="text-lg font-semibold text-red-700">
                                Error Results
                            </span>
                            <span class="text-sm font-normal text-gray-500">
                                This shows all of the errors of the selected beneficiary row.
                            </span>
                        </div>

                        <div
                            class="flex flex-col gap-4 h-[65vh] border rounded text-xs select-text border-gray-300 bg-gray-50 overflow-auto scrollbar-thin scrollbar-track-red-50 scrollbar-thumb-red-700">
                            @if ($this->beneficiary)
                                {{-- First Name --}}
                                @if ($this->checkIfErrors($this->errorResults['first_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            First Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['first_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['first_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Middle Name --}}
                                @if ($this->checkIfErrors($this->errorResults['middle_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Middle Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['middle_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['middle_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Last Name --}}
                                @if ($this->checkIfErrors($this->errorResults['last_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Last Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['last_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['last_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Extension Name --}}
                                @if ($this->checkIfErrors($this->errorResults['extension_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Extension Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['extension_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['extension_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Birthdate --}}
                                @if ($this->checkIfErrors($this->errorResults['birthdate']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Birthdate
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['birthdate'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['birthdate']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Contact Number --}}
                                @if ($this->checkIfErrors($this->errorResults['contact_num']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Contact Number
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['contact_num'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['contact_num']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Sex --}}
                                @if ($this->checkIfErrors($this->errorResults['sex']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Sex
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['sex'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['sex']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Civil Status --}}
                                @if ($this->checkIfErrors($this->errorResults['civil_status']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Civil Status
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['civil_status'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['civil_status']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Type of Beneficiary --}}
                                @if ($this->checkIfErrors($this->errorResults['beneficiary_type']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Type of Beneficiary
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['beneficiary_type'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['beneficiary_type']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- District --}}
                                @if ($this->checkIfErrors($this->errorResults['district']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            District
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['district'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['district']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Barangay Name --}}
                                @if ($this->checkIfErrors($this->errorResults['barangay_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Barangay Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['barangay_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['barangay_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Avg. Monthly Income --}}
                                @if ($this->checkIfErrors($this->errorResults['avg_monthly_income']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Avg. Monthly Income
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['avg_monthly_income'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['avg_monthly_income']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Occupation --}}
                                @if ($this->checkIfErrors($this->errorResults['occupation']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Occupation
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['occupation'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['occupation']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Dependent --}}
                                @if ($this->checkIfErrors($this->errorResults['dependent']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Dependent
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['dependent'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['dependent']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Type of ID --}}
                                @if ($this->checkIfErrors($this->errorResults['type_of_id']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Type of ID
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['type_of_id'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['type_of_id']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- ID Number --}}
                                @if ($this->checkIfErrors($this->errorResults['id_number']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            ID Number
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['id_number'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['id_number']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Spouse First Name --}}
                                @if ($this->checkIfErrors($this->errorResults['spouse_first_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Spouse First Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['spouse_first_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['spouse_first_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Spouse Middle Name --}}
                                @if ($this->checkIfErrors($this->errorResults['spouse_middle_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Spouse Middle Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['spouse_middle_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['spouse_middle_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Spouse Last Name --}}
                                @if ($this->checkIfErrors($this->errorResults['spouse_last_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Spouse Last Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['spouse_last_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['spouse_last_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Spouse Extension Name --}}
                                @if ($this->checkIfErrors($this->errorResults['spouse_extension_name']))
                                    <div class="flex flex-col p-2">
                                        <span class="text-base font-semibold gap-1.5">
                                            <span class="text-lg text-red-700 font-bold">#</span>
                                            Spouse Extension Name
                                            <span class="text-sm ms-0.5 px-2 py-0.5 rounded bg-red-200 text-red-800">
                                                {{ $this->beneficiary['spouse_extension_name'] ?? '-' }}
                                            </span>
                                        </span>
                                        @foreach ($this->onlyErrors($this->errorResults['spouse_extension_name']) as $error => $message)
                                            <span class="flex items-center py-2 px-6 gap-2">
                                                <span
                                                    class="font-medium text-red-500">{{ '• ' . mb_strtoupper($error, 'UTF-8') . ': ' }}</span>
                                                <span>{{ $message }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
