<div wire:ignore.self id="create-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded shadow ">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 border-b rounded-t ">
                <h1 class="text-lg font-semibold text-indigo-1100 ">
                    Create New Project Implementation
                </h1>
                <button type="button"
                    class="text-indigo-400 bg-transparent hover:bg-indigo-200 hover:text-indigo-900 rounded  w-8 h-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-300 ease-in-out"
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
                <div class="grid gap-4 mb-4 grid-cols-5 text-xs">
                    <div class="relative col-span-5 sm:col-span-2 mb-4">
                        <label for="project_num" class="block mb-1  font-medium text-gray-900 ">Project
                            Number</label>
                        <input type="text" id="project_num" wire:model.blur="project_num"
                            class="text-xs {{ $errors->has('project_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5"
                            placeholder="Type project number">
                        @error('project_num')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative col-span-5 sm:col-span-3 mb-4">
                        <label for="project_title" class="block mb-1  font-medium text-gray-900 ">Project
                            Title</label>
                        <input type="text" id="project_title" wire:model.blur="project_title"
                            class="text-xs {{ $errors->has('project_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type project title">
                        @error('project_title')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative col-span-5 sm:col-span-2 mb-4">
                        <label for="budget_amount" class="block mb-1  font-medium text-gray-900 ">Budget</label>
                        <div x-data="{
                            budgetToFloat: null,
                            budgetToInt: null,
                            unmaskedBudget: null,
                        
                            demaskValue(value) {
                                if (value) {
                                    // Remove commas
                                    this.budgetToFloat = value.replaceAll(',', '');
                        
                                    // Check if there's a decimal point
                                    if (this.budgetToFloat.includes('.')) {
                                        // Convert to float, format to 2 decimal places, then remove the decimal
                                        this.budgetToInt = parseInt((parseFloat(this.budgetToFloat).toFixed(2)).replace('.', ''));
                                    } else {
                                        // Append '00' if there's no decimal point
                                        this.budgetToInt = parseInt(this.budgetToFloat + '00');
                                    }
                                } else {
                                    this.budgetToInt = null;
                                }
                                this.unmaskedBudget = this.budgetToInt;
                            }
                        }" x-effect="console.log(unmaskedBudget)" class="relative">
                            <div
                                class="text-sm {{ $errors->has('budget_amount') ? ' bg-red-400 text-red-900 border border-red-500' : '' }} absolute inset-y-0 px-3 rounded-l bg-indigo-600 text-indigo-50 flex items-center justify-center text-center pointer-events-none">
                                <p class="flex text-center w-full relative items-center justify-center font-semibold">₱
                                </p>
                            </div>
                            <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric" min="0"
                                id="budget_amount" @input="demaskValue($el.value)"
                                @blur="$wire.set('budget_amount', unmaskedBudget)"
                                class="text-xs {{ $errors->has('budget_amount') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} ps-11 bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full pe-2.5 py-2.5"
                                placeholder="Type total budget">
                        </div>
                        @error('budget_amount')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="relative col-span-3 sm:col-span-2 mb-4">

                        <div class="flex items-center">
                            <label for="total_slots"
                                class="block mb-1 whitespace-nowrap font-medium text-gray-900 ">Total
                                Slots</label>

                            <div tabindex="-1" class="w-full mb-1 flex items-center justify-end">
                                <label class="inline-flex items-center cursor-pointer">
                                    <span
                                        class="me-2 text-xs {{ $isAutoComputeEnabled ? 'text-indigo-900' : 'text-gray-500' }} duration-150 ease-in-out">Auto
                                        compute by minimum wage</span>
                                    <input type="checkbox" id="auto-compute" wire:click="toggleTry"
                                        wire:model.blur="isAutoComputeEnabled" class="sr-only peer">
                                    <div
                                        class="relative w-9 h-4 bg-gray-500 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-[calc(100%+8px)] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:size-3 after:transition-all duration-300 ease-in-out peer-checked:bg-indigo-900">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <input type="number" min="0" id="total_slots" wire:model.blur="total_slots"
                            @if ($isAutoComputeEnabled) disabled @endif
                            class="text-xs duration-300 ease-in-out {{ $isAutoComputeEnabled ? 'bg-gray-200 border-gray-300 text-indigo-1100 focus:ring-gray-800 focus:border-gray-800' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} {{ $errors->has('total_slots') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} rounded border block w-full p-2.5"
                            placeholder="Type total slots">
                        @error('total_slots')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="relative col-span-2 sm:col-span-1 mb-4">
                        <label for="days_of_work" class="block mb-1  font-medium text-gray-900 ">Days of
                            Work</label>
                        <input type="number" min="0" max="15" id="days_of_work"
                            wire:model.blur="days_of_work"
                            class="text-xs {{ $errors->has('days_of_work') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type days of work">
                        @error('days_of_work')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="relative col-span-2 mb-4">
                        <label for="district" class="block mb-1  font-medium text-gray-900 ">District</label>
                        <input type="text" id="district" wire:model.blur="district"
                            class="text-xs {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type district">
                        @error('district')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative col-span-3 mb-4">
                        <label for="province" class="block mb-1  font-medium text-gray-900 ">Province</label>
                        <input type="text" id="province" wire:model.blur="province"
                            class="text-xs {{ $errors->has('province') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type province">
                        @error('province')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative col-span-2 mb-4">
                        <label for="city_municipality"
                            class="block mb-1  font-medium text-gray-900 ">City/Municipality</label>
                        <input type="text" id="city_municipality" wire:model.blur="city_municipality"
                            class="text-xs {{ $errors->has('city_municipality') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5      "
                            placeholder="Type city/municipality">
                        @error('city_municipality')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative col-span-3 mb-4">
                        <label for="purpose" class="block mb-1  font-medium text-gray-900 ">Purpose of
                            Project</label>
                        <select id="purpose" wire:model.blur="purpose"
                            class="text-xs {{ $errors->has('purpose') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} bg-gray-50 border border-indigo-300 text-gray-900  rounded focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5      ">
                            <option value="">Select a purpose...</option>
                            <option>DUE TO DISADVANTAGE/DISPLACEMENT</option>
                        </select>
                        @error('purpose')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="w-full flex relative justify-end">
                    <button type="submit"
                        class="text-white flex items-center bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-bold rounded px-2 text-center">
                        <svg class="size-10" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="pe-2">CREATE PROJECT</p>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
