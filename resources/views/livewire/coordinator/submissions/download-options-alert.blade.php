<div wire:ignore.self id="download-options-alert" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
    class="hidden backdrop-brightness-50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div x-data="{
        init() {
            window.addEventListener('download-options-confirmed', () => {
                const modal = FlowbiteInstances.getInstance('Modal', 'download-options-alert');
                trapDownload = false;
                modal.hide();
            });
        },
    }" class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div x-trap="trapDownload" class="relative bg-white rounded-md shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                <h1 class="text-lg font-semibold text-blue-1100 ">
                    Download Annex D Format
                </h1>

                <div class="flex items-center justify-center">
                    {{-- Loading State --}}
                    <div class="flex items-center justify-start me-4 z-50 text-blue-900" wire:loading
                        wire:target="export">
                        <svg class="size-7 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <button type="button" @click="trapDownload=false"
                        class="outline-none text-blue-400 hover:bg-blue-200 hover:text-blue-900 rounded size-8 ms-auto inline-flex justify-center items-center duration-200 ease-in-out"
                        data-modal-toggle="download-options-alert">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
            </div>

            <hr class="mx-4">

            <!-- Modal Body -->
            <div class="flex flex-col items-center p-4 md:p-5">
                <div class="grid grid-cols-1 my-4 text-xs w-full place-items-center">
                    <div class="relative mb-4 px-4 pb-1">
                        <label for="slots_allocated" class="block mb-1 font-medium text-blue-1100">How many slots you
                            want to generate?</label>
                        <input type="number" inputmode="numeric" id="slots_allocated" wire:model.live="slots_allocated"
                            autocomplete="off"
                            class="text-xs border w-full p-2.5 rounded {{ $errors->has('slots_allocated') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-blue-50 border-blue-300 text-blue-1100 focus:ring-blue-600 focus:border-blue-600' }}"
                            placeholder="0">
                        @error('slots_allocated')
                            <p class="ms-3 text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-center space-x-4 mb-4">
                    <button type="button" @click="trapDownload=false"
                        class="text-base font-bold px-2 py-1 outline-none text-blue-900 hover:text-blue-50 border border-blue-900 hover:border-transparent hover:bg-blue-800 active:bg-blue-900 rounded text-center duration-200 ease-in-out"
                        data-modal-toggle="download-options-alert">
                        BACK
                    </button>

                    <button type="button" wire:click="confirm"
                        class="text-base font-bold px-2 py-1 outline-none text-blue-50 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 rounded text-center duration-200 ease-in-out">
                        CONFIRM
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
