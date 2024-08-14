<div id="assign-batches-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 border-b rounded-t ">
                <h3 class="text-lg font-semibold text-gray-900 ">
                    Create New Project Implementation
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center focus:outline-none"
                    data-modal-toggle="assign-batches-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form wire:submit="saveBatches" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-5">
                    <div class="col-span-5 sm:col-span-2">
                        <label for="project_num" class="block mb-2 text-sm font-medium text-gray-900 ">Project
                            Number</label>
                        <input type="text" id="project_num" wire:model="project_num"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type project number" required>
                    </div>
                    <div class="col-span-5 sm:col-span-3">
                        <label for="project_title" class="block mb-2 text-sm font-medium text-gray-900 ">Project
                            Title</label>
                        <input type="text" id="project_title" wire:model="project_title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type project title" required>
                    </div>
                    <div class="col-span-5 sm:col-span-2">
                        <label for="budget_amount" class="block mb-2 text-sm font-medium text-gray-900 ">Budget</label>
                        <div class="relative">
                            <div
                                class="absolute rounded-l-lg bg-indigo-200 text-gray-900 inset-y-0 flex items-center justify-center text-center ps-6 pointer-events-none">
                                <p class="flex text-center w-full relative items-center justify-center font-bold">₱
                                </p>
                            </div>
                            <input type="number" min="0" id="budget_amount" wire:model="budget_amount"
                                class="ps-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full pe-2.5 py-2.5"
                                placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <label for="total_slots" class="block mb-2 text-sm font-medium text-gray-900 ">Total
                            Slots</label>
                        <input type="number" min="0" id="total_slots" wire:model="total_slots"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="0" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="days_of_work" class="block mb-2 text-sm font-medium text-gray-900 ">Days of
                            Work</label>
                        <input type="number" min="0" max="15" id="days_of_work" wire:model="days_of_work"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="0" required>
                    </div>
                    <div class="col-span-2">
                        <label for="district" class="block mb-2 text-sm font-medium text-gray-900 ">District</label>
                        <input type="text" id="district" wire:model="district"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type district" required>
                    </div>
                    <div class="col-span-3">
                        <label for="province" class="block mb-2 text-sm font-medium text-gray-900 ">Province</label>
                        <input type="text" id="province" wire:model="province"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type province" required>
                    </div>
                    <div class="col-span-2">
                        <label for="city_municipality"
                            class="block mb-2 text-sm font-medium text-gray-900 ">City/Municipality</label>
                        <input type="text" id="city_municipality" wire:model="city_municipality"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type city/municipality" required>
                    </div>
                    <div class="col-span-3">
                        <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 ">Purpose of
                            Project</label>
                        <select id="purpose" wire:model="purpose"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5      ">
                            <option selected value="DUE TO DISADVANTAGE/DISPLACEMENT">DUE TO
                                DISADVANTAGE/DISPLACEMENT</option>
                            <option value="DUE TO DISADVANTAGE/DISPLACEMENT">DUE TO DISADVANTAGE/DISPLACEMENT
                            </option>
                            <option value="DUE TO DISADVANTAGE/DISPLACEMENT">DUE TO DISADVANTAGE/DISPLACEMENT
                            </option>
                            <option value="DUE TO DISADVANTAGE/DISPLACEMENT">DUE TO DISADVANTAGE/DISPLACEMENT
                            </option>
                            <option value="DUE TO DISADVANTAGE/DISPLACEMENT">DUE TO DISADVANTAGE/DISPLACEMENT
                            </option>
                        </select>
                    </div>
                </div>
                <div class="p-0 w-full flex relative justify-end">
                    <button type="submit"
                        class="text-white flex items-center bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                        <svg class="me-1 -ms-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        DONE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
