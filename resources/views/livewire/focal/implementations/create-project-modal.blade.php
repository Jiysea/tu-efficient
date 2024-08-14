<div wire:ignore.self id="create-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
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
                    data-modal-toggle="create-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form wire:submit.prevent="saveProject" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-5">
                    <div class="relative col-span-5 sm:col-span-2">
                        <label for="project_num" class="block mb-2 text-sm font-medium text-gray-900 ">Project
                            Number</label>
                        <input type="text" id="project_num" wire:model.live="project_num"
                            class="{{ $errors->has('project_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type project number">
                        @error('project_num')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a project number.</p>
                        @enderror
                    </div>
                    <div class="relative col-span-5 sm:col-span-3">
                        <label for="project_title" class="block mb-2 text-sm font-medium text-gray-900 ">Project
                            Title</label>
                        <input type="text" id="project_title" wire:model.live="project_title"
                            class="{{ $errors->has('project_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type project title">
                        @error('project_title')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a project title.</p>
                        @enderror
                    </div>
                    <div class="relative col-span-5 sm:col-span-2">
                        <label for="budget_amount" class="block mb-2 text-sm font-medium text-gray-900 ">Budget</label>
                        <div class="relative">
                            <div
                                class="{{ $errors->has('budget_amount') ? ' bg-red-200 text-red-900 border border-red-500' : '' }} absolute inset-y-0 px-3 rounded-l-lg border border-indigo-300 bg-indigo-100 text-indigo-1100 flex items-center justify-center text-center pointer-events-none">
                                <p class="flex text-center w-full relative items-center justify-center font-bold">₱
                                </p>
                            </div>
                            <input type="number" min="0" id="budget_amount" wire:model.live="budget_amount"
                                class="{{ $errors->has('budget_amount') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} ps-11 bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full pe-2.5 py-2.5"
                                placeholder="0.00">
                        </div>
                        @error('budget_amount')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a valid budget
                                amount.
                            </p>
                        @enderror
                    </div>
                    <div class="relative col-span-3 sm:col-span-2">
                        <label for="total_slots" class="block mb-2 text-sm font-medium text-gray-900 ">Total
                            Slots</label>
                        <input type="number" min="0" id="total_slots" wire:model.live="total_slots"
                            class="{{ $errors->has('total_slots') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="0">
                        @error('total_slots')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a valid total slots.
                            </p>
                        @enderror
                    </div>
                    <div class="relative col-span-2 sm:col-span-1">
                        <label for="days_of_work" class="block mb-2 text-sm font-medium text-gray-900 ">Days of
                            Work</label>
                        <input type="number" min="0" max="15" id="days_of_work"
                            wire:model.live="days_of_work"
                            class="{{ $errors->has('days_of_work') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="0">
                        @error('days_of_work')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Invalid days of work.
                            </p>
                        @enderror
                    </div>
                    <div class="relative col-span-2">
                        <label for="district" class="block mb-2 text-sm font-medium text-gray-900 ">District</label>
                        <input type="text" id="district" wire:model.live="district"
                            class="{{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type district">
                        @error('district')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a district.</p>
                        @enderror
                    </div>
                    <div class="relative col-span-3">
                        <label for="province" class="block mb-2 text-sm font-medium text-gray-900 ">Province</label>
                        <input type="text" id="province" wire:model.live="province"
                            class="{{ $errors->has('province') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type province">
                        @error('province')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a province.</p>
                        @enderror
                    </div>
                    <div class="relative col-span-2">
                        <label for="city_municipality"
                            class="block mb-2 text-sm font-medium text-gray-900 ">City/Municipality</label>
                        <input type="text" id="city_municipality" wire:model.live="city_municipality"
                            class="{{ $errors->has('city_municipality') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type city/municipality">
                        @error('city_municipality')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please type a city or
                                municipality.</p>
                        @enderror
                    </div>
                    <div class="relative col-span-3">
                        <label for="purpose" class="block mb-2 text-sm font-medium text-gray-900 ">Purpose of
                            Project</label>
                        <select id="purpose" wire:model.live="purpose"
                            class="{{ $errors->has('purpose') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5      ">
                            <option value="">Select a purpose...</option>
                            <option>DUE TO DISADVANTAGE/DISPLACEMENT</option>
                        </select>
                        @error('purpose')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">Please select a purpose.</p>
                        @enderror
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
                        CREATE PROJECT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
