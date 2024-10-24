<div x-cloak x-show="editBeneficiaryModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50">

    <!-- Modal -->
    <div x-show="editBeneficiaryModal" x-trap.noautofocus.noscroll="editBeneficiaryModal"
        class="relative h-full p-4 overflow-y-auto flex items-center justify-center select-none">

        <!-- Modal content -->
        <div class="size-full max-w-7xl">
            <div x-data="{ confirmTypeChangeModal: $wire.entangle('confirmTypeChangeModal') }" class="relative bg-white rounded-md shadow">

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
                            @click="$wire.resetEditBeneficiary(); editBeneficiaryModal = false;">
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
                <form wire:submit.prevent="editBeneficiary" class="p-4 md:p-5">
                    <div class="grid gap-4 sm:gap-2 grid-cols-10 text-xs">

                        {{-- Similarity Results --}}
                        <div x-data="{ expanded: $wire.entangle('expanded'), addReasonModal: $wire.entangle('addReasonModal') }" class="relative col-span-full mb-2">
                            @if (isset($similarityResults))
                                <div class="flex items-center justify-between border rounded text-xs p-2 duration-200 ease-in-out"
                                    :class="{
                                        // A Perfect Duplicate && Unresolved Duplication Issue
                                        'border-red-300 bg-red-50 text-red-900': {{ json_encode($isPerfectDuplicate && !$isResolved) }},
                                        // A Possible Duplicate
                                        'border-amber-300 bg-amber-50 text-amber-900': {{ json_encode(!$isPerfectDuplicate && !$isResolved) }},
                                        // When a Perfect Duplicate is Resolved
                                        'border-green-300 bg-green-50 text-green-900': {{ json_encode($isResolved) }},
                                    }">

                                    {{-- If Inputted Beneficiary is on the same implementation --}}
                                    @if ($isSameImplementation)
                                        <p class="inline mx-2">You cannot enter the same beneficiary on the same
                                            implementation.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="underline underline-offset-2 font-bold">Show possible
                                                duplicates</button>
                                        </p>

                                        {{-- If the beneificary applied more than twice (2) already --}}
                                    @elseif($isIneligible)
                                        <p class="inline mx-2">This beneficiary
                                            has already applied more than twice (2) this year.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="underline underline-offset-2 font-bold">Show possible
                                                duplicates</button>
                                        </p>

                                        {{-- Perfect Duplicate --}}
                                    @elseif ($isPerfectDuplicate && !$isResolved)
                                        <p class="inline mx-2">This beneficiary
                                            has
                                            already
                                            been listed in the
                                            database this
                                            year.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="underline underline-offset-2 font-bold">Show possible
                                                duplicates</button>
                                        </p>

                                        {{-- Not a Special Case Edit && Set beneficiary_type as 'Special Case' --}}
                                        @if (strtolower($beneficiary_type) === 'special case')
                                            <button type="button" @click="addReasonModal = !addReasonModal"
                                                class="px-2 py-1 rounded font-bold text-xs {{ $isPerfectDuplicate ? ' bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50' : ' bg-amber-700 hover:bg-amber-800 active:bg-amber-900 text-amber-50' }}">
                                                ADD REASON
                                            </button>

                                            {{-- Not a Special Case Edit && Set beneficiary_type as 'Special Case' --}}
                                        @elseif (strtolower($beneficiary_type) !== 'special case')
                                            <p class="inline mx-2">Not a mistake? Change the
                                                Type of Beneficiary to
                                                <strong class="underline underline-offset-2">Special
                                                    Case</strong>
                                            </p>
                                        @endif

                                        {{-- Possible Duplicate --}}
                                    @elseif (!$isPerfectDuplicate && !$isResolved)
                                        <p class="inline mx-2">There are
                                            possible
                                            duplicates found
                                            associated with this name.

                                            <button type="button" @click="expanded = ! expanded"
                                                class="underline underline-offset-2 font-bold">Show
                                                possible duplicates</button>
                                        </p>
                                    @elseif($isResolved)
                                        <p class="inline mx-2">Possible
                                            duplication is resolved.
                                            <button type="button" @click="expanded = ! expanded"
                                                class="underline underline-offset-2 font-bold">Show
                                                possible duplicates</button>
                                        </p>

                                        <button type="button" @click="addReasonModal = !addReasonModal"
                                            class="px-2 py-1 rounded font-bold text-xs bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">VIEW
                                            REASON</button>
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
                                                <th scope="col" class="px-2 py-2">
                                                    project number
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    batch number
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    first name
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    middle name
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    last name
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    ext.
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    birthdate
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    contact #
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    barangay
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    sex
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    age
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    beneficiary type
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    id type
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    id #
                                                </th>
                                                <th scope="col" class="px-2 py-2">
                                                    pwd
                                                </th>
                                                <th scope="col" class="px-2 py-2">
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
                                                    <td class="px-2 py-2">
                                                        {{ $result['project_num'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        {{ $result['batch_num'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <span
                                                            class="{{ $first_name === $result['first_name'] ? 'bg-red-100 text-red-900' : 'bg-amber-100 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['first_name'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <span
                                                            class="{{ ($middle_name === $result['middle_name'] && !is_null($middle_name)) || ($middle_name === $result['middle_name'] && $middle_name !== '') ? 'bg-red-100 text-red-900' : 'bg-amber-100 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['middle_name'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <span
                                                            class="{{ $last_name === $result['last_name'] ? 'bg-red-100 text-red-900' : 'bg-amber-100 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['last_name'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <span
                                                            class="{{ ($extension_name === $result['extension_name'] && !is_null($extension_name)) || ($extension_name === $result['extension_name'] && $extension_name !== '') ? 'bg-red-100 text-red-900' : 'bg-amber-100 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['extension_name'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <span
                                                            class="{{ \Carbon\Carbon::createFromFormat('m-d-Y', $birthdate)->format('Y-m-d') === \Carbon\Carbon::parse($result['birthdate'])->format('Y-m-d') ? 'bg-red-100 text-red-900' : 'bg-amber-100 text-amber-900' }} rounded py-0.5 px-1.5">
                                                            {{ $result['birthdate'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        {{ $result['contact_num'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        {{ $result['barangay_name'] }}
                                                    </td>
                                                    <td class="px-2 py-2 capitalize">
                                                        {{ $result['sex'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        {{ $result['age'] }}
                                                    </td>
                                                    <td class="px-2 py-2 capitalize">
                                                        {{ $result['beneficiary_type'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        {{ $result['type_of_id'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        {{ $result['id_number'] }}
                                                    </td>
                                                    <td class="px-2 py-2 capitalize">
                                                        {{ $result['is_pwd'] }}
                                                    </td>
                                                    <td class="px-2 py-2">
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
                                <div x-cloak
                                    class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto backdrop-blur-sm z-50"
                                    x-show="addReasonModal">

                                    <!-- Modal -->
                                    <div x-show="addReasonModal" x-trap.noscroll.noautofocus="addReasonModal"
                                        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

                                        {{-- The Modal --}}
                                        <div class="relative size-full max-w-3xl">
                                            <div class="relative bg-white rounded-md shadow">
                                                <form wire:submit.prevent="saveReason">
                                                    <!-- Modal Header -->
                                                    <div
                                                        class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                        <span class="flex items-center justify-center">
                                                            <h1
                                                                class="text-sm sm:text-base font-semibold text-blue-1100">
                                                                Add Reason
                                                            </h1>
                                                        </span>

                                                        <div class="flex items-center justify-center">
                                                            {{-- Loading State for Changes --}}
                                                            <div class="z-50 text-blue-900" wire:loading>
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
                                                                class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                                                    <div class="pt-5 pb-6 px-3 md:px-12 text-blue-1100 text-xs">
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                                                            {{-- Case Proof --}}
                                                            <div class="relative col-span-full sm:col-span-1 pb-4">
                                                                <div class="flex flex-col items-start">
                                                                    <div class="flex items-center">
                                                                        <p
                                                                            class="inline mb-1 font-medium text-blue-1100">
                                                                            Case Proof <span
                                                                                class="text-gray-500">(optional)</span>
                                                                        </p>
                                                                    </div>

                                                                    {{-- Image Area --}}
                                                                    <label for="edit_reason_image_file_path"
                                                                        class="{{ $errors->has('reason_image_file_path') ? 'border-red-300 bg-red-50 text-red-500' : 'border-blue-300 bg-blue-50 text-gray-500' }} flex flex-col items-center justify-center w-full h-full border-2 border-dashed rounded cursor-pointer">

                                                                        {{-- Image Preview --}}
                                                                        <div
                                                                            class="relative flex flex-col items-center justify-center w-full h-full aspect-square">

                                                                            {{-- Loading State for Changes --}}
                                                                            <div class="absolute flex items-center justify-center w-full h-full z-50 text-blue-900"
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
                                                                            @if ($reason_saved_image_path || ($reason_image_file_path && !$errors->has('reason_image_file_path')))
                                                                                @if (is_object($reason_image_file_path))
                                                                                    <img class="size-[95%]"
                                                                                        src="{{ $reason_image_file_path->temporaryUrl() }}">
                                                                                @else
                                                                                    {{-- Show the saved image from the database --}}
                                                                                    <img class="size-[95%]"
                                                                                        src="{{ route('credentials.show', ['filename' => $reason_saved_image_path]) }}">
                                                                                @endif
                                                                                {{-- Default --}}
                                                                            @else
                                                                                <svg class="size-8 mb-4"
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
                                                                        <input id="edit_reason_image_file_path"
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
                                                                        class="block mb-1 font-medium text-blue-1100 ">Description
                                                                        <span
                                                                            class="text-red-700 font-normal text-xs">*</span></label>
                                                                    <textarea type="text" id="image_description" autocomplete="off" wire:model.blur="image_description"
                                                                        maxlength="255" rows="4"
                                                                        class="resize-none h-full text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('image_description') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
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
                                                                        class="px-2 py-1 rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 font-bold text-lg">
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
                        <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                            <label for="edit_first_name" class="block mb-1 font-medium text-blue-1100 ">First Name
                                <span class="text-red-700 font-normal text-xs">*</span></label>
                            <input type="text" id="edit_first_name" autocomplete="off"
                                wire:model.blur="first_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type first name">
                            @error('first_name')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Middle Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_middle_name" class="block mb-1  font-medium text-blue-1100 ">Middle
                                Name</label>
                            <input type="text" id="edit_middle_name" autocomplete="off"
                                wire:model.blur="middle_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('middle_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="(optional)">
                            @error('middle_name')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_last_name" class="block mb-1  font-medium text-blue-1100 ">Last Name
                                <span class="text-red-700 font-normal text-xs">*</span></label>
                            <input type="text" id="edit_last_name" autocomplete="off" wire:model.blur="last_name"
                                @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type last name">
                            @error('last_name')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Extension Name --}}
                        <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                            <label for="edit_extension_name" class="block mb-1 font-medium text-blue-1100 ">Ext.
                                Name</label>
                            <input type="text" id="edit_extension_name" autocomplete="off"
                                wire:model.blur="extension_name" @blur="$wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('extension_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="III, Sr., etc.">
                            @error('extension_name')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs whitespace-nowrap">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Birthdate --}}
                        <div x-data="{ birthdate: $wire.entangle('birthdate') }" class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_birthdate" class="block mb-1  font-medium text-blue-1100 ">Birthdate
                                <span class="text-red-700 font-normal text-xs">*</span></label>
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
                                id="edit_birthdate" autocomplete="off" wire:model.blur="birthdate"
                                @change-date.camel="$wire.set('birthdate', $el.value); $wire.nameCheck();"
                                class="text-xs border outline-none rounded block w-full py-2 ps-9 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Select date">
                            @error('birthdate')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contact Number --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_contact_num" class="block mb-1 font-medium text-blue-1100 ">Contact
                                Number <span class="text-red-700 font-normal text-xs">*</span></label>
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
                                <p class="whitespace-nowrap text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- E-payment Account Number --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_e_payment_acc_num"
                                class="block mb-1 font-medium text-blue-1100 ">E-payment
                                Account No.</label>
                            <input type="text" id="edit_e_payment_acc_num" autocomplete="off"
                                wire:model.blur="e_payment_acc_num"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600"
                                placeholder="Type e-payment account number">
                        </div>

                        {{-- Type of Beneficiary --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <p class="mb-1 font-medium text-blue-1100">Type of Beneficiary</p>
                            <div x-data="{ open: false, beneficiary_type: $wire.entangle('beneficiary_type') }" x-on:keydown.escape.prevent.stop="open = false;"
                                class="relative">

                                <!-- Button -->
                                <button type="button" @click="open = !open;" :aria-expanded="open"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-blue-50 border-blue-300 text-blue-1100 outline-blue-300 focus:outline-blue-600 focus:border-blue-600">
                                    <span x-text="beneficiary_type"></span>

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
                        <div x-data="{ avg: $wire.entangle('avg_monthly_income') }" class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_occupation" class="block mb-1  font-medium text-blue-1100 ">Occupation
                                <span x-show="avg" class="text-red-700 font-normal text-xs">*</span></label>
                            <input type="text" id="edit_occupation" autocomplete="off"
                                wire:model.live="occupation"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('occupation') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                placeholder="Type occupation">
                            @error('occupation')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Sex --}}
                        <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
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
                        <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
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
                                </div>
                            </div>
                        </div>

                        {{-- Average Monthly Income --}}
                        <div x-data="{ occ: $wire.entangle('occupation') }" class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_avg_monthly_income"
                                class="block mb-1  font-medium text-blue-1100 ">Average
                                Monthly
                                Income <span x-show="occ"
                                    class="text-red-700 font-normal text-xs">*</span></label></label>
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
                                    wire:model.blur="avg_monthly_income"
                                    class="text-xs outline-none border ps-10 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('avg_monthly_income') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50  border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                    placeholder="0.00">
                            </div>
                            @error('avg_monthly_income')
                                <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Dependent --}}
                        <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                            <div class="flex items-center">
                                <label for="edit_dependent"
                                    class="block mb-1 font-medium text-blue-1100 ">Dependent</label>
                                <p class="block mb-1 ms-2 text-gray-500 ">(must be 18+ years old)</p>
                            </div>
                            <input type="text" id="edit_dependent" autocomplete="off" wire:model.blur="dependent"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600"
                                placeholder="Type dependent's name">
                        </div>

                        {{-- Interested in Wage Employment or Self-Employment --}}
                        <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                            <p class="mb-1 font-medium text-blue-1100">Interested in Wage or
                                Self-Employment</p>
                            <div x-data="{ open: false, self_employment: $wire.entangle('self_employment') }" x-on:keydown.escape.prevent.stop="open = false;"
                                x-id="['self-employment-button']" class="relative">
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
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="edit_skills_training" class="block mb-1  font-medium text-blue-1100 ">Skills
                                Training
                                Needed</label>
                            <input type="text" id="edit_skills_training" autocomplete="off"
                                wire:model.blur="skills_training"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600"
                                placeholder="Type skills">
                        </div>

                        {{-- Bottom Section --}}
                        <div class="relative grid gap-x-4 gap-y-2 grid-cols-10 col-span-full grid-rows-2">

                            {{-- Proof of Identity --}}
                            <div class="relative h-full col-span-full row-span-full sm:col-span-2">
                                <div class="flex flex-col h-full items-start">
                                    <div class="flex items-center">
                                        <p class="inline mb-1 font-medium text-blue-1100">Proof of Identity <span
                                                class="text-gray-500">(optional)</span></p>

                                        {{-- Popover Thingy --}}
                                        <div x-data="{ pop: false }" class="relative flex items-center"
                                            id="identity-question-mark">
                                            <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-blue-700"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 20 20" @mouseleave="pop = false;"
                                                @mouseenter="pop = true;">
                                                <path
                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                            </svg>
                                            {{-- Popover --}}
                                            <div id="identity-popover"
                                                class="absolute -left-20 sm:left-0 bottom-full mb-2 z-50 text-xs whitespace-nowrap border border-gray-300 text-blue-50 bg-gray-700 rounded p-2 shadow"
                                                x-show="pop">
                                                It's basically an image of a beneficiary's ID card <br>
                                                to further prove that their identity is legitimate.
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Image Area --}}
                                    <label for="edit_image_file_path"
                                        class="flex flex-col items-center justify-center w-full h-full border-2 border-blue-300 border-dashed rounded cursor-pointer bg-blue-50">

                                        {{-- Image Preview --}}
                                        <div
                                            class="relative flex flex-col items-center justify-center py-4 w-full h-full">
                                            {{-- Loading State for Changes --}}
                                            <div class="absolute items-center justify-center w-full h-full z-50 text-blue-900"
                                                wire:loading.flex wire:target="image_file_path">
                                                <div
                                                    class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
                                                    {{-- Darkness... --}}
                                                </div>

                                                {{-- Loading Circle --}}
                                                <svg class="size-6 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>

                                            {{-- Preview --}}
                                            @if ($saved_image_path || ($image_file_path && !$errors->has('image_file_path')))
                                                @if (is_object($image_file_path))
                                                    <img class="w-28" src="{{ $image_file_path->temporaryUrl() }}">
                                                @else
                                                    {{-- Show the saved image from the database --}}
                                                    <img class="w-28"
                                                        src="{{ route('credentials.show', ['filename' => $saved_image_path]) }}">
                                                @endif
                                                {{-- Default --}}
                                            @else
                                                <svg class="size-8 mb-4 text-gray-500" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                </svg>
                                                <p class="mb-2 text-xs text-gray-500"><span
                                                        class="font-semibold">Click to
                                                        upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500 ">PNG or JPG (MAX. 5MB)</p>
                                            @endif
                                        </div>

                                        {{-- The Image itself --}}
                                        <input id="edit_image_file_path" wire:model="image_file_path" type="file"
                                            accept=".png,.jpg,.jpeg" class="hidden" />
                                    </label>
                                </div>
                                @error('image_file_path')
                                    <p
                                        class="text-center whitespace-nowrap w-full text-red-500 absolute -bottom-4 z-10 text-xs">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ID Type --}}
                            <div class="relative col-span-full sm:col-span-4 sm:row-span-1 mb-4 pb-1">
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

                                        @if ($birthdate && strtotime($birthdate) < strtotime(\Carbon\Carbon::now()->subYears(60)))
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
                            <div class="relative col-span-full sm:col-span-3 sm:row-span-1 mb-4 pb-1">
                                <label for="edit_id_number" class="block mb-1 font-medium text-blue-1100 ">ID Number
                                    <span class="text-red-700 font-normal text-xs">*</span></label>
                                <input type="text" id="edit_id_number" autocomplete="off"
                                    wire:model.blur="id_number"
                                    class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('id_number') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                                    placeholder="Type ID number">
                                @error('id_number')
                                    <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Is PWD? --}}
                            <div class="relative col-span-full sm:col-span-1 sm:row-span-1 mb-4 pb-1">
                                <div class="flex items-center">
                                    <p class="inline mb-1 font-medium text-blue-1100">Is PWD?</p>
                                    {{-- Popover Thingy --}}
                                    <div x-data="{ pop: false }" class="relative flex items-center"
                                        id="is-pwd-question-mark">
                                        <svg class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-blue-700"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20" @mouseleave="pop = false;" @mouseenter="pop = true;">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                        </svg>
                                        {{-- Popover --}}
                                        <div id="is-pwd-popover"
                                            class="absolute z-50 bottom-full mb-2 left-0 md:left-auto md:right-0 text-xs whitespace-nowrap border border-gray-300 text-blue-50 bg-gray-700 rounded p-2 shadow"
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
                                        <button type="button"
                                            @click="is_pwd = 'Yes'; open = false; $wire.$refresh();"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            Yes
                                        </button>

                                        <button type="button"
                                            @click="is_pwd = 'No'; open = false; $wire.$refresh(); "
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-blue-1100 hover:text-blue-900 focus:text-blue-900 active:text-blue-1000 hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-200">
                                            No
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Spouse First Name --}}
                            <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                                <label for="edit_spouse_first_name"
                                    class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-blue-1100' : 'text-gray-400' }}">Spouse
                                    First Name @if ($civil_status === 'Married')
                                        <span class="text-red-700 font-normal text-xs ms-0.5">*</span>
                                    @endif
                                </label>
                                <input type="text" id="edit_spouse_first_name" autocomplete="off"
                                    wire:model.blur="spouse_first_name"
                                    @if ($civil_status === 'Single') disabled @endif
                                    class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}
                                    @else
                                    bg-gray-200 border-gray-300 text-gray-500 @endif"
                                    placeholder="Type spouse first name">
                                @error('spouse_first_name')
                                    <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Spouse Middle Name --}}
                            <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                <label for="edit_spouse_middle_name"
                                    class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-blue-1100' : 'text-gray-400' }}">Spouse
                                    Middle Name </label>
                                <input type="text" id="edit_spouse_middle_name" autocomplete="off"
                                    wire:model.blur="spouse_middle_name"
                                    @if ($civil_status === 'Single') disabled @endif
                                    class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            {{ $civil_status === 'Married'
                                ? 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600'
                                : 'bg-gray-200 border-gray-300 text-gray-500' }}"
                                    placeholder="(optional)">
                            </div>

                            {{-- Spouse Last Name --}}
                            <div class="relative flex flex-col col-span-full sm:col-span-2 mb-4 pb-1">
                                <label for="edit_spouse_last_name"
                                    class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-blue-1100' : 'text-gray-400' }}">Spouse
                                    Last Name @if ($civil_status === 'Married')
                                        <span class="text-red-700 font-normal text-xs ms-0.5">*</span>
                                    @endif
                                </label>
                                <input type="text" id="edit_spouse_last_name" autocomplete="off"
                                    wire:model.blur="spouse_last_name"
                                    @if ($civil_status === 'Single') disabled @endif
                                    class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            
                            @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}
                            @else
                            bg-gray-200 border-gray-300 text-gray-500 @endif"
                                    placeholder="Type spouse last name">
                                @error('spouse_last_name')
                                    <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Spouse Extension Name --}}
                            <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                                <label for="edit_spouse_extension_name"
                                    class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-blue-1100' : 'text-gray-400' }}">Spouse
                                    Ext. Name</label>
                                <input type="text" id="edit_spouse_extension_name" autocomplete="off"
                                    wire:model.blur="spouse_extension_name"
                                    @if ($civil_status === 'Single') disabled @endif
                                    class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            {{ $civil_status === 'Married'
                                ? 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600'
                                : 'bg-gray-200 border-gray-300 text-gray-500' }}"
                                    placeholder="III, Sr., etc.">

                            </div>
                        </div>

                        {{-- Modal footer --}}
                        <div class="relative col-span-full w-full flex items-center justify-end">
                            <div class="flex items-center justify-end relative">
                                {{-- Loading State for Changes --}}
                                <button wire:click.prevent="editBeneficiary" wire:loading.attr="disabled"
                                    wire:target="editBeneficiary"
                                    class="space-x-2 py-2 px-4 text-center text-white font-bold flex items-center bg-blue-700 disabled:opacity-75 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-md">
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

                                    {{-- Add Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" wire:loading.remove
                                        wire:target="editBeneficiary" xmlns:xlink="http://www.w3.org/1999/xlink"
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
                    x-show="confirmTypeChangeModal">

                    <!-- Modal -->
                    <div x-show="confirmTypeChangeModal" x-trap.noreturn.noautofocus="confirmTypeChangeModal"
                        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

                        {{-- The Modal --}}
                        <div class="">
                            <div class="relative bg-white rounded-md shadow">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                    <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                                        Delete Beneficiary
                                    </h1>

                                    {{-- Close Button --}}
                                    <button type="button"
                                        @if ($confirmChangeType === 'beneficiary_type') @click="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'birthdate')
                                        @click="$wire.set('birthdate', '{{ \Carbon\Carbon::parse($this->beneficiary->birthdate)->format('m-d-Y') }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'first_name')
                                        @click="$wire.set('first_name', '{{ $this->beneficiary->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'middle_name')
                                        @click="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'last_name')
                                        @click="$wire.set('last_name', '{{ $this->beneficiary->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                    @elseif($confirmChangeType === 'extension_name') 
                                        @click="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif
                                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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

                                    <p class="font-medium text-sm mb-4">
                                        @if ($confirmChangeType === 'beneficiary_type')
                                            Are you sure about changing the beneficiary type?
                                        @elseif($confirmChangeType === 'birthdate')
                                            Are you sure about changing the birthdate?
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
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-gray-500 hover:border-blue-800 active:border-blue-900 text-gray-500 hover:text-blue-800 active:text-blue-50 active:bg-blue-900"
                                            @if ($confirmChangeType === 'beneficiary_type') @click="$wire.set('beneficiary_type', 'Special Case'); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'birthdate')
                                            
                                                @click="$wire.set('birthdate', '{{ \Carbon\Carbon::parse($this->beneficiary->birthdate)->format('m-d-Y') }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'first_name')
                                           
                                                @click="$wire.set('first_name', '{{ $this->beneficiary->first_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'middle_name')
                                            
                                                @click="$wire.setFieldName('middle_name'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                
                                        @elseif($confirmChangeType === 'last_name')
                                             
                                                @click="$wire.set('last_name', '{{ $this->beneficiary->last_name }}'); $wire.nameCheck(); confirmTypeChangeModal = false;"
                                                 
                                        @elseif($confirmChangeType === 'extension_name')
                                                
                                            @click="$wire.setFieldName('extension_name'); $wire.nameCheck(); confirmTypeChangeModal = false;" @endif>NO</button>
                                        <button type="button"
                                            @click="$wire.revokeSpecialCase(); confirmTypeChangeModal = false;"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">YES</button>
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
