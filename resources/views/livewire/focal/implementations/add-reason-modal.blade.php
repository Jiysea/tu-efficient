<div x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="addReasonModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="addReasonModal" x-trap.noscroll="addReasonModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full"
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        {{-- The Modal --}}
        <div class="relative w-full max-w-3xl max-h-full">
            <div class="relative bg-white rounded-md shadow">

                <form wire:submit.prevent="saveProject">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                        <span class="flex items-center justify-center">
                            <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">Add Reason
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
                            <button type="button" @click="$wire.resetEverything(); addReasonModal = false;"
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

                    {{-- Modal Body --}}
                    <div class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                            {{-- Case Proof --}}
                            <div class="relative col-span-full row-span-full sm:col-span-1 mb-4 pb-1">
                                <div class="flex flex-col items-start">
                                    <div class="flex items-center">
                                        <p class="inline mb-1 font-medium text-indigo-1100">Case Proof</p>
                                    </div>

                                    {{-- Image Area --}}
                                    <label for="reason_image_file_path"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded cursor-pointer bg-indigo-50">
                                        {{-- Loading State for Changes --}}
                                        <div class="absolute items-center justify-center w-full h-32 z-50 text-indigo-900"
                                            wire:loading.flex wire:target="reason_image_file_path">
                                            <div class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
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

                                        {{-- Image Preview --}}
                                        <div class="relative flex flex-col items-center justify-center">
                                            {{-- Preview --}}
                                            @if ($reason_image_file_path && !$errors->has('reason_image_file_path'))
                                                <img class="size-28"
                                                    src="{{ $reason_image_file_path->temporaryUrl() }}">

                                                {{-- Default --}}
                                            @else
                                                <svg class="size-8 mb-4 text-gray-500" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                </svg>
                                                <p class="mb-2 text-xs text-gray-500"><span class="font-semibold">Click
                                                        to
                                                        upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500 ">PNG or JPG (MAX. 5MB)</p>
                                            @endif
                                        </div>

                                        {{-- The Image itself --}}
                                        <input id="reason_image_file_path" wire:model="reason_image_file_path"
                                            type="file" accept=".png,.jpg,.jpeg" class="hidden" />
                                    </label>
                                </div>
                                @error('reason_image_file_path')
                                    <p
                                        class="text-center whitespace-nowrap w-full text-red-500 absolute -bottom-4 z-10 text-xs">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Image Description --}}
                            <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                                <label for="image_description"
                                    class="block mb-1 font-medium text-indigo-1100 ">Description
                                    <span class="text-red-700 font-normal text-xs">*</span></label>
                                <textarea type="text" id="image_description" autocomplete="off" wire:model.blur="image_description" maxlength="255"
                                    rows="4"
                                    class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('image_description') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                    placeholder="What is the reason for this special case?"></textarea>
                                @error('image_description')
                                    <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
