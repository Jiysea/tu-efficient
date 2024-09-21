<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="viewBatchModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="viewBatchModal" x-trap.noscroll="viewBatchModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full"
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        {{-- The Modal --}}
        <div class="relative w-full max-w-4xl max-h-full">
            <div class="relative bg-white text-indigo-1100 rounded-md shadow">

                <form wire:submit.prevent="saveProject">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                        <span class="flex items-center justify-center">
                            <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">View Batch
                            </h1>

                        </span>
                        <div class="flex items-center justify-center">
                            {{-- Loading State for Changes --}}
                            <div class="z-50 text-indigo-900" wire:loading>
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
                            <button type="button" @click="$wire.resetEverything(); viewBatchModal = false;"
                                class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                    <div class="pt-5 pb-6 px-3 md:px-12 text-xs">
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

                            {{-- Buttons --}}
                            <div
                                class="flex items-center justify-center gap-2 sm:gap-4 col-span-full relative mb-4 text-base font-bold">
                                @if ($this->batch->submission_status === 'submitted' && $this->batch->approval_status === 'pending')
                                    <button type="button"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                                        REVALIDATE
                                    </button>
                                    <button type="button" @if ($this->currentSlots !== $this->batch->slots_allocated) disabled @endif
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded disabled:bg-green-300 bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                        APPROVE
                                    </button>
                                @elseif($this->batch->submission_status === 'unopened')
                                    <button type="button"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-blue-50">
                                        OPEN ACCESS
                                    </button>
                                @elseif($this->batch->submission_status === 'encoding')
                                    <button type="button"
                                        class="text-center px-2 py-1 duration-200 ease-in-out outline-none rounded bg-amber-700 hover:bg-amber-800 active:bg-amber-900 text-amber-50">
                                        CLOSE ACCESS / REGENERATE CODE
                                    </button>
                                @endif
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
