<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="batchDeleteModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="batchDeleteModal" x-trap.noscroll="batchDeleteModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full"
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        {{-- The Modal --}}
        <div class="relative w-full max-w-xl max-h-full">
            <div class="relative bg-white rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center py-2 px-4 rounded-t-md">
                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">Delete the Batch
                    </h1>
                </div>

                <hr class="">

                {{-- Modal body --}}
                <div class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                    <p class="font-medium text-sm mb-1">
                        Are you sure about deleting this batch?
                    </p>
                    <p class="text-gray-500 text-sm mb-4">
                        (This is action is irreversible)
                    </p>
                    <div class="flex items-center justify-center w-full gap-4">
                        <button type="button"
                            class="duration-200 ease-in-out flex flex-1 items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50"
                            @click="batchDeleteModal = false;">CANCEL</button>

                        <button type="button" @click="batchDeleteModal = false; openProjectModal = false;"
                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50"
                            wire:click.prevent="$parent.deleteBatch()">CONFIRM</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
