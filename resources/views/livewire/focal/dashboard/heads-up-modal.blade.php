<div x-cloak x-data="{ showModal: false }" x-init="setTimeout(() => {
    showModal = true;
}, 500);" @keydown.escape.window="showModal = false">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50" x-show="showModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-trap.noscroll="showModal"
        class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none h-[calc(100%-1rem)] max-h-full"
        x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">

        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-md shadow">
                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <h2 class="text-sm sm:text-base font-semibold text-indigo-1100">Heads Up!</h2>

                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading>
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
                        <button @click="showModal = false"
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
                <!-- Modal Body -->
                <div class="pt-5 pb-6 px-6 sm:px-16 text-indigo-1100">

                    {{-- Welcome message | Headers --}}
                    <p class="text-lg sm:text-3xl font-bold flex items-center justify-start">Welcome
                        back
                        <span class="ms-3">
                            <img class="size-5 sm:size-10" src="{{ asset('assets/w_c.png') }}" alt="Confetti">
                        </span>
                    </p>

                    {{-- Counters --}}
                    <p class="text-sm mb-6 mt-3">
                        Here are some <strong>updates</strong> that happened while you
                        were
                        away:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 items-center justify-center my-3 pb-6 gap-2 sm:gap-4">
                        <p
                            class="flex h-full pb-4 relative w-full col-span-full text-indigo-1100 text-lg font-bold mt-4">
                            <span class="text-indigo-700 me-2">#</span>Batch Updates
                        </p>
                        {{-- Batches Counts --}}
                        <div class="flex flex-col col-span-full justify-center items-center">

                            <div
                                class="relative flex flex-col items-center justify-center gap-2 sm:gap-4 h-[28vh] w-full">

                                {{-- New Approved Batches --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-green-1100 hover:bg-green-50 focus:bg-green-50 border-green-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold size-8 text-green-50 bg-green-700 duration-200 ease-in-out rounded">
                                        {{ $this->activities['approved'] }}
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">New Approved Batches</p>
                                    </span>
                                </div>

                                {{-- New Submitted Lists --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-indigo-1100 hover:bg-indigo-50 focus:bg-indigo-50 border-indigo-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold size-8 text-indigo-50 bg-indigo-700 duration-200 ease-in-out rounded">
                                        {{ $this->activities['submitted'] }}
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">New List of Beneficiaries (Submissions)</p>
                                    </span>
                                </div>

                                {{-- New Opened Batches --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-blue-1100 hover:bg-blue-50 focus:bg-blue-50 border-blue-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold size-8 text-blue-50 bg-blue-700 duration-200 ease-in-out rounded">
                                        {{ $this->activities['encoding'] }}
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">New Opened Batches</p>
                                    </span>
                                </div>

                                {{-- New Revalidating Lists --}}
                                <div
                                    class="flex-1 m-0 flex w-full items-center justify-start px-2 py-2 border outline-none bg-white text-red-950 hover:bg-red-50 focus:bg-red-50 border-red-300 duration-200 ease-in-out rounded">
                                    <span
                                        class="text-sm grid place-items-center font-bold size-8 text-red-50 bg-red-700 duration-200 ease-in-out rounded">
                                        {{ $this->activities['revalidate'] }}
                                    </span>
                                    <span class="flex flex-col items-start ms-2 duration-200 ease-in-out">
                                        <p class="text-sm font-semibold">Revalidating Submissions</p>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
