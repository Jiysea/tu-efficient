<div x-cloak x-data="{
    accessCodeModal: $wire.entangle('accessCodeModal'),
    confirmModal: $wire.entangle('confirmModal'),
}" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
    x-show="viewBatchModal" @keydown.window.escape="if(!accessCodeModal && !confirmModal) viewBatchModal = false">

    <!-- Modal -->
    <div x-show="viewBatchModal" x-trap.noautofocus.noscroll="viewBatchModal"
        class="relative h-full p-4 flex items-start justify-center overflow-y-auto z-50 select-none">

        {{-- The Modal --}}
        <div class="w-full max-w-4xl">
            <div class="relative bg-white text-blue-1100 rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                            View Batch
                        </h1>
                    </span>

                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-blue-900" wire:loading wire:target="confirmModalOpen">
                            <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        {{-- Close Modal --}}
                        <button type="button" @click="viewBatchModal = false;"
                            class="outline-none text-blue-400 focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                        {{-- Batch Number --}}
                        <div class="flex flex-1 flex-col relative">
                            <p class="block mb-1 font-medium">
                                Batch Number
                            </p>
                            <span
                                class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-700 font-medium">{{ $this->batch?->batch_num }}</span>
                        </div>

                        @if ($this->batch?->is_sectoral)
                            {{-- Sector Title OFF --}}
                            <div class="relative flex flex-col sm:col-span-2">
                                <p class="mb-1 font-medium text-blue-1100 ">
                                    Sector Title
                                </p>
                                <span class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-700 font-medium">
                                    <span
                                        class="whitespace-nowrap overflow-x-auto scrollbar-none select-text">{{ $this->batch?->sector_title }}
                                    </span>
                                </span>
                            </div>
                        @else
                            {{-- District OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-blue-1100">
                                    District
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-700 font-medium">{{ $this->batch?->district }}</span>
                            </div>

                            {{-- Barangay OFF --}}
                            <div class="relative flex flex-col">
                                <p class="block mb-1 font-medium text-blue-1100">
                                    Barangay
                                </p>
                                <span
                                    class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-700 font-medium">{{ $this->batch?->barangay_name }}</span>
                            </div>
                        @endif

                        {{-- Slots Allocated --}}
                        <div class="flex flex-1 flex-col relative">
                            <p class="block mb-1 font-medium">
                                Current / Total Slots
                            </p>
                            <span
                                class="flex flex-1 text-sm rounded p-2.5 bg-blue-50 text-blue-900 font-medium">{{ $this->currentSlots . ' / ' . $this->batch?->slots_allocated }}</span>
                        </div>

                        {{-- Other Coordinators --}}
                        <div class="flex flex-1 flex-col col-span-full relative">
                            <p class="block mb-1 font-medium">
                                Other Coordinators
                            </p>
                            <div
                                class="flex flex-1 flex-wrap gap-2 sm:gap-4 text-sm rounded p-2.5 bg-blue-50 text-blue-1000 font-medium">
                                @forelse ($this->assignments as $key => $assignment)
                                    <span wire:key="{{ $key }}"
                                        class="text-center bg-blue-200 rounded px-2 py-1">
                                        {{ $this->getFullName($assignment) }}
                                    </span>
                                @empty
                                    <span class="w-full text-center bg-red-200 rounded px-2 py-1">
                                        NO OTHER COORDINATORS
                                    </span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Type of Batch OFF --}}
                        <div class="relative flex flex-col">
                            <p class="flex justify-center mb-1 font-medium text-blue-1100">
                                Type of Batch
                            </p>
                            <span class="flex justify-center text-sm rounded p-1.5 font-medium">
                                <span
                                    class="rounded-full py-1 px-3 font-semibold {{ $this->batch?->is_sectoral ? 'bg-rose-200 text-rose-800' : 'bg-emerald-200 text-emerald-800' }}">{{ $this->batch?->is_sectoral ? 'SECTORAL' : 'NON-SECTORAL' }}</span>
                            </span>
                        </div>

                        {{-- Approval Status OFF --}}
                        <div class="relative flex flex-col">
                            <p class="flex justify-center mb-1 font-medium text-blue-1100">
                                Approval Status
                            </p>
                            <span class="flex justify-center text-sm rounded p-1.5 text-blue-700 font-medium uppercase">
                                <span class="rounded-full py-1 px-3 font-semibold"
                                    :class="{
                                        'bg-amber-300 text-amber-900': {{ json_encode($this->batch?->approval_status === 'pending') }},
                                        'bg-green-300 text-green-1000': {{ json_encode($this->batch?->approval_status === 'approved') }},
                                    }">{{ $this->batch?->approval_status }}</span>
                            </span>
                        </div>

                        {{-- Submission Status OFF --}}
                        <div class="relative flex flex-col">
                            <p class="flex justify-center mb-1 font-medium text-blue-1100">
                                Submission Status
                            </p>
                            <span class="flex justify-center text-sm rounded p-1.5 text-blue-700 font-medium uppercase">
                                <span class="rounded-full py-1 px-3 font-semibold"
                                    :class="{
                                        'bg-amber-200 text-amber-900': {{ json_encode($this->batch?->submission_status === 'unopened') }},
                                        'bg-green-200 text-green-1000': {{ json_encode($this->batch?->submission_status === 'submitted') }},
                                        'bg-blue-200 text-blue-900': {{ json_encode($this->batch?->submission_status === 'encoding') }},
                                        'bg-red-200 text-red-900': {{ json_encode($this->batch?->submission_status === 'revalidate') }},
                                    }">{{ $this->batch?->submission_status }}</span>
                            </span>
                        </div>

                        {{-- Date Created / Last Updated --}}
                        <div class="flex items-center justify-between col-span-full relative">
                            <span class="flex items-center justify-center">
                                <p class="block font-medium">
                                    Date Created:
                                </p>
                                <span
                                    class="ms-2 flex items-center justify-center rounded px-2 py-1 bg-blue-50 text-blue-900 font-medium">{{ date('M d, Y @ h:i:s a', strtotime($this->batch?->created_at)) }}</span>
                            </span>

                            <span class="flex items-center justify-center">
                                <p class="block font-medium">
                                    Last Updated:
                                </p>
                                <span
                                    class="ms-2 flex items-center justify-center rounded px-2 py-1 bg-blue-50 text-blue-900 font-medium">{{ date('M d, Y @ h:i:s a', strtotime($this->batch?->updated_at)) }}</span>
                            </span>
                        </div>

                        {{-- Buttons --}}
                        @if ($this->batch?->approval_status === 'pending')
                            <div class="flex items-center justify-end gap-2 col-span-full relative text-base font-bold">
                                @if ($this->batch?->submission_status === 'submitted')
                                    <button type="button" wire:click="confirmModalOpen('{{ encrypt('revalidate') }}')"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-red-700 hover:border-transparent active:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50 active:text-red-50">
                                        REVALIDATE
                                    </button>
                                    <button type="button"
                                        @if ($this->currentSlots !== $this->batch?->slots_allocated) disabled @else wire:click="confirmModalOpen('{{ encrypt('approve') }}')" @endif
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-transparent bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                        APPROVE
                                    </button>
                                @elseif ($this->batch?->submission_status === 'unopened')
                                    <button type="button" @click="accessCodeModal = !accessCodeModal;"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-transparent bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                        OPEN ACCESS
                                    </button>
                                @elseif ($this->batch?->submission_status === 'encoding')
                                    <button type="button" @click="accessCodeModal = !accessCodeModal;"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-red-700 hover:border-transparent active:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50 active:text-red-50">
                                        REGENERATE CODE
                                    </button>
                                    <button type="button"
                                        wire:click="confirmModalOpen('{{ encrypt('force_submit') }}')"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-transparent bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                        FORCE SUBMIT
                                    </button>
                                @elseif ($this->batch?->submission_status === 'revalidate')
                                    <button type="button" @click="accessCodeModal = !accessCodeModal;"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-red-700 hover:border-transparent active:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50 active:text-red-50">
                                        REGENERATE CODE
                                    </button>
                                    <button type="button" wire:click="confirmModalOpen('{{ encrypt('resolve') }}')"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded border border-transparent bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                        RESOLVE
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- OPEN ACCESS / GENERATE CODE MODAL --}}
        <div x-cloak @keydown.window.escape="accessCodeModal = false;">
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="accessCodeModal">
            </div>

            <!-- Modal -->
            <div x-show="accessCodeModal" x-trap.noscroll.noautofocus="accessCodeModal"
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
                                <div class="z-50 {{ $this->batch?->submission_status === 'revalidate' ? 'text-red-900' : 'text-blue-900' }}"
                                    wire:loading wire:target="generateCode">
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
                                    class="outline-none rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out
                                    {{ $this->batch?->submission_status === 'revalidate' ? 'text-red-400 focus:bg-red-200 focus:text-red-900 hover:bg-red-200 hover:text-red-900' : 'text-blue-400 focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900' }}">
                                    <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close Modal</span>
                                </button>
                            </div>
                        </div>

                        <hr class="">

                        {{-- Modal body --}}
                        <div class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-16 text-blue-1100 text-xs">
                            <p class="mb-2 text-sm">Do not share this access code to just anyone!</p>
                            <div x-data="{ code: $wire.entangle('code'), copied: false, tooltip: false, tooltip2: false }" class="relative flex items-center justify-center w-full">
                                <div class="relative me-2">
                                    @if ($this->batch?->submission_status === 'unopened' || $this->batch?->submission_status === 'submitted')
                                        <button wire:loading.attr="disabled" @mouseover="tooltip2 = true;"
                                            @mouseleave="tooltip2 = false;"
                                            class="flex items-center justify-center border border-transparent disabled:opacity-75 p-2 rounded text-base font-bold duration-200 ease-in-out bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50"
                                            @click="if(copied == false) { $wire.generateCode(); }">
                                            OPEN
                                        </button>

                                        <span x-show="tooltip2" x-transition.opacity
                                            class="absolute bottom-full mb-2 left-0 border border-zinc-300 bg-zinc-900 text-zinc-50 rounded p-2 whitespace-nowrap">
                                            This will open the batch for submission<br>
                                            and generates an <span class="text-blue-500">access code</span>.
                                        </span>
                                    @elseif ($this->batch?->submission_status === 'encoding' || $this->batch?->submission_status === 'revalidate')
                                        <button wire:loading.attr="disabled" @mouseover="tooltip2 = true;"
                                            @mouseleave="tooltip2 = false;"
                                            class="flex items-center justify-center border border-transparent disabled:opacity-75 p-2 rounded text-base font-bold duration-200 ease-in-out"
                                            @click="if(copied == false) { $wire.generateCode(); }"
                                            :class="{
                                                'bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50': {{ json_encode($this->batch?->submission_status === 'revalidate') }},
                                                'bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50': {{ json_encode($this->batch?->submission_status === 'encoding') }},
                                            }">
                                            GENERATE
                                        </button>

                                        <span x-show="tooltip2" x-transition.opacity
                                            class="absolute bottom-full mb-2 left-0 border border-zinc-300 bg-zinc-900 text-zinc-50 rounded p-2 whitespace-nowrap">
                                            This will generate another unique <span
                                                class="{{ $this->batch?->submission_status === 'revalidate' ? 'text-red-500' : 'text-blue-500' }}">access
                                                code</span>.
                                        </span>
                                    @endif
                                </div>

                                <input type="text" id="code" x-model="code"
                                    @if ($this->batch?->submission_status === 'unopened' || $this->batch?->submission_status === 'submitted') disabled
                                    @else readonly @endif
                                    class="flex flex-1 rounded outline-none focus:outline-none border py-2.5 text-sm select-all duration-200 ease-in-out
                                        disabled:bg-gray-50 disabled:text-gray-500 disabled:border-gray-300"
                                    placeholder="Generate the access code"
                                    :class="{
                                        'border-red-300 bg-red-50 text-red-950 focus:ring-1 focus:ring-red-500 focus:border-red-300 selection:bg-red-700 selection:text-red-50': {{ json_encode($this->batch?->submission_status === 'revalidate') }},
                                        'border-blue-300 bg-blue-50 text-blue-1100 focus:ring-1 focus:ring-blue-500 focus:border-blue-300 selection:bg-blue-700 selection:text-blue-50': {{ json_encode($this->batch?->submission_status === 'encoding') }},
                                    }">

                                <div class="relative ms-2">
                                    <button @mouseover="tooltip = true;" @mouseleave="tooltip = false;"
                                        class="flex flex-1 items-center justify-center border p-3 rounded text-sm duration-200 ease-in-out
                                            disabled:border-gray-300 disabled:bg-gray-300 disabled:text-gray-500"
                                        :class="{
                                            'border-red-300 active:border-red-500 bg-red-50 hover:bg-red-100 active:bg-red-200 text-red-700 hover:text-red-800 active:text-red-900': {{ json_encode($this->batch?->submission_status === 'revalidate') }},
                                            'border-blue-300 active:border-blue-500 bg-blue-50 hover:bg-blue-100 active:bg-blue-200 text-blue-700 hover:text-blue-800 active:text-blue-900': {{ json_encode($this->batch?->submission_status === 'encoding') }},
                                        }"
                                        @if ($this->batch?->submission_status !== 'unopened') @click="if(copied == false) {navigator.clipboard.writeText(code).then(() => copied = true); setTimeout(() => copied = false, 3000);}"
                                        @else disabled @endif>
                                        <span id="default-icon" x-show="!copied">
                                            <svg class="size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 18 20">
                                                <path
                                                    d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                            </svg>
                                        </span>
                                        <span id="success-icon" x-show="copied">
                                            <svg class="size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 16 12">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M1 5.917 5.724 10.5 15 1.5" />
                                            </svg>
                                        </span>
                                    </button>


                                    <span x-show="tooltip" x-transition.opacity
                                        class="absolute bottom-full mb-2 right-0 border border-zinc-300 bg-zinc-900 text-zinc-50 rounded p-2 whitespace-nowrap">
                                        @if ($this->batch?->submission_status !== 'unopened')
                                            <p x-show="!copied">Copy Code</p>
                                            <p x-show="copied">Copied to Clipboard!</p>
                                        @else
                                            <p>You will be able to copy if <br>
                                                there is an <span class="text-blue-500">access code</span>.</p>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONFIRMATION MODAL --}}
        <div x-cloak @keydown.window.escape="$wire.resetConfirm(); confirmModal = false"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="confirmModal">

            <!-- Modal -->
            <div x-show="confirmModal" x-trap.noscroll="confirmModal"
                class="relative h-full p-4 flex justify-center items-center overflow-y-auto z-50 select-none">

                {{-- The Modal --}}
                <div class="w-full max-w-2xl">
                    <div class="relative bg-white rounded-md shadow">

                        <!-- Modal Header -->
                        <div class="relative flex items-center justify-between py-2 px-4 rounded-t-md">
                            <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                                @if ($this->confirmType === 'revalidate')
                                    Revalidate this Batch
                                @elseif($this->confirmType === 'resolve')
                                    Resolve this Batch
                                @elseif($this->confirmType === 'force_submit')
                                    Force Submit this Batch
                                @elseif($this->confirmType === 'approve')
                                    Approve this Batch
                                @endif
                            </h1>

                            <div class="flex items-center justify-center gap-2">

                                {{-- Loading State for Changes --}}
                                <svg class="size-6 text-blue-900 animate-spin" wire:loading
                                    wire:target="forceSubmitOrResolve, revalidateSubmission, approveSubmission"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Close Modal --}}
                                <button type="button" @click="$wire.resetConfirm(); confirmModal = false;"
                                    class="outline-none text-blue-400 focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                    <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6">
                                        </path>
                                    </svg>
                                    <span class="sr-only">Close Modal</span>
                                </button>
                            </div>
                        </div>

                        <hr class="">

                        {{-- Modal body --}}
                        <form
                            @if (in_array($this->confirmType, ['resolve', 'force_submit'])) wire:submit.prevent="forceSubmitOrResolve"
                            @elseif($this->confirmType === 'revalidate')
                                wire:submit.prevent="revalidateSubmission"
                            @elseif($this->confirmType === 'approve') 
                                wire:submit.prevent="approveSubmission" @endif
                            class="grid w-full place-items-center pt-5 pb-10 px-3 md:px-16 text-xs">

                            @if ($this->confirmType === 'revalidate')
                                <p class="mb-2 text-sm font-medium">Are you sure about revalidating this batch?</p>
                            @elseif($this->confirmType === 'resolve')
                                <p class="mb-2 text-sm font-medium">Are you sure about resolving this batch?</p>
                            @elseif($this->confirmType === 'force_submit')
                                <p class="mb-2 text-sm font-medium">Are you sure about force submitting this batch?</p>
                            @elseif($this->confirmType === 'approve')
                                <p class="mb-2 text-sm font-medium">Are you sure about approving this batch?</p>
                            @endif

                            <div class="relative flex items-center justify-center w-full">
                                <div class="flex items-center justify-center">
                                    <div class="relative me-2">
                                        <input type="password" id="password_confirm" autofocus autocomplete="off"
                                            wire:model.blur="password_confirm"
                                            class="flex flex-1 {{ $errors->has('password_confirm') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 selection:bg-red-700 selection:text-red-50' : 'border-blue-300 bg-blue-50 selection:bg-blue-700 selection:text-blue-50' }} focus:ring-0 rounded outline-none border py-2.5 text-sm duration-200 ease-in-out"
                                            placeholder="Enter your password">
                                        @error('password_confirm')
                                            <p class="absolute top-full left-0 text-xs text-red-700">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    <button type="submit"
                                        wire:target="forceSubmitOrResolve, revalidateSubmission, approveSubmission"
                                        class="flex items-center justify-center border border-transparent disabled:opacity-75 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 p-2 rounded text-base font-bold duration-200 ease-in-out">
                                        CONFIRM
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
