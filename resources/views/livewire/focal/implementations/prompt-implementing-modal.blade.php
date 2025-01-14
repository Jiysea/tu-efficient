<div x-cloak class="fixed inset-0 bg-black overflow-y-auto bg-opacity-50 backdrop-blur-sm z-50"
    x-show="promptImplementingModal" @keydown.escape.window="promptImplementingModal = false">

    <!-- Modal -->
    <div x-trap.noscroll="promptImplementingModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="relative size-full max-w-xl">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">
                        {{ $this->implementation?->status === 'implementing' ? 'Marking for Implementation' : 'Marking As Pending' }}
                    </h1>

                    <div class="flex items-center justify-center gap-3">

                        {{-- Loading State --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-indigo-900 animate-spin" wire:loading
                            wire:target="markForImplementation, markAsPending" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        {{-- Close Button --}}
                        <button type="button" @click="$wire.resetModal(); promptImplementingModal = false;"
                            class="outline-none text-indigo-400 focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
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
                <form
                    @if ($this->implementation?->status === 'pending') wire:submit.prevent="markForImplementation"
                    @elseif($this->implementation?->status === 'implementing')
                        wire:submit.prevent="markAsPending" @endif
                    class="flex flex-col items-center justify-center pt-5 pb-10 px-3 md:px-12 text-indigo-1100 text-xs">

                    @if ($this->implementation?->status === 'implementing')
                        <p class="mb-2 text-sm font-medium text-indigo-1100">
                            Are you sure about marking this for implementation?
                        </p>
                        <p class="mb-4 text-xs font-medium text-gray-500">
                            You won't be able to modify this project until it is
                            <span class="rounded-full bg-amber-200 text-amber-800 px-2 py-1 font-semibold">
                                PENDING
                            </span> again.
                        </p>
                    @elseif($this->implementation?->status === 'pending')
                        <p class="mb-2 text-sm font-medium text-indigo-1100">
                            Are you sure about marking this as pending?
                        </p>
                        <p class="mb-4 text-xs font-medium text-gray-500">
                            All beneficiaries that were checked for COS and Payroll will be saved.
                        </p>
                    @endif

                    <div class="relative flex items-center justify-center w-full gap-2">
                        <div class="relative">
                            <input autofocus type="password" id="password_implementing" wire:model.blur="password"
                                class="flex flex-1 {{ $errors->has('password') ? 'caret-red-900 border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'caret-indigo-900 border-indigo-300 focus:border-indigo-500 bg-indigo-50 focus:ring-0' }} rounded outline-none border py-2.5 text-sm select-text duration-200 ease-in-out"
                                placeholder="Enter your password">
                            @error('password')
                                <p class="absolute top-full left-0 mt-1 text-xs text-red-700">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="flex items-center justify-center disabled:bg-indigo-300 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50 py-2.5 px-2 rounded text-sm font-bold duration-200 ease-in-out">
                            CONFIRM
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
