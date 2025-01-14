<div x-cloak x-data="{ confirmTypeChangeModal: $wire.entangle('confirmTypeChangeModal') }" x-show="editBeneficiaryModal"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
    @keydown.window.escape="if(!confirmTypeChangeModal) {$wire.resetBeneficiaries(); $wire.clearAvgIncome(); editBeneficiaryModal = false;}">

    <!-- Modal -->
    <div x-show="editBeneficiaryModal" x-trap.noautofocus.noscroll="editBeneficiaryModal"
        class="relative h-full p-4 flex items-start justify-center overflow-y-auto z-50 select-none">

        <!-- Modal content -->
        <div class="w-full max-w-screen-xl">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-lg font-semibold text-blue-1100 ">
                        Edit Beneficiary
                    </h1>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="flex items-center justify-center z-50 text-blue-900 me-2" wire:loading
                            wire:target="nameCheck, beneficiary_type, civil_status, birthdate, is_pwd">

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
                            class="text-blue-400 bg-transparent focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900 outline-none rounded size-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-200 ease-in-out"
                            @click="$wire.resetBeneficiaries(); $wire.clearAvgIncome(); editBeneficiaryModal = false;">
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
                <form wire:submit.prevent="editBeneficiary" class="{{ $is_sectoral ? 'px-5 pt-5 pb-16' : 'p-5' }}">
                    <div
                        class="grid gap-x-2.5 gap-y-6 grid-cols-1 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 text-xs">

                        {{-- Similarity Results --}}
                        <div x-data="{ expanded: $wire.entangle('expanded'), addReasonModal: $wire.entangle('addReasonModal') }"
                            class="{{ $similarityResults && !$isOriginal ? '' : 'hidden' }} relative col-span-full">

                            <div class="flex items-center justify-between border rounded text-xs p-2 duration-200 ease-in-out"
                                :class="{
                                    // Ineligible Duplicate
                                    'border-orange-300 bg-orange-50 text-orange-900': {{ json_encode($isIneligible && $isPerfectDuplicate && !$isResolved) }},
                                    // A Perfect Duplicate && Unresolved Duplication Issue
                                    'border-red-300 bg-red-50 text-red-900': {{ json_encode($isPerfectDuplicate && !$isResolved) }},
                                    // A Possible Duplicate
                                    'border-amber-300 bg-amber-50 text-amber-900': {{ json_encode(!$isPerfectDuplicate && !$isResolved) }},
                                    // When a Perfect Duplicate is Resolved
                                    'border-green-300 bg-green-50 text-green-900': {{ json_encode($isResolved) }},
                                }">

                                @if ($isResolved)
                                    {{-- Resolved Duplication Issue --}}
                                    <p class="inline mx-2">Possible
                                        duplication is resolved.
                                        <button type="button" @click="expanded = ! expanded"
                                            class="outline-none underline underline-offset-2 font-bold">Show
                                            possible duplicates</button>
                                    </p>

                                    <button type="button" @click="addReasonModal = !addReasonModal"
                                        class="outline-none px-2 py-1 rounded font-bold text-xs bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">VIEW
                                        REASON</button>
                                @elseif ($isSameImplementation)
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
                                                class="font-medium py-0.5 px-1 bg-blue-100 text-blue-1100 rounded">Beneficiary
                                                Type</span> to <strong class="underline underline-offset-2">Special
                                                Case</strong>
                                        </p>
                                    @endif
                                @elseif (!$isPerfectDuplicate && !$isResolved)
                                    {{-- Possible Duplicates && Unresolved --}}
                                    <p class="inline mx-2">There are
                                        possible
                                        duplicates found
                                        associated with this name.
                                        <button type="button" @click="expanded = ! expanded"
                                            class="outline-none underline underline-offset-2 font-bold">Show
                                            possible duplicates</button>
                                    </p>
                                @endif
                            </div>

                            {{-- TABLE AREA --}}
                            <div x-show="expanded"
                                class="relative min-h-56 max-h-56 rounded border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
                                border-blue-300 text-blue-1100 scrollbar-track-blue-50 scrollbar-thumb-blue-700">
                                <table class="relative w-full text-sm text-left select-auto">
                                    <thead
                                        class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-blue-500 text-blue-50">
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
                                            <th scope="col" class="p-2">
                                                first name
                                            </th>
                                            <th scope="col" class="p-2">
                                                middle name
                                            </th>
                                            <th scope="col" class="p-2">
                                                last name
                                            </th>
                                            <th scope="col" class="p-2">
                                                ext.
                                            </th>
                                            <th scope="col" class="p-2">
                                                birthdate
                                            </th>
                                            <th scope="col" class="p-2">
                                                contact #
                                            </th>
                                            <th scope="col" class="p-2">
                                                barangay
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
                                            <tr wire:key='batch-{{ $key }}'
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
                                                        class="{{ mb_strtoupper($middle_name, 'UTF-8') === mb_strtoupper($result['middle_name'], 'UTF-8') || !isset($middle_name) === !isset($result['middle_name']) || empty($middle_name) === empty($result['middle_name']) ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
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
                                                        class="{{ mb_strtoupper($extension_name, 'UTF-8') === mb_strtoupper($result['extension_name'], 'UTF-8') || !isset($extension_name) === !isset($result['extension_name']) || empty($extension_name) === empty($result['extension_name']) ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                                        {{ $result['extension_name'] ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="p-2">
                                                    <span
                                                        class="{{ \App\Services\Essential::extract_date($birthdate, true, 'Y-m-d') ===
                                                        \App\Services\Essential::extract_date($result['birthdate'], true, 'Y-m-d')
                                                            ? 'bg-red-200 text-red-900'
                                                            : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
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

                            {{-- Legend --}}
                            <div x-show="expanded" class="flex flex-wrap rounded text-xs mt-2 text-zinc-900">
                                <p class="flex items-center justify-center gap-1">
                                    <span class="font-semibold">Legend:</span>
                                    <span class="flex items-center gap-1">
                                        <span class="bg-zinc-200 text-zinc-900 rounded p-1">User input is empty</span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="bg-red-200 text-red-900 rounded p-1">User input is similar to the
                                            possible duplicate field</span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="bg-amber-200 text-amber-900 rounded p-1">User input is different
                                            from the
                                            possible duplicate field</span>
                                    </span>
                                </p>
                            </div>

                            {{-- Add Reason Modal --}}
                            <div x-cloak x-show="addReasonModal"
                                class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

                                <!-- Modal -->
                                <div x-show="addReasonModal" x-trap.noautofocus.noscroll="addReasonModal"
                                    class="relative h-full overflow-y-auto p-4 flex items-start sm:items-center justify-center select-none">

                                    {{-- The Modal --}}
                                    <div class="w-full max-w-3xl">
                                        <div class="relative bg-white rounded-md shadow">
                                            <form wire:submit.prevent="saveReason">
                                                <!-- Modal Header -->
                                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                    <span class="flex items-center justify-center">
                                                        <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                                                            Add Reason
                                                        </h1>
                                                    </span>

                                                    <div class="flex items-center justify-center">
                                                        {{-- Loading State for Changes --}}
                                                        <div class="z-50 text-blue-900" wire:loading
                                                            wire:target="saveReason, resetReason">
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
                                                    </div>
                                                </div>

                                                <hr class="">

                                                {{-- Modal Body --}}
                                                <div class="pt-5 pb-10 px-5 md:px-12 text-blue-1100 text-xs">
                                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                                                        {{-- Case Proof --}}
                                                        <div class="relative col-span-full sm:col-span-1">
                                                            <div class="relative flex flex-col items-start">
                                                                <div class="flex items-center">
                                                                    <p class="inline mb-1 font-medium text-blue-1100">
                                                                        Case Proof <span
                                                                            class="text-gray-500">(optional)</span>
                                                                    </p>
                                                                </div>

                                                                {{-- Image Area --}}
                                                                <label for="edit_reason_image_file_path"
                                                                    id="edit_reason_dropzone" x-data="{ uploading: false, progress: 0 }"
                                                                    x-on:livewire-upload-start="uploading = true"
                                                                    x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                                                    x-on:livewire-upload-cancel="uploading = false"
                                                                    x-on:livewire-upload-error="uploading = false;"
                                                                    x-on:livewire-upload-progress="progress = $event.detail.progress;"
                                                                    class="{{ $errors->has('reason_image_file_path')
                                                                        ? 'border-red-300 bg-red-50 text-red-500 hover:text-orange-500'
                                                                        : 'border-blue-300 bg-blue-50 text-gray-500 hover:text-blue-500' }} 
                                                                                z-10 relative flex flex-col items-center justify-center size-full border-2 border-dashed rounded cursor-pointer duration-200 ease-in-out
                                                                                overflow-hidden">

                                                                    {{-- Image Preview --}}
                                                                    <div
                                                                        class="relative flex flex-col items-center justify-center size-full aspect-square">

                                                                        {{-- Loading State for Changes --}}
                                                                        <div class="absolute flex items-center justify-center size-full"
                                                                            wire:loading.flex
                                                                            wire:target="reason_image_file_path">
                                                                            <div
                                                                                class="absolute bg-black opacity-5 rounded min-w-full min-h-full">
                                                                                {{-- Darkness... --}}
                                                                            </div>

                                                                            {{-- Progress Bar & Loading Icon --}}
                                                                            <div x-show="uploading"
                                                                                class="absolute flex items-center justify-center w-3/4">
                                                                                <div
                                                                                    class="w-full bg-gray-300 rounded-lg h-2">
                                                                                    <div class="duration-200 ease-in-out h-full {{ $errors->has('reason_image_file_path') ? 'bg-red-500' : 'bg-blue-500' }} rounded-lg"
                                                                                        :style="'width: ' + progress +
                                                                                            '%'">
                                                                                    </div>
                                                                                </div>

                                                                                <svg class="ms-2 size-5 {{ $errors->has('reason_image_file_path') ? 'text-red-700' : 'text-blue-700' }} animate-spin"
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
                                                                        </div>

                                                                        @if (is_object($reason_image_file_path) && !$errors->has('reason_image_file_path'))
                                                                            {{-- Show the inserted image from the user --}}
                                                                            <img class="size-[90%] object-contain"
                                                                                src="{{ $reason_image_file_path->temporaryUrl() }}">
                                                                        @elseif($reason_saved_image_path && !$errors->has('reason_image_file_path'))
                                                                            {{-- Show the saved image from the database --}}
                                                                            <img class="size-[90%] object-contain"
                                                                                src="{{ route('credentials.show', ['filename' => $reason_saved_image_path]) }}">
                                                                        @else
                                                                            {{-- Default --}}
                                                                            <svg class="size-8 mt-2 mb-4"
                                                                                aria-hidden="true"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 20 16">
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
                                                                    <input id="edit_reason_image_file_path"
                                                                        wire:model="reason_image_file_path"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="reason_image_file_path"
                                                                        type="file" accept=".png,.jpg,.jpeg"
                                                                        class="hidden" />

                                                                    {{-- Cancel (X) button --}}
                                                                    <span x-show="uploading"
                                                                        class="absolute top-0 right-0 inline-flex">
                                                                        <button
                                                                            class="p-2 text-gray-500 {{ $errors->has('reason_image_file_path') ? 'hover:text-red-700' : 'hover:text-blue-700' }} duration-200 ease-in-out"
                                                                            type="button"
                                                                            wire:click="cancelUpload('reason_image_file_path')"
                                                                            @click="$wire.$refresh();">

                                                                            {{-- X Icon --}}
                                                                            <svg class="size-3" aria-hidden="true"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 14 14">
                                                                                <path stroke="currentColor"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                            </svg>
                                                                        </button>
                                                                    </span>

                                                                    @if ($reason_image_file_path || $reason_saved_image_path)
                                                                        {{-- Remove Image (X) button --}}
                                                                        <span x-show="!uploading"
                                                                            class="absolute -top-2 -right-2 inline-flex">
                                                                            <button wire:loading.attr="disabled"
                                                                                class="p-4 rounded-bl-full bg-transparent text-zinc-700 {{ $errors->has('reason_image_file_path') ? 'hover:bg-red-700 hover:text-red-50' : 'hover:bg-blue-700 hover:text-blue-50' }} duration-200 ease-in-out"
                                                                                type="button"
                                                                                @click="$wire.$set('reason_image_file_path', null); $wire.$set('reason_saved_image_path', null)">

                                                                                {{-- Loading Icon --}}
                                                                                <svg class="size-3 animate-spin"
                                                                                    wire:loading
                                                                                    wire:target="reason_image_file_path"
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

                                                                                {{-- X Icon --}}
                                                                                <svg class="size-3" wire:loading.remove
                                                                                    wire:target="reason_image_file_path"
                                                                                    aria-hidden="true"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 14 14">
                                                                                    <path stroke="currentColor"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                                </svg>
                                                                            </button>
                                                                        </span>
                                                                    @endif
                                                                </label>
                                                                @error('reason_image_file_path')
                                                                    <p
                                                                        class="text-center whitespace-nowrap w-full text-red-500 absolute top-full mt-1 text-xs">
                                                                        {{ $message }}
                                                                    </p>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        {{-- Image Description --}}
                                                        <div
                                                            class="relative flex flex-col justify-between col-span-full sm:col-span-2 gap-4">
                                                            <div class="relative flex flex-col">
                                                                <label for="edit_image_description"
                                                                    class="relative block mb-1 font-medium text-blue-1100 ">
                                                                    <span class="relative">Description
                                                                        <span
                                                                            class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                                <textarea type="text" id="edit_image_description" autocomplete="off" wire:model.blur="image_description"
                                                                    maxlength="255" rows="4"
                                                                    class="resize-none h-full text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('image_description') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                                                    placeholder="What is the reason for this special case?"></textarea>

                                                                @error('image_description')
                                                                    <p
                                                                        class="text-red-500 whitespace-nowrap w-full absolute left-0 top-full mt-1 text-xs">
                                                                        {{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="flex justify-end gap-2 w-full">
                                                                <button type="button" wire:click="resetReason"
                                                                    wire:loading.attr="disabled"
                                                                    class="outline-none duration-200 ease-in-out px-2 py-1 rounded font-bold text-lg border border-zinc-500 hover:border-transparent focus:border-transparent text-zinc-500 hover:text-blue-50 active:text-blue-50 hover:bg-blue-800 active:bg-blue-900 focus:outline-offset-2 focus:outline-blue-300">
                                                                    CANCEL
                                                                </button>
                                                                <button type="button" wire:click="saveReason"
                                                                    wire:loading.attr="disabled"
                                                                    class="outline-none duration-200 ease-in-out px-2 py-1 rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 focus:outline-offset-2 focus:outline-blue-300 text-blue-50 font-bold text-lg">
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

                        </div>

                        {{-- First Name --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <label for="edit_first_name" class="block mb-1 font-medium text-blue-1100">
                                <span class="relative">First Name
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_first_name" autocomplete="off"
                                wire:model.blur="first_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type first name">
                            @error('first_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Middle Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2 xl:col-span-2  pb-1">
                            <label for="edit_middle_name" class="block mb-1  font-medium text-blue-1100 ">Middle
                                Name</label>
                            <input type="text" id="edit_middle_name" autocomplete="off"
                                wire:model.blur="middle_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="(optional)">
                            @error('middle_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_last_name" class="block mb-1  font-medium text-blue-1100 ">
                                <span class="relative">Last Name
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_last_name" autocomplete="off" wire:model.blur="last_name"
                                @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type last name">
                            @error('last_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Extension Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2 xl:col-span-1  pb-1">
                            <label for="edit_extension_name" class="block mb-1 font-medium text-blue-1100 ">Ext.
                                Name</label>
                            <input type="text" id="edit_extension_name" autocomplete="off"
                                wire:model.blur="extension_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="III, Sr., etc.">
                            @error('extension_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs whitespace-nowrap">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Birthdate --}}
                        <div x-data="{ birthdate: $wire.entangle('birthdate') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_birthdate" class="block mb-1  font-medium text-blue-1100 ">
                                <span class="relative">Birthdate
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <div class="absolute start-0 bottom-3.5 flex items-center ps-3 pointer-events-none">
                                <svg class="size-4 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'text-red-700' : 'text-blue-900' }}"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input type="text" datepicker datepicker-autohide datepicker-format="mm-dd-yyyy"
                                datepicker-min-date='{{ $minDate }}' datepicker-max-date='{{ $maxDate }}'
                                id="edit_birthdate" autocomplete="off" wire:model="birthdate"
                                @change-date.camel="$wire.$set('birthdate', $el.value);"
                                class="text-xs border outline-none rounded block w-full py-2 ps-9 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Select date">
                            @error('birthdate')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contact Number --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_contact_num" class="block mb-1 font-medium text-blue-1100 ">
                                <span class="relative">Contact Number
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <div class="relative">
                                <div
                                    class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('contact_num') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-blue-700 text-blue-50' }}">
                                    <p
                                        class="flex text-center w-full relative items-center justify-center font-medium">
                                        +63
                                    </p>
                                </div>
                                <input x-mask="99999999999" type="text" min="0"
                                    @blur="if($el.value == '') { $wire.contact_num = null; }" autocomplete="off"
                                    id="edit_contact_num" wire:model.blur="contact_num"
                                    class="text-xs outline-none border ps-12 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('contact_num') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50  border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
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
                            <label for="edit_e_payment_acc_num"
                                class="block mb-1 font-medium text-blue-1100 ">E-payment
                                Account No.</label>
                            <input type="text" id="edit_e_payment_acc_num" autocomplete="off"
                                wire:model.blur="e_payment_acc_num"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600"
                                placeholder="Type e-payment account number">
                        </div>

                        {{-- Beneficiary Type --}}
                        <div class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2  pb-1">
                            <p class="mb-1 font-medium text-blue-1100">Beneficiary Type</p>
                            <div x-data="{ open: false, beneficiary_type: $wire.entangle('beneficiary_type') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">

                                <!-- Button -->
                                <button type="button" @click="open = !open;" :aria-expanded="open"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span class="text-xs md:max-[890px]:text-2xs" x-text="beneficiary_type"></span>

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-blue-1100 group-hover:text-blue-900 group-active:text-blue-1000 duration-200 ease-in-out"
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
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                    <button type="button"
                                        @click="open = !open; $wire.set('beneficiary_type', 'Underemployed');"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Underemployed
                                    </button>

                                    @if (isset($similarityResults) && $isPerfectDuplicate && !$isSameImplementation && !$isIneligible)
                                        <button type="button"
                                            @click="open = !open; $wire.set('beneficiary_type', 'Special Case');"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            Special Case
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Occupation --}}
                        <div x-data="{ avg: $wire.entangle('avg_monthly_income') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_occupation" class="block mb-1  font-medium text-blue-1100 ">
                                <span class="relative">Occupation
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_occupation" autocomplete="off"
                                wire:model.blur="occupation"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('occupation') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type occupation">
                            @error('occupation')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Sex --}}
                        <div class=" relative col-span-full sm:col-span-1  pb-1">
                            <p class="mb-1 font-medium text-blue-1100 ">Sex</p>
                            <div x-data="{ open: false, sex: $wire.entangle('sex') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span x-text="sex"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-blue-1100 group-hover:text-blue-900 group-active:text-blue-1000 duration-200 ease-in-out"
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
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                    <button type="button" @click="sex = 'Male'; open = false;"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Male
                                    </button>

                                    <button type="button" @click="sex = 'Female'; open = false;"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Female
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Civil Status --}}
                        <div
                            class=" relative col-span-full sm:col-span-1 md:col-span-2 lg:lg:col-span-1 xl:col-span-1  pb-1">
                            <p class="mb-1 font-medium text-blue-1100 ">Civil Status</p>
                            <div x-data="{ open: false, civil_status: $wire.entangle('civil_status') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span x-text="civil_status"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-blue-1100 group-hover:text-blue-900 group-active:text-blue-1000 duration-200 ease-in-out"
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
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                    <button type="button"
                                        @click="civil_status = 'Single'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Single
                                    </button>

                                    <button type="button"
                                        @click="civil_status = 'Married'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Married
                                    </button>

                                    <button type="button"
                                        @click="civil_status = 'Separated'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Separated
                                    </button>

                                    <button type="button"
                                        @click="civil_status = 'Widowed'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Widowed
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Average Monthly Income --}}
                        <div x-data="{ occ: $wire.entangle('occupation') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_avg_monthly_income" class="block mb-1  font-medium text-blue-1100 ">
                                <span class="relative">Average Monthly Income
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <div class="relative">
                                <div
                                    class="text-sm duration-200 ease-in-out absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none {{ $errors->has('avg_monthly_income') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-blue-700 text-blue-50' }}">
                                    <p
                                        class="flex text-center w-full relative items-center justify-center font-medium">
                                        ₱
                                    </p>
                                </div>
                                <input x-mask:dynamic="$money($input)" type="text" min="0"
                                    autocomplete="off" id="edit_avg_monthly_income"
                                    @blur="$wire.$set('avg_monthly_income', $el.value);"
                                    class="text-xs outline-none border ps-10 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('avg_monthly_income') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50  border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
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
                                <label for="edit_dependent"
                                    class="relative flex items-center gap-1.5 mb-1 font-medium text-blue-1100">
                                    Dependent <span class="text-gray-500">(must be 18+ years old)</span>
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </label>

                            </div>
                            <input type="text" id="edit_dependent" autocomplete="off" wire:model.blur="dependent"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('dependent') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type dependent's name">
                            @error('dependent')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Interested in Wage Employment or Self-Employment --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <p class="mb-1 font-medium text-blue-1100">Interested in Wage or
                                Self-Employment</p>
                            <div x-data="{ open: false, self_employment: $wire.entangle('self_employment') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">
                                <!-- Button -->
                                <button @click="open = !open;" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span x-text="self_employment"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-blue-1100 group-hover:text-blue-900 group-active:text-blue-1000 duration-200 ease-in-out"
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
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                    <button type="button" @click="self_employment = 'Yes'; open = false;"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Yes
                                    </button>

                                    <button type="button" @click="self_employment = 'No'; open = false;"
                                        class="flex
                                        items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b
                                        p-2 text-left text-xs text-blue-1100 hover:text-blue-900
                                        focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100
                                        focus:bg-blue-100 active:bg-blue-200">
                                        No
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Skills Training Needed --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 {{ $is_sectoral ? 'lg:col-span-2' : '' }} md:col-span-6 xl:col-span-2 pb-1">
                            <label for="edit_skills_training" class="block mb-1  font-medium text-blue-1100 ">Skills
                                Training
                                Needed</label>
                            <input type="text" id="edit_skills_training" autocomplete="off"
                                wire:model.blur="skills_training"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600"
                                placeholder="(optional)">
                        </div>

                        @if ($is_sectoral)

                            {{-- District --}}
                            <div x-data="{ show: false, district: $wire.entangle('district') }"
                                class="relative flex flex-col col-span-full sm:col-span-2 md:col-span-3 lg:col-span-2">
                                <p class="block mb-1 font-medium text-blue-1100 ">
                                    <span class="relative">District
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    </span>
                                </p>
                                <button type="button" id="edit_district" @click="show = !show;"
                                    class="text-xs flex items-center justify-between outline-none border rounded w-full p-2 duration-200 ease-in-out {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }} ">
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
                                    class="w-full end-0 top-full absolute text-blue-1100 bg-white shadow-lg border z-50 border-blue-300 rounded p-3 mt-2">
                                    <ul
                                        class="text-xs overflow-y-auto min-h-44 max-h-44 border border-gray-300 rounded p-2">
                                        @forelse ($this->districts as $key => $dist)
                                            <li wire:key={{ $key }}>
                                                <button type="button"
                                                    @click="show = !show; district = '{{ $dist }}'; $wire.resetBarangays();"
                                                    wire:loading.attr="disabled" aria-label="{{ __('Districts') }}"
                                                    class="w-full flex items-center justify-start px-4 py-2 text-blue-1100 hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out">{{ $dist }}</button>
                                            </li>
                                        @empty
                                            <div class="size-full text-xs text-gray-500 p-2">
                                                No districts found
                                            </div>
                                        @endforelse
                                    </ul>
                                </div>
                                @error('district')
                                    <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Barangay --}}
                            <div x-data="{ show: false, barangay_name: $wire.entangle('barangay_name') }"
                                class="relative flex flex-col col-span-full sm:col-span-2 md:col-span-3 lg:col-span-2">
                                <span class="block mb-1 font-medium"
                                    :class="{
                                        'text-gray-500': {{ json_encode(!isset($district) || empty($district)) }},
                                        'text-blue-1100': {{ json_encode($district) }},
                                    }">
                                    <span class="relative">Barangay
                                        <span
                                            class="absolute left-full ms-1 -top-2 {{ $district ? 'text-red-700' : 'text-gray-500' }} font-medium text-lg">*
                                        </span>
                                    </span>
                                </span>
                                <button type="button" id="edit_barangay_name"
                                    @if ($district) @click="show = !show;"
                            @else
                                disabled @endif
                                    class="text-xs flex items-center justify-between p-2 outline-none border rounded w-full duration-200 ease-in-out"
                                    :class="{
                                        'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600': {{ json_encode($errors->has('barangay_name')) }},
                                        'border-gray-300 bg-gray-50 text-gray-500': {{ json_encode(!isset($district) || empty($district)) }},
                                        'border-blue-300 bg-blue-50 focus:ring-blue-600 focus:border-blue-600 text-blue-1100': {{ json_encode(!$errors->has('barangay_name') && $district) }},
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
                                    class="w-full end-0 top-full absolute text-blue-1100 bg-white shadow-lg border z-50 border-blue-600 rounded p-3 mt-2">
                                    <div class="relative flex items-center justify-center py-1 group">

                                        {{-- Search Icon --}}
                                        <svg wire:loading.remove wire:target="searchBarangay"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="absolute start-0 ps-2 size-6 group-hover:text-blue-500 group-focus:text-blue-900 duration-200 ease-in-out pointer-events-none">
                                            <path fill-rule="evenodd"
                                                d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                                clip-rule="evenodd" />
                                        </svg>

                                        {{-- Loading Icon --}}
                                        <svg wire:loading wire:target="searchBarangay"
                                            class="absolute start-0 ms-2 size-4 group-hover:text-blue-500 group-focus:text-blue-900 duration-200 ease-in-out pointer-events-none animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <input id="edit_searchBarangay"
                                            wire:model.live.debounce.300ms="searchBarangay" type="text"
                                            autocomplete="off"
                                            class="rounded w-full ps-8 text-xs text-blue-1100 border-blue-200 hover:placeholder-blue-500 hover:border-blue-500 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none duration-200 ease-in-out"
                                            placeholder="Search barangay">
                                    </div>
                                    <ul class="mt-2 text-xs overflow-y-auto min-h-44 max-h-44">
                                        @forelse ($this->barangays as $key => $barangay)
                                            <li wire:key={{ $key }}>
                                                <button type="button"
                                                    @click="show = !show; barangay_name = '{{ $barangay }}'; $wire.$refresh();"
                                                    wire:loading.attr="disabled" aria-label="{{ __('Barangays') }}"
                                                    class="w-full flex items-center justify-start px-4 py-2 text-blue-1100 hover:text-blue-900 hover:bg-blue-100 duration-200 ease-in-out">{{ $barangay }}</button>
                                            </li>
                                        @empty
                                            <div class="h-full w-full text-xs text-gray-500 p-2">
                                                No barangays found
                                            </div>
                                        @endforelse
                                    </ul>
                                </div>
                                @error('barangay_name')
                                    <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}
                                    </p>
                                @enderror
                            </div>

                        @endif

                        {{-- ID Type --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-3 lg:col-span-3 xl:col-span-3  pb-1">
                            <p class="mb-1 font-medium text-blue-1100 ">ID Type</p>
                            <div x-data="{ open: false, type_of_id: $wire.entangle('type_of_id') }" class="relative"
                                @click.away="
                            if(open) {
                            open = false;
                            } ">
                                <!-- Button -->
                                <button @click="open = !open" :aria-expanded="open" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span x-text="type_of_id"></span> <!-- Display selected option -->

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-blue-1100 group-hover:text-blue-900 group-active:text-blue-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Dropdown Content -->
                                <div x-show="open"
                                    class="absolute left-0 mt-2 max-h-[10rem] w-full z-50 rounded bg-blue-50 shadow-lg border overflow-y-scroll border-blue-500 scrollbar-thin scrollbar-track-blue-50 scrollbar-thumb-blue-700">
                                    @if ($is_pwd === 'Yes')
                                        <button type="button"
                                            @click="type_of_id = 'Person\'s With Disability (PWD) ID'; open = false;"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            Person's With Disability (PWD) ID
                                        </button>
                                    @endif

                                    @if ($this->seniorCitizenCheck)
                                        <button type="button"
                                            @click="type_of_id = 'Senior Citizen ID'; open = false;"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            Senior Citizen ID
                                        </button>
                                    @endif

                                    @foreach ($this->listOfIDs as $id)
                                        <button type="button"
                                            @click="type_of_id = `{{ $id }}`; open = false;"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            {{ $id }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ID Number --}}
                        <div class=" relative col-span-full sm:col-span-1 md:col-span-2 lg:col-span-2  pb-1">
                            <label for="edit_id_number" class="block mb-1 font-medium text-blue-1100 ">
                                <span class="relative">ID Number
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_id_number" autocomplete="off" wire:model.blur="id_number"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('id_number') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type ID number">
                            @error('id_number')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is PWD? --}}
                        <div
                            class="pb-1 relative col-span-full sm:col-span-1 {{ $is_sectoral ? '' : 'xl:col-span-3' }}">
                            <div class="flex items-center">
                                <p class="inline mb-1 font-medium text-blue-1100">Is PWD?</p>
                                {{-- Popover Thingy --}}
                                <div x-data="{ pop: false }" class="relative flex items-center"
                                    id="edit_is-pwd-question-mark">
                                    <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-blue-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 20 20" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                    </svg>
                                    {{-- Popover --}}
                                    <div id="edit_is-pwd-popover"
                                        class="absolute z-50 bottom-full mb-2 left-0 md:left-auto md:right-0 text-xs whitespace-nowrap border border-zinc-300 text-zinc-50 bg-zinc-900 rounded p-2 shadow"
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
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span x-text="is_pwd"></span>

                                    <!-- Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-blue-1100 group-hover:text-blue-900 group-active:text-blue-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-show="open" @click.away="open = false;"
                                    class="absolute left-0 mt-2 w-full z-50 rounded bg-blue-50 shadow-lg border border-blue-500">
                                    <button type="button" @click="is_pwd = 'Yes'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        Yes
                                    </button>

                                    <button type="button" @click="is_pwd = 'No'; open = false; $wire.$refresh(); "
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                        No
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Proof of Identity --}}
                        <div class="w-full relative col-span-full lg:col-span-2 pb-1">
                            <div class="lg:absolute w-full flex flex-col items-start justify-center">

                                {{-- Header --}}
                                <div class="flex items-center">
                                    <p class="inline mb-1 font-medium text-blue-1100">Proof of Identity <span
                                            class="text-gray-500">(optional)</span></p>

                                    {{-- Popover Thingy --}}
                                    <div x-data="{ pop: false }" class="relative flex items-center"
                                        id="identity-question-mark">
                                        <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-blue-700"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                        </svg>
                                        {{-- Popover --}}
                                        <div id="identity-popover"
                                            class="absolute -left-20 sm:left-0 lg:left-auto lg:right-0 bottom-full mb-2 z-50 text-xs whitespace-nowrap border border-zinc-300 text-zinc-50 bg-zinc-900 rounded p-2 shadow"
                                            x-show="pop">
                                            It's basically an image of a beneficiary's ID card <br>
                                            to further prove that their identity is legitimate.
                                        </div>
                                    </div>
                                </div>

                                {{-- Image Area --}}
                                <label for="edit_image_file_path" id="edit_id_dropzone" x-data="{ uploading: false, progress: 0 }"
                                    x-on:livewire-upload-start="uploading = true"
                                    x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                    x-on:livewire-upload-cancel="uploading = false"
                                    x-on:livewire-upload-error="uploading = false;"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress;"
                                    class="z-10 relative overflow-hidden flex flex-col items-center justify-center size-full border-2 border-dashed rounded cursor-pointer duration-200 ease-in-out
                                            {{ $errors->has('image_file_path')
                                                ? 'border-red-300 bg-red-100 text-red-500 hover:text-orange-500'
                                                : 'border-blue-300 bg-blue-50 text-gray-500 hover:text-blue-500' }}">

                                    {{-- Image Preview --}}
                                    <div class="relative flex flex-col items-center justify-center py-3 size-full">
                                        {{-- Loading State for Changes --}}
                                        <div class="absolute flex items-center justify-center size-full"
                                            wire:loading.flex wire:target="image_file_path">
                                            <div class="absolute bg-black opacity-5 rounded min-w-full min-h-full">
                                                {{-- Darkness... --}}
                                            </div>

                                            <!-- Progress Bar && Loading Icon -->
                                            <div x-show="uploading"
                                                class="absolute flex items-center justify-center w-3/4">
                                                <div class="w-full bg-gray-300 rounded-lg h-2">
                                                    <div class="duration-200 ease-in-out h-full {{ $errors->has('image_file_path') ? 'bg-red-500' : 'bg-blue-500' }} rounded-lg"
                                                        x-bind:style="'width: ' + progress + '%'">
                                                    </div>
                                                </div>
                                                <svg class="ms-2 size-5 {{ $errors->has('image_file_path') ? 'text-red-700' : 'text-blue-700' }} animate-spin"
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
                                        @if (is_object($image_file_path) && !$errors->has('image_file_path'))
                                            {{-- Show the inserted image from the user --}}
                                            <img class="w-32 h-24 object-contain"
                                                src="{{ $image_file_path->temporaryUrl() }}">
                                        @elseif($saved_image_path && !$errors->has('image_file_path'))
                                            {{-- Show the saved image from the database --}}
                                            <img class="w-32 h-24 object-contain"
                                                src="{{ route('credentials.show', ['filename' => $saved_image_path]) }}">
                                        @else
                                            {{-- Default --}}
                                            <svg class="size-8 mt-2 mb-4" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-xs"><span class="font-semibold">Click to
                                                    upload</span> or drag and drop</p>
                                            <p class="text-xs">PNG or JPG (MAX. 5MB)</p>
                                        @endif
                                    </div>

                                    {{-- The Image itself --}}
                                    <input id="edit_image_file_path" wire:model="image_file_path"
                                        wire:loading.attr="disabled" wire:target="image_file_path" type="file"
                                        accept=".png,.jpg,.jpeg" class="hidden" />

                                    {{-- Cancel Upload (X) button --}}
                                    <span x-show="uploading" class="absolute top-0 right-0 inline-flex">
                                        <button
                                            class="p-2 text-gray-500 {{ $errors->has('image_file_path') ? 'hover:text-red-700' : 'hover:text-blue-700' }} duration-200 ease-in-out"
                                            type="button" wire:click="cancelUpload('image_file_path')"
                                            @click="$wire.$refresh();"><svg class="size-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                        </button>
                                    </span>

                                    @if ($image_file_path || $saved_image_path)
                                        {{-- Remove Image (X) button --}}
                                        <span x-show="!uploading" class="absolute -top-2 -right-2 inline-flex">
                                            <button wire:loading.attr="disabled"
                                                class="p-4 rounded-bl-full bg-transparent text-zinc-700 {{ $errors->has('image_file_path') ? 'hover:bg-red-700 hover:text-red-50' : 'hover:bg-blue-700 hover:text-blue-50' }} duration-200 ease-in-out"
                                                type="button"
                                                @click="$wire.$set('image_file_path', null); $wire.$set('saved_image_path', null)">

                                                {{-- Loading Icon --}}
                                                <svg class="size-3 animate-spin" wire:loading
                                                    wire:target="image_file_path, saved_image_path"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>

                                                {{-- X Icon --}}
                                                <svg class="size-3" wire:loading.remove
                                                    wire:target="image_file_path, saved_image_path" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                </label>
                                @error('image_file_path')
                                    <p class="text-center whitespace-nowrap w-full text-red-500 absolute top-full text-xs">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Spouse First Name --}}
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3  pb-1">
                            <label for="edit_spouse_first_name"
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-blue-1100' : 'text-gray-400' }}">
                                <span class="relative"> Spouse
                                    First Name @if (in_array($civil_status, ['Married', 'Separated', 'Widowed']))
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    @endif
                                </span>
                            </label>
                            <input type="text" id="edit_spouse_first_name" autocomplete="off"
                                wire:model.blur="spouse_first_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                    @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}
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
                            <label for="edit_spouse_middle_name"
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-blue-1100' : 'text-gray-400' }}">Spouse
                                Middle Name </label>
                            <input type="text" id="edit_spouse_middle_name" autocomplete="off"
                                wire:model.blur="spouse_middle_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                    @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}
                                @else
                                bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="(optional)">
                            @error('spouse_middle_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Spouse Last Name --}}
                        <div class=" relative flex flex-col col-span-full sm:col-span-2  pb-1">
                            <label for="edit_spouse_last_name"
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-blue-1100' : 'text-gray-400' }}"><span
                                    class="relative"> Spouse
                                    Last Name @if (in_array($civil_status, ['Married', 'Separated', 'Widowed']))
                                        <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                        </span>
                                    @endif
                                </span>
                            </label>
                            <input type="text" id="edit_spouse_last_name" autocomplete="off"
                                wire:model.blur="spouse_last_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                    
                                @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}
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
                            <label for="edit_spouse_extension_name"
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-blue-1100' : 'text-gray-400' }}">Spouse
                                Ext. Name</label>
                            <input type="text" id="edit_spouse_extension_name" autocomplete="off"
                                wire:model.blur="spouse_extension_name"
                                @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}
                                @else
                                bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="III, Sr., etc.">
                            @error('spouse_extension_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Modal footer --}}
                        <div class="relative col-span-full w-full flex items-center justify-end">
                            <div class="flex items-center justify-end gap-2 relative">

                                {{-- Save Button --}}
                                <button type="submit" wire:loading.attr="disabled" wire:target="editBeneficiary"
                                    class="outline-none py-2 px-4 font-bold flex items-center justify-center gap-1.5 rounded-md disabled:opacity-75 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    <p>SAVE</p>

                                    {{-- Loading Icon --}}
                                    <svg class="size-5 animate-spin" wire:loading wire:target="editBeneficiary"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- Check Icon --}}
                                    <svg class="size-5" wire:loading.remove wire:target="editBeneficiary"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M179.372 38.390 C 69.941 52.432,5.211 171.037,53.012 269.922 C 112.305 392.582,285.642 393.654,346.071 271.735 C 403.236 156.402,307.211 21.986,179.372 38.390 M273.095 139.873 C 278.022 142.919,280.062 149.756,277.522 154.718 C 275.668 158.341,198.706 250.583,194.963 253.668 C 189.575 258.110,180.701 259.035,173.828 255.871 C 168.508 253.422,123.049 207.486,121.823 203.320 C 119.042 193.868,129.809 184.732,138.528 189.145 C 139.466 189.620,149.760 199.494,161.402 211.088 L 182.569 232.168 220.917 186.150 C 242.008 160.840,260.081 139.739,261.078 139.259 C 264.132 137.789,270.227 138.101,273.095 139.873 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Confirm Beneficiary Type Change Modal --}}
                <div x-cloak class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto backdrop-blur-sm z-50"
                    x-show="confirmTypeChangeModal"
                    @if ($confirmChangeType === 'beneficiary_type') @keydown.window.escape="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                @elseif($confirmChangeType === 'first_name')
                    @keydown.window.escape="$wire.set('first_name', '{{ $this->beneficiary?->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                @elseif($confirmChangeType === 'middle_name')
                    @keydown.window.escape="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                @elseif($confirmChangeType === 'last_name')
                    @keydown.window.escape="$wire.set('last_name', '{{ $this->beneficiary?->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                @elseif($confirmChangeType === 'extension_name') 
                    @keydown.window.escape="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif>

                    <!-- Modal -->
                    <div x-show="confirmTypeChangeModal" x-trap.noreturn.noautofocus="confirmTypeChangeModal"
                        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

                        {{-- The Modal --}}
                        <div class="">
                            <div class="relative bg-white rounded-md shadow">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                                        Confirm Change
                                    </h1>

                                    {{-- Close Button --}}
                                    <button type="button"
                                        @if ($confirmChangeType === 'beneficiary_type') @click="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'first_name')
                                        @click="$wire.set('first_name', '{{ $this->beneficiary->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'middle_name')
                                        @click="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'last_name')
                                        @click="$wire.set('last_name', '{{ $this->beneficiary->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'extension_name') 
                                        @click="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif
                                        class="outline-none text-blue-400 focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                        <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close Modal</span>
                                    </button>
                                </div>

                                <hr class="">

                                {{-- Modal body --}}
                                <div class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-blue-1100">

                                    <p class="font-medium text-sm mb-1">
                                        @if ($confirmChangeType === 'beneficiary_type')
                                            Are you sure about changing the beneficiary type?
                                        @elseif($confirmChangeType === 'first_name')
                                            Are you sure about changing the first name?
                                        @elseif($confirmChangeType === 'middle_name')
                                            Are you sure about changing the middle name?
                                        @elseif($confirmChangeType === 'last_name')
                                            Are you sure about changing the last name?
                                        @elseif($confirmChangeType === 'extension_name')
                                            Are you sure about changing the extension name?
                                        @endif
                                    </p>
                                    <p class="font-normal text-xs text-gray-500 mb-6">
                                        This would clear out the case proof and you cannot undo this action.
                                    </p>

                                    <div class="flex items-center justify-center w-full gap-2">
                                        <button type="button"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-gray-500 hover:border-blue-800 hover:bg-blue-800 active:bg-blue-900 text-gray-500 hover:text-blue-50 active:text-blue-50"
                                            @if ($confirmChangeType === 'beneficiary_type') @click="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                                                
                                            @elseif($confirmChangeType === 'first_name')
                                           
                                                @click="$wire.set('first_name', '{{ $this->beneficiary->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                            @elseif($confirmChangeType === 'middle_name')
                                            
                                                @click="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                            @elseif($confirmChangeType === 'last_name')
                                             
                                                @click="$wire.set('last_name', '{{ $this->beneficiary->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                 
                                            @elseif($confirmChangeType === 'extension_name')
                                                
                                                @click="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif>NO
                                        </button>

                                        <button type="button"
                                            @click="$wire.revokeSpecialCase(); confirmTypeChangeModal = false;"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-transparent bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">YES</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

        let initialError = null; // Check if error styles exist
        const errorStyles = ['text-red-500'];
        const defaultStyles = ['text-gray-500'];
        const errorDragStyles = ['text-orange-500'];
        const defaultDragStyles = ['text-blue-500'];

        $wire.on('load-id-dropzone', () => {
            const image_file_path = document.getElementById('edit_image_file_path');
            const id_dropzone = document.getElementById('edit_id_dropzone');

            id_dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                initialError = id_dropzone.classList.contains('text-red-500');
                initialError ? id_dropzone.classList.remove(...errorStyles) : id_dropzone.classList.remove(
                    ...
                    defaultStyles);
                initialError ? id_dropzone.classList.add(...errorDragStyles) : id_dropzone.classList.add(...
                    defaultDragStyles);
            });

            id_dropzone.addEventListener('dragleave', () => {
                initialError = id_dropzone.classList.contains('text-red-500') || id_dropzone.classList
                    .contains(
                        'text-orange-500');
                initialError ? id_dropzone.classList.add(...errorStyles) : id_dropzone.classList.add(...
                    defaultStyles);
                initialError ? id_dropzone.classList.remove(...errorDragStyles) : id_dropzone.classList
                    .remove(...
                        defaultDragStyles);
            });

            id_dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                initialError = id_dropzone.classList.contains('text-red-500') || id_dropzone.classList
                    .contains(
                        'text-orange-500');
                initialError ? id_dropzone.classList.add(...errorStyles) : id_dropzone.classList.add(...
                    defaultStyles);
                initialError ? id_dropzone.classList.remove(...errorDragStyles) : id_dropzone.classList
                    .remove(...
                        defaultDragStyles);

                files = e.dataTransfer.files;
                if (files.length) {
                    image_file_path.files = files;
                    image_file_path.dispatchEvent(new Event('change'));
                }
            });
        });

        $wire.on('load-reason', () => {
            const reason_image_file_path = document.getElementById('edit_reason_image_file_path');
            const reason_dropzone = document.getElementById('edit_reason_dropzone');
            reason_dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                initialError = reason_dropzone.classList.contains('text-red-500');
                initialError ? reason_dropzone.classList.remove(...errorStyles) : reason_dropzone.classList
                    .remove(...
                        defaultStyles);
                initialError ? reason_dropzone.classList.add(...errorDragStyles) : reason_dropzone.classList
                    .add(...
                        defaultDragStyles);
            });

            reason_dropzone.addEventListener('dragleave', () => {
                initialError = reason_dropzone.classList.contains('text-red-500') || reason_dropzone
                    .classList.contains(
                        'text-orange-500');
                initialError ? reason_dropzone.classList.add(...errorStyles) : reason_dropzone.classList
                    .add(...
                        defaultStyles);
                initialError ? reason_dropzone.classList.remove(...errorDragStyles) : reason_dropzone
                    .classList.remove(
                        ...
                        defaultDragStyles);
            });

            reason_dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                initialError = reason_dropzone.classList.contains('text-red-500') || reason_dropzone
                    .classList.contains(
                        'text-orange-500');
                initialError ? reason_dropzone.classList.add(...errorStyles) : reason_dropzone.classList
                    .add(...
                        defaultStyles);
                initialError ? reason_dropzone.classList.remove(...errorDragStyles) : reason_dropzone
                    .classList.remove(
                        ...
                        defaultDragStyles);

                files = e.dataTransfer.files;
                if (files.length) {
                    reason_image_file_path.files = files;
                    reason_image_file_path.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
@endscript
