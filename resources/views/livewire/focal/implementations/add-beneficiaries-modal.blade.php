<div x-cloak x-show="addBeneficiariesModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

    <!-- Modal -->
    <div x-show="addBeneficiariesModal" x-trap.noscroll="addBeneficiariesModal"
        class="relative h-full overflow-y-auto p-4 flex items-start justify-center select-none">

        <!-- Modal content -->
        <div class="w-full sm:h-auto">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-xs sm:text-sm md:text-lg font-semibold text-indigo-1100 ">
                        Add New Beneficiaries
                        @if (!$is_sectoral)
                            in
                            {{ $this->batch?->barangay_name }}
                        @endif
                        <span class="text-gray-500">{{ ' (' . $this->batch?->batch_num . ')' }}</span>
                    </h1>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="flex items-center justify-center z-50 text-indigo-900 me-2" wire:loading
                            wire:target="nameCheck, beneficiary_type, civil_status, birthdate, is_pwd, district, barangay_name">

                            {{-- Loading Circle --}}
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

                        <button type="button"
                            class="text-indigo-400 bg-transparent focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 outline-none rounded size-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-200 ease-in-out"
                            @click="$wire.resetBeneficiaries(); addBeneficiariesModal = false;">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                <!-- Modal body -->
                <form wire:submit.prevent="saveBeneficiary" class="pt-5 pb-12 px-10">
                    <div
                        class="grid gap-x-2.5 gap-y-6 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 text-xs">

                        {{-- Similarity Results --}}
                        <div x-data="{ expanded: $wire.entangle('expanded'), addReasonModal: $wire.entangle('addReasonModal') }" class="order-first relative col-span-full">

                            @if (isset($similarityResults))
                                <div class="flex items-center justify-between border rounded text-xs p-2 duration-200 ease-in-out"
                                    :class="{
                                        // A Perfect Duplicate && Unresolved Duplication Issue
                                        'border-orange-300 bg-orange-50 text-orange-900': {{ json_encode($isIneligible && $isPerfectDuplicate && !$isResolved) }},
                                        // A Perfect Duplicate && Unresolved Duplication Issue
                                        'border-red-300 bg-red-50 text-red-900': {{ json_encode($isPerfectDuplicate && !$isResolved) }},
                                        // A Possible Duplicate
                                        'border-amber-300 bg-amber-50 text-amber-900': {{ json_encode(!$isPerfectDuplicate && !$isResolved) }},
                                        // When a Perfect Duplicate is Resolved
                                        'border-green-300 bg-green-50 text-green-900': {{ json_encode($isResolved) }},
                                    }">

                                    @if ($isSameImplementation)
                                        {{-- If the user is attempting to add the beneficiary on the same implementation --}}
                                        <p class="inline mx-2">You cannot enter the same beneficiary on the same
                                            implementation.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="outline-none underline underline-offset-2 font-bold">Show
                                                possible
                                                duplicates</button>
                                        </p>
                                    @elseif($isIneligible)
                                        {{-- If the beneficiary has already applied more than 2 times this year --}}
                                        <p class="inline mx-2">This beneficiary
                                            has already applied more than twice (2) this year.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="outline-none underline underline-offset-2 font-bold">Show
                                                possible
                                                duplicates</button>
                                        </p>
                                    @elseif($isSamePending)
                                        {{-- If the beneficiary has already applied from another pending implementation project --}}
                                        <p class="inline mx-2">This beneficiary
                                            has already applied in another pending implementation project.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="outline-none underline underline-offset-2 font-bold">Show
                                                possible
                                                duplicates</button>
                                        </p>
                                    @elseif ($isPerfectDuplicate && !$isResolved)
                                        {{-- Perfect Duplicate && Unresolved --}}
                                        <p class="inline mx-2">This beneficiary
                                            has
                                            already
                                            been listed in the
                                            database this
                                            year.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="outline-none underline underline-offset-2 font-bold">Show
                                                possible
                                                duplicates</button>
                                        </p>

                                        @if (strtolower($beneficiary_type) === 'special case')
                                            <button type="button" @click="addReasonModal = !addReasonModal"
                                                class="px-2 py-1 rounded font-bold text-xs {{ $isPerfectDuplicate ? ' bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50' : ' bg-amber-700 hover:bg-amber-800 active:bg-amber-900 text-amber-50' }}">
                                                ADD REASON
                                            </button>
                                        @elseif (strtolower($beneficiary_type) !== 'special case')
                                            <p class="inline mx-2">Not a mistake? Change the
                                                <span
                                                    class="font-medium py-0.5 px-1 bg-indigo-100 text-indigo-1100 rounded">Beneficiary
                                                    Type</span> to <strong class="underline underline-offset-2">Special
                                                    Case</strong>
                                            </p>
                                        @endif

                                        {{-- Possible Duplicates && Unresolved --}}
                                    @elseif (!$isPerfectDuplicate && !$isResolved)
                                        <p class="inline mx-2">There are
                                            possible
                                            duplicates found
                                            associated with this name.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="outline-none underline underline-offset-2 font-bold">Show
                                                possible duplicates</button>
                                        </p>

                                        {{-- Resolved Duplication Issue --}}
                                    @elseif($isResolved)
                                        <p class="inline mx-2">Possible
                                            duplication is resolved.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="outline-none underline underline-offset-2 font-bold">Show
                                                possible duplicates</button>
                                        </p>

                                        <button type="button" @click="addReasonModal = !addReasonModal"
                                            class="outline-none px-2 py-1 rounded font-bold text-xs bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">VIEW
                                            REASON</button>
                                    @endif
                                </div>

                                {{-- TABLE AREA --}}
                                <div x-show="expanded"
                                    class="relative min-h-56 max-h-56 rounded border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                border-indigo-300 text-indigo-1100 scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                    <table class="relative w-full text-sm text-left select-auto">
                                        <thead
                                            class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-indigo-500 text-indigo-50">
                                            <tr>
                                                <th scope="col" class="ps-4 py-2">
                                                    similarity %
                                                </th>
                                                <th scope="col" class="p-2">
                                                    project number
                                                </th>
                                                <th scope="col" class="p-2">
                                                    batch number
                                                </th>
                                                <th scope="col" class="relative p-2">
                                                    <span data-popover-target="first" data-popover-trigger="hover">first
                                                        name</span>
                                                    <div data-popover id="first" role="tooltip"
                                                        class="normal-case absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                        <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                            <p>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-red-500 rounded"></span>
                                                                <span>Same first name as input</span>
                                                            </div>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-amber-500 rounded"></span>
                                                                <span>Different from input</span>
                                                            </div>
                                                            </p>
                                                        </div>
                                                        <div data-popper-arrow></div>
                                                    </div>

                                                </th>
                                                <th scope="col" class="relative p-2">
                                                    <span data-popover-target="middle"
                                                        data-popover-trigger="hover">middle name</span>
                                                    <div data-popover id="middle" role="tooltip"
                                                        class="normal-case absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                        <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                            <p>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-red-500 rounded"></span>
                                                                <span>Same middle name as input</span>
                                                            </div>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-amber-500 rounded"></span>
                                                                <span>Different from input</span>
                                                            </div>
                                                            </p>
                                                        </div>
                                                        <div data-popper-arrow></div>
                                                    </div>
                                                </th>
                                                <th scope="col" class="relative p-2">
                                                    <span data-popover-target="last" data-popover-trigger="hover">last
                                                        name</span>
                                                    <div data-popover id="last" role="tooltip"
                                                        class="normal-case absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                        <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                            <p>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-red-500 rounded"></span>
                                                                <span>Same last name as input</span>
                                                            </div>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-amber-500 rounded"></span>
                                                                <span>Different from input</span>
                                                            </div>
                                                            </p>
                                                        </div>
                                                        <div data-popper-arrow></div>
                                                    </div>
                                                </th>
                                                <th scope="col" class="relative p-2">
                                                    <span data-popover-target="ext"
                                                        data-popover-trigger="hover">ext.</span>
                                                    <div data-popover id="ext" role="tooltip"
                                                        class="normal-case absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                        <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                            <p>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-red-500 rounded"></span>
                                                                <span>Same extension name as input</span>
                                                            </div>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-amber-500 rounded"></span>
                                                                <span>Different from input</span>
                                                            </div>
                                                            </p>
                                                        </div>
                                                        <div data-popper-arrow></div>
                                                    </div>
                                                </th>
                                                <th scope="col" class="relative p-2">
                                                    <span data-popover-target="birthdate-sim"
                                                        data-popover-trigger="hover">birthdate</span>
                                                    <div data-popover id="birthdate-sim" role="tooltip"
                                                        class="normal-case absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                        <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                            <p>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-red-500 rounded"></span>
                                                                <span>Same birthdate as input</span>
                                                            </div>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-amber-500 rounded"></span>
                                                                <span>Different from input</span>
                                                            </div>
                                                            </p>
                                                        </div>
                                                        <div data-popper-arrow></div>
                                                    </div>
                                                </th>
                                                <th scope="col" class="p-2">
                                                    contact #
                                                </th>
                                                <th scope="col" class="relative p-2">
                                                    <span data-popover-target="barangay-sim"
                                                        data-popover-trigger="hover">barangay</span>
                                                    <div data-popover id="barangay-sim" role="tooltip"
                                                        class="normal-case absolute z-30 invisible inline-block text-indigo-50 transition-opacity duration-300 bg-gray-900 border-gray-300 border rounded-lg shadow-sm opacity-0">
                                                        <div class="flex flex-col text-xs font-medium p-2 gap-1">
                                                            <p>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-red-500 rounded"></span>
                                                                <span>Same barangay</span>
                                                            </div>
                                                            <div class="flex items-center gap-2"><span
                                                                    class="p-1.5 bg-amber-500 rounded"></span>
                                                                <span>Different barangay</span>
                                                            </div>
                                                            </p>
                                                        </div>
                                                        <div data-popper-arrow></div>
                                                    </div>
                                                </th>
                                                <th scope="col" class="p-2">
                                                    sex
                                                </th>
                                                <th scope="col" class="p-2">
                                                    age
                                                </th>
                                                <th scope="col" class="p-2">
                                                    beneficiary type
                                                </th>
                                                <th scope="col" class="p-2">
                                                    id type
                                                </th>
                                                <th scope="col" class="p-2">
                                                    id #
                                                </th>
                                                <th scope="col" class="p-2">
                                                    pwd
                                                </th>
                                                <th scope="col" class="p-2">
                                                    dependent
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs relative">
                                            @forelse ($similarityResults ?? [] as $key => $result)
                                                <tr wire:key='similiar-beneficiary-{{ $key }}'
                                                    class="relative whitespace-nowrap hover:bg-gray-50">
                                                    <td class="ps-4 py-2 font-medium">
                                                        {{ $result['coEfficient'] }}%
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['project_num'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['batch_num'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        <span
                                                            class="{{ mb_strtoupper($first_name, 'UTF-8') === mb_strtoupper($result['first_name'], 'UTF-8') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} z-50 rounded py-0.5 px-1.5">
                                                            {{ $result['first_name'] }}
                                                        </span>
                                                    </td>
                                                    <td class="p-2">
                                                        <span
                                                            class="{{ mb_strtoupper($middle_name, 'UTF-8') === mb_strtoupper($result['middle_name'], 'UTF-8') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['middle_name'] ?? '-' }}
                                                        </span>

                                                    </td>
                                                    <td class="p-2">
                                                        <span
                                                            class="{{ mb_strtoupper($last_name, 'UTF-8') === mb_strtoupper($result['last_name'], 'UTF-8') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['last_name'] }}
                                                        </span>

                                                    </td>
                                                    <td class="p-2">
                                                        <span
                                                            class="{{ mb_strtoupper($extension_name, 'UTF-8') === mb_strtoupper($result['extension_name'], 'UTF-8') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['extension_name'] ?? '-' }}
                                                        </span>

                                                    </td>
                                                    <td class="p-2">
                                                        <span
                                                            class="{{ \Carbon\Carbon::createFromFormat('m-d-Y', $birthdate)->format('Y-m-d') === \Carbon\Carbon::parse($result['birthdate'])->format('Y-m-d') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') }}
                                                        </span>

                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['contact_num'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        <span
                                                            class="{{ $this->batch?->barangay_name === $result['barangay_name'] ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['barangay_name'] }}
                                                        </span>

                                                    </td>
                                                    <td class="p-2 capitalize">
                                                        {{ $result['sex'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['age'] }}
                                                    </td>
                                                    <td class="p-2 capitalize">
                                                        {{ $result['beneficiary_type'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['type_of_id'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['id_number'] }}
                                                    </td>
                                                    <td class="p-2 capitalize">
                                                        {{ $result['is_pwd'] }}
                                                    </td>
                                                    <td class="p-2">
                                                        {{ $result['dependent'] ?? '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td>No possible duplicates found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Add Reason Modal --}}
                                <div x-cloak>
                                    <!-- Modal Backdrop -->
                                    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                        x-show="addReasonModal">
                                    </div>

                                    <!-- Modal -->
                                    <div x-show="addReasonModal" x-trap.noscroll="addReasonModal"
                                        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                        {{-- The Modal --}}
                                        <div class="relative w-full max-w-3xl max-h-full">
                                            <div class="relative bg-white rounded-md shadow">
                                                <form wire:submit.prevent="saveReason">
                                                    <!-- Modal Header -->
                                                    <div
                                                        class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                        <span class="flex items-center justify-center">
                                                            <h1
                                                                class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                                Add Reason
                                                            </h1>
                                                        </span>

                                                        <div class="flex items-center justify-center">
                                                            {{-- Loading State for Changes --}}
                                                            <div class="z-50 text-indigo-900" wire:loading>
                                                                <svg class="size-6 mr-3 -ml-1 animate-spin"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4">
                                                                    </circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <button type="button" @click="addReasonModal = false;"
                                                                class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                                                <svg class="size-3" aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 14 14">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                </svg>
                                                                <span class="sr-only">Close Modal</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <hr class="">

                                                    {{-- Modal Body --}}
                                                    <div class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                                                            {{-- Case Proof --}}
                                                            <div class="relative col-span-full sm:col-span-1 pb-4">
                                                                <div class="flex flex-col items-start">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="inline mb-1 font-medium text-indigo-1100">
                                                                            Case Proof <span
                                                                                class="text-gray-500">(optional)</span>
                                                                        </p>
                                                                    </div>

                                                                    {{-- Image Area --}}
                                                                    <label for="reason_image_file_path"
                                                                        class="{{ $errors->has('reason_image_file_path') ? 'border-red-300 bg-red-50 text-red-500' : 'border-indigo-300 bg-indigo-50 text-gray-500' }} flex flex-col items-center justify-center w-full h-full border-2 border-dashed rounded cursor-pointer">

                                                                        {{-- Image Preview --}}
                                                                        <div
                                                                            class="relative flex flex-col items-center justify-center w-full h-full aspect-square">

                                                                            {{-- Loading State for Changes --}}
                                                                            <div class="absolute flex items-center justify-center w-full h-full z-50 text-indigo-900"
                                                                                wire:loading.flex
                                                                                wire:target="reason_image_file_path">
                                                                                <div
                                                                                    class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
                                                                                    {{-- Darkness... --}}
                                                                                </div>

                                                                                {{-- Loading Circle --}}
                                                                                <svg class="size-6 animate-spin"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24">
                                                                                    <circle class="opacity-25"
                                                                                        cx="12" cy="12"
                                                                                        r="10" stroke="currentColor"
                                                                                        stroke-width="4">
                                                                                    </circle>
                                                                                    <path class="opacity-75"
                                                                                        fill="currentColor"
                                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                                    </path>
                                                                                </svg>
                                                                            </div>

                                                                            {{-- Preview --}}
                                                                            @if ($reason_image_file_path && !$errors->has('reason_image_file_path'))
                                                                                <img class="w-[95%]"
                                                                                    src="{{ $reason_image_file_path->temporaryUrl() }}">

                                                                                {{-- Default --}}
                                                                            @else
                                                                                <svg class="size-8 "
                                                                                    aria-hidden="true"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 20 16">
                                                                                    <path stroke="currentColor"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                                                </svg>
                                                                                <p class="mb-2 text-xs">
                                                                                    <span class="font-semibold">Click
                                                                                        to
                                                                                        upload</span> or
                                                                                    drag
                                                                                    and
                                                                                    drop
                                                                                </p>
                                                                                <p class="text-xs">
                                                                                    PNG or JPG (MAX. 5MB)
                                                                                </p>
                                                                            @endif
                                                                        </div>

                                                                        {{-- The Image itself --}}
                                                                        <input id="reason_image_file_path"
                                                                            wire:model="reason_image_file_path"
                                                                            type="file" accept=".png,.jpg,.jpeg"
                                                                            class="hidden" />
                                                                    </label>
                                                                </div>
                                                                @error('reason_image_file_path')
                                                                    <p
                                                                        class="text-center whitespace-nowrap w-full text-red-500 mt-1 z-10 text-xs">
                                                                        {{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            {{-- Image Description --}}
                                                            <div
                                                                class="relative flex flex-col justify-between col-span-full sm:col-span-2 pb-4">
                                                                <div class="flex flex-col">
                                                                    <label for="image_description"
                                                                        class="relative block mb-1 font-medium text-indigo-1100 ">
                                                                        <span class="relative">Description
                                                                            <span
                                                                                class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                                            </span>
                                                                        </span>
                                                                    </label>
                                                                    <textarea type="text" id="image_description" autocomplete="off" wire:model.blur="image_description"
                                                                        maxlength="255" rows="4"
                                                                        class="resize-none h-full text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('image_description') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                                                        placeholder="What is the reason for this special case?"></textarea>

                                                                    @error('image_description')
                                                                        <p
                                                                            class="text-red-500 whitespace-nowrap w-full mt-1 z-10 text-xs">
                                                                            {{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="flex justify-end w-full">
                                                                    <button type="button"
                                                                        wire:click.prevent="saveReason"
                                                                        class="px-2 py-1 rounded bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 font-bold text-lg">
                                                                        CONFIRM
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- First Name --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <label for="first_name" class="block mb-1 font-medium text-indigo-1100">
                                <span class="relative">First Name
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="first_name" autocomplete="off" wire:model.blur="first_name"
                                @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type first name">
                            @error('first_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Middle Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2 xl:col-span-2  pb-1">
                            <label for="middle_name" class="block mb-1  font-medium text-indigo-1100 ">Middle
                                Name</label>
                            <input type="text" id="middle_name" autocomplete="off" wire:model.blur="middle_name"
                                @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="(optional)">
                            @error('middle_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="last_name" class="block mb-1  font-medium text-indigo-1100 ">
                                <span class="relative">Last Name
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="last_name" autocomplete="off" wire:model.blur="last_name"
                                @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type last name">
                            @error('last_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Extension Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2 xl:col-span-1  pb-1">
                            <label for="extension_name" class="block mb-1 font-medium text-indigo-1100 ">Ext.
                                Name</label>
                            <input type="text" id="extension_name" autocomplete="off"
                                wire:model.blur="extension_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="III, Sr., etc.">
                            @error('extension_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs whitespace-nowrap">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Birthdate --}}
                        <div x-data="{ birthdate: $wire.entangle('birthdate') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="birthdate" class="block mb-1  font-medium text-indigo-1100 ">
                                <span class="relative">Birthdate
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <div class="absolute start-0 bottom-3.5 flex items-center ps-3 pointer-events-none">
                                <svg class="size-4 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'text-red-700' : 'text-indigo-900' }}"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input type="text" datepicker datepicker-autohide datepicker-format="mm-dd-yyyy"
                                datepicker-min-date='{{ $minDate }}' datepicker-max-date='{{ $maxDate }}'
                                id="birthdate" autocomplete="off" wire:model.blur="birthdate"
                                @change-date.camel="$wire.set('birthdate', $el.value); $wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full py-2 ps-9 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Select date">
                            @error('birthdate')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contact Number --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="contact_num" class="block mb-1 font-medium text-indigo-1100 ">
                                <span class="relative">Contact Number
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <div class="relative">
                                <div
                                    class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('contact_num') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                    <p
                                        class="flex text-center w-full relative items-center justify-center font-medium">
                                        +63
                                    </p>
                                </div>
                                <input x-mask="99999999999" type="text" min="0"
                                    @blur="if($el.value == '') { $wire.contact_num = null; }" autocomplete="off"
                                    id="contact_num" wire:model.blur="contact_num"
                                    class="text-xs outline-none border ps-12 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('contact_num') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50  border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                    placeholder="ex. 09123456789">
                            </div>
                            @error('contact_num')
                                <p class="whitespace-nowrap text-red-500 absolute left-2 top-full text-xs">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- E-payment Account Number --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="e_payment_acc_num" class="block mb-1 font-medium text-indigo-1100 ">E-payment
                                Account No.</label>
                            <input type="text" id="e_payment_acc_num" autocomplete="off"
                                wire:model.blur="e_payment_acc_num"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                                placeholder="Type e-payment account number">
                        </div>

                        {{-- Beneficiary Type --}}
                        <div class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2  pb-1">
                            <p class="mb-1 font-medium text-indigo-1100">Beneficiary Type</p>
                            <div x-data="{ open: false, beneficiary_type: $wire.entangle('beneficiary_type') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">

                                <!-- Button -->
                                <button type="button" @click="open = !open;" :aria-expanded="open"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="beneficiary_type"></span>

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open"
                                    @click.away="
                                    if(open) {
                                        open = false;
                                    }"
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                    <button type="button"
                                        @click="open = !open; $wire.set('beneficiary_type', 'Underemployed');"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Underemployed
                                    </button>

                                    @if (isset($similarityResults) && $isPerfectDuplicate && !$isSameImplementation && !$isIneligible)
                                        <button type="button"
                                            @click="open = !open; $wire.set('beneficiary_type', 'Special Case');"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                            Special Case
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Occupation --}}
                        <div x-data="{ avg: $wire.entangle('avg_monthly_income') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="occupation" class="block mb-1  font-medium text-indigo-1100 ">
                                <span class="relative">Occupation
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="occupation" autocomplete="off" wire:model.blur="occupation"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('occupation') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type occupation">
                            @error('occupation')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Sex --}}
                        <div class=" relative col-span-full sm:col-span-1  pb-1">
                            <p class="mb-1 font-medium text-indigo-1100 ">Sex</p>
                            <div x-data="{ open: false, sex: $wire.entangle('sex') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="sex"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open"
                                    @click.away="
                                    if(open) {
                                    open = false;
                                    }"
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                    <button type="button" @click="sex = 'Male'; open = false;"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Male
                                    </button>

                                    <button type="button" @click="sex = 'Female'; open = false;"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Female
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Civil Status --}}
                        <div
                            class=" relative col-span-full sm:col-span-1 md:col-span-2 lg:lg:col-span-1 xl:col-span-1  pb-1">
                            <p class="mb-1 font-medium text-indigo-1100 ">Civil Status</p>
                            <div x-data="{ open: false, civil_status: $wire.entangle('civil_status') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="civil_status"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open"
                                    @click.away="
                                if(open) {
                                open = false;
                                }"
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                    <button type="button"
                                        @click="civil_status = 'Single'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Single
                                    </button>

                                    <button type="button"
                                        @click="civil_status = 'Married'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Married
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Average Monthly Income --}}
                        <div x-data="{ occ: $wire.entangle('occupation') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="avg_monthly_income" class="block mb-1  font-medium text-indigo-1100 ">
                                <span class="relative">Average Monthly Income
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <div class="relative">
                                <div
                                    class="text-sm duration-200 ease-in-out absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none {{ $errors->has('avg_monthly_income') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                    <p
                                        class="flex text-center w-full relative items-center justify-center font-medium">
                                        ₱
                                    </p>
                                </div>
                                <input x-mask:dynamic="$money($input)" type="text" min="0"
                                    autocomplete="off" id="avg_monthly_income"
                                    @input="$wire.avg_monthly_income = $el.value;"
                                    class="text-xs outline-none border ps-10 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('avg_monthly_income') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50  border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                    placeholder="0.00">
                            </div>
                            @error('avg_monthly_income')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Dependent --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <div class="flex items-center">
                                <label for="dependent"
                                    class="relative flex items-center gap-1.5 mb-1 font-medium text-indigo-1100">
                                    Dependent <span class="text-gray-500">(must be 18+ years old)</span>
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </label>

                            </div>
                            <input type="text" id="dependent" autocomplete="off" wire:model.blur="dependent"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('dependent') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type dependent's name">
                            @error('dependent')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Interested in Wage Employment or Self-Employment --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <p class="mb-1 font-medium text-indigo-1100">Interested in Wage or
                                Self-Employment</p>
                            <div x-data="{ open: false, self_employment: $wire.entangle('self_employment') }" x-on:keydown.escape.prevent.stop="open = false;"
                                x-id="['self-employment-button']" class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="self_employment"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open"
                                    @click.away="
                                    if(open) {
                                    open = false;
                                    }"
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                    <button type="button" @click="self_employment = 'Yes'; open = false;"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Yes
                                    </button>

                                    <button type="button" @click="self_employment = 'No'; open = false;"
                                        class="flex
                                        items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b
                                        p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900
                                        focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100
                                        focus:bg-indigo-100 active:bg-indigo-200">
                                        No
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Skills Training Needed --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 {{ $is_sectoral ? 'lg:col-span-2' : '' }} md:col-span-6 xl:col-span-2 pb-1">
                            <label for="skills_training" class="block mb-1  font-medium text-indigo-1100 ">Skills
                                Training
                                Needed</label>
                            <input type="text" id="skills_training" autocomplete="off"
                                wire:model.blur="skills_training"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                                placeholder="(optional)">
                        </div>

                        @if ($is_sectoral)

                            {{-- District --}}
                            <div x-data="{ show: false, district: $wire.entangle('district') }"
                                class="relative flex flex-col col-span-full sm:col-span-2 md:col-span-3 lg:col-span-2">
                                <p class="block mb-1 font-medium text-indigo-1100 ">
                                    <span class="relative">District
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    </span>
                                </p>
                                <button type="button" id="district" @click="show = !show;"
                                    class="text-xs flex items-center justify-between outline-none border rounded w-full p-2 duration-200 ease-in-out {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} ">
                                    @if ($district)
                                        <span x-text="district"></span>
                                    @else
                                        <span>Select a district...</span>
                                    @endif

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-3 duration-200 ease-in-out">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- District content --}}
                                <div x-show="show" @click.away=" if(show == true) { show = !show; }"
                                    class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-300 rounded p-3 mt-2">
                                    <ul
                                        class="text-xs overflow-y-auto min-h-44 max-h-44 border border-gray-300 rounded p-2">
                                        @forelse ($this->districts as $key => $dist)
                                            <li wire:key={{ $key }}>
                                                <button type="button"
                                                    @click="show = !show; district = '{{ $dist }}'; $wire.resetBarangays();"
                                                    wire:loading.attr="disabled" aria-label="{{ __('Districts') }}"
                                                    class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $dist }}</button>
                                            </li>
                                        @empty
                                            <div class="size-full text-xs text-gray-500 p-2">
                                                No districts found
                                            </div>
                                        @endforelse
                                    </ul>
                                </div>
                                @error('district')
                                    <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Barangay --}}
                            <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }"
                                class="relative flex flex-col col-span-full sm:col-span-2 md:col-span-3 lg:col-span-2">
                                <span class="block mb-1 font-medium"
                                    :class="{
                                        'text-gray-500': {{ json_encode(!isset($district) || empty($district)) }},
                                        'text-indigo-1100': {{ json_encode(!$errors->has('barangay_name') && $district) }},
                                    }">
                                    <span class="relative">Barangay
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    </span>
                                </span>
                                <button type="button" id="barangay_name"
                                    @if ($district) @click="show = !show;"
                                    @else
                                        disabled @endif
                                    class="text-xs flex items-center justify-between p-2 outline-none border rounded w-full duration-200 ease-in-out"
                                    :class="{
                                        'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600': {{ json_encode($errors->has('barangay_name')) }},
                                        'border-gray-300 bg-gray-50 text-gray-500': {{ json_encode(!isset($district) || empty($district)) }},
                                        'border-indigo-300 bg-indigo-50 focus:ring-indigo-600 focus:border-indigo-600 text-indigo-1100': {{ json_encode(!$errors->has('barangay_name') && $district) }},
                                    }">
                                    @if ($barangay_name)
                                        <span x-text="barangay_name"></span>
                                    @elseif(!$district)
                                        <span class="inline sm:hidden md:inline">Choose a district
                                            first...</span>
                                        <span class="hidden sm:inline md:hidden">District first...</span>
                                    @else
                                        <span>Select a barangay...</span>
                                    @endif

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-3 duration-200 ease-in-out">
                                        <path fill-rule="evenodd"
                                            d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Barangay Name content --}}
                                <div x-show="show" @click.away=" if(show == true) { show = !show; }"
                                    class="w-full end-0 top-full absolute text-indigo-1100 bg-white shadow-lg border z-50 border-indigo-600 rounded p-3 mt-2">
                                    <div class="relative flex items-center justify-center py-1 group">

                                        {{-- Search Icon --}}
                                        <svg wire:loading.remove wire:target="searchBarangay"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="absolute start-0 ps-2 size-6 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none">
                                            <path fill-rule="evenodd"
                                                d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                clip-rule="evenodd" />
                                        </svg>

                                        {{-- Loading Icon --}}
                                        <svg wire:loading wire:target="searchBarangay"
                                            class="absolute start-0 ms-2 size-4 group-hover:text-indigo-500 group-focus:text-indigo-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <input id="searchBarangay" wire:model.live.debounce.300ms="searchBarangay"
                                            type="text" autocomplete="off"
                                            class="rounded w-full ps-8 text-xs text-indigo-1100 border-indigo-200 hover:placeholder-indigo-500 hover:border-indigo-500 focus:border-indigo-900 focus:ring-1 focus:ring-indigo-900 focus:outline-none duration-200 ease-in-out"
                                            placeholder="Search barangay">
                                    </div>
                                    <ul class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44">
                                        @forelse ($this->barangays as $key => $barangay)
                                            <li wire:key={{ $key }}>
                                                <button type="button"
                                                    @click="show = !show; barangay_name = '{{ $barangay }}'; $wire.$refresh();"
                                                    wire:loading.attr="disabled" aria-label="{{ __('Barangays') }}"
                                                    class="w-full flex items-center justify-start px-4 py-2 text-indigo-1100 hover:text-indigo-900 hover:bg-indigo-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                            </li>
                                        @empty
                                            <div class="h-full w-full text-xs text-gray-500 p-2">
                                                No barangays found
                                            </div>
                                        @endforelse
                                    </ul>
                                </div>
                                @error('barangay_name')
                                    <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                        @endif

                        {{-- ID Type --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-3  pb-1">
                            <p class="mb-1 font-medium text-indigo-1100 ">ID Type</p>
                            <div x-data="{ open: false, type_of_id: $wire.entangle('type_of_id') }" class="relative"
                                @click.away="
                                    if(open) {
                                    open = false;
                                    } ">
                                <!-- Button -->
                                <button @click="open = !open" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="type_of_id"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Dropdown Content -->
                                <div x-show="open"
                                    class="absolute left-0 mt-2 max-h-[10rem] w-full z-50 rounded bg-indigo-50 shadow-lg border overflow-y-scroll border-indigo-500 scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                    @if ($is_pwd === 'Yes')
                                        <button type="button"
                                            @click="type_of_id = 'Person\'s With Disability (PWD) ID'; open = false;"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                            Person's With Disability (PWD) ID
                                        </button>
                                    @endif

                                    @if ($birthdate && strtotime($birthdate) < strtotime(\Carbon\Carbon::now()->subYears(60)))
                                        <button type="button"
                                            @click="type_of_id = 'Senior Citizen ID'; open = false;"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                            Senior Citizen ID
                                        </button>
                                    @endif

                                    @foreach ($this->listOfIDs as $id)
                                        <button type="button"
                                            @click="type_of_id = `{{ $id }}`; open = false;"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                            {{ $id }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ID Number --}}
                        <div class=" relative col-span-full sm:col-span-1 md:col-span-2 lg:col-span-2  pb-1">
                            <label for="id_number" class="block mb-1 font-medium text-indigo-1100 ">
                                <span class="relative">ID Number
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="id_number" autocomplete="off" wire:model.blur="id_number"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('id_number') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type ID number">
                            @error('id_number')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is PWD? --}}
                        <div
                            class="pb-1 relative col-span-full sm:col-span-1 {{ $is_sectoral ? '' : 'xl:col-span-3' }}">
                            <div class="flex items-center">
                                <p class="inline mb-1 font-medium text-indigo-1100">Is PWD?</p>
                                {{-- Popover Thingy --}}
                                <div x-data="{ pop: false }" class="relative flex items-center"
                                    id="is-pwd-question-mark">
                                    <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-indigo-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                    </svg>
                                    {{-- Popover --}}
                                    <div id="is-pwd-popover"
                                        class="absolute z-50 bottom-full mb-2 left-0 md:left-auto md:right-0 text-xs whitespace-nowrap border border-gray-300 text-indigo-50 bg-gray-700 rounded p-2 shadow"
                                        x-show="pop">
                                        PWD stands for <b>P</b>erson <b>w</b>ith
                                        <b>D</b>isability
                                    </div>
                                </div>
                            </div>
                            <div x-data="{ open: false, is_pwd: $wire.entangle('is_pwd') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="is_pwd"></span>

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open" @click.away="open = false;"
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                    <button type="button" @click="is_pwd = 'Yes'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Yes
                                    </button>

                                    <button type="button" @click="is_pwd = 'No'; open = false; $wire.$refresh(); "
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        No
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Proof of Identity --}}
                        <div class=" w-full relative col-span-full lg:col-span-2  pb-1">
                            <div class="lg:absolute w-full flex flex-col items-start justify-center">

                                {{-- Header --}}
                                <div class="flex items-center">
                                    <p class="inline mb-1 font-medium text-indigo-1100">Proof of Identity <span
                                            class="text-gray-500">(optional)</span></p>

                                    {{-- Popover Thingy --}}
                                    <div x-data="{ pop: false }" class="relative flex items-center"
                                        id="identity-question-mark">
                                        <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-indigo-700"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                        </svg>
                                        {{-- Popover --}}
                                        <div id="identity-popover"
                                            class="absolute -left-20 sm:left-0 bottom-full mb-2 z-50 text-xs whitespace-nowrap border border-gray-300 text-indigo-50 bg-gray-700 rounded p-2 shadow"
                                            x-show="pop">
                                            It's basically an image of a beneficiary's ID card <br>
                                            to further prove that their identity is legitimate.
                                        </div>
                                    </div>
                                </div>

                                {{-- Image Area --}}
                                <label for="image_file_path" x-data="{ uploading: false, progress: 0 }"
                                    x-on:livewire-upload-start="uploading = true"
                                    x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                    x-on:livewire-upload-cancel="uploading = false"
                                    x-on:livewire-upload-error="uploading = false;"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress;"
                                    class="flex flex-col items-center justify-center size-full border-2 border-indigo-300 border-dashed rounded cursor-pointer bg-indigo-50">

                                    {{-- Image Preview --}}
                                    <div class="relative flex flex-col items-center justify-center py-4 size-full">
                                        {{-- Loading State for Changes --}}
                                        <div class="absolute flex items-center justify-center size-full z-50 text-indigo-900"
                                            x-show="uploading">
                                            <div
                                                class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
                                                {{-- Darkness... --}}
                                            </div>

                                            <!-- Progress Bar && Loading Icon -->
                                            <div class="absolute flex items-center justify-center w-3/4 z-50">
                                                <div class="w-full bg-gray-300 rounded-lg h-2">
                                                    <div class=" h-full bg-indigo-500 rounded-lg"
                                                        x-bind:style="'width: ' + progress + '%'">
                                                    </div>
                                                </div>
                                                <svg class="ms-2 size-5 text-indigo-900 animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Preview --}}
                                        @if ($image_file_path && !$errors->has('image_file_path'))
                                            <img class="w-32 h-20 object-contain"
                                                src="{{ $image_file_path->temporaryUrl() }}">

                                            {{-- Default --}}
                                        @else
                                            <svg class="size-8  text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-xs text-gray-500"><span class="font-semibold">Click to
                                                    upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 ">PNG or JPG (MAX. 5MB)</p>
                                        @endif
                                    </div>

                                    {{-- The Image itself --}}
                                    <input id="image_file_path" wire:model="image_file_path" type="file"
                                        accept=".png,.jpg,.jpeg" class="hidden" />
                                </label>
                            </div>
                            @error('image_file_path')
                                <p class="text-center whitespace-nowrap w-full text-red-500 absolute top-full text-xs">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Spouse First Name --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <label for="spouse_first_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">
                                <span class="relative"> Spouse
                                    First Name @if ($civil_status === 'Married')
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    @endif
                                </span>
                            </label>
                            <input type="text" id="spouse_first_name" autocomplete="off"
                                wire:model.blur="spouse_first_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
                                @else
                                    bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="Type spouse first name">
                            @error('spouse_first_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Spouse Middle Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-1 xl:col-span-2  pb-1">
                            <label for="spouse_middle_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Middle Name </label>
                            <input type="text" id="spouse_middle_name" autocomplete="off"
                                wire:model.blur="spouse_middle_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                @if ($civil_status === 'Married') {{ $errors->has('spouse_middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
                            @else
                            bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="(optional)">
                            @error('spouse_middle_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Spouse Last Name --}}
                        <div class=" relative flex flex-col col-span-full sm:col-span-2  pb-1">
                            <label for="spouse_last_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}"><span
                                    class="relative"> Spouse
                                    Last Name @if ($civil_status === 'Married')
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    @endif
                                </span>
                            </label>
                            <input type="text" id="spouse_last_name" autocomplete="off"
                                wire:model.blur="spouse_last_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            
                            @if ($civil_status === 'Married') {{ $errors->has('spouse_last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
                            @else
                            bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="Type spouse last name">
                            @error('spouse_last_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Spouse Extension Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-1 xl:col-span-1  pb-1">
                            <label for="spouse_extension_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Ext. Name</label>
                            <input type="text" id="spouse_extension_name" autocomplete="off"
                                wire:model.blur="spouse_extension_name"
                                @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                @if ($civil_status === 'Married') {{ $errors->has('spouse_extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
                            @else
                            bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="III, Sr., etc.">
                            @error('spouse_extension_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Modal footer --}}
                        <div class=" relative col-span-full w-full flex items-center justify-end">
                            <div class="flex items-center justify-between relative">

                                {{-- Loading State for Changes --}}
                                <button type="submit" wire:loading.attr="disabled" wire:target="saveBeneficiary"
                                    class="space-x-2 py-2 px-4 text-center text-white font-bold flex items-center bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 rounded-md">
                                    <p>ADD</p>

                                    {{-- Loading Icon --}}
                                    <svg class="size-5 animate-spin" wire:loading wire:target="saveBeneficiary"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Add Icon --}}
                                    <svg class="size-5" wire:loading.remove wire:target="saveBeneficiary"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M181.716 13.755 C 102.990 27.972,72.357 125.909,128.773 183.020 C 181.183 236.074,272.696 214.609,295.333 143.952 C 318.606 71.310,256.583 0.235,181.716 13.755 M99.463 202.398 C 60.552 222.138,32.625 260.960,26.197 304.247 C 24.209 317.636,24.493 355.569,26.629 361.939 C 30.506 373.502,39.024 382.022,50.561 385.877 C 55.355 387.479,56.490 387.500,136.304 387.500 L 217.188 387.500 209.475 379.883 C 171.918 342.791,164.644 284.345,192.232 241.338 C 195.148 236.792,195.136 236.719,191.484 236.719 C 169.055 236.719,137.545 223.179,116.259 204.396 L 108.691 197.717 99.463 202.398 M269.531 213.993 C 176.853 234.489,177.153 366.574,269.922 386.007 C 337.328 400.126,393.434 333.977,369.538 268.559 C 355.185 229.265,310.563 204.918,269.531 213.993 M293.788 265.042 C 298.143 267.977,299.417 271.062,299.832 279.675 L 300.199 287.301 307.825 287.668 C 319.184 288.215,324.219 292.002,324.219 300.000 C 324.219 307.998,319.184 311.785,307.825 312.332 L 300.199 312.699 299.832 320.325 C 299.285 331.684,295.498 336.719,287.500 336.719 C 279.502 336.719,275.715 331.684,275.168 320.325 L 274.801 312.699 267.175 312.332 C 255.816 311.785,250.781 307.998,250.781 300.000 C 250.781 292.002,255.816 288.215,267.175 287.668 L 274.801 287.301 275.168 279.675 C 275.715 268.316,279.502 263.281,287.500 263.281 C 290.019 263.281,291.997 263.835,293.788 265.042 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        $wire.on('init-reload', () => {
            setTimeout(() => {
                initFlowbite();
            }, 1);
        });
    </script>
@endscript
