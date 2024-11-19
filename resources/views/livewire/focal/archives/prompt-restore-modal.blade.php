<div x-cloak class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50"
    x-show="promptRestoreModal">

    <!-- Modal -->
    <div x-trap.noautofocus.noscroll="promptRestoreModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="relative size-full max-w-xl">
            <div class="relative bg-white rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                        Restore this Record
                    </h1>

                    <div class="flex items-center justify-between gap-4">
                        {{-- Loading Icon --}}
                        <svg class="size-6 text-indigo-900 animate-spin" wire:loading wire:target="restoreRow"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        {{-- Close Button --}}
                        <button type="button" @click="promptRestoreModal = false;"
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
                <div class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">

                    <p class="font-medium text-sm mb-2">
                        Are you sure about restoring this record?
                    </p>
                    <p class="text-gray-500 text-xs font-normal mb-4">
                        Make sure its origin batch is not full-slotted, approved, or non-existent.
                    </p>

                    <div class="flex items-center justify-center w-full gap-2">
                        {{-- Cancel Button --}}
                        <button type="button" @click="promptRestoreModal = false;"
                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-indigo-700 hover:border-transparent hover:bg-indigo-800 active:bg-indigo-900 text-indigo-700 hover:text-indigo-50 active:text-indigo-50">
                            CANCEL
                        </button>

                        {{-- Confirm Button --}}
                        <button type="button" wire:click="$parent.restoreRow();"
                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-indigo-700 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                            CONFIRM
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
