<div x-cloak class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50"
    x-show="promptMultiDeleteModal" @keydown.escape.window="promptMultiDeleteModal = false">

    <!-- Modal -->
    <div x-trap.noautofocus.noreturn.noscroll="promptMultiDeleteModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="relative size-full max-w-xl">
            <div class="relative bg-white rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-sm sm:text-base font-semibold text-blue-1100">
                        @if ($this->count > 1)
                            {{ $defaultArchive ? 'Archive Records' : 'Permanently Delete Records' }}
                        @else
                            {{ $defaultArchive ? 'Archive this Record' : 'Permanently Delete this Record' }}
                        @endif
                    </h1>

                    <div class="flex items-center">
                        {{-- Close Button --}}
                        <button type="button" @click="promptMultiDeleteModal = false;"
                            class="outline-none text-blue-400 focus:bg-blue-200 focus:text-blue-900 hover:bg-blue-200 hover:text-blue-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                    class="flex flex-col items-center justify-center w-full pt-5 pb-6 px-3 md:px-12 text-blue-1100 text-xs">
                    @if ($this->count > 1)
                        @if ($defaultArchive)
                            <p class="font-medium text-sm mb-2">
                                Are you sure about archiving {{ $this->count }} records?
                            </p>
                            <p class="text-gray-500 text-xs font-normal mb-4">
                                You could access them again in the Archives page.
                            </p>
                        @else
                            <p class="font-medium text-sm mb-2">
                                Are you sure about permanently deleting {{ $this->count }} records?
                            </p>
                            <p class="text-gray-500 text-xs font-normal mb-4">
                                This action is irreversible and no way of undoing it.
                            </p>
                        @endif
                    @else
                        @if ($defaultArchive)
                            <p class="font-medium text-sm mb-2">
                                Are you sure about archiving this record?
                            </p>
                            <p class="text-gray-500 text-xs font-normal mb-4">
                                You could access it again in the Archives page.
                            </p>
                        @else
                            <p class="font-medium text-sm mb-2">
                                Are you sure about permanently deleting this record?
                            </p>
                            <p class="text-gray-500 text-xs font-normal mb-4">
                                This action is irreversible and no way of undoing it.
                            </p>
                        @endif
                    @endif

                    <div class="flex items-center justify-center w-full gap-2">
                        {{-- Cancel Button --}}
                        <button type="button" @click="promptMultiDeleteModal = false;"
                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-blue-700 hover:border-transparent hover:bg-blue-800 active:bg-blue-900 text-blue-700 hover:text-blue-50 active:text-blue-50">
                            CANCEL
                        </button>

                        {{-- Confirm Button --}}
                        <button type="button" wire:loading.attr="disabled" wire:click="$parent.removeBeneficiaries()"
                            @click="promptMultiDeleteModal = false"
                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm border border-red-700 bg-red-700 hover:bg-red-800 active:bg-red-900 text-red-50">
                            CONFIRM
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
