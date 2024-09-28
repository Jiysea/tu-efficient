<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="deleteProjectModal">
    </div>

    <!-- Modal -->
    <div x-trap.inert="deleteProjectModal" x-show="deleteProjectModal" x-trap.noscroll="deleteProjectModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

        {{-- The Modal --}}
        <div class="relative w-full max-w-xl max-h-full">
            <div class="relative bg-white rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center py-2 px-4 rounded-t-md">
                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">Delete the Project
                    </h1>
                </div>

                <hr class="">

                {{-- Modal body --}}
                <div class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                    <p class="font-medium text-sm mb-1">
                        Are you sure about deleting this project?
                    </p>
                    <p class="text-gray-500 text-sm mb-4">
                        (This is action is irreversible)
                    </p>
                    <div class="flex items-center justify-center w-full gap-4">
                        <button type="button"
                            class="duration-200 ease-in-out flex flex-1 items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50"
                            @click="deleteProjectModal = false;">CANCEL</button>

                        <button type="button" @click="deleteProjectModal = false; viewProjectModal = false;"
                            class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50"
                            wire:click.prevent="$parent.deleteProject()">CONFIRM</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
