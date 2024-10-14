<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="viewBatchModal">

    <!-- Modal -->
    <div x-data="{ accessCodeModal: $wire.entangle('accessCodeModal'), forceSubmitConfirmationModal: $wire.entangle('forceSubmitConfirmationModal'), revalidateConfirmationModal: $wire.entangle('revalidateConfirmationModal') }" x-show="viewBatchModal" x-trap.noscroll="viewBatchModal"
        class="min-h-screen p-4 flex items-center justify-center overflow-y-auto z-50 select-none">

        @if ($passedBatchId)
            {{-- The Modal --}}
            <div class="size-full max-w-4xl">
                <div class="relative bg-white text-blue-1100 rounded-md shadow">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                        <span class="flex items-center justify-center">
                            <h1 class="text-sm sm:text-base font-semibold text-blue-1100">View Batch
                            </h1>

                        </span>
                        <div class="flex items-center justify-center">
                            {{-- Loading State for Changes --}}
                            <div class="z-50 text-blue-900" wire:loading
                                wire:target="generateCode, forceSubmitOrResolve, revalidateSubmission">
                                <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>

                            {{-- Close Modal --}}
                            <button type="button" @click="$wire.resetEverything(); viewBatchModal = false;"
                                class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                    <form wire:submit.prevent="saveProject" class="py-5 px-3 md:px-12 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                            {{-- Batch Number --}}
                            <div class="flex flex-1 flex-col relative mb-4">
                                <p class="block mb-1 font-medium">
                                    Batch Number
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-900 font-medium">{{ $this->batch->batch_num }}</span>
                            </div>

                            {{-- Barangay --}}
                            <div class="flex flex-1 flex-col relative mb-4">
                                <p class="block mb-1 font-medium">
                                    Barangay
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-900 font-medium">{{ $this->batch->barangay_name }}</span>
                            </div>

                            {{-- Slots Allocated --}}
                            <div class="flex flex-1 flex-col relative mb-4">
                                <p class="block mb-1 font-medium">
                                    Current / Total Slots
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-900 font-medium">{{ $this->currentSlots . ' / ' . $this->batch->slots_allocated }}</span>
                            </div>

                            {{-- Other Coordinators --}}
                            <div class="flex flex-1 flex-col col-span-full relative mb-4">
                                <p class="block mb-1 font-medium">
                                    Other Coordinators
                                </p>
                                <div
                                    class="flex flex-1 flex-wrap gap-2 sm:gap-4 text-sm rounded p-2.5 bg-blue-50 text-blue-1000 font-medium">
                                    @foreach ($this->assignments as $key => $assignment)
                                        <span wire:key="{{ $key }}"
                                            class="text-center bg-blue-200 rounded px-2 py-1">
                                            {{ $this->getFullName($assignment) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Date Created / Last Updated --}}
                            <div class="flex items-center justify-between col-span-full relative mb-4">
                                <span class="flex items-center justify-center">
                                    <p class="block font-medium">
                                        Date Created:
                                    </p>
                                    <span
                                        class="ms-2 flex items-center justify-center rounded px-2 py-1 bg-blue-50 text-blue-900 font-medium">{{ date('M d, Y @ h:i:s a', strtotime($this->batch->created_at)) }}</span>
                                </span>

                                <span class="flex items-center justify-center">
                                    <p class="block font-medium">
                                        Last Updated:
                                    </p>
                                    <span
                                        class="ms-2 flex items-center justify-center rounded px-2 py-1 bg-blue-50 text-blue-900 font-medium">{{ date('M d, Y @ h:i:s a', strtotime($this->batch->updated_at)) }}</span>
                                </span>
                            </div>

                            {{-- Buttons --}}
                            @if ($this->batch->approval_status === 'pending')
                                <div
                                    class="flex items-center justify-end gap-2 sm:gap-4 col-span-full relative text-base font-bold">
                                    @if ($this->batch->submission_status === 'submitted')
                                        <button type="button"
                                            @click="revalidateConfirmationModal = !revalidateConfirmationModal;"
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                            REVALIDATE
                                        </button>
                                        <button type="button" @if ($this->currentSlots !== $this->batch->slots_allocated) disabled @endif
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded disabled:bg-green-300 bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                            APPROVE
                                        </button>
                                    @elseif ($this->batch->submission_status === 'unopened')
                                        <button type="button" @click="accessCodeModal = !accessCodeModal;"
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                            OPEN ACCESS
                                        </button>
                                    @elseif ($this->batch->submission_status === 'encoding')
                                        <button type="button"
                                            @click="forceSubmitConfirmationModal = !forceSubmitConfirmationModal;"
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-amber-700 hover:bg-amber-800 active:bg-amber-900 text-amber-50">
                                            FORCE SUBMIT
                                        </button>
                                        <button type="button" @click="accessCodeModal = !accessCodeModal;"
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                            REGENERATE CODE
                                        </button>
                                    @elseif ($this->batch->submission_status === 'revalidate')
                                        <button type="button"
                                            @click="forceSubmitConfirmationModal = !forceSubmitConfirmationModal;"
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                            RESOLVE
                                        </button>
                                        <button type="button" @click="accessCodeModal = !accessCodeModal;"
                                            class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                            REGENERATE CODE
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Other Nested Modals --}}

            {{-- OPEN ACCESS / GENERATE CODE MODAL --}}
            <div x-cloak>
                <!-- Modal Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="accessCodeModal">
                </div>

                <!-- Modal -->
                <div x-show="accessCodeModal" x-trap.noscroll="accessCodeModal"
                    class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                    {{-- The Modal --}}
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-md shadow">

                            <!-- Modal Header -->
                            <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                                <h1 class="text-sm sm:text-base font-semibold text-blue-1100">Access Code
                                </h1>

                                <div class="flex items-center justify-center">
                                    {{-- Loading State for Changes --}}
                                    <div class="z-50 text-blue-900" wire:loading wire:target="generateCode">
                                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>

                                    {{-- Close Modal --}}
                                    <button type="button" @click="accessCodeModal = false;"
                                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                        <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
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
                            <div class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-16 text-blue-1100 text-xs">
                                <p class="mb-2 text-sm">Do not share this access code to just anyone!</p>
                                <div x-data="{ code: $wire.entangle('code'), copied: false, tooltip: false }" class="relative flex items-center justify-center w-full">
                                    <div class="relative me-2">
                                        @if ($this->batch->submission_status === 'unopened')
                                            <button wire:loading.attr="disabled"
                                                class="flex items-center justify-center disabled:bg-blue-300 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                                @click="
                                        if(copied == false) {
                                            $wire.generateCode();
                                        }">
                                                GENERATE / OPEN
                                            </button>
                                        @elseif ($this->batch->submission_status === 'encoding' || $this->batch->submission_status === 'revalidate')
                                            <button wire:loading.attr="disabled"
                                                class="flex items-center justify-center disabled:bg-blue-300 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 p-2 rounded text-sm font-bold duration-200 ease-in-out"
                                                @click="
                                        if(copied == false) {
                                            $wire.generateCode();
                                        }">
                                                REGENERATE
                                            </button>
                                        @endif
                                    </div>

                                    <input type="text" id="code" x-model="code" readonly
                                        class="flex flex-1 border-blue-300 bg-blue-50 text-blue-1100 rounded outline-none border py-2.5 text-sm select-all duration-200 ease-in-out"
                                        placeholder="Generate the access code">

                                    <div class="relative ms-2">
                                        <button @mouseover="tooltip = true;" @mouseleave="tooltip = false;"
                                            class="flex flex-1 items-center justify-center border border-gray-300 active:border-gray-500 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-500 p-3 rounded text-sm duration-200 ease-in-out"
                                            @click="
                                        if(copied == false) {
                                            navigator.clipboard.writeText(code)
                                            .then(() => copied = true); 
                                            setTimeout(() => copied = false, 3000);
                                        }">
                                            <span id="default-icon" x-show="!copied">
                                                <svg class="size-4" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 18 20">
                                                    <path
                                                        d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                                </svg>
                                            </span>
                                            <span id="success-icon" x-show="copied">
                                                <svg class="size-4" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 16 12">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M1 5.917 5.724 10.5 15 1.5" />
                                                </svg>
                                            </span>
                                        </button>

                                        <span x-show="tooltip" x-transition.opacity
                                            class="absolute -top-full right-0 bg-gray-700 text-blue-50 rounded p-2 whitespace-nowrap">
                                            <p x-show="!copied">Copy Code</p>
                                            <p x-show="copied">Copied to Clipboard!</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORCE SUBMIT && RESOLVE CONFIRMATION MODAL --}}
            <div x-cloak>
                <!-- Modal Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                    x-show="forceSubmitConfirmationModal">
                </div>

                <!-- Modal -->
                <div x-show="forceSubmitConfirmationModal" x-trap.noscroll="forceSubmitConfirmationModal"
                    class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                    {{-- The Modal --}}
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-md shadow">

                            <!-- Modal Header -->
                            <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                                <h1 class="text-sm sm:text-base font-semibold text-blue-1100">Access Code
                                </h1>

                                <div class="flex items-center justify-center">
                                    {{-- Loading State for Changes --}}
                                    <div class="z-50 text-blue-900" wire:loading wire:target="forceSubmitOrResolve">
                                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>

                                    {{-- Close Modal --}}
                                    <button type="button" @click="forceSubmitConfirmationModal = false;"
                                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                        <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
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
                            <div class="grid w-full place-items-center pt-5 pb-10 px-3 md:px-16 text-xs">

                                @if ($this->batch->submission_status === 'encoding')
                                    <p class="mb-2 text-sm font-medium">Are you sure about force submitting this batch?
                                    </p>
                                @elseif($this->batch->submission_status === 'revalidate')
                                    <p class="mb-2 text-sm font-medium">Are you sure about resolving this batch?</p>
                                @endif

                                <div class="relative flex items-center justify-center w-full">
                                    <div class="flex items-center justify-center">
                                        <div class="relative me-2">
                                            <input type="password" id="password_force_submit"
                                                wire:model.blur="password_force_submit"
                                                class="flex flex-1 {{ $errors->has('password_force_submit') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'border-blue-300 bg-blue-50' }} rounded outline-none border py-2.5 text-sm select-all duration-200 ease-in-out"
                                                placeholder="Enter your password">
                                            @error('password_force_submit')
                                                <p class="absolute top-full left-0 text-xs text-red-700">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <button wire:loading.attr="disabled" wire:target="forceSubmitOrResolve"
                                            class="flex items-center justify-center disabled:bg-red-300 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                            @click="$wire.forceSubmitOrResolve();">
                                            CONFIRM
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- REVALIDATING CONFIRMATION MODAL --}}
            <div x-cloak>
                <!-- Modal Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                    x-show="revalidateConfirmationModal">
                </div>

                <!-- Modal -->
                <div x-show="revalidateConfirmationModal" x-trap.noscroll="revalidateConfirmationModal"
                    class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full">

                    {{-- The Modal --}}
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-md shadow">

                            <!-- Modal Header -->
                            <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                                <h1 class="text-sm sm:text-base font-semibold text-blue-1100">Access Code
                                </h1>

                                <div class="flex items-center justify-center">
                                    {{-- Loading State for Changes --}}
                                    <div class="z-50 text-blue-900" wire:loading wire:target="revalidateSubmission">
                                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>

                                    {{-- Close Modal --}}
                                    <button type="button" @click="revalidateConfirmationModal = false;"
                                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                        <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
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
                            <div class="grid w-full place-items-center pt-5 pb-10 px-3 md:px-16 text-xs">
                                <p class="mb-2 text-sm font-medium">Are you sure about revalidating this batch?</p>
                                <div class="relative flex items-center justify-center w-full">
                                    <div class="flex items-center justify-center">
                                        <div class="relative me-2">
                                            <input type="password" id="password_revalidate"
                                                wire:model.blur="password_revalidate"
                                                class="flex {{ $errors->has('password_revalidate') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'border-blue-300 bg-blue-50' }} rounded outline-none border p-2.5 text-sm select-all duration-200 ease-in-out"
                                                placeholder="Enter your password">
                                            @error('password_revalidate')
                                                <p class="absolute top-full left-0 text-xs text-red-700">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <button wire:loading.attr="disabled" wire:target="revalidateSubmission"
                                            class="flex items-center justify-center disabled:bg-red-300 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                            @click="$wire.revalidateSubmission();">
                                            CONFIRM
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>
</div>
