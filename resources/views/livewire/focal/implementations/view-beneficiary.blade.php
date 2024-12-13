<div x-cloak x-data="{ editMode: $wire.entangle('editMode'), deleteBeneficiaryModal: $wire.entangle('deleteBeneficiaryModal'), viewCredentialsModal: $wire.entangle('viewCredentialsModal'), confirmTypeChangeModal: $wire.entangle('confirmTypeChangeModal') }" x-show="viewBeneficiaryModal"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
    @keydown.window.escape="if(!deleteBeneficiaryModal && !viewCredentialsModal && !confirmTypeChangeModal) {$wire.resetBeneficiaries(); $wire.clearAvgIncome(); viewBeneficiaryModal = false;}">

    <!-- Modal -->
    <div x-show="viewBeneficiaryModal" x-trap.noscroll.noautofocus="viewBeneficiaryModal"
        class="relative h-full overflow-y-auto p-4 flex items-start justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="w-full max-w-screen-xl">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                            {{ $editMode ? 'Edit Beneficiary' : 'View Beneficiary' }}
                        </h1>

                    </span>
                    <div class="flex items-center justify-between gap-4">

                        {{-- Loading State for Changes --}}
                        <svg class="size-6 text-indigo-900 animate-spin" wire:loading
                            wire:target="nameCheck, beneficiary_type, civil_status, birthdate, is_pwd, district, barangay_name"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        {{-- Close Button --}}
                        <button type="button"
                            @click="$wire.resetBeneficiaries(); $wire.clearAvgIncome(); viewBeneficiaryModal = false;"
                            class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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

                <form wire:submit.prevent="editBeneficiary"
                    class="
                    @if ($editMode && $is_sectoral) px-5 pt-5 pb-16
                    @elseif($editMode && !$is_sectoral)
                        p-5 @endif">

                    {{-- IF Edit Mode is ON --}}
                    <div x-show="editMode"
                        class="grid gap-x-2.5 gap-y-6 grid-cols-1 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 text-xs">

                        {{-- Similarity Results --}}
                        <div x-data="{ expanded: $wire.entangle('expanded'), addReasonModal: $wire.entangle('addReasonModal') }"
                            class="{{ $similarityResults && !$isOriginal ? '' : 'hidden' }} relative col-span-full">

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

                                {{-- If the user is attempting to add the beneficiary on the same implementation --}}
                                @if ($isSameImplementation)
                                    <p class="inline mx-2">You cannot enter the same beneficiary on the same
                                        implementation.
                                        <button type="button" @click="expanded = ! expanded"
                                            class="outline-none underline underline-offset-2 font-bold">Show
                                            possible
                                            duplicates</button>
                                    </p>

                                    {{-- If the beneficiary has already applied more than 2 times this year --}}
                                @elseif($isIneligible)
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

                                    {{-- Perfect Duplicate && Unresolved --}}
                                @elseif ($isPerfectDuplicate && !$isResolved)
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
                                                    <span class="rounded py-0.5 px-1.5"
                                                        :class="{
                                                            'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($middle_name)) }},
                                                            'bg-red-200 text-red-900': {{ json_encode(mb_strtoupper($middle_name, 'UTF-8') === mb_strtoupper($result['middle_name'], 'UTF-8')) }},
                                                            'bg-amber-200 text-amber-900': {{ json_encode(mb_strtoupper($middle_name, 'UTF-8') !== mb_strtoupper($result['middle_name'], 'UTF-8')) }},
                                                        }">
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
                                                    <span class="rounded py-0.5 px-1.5"
                                                        :class="{
                                                            'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($extension_name)) }},
                                                            'bg-red-200 text-red-900': {{ json_encode(mb_strtoupper($extension_name, 'UTF-8') === mb_strtoupper($result['extension_name'], 'UTF-8')) }},
                                                            'bg-amber-200 text-amber-900': {{ json_encode(mb_strtoupper($extension_name, 'UTF-8') !== mb_strtoupper($result['extension_name'], 'UTF-8')) }},
                                                        }">
                                                        {{ $result['extension_name'] ?? '-' }}
                                                    </span>

                                                </td>
                                                <td class="p-2">
                                                    <span class="rounded py-0.5 px-1.5"
                                                        :class="{
                                                            'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($birthdate)) }},
                                                            'bg-red-200 text-red-900': {{ json_encode(
                                                                \App\Services\Essential::extract_date($birthdate, true, 'Y-m-d') ===
                                                                    \App\Services\Essential::extract_date($result['birthdate'], true, 'Y-m-d'),
                                                            ) }},
                                                            'bg-amber-200 text-amber-900': {{ json_encode(
                                                                \App\Services\Essential::extract_date($birthdate, true, 'Y-m-d') !==
                                                                    \App\Services\Essential::extract_date($result['birthdate'], true, 'Y-m-d'),
                                                            ) }},
                                                        }">
                                                        {{ \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') }}
                                                    </span>

                                                </td>
                                                <td class="p-2">
                                                    {{ $result['contact_num'] }}
                                                </td>
                                                <td class="p-2">
                                                    <span class="rounded py-0.5 px-1.5"
                                                        :class="{
                                                            'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($this->batch?->barangay_name)) }},
                                                            'bg-red-200 text-red-900': {{ json_encode($this->batch?->barangay_name === $result['barangay_name']) }},
                                                            'bg-amber-200 text-amber-900': {{ json_encode($this->batch?->barangay_name !== $result['barangay_name']) }},
                                                        }">
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
                                <div x-show="addReasonModal" x-trap.noscroll="addReasonModal"
                                    class="relative h-full overflow-y-auto p-4 flex items-start sm:items-center justify-center select-none">

                                    {{-- The Modal --}}
                                    <div class="w-full max-w-3xl">
                                        <div class="relative bg-white rounded-md shadow">
                                            <form wire:submit.prevent="saveReason">
                                                <!-- Modal Header -->
                                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                    <span class="flex items-center justify-center">
                                                        <h1
                                                            class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                            Add Reason
                                                        </h1>
                                                    </span>

                                                    <div class="flex items-center justify-center">
                                                        {{-- Loading State for Changes --}}
                                                        <div class="z-50 text-indigo-900" wire:loading
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
                                                <div class="pt-5 pb-10 px-5 md:px-12 text-indigo-1100 text-xs">
                                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                                                        {{-- Case Proof --}}
                                                        <div class="relative col-span-full sm:col-span-1">
                                                            <div class="relative flex flex-col items-start">
                                                                <div class="flex items-center">
                                                                    <p
                                                                        class="inline mb-1 font-medium text-indigo-1100">
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
                                                                        : 'border-indigo-300 bg-indigo-50 text-gray-500 hover:text-indigo-500' }} 
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
                                                                                    <div class="duration-200 ease-in-out h-full {{ $errors->has('reason_image_file_path') ? 'bg-red-500' : 'bg-indigo-500' }} rounded-lg"
                                                                                        :style="'width: ' + progress +
                                                                                            '%'">
                                                                                    </div>
                                                                                </div>

                                                                                <svg class="ms-2 size-5 {{ $errors->has('reason_image_file_path') ? 'text-red-700' : 'text-indigo-700' }} animate-spin"
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
                                                                            class="p-2 text-gray-500 {{ $errors->has('reason_image_file_path') ? 'hover:text-red-700' : 'hover:text-indigo-700' }} duration-200 ease-in-out"
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
                                                                                class="p-4 rounded-bl-full bg-transparent text-zinc-700 {{ $errors->has('reason_image_file_path') ? 'hover:bg-red-700 hover:text-red-50' : 'hover:bg-indigo-700 hover:text-indigo-50' }} duration-200 ease-in-out"
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
                                                                    class="relative block mb-1 font-medium text-indigo-1100 ">
                                                                    <span class="relative">Description
                                                                        <span
                                                                            class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                                <textarea type="text" id="edit_image_description" autocomplete="off" wire:model.blur="image_description"
                                                                    maxlength="255" rows="4"
                                                                    class="resize-none h-full text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('image_description') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
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
                                                                    class="outline-none duration-200 ease-in-out px-2 py-1 rounded font-bold text-lg border border-zinc-500 hover:border-transparent focus:border-transparent text-zinc-500 hover:text-indigo-50 active:text-indigo-50 hover:bg-indigo-800 active:bg-indigo-900 focus:outline-offset-2 focus:outline-indigo-300">
                                                                    CANCEL
                                                                </button>
                                                                <button type="button" wire:click.prevent="saveReason"
                                                                    class="px-2 py-1 rounded bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 focus:outline-offset-2 focus:outline-indigo-300 text-indigo-50 font-bold text-lg">
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
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3 pb-1">
                            <label for="edit_first_name" class="block mb-1 font-medium text-indigo-1100">
                                <span class="relative">First Name
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_first_name" autocomplete="off"
                                wire:model.blur="first_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type first name">
                            @error('first_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Middle Name --}}
                        <div
                            class=" relative col-span-full sm:col-span-2 md:col-span-1 lg:col-span-2 xl:col-span-2  pb-1">
                            <label for="edit_middle_name" class="block mb-1  font-medium text-indigo-1100 ">Middle
                                Name</label>
                            <input type="text" id="edit_middle_name" autocomplete="off"
                                wire:model.blur="middle_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="(optional)">
                            @error('middle_name')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_last_name" class="block mb-1  font-medium text-indigo-1100 ">
                                <span class="relative">Last Name
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_last_name" autocomplete="off" wire:model.blur="last_name"
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
                            <label for="edit_extension_name" class="block mb-1 font-medium text-indigo-1100 ">Ext.
                                Name</label>
                            <input type="text" id="edit_extension_name" autocomplete="off"
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
                            <label for="edit_birthdate" class="block mb-1  font-medium text-indigo-1100 ">
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
                                id="edit_birthdate" autocomplete="off" wire:model.blur="birthdate"
                                @change-date.camel="$wire.$set('birthdate', $el.value);"
                                class="text-xs border outline-none rounded block w-full py-2 ps-9 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Select date">
                            @error('birthdate')
                                <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contact Number --}}
                        <div class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_contact_num" class="block mb-1 font-medium text-indigo-1100 ">
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
                                    id="edit_contact_num" wire:model.blur="contact_num"
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
                            <label for="edit_e_payment_acc_num"
                                class="block mb-1 font-medium text-indigo-1100 ">E-payment
                                Account No.</label>
                            <input type="text" id="edit_e_payment_acc_num" autocomplete="off"
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
                                    <span class="text-xs md:max-[890px]:text-2xs" x-text="beneficiary_type"></span>

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
                            <label for="edit_occupation" class="block mb-1  font-medium text-indigo-1100 ">
                                <span class="relative">Occupation
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_occupation" autocomplete="off"
                                wire:model.blur="occupation"
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

                                    <button type="button"
                                        @click="civil_status = 'Separated'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Separated
                                    </button>

                                    <button type="button"
                                        @click="civil_status = 'Widowed'; open = false; $wire.$refresh();"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Widowed
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Average Monthly Income --}}
                        <div x-data="{ occ: $wire.entangle('occupation') }" class=" relative col-span-full sm:col-span-2  pb-1">
                            <label for="edit_avg_monthly_income" class="block mb-1  font-medium text-indigo-1100 ">
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
                                    autocomplete="off" id="edit_avg_monthly_income"
                                    @blur="$wire.$set('avg_monthly_income', $el.value);"
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
                                <label for="edit_dependent"
                                    class="relative flex items-center gap-1.5 mb-1 font-medium text-indigo-1100">
                                    Dependent <span class="text-gray-500">(must be 18+ years old)</span>
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </label>

                            </div>
                            <input type="text" id="edit_dependent" autocomplete="off" wire:model.blur="dependent"
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
                            <label for="edit_skills_training" class="block mb-1  font-medium text-indigo-1100 ">Skills
                                Training
                                Needed</label>
                            <input type="text" id="edit_skills_training" autocomplete="off"
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
                                    <p class="text-red-500 absolute left-2 top-full text-xs">{{ $message }}
                                    </p>
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

                                    @if (
                                        $birthdate &&
                                            strtotime(\Carbon\Carbon::createFromFormat('m-d-Y', $birthdate)->format('Y-m-d')) <
                                                strtotime(\Carbon\Carbon::now()->subYears(60)))
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
                            <label for="edit_id_number" class="block mb-1 font-medium text-indigo-1100 ">
                                <span class="relative">ID Number
                                    <span class="absolute left-full ms-1 -top-2 text-red-700 font-medium text-lg">*
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="edit_id_number" autocomplete="off" wire:model.blur="id_number"
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
                        <div class="w-full relative col-span-full lg:col-span-2 pb-1">
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
                                                : 'border-indigo-300 bg-indigo-50 text-gray-500 hover:text-indigo-500' }}">

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
                                                    <div class="duration-200 ease-in-out h-full {{ $errors->has('image_file_path') ? 'bg-red-500' : 'bg-indigo-500' }} rounded-lg"
                                                        x-bind:style="'width: ' + progress + '%'">
                                                    </div>
                                                </div>
                                                <svg class="ms-2 size-5 {{ $errors->has('image_file_path') ? 'text-red-700' : 'text-indigo-700' }} animate-spin"
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
                                            class="p-2 text-gray-500 {{ $errors->has('image_file_path') ? 'hover:text-red-700' : 'hover:text-indigo-700' }} duration-200 ease-in-out"
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
                                                class="p-4 rounded-bl-full bg-transparent text-zinc-700 {{ $errors->has('image_file_path') ? 'hover:bg-red-700 hover:text-red-50' : 'hover:bg-indigo-700 hover:text-indigo-50' }} duration-200 ease-in-out"
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
                        <div class=" relative col-span-full sm:col-span-2 xl:col-span-3 pb-1">
                            <label for="edit_spouse_first_name"
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-indigo-1100' : 'text-gray-400' }}">
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
                                            @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
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
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Middle Name </label>
                            <input type="text" id="edit_spouse_middle_name" autocomplete="off"
                                wire:model.blur="spouse_middle_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                            @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
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
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-indigo-1100' : 'text-gray-400' }}"><span
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
                            
                                        @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
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
                                class="flex items-end mb-1 font-medium h-6 {{ in_array($civil_status, ['Married', 'Separated', 'Widowed']) ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Ext. Name</label>
                            <input type="text" id="edit_spouse_extension_name" autocomplete="off"
                                wire:model.blur="spouse_extension_name"
                                @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                                        @if (in_array($civil_status, ['Married', 'Separated', 'Widowed'])) {{ $errors->has('spouse_extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
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
                                    class="outline-none py-2 px-4 font-bold flex items-center justify-center gap-1.5 rounded-md disabled:opacity-75 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 focus:ring-4 focus:outline-none focus:ring-indigo-300">
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

                                {{-- Cancel/X Button --}}
                                <button type="button" wire:click="toggleEdit"
                                    class="py-2 px-4 font-bold flex items-center justify-center gap-1.5 rounded-md disabled:opacity-75 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 focus:ring-4 focus:outline-none focus:ring-red-300">

                                    {{-- Loading Icon --}}
                                    <svg class="size-5 animate-spin" wire:loading wire:target="toggleEdit"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    {{-- X Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" wire:loading.remove
                                        wire:target="toggleEdit" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="400" viewBox="0, 0, 400,400">
                                        <g>
                                            <path
                                                d="M177.897 17.596 C 52.789 32.733,-20.336 167.583,35.137 280.859 C 93.796 400.641,258.989 419.540,342.434 316.016 C 445.776 187.805,341.046 -2.144,177.897 17.596 M146.875 125.950 C 148.929 126.558,155.874 132.993,174.805 151.829 L 200.000 176.899 225.195 151.829 C 245.280 131.845,251.022 126.556,253.503 125.759 C 264.454 122.238,275.000 129.525,275.000 140.611 C 275.000 147.712,274.055 148.915,247.831 175.195 L 223.080 200.000 247.831 224.805 C 274.055 251.085,275.000 252.288,275.000 259.389 C 275.000 270.771,263.377 278.313,252.691 273.865 C 250.529 272.965,242.208 265.198,224.805 247.831 L 200.000 223.080 175.195 247.769 C 154.392 268.476,149.792 272.681,146.680 273.836 C 134.111 278.498,121.488 265.871,126.173 253.320 C 127.331 250.217,131.595 245.550,152.234 224.799 L 176.909 199.989 152.163 175.190 C 135.672 158.663,127.014 149.422,126.209 147.486 C 122.989 139.749,126.122 130.459,133.203 126.748 C 137.920 124.276,140.678 124.115,146.875 125.950 "
                                                stroke="none" fill="currentColor" fill-rule="evenodd">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="{{ $editMode ? '' : 'p-5' }}">
                    {{-- IF Edit Mode is OFF --}}
                    <div x-show="!editMode" class="grid gap-4 grid-cols-1 sm:grid-cols-3 md:grid-cols-5 text-xs">
                        {{-- Project Number OFF --}}
                        <div class="relative md:col-span-2 flex flex-col mb-4">
                            <p class="block mb-1 font-medium text-indigo-1100">
                                Project Number
                            </p>
                            <span
                                class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->projectInformation?->project_num }}</span>
                        </div>

                        {{-- Batch Number OFF --}}
                        <div class="relative md:col-span-2 flex flex-col mb-4">
                            <p class="block mb-1 font-medium text-indigo-1100">
                                Batch Number
                            </p>
                            <span
                                class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->projectInformation?->batch_num }}</span>
                        </div>

                        {{-- Edit | Delete Buttons OFF --}}
                        <div class="flex justify-center items-center gap-4">
                            <button type="button" wire:loading.attr="disabled" wire:target="toggleEdit"
                                wire:click.prevent="toggleEdit"
                                class="duration-200 ease-in-out flex flex-1 gap-2 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                EDIT

                                {{-- Loading Icon --}}
                                <svg class="size-4 animate-spin" wire:loading wire:target="toggleEdit"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Edit Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" wire:loading.remove
                                    wire:target="toggleEdit" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                        </path>
                                    </g>
                                </svg>
                            </button>

                            {{-- Delete/Trash Button --}}
                            <button type="button"
                                @if ($this->projectInformation?->approval_status !== 'approved') @click="deleteBeneficiaryModal = !deleteBeneficiaryModal;"
                                    @else
                                    disabled @endif
                                class="duration-200 ease-in-out flex shrink items-center justify-center p-2 rounded outline-none font-bold text-sm disabled:bg-gray-300 disabled:text-gray-500 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                    viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd">
                                        </path>
                                    </g>
                                </svg>
                            </button>

                        </div>

                        {{-- Basic Information --}}
                        <span
                            class="relative flex col-span-full mb-2 font-semibold text-sm rounded px-2 py-1 border border-indigo-300 bg-indigo-100 text-indigo-900">Basic
                            Information</span>
                        @foreach ($this->basicInformation as $key => $info)
                            <div class="relative flex flex-col mb-2">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    {{ $key }}
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $info }}</span>
                            </div>
                        @endforeach

                        {{-- Address Information --}}
                        <span
                            class="relative flex col-span-full mt-4 mb-2 font-semibold text-sm rounded px-2 py-1 border border-indigo-300 bg-indigo-100 text-indigo-900">Address
                            / Location of the Implementation</span>
                        @foreach ($this->addressInformation as $key => $info)
                            <div
                                class="relative flex flex-col {{ mb_strtolower($key, 'UTF-8') === 'barangay' ? 'sm:col-span-2' : '' }} mb-2">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    {{ $key }}
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $info }}</span>
                            </div>
                        @endforeach
                        {{-- Additional Information --}}
                        <span
                            class="relative flex col-span-full mt-4 mb-2 font-semibold text-sm rounded px-2 py-1 border border-indigo-300 bg-indigo-100 text-indigo-900">Additional
                            Information</span>
                        @foreach ($this->additionalInformation as $key => $info)
                            <div class="relative flex flex-col mb-2">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    {{ $key }}
                                </p>

                                @if ($key === 'ID Number')
                                    <button type="button" wire:click="viewCredential('identity')"
                                        class="flex flex-1 items-center justify-between text-sm rounded p-2.5 font-medium bg-indigo-50 text-indigo-700">

                                        <p>{{ $info }}</p>

                                        {{-- View Button --}}
                                        <span
                                            class="relative flex items-center justify-center p-3 rounded bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">

                                            {{-- Loading State for Changes --}}
                                            <svg class="absolute size-4 animate-spin" wire:loading
                                                wire:target="viewCredential('identity')"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Eye Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute size-5"
                                                wire:loading.remove wire:target="viewCredential('identity')"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </span>
                                    </button>
                                @elseif ($key === 'Type of Beneficiary' && strtolower($info) === 'special case')
                                    <button type="button" wire:click="viewCredential('special')"
                                        class="flex flex-1 items-center justify-between text-sm rounded p-2.5 font-medium"
                                        :class="{
                                            'bg-red-50 text-red-700': {{ json_encode($key === 'Type of Beneficiary' && strtolower($info) === 'special case') }},
                                            'bg-indigo-50 text-indigo-700': {{ json_encode(($key === 'Type of Beneficiary' && strtolower($info) !== 'special case') || $key !== 'Type of Beneficiary') }},
                                        }">

                                        <p>{{ $info }}</p>

                                        {{-- View Button --}}
                                        <span
                                            class="relative flex items-center justify-center p-3 rounded bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">

                                            {{-- Loading State for Changes --}}
                                            <svg class="absolute size-4 animate-spin" wire:loading
                                                wire:target="viewCredential('special')"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>

                                            {{-- Eye Icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute size-5"
                                                wire:loading.remove wire:target="viewCredential('special')"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M181.641 87.979 C 130.328 95.222,89.731 118.794,59.712 158.775 C 35.189 191.436,35.188 208.551,59.709 241.225 C 108.153 305.776,191.030 329.697,264.335 300.287 C 312.216 281.078,358.187 231.954,358.187 200.000 C 358.187 163.027,301.790 109.157,246.875 93.676 C 229.295 88.720,196.611 85.866,181.641 87.979 M214.728 139.914 C 251.924 148.468,272.352 190.837,256.127 225.780 C 234.108 273.202,167.333 273.905,144.541 226.953 C 121.658 179.813,163.358 128.100,214.728 139.914 M188.095 164.017 C 162.140 172.314,153.687 205.838,172.483 225.933 C 192.114 246.920,228.245 238.455,236.261 210.991 C 244.785 181.789,217.066 154.756,188.095 164.017 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </span>
                                    </button>
                                @else
                                    <span
                                        class="flex flex-1 items-center justify-between text-sm rounded p-2.5 font-medium bg-indigo-50 text-indigo-700">
                                        <p>{{ $info }}</p>
                                    </span>
                                @endif
                            </div>
                        @endforeach

                        {{-- Spouse Information --}}
                        <span
                            class="relative flex col-span-full mt-4 mb-2 font-semibold text-sm rounded px-2 py-1 border border-indigo-300 bg-indigo-100 text-indigo-900">Spouse
                            Information</span>
                        @foreach ($this->spouseInformation as $key => $info)
                            <div class="relative flex flex-col mb-2">
                                <p class="block mb-1 font-medium text-indigo-1100">
                                    {{ $key }}
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $info }}</span>
                            </div>
                        @endforeach

                        {{-- Date Added && Last Updated --}}
                        <div
                            class="my-4 flex flex-col sm:flex-row items-center justify-between col-span-full gap-2 sm:gap-4">
                            <div class="flex flex-1 items-center justify-center">
                                <p class="font-bold text-indigo-1100">
                                    Date Added:
                                </p>
                                <span
                                    class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                    {{ date('M d, Y @ h:i:s a', strtotime($this->beneficiary?->created_at)) }}</span>
                            </div>

                            <div class="flex flex-1 items-center justify-center">
                                <p class="font-bold text-indigo-1100">
                                    Last Updated:
                                </p>
                                <span
                                    class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                    {{ date('M d, Y @ h:i:s a', strtotime($this->beneficiary?->updated_at)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delete Beneficiary Modal --}}
                <div x-cloak @keydown.window.escape="deleteBeneficiaryModal = false"
                    class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50"
                    x-show="deleteBeneficiaryModal">

                    <!-- Modal -->
                    <div x-trap.noautofocus.noscroll="deleteBeneficiaryModal"
                        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

                        {{-- The Modal --}}
                        <div class="relative size-full max-w-xl">
                            <div class="relative bg-white rounded-md shadow">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                                        {{ $this->defaultArchive ? 'Archive' : 'Delete' }} this Beneficiary
                                    </h1>

                                    <div class="flex items-center justify-between gap-4">
                                        {{-- Loading Icon --}}
                                        <svg class="size-6 text-indigo-900 animate-spin" wire:loading
                                            wire:target="deleteBeneficiary" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>

                                        {{-- Close Button --}}
                                        <button type="button" @click="deleteBeneficiaryModal = false;"
                                            class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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

                                {{-- Modal body --}}
                                <div
                                    class="flex flex-col items-center justify-center gap-4 w-full pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                    @if ($this->defaultArchive)
                                        <p class="font-medium text-sm mb-2">
                                            Are you sure about archiving this beneficiary?
                                        </p>
                                        <p class="text-gray-500 text-xs font-semibold mb-4">
                                            You could restore this beneficiary back from the Archives page
                                        </p>
                                    @else
                                        <p class="font-medium text-sm mb-2">
                                            Are you sure about deleting this beneficiary?
                                        </p>
                                        <p class="text-gray-500 text-xs font-semibold mb-4">
                                            This is action is irreversible
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-center w-full gap-2">
                                        {{-- Cancel Button --}}
                                        <button type="button" @click="deleteBeneficiaryModal = false;"
                                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-indigo-700 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900 text-indigo-700 hover:text-indigo-50 active:text-indigo-50">
                                            CANCEL
                                        </button>

                                        {{-- Confirm Button --}}
                                        <button type="button" wire:click="deleteBeneficiary"
                                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                            CONFIRM
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- View Credentials Modal --}}
                <livewire:focal.implementations.view-credentials-modal :$passedCredentialId />

                {{-- Confirm Beneficiary Type Change Modal --}}
                <div x-cloak 
                    @if ($confirmChangeType === 'beneficiary_type')
                        @keydown.window.escape="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                    @elseif($confirmChangeType === 'first_name')
                        @keydown.window.escape="$wire.set('first_name', '{{ $this->beneficiary?->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                    @elseif($confirmChangeType === 'middle_name')
                        @keydown.window.escape="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                    @elseif($confirmChangeType === 'last_name')
                        @keydown.window.escape="$wire.set('last_name', '{{ $this->beneficiary?->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                    @elseif($confirmChangeType === 'extension_name') 
                        @keydown.window.escape="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" 
                    @endif
                    class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto backdrop-blur-sm z-50"
                    x-show="confirmTypeChangeModal">

                    <!-- Modal -->
                    <div x-show="confirmTypeChangeModal" x-trap.noreturn.noautofocus="confirmTypeChangeModal"
                        class="relative h-full p-4 flex items-center justify-center z-50 select-none">

                        {{-- The Modal --}}
                        <div class="w-full max-w-xl">
                            <div class="relative bg-white rounded-md shadow">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                                        Confirm Change
                                    </h1>

                                    {{-- Close Button --}}
                                    <button type="button"
                                        @if ($confirmChangeType === 'beneficiary_type') @click="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'first_name')
                                        @click="$wire.set('first_name', '{{ $this->beneficiary?->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'middle_name')
                                        @click="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'last_name')
                                        @click="$wire.set('last_name', '{{ $this->beneficiary?->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'extension_name') 
                                        @click="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif
                                        class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                                <div
                                    class="flex flex-col items-center justify-center gap-4 w-full pt-5 pb-6 px-3 md:px-12 text-indigo-1100">

                                    <p class="font-medium text-sm mb-4">
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
                                    <p class="font-medium text-xs text-gray-500 mb-2">
                                        This would clear out the case proof and you cannot undo this action.
                                    </p>

                                    <div class="flex items-center justify-center w-full gap-2">
                                        <button type="button"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-gray-500 hover:border-indigo-800 active:border-indigo-900 text-gray-500 hover:text-indigo-800 active:text-indigo-50 active:bg-indigo-900"
                                            @if ($confirmChangeType === 'beneficiary_type') @click="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'first_name')
                                           
                                                @click="$wire.set('first_name', '{{ $this->beneficiary?->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'middle_name')
                                            
                                                @click="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'last_name')
                                             
                                                @click="$wire.set('last_name', '{{ $this->beneficiary?->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                 
                                        @elseif($confirmChangeType === 'extension_name')
                                                
                                            @click="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif>NO</button>
                                        <button type="button"
                                            @click="$wire.revokeSpecialCase(); confirmTypeChangeModal = false;"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">YES</button>
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
        const defaultDragStyles = ['text-indigo-500'];

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
